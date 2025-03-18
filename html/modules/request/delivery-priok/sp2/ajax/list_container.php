<?php
	$req=$_GET['id'];
?>
<script type="text/javascript">
jQuery(function() {
 jQuery("#l_vessel").jqGrid({
	url:"<?=HOME?>datanya/data_cont?q=del&r=<?=$req;?>",
	mtype : "post",
	datatype: "json",
	colNames:['','NO CONTAINER','Size - Type - Status','HZ','VESSEL','VOYAGE','Commodity'], 
	colModel:[
		{name:'aksi', width:70, align:"center", search:false},
		{name:'ct', width:130, align:"center"},
		{name:'rm', width:170, align:"center"},
		{name:'hz',index:'hz', width:60, align:"center"},
		{name:'vsl',index:'vsl', width:200, align:"center"},
		{name:'voy',index:'voy', width:80, align:"center"},
		{name:'cm',index:'cm', width:100, align:"center"}
		
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

function all_done()
{
	var url_save_flag="<?=HOME;?>request.delivery.sp2.ajax/save_flag_req";
		$.post(url_save_flag,{noreq:'<?=$req;?>'},function(data){	
			//alert(data);
		});
		
	var url="<?=HOME?>request.delivery.sp2.ajax/update_qtycont";
	$.post(url,{ID:'<?=$req;?>'}, function (data){
		if(data='OK')
		{
			window.location="<?=HOME?>request.delivery.sp2/";
		}
		else
		{
			alert("save failed, call programmer");
		}
		}
	);
	
}
</script>

<table id='l_vessel' width="100%"></table> <div id='pg_l_vessel'></div>
<br>
<button onclick="all_done()"><img src="<?=HOME?>images/sg3.png"></button>