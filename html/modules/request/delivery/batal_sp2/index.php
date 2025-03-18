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
 jQuery("#l_delivery").jqGrid({
	url:'<?=HOME?>datanya/batal_delivery',
	mtype : "post",
	datatype: "json",
	colNames:['','No BA','No Req SP2','No Container','Tgl Batal SP2','Vessel', 'Voyage In'], 
	colModel:[
		{name:'aksi', width:100, align:"center",sortable:false,search:false},
		{name:'no_ba',index:'no_ba', width:150, align:"center"},
		{name:'no_req',index:'no_req', width:150, align:"center"},
		{name:'no_cont',index:'no_cont', width:100, align:"center"},
		{name:'tgl_batal',index:'tgl_batal', width:100, align:"center"},
		{name:'vessel',index:'vessel', width:120, align:"center"},
		{name:'voyage_in',index:'voyage_in', width:95, align:"center"}
	],
	rowNum:20,
	width: 865,
	height: "100%",//250

	rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_l_delivery',
	viewrecords: true,
	shrinkToFit: false,
	caption:"Batal Delivery"
 });
  jQuery("#l_delivery").jqGrid('navGrid','#pg_l_delivery',{del:false,add:false,edit:false,search:false}); 
 jQuery("#l_delivery").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
 
});
</script>

<div class="content">
	<div class="main_side">
	<p>
	<h2> <img src="<?=HOME?>images/delivery.png" height="5%" width="5%" style="vertical-align:middle"> Request Batal SP2</h2></p>
	<p><br/>
	  <a href="<?=HOME?>request.delivery.batal_sp2/batalsp2" class="link-button">
      <img border="0" src="images/sp2p.png" />
		BATAL SP2 
      </a>	  
	  </p>
	<p><br/>
	  </p>
	<p><br/></p>
	<table id='l_delivery' width="100%"></table> <div id='pg_l_delivery'></div>

	<br/>
	<br/>
	</div>
</div>