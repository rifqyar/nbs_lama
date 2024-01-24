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
function ReloadPage() { 
	location.reload();
}
$(document).ready(function() {
	setTimeout("ReloadPage()", 180000);
});

jQuery(function() {
 jQuery("#l_receiving").jqGrid({
	url:'<?=HOME?>datanya/data_diskon?q=l_transhipment',
	mtype : "post",
	datatype: "json",
	colNames:['','No Request','No. BC 1.2','Surat Transhipment','Customer','Ex-Vessel','Destination Vessel','Qty Cont.'], 
	colModel:[
		{name:'aksi', width:100, align:"center",sortable:false,search:false},
		{name:'id_req',index:'id_req', width:100, align:"center"},
		{name:'bc',index:'peb', width:100, align:"center"},
		{name:'st',index:'npe', width:100, align:"center"},
		{name:'cust',index:'cust', width:100, align:"center"},
		{name:'vf',index:'vf', width:200, align:"center"},
		{name:'vt',index:'vt', width:200, align:"center"},
		{name:'qty',index:'qty', width:95, align:"center"}
	],
	rowNum:20,
	width: 865,
	height: "100%",//250

	rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_l_receiving',
	viewrecords: true,
	shrinkToFit: false,
	caption:"Data Request Transhipment"
 });
  jQuery("#l_receiving").jqGrid('navGrid','#pg_l_receiving',{del:false,add:false,edit:false,search:false}); 
 jQuery("#l_receiving").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
 
});

function final_req(a)
{
	var r=confirm("Are You Sure?");
	var url="<?=HOME?>request.anne.ajax/final_req";
	if (r==true)
	{
		$.post(url, {ID_REQ:a},function(data){
			if(data=='ok')
			{
				$("#l_receiving").jqGrid('setGridParam',{url:"<?=HOME?>datanya/data?q=l_receiving", datatype:"json"}).trigger("reloadGrid");	
			}
			else
			{
				alert(data);
				return false
			}
			
		});
	}
	else
	{
		return false;
	} 
	//alert(x);
	return false;
}

function confirm_no_charge(id_req)
{
	var answer = confirm("Are you sure you want save this request? You cannot edit this request after save.");
	if(answer != 0){
		var url="<?=HOME;?>request.transhipment/no_charge";
			$.post(url,{no_req:id_req},function(data){	
			alert(data);
			window.location="<?=HOME?>request.transhipment";			
		});	
	}
}

</script>

<div class="content">
	<div class="main_side">
	<p>
	<h2> <img src="<?=HOME?>images/delivery.png" height="5%" width="5%" style="vertical-align:middle"> Request Transhipment</h2></p>
	<p><br/>
	  <a href="<?=HOME?>request.transhipment/request" class="link-button">
      <img border="0" src="images/sp2p.png" />
		Request Transhipment
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