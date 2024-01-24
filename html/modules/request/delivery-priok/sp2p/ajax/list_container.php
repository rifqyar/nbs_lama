<?php
	$req=$_GET['id'];
	$old_req=$_GET['old_req'];
	$v_req_ke=$_GET['v_req_ke'];
	
	//debug($_GET);

?>
<script type="text/javascript">
jQuery(function() {
 jQuery("#l_vessel").jqGrid({
	url:"<?=HOME?>datanya/data_cont?q=del2&r=<?=$req;?>",
	mtype : "post",
	datatype: "json",
	colNames:['','','NO CONTAINER','','HZ','VESSEL','VOYAGE','Plug In', 'Plug Out', 'Plug Out Ext'], 
	colModel:[
		{name:'act', width:70, align:"center", search:false},
		{name:'aksi', width:70, align:"center", search:false},
		{name:'ct', width:100, align:"center"},
		{name:'rm', width:80, align:"center"},
		{name:'hz',index:'hz', width:60, align:"center"},
		{name:'vsl',index:'vsl', width:140, align:"center"},
		{name:'voy',index:'voy', width:60, align:"center"},
		{name:'pli',index:'pli', width:100, align:"center"},
		{name:'plo',index:'plo', width:100, align:"center", editable:true, readonly:true},
		{name:'plx',index:'plx', width:100, align:"center", editable:true,editoptions:{size:20, 
                  dataInit:function(el){ 
                        $(el).datetimepicker({dateFormat:'dd-mm-yy'}); 
                  }
				}}
		
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
	caption:"Data Container",
	gridComplete: function(){ 
		var ids = jQuery("#l_vessel").jqGrid('getDataIDs'); 
		for(var i=0;i < ids.length;i++){ 
			var rowId = ids[i];
			var rowData = jQuery('#l_vessel').jqGrid ('getRowData', rowId);
			var cl = ids[i]; be = "<input style='height:22px;width:20px;' type='button' value='E' onclick=\"jQuery('#l_vessel').editRow('"+cl+"');\" />"; 
			se = "<input style='height:22px;width:20px;' type='button' value='S' onclick=\"jQuery('#l_vessel').saveRow('"+cl+"'); $('#l_vessel').jqGrid('setGridParam',{url:'<?=HOME?>datanya/data_cont?q=del2&r=<?=$req;?>', datatype:'json'}).trigger('reloadGrid');\"/>"; 
			
			jQuery("#l_vessel").jqGrid('setRowData',ids[i],{act:be+' '+se}); 
			} 
	}
	, editurl:"<?=HOME;?>request.delivery.sp2p.ajax/edit_row?id_req='<?=$req;?>'"	
 });
   jQuery("#l_vessel").jqGrid('navGrid','#pg_l_vessel',{del:false,add:false,edit:false,search:false});
 jQuery("#l_vessel").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});


 });

 $(document).ready(function() 
{	
	 $("#plx").datepicker({
			dateFormat: 'dd-mm-yy'
            });

});
function all_done(a,b,j)
{
	var url="<?=HOME;?>request.delivery.sp2p.ajax/save_req_d";	
	$.post(url,{REQ:a, OLDREQ: b, KE: j}, function(data){
		//alert('Request telah disimpan');
		alert(data);
		window.open("<?=HOME?>request.delivery.sp2p/", "_SELF");
	});
}



</script>

<table id='l_vessel' width="100%"></table> <div id='pg_l_vessel'></div>
<br>
<button onclick="all_done('<?=$req?>', '<?=$old_req?>', '<?=$v_req_ke?>')"><img src="<?=HOME?>images/sg3.png"></button>