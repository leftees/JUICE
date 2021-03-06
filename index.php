<?php
	require_once("jate.php");

	//CLASSES
	$connection = new Connection();
	$webApp			= new WebApp();

	//FETCH
	if(!isset($_GET["page"])) $_GET["page"] = "home";
	$webApp->addPages([
		["home","Home"],
		["detail","Detail"]
	]);
	$page = $webApp->fetchPage($_GET["page"]);
	$page->uniforma();

	//TEMPLATE
	require_once($page->data["template"]);
	$gui = new GUI();
	$gui->init();
	$gui->brand    		= $page->data["brand"];
	$gui->menu  			= $page->data["menu"];
	$gui->title				= $page->data["title"];
	$gui->subtitle		= $page->data["subtitle"];
	$gui->content			= $page->data["content"];
	$gui->pagePath		= $page->data["pagePath"];
	$gui->css					= $page->data["css"];
	$gui->js					= $page->data["js"];
	$gui->jsVariables	= $page->data["jsVariables"];
	$gui->footer			= $page->data["footer"];
	$output = $gui->draw();
	echo minify_output($output);
?>
