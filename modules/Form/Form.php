<?php
	class Form extends Module {
		public $name;
		public $data;
		public $form;
		public $dipendence;
		public $message;
		public function __construct( $_f = "" ) {
			$this->name = get_class($this);
			$this->data = [];
			$this->form = $_f;
			$this->dipendence = array("modules/Form/Form.min.css");
			$this->message = "I campi contrassegnati con * sono obbligatori.<br>";
			$this->requires = [];
		}
		public function setForm( $_f ) {
			$this->form = $_f;
		}
		public function init( $_a = [], $_f = "" ) {
			$this->data = [];
			$this->setForm( $_f );
			return $this->push($_a);
		}
		public function push( $_a ) {
			foreach ($_a as $i)
				$this->pushLine( $i );
			return $this->data;
		}
		public function pushLine( $_a ) {
			array_push( $this->data, array("name"=>$_a[0],"type"=>$_a[1],"notNull"=>$_a[2],"val"=>$_a[3]) );
		}
		public function draw() {
			$data = $this->parsing();
			$temp =
				'<div class="row" style="margin-bottom:10px;padding:15px;">'.$this->message.'</div>'.
				'<div class="errore alert-danger" style="margin-bottom:20px;"></div>'.
				'<div class="row">'.
				'<div class="col-md-12">'.
				$data.
				'</div>'.
				'</div>';
			return $temp;
		}
		public function standardTable( $_t, $_f, $_hidden = false ) {
			$dataRaw = c_query("DESCRIBE ".$_t,"Errore 03");
			$data = [];
			foreach ($dataRaw as $i) {
				if (strpos($i["Field"],'pk_') === false && strpos($i["Field"],'flag_') === false) {
					if(strpos($i["Field"],'fk_') !== false) {
						array_push($data, array( $i["Field"], $this->getSubTable(str_replace("fk_","",$i["Field"])), ($i["Null"]=="NO"), ""));
					} else if(strpos($i["Field"],'password') !== false) {
						array_push($data, array( $i["Field"], "password", ($i["Null"]=="NO"), ""));
					} else if( strpos($i["Type"],'tinyint') !== false || strpos($i["Type"],'bit') !== false ) {
						array_push($data, array( $i["Field"], array(array("SI",1),array("NO",0)), ($i["Null"]=="NO"), ""));
					} else {
						array_push($data, array( $i["Field"], $i["Type"], ($i["Null"]=="NO"), ""));
					}
				} else if($_hidden) {
					array_push($data, array( $i["Field"], "hidden", ($i["Null"]=="NO"), ""));
				}
			}
			return $this->init($data, $_f);
		}
		public function parsing() {
			$temp = "";
			foreach ($this->data as &$i) {
				$row = "";
				if(is_array($i["type"])) {
					$name	= $this->parsingName($i["name"]);
					$row 	= $row.'<div class="input-group formInput-row">';
					$row 	= $row.'<span class="input-group-addon" >'.$name.'</span>';
					$row 	= $row.'<select class="form-control formInput chosen-select" data-live-search="true" form="'.$this->form.'" id="'.$i["name"].'">';
					$row = $row.'<option value="'.$GLOBALS["all"].'">TUTTI</option>';
					foreach ($i["type"] as $j) {
						$row = $row.'<option value="'.$j[1].'">'.$j[0].'</option>';
					}
					$row = $row.'</select>';
					$row = $row.'</div>';
				} else {
					$name 		= $this->parsingName		($i["name"]);
					$type 		= $this->parsingType		($i["type"]);
					$notNull 	= $this->parsingNotNull	($i["notNull"]);
					$val 			= $this->parsingValue		($i["val"]);
					$class = "";
					if($notNull) $class = "notNull";
					if($type == "textarea") {
						$row = $row.'<div class="input-group formInput-row">';
						$row = $row.'<span class="input-group-addon" >'.$name.$notNull.'</span>';
						$row = $row.'<textarea class="form-control formInput '.$class.'" rows="5" placeholder="'.$name.'" form="'.$this->form.'" id="'.$i["name"].'">'.$val.'</textarea>';
						$row = $row.'</div>';
					} else if($type == "hidden") {
						$row = $row.'<input type="'.$type.'" class="form-control formInput '.$class.'" placeholder="'.$name.'" form="'.$this->form.'" id="'.$i["name"].'" value="'.$val.'">';
					} else {
						$row = $row.'<div class="input-group formInput-row">';
						$row = $row.'<span class="input-group-addon" >'.$name.$notNull.'</span>';
						$row = $row.'<input type="'.$type.'" class="form-control formInput '.$class.'" placeholder="'.$name.'" form="'.$this->form.'" id="'.$i["name"].'" value="'.$val.'">';
						$row = $row.'</div>';
					}
				}
				$temp = $temp.$row;
			}
			return $temp;
		}
		public function parsingType( $_t ) {
			$temp = "text";
			$_t = explode("(", $_t)[0];
			switch ($_t) {
				case 'varchar': 	$temp = "text"; 		break;
				case 'int': 			$temp = "number"; 	break;
				case 'text': 			$temp = "textarea"; break;
				case 'hidden': 		$temp = "hidden"; 	break;
				case 'password':	$temp = "password"; break;
				default: 					$temp = "text";			break;
			}
			return $temp;
		}
		public function parsingName( $_n ) {
			$_n = str_replace("fk_", "", $_n);
			$_n = str_replace("pk_", "", $_n);
			$_n = str_replace("flag_", "", $_n);
			$_n = str_replace("_", " ", $_n);
			return $_n;
		}
		public function parsingNotNull( $_n ) {
			return ($_n?"*":"");
		}
		public function parsingValue( $_v ) {
			return $_v;
		}
		private function getSubTable( $_t ) {
			$temp		= [];
			$table	= c_query("SELECT * FROM ".$_t." WHERE flag_active = 1","Table,getSubTable,table");
			$rows		= c_query("SELECT main_field FROM tables WHERE pk_tables='$_t'","Table,getSubTable,label");
			if(count($rows)!=1) return $temp;
			if($rows[0]["main_field"]=="") return $temp;
			foreach ($table as $i) {
				array_push($temp,array($i[$rows[0]["main_field"]],$i["pk_".$_t]));
			}
			return $temp;
		}
	}
?>
