//GLOBALI
var table = {
	formName : "",
	urlAjax : "ajax.php",
	titoli : ["titolo"],
	data : [
		["link",["a"]],
		["b",["b"]],
		["c",["c"]]
	],
	visibili : [
		['','','','',''],
		["","hide","hide","hide","hide"]
	],
	buttons : [
		'<button type="button" class="btn btn-default disable">Disabilita</button>',
		'<button type="button" class="btn btn-default modify">Modifica</button>',
		'<button type="button" class="btn btn-default delete">Remove</button>'
	]
};

$(document).ready(function() {
	drawTable();
	$(".table-page").change(function(event) {
		var newPage = newPagePath($(this).val());
		window.location.search = newPage;
		window.location.href;
	});
});

function drawTable() {
	makeTable("#table", table.titoli, table.data, table.visibili[0] );
}

function makeTable( _target, _title, _data, _visible ) {
	var colSize = 12;
	colSize = Math.floor(12 / _title.length);
	var tempStr = "";
	var tempForm = new Form();
	tempStr +=
		'<div class="table row">'+
		'<div class="col-xs-12">'+
		'<div class="row table-header">';
	for (var i = 0; i < _title.length; i++) {
		tempStr +='<div class="table-col col-xs-'+colSize+' '+_visible[i]+'">'+tempForm.parsingName(_title[i])+'</div>';
	}
	tempStr +=
		'</div>'+
		'</div>'+
		'<div class="col-xs-12 table-body">';
	var cont = 0;
	var max = 0;
	for (var i = 0; i < _data.length; i++)
		if(max < _data[i][1].length)
			max = _data[i][1].length;
	colSize = Math.floor(12 / max);
	for (var i = 0; i < _data.length; i++) {
		tempStr +=
			'<div class="row table-id table-row-'+((cont++)%2)+'">'+
			'<a pos="'+i+'" href="'+_data[i][0]+'" OnClick="formModal('+i+')" class="'+_data[i][2]+'">';
		for (var j = 0; j < _data[i][1].length; j++) {
			tempStr += '<div class="table-col col-xs-'+colSize+' '+_visible[j]+'">'+_data[i][1][j]+'</div>';
		}
		tempStr +=
			'</a>'+
			'</div>';
	}
	tempStr +=
		'</div>'+
		'<div class="col-xs-12 table-page-content">'+
		'<b>Page: </b><select class="table-page">';
	for(var i = 0; i < table.nPage; i++)
		if(i == table.currentPage)
			tempStr += '<option value="'+i+'" selected>'+(i+1)+'</option>';
		else
			tempStr += '<option value="'+i+'">'+(i+1)+'</option>';
	tempStr +=
		'</select>'+
		'</div>';
		'</div>';
	$(_target).html(tempStr);
}

function formModal( _id ) {
	var q;
	var text = "";
	var formAnagrafica = new Form();
	console.log(table.bigData[_id]);
	var activeFlag = findActive(table.bigData[_id]);
	var tempButtons = table.buttons.slice();
	if(activeFlag != false) {
		if(activeFlag.val == "1")
			tempButtons.splice(0,1);
		else
			tempButtons.splice(1,1);
	}
	formAnagrafica.initObj(table.bigData[_id],table.formName);
	text = formAnagrafica.draw();
	var btn = tempButtons.join(" ")+" ";
	clearDialog("#modalInfo");
	$("#modalInfo").find(".modal-title").html("Dettaglio");
	$("#modalInfo").find(".modal-data").html(text);
	$("#modalInfo").find(".modal-footer").prepend(btn);
	$("#modalInfo").modal();
	$(".btn-modify"	).click(function(event) { modify(this); });
	$(".btn-disable").click(function(event) { disable(this);});
	$(".btn-delete"	).click(function(event) { remove(this); });
}

function findActive( _a ) {
	var success = false;
	for (var i = 0; i < _a.length; i++)
		if(_a[i].name == "flag_active")
			success = _a[i];
	return success;
}

function disable( _this ) {
	var tab = $(_this).attr('form');
	var tabName = $(_this).attr("t");
	modifyTable("disable",tab,tabName);
}

function modify( _this ) {
	var tab = $(_this).attr('form');
	var tabName = $(_this).attr("t");
	modifyTable("modify",tab,tabName);
}

function remove( _this ) {
	var tab = $(_this).attr('form');
	var tabName = $(_this).attr("t");
	modifyTable("delete",tab,tabName);
}

function modifyTable( _action, _frm, _table ) {
	var nameField = "";
	var valField = "";
	var typeFiel = "";
	var dataToSend = [];
	var dts = "";
	$('[form="'+_frm+'"]').each(function(index, el) {
		if(!($(el).is("a") || $(el).is("div"))) {
			nameField = $(el).attr('id');
			valField = $(el).val();
			if( $(el).is("input") && $(el).attr("type") == "number" )
				typeFiel = "number";
			else
				typeFiel = "string";
			dataToSend.push({"name":nameField,"type":typeFiel,"val":valField});
		}
	});
	dts = JSON.stringify({"table":_table,"fields":dataToSend});
	$.post('ajax.php', {"action":_action, "data":dts}, function(data, textStatus, xhr) {
		if(data == "true" ) {
			out(button.mex[0],1);
			//postEffectTable(_action,dataToSend);
			setTimeout(function () {
				document.location.reload(true);
			}, 500);
		} else
			out(button.mex[1],3);
	});
}

function postEffectTable( _action, _data) {
	console.log(_action);
	console.log(_data);
}

function newPagePath( _p ) {// i need optimization
	var path = window.location.search;
	path = path.split("?")[1];
	path = path.split("&");
	var temp = [];
	var success = false;
	for( var i = 0; i < path.length; i++) {
		temp.push(path[i].split("="));
	}
	for( var i = 0; i < temp.length; i++)
		if (temp[i][0]=="p") {
			success = true;
			temp[i][1] = _p;
		}
	if(!success)
	temp.push(["p",_p]);
	path = [];
	for( var i = 0; i < temp.length; i++) {
		path.push(temp[i].join("="));
	}
	path = path.join("&");
	path = "?"+path;
	return path;
}
