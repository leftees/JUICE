<?php
	class Graph extends Module {
		public $name;
		public $data;

		public function __construct() {
			$this->name = get_class($this);
			$this->data = [];
			$this->dipendence = array(
				"modules/Graph/Graph.min.js",
				"modules/Graph/Chart.Core.min.js",
				"modules/Graph/Chart.Scatter.min.js"
			);
		}

		public function init() {
			jBlock();
			?>
				<div class="row" id="graphs">
					<div class="col-xs-8">
						<canvas id="header-canvas" width="420" height="180"></canvas>
					</div>
					<div class="col-xs-4" id="header-canvas_legend">

					</div>
				</div>
			<?php
			return jBlockEnd();
		}

		public function draw() {}

		public function make3line( $_row ) {}

		public function make2line( $_row, $_x, $_v1, $_v2, $_t1 = "Massimo", $_t2 = "Minimo" ) {
			$cont = 0;
			$linee = [];
			$delta = 0;
			$max1 = 0;
			$min1 = 0;
			$max2 = 0;
			$min2 = 0;
			$origin = 0;
			$nDivision = 10;
			$temp = $this->makeBreakLine($_row, $_v1, 0, null, $_x, $max1, $min1);
			foreach ($temp as $i) {
				$linee[$cont++] = $this->make1line("green", $_t1, $i);
			}
			$temp = $this->makeBreakLine($_row, $_v2, 0, null, $_x, $max2, $min2);
			foreach ($temp as $i) {
				$linee[$cont++] = $this->make1line("red", $_t2, $i);
			}
			$max = $max1;
			if( $max < $max2) $max = $max2;
			$min = $min1;
			if( $min > $min2) $min = $min2;
			echo $max." - ".$min;
			$delta = ($max-$min)/$nDivision;
			$origin = $min - $delta;
			return array($linee, $origin, $delta, $nDivision+2);
		}

		public function make1line( $_col, $_lab, $_punti ) {
			$punti = [];
			$punti["label"] = $_lab;
			$punti["strokeColor"] = $_col;
			$punti["pointColor"] = $_col;
			$punti["pointStrokeColor"] = "white";
			$punti["data"] = $_punti;
			return $punti;
		}

		public function makeBreakLine( $_row, $_val, $_transl = 0, $_overwrite = null, $_x = "data", &$_max, &$_min ) {
			$linee = [];
			$oldNegat = true;
			$newNegat = false;
			$abile = true;
			$cont = -1;
			$max = "max";
			$min = "min";
			foreach ($_row as $v) {
				$val = floatVal($v[$_val]);
				if( !is_null($_overwrite) ) $val = $_overwrite;
				$newNegat = (floatVal($v[$_val]) < $_transl);
				if( $oldNegat && !$newNegat ) $linee[++$cont] = [];
				if(!$newNegat) array_push($linee[$cont], array( "x" => $v[$_x], "y" => $val));
				$oldNegat = $newNegat;
				if($max == "max") $max = $val;
				if($max < $val) $max = $val;
				if($min == "min") $min = $val;
				if($min > $val) $min = $val;
			}
			$_max = $max;
			$_min = $min;
			return $linee;
		}
	}
?>
