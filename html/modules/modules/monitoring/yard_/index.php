<?php
$db = getDB();
$query = "SELECT ROUND(NVL((COUNT(DISTINCT(a.NO_CONTAINER))/b.KAPASITAS),0)*100,3) YOR FROM YD_PLACEMENT_YARD a, (SELECT COUNT(c.INDEX_CELL) KAPASITAS FROM YD_BLOCKING_CELL c, YD_BLOCKING_AREA d, YD_YARD_AREA e  WHERE c.ID_BLOCKING_AREA = d.ID AND d.ID_YARD_AREA = e.ID AND e.STATUS = 'AKTIF') b
GROUP BY b.KAPASITAS";
$result = $db->query($query);
$yor    = $result->fetchRow();
$get_yor_ = $yor['YOR'];

$query2 = "SELECT a.NAMA_VESSEL, b.VOYAGE FROM master_vessel a, vessel_schedule b, yd_placement_yard c
WHERE a.KODE_KAPAL = b.ID_VES
AND b.ID_VS = c.ID_VS";
$result2 = $db->query($query2);
$vessel    = $result2->getAll();
$jumlah    = count($vessel);

foreach($vessel as $row){
	$vessel_voy .= "'".$row['NAMA_VESSEL'].' '.$row['VOYAGE']."',";
}


$query3 = "SELECT x.ID_VS, x.NAMA_VESSEL, x.VOYAGE, x.YOR FROM (
SELECT c.ID_VS, a.NAMA_VESSEL, b.VOYAGE, ROUND(NVL((COUNT(DISTINCT(c.NO_CONTAINER))/d.KAPASITAS),0)*100,3) YOR
FROM master_vessel a, vessel_schedule b, yd_placement_yard c, (SELECT COUNT(c.INDEX_CELL) KAPASITAS FROM YD_BLOCKING_CELL c, YD_BLOCKING_AREA d, YD_YARD_AREA e  WHERE c.ID_BLOCKING_AREA = d.ID AND d.ID_YARD_AREA = e.ID AND e.STATUS = 'AKTIF') d
WHERE a.KODE_KAPAL = b.ID_VES
AND b.ID_VS = c.ID_VS
GROUP BY c.ID_VS,a.NAMA_VESSEL, b.VOYAGE,c.NO_CONTAINER,d.KAPASITAS) x 
GROUP BY x.ID_VS, x.NAMA_VESSEL, x.VOYAGE, x.YOR";
$result3 = $db->query($query3);
$kategori    = $result3->getAll();
$i = 0;
foreach($kategori as $row){
	//echo $kategori;
	$vessel = $row['NAMA_VESSEL'];
	$voyage = $row['VOYAGE'];
	$id_vs = $row['ID_VS'];
	$tes .= "{y: 0".$row['YOR']. ",". "color : colors[".$i."],drilldown:{name: '".$vessel.'-'.$voyage."',categories:[";
	$id = $row['ID_VS'];
	$query4 = "SELECT CONCAT(CONCAT(CONCAT(CONCAT(c.size_,'-'),c.type_cont),'-'), c.status_cont) type, count(c.NO_CONTAINER) JUMLAH
                FROM master_vessel a, vessel_schedule b, yd_placement_yard c
                WHERE a.KODE_KAPAL = b.ID_VES
                AND b.ID_VS = c.ID_VS
                AND c.ID_VS = '$id_vs'
                GROUP BY c.ID_VS, a.NAMA_VESSEL, b.VOYAGE, c.size_, c.type_cont, c.status_cont";
				$result4 = $db->query($query4);
				$detail    = $result4->getAll();
	
	$kateg = "";
	$data  = "";
	$j=0;
	foreach($detail as $rows){
		$kateg .= $kateg."'".$rows['TYPE']."'";
		if ($j+1 < count($detail)){
			$kateg = $kateg.",";
		} else {
			$kateg = $kateg."],";
		}
		$j++;
	}
	//echo $kateg;
	$k=0;
	foreach($detail as $rows){
		$jumlah = $rows['JUMLAH'];
		$data .= $data.$jumlah;
		if ($k+1 < count($detail)){
			$data = $data.",";
		} else {
			$data = $data."],";
		}
		$k++;
	}
	$data1 .= "data:[".$data;
	//echo $data1;
	$final .= $tes.$kateg.$data1."color: colors[".$i."]}";
	if (($i+1) >= count($kategori)){
		$final = $final."}";
	} else {
		$final = $final."},";
	}
	$i++;
	$kateg = "";
	$tes   ="";
	$data = "";
	$data1 = "";
}
//echo $final;

?>
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
            categories = [<?=$vessel_voy;?>],
            name = 'Placement Percentage',
			data = [<?=$final;?> ];
    
    
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
                renderTo: 'container4',
                type: 'pie'
            },
            title: {
                text: 'Placement Percentage <?=date('d M Y');?>'
            },
            yAxis: {
                title: {
                    text: 'Total percent per vessel'
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
                name: 'Vessel',
                data: browserData,
                size: '60%',
                dataLabels: {
                    formatter: function() {
                        return this.y > 0 ? this.point.name : null;
                    },
                    color: 'white',
                    distance: -30
                }
            }, {
                name: 'iso code',
                data: versionsData,
                innerSize: '60%',
                dataLabels: {
                    formatter: function() {
                        // display only if larger than 1
                        return this.y > 0 ? '<b>'+ this.point.name +':</b> '+ this.y +'%'  : null;
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
                marginRight: 0,
                events: {
                    load: function() {
    
                        // set up the updating of the chart each second
                        var series = this.series[0];
                        setInterval(function() {
                            var x = (new Date()).getTime(), // current time
                              //  y = //$("#get_yor").val();
							  y = <?=$get_yor_;?>;
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
                name: 'YOR',
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

	</head>
	<body>

<div>
<fieldset style="border-color:#FAEBD7;">
<div style="padding-left: 0px; float:left">
	 <center>
         <fieldset style="margin: 0px 0px 10px 0px; width:1340px; height:89px; background-color:#000000;vertical-align:middle;">
		<br><font color="white"><font size="8pt"><b><marquee onmouseover=”this.stop()” onmouseout=”this.start()” scrollamount=”2? direction=”up” width=”100%” height=”100? align=”center”> Y A R D &nbsp &nbsp M O N I T O R I N G</marquee></b></font></font>
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
		<div id="container4" style="width: 500px; height: 230px; margin: 0 auto"></div>
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