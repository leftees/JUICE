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
		echo $data["nome"];
		$logs = [];
		exec("start ".$data["nome"], $logs);
	}
?>
