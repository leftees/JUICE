<?php
	require_once("jate.php");
	if($_POST["action"]=="add") {
		$data = json_decode($_POST["data"],true);
		//check if exist
		$projects = subFolder("./projects");
		$success = false;
		foreach ($projects as $i)
			if($data["name"] == $i) {
				$success = true;
				break;
			}
		if(!$success) {
			//make folder
			mkdir("./projects/".$data["name"]);
			//make database
			//install JATE
				exec("cd ./projects/".$data["name"]." & bower install JATE ");
			//set git
				exec("cd ./projects/".$data["name"]." & git init ");
		}
		echo !$success? "true" : "false";
	}
	if($_POST["action"]=="openFolder") {
		$data = json_decode($_POST["data"],true);
		echo $data["name"]."<br>";
		$logs = [];
		exec("start ".$data["name"], $logs);
		var_dump($logs);
	}
	if($_POST["action"]=="openConsole") {
		$data = json_decode($_POST["data"],true);
		echo $data["name"]."<br>";
		$logs = [];
		exec("cd ".$data["name"]." & start ", $logs);
		var_dump($logs);
	}
	if($_POST["action"]=="execCheck") {
		if(exec('echo EXEC') == 'EXEC'){
			echo 'exec works';
		}
	}
?>
