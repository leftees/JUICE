$(document).ready(function() {
	$(".openFolder").click(function(event) {
		var name = $(this).attr('folder');
		console.log("apri cartella: "+name);
		fai("openFolder",{"nome": name});
	});
	$(".openConsole").click(function(event) {
		var name = $(this).attr('folder');
		console.log("apri console: "+name);
		fai("openConsole",{"nome": name});
	});
});

function fai( _action, _obj ) {
	var dts = "";
	dts = JSON.stringify(_obj);
	$.post('ajax.php', {"action":_action, "data":dts}, function(data, textStatus, xhr) {
		if(data == "true" ) {
			console.log("Ok.");
			window.location.reload();
		} else
			console.log("No Ok.");
	});
}
