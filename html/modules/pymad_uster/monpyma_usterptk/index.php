<link rel="stylesheet" type="text/css" href="css/jui/alto_v2/jquery-ui-1.10.3.custom.min.css" />
<link rel="stylesheet" type="text/css" href="css/jqgrid/ui.jqgrid.css" />
<!--<link rel="stylesheet" type="text/css" href="css/jqgrid/jquery-ui-1.8.6.custom.css" />-->
<script type="text/javascript" src="js/jqgrid/jquery-1.9.0.min.js"></script>
<script src="js/jui/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
<script src="js/jqgrid/i18n/grid.locale-en.js" type="text/javascript"></script>
<script src="js/jqgrid/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="js/highchart_new/highcharts.js"></script>
<script src="js/highchart_new/modules/exporting.js"></script>
<style>
.myAltRowClass{
	background:pink
}
.bg_form1 {
    background: rgba(0, 0, 0, 0) url("<?php echo HOME;?>/images/bgform.jpg") no-repeat scroll 0 0;
    height: 70px;
}

#container {
    width: 980px !important;
    margin: 0px auto;
}

</style>
<?php

$db = getDB('storage');
$KD_CAB = '05';
$ACC_KD_CAB = '05A';
$qc = "select count(distinct(org_id)) as corg_id from kapal_prod.mst_org_id@dbint_uster where kd_cabang = '$KD_CAB' AND KD_ACCOUNT_CABANG = '$ACC_KD_CAB'";
$crow = $db->query($qc)->fetchRow();
$count = $crow['CORG_ID'];
if($count > 0){
$no = 1;
$q = "select distinct(org_id) as org_id from kapal_prod.mst_org_id@dbint_uster where kd_cabang = '$KD_CAB' AND KD_ACCOUNT_CABANG = '$ACC_KD_CAB'";
$R = $db->query($q)->getAll();
$ORG_ID = '(';
foreach($R as $row){
	if($no!=$count){
		$ORG_ID .= $row['ORG_ID'].',';
	}else{
		$ORG_ID .= $row['ORG_ID'];
	}
$no++; }
$ORG_ID .= ')';
$where_cab = "AND ORG_ID in ".$ORG_ID."";
}else{
$where_cab = '';
}

if($_GET['year']){
	$YEAR = $_GET['year'];
}else{
	$YEAR = '';
}


