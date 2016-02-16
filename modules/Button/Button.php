<?php
	class Button extends Module {
		public $name;
		public $data;
		public function __construct( $_js = true ) {
			$this->name = get_class($this);
			$this->data = [];
			if($_js)
				$this->dipendence = array("modules/Button/Button.min.css","modules/Button/Button.min.js");
			else
				$this->dipendence = array("modules/Button/Button.min.css");
		}
		public function init( $_type="", $_table="", $_label="", $_form="", $_searchPath="" ) {
			return $this->generic( "", $this->fetch($_type), $_table, $_label, $_form, $_searchPath );
		}
		public function init2( $_type, $_id, $_class, $_label, $_searchPath="" ) {
			return $this->generic( "", $this->fetch($_type), "", $_label, "", $_searchPath, $_id, $_class );
		}
		public function init_w( $_type, $_table, $_label, $_form, $_searchPath="" ) {
			return $this->generic( "btn-wide", $this->fetch($_type), $_table, $_label, $_form, $_searchPath );
		}
		public function init_w2( $_type, $_id, $_class, $_label, $_searchPath="" ) {
			return $this->generic( "btn-wide", $this->fetch($_type), "", $_label, "", $_searchPath, $_id, $_class );
		}
		public function draw() {
			return $this->data["btn"];
		}
		private function generic( $_wide = "", $_type, $_table = "", $_label = "", $_form = "", $_searchPath = "", $_id = "", $_class = "" ) {
			if($_searchPath != "")
				array_push($this->dipendence,array("button.searchPath","'".$_searchPath."'"));
			$temp = '<div t="'.$_table.'" form="'.$_form.'" id="'.$_id.'" class="btn-'.$_type.' '.$_wide.' '.$_class.'">'.$_label.'</div>';
			$this->data["btn"] = $temp;
			return $temp;
		}
		private function fetch( $_type ) {
			$temp = "";
			switch ( $_type ) {
				case 'add':			$temp = "insert";		break;
				case 'insert':	$temp = "insert";		break;
				case 'create':	$temp = "insert";		break;
				case 'find':		$temp = "find";			break;
				case 'modify':	$temp = "modify";		break;
				case 'del':			$temp = "delete";		break;
				case 'delete':	$temp = "delete";		break;
				case 'remove':	$temp = "delete";		break;
				case 'canc':		$temp = "delete";		break;
				case 'disable':	$temp = "disable";	break;
				default: $temp = "generic"; break;
			}
			return $temp;
		}
	}
?>
