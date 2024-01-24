<?php
	$db=getDb("storage");
	$nota=$_GET['idn'];
	$mti_nota=$_GET['mti'];
	$req=$_GET['req'];
	$jenis=$_GET['ket'];
	$emkl=$_GET['emkl'];
	$tgl=$_GET['tgl'];
	$koreksi=$_GET['koreksi'];
	$status='Y';
	$total=number_format ( $_GET['total'] , 0 , "." ,"," );
    $jum = $_GET['total'];
    
   // $no_mat=$_GET['NO_PERATURAN']; 25 nov 2020
    $mat="SELECT * FROM MASTER_MATERAI where STATUS='".$status."'";
		
		$mat2 = $db->query($mat);
		$mat3	= $mat2->fetchRow();
		$no_matx    = $mat3['NO_PERATURAN'];





	
	$query="select count(*) as JUM from itpk_nota_header where trim(trx_number)=trim('".$nota."') and trim(NO_REQUEST)=trim('".$req."')";
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


	if ($jum>5000000) {
			$no_mat=$no_matx;
	}else{
			$no_mat='';
	}
	// 
?>
<script>
function via()
{
	$('#vias').load("<?=HOME?><?=APPID?>.ajax/paid_via").dialog({modal:true, height:120,width:220, title : "Via Payment"});
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
	<td>No. Proforma</td>
	<td> : </td>
	<td><b><?=$mti_nota?></b></td>
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
	<td>Bayar Melalui</td>
	<td> : </td>
	<td><select id='kd_pelunasan' onchange="get_paid_via()">
		<!-- <option selected="selected" value="0">--- PILIH ---</option> -->
		<option value="BANK">BANK</option>
		<!--<option value="CASH">CASH</option> -->
		<option value="AUTODB">AUTODB</option>
		<option value="DEPOSIT">DEPOSIT</option>
		</select>
	</td>
</tr>
<tr>
	<td>Paid Via</td>
	<td> : </td>
	<td>
		<div id="paid_via"></div>
	</td>
</tr>
<tr>
	<td>No. Peraturan</td>
	<td> : </td>
	<td>
	 <?=$no_mat?>
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
		<button onclick="save_payment('<?=$nota?>','<?=$req?>','<?=$jenis?>','<?=$emkl?>','<?=$koreksi?>','<?=$jum?>','<?=$mti_nota?>','<?=$no_mat?>')"> P A I D</button>
	</td>
</tr>

</table>
<form id="mainform2">
	<div id='vias'></div>
	
	</form>