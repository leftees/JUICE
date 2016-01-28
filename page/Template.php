<?php
	class Template extends Html {
		public function __construct() {
			parent::__construct();
			$this->data["brand"] = array("JUICE","img/brand1.png");
			$this->data["title"] = "JUICE";
			$this->data["template"] = "gui/template.php";
			$this->data["css"] = array_merge( $this->data["css"], array(
				"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css",
				"https://fonts.googleapis.com/css?family=Quicksand",
				"https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css",
				"css/main.min.css"
			));
			$this->data["js"] = array_merge( $this->data["js"], array(
				"https://code.jquery.com/jquery-1.11.3.min.js",
				"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
			));
		}
	}
?>
