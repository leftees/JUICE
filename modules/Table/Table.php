<?php
	/***
		CREATE TABLE IF NOT EXISTS `tables` (
			`pk_tables` varchar(100) NOT NULL,
			`fields` varchar(500) NOT NULL,
			`main_field` varchar(100) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	***/
	class Table extends Module {
		public $name;
		public $data;
		public $dataRaw;
		public $dataRawDescribe;
		public $dataRawPartial;
		public $dataRawFull;
		public $dipendence;
		public function __construct() {
			$this->name = get_class($this);
			$this->requires["Form"] = new Form();
			$this->data 						= [];
			$this->data["header"] 	= [];
			$this->data["body"] 		= [];
			$this->dipendence 			= [
				"modules/Table/Table.min.js",
				"modules/Table/classes/Form.min.js",
				"modules/Table/Table.min.css"
			];
			$this->dataRaw 					= [];
			$this->dataRawFull 			= [];
			$this->dataRawPartial		= [];
			$this->dataRawDescribe	= [];
			$this->pageSize					= 50;
			$this->nPage						= 1;
			$this->currentPage			= 0;
			$this->tablesCached			= [];
		}
		public function standardTable( $_t, $_filter = "", $_page = 0 ) {
			$this->data = [];
			$this->data["header"] = [];
			$this->data["header"][0] = [];
			$this->data["body"] = [];
			$this->data["filter"] = $this->getFilter($_t);
			$filter = "*";
			$this->dataRawDescribe = c_query("DESCRIBE ".$_t,"Table,standardTable,header");

			if($this->data["filter"] == NULL) {//causa problemi se c'è una tabella senza campi validi
				$this->data["filter"] = [];
				foreach ($this->dataRawDescribe as $i) {
					//if (strpos($i["Field"],'fk_') === false)
					if (strpos($i["Field"],'pk_') === false)
					if (strpos($i["Field"],'flag_') === false)
					array_push($this->data["filter"], $i["Field"]);
				}
			}
			$this->data["header"][0] = $this->data["filter"];
			$filter = implode(",", $this->data["filter"]);
			$formAnagrafica = new Form();//$this->requires["Form"]
			$popUp 					= [];
			$dbName = $GLOBALS["config"]["connection"]["database"];
			$tempQuery =
				"SELECT `COLUMN_NAME` as res
				FROM `information_schema`.`COLUMNS`
				WHERE (`TABLE_SCHEMA` = '$dbName')
					AND (`TABLE_NAME` = '$_t')
					AND (`COLUMN_KEY` = 'PRI')";
			$tempResult = c_query($tempQuery,"temp");
			$tempResult = array_column ( $tempResult , "res");
			$tempResult = implode($tempResult," DESC, ");
			$dataRow 				= c_query(
			"SELECT * FROM $_t $_filter ORDER BY flag_active DESC, $tempResult
			DESC LIMIT ".$this->pageSize." OFFSET ".($this->pageSize * $_page),
				"Table,standardTable,body"
			);
			$nPage 				= c_query("SELECT COUNT(*) as c FROM ".$_t.$_filter." ORDER BY flag_active DESC","Table,standardTable,count");
			$nPage = intval($nPage[0]['c']);
			$nPage = $nPage / $this->pageSize;
			$nPage = ceil($nPage);
			$this->nPage = $nPage;
			$this->currentPage = $_page;
			foreach ($dataRow as $j) {
				$formTemp = [];
				$formAnagrafica->init();
				$tempArray_fk = [];
				foreach ($this->dataRawDescribe as $i) {
					if (strpos($i["Field"],'pk_') === false && strpos($i["Field"],'flag_') === false) {
						if(strpos($i["Field"],'fk_') !== false) {
							$t_fk = $this->getSubTable(str_replace("fk_","",$i["Field"]),$j[$i["Field"]]);
							$tempArray_fk[$i["Field"]] = $t_fk;
							$formAnagrafica->pushLine(array( $i["Field"], $t_fk, ($i["Null"]=="NO"), ""));
						} else if(strpos($i["Field"],'password') !== false) {
							$formAnagrafica->pushLine(array( $i["Field"], "password", ($i["Null"]=="NO"), $j[$i["Field"]]));
						} else if( strpos($i["Type"],'tinyint') !== false || strpos($i["Type"],'bit') !== false ) {
							$formAnagrafica->pushLine(array( $i["Field"], array(array("SI",1),array("NO",0)), ($i["Null"]=="NO"), $j[$i["Field"]]));
						} else {
							$formAnagrafica->pushLine(array( $i["Field"], $i["Type"], ($i["Null"]=="NO"), $j[$i["Field"]]));
						}
					} else {
						$formAnagrafica->pushLine(array( $i["Field"], "hidden", ($i["Null"]=="NO"), $j[$i["Field"]]));
					}
				}
				$formTemp = $formAnagrafica->data;
				array_push( $popUp, $formTemp );
				//intervenire qui
				$tempArray = [];
				// var_dump($tempArray_fk[0][1]);
				foreach ($this->data["filter"] as $i) {//load all filter value
					if(strpos($i,'fk_') !== false) {
						array_push($tempArray, $tempArray_fk[$i][0][0]);
					} else
						array_push($tempArray, $j[$i]);
				}
				if($j["flag_active"])
					array_push( $this->dataRawPartial, array("javascript:void(0)", $tempArray, "row-active") );
				else
					array_push( $this->dataRawPartial, array("javascript:void(0)", $tempArray, "row-disactive") );
				$tempArray = [];
				foreach ($this->data["filter"] as $i) {
					$tempArray[$i] = $j[$i];
				}
				$this->pushLine($tempArray);
			}
			$this->dataRawFull = 	$popUp;
			return $this->data;
		}
		public function init( $_h = [], $_b = [] ) {
			$this->data = [];
			$this->data["header"] = $_h;
			$this->data["body"] = [];
			return $this->push($_b);
		}
		public function push( $_b ) {
			foreach ($_b as $i)
				$this->pushLine( $i );
			return $this->data;
		}
		public function pushLine( $_b, $_active=false ) {
			$temp = array($_b,$_active);
			array_push( $this->data["body"], $temp );
		}
		public function draw( $_type = "JS" ) {
			$temp = "";
			if($_type=="PHP") {
				$nCol = count($this->data["header"][0]);
				if($nCol < 1) $nCol = 1;
				$h = $this->subdivide($this->data["header"],$nCol);
				$b = $this->subdivide($this->data["body"],$nCol);
				$temp =
					'<div class="col-xs-12 table">'.
					'<div class="row">'.
					'<div class="col-xs-12 table-header">'.
					$h.
					'</div>'.
					'</div>'.
					'<div class="row">'.
					'<div class="col-xs-12 table-body">'.
					$b.
					'</div>'.
					'</div>'.
					'</div>';
				}
			if($_type=="JS") {
				if(count($this->data["filter"])>0)
					$titoli = $this->data["filter"];
				else
					$titoli = array("Table");
				array_push($this->dipendence, array("table.currentPage",	json_encode($this->currentPage)));
				array_push($this->dipendence, array("table.nPage",				json_encode($this->nPage)));
				array_push($this->dipendence, array("table.titoli",				json_encode($titoli)));
				array_push($this->dipendence, array("table.data",					json_encode(($this->dataRawPartial))));
				array_push($this->dipendence, array("table.bigData",			json_encode(($this->dataRawFull))));
				jBlock();
				?>
				<div class="row">
					<div class="col-xs-12" id="table">
					</div>
				</div>
				<?php
				$temp = jBlockEnd();
			}
			return $temp;
		}
		public function subdivide( $_a, $_nCol ) {
			$temp ="";
			$sizeCol = 100 / $_nCol;
			$cont = 0;
			foreach ($_a as $i) {
				$temp = $temp.'<div class=" row table-row-'.(++$cont%2).' ">';
				foreach ($i[0] as $j) {
					if($i[1])
						$temp = $temp.'<div class="table-col" style="width:'.$sizeCol.'%">'.$j.'</div>';
					else
						$temp = $temp.'<div class="table-col" style="text-decoration:line-through; width:'.$sizeCol.'%">'.$j.'</div>';
				}
				$temp = $temp.'</div>';
			}
			return $temp;
		}
		public function getFilter( $_t ) {
			$rows = c_query("SELECT fields FROM tables WHERE pk_tables='$_t'","Table,getFilter,filter");
			if(count($rows)!=1) return NULL;
			if($rows[0]["fields"]=="") return NULL;
			return explode("|",$rows[0]["fields"]);
		}
		private function getSubTable( $_t, $_select ) {
			$temp		= [];
			if(isset($this->tablesCached[$_t]))
				$table	= $this->tablesCached[$_t];
			else {
				$table	= c_query("SELECT * FROM ".$_t,"Table,getSubTable,table");
				$this->tablesCached[$_t]	= $table;
			}
			$rows		= c_query("SELECT main_field FROM tables WHERE pk_tables='$_t'","Table,getSubTable,label");
			if(count($rows)!=1) return $temp;
			if($rows[0]["main_field"]=="") return $temp;
			$success = false;
			foreach ($table as $i) {
				if($_select == $i["pk_".$_t]) {
					$success = true;
					$temp = array_merge(array(array($i[$rows[0]["main_field"]],$i["pk_".$_t])),$temp);
				} else {
					$temp = array_merge($temp,array(array($i[$rows[0]["main_field"]],$i["pk_".$_t])));
				}
			}
			if(!$success)
				$temp = array_merge(array(array("",$_select)),$temp);
			return $temp;
		}
	}
?>
