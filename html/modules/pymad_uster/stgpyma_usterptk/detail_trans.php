
<?php
//print_r($_SESSION); die();
if (!isset($_SESSION['__ACL_logininfo'])){
header('Location:http://10.10.33.123/barang/');
}
?>
<script type="text/javascript" src="js/jqgrid/i18n/grid.locale-en.js" ></script>
<script type="text/javascript" src="js/jqgrid/jquery.jqGrid.min.js" ></script>

<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/jqgrid/ui.jqgrid.css" />
<script type="text/javascript"> 
jQuery(function() {
 jQuery("#l_sum").jqGrid({
	url:'stgpyma_barangcab.sum/',
	mtype : "post",
	datatype: "json",
	colNames:['KEGIATAN','CURRENCY','TOTAl PRANOTA PYMAD','JUMLAH NILAI PYMAD','TOTAL PRANOTA BATAL PYMAD','JUMLAH NILAI BATAL PYMAD'], 
	colModel:[
		{name:'NOTA_NAME', width:200, align:"left",sortable:true,search:false},
		{name:'CURRENCY', width:130, align:"center",sortable:true,search:false,summaryType:'count', summaryTpl : 'Total {0} PRANOTA'},
		{name:'COUNT_YES', width:130, align:"right",sortable:true,search:false},
		{name:'AMOUNT_YES', width:150, align:"right",sortable:true,search:false},
		{name:'COUNT_NO', width:150, align:"right",search:false,summaryType:'sum'},
		{name:'AMOUNT_NO',index:'bprp', width:110, align:"right",search:false},
		
		
	],
	rowNum:20,
	width: 925,
	height: 200,//250
	rowList:[10,20,30,40,50,1000],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_sum',
	sortname: 'TRX_NUMBER', recordpos: 'left',
	viewrecords: true,
	shrinkToFit: false,
	addurl: "maintenance.master.user/add/",
	caption:"SUMMARY PYMAD BARANG CABANG"
 }).navGrid('#pg_sum',{
	search:false,
	add:false,
	edit:false,
	del:false
 });
 

 jQuery("#l_user").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
 
 jQuery("#m1").click( 
 function() { 
 var s; s = jQuery("#l_user").jqGrid('getGridParam','selarrrow'); 
 $.post("<?=HOME?>stgpyma_barangcab.ajax/update_stat",{AID:s,PYMASTAT:'Y'},function(){
	
	$("#l_user").jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
	});
 });
 jQuery("#m2").click( 
 function() { 
 var s; s = jQuery("#l_user").jqGrid('getGridParam','selarrrow'); 
 $.post("<?=HOME?>stgpyma_barangcab.ajax/update_stat",{AID:s,PYMASTAT:'X'},function(){
	
	$("#l_user").jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
	});
 });
});

function detail(id,vv,bprp,cust)
{
	$('#add_config').load("<?=HOME?>stgpyma_barangcab.ajax/detail_trans",{AID:id,}).dialog({modal:true, height:400,width:800, buttons: { "close": function() 
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
</style>

<div class="content">

<h2>STAGING PYMAD BARANG CABANG</h2>

<p><br/></p>

<table id='l_sum'></table> <div id='pg_sum' style="margin-bottom:-10px"></div>

<div id="dialog-form">
<form id="mainform">
	<div id="add_config"></div>
	<div id="edit_user"></div>
</form>
</div>

</div>
<input type="button" id="m1" value="PYMAD" style="margin-left:23px">
<input type="button" id="m2" value="Batal PYMAD" style="margin-left:5px"><br>