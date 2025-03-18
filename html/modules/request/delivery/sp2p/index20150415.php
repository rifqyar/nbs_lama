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
 jQuery("#l_delivery_p").jqGrid({
	url:'<?=HOME?>datanya/data?q=l_delivery_p',
	mtype : "post",
	datatype: "json",
	colNames:['Status Full','Aksi Full','Status Empty','Aksi Empty','No Request','Old Request','QTY','EMKL','Vessel - Voy', 'Perpanjangan Ke-','Tipe Req'], 
	colModel:[
		{name:'status_full', width:100, align:"center",sortable:false,search:false},
		{name:'aksi_full', width:100, align:"center",sortable:false,search:false},
		{name:'status_empty', width:100, align:"center",sortable:false,search:false},
		{name:'aksi_empty', width:100, align:"center",sortable:false,search:false},
		{name:'id_req',index:'id_req', width:100, align:"center"},
		{name:'old_req',index:'old_req', width:100, align:"center"},
		{name:'jum_cont',index:'jum_cont', width:60, align:"center"},
		//{name:'tgl_req',index:'tgl_req', width:100, align:"center"},
		{name:'emkl',index:'emkl', width:200, align:"center"},
		{name:'vessel',index:'vessel', width:200, align:"center"},		
		{name:'sp2p_ke',index:'sp2p_ke', width:70, align:"center"}, 
		{name:'tipe_cont_req',index:'tipe_cont_req', width:50, align:"center"}
	],
	rowNum:20,
	width: 865,
	height: "100%",//250

	rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_l_delivery_p',
	viewrecords: true,
	shrinkToFit: false,
	caption:"Data Request Delivery Perpanjangan"
 });
  jQuery("#l_delivery_p").jqGrid('navGrid','#pg_l_delivery_p',{del:false,add:false,edit:false,search:false}); 
 jQuery("#l_delivery_p").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
 
});

function find_request()
{
	var noreq=$('#noreq').val();
	//alert(noreq);
	$("#l_delivery_p").jqGrid('setGridParam',{url:"<?=HOME?>datanya/data?q=l_delivery_p2&idreqx="+noreq, datatype:"json"}).trigger("reloadGrid");		
}
</script>

<div class="content">
	<div class="main_side">
	<p>
	<h2> <img src="<?=HOME?>images/delivery.png" height="5%" width="5%" style="vertical-align:middle"> Request SP2 Perpanjangan Delivery</h2></p>
	<p><br/>
	  </p>
	<p><br/>
	  </p>
	<p><br/>
		Search No.Request untuk Perpanjangan : <input type="text" id='noreq' name="noreq" size="20"/> <button onclick="find_request()" > F I N D </button>
	</p>
	<table id='l_delivery_p' width="100%"></table> <div id='pg_l_delivery_p'></div>

	<br/>
	<br/>
	</div>
</div>