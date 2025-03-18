<style>
.content{
	width:95%;
	margin-left:auto;
	margin-right:auto;
	margin-bottom: 10px;
	margin-top:20px;
}
.main_side{
	width:100%;
	float:left;
	text-align:left;
}
.rightside{ 
	width:25%;
	float:right;
	text-align:center;
}
.ganjil {
  background-color: #FFF; /* Warna untuk baris ganjil */
}
.genap {
  background-color: #bbe3fe; /* Warna untuk baris genap */
}   
.search2{
  padding 0 10px;
  font-size:0.8em;
  **line-height: 0.8em;**
  float:right;
  height:100%;
  display:table;
  vertical-align:middle;
}
button{
	border-radius: 3px;
    border: 1px solid #d0d0d0;
	}
</style>
<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
$backuri                      = str_replace('&_saveok=1', '', $_SERVER['REQUEST_URI']);
$_SESSION['__uriback'][APPID] = $backuri . (strpos($_SERVER['REQUEST_URI'], '?') === false ? '?' : '');

?>
<script type="text/javascript">
jQuery(function() {
 jQuery("#l_vessel").jqGrid({
	url:'<?=HOME?>request.perp_export.data/',
	mtype : "post",
	datatype: "json",
	colNames:['','No. Request','UKK','Vessel','Voyage','Type','ETA','ETD','RTA','RTD'], 
	colModel:[
		{name:'aksi', width:70, align:"center", search:false},
		{name:'no_req', width:100, align:"center"},
		{name:'aksi3', width:100, align:"center"},
		{name:'aksi2', width:150, align:"center"},
		{name:'nama_kapal',index:'nama_kapal', width:80, align:"center"},
		{name:'type_s',index:'type_s', width:80, align:"center"},
		{name:'eta',index:'eta', width:120, align:"center"},
		{name:'etd',index:'etd', width:120, align:"center"},
		{name:'rta',index:'rta', width:120, align:"right"},
		{name:'rtd',index:'rtd', width:120, align:"right"}
	],
	rowNum:20,
	width: 865,
	height: "100%",
	ignoreCase:true,
	//250
	sortorder: "desc",
	rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_l_vessel',
	viewrecords: true,
	shrinkToFit: false,
	caption:"Stacking Extension Request"
 });
   jQuery("#l_vessel").jqGrid('navGrid','#pg_l_vessel',{del:false,add:false,edit:false,search:false});

 
 jQuery("#l_vessel").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
 
});


function ext(i)
{
	$('#rta').load("<?=HOME?>request.perp_export.ajax/rta?id="+i).dialog({closeOnEscape: false, modal:true, height:280,width:350, title : "Submit Stacking Extension", open: function(event,ui){$(".ui-dialog-titlebar-close",ui.dialog).hide();}});
}
</script>

<div class="content">
	<div class="main_side">
	<p>
	 <img src="<?=HOME?>images/stack_ext.png" height="10%" width="10%" style="vertical-align:middle"><b> <font color='#69b3e2' size='5px'>Stacking Extension</font> </b>
	 <font color='#888b8d' size='5px'>
	 Export
	 </font>
	 
	 </p>
	
	<p><br/>
	
	<br><br>
	</p>
	
	<table id='l_vessel' width="100%"></table> <div id='pg_l_vessel'></div>
	
	<form id="mainform">
	<div id='add_vessel'></div>
	<div id='edit_vessel'></div>
	<div id='rta'></div>
	<div id='rtd'></div>
	</form>
	<br/>
	<br/>
	</div>
</div>