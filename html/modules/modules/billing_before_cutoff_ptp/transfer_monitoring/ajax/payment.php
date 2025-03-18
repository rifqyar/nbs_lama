<?php
	$db=getDb();
	$nota=$_GET['idn'];
	$req=$_GET['req'];
	$jenis=$_GET['ket'];
	$vessel=$_GET['vessel'];
	$voyin=$_GET['voyin'];
	$voyout=$_GET['voyout'];
	$total=number_format ( $_GET['total'] , 0 , "." ,"," );
	
	$query="select count(*) as JUM from TTH_NOTA_ALL2 where trim(NO_NOTA)=trim('".$nota."') and trim(NO_REQUEST)=trim('".$req."')";
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
function via()
{
	$('#vias').load("<?=HOME?>billing.paymentcash.ajax/via").dialog({modal:true, height:120,width:220, title : "Via Payment"});
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
	<td>No. Nota</td>
	<td> : </td>
	<td><b><?=$nota?></b></td>
</tr>
<tr>
	<td>No. Request</td>
	<td> : </td>
	<td><b><?=$req?></b></td>
</tr>
<tr>
	<td>Jenis Nota</td>
	<td> : </td>
	<td><b><?=$jenis?></b></td>
</tr>
<tr>
	<td>Vessel</td>
	<td> : </td>
	<td><b><?=$vessel?></b></td>
</tr>
<tr>
	<td>Voy In</td>
	<td> : </td>
	<td><b><?=$voyin?></b></td>
</tr>
<tr>
	<td>Voy Out</td>
	<td> : </td>
	<td><b><?=$voyout?></b></td>
</tr>
<tr>
	<td>CMS</td>
	<td> : </td>
	<td><select id='cms'>
		<option value='0'>Non CMS</option>
		<option value='1'>CMS</option>
		</select></td>
</tr>
<tr>
	<td>Bayar Melalui</td>
	<td> : </td>
	<td><select id='kd_pelunasan' onchange="get_paid_via()">
		<option selected="selected" value="0">--- PILIH ---</option>
		<option value="1">BANK</option>
		<option value="2">CASH</option>
		<option value="3">AUTODB</option>
		<option value="4">DEPOSIT</option>
		</select>
	</td>
</tr>
<tr>
	<td>Paid Via</td>
	<td> : </td>
	<td>
		<!--<input type="text" size="15" id="via" onclick="via()"/>-->
		<div id="paid_via"></div>
<!-- 		<select id="via">
<?php
	$query="select BANK_ACCOUNT_ID, RECEIPT_ACCOUNT from xpi2_ar_receipt_method_v where kode_cabang = 'TPK' AND CURRENCY_CODE='IDR'";
	$rs = $db->query($query);
	while($row = $rs->fetchRow()) {
?>
		<option value="<?=$row['BANK_ACCOUNT_ID']?>"><?=$row['RECEIPT_ACCOUNT']?></option>
<?		
	}
?>		
		</select> -->
	</td>
</tr>
<tr>
	<td>Total</td>
	<td> : </td>
	<td>
		Rp. <?=$total?>
	</td>
</tr>

<tr>
	<td>&nbsp;
	</td>
</tr>
<tr>
	<td></td>
	<td></td>
	<td>
		<button onclick="save_payment('<?=$nota?>','<?=$req?>','<?=$jenis?>','<?=$vessel?>','<?=$voyin?>','<?=$voyout?>')"> P A I D</button>
	</td>
</tr>

</table>
<form id="mainform2">
	<div id='vias'></div>
	
	</form>