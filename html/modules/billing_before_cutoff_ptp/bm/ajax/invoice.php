<?
$pr=$_GET['id'];
$db=getDb();
$qrt="select a.NO_PRANOTA, a.NO_UKK,case when a.OI='O' then 'Ocean Going' when a.OI='I' THEN'Interinsuler' end OI,   
		a.NO_UPER, to_char(a.TOTAL,'999,999,999,999.00') TOTAL, 
		TO_CHAR(a.TOTAL_UPER,'999,999,999,999.00') TOTAL_UPER,
		TO_CHAR((a.TOTAL-a.TOTAL_UPER),'999,999,999,999.00') SISA,
		b.NM_KAPAL,b.VOYAGE_IN, b.VOYAGE_OUT, b.RTA,b.RTD, b.NM_PEMILIK
		from RBM_H_PR a left join RBM_H b on a.NO_UKK=b.NO_UKK where a.NO_PRANOTA='$pr'";
$iqrt=$db->query($qrt);
$hqrt=$iqrt->fetchRow();

?>
<table>
<tr>
	<td>No. Pranota</td>
	<td> : </td>
	<td><?=$hqrt['NO_PRANOTA']?></td>
</tr>
<tr>
	<td>No. Uper</td>
	<td> : </td>
	<td><?=$hqrt['NO_UPER']?></td>
</tr>
<tr>
	<td>No. UKK</td>
	<td> : </td>
	<td><?=$hqrt['NO_UKK']?></td>
</tr>
<tr>
	<td>Shipping</td>
	<td> : </td>
	<td><?=$hqrt['OI']?></td>
</tr>
<tr>
	<td>Shipping Line</td>
	<td> : </td>
	<td><?=$hqrt['NM_PEMILIK']?></td>
</tr>
<tr>
	<td>Vessel - voy</td>
	<td> : </td>
	<td><?=$hqrt['VOYAGE_IN']?> -  <?=$hqrt['VOYAGE_OUT']?></td>
</tr>
<tr>
	<td>RTA - RTD</td>
	<td> : </td>
	<td><?=$hqrt['RTA']?> - <?=$hqrt['RTD']?></td>
</tr>
<tr>
	<td>Jumlah Tagihan</td>
	<td> : </td>
	<td> <?=$hqrt['TOTAL']?></td>
</tr>
<tr>
	<td>Jumlah Uper</td>
	<td> : </td>
	<td> <?=$hqrt['TOTAL_UPER']?></td>
</tr>
<tr>
	<td>Sisa Uper / Piutang</td>
	<td> : </td>
	<td> <?=$hqrt['SISA']?></td>
</tr>
<tr>
	<td></td>
</tr>
<tr>
	<td></td>
	<td></td>
	<td><button onclick="save_nota_fix('<?=$pr?>')"><img src="images/save_inv.png"></button></td>
</tr>
</table>