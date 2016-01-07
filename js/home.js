$(document).ready(function() {
	$(".creaSito").click(function(event) {
		creaSito();
	});
	$(".openFolder").click(function(event) {
		console.log("apri cartella");
		var name = $(this).attr('folder');
		fai("openFolder",{"nome": name});
	});
});

function creaSito() {
	console.log("Creo Sito.");
	fai("add",{"nome": "pippo"});
}

function fai( _action, _obj ) {
	var dts = "";
	dts = JSON.stringify(_obj);
	$.post('ajax.php', {"action":_action, "data":dts}, function(data, textStatus, xhr) {
		if(data == "true" )
			console.log("Ok.");
		else
			console.log("No Ok.");
	});
}
