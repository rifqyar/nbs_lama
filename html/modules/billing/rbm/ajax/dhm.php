<?php
	$idvsb=$_GET['idvsb'];
	$opid=$_GET['opid'];
?>

<script type="text/javascript">
jQuery(function() {
 jQuery("#dhm").jqGrid({
	url:'<?=HOME?>datanya/data_detailrbm?q=dhm&s=<?=$idvsb;?>&r=<?=$opid;?>',
	mtype : "post",
	datatype: "json",
	colNames:['Action','Eqp','Open/Close','Qty','Start Open','Finish Open','Start Close','Finish Close'], 
	colModel:[
		//{name:'status', width:95, align:"center",sortable:false,search:false},
		{name:'acts', width:40, align:"center",sortable:false,search:false},
		{name:'cont',index:'cont', width:60, align:"center"},
		{name:'iso',index:'iso', width:60, align:"center"},
		{name:'ct',index:'ct', width:40, align:"center",sortable:false,search:false},
		{name:'ei',index:'ei', width:80, align:"center"},
		{name:'js',index:'js', width:80, align:"center"},
		{name:'fr',index:'fr', width:80, align:"center"},
		{name:'to',index:'to', width:80, align:"center"}
		],
	rowNum:10,
	width: 580,
	height: 300,

	//rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_dhm',
	viewrecords: true,
	shrinkToFit: false,
	caption:"List Detail"
 });
  jQuery("#dhm").jqGrid('navGrid','#pg_dhm',{del:false,add:false,edit:false,search:false}); 
 jQuery("#dhm").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
 
});
</script>
<table id='dhm' width="100%"></table> <div id='pg_dhm'></div>