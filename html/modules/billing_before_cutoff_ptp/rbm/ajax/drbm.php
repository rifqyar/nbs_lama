<?php
	$idvsb=$_GET['idvsb'];
	$opid=$_GET['opid'];
?>

<script type="text/javascript">
jQuery(function() {
 jQuery("#drbm").jqGrid({
	url:'<?=HOME?>datanya/data_detailrbm?q=drbm&s=<?=$idvsb;?>&r=<?=$opid;?>',
	mtype : "post",
	datatype: "json",
	colNames:['Action','Extra Tools','DG Non Label','No. Container','ISO CODE','Size - Type - Status - Height','Eqp','Hz','EI','UN','UC','Weight','Dimension'], 
	colModel:[
		//{name:'status', width:95, align:"center",sortable:false,search:false},
		{name:'acts', width:40, align:"center",sortable:false,search:false},
		{name:'acts2', width:60, align:"center",sortable:false,search:false},
		{name:'acts3', width:70, align:"center",sortable:false,search:false},
		{name:'cont',index:'cont', width:80, align:"center"},
		{name:'iso',index:'iso', width:60, align:"center"},
		{name:'ct',index:'ct', width:100, align:"center",sortable:false,search:false},
		{name:'ct',index:'ct', width:50, align:"center"},
		{name:'hz',index:'hz', width:40, align:"center"},
		{name:'ei',index:'ei', width:40, align:"center"},
		{name:'un',index:'un', width:40, align:"center"},
		{name:'uc',index:'uc', width:40, align:"center"},
		{name:'wh',index:'wh', width:40, align:"center"},
		{name:'dms',index:'dms', width:40, align:"center"}
	],
	rowNum:20,
	width: 585,
	height: 540,

	//rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_drbm',
	viewrecords: true,
	shrinkToFit: false,
	caption:"List Detail"
 });
  jQuery("#drbm").jqGrid('navGrid','#pg_drbm',{del:false,add:false,edit:false,search:false}); 
 jQuery("#drbm").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
 
});
</script>
<table id='drbm' width="100%"></table> <div id='pg_drbm'></div>