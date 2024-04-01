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
	url:'<?=HOME?><?=APPID?>/data',
	mtype : "post",
	datatype: "json",
	colNames:['','No. NOTA','No. Proforma','SAP No. Faktur','No. Request','EMKL','Modul','Tanggal Kegiatan','Amount'], 
	colModel:[
		{name:'act', width:70, align:"center", search:false},
		{name:'nn', width:120, align:"center"},
		{name:'nt', width:120, align:"center"},
		{name:'nf', width:120, align:"center"},
		{name:'nr',index:'nr', width:100, align:"center"},
		{name:'em',index:'em', width:160, align:"center"},
		{name:'aem',index:'aem', width:120, align:"center"},
		{name:'vv',index:'vv', width:160, align:"center"},
		{name:'sn',index:'sn', width:100, align:"center"}
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


function pay(a,b,c,total,emkl,koreksi,mti,tgl)
{
	$('#pay').load("<?=HOME?><?=APPID?>.ajax/payment?idn="+a+"&req="+encodeURIComponent(b)+"&ket="+c+"&total="+total+"&emkl="+emkl+"&koreksi="+koreksi+"&mti="+mti+"&tgl="+tgl, function(data){
		$("#paid_via").load("<?=HOME?><?=APPID?>.ajax/paid_via?tgl="+tgl,{KD_PELUNASAN:1,idn:a});	
	}).dialog({modal:true, height:500,width:350, title : "Payment cash"});
}

function print(a,b,c)
{

	var url;
	var url2;
	url='<?=HOME?><?=APPID?>.print/print?no_req='+a+'&jn='+b+'&tgl='+c;
	
	
	window.open(url, '_blank');
	
	
	return false;
}

function get_paid_via() {
	var kd_pelunasan = $("#kd_pelunasan").val();
	$("#paid_via").load("<?=HOME?><?=APPID?>.ajax/paid_via",{KD_PELUNASAN:kd_pelunasan});
}


function save_payment(a,b,c,d,e,f,g,h)
{
	var bankid=$('#via').val();
	var kd_pelunasan = $("#kd_pelunasan").val();
	var url="<?=HOME?><?=APPID?>.ajax/save_payment_praya";
	
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
			$.post(url,{IDN: a,IDR:b, JENIS:c, BANK_ID:bankid, VIA:kd_pelunasan,EMKL:d,KOREKSI:e,JUM:f,MTI:g,NO_PERATURAN:h}).done(function(data){	
				alert(data);
				//if (data="sukses")
				//{				
					$.unblockUI({
						onUnblock: function(){  }
					});
					$('#pay').dialog('destroy').remove();	
					$('#mainform').append('<div id="pay"></div>');
					//document.location.reload(true);
					$("#l_pay").jqGrid('setGridParam',{url:"<?=HOME?><?=APPID?>/data", datatype:"json"}).trigger("reloadGrid");
				//}			
			}).fail(function(xhr, status, error){
				alert(status);
				alert(error);
			})
		}
	}
}

function sync_payment(id_request, id_nota, kegiatan)
{
	var url="<?=HOME?><?=APPID?>.ajax/sync_payment";
	question = confirm("data akan disync, cek apakah data sudah benar?")
		if (question != "0") {
			$.blockUI({ message: '<h1><br>Please wait... Sync Payment</h1><br><img src=images/loadingbox.gif /><br><br>' });
			$.post(url,{IDN: id_nota,IDR:id_request,KG:kegiatan},function(data){	
				$.unblockUI({
								onUnblock: function(){  }
							});
				alert(data);
				$("#l_pay").jqGrid('setGridParam',{url:"<?=HOME?><?=APPID?>/data", datatype:"json"}).trigger("reloadGrid");
			});
	}
}
//ESB Implementasi Add Button
function esb_resend(no_nota, id_nota, kegiatan)
{
	var url="<?=HOME?><?=APPID?>.ajax/esb_resend";
	question = confirm("data akan Dikirim ulang, cek apakah data sudah benar?")
		if (question != "0") {
			$.blockUI({ message: '<h1><br>Please wait... Kirim ESB</h1><br><img src=images/loadingbox.gif /><br><br>' });
			$.post(url,{IDN: id_nota,NOTA:no_nota,KG:kegiatan},function(data){	
				$.unblockUI({
								onUnblock: function(){  }
							});
				alert(data);
				$("#l_pay").jqGrid('setGridParam',{url:"<?=HOME?><?=APPID?>/data", datatype:"json"}).trigger("reloadGrid");
			});
	}
}
//END ESB

function search_payment(){
    var id_req = $("#id_req").val();
    $("#l_pay").jqGrid('setGridParam',{url:"<?=HOME?><?=APPID?>/data?idreq="+id_req, datatype:"json"}).trigger("reloadGrid");
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
	<label>Cari No. Request : </label> <input type="text" id="id_req" name="id_req"/> <input type="button" onclick="search_payment()" value="Cari" />
	<table id='l_pay' width="100%"></table> <div id='pg_l_pay'></div>
	
	<form id="mainform">
	<div id='pay'></div>
	
	</form>
	<br/>
	<br/>
	</div>
</div>