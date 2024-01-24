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
function ReloadPage() { 
	location.reload();
}
$(document).ready(function() {
	setTimeout("ReloadPage()", 180000);
});

jQuery(function() {
 jQuery("#l_pay").jqGrid({
	url:'<?=HOME?>datanya/data_diskon?q=payment_cash',
	mtype : "post",
	datatype: "json",
	colNames:['','No. Nota','No. Faktur','No. Request','EMKL','Modul','Vessel - Voy','Save Nota','Payment','Payment VIA'], 
	colModel:[
		{name:'act', width:70, align:"center", search:false},
		{name:'nt', width:120, align:"center"},
		{name:'nf', width:120, align:"center"},
		{name:'nr',index:'nr', width:100, align:"center"},
		{name:'em',index:'em', width:160, align:"center"},
		{name:'aem',index:'aem', width:120, align:"center"},
		{name:'vv',index:'vv', width:160, align:"center"},
		{name:'sn',index:'sn', width:100, align:"center"},
		{name:'pn',index:'pn', width:100, align:"center"},
		{name:'vp',index:'vp', width:80, align:"center"}
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
	pager: '#pg_l_pay',
	viewrecords: true,
	shrinkToFit: false,
	caption:"Payment Cash"
 });
   jQuery("#l_pay").jqGrid('navGrid','#pg_l_pay',{del:false,add:false,edit:false,search:false});
 
 jQuery("#l_pay").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : true,defaultSearch: 'cn'});
 
});


function pay(a,b,c, vessel, voyin, voyout, total)
{
	$('#pay').load("<?=HOME?>billing.paymentcash.ajax/payment?idn="+a+"&req="+encodeURIComponent(b)+"&ket="+c+"&vessel="+encodeURIComponent(vessel)+"&voyin="+encodeURIComponent(voyin)+"&voyout="+encodeURIComponent(voyout)+"&total="+total, function(data){
		$("#paid_via").load("<?=HOME?>billing.paymentcash.ajax/paid_via",{KD_PELUNASAN:1,idn:a});	
	}).dialog({modal:true, height:500,width:350, title : "Payment cash"});
}

function print(a,b,c,d)
{

	var url;
	var url2;
	url='<?=HOME?>print/cetak_nota?id='+a+'&jn='+c+'&ct='+d;
	
	
	window.open(url, '_blank');
	
	
	return false;
}

function get_paid_via() {
	var kd_pelunasan = $("#kd_pelunasan").val();
	$("#paid_via").load("<?=HOME?>billing.paymentcash.ajax/paid_via",{KD_PELUNASAN:kd_pelunasan});
}


function save_payment(a,b,c,vessel,voyin,voyout)
{
	var via=$('#via').val();
	var cms=$('#cms').val();
	var kd_pelunasan = $("#kd_pelunasan").val();
	var url="billing.paymentcash.ajax/save_payment";
	
	if(kd_pelunasan==0)
	{
		alert ('Please Choose Payment Method');
		return false;
	}
	else
	{
		question = confirm("data akan ditransfer, cek apakah data sudah benar?")
		if (question != "0") {
			$.blockUI({ message: '<h1><br>Please wait... Transfer Payment</h1><br><img src=images/loadingbox.gif /><br><br>' });
			$.post(url,{IDN: a,IDR:b, JENIS:c, VIA:via, CMS:cms, VESSEL:vessel, VOYIN:voyin, VOYOUT:voyout, KD_PELUNASAN:kd_pelunasan},function(data){	
				alert(data);
				//if (data="sukses")
				//{				
					$.unblockUI({
						onUnblock: function(){  }
					});
					$('#pay').dialog('destroy').remove();	
					$('#mainform').append('<div id="pay"></div>');
					//document.location.reload(true);
					$("#l_pay").jqGrid('setGridParam',{url:"datanya/data_diskon?q=payment_cash", datatype:"json"}).trigger("reloadGrid");
				//}			
			});
		}
	}
}

function sync_payment(id_request, id_nota)
{
	var url="billing.paymentcash.ajax/sync_payment";
	question = confirm("data akan disync, cek apakah data sudah benar?")
		if (question != "0") {
			$.blockUI({ message: '<h1><br>Please wait... Sync Payment</h1><br><img src=images/loadingbox.gif /><br><br>' });
			$.post(url,{IDN: id_nota,IDR:id_request},function(data){	
				$.unblockUI({
								onUnblock: function(){  }
							});
				alert(data);
				$("#l_pay").jqGrid('setGridParam',{url:"datanya/data_diskon?q=payment_cash", datatype:"json"}).trigger("reloadGrid");
			});
	}
}

</script>

<div class="content">
	<div class="main_side">
	<p>
	 <img src="<?=HOME?>images/crdc.png" style="vertical-align:middle">&nbsp;<b> <font color='#69b3e2' size='5px'>Payment</font> </b>
	 <font color='#888b8d' size='5px'>
	 Cash
	 </font>
	 
	 </p>
	<p><br/>
	  </p>
	<p><br/>
	
	</p>
	
	<table id='l_pay' width="100%"></table> <div id='pg_l_pay'></div>
	
	<form id="mainform">
	<div id='pay'></div>
	
	</form>
	<br/>
	<br/>
	</div>
</div>