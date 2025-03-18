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
</style>
<?php
$db = getDB();
//$KD_CAB = $_SESSION['SESS_KD_CABANG'];
$CORPORATE_NAME = 'IPTK';
$qc = "select count(distinct(org_id)) as corg_id from mst_org_id where corporate_name = '$CORPORATE_NAME'";
$crow = $db->query($qc)->fetchRow();
$count = $crow['CORG_ID'];
if($count > 0){
$no = 1;
$q = "select distinct(org_id) as org_id from mst_org_id where corporate_name = '$CORPORATE_NAME'";
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
$db = getDB();
$sql = "select a.nota_name as NOTA_NAME, 
(select count(distinct(b.trx_number)) from BILLING_NBS.pyma_staging b where b.nota_name = a.nota_name and b.transfer_status = 'S' and b.trx_number IS NOT NULL and to_char(b.trx_date,'yyyy')='$YEAR' and b.org_id in $ORG_ID) as COUNT_YES, 
(select count(distinct(d.trx_number)) from BILLING_NBS.pyma_staging d where d.nota_name = a.nota_name and d.transfer_status = 'F' and d.trx_number IS NOT NULL and to_char(d.trx_date,'yyyy')='$YEAR' and d.org_id in $ORG_ID) as COUNT_NO
from BILLING_NBS.pyma_staging a where a.trx_number IS NOT NULL and to_char(a.trx_date,'yyyy') = '$YEAR' $where_cab group by a.nota_name, a.currency order by a.nota_name asc";
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
	url:'pymad.monpymad_nbsopus_pontianak.sum/?year=<?php echo $YEAR;?>',
	mtype : "post",
	datatype: "json",
	colNames:['KEGIATAN','CURRENCY','QUANTITY','AMOUNT','QUANTITY','AMOUNT'], 
	colModel:[
		{name:'NOTA_NAME', width:170, align:"left",sortable:true,search:false},
		{name:'CURRENCY', width:100, align:"center",sortable:true,search:false},
		{name:'COUNT_YES', width:130, align:"right",sortable:true,search:false,formatter:'integer', thousandsSeparator:','},
		{name:'AMOUNT_YES', width:150, align:"right",sortable:true,search:false,formatter:'integer', thousandsSeparator:','},
		{name:'COUNT_NO', width:150, align:"right",search:false,formatter:'integer', thousandsSeparator:','},
		{name:'AMOUNT_NO',index:'bprp', width:110, align:"right",search:false,formatter:'integer', thousandsSeparator:','},
		
		
	],
	rowNum:20,
	width: 875,
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
	caption:"&nbsp;&nbsp;&nbsp;&nbsp;SUMMARY MONITORING PYMAD STAGING NBS OPUS PONTIANAK",
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
	url:'pymad.monpymad_nbsopus_pontianak.sum/?data=arm&year=<?php echo $YEAR;?>',
	mtype : "post",
	datatype: "json",
	colNames:['KEGIATAN','CURRENCY','AR','NON AR'], 
	colModel:[
		{name:'NOTA_NAME', width:300, align:"left",sortable:true,search:false},
		{name:'CURRENCY', width:130, align:"center",sortable:true,search:false},
		{name:'AR', width:200, align:"right",sortable:true,search:false,formatter:'integer', thousandsSeparator:','},
		{name:'NON_AR', width:200, align:"right",sortable:true,search:false,formatter:'integer', thousandsSeparator:','}
	],
	rowNum:20,
	width: 875,
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
	caption:"&nbsp;&nbsp;&nbsp;&nbsp;SUMMARY MONITORING AR MONITORING PYMAD STAGING NBS OPUS 009",
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
                text: 'NBS OPUS PONTIANAK'
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
                        this.x +': '+ this.y +' mm';
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
						
							$('#add_config').load("<?php echo HOME;?>pymad.monpymad_nbsopus_pontianak/detail",{NOTANAME:this.category,STATUS:this.series.name,YEAR:'<?php echo $YEAR;?>'}).dialog({modal:true, height:500,width:1220});
							
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
<style>
fieldset.dor {
	background :#FAFAFA url(<?php echo $HOME; ?>images/jqgrid_fieldset.png) repeat-x;
	border:1px solid #DBDBDB;
	-moz-border-radius: 8px; 
	border-radius: 8px; 
	width:875px; 
	padding-bottom:10px; 
	padding-top:10px;
}
input {
	font-size:15px
	padding: 5px;   
	border: 1px solid #EEEEEE;
	
	/*Applying CSS3 gradient*/
	background: -moz-linear-gradient(center top , #FFFFFF,  #EFEFEF 1px, #FFFFFF 10px);	
	background: -webkit-gradient(linear, left top, left 20, from(#FFFFFF), color-stop(5%, #EFEFEF) to(#FFFFFF));
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FEFEFE', endColorstr='#FFFFFF');
	
	/*Applying CSS 3radius*/   
	-moz-border-radius: 3px;
	-webkit-border-radius: 3px;
	border-radius: 3px;
	
	/*Applying CSS3 box shadow*/
	-moz-box-shadow: 2px 2px 3px  #dedede;
	-webkit-box-shadow: 2px 2px 3px  #dedede;
	box-shadow: 2px 2px 3px #dedede;
	transition-property:all;
	transition-duration:1s;

	/* Safari */
	-webkit-transition-property:all;
	-webkit-transition-duration:1s;

}
input[type="text"]:hover{
	border:1px solid #00C4F3;
}
input[type="text"]:focus{
	box-shadow:0 0 1px #00C4F3;
}
select {
	font-size:14px
	padding: 5px;   
	border: 1px solid #FFFFFF;
	width:300px;
	
	/*Applying CSS3 gradient*/
	background: -moz-linear-gradient(center top , #FFFFFF,  #EFEFEF 1px, #FFFFFF 10px);	
	background: -webkit-gradient(linear, left top, left 20, from(#FFFFFF), color-stop(5%, #EFEFEF) to(#FFFFFF));
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FEFEFE', endColorstr='#FFFFFF');
	
	/*Applying CSS 3radius*/   
	-moz-border-radius: 3px;
	-webkit-border-radius: 3px;
	border-radius: 3px;
	
	/*Applying CSS3 box shadow*/
	-moz-box-shadow: 0 0 2px #DDDDDD;
	-webkit-box-shadow: 0 0 2px #DDDDDD;
	box-shadow: 0 0 2px #DDDDDD;
	
	transition-property:all;
	transition-duration:1s;

	/* Safari */
	-webkit-transition-property:all;
	-webkit-transition-duration:1s;
}
select:hover{
	border:1px solid #00C4F3;
}
select:focus{
	box-shadow:0 0 2px #00C4F3;
}
.shadow{
	box-shadow: 2px 2px 3px #dedede;
}

.myButton {
	-moz-box-shadow:inset 0px 0px 0px 0px #ffffff;
	-webkit-box-shadow:inset 0px 0px 0px 0px #ffffff;
	box-shadow:inset 0px 0px 0px 0px #ffffff;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #f9f9f9), color-stop(1, #e9e9e9));
	background:-moz-linear-gradient(top, #f9f9f9 5%, #e9e9e9 100%);
	background:-webkit-linear-gradient(top, #f9f9f9 5%, #e9e9e9 100%);
	background:-o-linear-gradient(top, #f9f9f9 5%, #e9e9e9 100%);
	background:-ms-linear-gradient(top, #f9f9f9 5%, #e9e9e9 100%);
	background:linear-gradient(to bottom, #f9f9f9 5%, #e9e9e9 100%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#f9f9f9', endColorstr='#e9e9e9',GradientType=0);
	background-color:#f9f9f9;
	-moz-border-radius:6px;
	-webkit-border-radius:6px;
	border-radius:6px;
	border:1px solid #dcdcdc;
	display:inline-block;
	color:#3590cc;
	font-family:Trebuchet MS;
	font-size:13px;
	font-weight:bold;
	padding:6px 24px;
	text-decoration:none;
	text-shadow:2px 1px 3px #ebeef0;
	width:60px;
	transition:width 1s;
	-webkit-transition:width 1s; /* Safari */
	text-align:center;
	
}
.myButton:hover {
	width:100px;
	border:1px solid #00C4F3;
	text-decoration:none;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #e9e9e9), color-stop(1, #f9f9f9));
	background:-moz-linear-gradient(top, #e9e9e9 5%, #f9f9f9 100%);
	background:-webkit-linear-gradient(top, #e9e9e9 5%, #f9f9f9 100%);
	background:-o-linear-gradient(top, #e9e9e9 5%, #f9f9f9 100%);
	background:-ms-linear-gradient(top, #e9e9e9 5%, #f9f9f9 100%);
	background:linear-gradient(to bottom, #e9e9e9 5%, #f9f9f9 100%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#e9e9e9', endColorstr='#f9f9f9',GradientType=0);background-color:#e9e9e9;
	cursor: pointer;
	}
.myButton:active {
	position:relative;
	top:1px;
}
.myButton2 {
	-moz-box-shadow:inset 0px 0px 0px 0px #ffffff;
	-webkit-box-shadow:inset 0px 0px 0px 0px #ffffff;
	box-shadow:inset 0px 0px 0px 0px #ffffff;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #f9f9f9), color-stop(1, #e9e9e9));
	background:-moz-linear-gradient(top, #f9f9f9 5%, #e9e9e9 100%);
	background:-webkit-linear-gradient(top, #f9f9f9 5%, #e9e9e9 100%);
	background:-o-linear-gradient(top, #f9f9f9 5%, #e9e9e9 100%);
	background:-ms-linear-gradient(top, #f9f9f9 5%, #e9e9e9 100%);
	background:linear-gradient(to bottom, #f9f9f9 5%, #e9e9e9 100%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#f9f9f9', endColorstr='#e9e9e9',GradientType=0);
	background-color:#f9f9f9;
	-moz-border-radius:6px;
	-webkit-border-radius:6px;
	border-radius:6px;
	border:1px solid lightgray;
	display:inline-block;
	color:#3590cc;
	font-family:Trebuchet MS;
	font-size:13px;
	font-weight:bold;
	padding:6px 24px;
	text-decoration:none;
	text-shadow:2px 1px 3px #ebeef0;
	width:150px;
	transition:width 1s;
	-webkit-transition:width 1s; /* Safari */
	text-align:center;
	
}
.myButton2:hover {
	width:200px;
	border:1px solid #00C4F3;
	text-decoration:none;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #e9e9e9), color-stop(1, #f9f9f9));
	background:-moz-linear-gradient(top, #e9e9e9 5%, #f9f9f9 100%);
	background:-webkit-linear-gradient(top, #e9e9e9 5%, #f9f9f9 100%);
	background:-o-linear-gradient(top, #e9e9e9 5%, #f9f9f9 100%);
	background:-ms-linear-gradient(top, #e9e9e9 5%, #f9f9f9 100%);
	background:linear-gradient(to bottom, #e9e9e9 5%, #f9f9f9 100%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#e9e9e9', endColorstr='#f9f9f9',GradientType=0);background-color:#e9e9e9;
	cursor: pointer;
	}
.myButton2:active {
	position:relative;
	top:1px;
}
.ui-dialog .ui-dialog-titlebar-close span {
    display: block;
    margin: -8px !important;
}
</style>
<?php
$query = "select distinct(extract(year from trx_date)) as years from pyma_staging ";
$res = $db->query($query);
$rows = $res->getAll();
?>
<div class="content">
<table width="825px" cellspacing="0" height="81px" border="0" style="margin-top:-40px;margin-left:-24px">
	<tr>
		<td style="padding-top:7px">
			<span class="graybrown " style="">
				<font color="0268AB" size="+2" >&nbsp; MONITORING PYMAD STAGING</font> 
				<font  color="green" size="+2"> NBS OPUS PONTIANAK</font>
			</span>
		</td>
	</tr>
</table>
<br/>
Tampilkan Data Tahun &nbsp; : &nbsp;
<select id="year" style="border:1px solid lightgray">
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
