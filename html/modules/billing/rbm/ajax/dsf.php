<?php
	$idvsb=$_GET['idvsb'];
	$opid=$_GET['opid'];
?>

<script type="text/javascript">
jQuery(function() {
 jQuery("#dsf").jqGrid({
	url:'<?=HOME?>datanya/data_detailrbm?q=dsf&s=<?=$idvsb;?>&r=<?=$opid;?>',
	mtype : "post",
	datatype: "json",
	colNames:['Action','No. Container','ISO CODE','Status','HZ-IMO','EI','Shift','From','To','Date'], 
	colModel:[
		//{name:'status', width:95, align:"center",sortable:false,search:false},
		{name:'acts', width:40, align:"center",sortable:false,search:false},
		{name:'cont',index:'cont', width:80, align:"center"},
		{name:'iso',index:'iso', width:60, align:"center"},
		{name:'ct',index:'ct', width:40, align:"center",sortable:false,search:false},
		{name:'hz',index:'hz', width:60, align:"center"},
		{name:'ei',index:'ei', width:40, align:"center"},
		{name:'js',index:'js', width:40, align:"center"},
		{name:'fr',index:'fr', width:40, align:"center"},
		{name:'to',index:'to', width:40, align:"center"},
		{name:'dt',index:'dt', width:80, align:"center"}],
	rowNum:20,
	width: 585,
	height: 540,

	//rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_dsf',
	viewrecords: true,
	shrinkToFit: false,
	caption:"List Detail"
 });
  jQuery("#dsf").jqGrid('navGrid','#pg_dsf',{del:false,add:false,edit:false,search:false}); 
 jQuery("#dsf").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
 
});
</script>
<table id='dsf' width="100%"></table> <div id='pg_dsf'></div>