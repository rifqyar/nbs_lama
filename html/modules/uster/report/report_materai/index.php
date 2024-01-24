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
	
.tombol {
   padding: 10px 10px;
   background: #69b3e2;
   display: table;
   transition: .3s ease-in;
   background-image: url('icon.png');
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
	setTimeout("ReloadPage()", 120000);
});

jQuery(function() {
 jQuery("#l_pay").jqGrid({
	url:'<?=HOME?><?=APPID?>/data',
	mtype : "post",
	datatype: "json",
	colNames:['No. Peraturan','Jumlah Deposit','Tanggal Peraturan','Terpakai','Tersisa'], 
	colModel:[
		{name:'nt', width:200, align:"center"},
		{name:'nf', width:120, align:"center"},
		{name:'nr',index:'nr', width:150, align:"center"},
		{name:'em',index:'em', width:100, align:"center"},
		{name:'aem',index:'aem', width:250, align:"center"}
	
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
	caption:"Report E-Materai"
 });
   jQuery("#l_pay").jqGrid('navGrid','#pg_l_pay',{del:false,add:false,edit:false,search:false});
 
 jQuery("#l_pay").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : true,defaultSearch: 'cn'});
 
});


		$(function() {	

	$( "#id_time" ).datepicker();
	$( "#id_time" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
        
        $( "#id_time2" ).datepicker();
	$( "#id_time2" ).datepicker( "option", "dateFormat", "yy-mm-dd" );

});

function pay(a,b,c,total,emkl,koreksi)
{
	$('#pay').load("<?=HOME?><?=APPID?>.ajax/payment?idn="+a+"&req="+encodeURIComponent(b)+"&ket="+c+"&total="+total+"&emkl="+emkl+"&koreksi="+koreksi, function(data){
		$("#paid_via").load("<?=HOME?><?=APPID?>.ajax/paid_via",{KD_PELUNASAN:1,idn:a});	
	}).dialog({modal:true, height:500,width:350, title : "Report Laporan Harian"});
}

function print(a,b)
{

	var url;
	var url2;
	url='<?=HOME?><?=APPID?>.print/print?no_req='+a+'&jn='+b;
	
	
	window.open(url, '_blank');
	
	
	return false;
}

function get_paid_via() {
	var kd_pelunasan = $("#kd_pelunasan").val();
	$("#paid_via").load("<?=HOME?><?=APPID?>.ajax/paid_via",{KD_PELUNASAN:kd_pelunasan});
}


function save_payment(a,b,c,d,e,f)
{
	var bankid=$('#via').val();
	var kd_pelunasan = $("#kd_pelunasan").val();
	var url="<?=HOME?><?=APPID?>.ajax/save_payment";
	
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
			$.post(url,{IDN: a,IDR:b, JENIS:c, BANK_ID:bankid, VIA:kd_pelunasan,EMKL:d,KOREKSI:e,JUM:f},function(data){	
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
			});
		}
	}
}


function search_payment(){
    var id_req = $("#id_req").val();
    var id_time = $("#id_time").val();
     var id_time2 = $("#id_time2").val();
    $("#l_pay").jqGrid('setGridParam',{url:"<?=HOME?><?=APPID?>/data?id_req="+id_req+"&id_time="+id_time+"&id_time2="+id_time2, datatype:"json"}).trigger("reloadGrid");
}



function create_excel(){
    var id_req = $("#id_req").val();
    var id_time = $("#id_time").val();
    $("#l_pay").jqGrid('setGridParam',{url:"<?=HOME?><?=APPID?>/data?idreq="+id_req, datatype:"json"}).trigger("reloadGrid");
}
function toexcel() {
	var id_req = $("#id_req").val();
    var id_time = $("#id_time").val();
    var id_time2 = $("#id_time2").val();
	var url 	= "<?=HOME?><?=APPID?>.toexcel2/toexcel2?id_time="+id_time+"&id_req="+id_req+"&id_time2="+id_time2;
	
	window.open(url, "_blank");		
}

</script>

<div class="content">
	<div class="main_side">
	<p>
	 <img src="<?=HOME?>images/crdc.png" style="vertical-align:middle">&nbsp;<b> <font color='#69b3e2' size='5px'>Report</font> </b>
	 <font color='#888b8d' size='5px'>
	 E-Materai
	 </font>
	 
	 </p>
	<p><br/>
	  </p>
	<p><br/>
	
	</p>
	<a href="#">
	<div class="tombol">
	   
	</div>
	</a>
	<table border="0">
		<tr>
			<td>Cari No. Peraturan</td>
			<td>:</td>
			<td><input type="text" id="id_req" name="id_req"/></td>
		</tr>
		<tr>
			<td>Tanggal</td>
			<td>:</td>
			<td><input type="text" id="id_time" name="id_time"/><?php echo " s/d "; ?><input type="text" id="id_time2" name="id_time2"/> <input type="button" onclick="search_payment()" value="Cari" /></td>
		</tr>
		<tr>
			<td><input type="button" onclick="toexcel()" value="Export to Excel" /></td>
			<td></td>
			<td></td>
		</tr>
	</table>
	<table id='l_pay' width="100%"></table> <div id='pg_l_pay'></div>
	
	<form id="mainform">
	<div id='pay'></div>
	
	</form>
	<br/>
	<br/>
	</div>
</div>