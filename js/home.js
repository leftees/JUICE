$(document).ready(function() {
	$("input:text:visible:first").focus();
	$(".newSite").click(function(event) {
		newSite();
	});
	$(".openFolder").click(function(event) {
		var name = $(this).attr('folder');
		console.log("apri cartella: "+name);
		fai("openFolder",{"name": name});
	});
	$(".openConsole").click(function(event) {
		var name = $(this).attr('folder');
		console.log("apri console: "+name);
		fai("openConsole",{"name": name});
	});
	$("#search").keyup(function(event) {
		var val = $(this).val();
		filter(val);
	});
	$("#searchClear").click(function(){
		$("#search").val('');
		$("#search").focus();
		filter('');
	});
});

function newSite() {
	var temp = "";
	temp +=
		'<div class="input-group">\
		<span class="input-group-addon" id="basic-addon1">Project Name</span>\
		<input type="text" class="form-control WAname" placeholder="Project Name" aria-describedby="basic-addon1">\
		</div>';
	dialog( "NEW WEB APP", temp, -1, [
		'<button type="button" class="btn btn-default createWA" style="vertical-align:bottom;">Create</button>',
		'<button type="button" class="btn btn-default" style="vertical-align:bottom;" data-dismiss="modal">Cancel</button>'
		], function() {
			$(".WAname").focus();
			$(".createWA").click(function(event) {
				var name = $(".WAname").val();
				createProject("add",{"name": name});
			});
			$('.WAname').keypress(function (e) {
				if (e.which == 13) {
					var name = $(".WAname").val();
					createProject("add",{"name": name});
				}
			});
		}
	);
}

function fai( _action, _obj ) {
	var dts = "";
	dts = JSON.stringify(_obj);
	$.post('ajax.php', {"action":_action, "data":dts}, function(data, textStatus, xhr) {});
}

function createProject( _action, _obj ) {
	var dts = "";
	dts = JSON.stringify(_obj);
	$.post('ajax.php', {"action":_action, "data":dts}, function(data, textStatus, xhr) {
		if(data == "true" )
			out("Ok.",0);
		else
			out("No Ok.",3);
	});
}

function filter( _val ) {
	$(".site").each(function(index, el) {
		var text = $(el).html();
		var father = $(el).closest(".project");
		if(text.indexOf(_val) > -1)
			$(father).removeClass('hide');
		else
			$(father).addClass('hide');
	});
}

function out( _msg, _lvl, _end ) {
	if(_end == undefined) _end = true;
	dialog("INFO", _msg, _lvl, []);
	if(_end)
		setTimeout(function () {
			outEnd();
		}, 1000);
}

function outEnd() {
	$("#modalInfo").modal('hide');
}
