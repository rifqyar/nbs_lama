<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
 <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/stickytooltip.js"></script>
<link type="text/css" href="css/default.css" rel="stylesheet" />
<link type="text/css" href="css/application.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="css/stickytooltip.css" />
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.20.custom.css" />

<!--jqgrid-->
<script type="text/javascript" src="jqueryui/js/grid.locale-en.js"></script>
<script type="text/javascript" src="jqueryui/js/jquery.jqGrid.min.js"></script>
<script type="text/javascript" src="jqueryui/js/jquery.searchFilter.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="jqueryui/css/ui.jqgrid.css" />
<!--jqgrid-->


<script src="js_chart/js/highcharts.js"></script>
<script src="js_chart/js/modules/exporting.js"></script>

<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
$backuri                      = str_replace('&_saveok=1', '', $_SERVER['REQUEST_URI']);
$_SESSION['__uriback'][APPID] = $backuri . (strpos($_SERVER['REQUEST_URI'], '?') === false ? '?' : '');

?>
<script type="text/javascript">
$(document).ready(function() 
{
	$('#load_layout').load("<?=HOME?>monitoring.yard/load_layout?id=41 #load_layout");
});

jQuery(function() {
 jQuery("#monitoring_yard").jqGrid({
	url:'<?=HOME?>datanya/data?q=monitoring_yard',
	mtype : "post",
	datatype: "json",
	colNames:['NO_CONTAINER','SIZE','TYPE','STATUS','BERAT','VESSEL','VOYAGE','PEL ASAL','PEL TUJUAN','LOKASI PLANNING','LOKASI REALISASI','TGL GATE IN','TGL PLACEMENT', 'DURASI'], 
	colModel:[
		{name:'a',index:'a', width:100, align:"center"},
		{name:'b',index:'b', width:30, align:"center"},
		{name:'c',index:'c', width:30, align:"center"},
		{name:'d',index:'d', width:40, align:"center"},
		{name:'e',index:'e', width:50, align:"center"},
		{name:'f',index:'f', width:100, align:"center"},
		{name:'g',index:'g', width:50, align:"center"},
		{name:'h',index:'h', width:100, align:"center"},
		{name:'i',index:'i', width:100, align:"center"},
		{name:'j',index:'j', width:150, align:"center"},
		{name:'k',index:'k', width:150, align:"center"},
		{name:'l',index:'l', width:100, align:"center"},
		{name:'m',index:'m', width:100, align:"center"},
		{name:'t',index:'t', width:150, align:"center"}
	],
	rowNum:6,
	width: 1340,
	height: "100%",//250

	rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_monitoring_yard',
	viewrecords: true,
	shrinkToFit: false,
	caption:"Detail Container"
 });
  jQuery("#monitoring_yard").jqGrid('navGrid','#pg_monitoring_yard',{del:false,add:false,edit:false,search:false}); 
 jQuery("#monitoring_yard").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
 
});
</script>

<script type="text/javascript">
	
$(function () {
    var chart;
    $(document).ready(function() {
	
	
    	// Radialize the colors
		Highcharts.getOptions().colors = $.map(Highcharts.getOptions().colors, function(color) {
		    return {
		        radialGradient: { cx: 0.5, cy: 0.3, r: 0.7 },
		        stops: [
		            [0, color],
		            [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
		        ]
		    };
		});
		
		// Build the chart
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container5',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: ''
            },
            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.percentage}%</b>',
            	percentageDecimals: 1
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        formatter: function() {
                            return '<b>'+ this.point.name +'</b>: '+ this.percentage +' %';
                        }
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'Yard Occupancy Ratio',
                data: [
                    ['Maersk Line',   45.0],
                    ['Ever Green',       26.8],
                    {
                        name: 'tes 1',
                        y: 12.8,
                        sliced: true,
                        selected: true
                    },
                    ['tes 2',    8.5],
                    ['tes 3',     6.2],
                    ['tes 4',   0.7]
                ]
            }]
        });
    });
    
});
		</script>
		<script>
		$(function () {
    $(document).ready(function() {
        Highcharts.setOptions({
            global: {
                useUTC: false
            }
        });
    
        var chart;
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container2',
                type: 'spline',
                marginRight: 10,
                events: {
                    load: function() {
    
                        // set up the updating of the chart each second
                        var series = this.series[0];
                        setInterval(function() {
                            var x = (new Date()).getTime(), // current time
                                y = 0.094;
                            series.addPoint([x, y], true, true);
                        }, 1000);
                    }
                }
            },
             title: {
                text: 'Yard Occupancy Ratio TO 3'
            },
			subtitle: {
					text: '<? echo date('d M Y');?>'
				},
            xAxis: {
                type: 'datetime',
                tickPixelInterval: 150
            },
            yAxis: {
                title: {
                    text: 'Value'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                formatter: function() {
                        return '<b>'+ this.series.name +'</b><br/>'+
                        Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) +'<br/>'+
                        Highcharts.numberFormat(this.y, 2);
                }
            },
            legend: {
                enabled: false
            },
            exporting: {
                enabled: false
            },
            series: [{
                name: 'Yard Occupancy Ratio',
                data: (function() {
                    // generate an array of random data
                    var data = [],
                        time = (new Date()).getTime(),
                        i;
    
                    for (i = -19; i <= 0; i++) {
                        data.push({
                            x: time + i * 1000,
                            y: Math.random()
                        });
                    }
                    return data;
                })()
            }]
        });
    });
    
});
		</script>
		</script>
	</head>
	<body>

</head>
<body>

<div>
<fieldset style="background-color:#FAEBD7;">
<div style="padding-left: 0px; float:left">
	 <center>
         <fieldset style="margin: 0px 0px 10px 0px; width:1340px; height:50px; background-color:black;vertical-align:middle;">
		<font color="white"><font size="8pt"><b><marquee onmouseover=”this.stop()” onmouseout=”this.start()” scrollamount=”2? direction=”up” width=”100%” height=”100? align=”center”> Y A R D &nbsp &nbsp M O N I T O R I N G</marquee></b></font></font>
		</fieldset>
	</center>
</div>

<div style="padding-left: 10px; float:left">
	 <center>
		<img border="0px" align="left" src="images/yard_view.gif"><br>
         <fieldset style="background-color:#ffffff;margin: 0px 0px 0px 0px; width:810px; height:565px; align:left;vertical-align:middle; border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px;">
		
<div style="margin-top:0px;border:0px solid black;width:780;height:530;overflow-y:scroll;overflow-x:scroll;">
<p style="width:100%;">
<div id="load_layout"></div>
</p>

		
		</fieldset>
	</center>
</div>
<div style="padding-left: 10px;  padding-right: 10px; float:left">
	<center>
		<img border="0px" align="left" src="images/yor.gif"><br>
        <fieldset style="background-color:#ffffff;align:left;margin: 0px 0px 0px 0px; width:500px; height:250px; vertical-align:top; border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px;">
		<br><p>
		<div id="container2" style="min-width: 220px; height: 230px; margin: 0 auto"></div>
		</fieldset>
		<img border="0px" align="left" src="images/yor.gif"><br>
        <fieldset style="background-color:#ffffff;align:left;margin: 0px 0px 0px 0px; width:500px; height:250px; vertical-align:top; border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px;">
		<br>
		<div id="container5" style="width: 300px; height: 230px; margin: 0 auto"></div>
		</fieldset>
	</center>
</div>

<div style="padding-left: 10px;padding-top: 10px; float:left">
	 <center>
	<table id='monitoring_yard' width="100%"></table> <div id='monitoring_yard'></div>
	</center>
</div>
</fieldset>
</div>
</body>
</html>