<?php
	require_once("jate.php");
	if($_POST["action"]=="add") {
		$data = json_decode($_POST["data"],true);
		echo $data["nome"];
		//controllo se esiste e do errore
		//creo cartella
		//controllo se esiste e do errore
		//creo database
		//installo jate
		//setto git
	}
	if($_POST["action"]=="openFolder") {
		$data = json_decode($_POST["data"],true);
		echo $data["nome"]."<br>";
		$logs = [];
		exec("start ".$data["nome"], $logs);
		var_dump($logs);
	}
	if($_POST["action"]=="openConsole") {
		$data = json_decode($_POST["data"],true);
		echo $data["nome"]."<br>";
		$logs = [];
		exec("cd ".$data["nome"]." & start ", $logs);
		var_dump($logs);
	}
	if($_POST["action"]=="execCheck") {
		if(exec('echo EXEC') == 'EXEC'){
			echo 'exec works';
		}
	}
?>
