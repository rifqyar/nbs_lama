<link rel="stylesheet" type="text/css" href="css/jui/alto_v2/jquery-ui-1.10.3.custom.min.css" />
<link rel="stylesheet" type="text/css" href="css/jqgrid/ui.jqgrid.css" />
<!--<link rel="stylesheet" type="text/css" href="css/jqgrid/jquery-ui-1.8.6.custom.css" />-->
<script type="text/javascript" src="js/jqgrid/jquery-1.9.0.min.js"></script>
<script src="js/jui/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
<script src="js/jqgrid/i18n/grid.locale-en.js" type="text/javascript"></script>
<script src="js/jqgrid/jquery.jqGrid.min.js" type="text/javascript"></script>

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
    margin: 0 auto;
}
</style>

<script type="text/javascript"> 
jQuery(function() {
 jQuery("#l_sum").jqGrid({
	url:'pymad_uster.stgpyma_usterptk.sum/?year=',
	mtype : "post",
	datatype: "json",
	colNames:['KEGIATAN','CURRENCY','QUANTITY','AMOUNT','QUANTITY','AMOUNT'], 
	colModel:[
		{name:'NOTA_NAME', width:200, align:"left",sortable:true,search:false},
		{name:'CURRENCY', width:130, align:"center",sortable:true,search:false},
		{name:'COUNT_YES', width:130, align:"right",sortable:true,search:false,formatter:'integer', thousandsSeparator:','},
		{name:'AMOUNT_YES', width:150, align:"right",sortable:true,search:false,formatter:'integer', thousandsSeparator:','},
		{name:'COUNT_NO', width:150, align:"right",search:false,formatter:'integer', thousandsSeparator:','},
		{name:'AMOUNT_NO',index:'bprp', width:110, align:"right",search:false,formatter:'integer', thousandsSeparator:','},
		
		
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
	caption:"&nbsp;&nbsp;&nbsp;&nbsp;SUMMARY PYMAD USTER PONTIANAK",
	footerrow: true,
	userDataOnFooter : true,
    loadComplete:function(){
		var $grid = $('#l_sum');
		var colSum = $grid.jqGrid('getCol','AMOUNT_YES',false,'sum');
		$grid.jqGrid('footerData','set',{ClientName:'Total',AMOUNT_YES:colSum});
		var colSum = $grid.jqGrid('getCol','AMOUNT_NO',false,'sum');
		$grid.jqGrid('footerData','set',{AMOUNT_NO:colSum});
	}
 }).navGrid('#pg_sum',{
	search:false,
	add:false,
	edit:false,
	del:false
 });
 jQuery("#l_sum").jqGrid('setGroupHeaders', {
  useColSpanStyle: true,   
  groupHeaders:[{startColumnName: 'COUNT_YES', numberOfColumns: 2, titleText: 'PYMAD'},{startColumnName: 'COUNT_NO', numberOfColumns: 2, titleText: 'NON PYMAD'}]
 });


 jQuery("#l_user").jqGrid({
	url:'pymad_uster.stgpyma_usterptk.data/?year=',
	mtype : "post",
	datatype: "json",
	colNames:['NAMA PRANOTA','NO PRANOTA','TGL PRANOTA','NAMA PELANGGAN','AMOUNT','PYMAD','DETAIL'], 
	colModel:[
		{name:'NOTA_NAME', width:130, align:"center",sortable:true,search:false,hidden:true},
		{name:'TRX_NUMBER', width:140, align:"left",sortable:true,search:false,summaryType:'count', summaryTpl : 'Total {0} PRANOTA'},
		{name:'TRX_DATE', width:90, align:"center",sortable:true,formatter: 'date', formatoptions: {newformat: 'd-M-Y'},search:false,sorttype:'date'},
		{name:'CUSTOMER_NAME', width:200, align:"left",sortable:true,search:false},
		{name:'AMOUNT', width:100, align:"right",sortable:true,sorttype:'int',search:false,summaryType:'sum',formatter:'currency', thousandsSeparator:','},
		{name:'CONFIRMATION_STATUS', width:100, align:"center",search:false},
		{name:'ACTION', width:80, align:"center",search:false},
		
		
	],
	rowNum:500,
	width: 925,
	height: 300,//250
	rowList:[1000,2000,3000,4000,5000,10000],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_user',
	sortname: 'TRX_NUMBER',
	viewrecords: true,
	shrinkToFit: false,
	addurl: "maintenance.master.user/add/",
	caption:"&nbsp;&nbsp;&nbsp;&nbsp;STAGING PYMAD USTER PONTIANAK",
	grouping: true,
	multiselect:true,
	groupingView : { 
				groupField : ['NOTA_NAME'], 
				groupColumnShow : [true], 
				groupText : ['<b>{0}</b>'], 
				groupCollapse : true, 
				groupOrder: ['asc'], 
				groupSummary : [true], 
				showSummaryOnHide: true, 
				groupDataSorted : true 
				},
	beforeSelectRow: function (rowid, e) {
    var $myGrid = $(this),
        i = $.jgrid.getCellIndex($(e.target).closest('td')[0]),
        cm = $myGrid.jqGrid('getGridParam', 'colModel');
    return (cm[i].name === 'cb');
	},
	gridview: true,
	rowattr: function (rd) {
    if (rd.CONFIRMATION_STATUS === "X") { // verify that the testing is correct in your case
        return {"class": "myAltRowClass"};
    }
	}
	
 }).navGrid('#pg_user',{
	search:false,
	add:false,
	edit:false,
	del:false
 });
 /*jQuery("#l_user").jqGrid('setGroupHeaders', {
  useColSpanStyle: true,   
  groupHeaders:[{startColumnName: 'RADIO1', numberOfColumns: 2, titleText: 'PYMAD'}]
});*/
 jQuery("#l_user").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
 
 jQuery("#m1").click( 
 function() { 
 var s; s = jQuery("#l_user").jqGrid('getGridParam','selarrrow'); 
 if(s==''){
	 alert('Anda Belum Memilih Data!');
 }else{
 $.post("<?=HOME?>pymad_uster.stgpyma_usterptk.ajax/update_stat",{AID:s,PYMASTAT:'Y'},function(){
	
	$("#l_user").jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
	$("#l_sum").jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
	});
 }
 });
  jQuery("#submit").click( 
 function() { 
 var col = $('#col').val();
 var key = $('#key').val();
 $("#l_user").jqGrid('setGridParam',{datatype:'json',url:'pymad_uster.stgpyma_usterptk.data/?col='+col+'&key='+key}).trigger('reloadGrid');
 $("#l_sum").jqGrid('setGridParam',{datatype:'json',url:'pymad_uster.stgpyma_usterptk.sum/?col='+col+'&key='+key}).trigger('reloadGrid');
 });
 jQuery("#col").change(function(){ 
 $('#year').val('');
 });
 jQuery("#year").change( 
 function() { 
 $('#col').val('');
 $('#key').val('');
 var year = $('#year').val();
 $("#l_sum").jqGrid('setGridParam',{datatype:'json',url:'pymad_uster.stgpyma_usterptk.sum/?year='+year}).trigger('reloadGrid');
 $("#l_user").jqGrid('setGridParam',{datatype:'json',url:'pymad_uster.stgpyma_usterptk.data/?year='+year}).trigger('reloadGrid');
 });
 jQuery("#m2").click( 
 function() { 
 var s; s = jQuery("#l_user").jqGrid('getGridParam','selarrrow'); 
 if(s==''){
	 alert('Anda Belum Memilih Data!');
 }else{
 $.post("<?=HOME?>pymad_uster.stgpyma_usterptk.ajax/update_stat",{AID:s,PYMASTAT:'X'},function(){
	
	$("#l_user").jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
	$("#l_sum").jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
	});
 }
 });
});
	
 function print(){
 var year = $('#year').val();
 var col  = $('#col').val();
 var key  = $('#key').val();
 window.open("<?=HOME?>pymad_uster.stgpyma_usterptk.excel/?col="+col+"&key="+key+"&year="+year,'_blank');
 }

function detail(id,vv,bprp,cust)
{
	$('#add_config').load("<?=HOME?>pymad_uster.stgpyma_usterptk.ajax/detail_trans",{AID:id,}).dialog({modal:true, height:400,width:800, buttons: { "close": function() 
			{ 
				$(this).dialog("close"); 
				//document.location.reload(true);
			} }});
}
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
	width:925px; 
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
<div class="content">
<table width="980px" cellspacing="0" height="81px" border="0" style="margin-top:-40px;margin-left:-24px">
	<tr>
		<td  class="bg_form1" style="padding-top:7px">
			<span class="graybrown " style="">
				<img src='images/staging.png' border='0' class="icon" style="width:70px"/> 
				<font color="0268AB" size="+2" >&nbsp; STAGING PYMAD</font> 
				<font  color="green" size="+2"> USTER PONTIANAK</font>
			</span>
		</td>
	</tr>
</table>
<br/>
<fieldset class="dor">  
<table class="{$style.gridtable}" border="0" cellpadding="2" cellspacing="2" width="100%">
<tr ><td align="center"></td><td>&nbsp; </td><td ></td></tr>
<tr ><td align="center"></td><td>&nbsp; </td><td ></td></tr>
<tr>
			<td class="form-field-caption" align="left" style="width:130px">Kolom</td>
      		<td class="form-field-input" style="width:200px">: <select name="kolom" id="col" style="width:149px;border:1px solid lightgray"/>
			<option></option>
			<option value="TRX_NUMBER">No Pranota</option>
			<option value="CUSTOMER_NAME">Nama Pelanggan</option>
			<option></option>
			</select></td>
			<td rowspan="2" style="padding-top:10px">
				<!--a class="link-button" id="submit"><img src='images/cari.png' border='0'/>Cari</a>&nbsp;&nbsp;
				<a class="link-button" >&nbsp;Print PDF</a>
				<a class="link-button" onclick="print()">&nbsp;Print Excel</a-->
			<a class="myButton" id="submit"><img src='images/cari.png' border='0'/>Cari</a>&nbsp;&nbsp;
			<a class="myButton" onclick="print()"><img src='images/excel.png' border='0'/>&nbsp;Excel</a>
			</td>
		</tr>
	    <tr>
            <td class="form-field-caption" style="width:130px">Keyword</td>
            <td class="form-field-input" style="width:200px">: 
                 <input type="text" name="keyword" value="" id="key" style="border:1px solid lightgray"/>
            </td>
        </tr>
</table> 
<br>
<center>
<hr style="border:1px dashed silver;width:500px">
</center>
<br>
<table class="{$style.gridtable}" border="0" cellpadding="2" cellspacing="2" width="100%">
    <tr>
        <td class="form-field-caption" style="width:130px">Tampilkan Data Tahun</td>
        <td class="form-field-input" > :
             <?php
        		$db = getDB("storage");
        		$querys = "select distinct(extract(year from trx_date)) as years from pyma_staging";
        		$res = $db->query($querys);
        		$rows = $res->getAll();
        	  ?>
        	<select name="year" id="year" style="width:149px;border:1px solid lightgray"/>
    			<option value="">Pilih Tahun</option>
    			<?php foreach($rows as $row){?>
    			<option value="<?php echo $row['YEARS'];?>"><?php echo $row['YEARS'];?></option>
    			<?php } ?>
			</select>
        </td>
    </tr>
</tr>
</table>
</fieldset>
<!--span class="graybrown" style="margin-left:0px"><img src='images/dokumenbig.png' border='0' class="icon"/>&nbsp;&nbsp;STAGING PYMAD BARANG CABANG</span><br/><br>
<fieldset class="form-fieldset" style="width:920px;">

</fieldset-->
<p><br/></p>

<table id='l_sum'></table> <div id='pg_sum' style="margin-bottom:-10px"></div><br><br>
<table id='l_user'></table> <div id='pg_user'></div>

<div id="dialog-form">
<form id="mainform">
	<div id="add_config"></div>
	<div id="edit_user"></div>
</form>
</div>

</div>
<div align="left" style="width:950px;">
<?php
 function toInt($datetime){
	 $int = str_replace('-','',$datetime);
	 $int = str_replace(' ','',$int);
	 $int = str_replace(':','',$int);
	 return (int)$int;
 }
$db = getDB('storage');
$QUERYBUTT="select to_char(sysdate, 'mm-dd  HH24:MI:SS') as dates, to_char(sysdate,'yyyy')  as years from dual";
$result = $db->query($QUERYBUTT)->fetchRow();
$rowd   = $result['DATES'];
$years = $result['YEARS'];
$rowdate = toInt($years.'-'.$rowd);
$start = date('Y')."12-20 00:00:00";
$end = date('Y')."12-31 23:59:00";
if($rowdate > toInt($start) AND $rowdate < toInt($end)){
		echo "<a class='myButton2' id='m1' style='margin-left:23px'><img src='images/checks.png' border='0' style='width:20px'/>&nbsp;Set PYMAD</a>";
		echo "<a class='myButton2' id='m2' style='margin-left:10px'><img src='images/crosss.png' border='0'  style='width:21px'/>&nbsp;Set Batal PYMAD</a>";
	}else{
		echo '';
	}
?>
<br><br>
</div>