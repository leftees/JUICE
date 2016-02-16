<?php
	class Error {
		public $data;
		public function __construct( $_class = "", $_function = "", $_section = "", $_level = 0) {
			$data = array("class" => $_class, "function" => $_function, "section" => $_section, "level" => $_level);
		}
	}
?>
