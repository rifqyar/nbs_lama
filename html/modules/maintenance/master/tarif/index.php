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
 jQuery("#l_tarif").jqGrid({
	url:'<?=HOME?>datanya/data?q=l_tarif',
	mtype : "post",
	datatype: "json",
	colNames:['','Kode Cont','Desc Cont','Jenis Biaya','Biaya Lainnya','Tarif','Val','O_I', 'Start Period', 'End Period'], 
	colModel:[
		{name:'aksi', width:50, align:"center",sortable:false,search:false},
		{name:'kode_cont',index:'kode_cont', width:80, align:"center"},
		{name:'desc_cont',index:'desc_cont', width:100, align:"center"},
		{name:'jenis',index:'jenis', width:150, align:"center"},
		{name:'biaya',index:'biaya', width:100, align:"center"},
		{name:'tarif',index:'tarif', width:95, align:"center"},
		{name:'val',index:'val', width:50, align:"center"},
		{name:'o_i',index:'o_i', width:50, align:"center"},
		{name:'start',index:'start', width:80, align:"center"},
		{name:'end',index:'end', width:80, align:"center"},
	],
	rowNum:20,
	width: 865,
	height: "100%",//250

	rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_l_tarif',
	viewrecords: true,
	shrinkToFit: false,
	caption:"Data Master Tarif"
 });
  jQuery("#l_tarif").jqGrid('navGrid','#pg_l_tarif',{del:false,add:false,edit:false,search:false}); 
 jQuery("#l_tarif").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
 
});
</script>

<div class="content">
	<div class="main_side">
	<p>
	<h2> <img src="<?=HOME?>images/delivery.png" height="5%" width="5%" style="vertical-align:middle"> Master Tarif </h2></p>
	<p><br/>
	  <a href="<?=HOME?>maintenance.master.tarif/add" class="link-button">
      <img border="0" src="images/sp2p.png" />
		Tambah Tarif
      </a>	  
	  </p>
	<p><br/>
	  </p>
	<p><br/></p>
	<table id='l_tarif' width="100%"></table> <div id='pg_l_tarif'></div>

	<br/>
	<br/>
	</div>
</div>