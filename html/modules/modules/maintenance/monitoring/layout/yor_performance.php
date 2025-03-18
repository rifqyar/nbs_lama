<script src="<?=HOME?>js_chart/js/highcharts.js"></script>
<script src="<?=HOME?>js_chart/js/modules/exporting.js"></script>
<link rel="stylesheet" href="<?=HOME?>yard/src/css/excite-bike/jquery-ui-1.8.16.custom.css">
	<script src="<?=HOME?>yard/src/js/jquery-1.7.min.js" type="text/javascript"></script>
	<script src="<?=HOME?>yard/src/js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
<?php
	$db=getDb();
	$query_blok = "select count(1) JUMLAH from yd_blocking_cell a, yd_blocking_area b 
					where b.ID_YARD_AREA=81 and a.ID_BLOCKING_AREA=B.ID";
    $result_    = $db->query($query_blok);
    $blok       = $result_->fetchRow();
	$total_cell      = $blok['JUMLAH'];
	
	$query_blok2 = "select count(1) JUMLAH_ISI from yd_placement_yard a, yd_blocking_area b 
					where b.ID_YARD_AREA=81 and a.ID_BLOCKING_AREA=B.ID";
    $result_2    = $db->query($query_blok2);
    $blok2       = $result_2->fetchRow();
	$total_cell_isi = $blok2['JUMLAH_ISI'];
	$used =ceil($total_cell_isi/$total_cell*100); //ceil digunakan untuk pembulatan keatas
	$avb=floor(($total_cell-$total_cell_isi)/$total_cell*100); //floor digunakan untuk pembulatan kebawah
?>

<script>

var chart1; // globally available
var chart2; // globally available

function chart2_create()
{
	chart2 = new Highcharts.Chart({
        chart: {
            renderTo: 'container2',
            
            //type: 'pie'
        },
        title: {
            text: 'YOR per kapal'
        },
		tooltip: {
        	    pointFormat: '{series.name}: <b>{point.percentage}%</b>',
            	percentageDecimals: 1
        },
		plotOptions: 
		{
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
					
					events: {
						click: function(event) {
						alert ("coba");
						}
					},
					
                    showInLegend: true
                }
        },		
        series: [{
                type: 'pie',
                name: 'Capacity',
                data: [
                    
                    ['Masovia 1233',50],
					['Warnow Mate', 50]
                    
                ]}]
      });
}

$(document).ready(function() 
{
    chart1 = new Highcharts.Chart({
        chart: {
            renderTo: 'container',
            
            //type: 'pie'
        },
        title: {
            text: 'YOR Overall'
        },
		tooltip: {
        	    pointFormat: '{series.name}: <b>{point.percentage}%</b>',
            	percentageDecimals: 1
        },
		plotOptions: 
		{
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
					
					events: {
						click: function(event) {
						chart2_create();
						}
					},
					
                    showInLegend: true
                }
        },		
        series: [{
                type: 'pie',
                name: 'Capacity',
                data: [
                    
                    ['Space Available',       <?=$avb?>],
					['Used',   <?=$used?>]
                    
                ]}]
      });
});
</script>
<br>
<h2>YOR Performance</h2>
<div align='center'>

<br><br>
<fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; background-color:#ffffff; vertical-align:middle; border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px;border-color:#eeeeee; border-style:solid;">
<div id="container" style="width: 100%; height: 400px"></div>
</fieldset>
<br>Total Cell Used = <?=$total_cell_isi?><br>Available Cell=<?=$total_cell?>
<br><br>
<div id="container2" style="width: 300px; height: 300px"></div>
<br><br>
</div>