var button = {
	searchPath : "",
	mex : [
		"Operazione avvenuta con successo",
		"Operazione fallita, contattare l'assistenza",
		"Il campo non può essere vuoto.",
		"Lo Username ha meno di 6 caratteri.",
		"Formato email non valido."

	]
};

$(document).ready(function() {
	$(".btn-insert").click(function(event) {
		var tab = $(this).attr('form');
		var tabName = $(this).attr("t");
		if(commonCheck())
			insertTable(tab, tabName);
	});
	$(".btn-find").click(function(event) {
		var tab = $(this).attr('form');
		var tabName = $(this).attr("t");
		ricerca_d();
	});
});

function insertTable( _frm, _table ) {
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
	$.post('ajax.php', {"action":"insert", "data":dts}, function(data, textStatus, xhr) {
		if(data == "true" )
			out(button.mex[0],1);
		else
			out(button.mex[1],3);
	});
}

function findTable( _frm, _table ) {
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
	ricerca_d();
}

function commonCheck () {
	var paramForm = {
		list : [
			".formInput",
			".notNull"
		]
	};
	//CLEAR
	for (var i = 0; i < paramForm.list.length; i++) {
		$(paramForm.list[i]).removeClass('alert-danger');
	}
	$(".errore").removeClass('alert');
	$(".errore").html("");

	//CHECK
	var success = true;
	$(".notNull").each(function() {
			if($(this).val() == "") {
				reports(button.mex[2]);
				$(this).addClass('alert-danger');
				success = false;
			}
	});
	if(!success) return false;
	if($("#username").length)
	if($("#username").val().length < 6 ) {
			reports(button.mex[3]);
		$("#username").addClass('alert-danger');
		return false;
	}
	$("[id*='email']").each(function(index, el) {
		if($(this).val().length)
		if(($(this).val().indexOf('@') == -1) || $(this).val().indexOf('.') == -1 ||  $(this).val().length < 5) {
			reports(button.mex[4]);
			$(this).addClass('alert-danger');
			success = false;
			if(!success) return;
		}
	});
	return success;
}

function reports( _error ) {
	$(".errore").addClass('alert')
	$(".errore").html(_error);
}

function ricerca_d() {
	var temp = [];
	$(".formInput").each(function() {
		temp.push([$(this).attr('id'), $(this).val()]);
		console.log("aaa");
	});
 invia_dati(button.searchPath, temp, "post");
}

function invia_dati(servURL, params, method) {
  method = method || "post";
  var form = document.createElement("form");
  form.setAttribute("method", method);
  form.setAttribute("action", servURL);
  form.setAttribute("id", "sendData");
  for(var i=0; i<params.length; i++) {
    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", params[i][0]);
		if(params[i][1]!="")
    hiddenField.setAttribute("value", params[i][1]);
    form.appendChild(hiddenField);
  }
  // document.body.appendChild(form);
	// console.log(form);
  // form.submit();
	$("body").append(form);
	setTimeout(function () { $("#sendData").submit(); }, 10);
}

function getUrl() {
	var uri =
		request.getScheme() + "://" +   // "http" + "://
		request.getServerName() +       // "myhost"
		":" +                           // ":"
		request.getServerPort() +       // "8080"
		request.getRequestURI() +       // "/people"
		"?" +                           // "?"
		request.getQueryString();       // "lastname=Fox&age=30"
		return uri;
}
