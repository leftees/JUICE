function Form() {
	//variabili
	this.name = "";
	this.data = [];
	this.dataFormed = [];
	//funzioni
	this.init = function ( _a, _name ) {
		this.data = [];
		this.dataFormed = [];
		this.push( _a );
		this.name = _name;
	}
	this.initObj = function ( _a, _name ) {
		this.data = [];
		this.dataFormed = [];
		this.pushObj( _a );
		this.name = _name;
	}
	this.push = function ( _a ) {
		//console.log(_a);
		for (var i = 0; i < _a.length; i++) {
			this.data.push( { "name":_a[i][0], "type":_a[i][1], "notNull":_a[i][2], "val":_a[i][3] } );
		}
	}
	this.pushObj = function ( _a ) {
		//console.log(_a);
		for (var i = 0; i < _a.length; i++) {
			this.data.push( _a[i] );
		}
	}
	this.draw = function () {
		var data = this.parsing();
		var temp =
			'<div class="row" style="margin-bottom:10px;padding:15px;">I campi contrassegnati con * sono obbligatori.<br></div>'+
			'<div class="errore alert-danger" style="margin-bottom:20px;"></div>'+
			'<div class="row">'+
			'<div class="col-md-12">'+
			data+
			'</div>'+
			'</div>';
		return temp;
	}
	this.parsing = function () {
		var temp = "";
		var name;
		var type;
		var notNull;
		var val;
		var row;
		//console.log("-->"+this.data);
		//console.log("this.data.length: "+this.data.length);
		for (var i = 0; i < this.data.length; i++) {
			//console.log(this.data[i]);
			row = "";
			if(Object.prototype.toString.call( (this.data[i].type) ) === '[object Array]') {
				name	= this.parsingName(this.data[i].name);
				row 	= row+'<div class="input-group profiloInuput formInput-row">';
				row 	= row+'<span class="input-group-addon" >'+name+'</span>';
				row 	= row+'<select class="form-control formInput chosen-select" data-live-search="true" form="'+this.name+'" id="'+this.data[i].name+'">';
				// row = row+'<option value="ALL">TUTTI</option>';
				for (var j = 0; j < this.data[i].type.length; j++) {
					row = row+'<option value="'+this.data[i].type[j][1]+'">'+this.data[i].type[j][0]+'</option>';
				}
				row = row+'</select>';
				row = row+'</div>';
			} else {
				name 		= this.parsingName		(this.data[i].name);
				type 		= this.parsingType		(this.data[i].type);
				notNull = this.parsingNotNull	(this.data[i].notNull);
				val 		= this.parsingValue		(this.data[i].val);
				var classe = "";
				if(notNull) classe = "notNull";
				if(type == "textarea") {
					row = row+'<div class="input-group profiloInuput formInput-row">';
					row = row+'<span class="input-group-addon" >'+name+notNull+'</span>';
					row = row+'<textarea class="form-control formInput '+classe+'" rows="5" placeholder="'+name+'" form="'+this.name+'" id="'+this.data[i].name+'">'+val+'</textarea>';
					row = row+'</div>';
				} else if(type == "hidden") {
					row = row+'<input type="'+type+'" class="form-control formInput '+classe+'" placeholder="'+name+'" form="'+this.name+'" id="'+this.data[i].name+'" value="'+val+'">';
				} else {
					row = row+'<div class="input-group profiloInuput formInput-row">';
					row = row+'<span class="input-group-addon" >'+name+notNull+'</span>';
					row = row+'<input type="'+type+'" class="form-control formInput '+classe+'" placeholder="'+name+'" form="'+this.name+'" id="'+this.data[i].name+'" value="'+val+'">';
					row = row+'</div>';
				}
			}
			temp = temp+row;
		}
		return temp;
	}
	this.parsingType = function ( _t ) {
		var temp = "text";
		_t = _t.toString().split("(")[0];
		switch (_t) {
			case 'varchar': 	temp = "text"; 			break;
			case 'int': 			temp = "number"; 		break;
			case 'text': 			temp = "textarea"; 	break;
			case 'hidden': 		temp = "hidden"; 		break;
			case 'password':	temp = "password";	break;
			default: 					temp = "text";  		break;
		}
		return temp;
	}
	this.parsingName = function ( _n ) {
		return _n.toString().replace("pk_", "").replace("fk_", "").replace("flag_", "").replace(/_/g, " ");
	}
	this.parsingNotNull = function ( _n ) {
		return (_n?"*":"");
	}
	this.parsingValue = function ( _v ) {
		return _v;
	}
}
