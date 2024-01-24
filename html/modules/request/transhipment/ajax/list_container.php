<?php
	$req=$_GET['id'];
?>
<script type="text/javascript">
jQuery(function() {
 jQuery("#l_vessel").jqGrid({
	url:"<?=HOME?>datanya/data_cont?q=trans&r=<?=$req;?>",
	mtype : "post",
	datatype: "json",
	colNames:['','NO CONTAINER','','HZ','HEIGHT','EX-UKK','DEST-UKK'], 
	colModel:[
		{name:'aksi', width:70, align:"center", search:false},
		{name:'ct', width:120, align:"center"},
		{name:'rm', width:160, align:"center"},
		{name:'hz',index:'hz', width:60, align:"center"},
		{name:'hg',index:'hg', width:80, align:"center"},
		{name:'oukk',index:'oukk', width:60, align:"center"},
		{name:'nukk',index:'nukk', width:60, align:"center"}
	],
	rowNum:20,
	width: 865,
	height: "100%",
	ignoreCase:true,
	//250

	rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_l_vessel',
	viewrecords: true,
	shrinkToFit: false,
	caption:"Data Container"
 });
   jQuery("#l_vessel").jqGrid('navGrid','#pg_l_vessel',{del:false,add:false,edit:false,search:false});
 jQuery("#l_vessel").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});

});

</script>

<table id='l_vessel' width="100%"></table> <div id='pg_l_vessel'></div>
<br>
<button onclick="update_req()"><img src="<?=HOME?>images/sg3.png"></button>