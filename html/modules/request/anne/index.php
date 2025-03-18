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

</style>
<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
$backuri                      = str_replace('&_saveok=1', '', $_SERVER['REQUEST_URI']);
$_SESSION['__uriback'][APPID] = $backuri . (strpos($_SERVER['REQUEST_URI'], '?') === false ? '?' : '');

?>
<script type="text/javascript">
jQuery(function() {
 jQuery("#l_receiving").jqGrid({
	url:'<?=HOME?>datanya/data?q=l_receiving',
	mtype : "post",
	datatype: "json",
	colNames:['','No Request','Qty','POD','PEB','NPE','EMKL','Vessel - Voy','Start Stack','Loading Date'], 
	colModel:[
		{name:'aksi', width:100, align:"center",sortable:false,search:false},
		{name:'id_req',index:'id_req', width:100, align:"center"},
		{name:'jum_cont',index:'jum_cont', width:50, align:"center"},
		{name:'pod',index:'pod', width:95, align:"center"},
		{name:'peb',index:'peb', width:100, align:"center"},
		{name:'npe',index:'npe', width:100, align:"center"},
		{name:'emkl',index:'emkl', width:200, align:"center"},
		{name:'vessel',index:'vessel', width:200, align:"center"},
		{name:'st',index:'st', width:100, align:"center"},
		{name:'ld',index:'ld', width:100, align:"center"}
	],
	rowNum:20,
	width: 865,
	height: "100%",//250
	ignoreCase:true,
	rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_l_receiving',
	viewrecords: true,
	shrinkToFit: false,
	caption:"Data Request Receiving"
 });
  jQuery("#l_receiving").jqGrid('navGrid','#pg_l_receiving',{del:false,add:false,edit:false,search:false}); 
 jQuery("#l_receiving").jqGrid('filterToolbar',{defaultSearch:'cn',stringResult: true,searchOnEnter : false});
 
});


</script>

<div class="content">
	<div class="main_side">
	<p>
	<h2> <img src="<?=HOME?>images/delivery.png" height="5%" width="5%" style="vertical-align:middle"> Request ANNE (Announcement Export)</h2></p>
	<p><br/>
	  <a href="<?=HOME?>request.anne/add_req" class="link-button">
      <img border="0" src="images/sp2p.png" />
		Request ANNE
      </a>	  
	  </p>
	<p><br/>
	  </p>
	<p><br/></p>
	<table id='l_receiving' width="100%"></table> <div id='pg_l_receiving'></div>

	<br/>
	<br/>
	</div>
</div>