$sql = "select a.nota_name as nota_name, 
(select count(distinct(b.trx_number)) from uster.pyma_staging b where b.nota_name = a.nota_name and b.transfer_status = 'S' and b.trx_number IS NOT NULL and to_char(b.trx_date,'yyyy') = '$YEAR' and b.org_id in $ORG_ID) as count_yes, 
(select count(distinct(d.trx_number)) from uster.pyma_staging d where d.nota_name = a.nota_name and d.transfer_status = 'F' and d.trx_number IS NOT NULL  and to_char(d.trx_date,'yyyy') = '$YEAR' and d.org_id in $ORG_ID) as count_no
from uster.pyma_staging a where a.trx_number IS NOT NULL and to_char(a.trx_date,'yyyy') = '$YEAR' $where_cab group by a.nota_name, a.currency order by a.nota_name asc";
$res = $db->query($sql);
$rows = $res->getAll();
$notaname = "";
$notesucc = "";
$notefail = "";
foreach($rows as $row){
	$notaname .= "'".$row['NOTA_NAME']."',";
	$notesucc .= $row['COUNT_YES'].','; 
	$notefail .= $row['COUNT_NO'].','; 
}
?>
<script type="text/javascript"> 
jQuery(function() {
 jQuery("#l_sum").jqGrid({
	url:'pymad_uster.monpyma_usterptk.sum/?year=<?php echo $YEAR;?>',
	mtype : "post",
	datatype: "json",
	colNames:['KEGIATAN','CURRENCY','QUANTITY','AMOUNT','QUANTITY','AMOUNT'], 
	colModel:[
		{name:'NOTA_NAME', width:200, align:"left",sortable:true,search:false},
		{name:'CURRENCY', width:130, align:"center",sortable:true,search:false},
		{name:'COUNT_YES', width:130, align:"right",sortable:true,search:false,formatter:'currency', thousandsSeparator:','},
		{name:'AMOUNT_YES', width:150, align:"right",sortable:true,search:false,formatter:'currency', thousandsSeparator:','},
		{name:'COUNT_NO', width:150, align:"right",search:false,formatter:'currency', thousandsSeparator:','},
		{name:'AMOUNT_NO',index:'bprp', width:110, align:"right",search:false,formatter:'currency', thousandsSeparator:','},
		
		
	],
	rowNum:20,
	width: 925,
	height: "100%",//250
	rowList:[10,20,30,40,50,1000],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_sum',
	sortname: 'TRX_NUMBER',
	viewrecords: true,
	shrinkToFit: false,
	addurl: "maintenance.master.user/add/",
	caption:"&nbsp;&nbsp;&nbsp;&nbsp;SUMMARY MONITORING PYMAD STAGING USAHA TERMINAL PONTIANAK",
	footerrow: true,
	userDataOnFooter : true,
    loadComplete:function(){
		var $grid = $('#l_sum');
		var colSum = $grid.jqGrid('getCol','COUNT_YES',false,'sum');
		$grid.jqGrid('footerData','set',{COUNT_YES:colSum});
		var colSum = $grid.jqGrid('getCol','AMOUNT_YES',false,'sum');
		$grid.jqGrid('footerData','set',{AMOUNT_YES:colSum});
		var colSum = $grid.jqGrid('getCol','COUNT_NO',false,'sum');
		$grid.jqGrid('footerData','set',{COUNT_NO:colSum});
		var colSum = $grid.jqGrid('getCol','AMOUNT_NO',false,'sum');
		$grid.jqGrid('footerData','set',{AMOUNT_NO:colSum});
	}
 }).navGrid('#pg_sum',{
	search:false,
	add:false,
	edit:false,
	del:false
 });
 
 jQuery("#l_pymad").jqGrid({
	url:'pymad_uster.monpyma_usterptk.sum/?data=arm&year=<?php echo $YEAR;?>',
	mtype : "post",
	datatype: "json",
	colNames:['KEGIATAN','CURRENCY','AR','NON AR'], 
	colModel:[
		{name:'NOTA_NAME', width:350, align:"left",sortable:true,search:false},
		{name:'CURRENCY', width:130, align:"center",sortable:true,search:false},
		{name:'AR', width:200, align:"right",sortable:true,search:false,formatter:'integer', thousandsSeparator:','},
		{name:'NON_AR', width:200, align:"right",sortable:true,search:false,formatter:'integer', thousandsSeparator:','}
	],
	rowNum:20,
	width: 925,
	height: "100%",//250
	rowList:[10,20,30,40,50,1000],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_pymad',
	sortname: 'TRX_NUMBER',
	viewrecords: true,
	shrinkToFit: false,
	caption:"&nbsp;&nbsp;&nbsp;&nbsp;SUMMARY MONITORING AR MONITORING PYMAD STAGING USAHA TERMINAL PONTIANAK",
	footerrow: true,
	userDataOnFooter : true
 }).navGrid('#pg_pymad',{
	search:false,
	add:false,
	edit:false,
	del:false
 });
 
 jQuery("#l_sum").jqGrid('setGroupHeaders', {
  useColSpanStyle: true,   
  groupHeaders:[{startColumnName: 'COUNT_YES', numberOfColumns: 2, titleText: 'SUCCESS'},{startColumnName: 'COUNT_NO', numberOfColumns: 2, titleText: 'FAIL'}]
 });
 jQuery('#year').change(function(){
	document.location.href='<?php echo HOME.APPID;?>/?year='+$(this).val();
});
});
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'chart',
                type: 'column'
            },
            title: {
                text: 'MONITORING PYMAD STAGING'
            },
            subtitle: {
                text: 'USAHA TERMINAL PONTIANAK'
            },
            xAxis: {
                categories: [
                   <?php echo $notaname;?>
                ]
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Quantity'
                }
            },
            legend: {
                layout: 'vertical',
                backgroundColor: '#FFFFFF',
                align: 'left',
                verticalAlign: 'top',
                x: 100,
                y: 70,
                floating: true,
                shadow: true
            },
            tooltip: {
                formatter: function() {
                    return ''+
                        this.x +': '+ this.y +' Nota';
                }
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                },
				series: {
                cursor: 'pointer',
                point: {
                    events: {
                        click: function () {
						
							$('#add_config').load("<?php echo HOME;?>pymad_uster.monpyma_usterptk/detail",{NOTANAME:this.category,STATUS:this.series.name,YEAR:'<?php echo $YEAR;?>'}).dialog({modal:true, height:500,width:1250});
							
                        }
                    }
                }
            }
            },
                series: [
			{
                name: 'Success',
                data: [<?php echo $notesucc;?>]
    
            }, 
			{
                name: 'Fail',
                data: [<?php echo $notefail;?>]
    
            }
			]
        });
    });
    
});
</script>
<style>
.content{
	width:95%;
	margin-left:auto;
	margin-right:auto;
	margin-bottom: 10px;
	margin-top:20px;
}
.newButton{
background: #319ff9; /* Old browsers */
background: -moz-linear-gradient(top, #319ff9 0%, #0073ea 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#319ff9), color-stop(100%,#0073ea)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, #319ff9 0%,#0073ea 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, #319ff9 0%,#0073ea 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top, #319ff9 0%,#0073ea 100%); /* IE10+ */
background: linear-gradient(to bottom, #319ff9 0%,#0073ea 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#319ff9', endColorstr='#0073ea',GradientType=0 ); /* IE6-9 */
border:none;
padding:2px 5px 1px 5px;
border-radius:7px;color:white;margin:2px 0px;
font-size:7pt
}
</style>
<?php
$query = "select distinct(extract(year from trx_date)) as years from pyma_staging ";
$res = $db->query($query);
$rows = $res->getAll();
?>
<div class="content">
<table width="980px" cellspacing="0" height="81px" border="0" style="margin-top:-40px;margin-left:-24px">
	<tr>
		<td  class="bg_form1" style="padding-top:7px">
			<span class="graybrown " style="">
				<font color="0268AB" size="+2" >&nbsp; MONITORING PYMAD STAGING</font> 
				<font  color="green" size="+2"> USAHA TERMINAL PONTIANAK</font>
			</span>
		</td>
	</tr>
</table>
<br/>
Tampilkan Data Tahun &nbsp; : &nbsp;
<select id="year" style="border:1px solid lightgray;width:150px">
	<option value='' <?php if($_GET['year']){ if($_GET['year']==''){ echo 'selected'; } } ?>>Pilih Tahun</option>
	<?php foreach($rows as $row){?>
	<option value="<?php echo $row['YEARS'];?>" <?php if($_GET['year']){ if($_GET['year']==$row['YEARS']){ echo 'selected'; } } ?>><?php echo $row['YEARS'];?></option>
	<?php } ?>
</select>
<div id="dialog-form">
<form id="mainform">
	<div id="add_config"></div>
	<div id="edit_user"></div>
</form>
</div>
<p><br/></p>

<table id='l_sum'></table> <div id='pg_sum' style="margin-bottom:-10px"></div><br><br>
<table id='l_pymad'></table> <div id='pg_pymad' style="margin-bottom:-10px"></div><br><br>

<div id="chart" style="border:1px solid lightgray"></div>
<font style="color:red;font-size:8pt">Note :Klik pada chart untuk menampilkan detail</font>
<br><br>
<div id="detail"></div>
</div>
