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
    
        var colors = Highcharts.getOptions().colors,
            categories = ['MSIE', 'Firefox', 'Chrome', 'Safari', 'Opera'],
            name = 'Browser brands',
            data = [{
                    y: 55.11,
                    color: colors[0],
                    drilldown: {
                        name: 'MSIE versions',
                        categories: ['MSIE 6.0', 'MSIE 7.0', 'MSIE 8.0', 'MSIE 9.0'],
                        data: [10.85, 7.35, 33.06, 2.81],
                        color: colors[0]
                    }
                }, {
                    y: 21.63,
                    color: colors[1],
                    drilldown: {
                        name: 'Firefox versions',
                        categories: ['Firefox 2.0', 'Firefox 3.0', 'Firefox 3.5', 'Firefox 3.6', 'Firefox 4.0'],
                        data: [0.20, 0.83, 1.58, 13.12, 5.43],
                        color: colors[1]
                    }
                }, {
                    y: 11.94,
                    color: colors[2],
                    drilldown: {
                        name: 'Chrome versions',
                        categories: ['Chrome 5.0', 'Chrome 6.0', 'Chrome 7.0', 'Chrome 8.0', 'Chrome 9.0',
                            'Chrome 10.0', 'Chrome 11.0', 'Chrome 12.0'],
                        data: [0.12, 0.19, 0.12, 0.36, 0.32, 9.91, 0.50, 0.22],
                        color: colors[2]
                    }
                }, {
                    y: 7.15,
                    color: colors[3],
                    drilldown: {
                        name: 'Safari versions',
                        categories: ['Safari 5.0', 'Safari 4.0', 'Safari Win 5.0', 'Safari 4.1', 'Safari/Maxthon',
                            'Safari 3.1', 'Safari 4.1'],
                        data: [4.55, 1.42, 0.23, 0.21, 0.20, 0.19, 0.14],
                        color: colors[3]
                    }
                }, {
                    y: 2.14,
                    color: colors[4],
                    drilldown: {
                        name: 'Opera versions',
                        categories: ['Opera 9.x', 'Opera 10.x', 'Opera 11.x'],
                        data: [ 0.12, 0.37, 1.65],
                        color: colors[4]
                    }
                }];
    
    
        // Build the data arrays
        var browserData = [];
        var versionsData = [];
        for (var i = 0; i < data.length; i++) {
    
            // add browser data
            browserData.push({
                name: categories[i],
                y: data[i].y,
                color: data[i].color
            });
    
            // add version data
            for (var j = 0; j < data[i].drilldown.data.length; j++) {
                var brightness = 0.2 - (j / data[i].drilldown.data.length) / 5 ;
                versionsData.push({
                    name: data[i].drilldown.categories[j],
                    y: data[i].drilldown.data[j],
                    color: Highcharts.Color(data[i].color).brighten(brightness).get()
                });
            }
        }
    
        // Create the chart
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container',
                type: 'pie'
            },
            title: {
                text: ''
            },
            yAxis: {
                title: {
                    text: 'Total percent market share'
                }
            },
            plotOptions: {
                pie: {
                    shadow: false
                }
            },
            tooltip: {
        	    valueSuffix: '%'
            },
            series: [{
                name: 'Browsers',
                data: browserData,
                size: '100%',
                dataLabels: {
                    formatter: function() {
                        return this.y > 5 ? this.point.name : null;
                    },
                    color: 'white',
                    distance: -30
                }
            }, {
                name: 'Versions',
                data: versionsData,
                innerSize: '100%',
                dataLabels: {
                    formatter: function() {
                        // display only if larger than 1
                        return this.y > 1 ? '<b>'+ this.point.name +':</b> '+ this.y +'%'  : null;
                    }
                }
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
                                y = Math.random();
                            series.addPoint([x, y], true, true);
                        }, 1000);
                    }
                }
            },
            title: {
                text: 'Live random data'
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
                name: 'Random data',
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

<div style="padding-left: 0px; float:left">
	 <center>
		<img border="0px" align="left" src="images/yard_view.gif"><br><br>
         <fieldset style="background-color:#ffffff;margin: 0px 0px 0px 0px; width:1340px; height:1100px; align:left;vertical-align:middle; border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px;">
		<table border="0">
		<tr height="150">
			<td width="600" align="center" bgcolor="#ADE8E6"> dermaga</td>
			<td width="300"><div id="container2" style="width: 300px; height: 150px; margin: 0 auto"></div></td>
			<td width="400"><div id="container" style="width: 300px; height: 100px; margin: 0 auto"></div></td>
		</tr>
		<tr height="100"  align="center">
			<td colspan='3' bgcolor="#ADE8E6"> dermaga </td>
		</tr>
		<tr>
			<td colspan='3'>
			<div style="margin-top:0px;border:0px solid black;width:1340;height:800;">
			<p style="width:100%;">
			<div id="load_layout"></div>
			</p>
		</td>
		</tr>
		</table>
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
		<div id="container" style="width: 300px; height: 230px; margin: 0 auto"></div>
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