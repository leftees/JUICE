function dialog( _title, _content, _level, _buttons, _callback) {
	myModal( "#modalInfo", _title, _content, _level, _buttons, _callback );
}

function myModal( _id, _title, _content, _level, _buttons, _callback ) {
	if(_buttons == undefined) _buttons = [
		'<button type="button" class="btn btn-default" style="vertical-align:bottom;" data-dismiss="modal">Cancel</button>'
	];
	clearDialog(_id);
	$(_id).find(".modal-footer").html(_buttons);
	$(_id).find(".modal-title").html(_title);
	$(_id).find(".modal-data").html(_content);
	$(_id).find(".modal-data").removeClass('alert-success');
	$(_id).find(".modal-data").removeClass('alert-info');
	$(_id).find(".modal-data").removeClass('alert-warning');
	$(_id).find(".modal-data").removeClass('alert-danger');
	switch (_level) {
		case 0: $(_id).find(".modal-data").addClass('alert-success');	break;
		case 1: $(_id).find(".modal-data").addClass('alert-info');		break;
		case 2: $(_id).find(".modal-data").addClass('alert-warning'); break;
		case 3: $(_id).find(".modal-data").addClass('alert-danger');	break;
		default: break;
	}
	$(_id).find(".modal-data").html(_content);
	if( _callback != undefined )
		$(_id).on('show.bs.modal', function (e) {
			setTimeout(function(){
				_callback();
			}, 300);
		});
	$(_id).modal();
}

function showPopupWaiting( _enable ) {
	if( _enable ) {
		myModal( "#waiting", "WARNING", "Sincronizzazione dati del database.<br>L'operazione potrebber richiedere qualche minuto.", 3);
	} else {
		$("#waiting").modal('hide');
	}
}

function clearDialog( _id ) {
	$(_id).find(".modal-title").html('');
	$(_id).find(".modal-data").html('');
	$(_id).find(".modal-footer").html('<button type="button" class="btn btn-default" style="vertical-align:bottom;" data-dismiss="modal">Cancel</button>');
	$(_id).find(".modal-data").removeClass('alert-success');
	$(_id).find(".modal-data").removeClass('alert-info');
	$(_id).find(".modal-data").removeClass('alert-warning');
	$(_id).find(".modal-data").removeClass('alert-danger');
}

function out( _msg, _lvl ) {
	dialog("INFO", _msg, _lvl);
	setTimeout(function () {
		$("#modalInfo").modal('hide');
	}, 2000);
}

function queryDialog( _title, _content, _callback ) {
	var buttons = [
			'<button type="button" class="btn btn-default queryYes" style="vertical-align:bottom;" data-dismiss="modal">Yes</button>'
		, '<button type="button" class="btn btn-default queryNo" style="vertical-align:bottom;" data-dismiss="modal">No</button>'
	];
	myModal( "#modalInfo", _title, _content, 2, buttons, function() {
		$(".queryYes").click(function(event) {
			_callback();
		});
	});

}
