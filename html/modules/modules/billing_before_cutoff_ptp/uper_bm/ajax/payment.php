<?php
	$no_uper=$_GET['iduper'];
	$no_ukk=$_GET['ukk'];
	$val=$_GET['val'];
	$tgh=$_GET['tgh'];
	$datenow = date('d-m-Y');
	
	$query="select count(*) as JUM from PETIKEMAS_CABANG.STG_PAYMENT@BARANG_PROD where trim(RECEIPT_NUMBER)=trim('".$no_uper."')";
	//print_r($query);die;
	$rs = $db->query($query);
	$row = $rs->fetchRow();
	$hasil=$row['JUM'];
	
	if($hasil>0) {	//sudah payment
		echo "<script>
				alert('Request / Nota ini sudah payment');
				ReloadPage();
			  </script>";
	}
?>
<script>
$(document).ready(function()
{
	$("#payment_date").datepicker({
		dateFormat: 'dd-mm-yy'               
	});
	
	$("#btnClose").click(function(){
		$('#pay').dialog('destroy').remove();
		$('#mainform').append('<div id="pay"></div>');
	});
});

function via()
{
	$('#vias').load("<?=HOME?>billing.uper_bm.ajax/via").dialog({modal:true, height:120,width:220, title : "Via Payment"});
}

function set_via(i)
{
	$('#via').val(i);
	$('#vias').dialog('destroy').remove();
	$('#mainform2').append('<div id="vias"></div>');
}
</script>
<table>
<tr>
	<td>No. Uper</td>
	<td> : </td>
	<td><b><?=$no_uper?></b></td>
</tr>
<tr>
	<td>No. UKK</td>
	<td> : </td>
	<td><b><?=$no_ukk?></b></td>
</tr>
<tr>
	<td>Valuta</td>
	<td> : </td>
	<td><b><?=$val?></b></td>
</tr>
<tr>
	<td>Tagihan</td>
	<td> : </td>
	<td><b><?=$tgh?></b></td>
</tr>
<!--<tr>
	<td>Paid Date</td>
	<td> : </td>
	<td>
		<input type="text" size="10" id="payment_date" name="payment_date" value=<?=$datenow?> ReadOnly />
	</td>
</tr>-->
<tr>
	<td>Paid Via</td>
	<td> : </td>
	<td>
		<!--<input type="text" size="10" id="via" onclick="via()"/>-->
		<select id="via">
<?php
	$db=getDb();
	$query="select RECEIPT_ACCOUNT from xpi2_ar_receipt_method_v@keudev where kode_cabang = 'JBI' AND CURRENCY_CODE='".$val."'";
	$rs = $db->query($query);
	while($row = $rs->fetchRow()) {
?>
		<option value="<?=$row['RECEIPT_ACCOUNT']?>"><?=$row['RECEIPT_ACCOUNT']?></option>
<?		
	}
?>		
		</select>
	</td>
</tr>
<tr>
	<td>&nbsp;
	</td>
</tr>
<tr>
	<td colspan="3" align="center">
		<!--<input type="button" id="btnClose" value="&nbsp;Close&nbsp;"/>&nbsp;-->
		<button id="btnClose">Close</button>&nbsp;
		<button onclick="save_payment('<?=$no_uper?>','<?=$no_ukk?>')">P A I D</button>
	</td>
</tr>

</table>
<form id="mainform2">
	<div id='vias'></div>
</form>