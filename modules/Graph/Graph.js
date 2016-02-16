function drawGraph( _linee, _id, _steps, _delta, _origin, _dot ) {
	console.log( _linee, _id, _steps, _delta, _origin, _dot );
	var graph = {
		bezierCurve: true,
		bezierCurveTension: 0.01,
		showScale: true,
		scaleShowLabels: true,
		scaleShowHorizontalLines: true,
		scaleShowVerticalLines: true,
		scaleLineWidth: 1,
		scaleLineColor: "black",
		scaleGridLineColor: "#999",
		scaleLabel: "<%=((value*100)-(value*100%1))/100%>",
		scaleArgLabel: "<%=value%>",
		//scaleDateFormat: "dd/mm/yyyy",
		//scaleTimeFormat: "HH:MM",
		scaleDateTimeFormat: "HH:MM",
		scaleGridLineWidth: 0.3,
		useUtc: true,
		pointDot: _dot,
		animation: true,
		responsive: true,
		scaleType: "date",

		//scala ordinate
		scaleOverride: true,
		scaleSteps: _steps,
		scaleStepWidth: _delta,
		scaleStartValue: _origin,
		legendTemplate:
		"<ul class=\"<%=name.toLowerCase()%>-legend\">"+
		"<%for(var i=0;i<datasets.length;i++){%>"+
			"<li>"+
			"<span class=\"<%=name.toLowerCase()%>-legend-marker\" style=\"color:<%=datasets[i].strokeColor%>;\"><%=datasets[i].label%></span>"+
			"</li>"+
		"<%}%>"+
		"</ul>"
	};

	var ctx = document.getElementById(_id).getContext("2d");
	var myChart = new Chart(ctx).Scatter(_linee, graph);
	var lgnd = _id+"_legend";
	document.getElementById(lgnd).innerHTML = myChart.generateLegend();
}


//////////////////////////////////////////////////////////////////////////////
var graphInfo = {
	id : "header-canvas",
	linee : [],
	dot: true
};

$(document).ready(function() {
	for (var i = 0; i < graphInfo.linee.length; i++)
		for (var j = 0; j < graphInfo.linee[i].data.length; j++)
			graphInfo.linee[i].data[j].x = new Date(graphInfo.linee[i].data[j].x);
	drawGraph( graphInfo.linee, graphInfo.id, graphInfo.steps, graphInfo.delta, graphInfo.origin, graphInfo.dot );
});

function listaGraphs( _array ) {
	var temp  = "";
	for (var i = 0; i < _array.length; i++) {
		temp +=
			'<div class="row">'+
			'<div class="col-md-9">'+
			'<div class="row"><canvas id="'+_array[i]+'" width="420" height="180"></canvas></div>'+
			'</div>'+
			'<div class="col-md-3">'+
			'<div class="row" id="'+_array[i]+'_legend"></div>'+
			'</div>'+
			'</div>';
	}
	$("#grafici").html(temp);
	for (var i = 0; i < _array.length; i++) {
		drawGraph(
			graphInfo.linee[_array[i]],
			_array[i],
			graphInfo.steps[_array[i]],
			graphInfo.delta[_array[i]],
			graphInfo.origin[_array[i]],
			graphInfo.dot
		);
	}
}

function myDate( _date, _inverse ) {
	var today;
	if( _date == "now" ) today = new Date();
	else today = new Date( _date );
	var dd = today.getDate();
	var mm = today.getMonth()+1;
	var yyyy = today.getFullYear();
	if(dd<10) {
    dd='0'+dd
	}
	if(mm<10) {
    mm='0'+mm
	}
	if( _inverse == 1)
		today = yyyy+'-'+mm+'-'+dd;
	else
		today = dd+'-'+mm+'-'+yyyy;
	return today;
}
