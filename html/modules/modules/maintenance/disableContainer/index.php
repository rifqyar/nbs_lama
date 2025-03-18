<style>
.content{
	width:95%;
	margin-left:auto;
	margin-right:auto;
	margin-bottom: 10px;
	margin-top:20px;
}
</style>
<script type="text/javascript">
jQuery(function() {
 jQuery("#l_renamecontainer").jqGrid({
	url:'<?=HOME?>datanya/dataMaintenis?q=disableContainer',
	mtype : "post",
	datatype: "json",
	colNames:['Disab Date','Name','No Container','Container Spec','Vessel','Nomor Transaksi','Remarks'], 
	colModel:[
		{name:'DISBDATE',index:'DISBDATE', width:100, valign:"center", align:"center",sortable:false,search:false},
		{name:'NAME',index:'NAME', width:125, align:"center",search:false},
		{name:'CONTAINER',index:'CONTAINER', width:150, align:"center"},
		{name:'CONTAINER_SPEC',index:'CONTAINER_SPEC', width:125, align:"center",search:false},
		{name:'VESSEL',index:'VESSEL', width:125, align:"center"},
		{name:'Transaksi',index:'Transaksi', width:150, align:"center"},
		{name:'Remarks',index:'Remarks', width:150, align:"center",search:false}
	],
	rowNum:20,
	width: 865,
	height: "100%",//250
	sortname: "DISBDATE",
	sortorder: "desc",
	rowList:[10,20,30,40,50,60],
	//loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_l_renamecontainer',
	viewrecords: true,
	shrinkToFit: false,
	caption:"Data Disable Container"
 });
  jQuery("#l_renamecontainer").jqGrid('navGrid','#pg_l_renamecontainer',{del:false,add:false,edit:false,search:false}); 
 jQuery("#l_renamecontainer").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
});


function disableCont()
{
	$('#disableCont').load("<?=HOME?>maintenance.disableContainer.ajax/insert").dialog({closeOnEscape: false, modal:true, height:300,width:460, title : "disable Container", open: function(event,ui){$(".ui-dialog-titlebar-close",ui.dialog).hide();}});
}

</script>



<div class="content">
<div class="main_side">
<p>
<span class="graybrown">
<img class="icon" border="0" src="images/contaier.png">
Disable Container
</span>
</p>
<br>
<br>
<p>
<!--<a class="link-button" href="<?=HOME?>maintenance.rename_container/add_req">-->
<a class="link-button" onclick="disableCont()">
<img border="0" src="images/tambah.png">
Disable
</a>
</p>
<br/>
<table id='l_renamecontainer' width="100%"></table> <div id='pg_l_renamecontainer'></div>
<div id="dialog-form">
<form id="mainform">
	<!--<input type="hidden" name="tes" value="tes"/>-->
	<div id="disableCont"></div>
	
</form>
</div>

</div>	

</div>

