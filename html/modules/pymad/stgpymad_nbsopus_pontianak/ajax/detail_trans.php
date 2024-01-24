<?
$db=getdb();
$id=$_POST['AID'];
$query="select * from BILLING.PYMA_STAGING where trx_number = '$id'";
$getds=$db->query($query);
$getas=$getds->getAll();


?>

<p>

<table width="100%">
<tr>
	<th>No</th>
	<th>No Pranota</th>
	<th>Tgl Pranota</th>
	<th>Nama Pelanggan</th>
	<th>Currency</th>
	<th>Amount</th>
	<th>Status PYMAD</th>
	<th>Tipe Layanan</th>
	<th>Kegiatan</th>
</tr>
<?
	$i=1;
	foreach ($getas as $row)
	{
?>
<tr>
	<td><?=$i;?></td>
	<td><?=$row['TRX_NUMBER'];?></td>
	<td align="center"><?=$row['TRX_DATE'];?></td>
	<td><?=$row['CUSTOMER_NAME'];?></td>
	<td align="center"><?=$row['CURRENCY'];?></td>
	<td align="right"><?=number_format($row['AMOUNT']);?></td>
	<td align="center"><?=$row['CONFIRMATION_STATUS'];?></td>
	<td><?=$row['SERVICE_TYPE'];?></td>
	<td><?=$row['LINE_DESC'];?></td>

</tr>
<?
$i++;
}
	
?>
</table>