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
	colNames:['','','NO CONTAINER','','HZ','VESSEL','Disch', 'End Stack', 'Del Ext'], 
	colModel:[
		{name:'act', width:70, align:"center", search:false},
		{name:'aksi', width:70, align:"center", search:false},
		{name:'ct', width:100, align:"center"},
		{name:'rm', width:80, align:"center"},
		{name:'hz',index:'hz', width:60, align:"center"},
		{name:'vsl',index:'vsl', width:140, align:"center"},
		{name:'dis',index:'dis', width:100, align:"center", editable:false},
		{name:'ends',index:'ends', width:100, align:"center", editable:false, readonly:true},
		//{name:'deli',index:'plo', width:100, align:"center", hidden: true, editable:true, readonly:true, editrules: { edithidden: false }},
		{name:'deli',index:'deli', width:100, align:"center", editable:true,editoptions:{size:20, 
                  dataInit:function(el){ 
                        $(el).datepicker({dateFormat:'dd-mm-yy'}); 
                  }
				}}
		
	],
	//rowNum:100,
	width: 865,
	height: "100%",
	ignoreCase:true,
	//250

	rowList:[100,200,300],
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
			var cl = ids[i]; be = "<input style='height:22px;width:20px;' type='button' value='E' onclick=\"jQuery('#l_vessel').editRow('"+cl+"'); $('#alldonebt').attr('style','display:none');\" />"; 
			se = "<input style='height:22px;width:20px;' type='button' value='S' onclick=\"jQuery('#l_vessel').saveRow('"+cl+"'); $('#l_vessel').jqGrid('setGridParam',{url:'<?=HOME?>datanya/data_cont?q=del2&r=<?=$req;?>', datatype:'json'}).trigger('reloadGrid'); $('#alldonebt').removeAttr('style');\"/>"; 
			
			jQuery("#l_vessel").jqGrid('setRowData',ids[i],{act:be+' '+se}); 
			} 
	}
	, editurl:"<?=HOME;?>request.delivery.sp2pnew.ajax/edit_row?id_req='<?=$req;?>'"	
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
function all_done()
{
	/*var url="<?=HOME;?>request.delivery.sp2pnew.ajax/save_req_d";	
	$.post(url,{REQ:a, OLDREQ: b, KE: j}, function(data){
		//alert('Request telah disimpan');
		alert(data);
		//window.open("<?=HOME?>request.delivery.sp2pnew/", "_SELF");
	});*/
	$("#formsavereq").submit()
}

function checkAlls() {
     var checkboxes = new Array();
     checkboxes = document.getElementsByTagName('input');

     if ($("#cb_all").is(":checked")) {
     for (var i = 0; i < checkboxes.length; i++) {
         if (checkboxes[i].type == 'checkbox') {
             checkboxes[i].setAttribute('checked', true)
         }
     }
 	}
 	else {
 		for (var i = 0; i < checkboxes.length; i++) {
         if (checkboxes[i].type == 'checkbox') {
             //checkboxes[i].setAttribute('checked', false)
			 checkboxes[i].removeAttribute('checked');
         }
     }
 	}
 }

</script>
<form action="<?=HOME;?>request.delivery.sp2pnew.ajax/save_req_d" method="POST" id="formsavereq">
	<input type="hidden" name="noreq_p" value="<?=$req?>"/>
	<input type="hidden" name="old_noreq_p" value="<?=$old_req?>"/>
	<input type="hidden" name="v_reqke_p" value="<?=$v_req_ke?>"/>

<!-- <div class="ui-widget ui-widget-content ui-corner-all ui-jqgrid" style="float:left;margin-left:5px"> -->
        
<!-- </div> -->
<fieldset style="float:left;padding-left:10px" width="15%">
        <legend class="ui-widget-header ui-corner-top"><b>Pilih Container</b></legend>
        <input type="checkbox" id="cb_all" onclick="checkAlls()"/>Semua<br/>        
</fieldset>
<div style='float:right'>
<table id='l_vessel' width="100%"></table> 
<div id='pg_l_vessel'></div>
</div>
<br>
<br>
<button onclick="all_done()" id='alldonebt'><img src="<?=HOME?>images/sg3.png"></button> 
</form>