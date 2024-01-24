<?php
	$id_vsb=$_GET['id'];
	
	//echo $id_vsb;die;
	$db=getDb();
	$qdt="select operator_gr, id_rpstv, id_kategori,sts_rpstv, rp_date, name, vessel, voy_in, voy_out
	from bil_rpstv_h 
    join tb_user on bil_rpstv_h.user_id=tb_user.id where id_vsb_voyage='$id_vsb'";

	$rdt=$db->query($qdt);
	
	$gdt=$rdt->getAll();
?>
<script>
	function dfh(a)
	{
		window.open('<?=HOME;?>billing.rbm.ajax/print_rbm_final?id='+a,'_blank'); 
		// <- This is what makes it open in a new window.
	}
</script>
<style>
table
{
border-collapse:collapse;
}
table, td, th
{
border:1px solid #b3b2b1;
}
</style>
<p align="left"><b><i>Report Stevedoring Final</i></b></p>

<table border="1" >
	<tr>
		<th class="grid-header"><b>No.</b></th>
		<th class="grid-header" width=30><b>Content</b></th>
		<th class="grid-header" width=30><b>Report (ID)</b></th>
		<th class="grid-header" width=150><b>Vessel</b></th>
		<th class="grid-header" width=150><b>Operator</b></th>
		<th class="grid-header" width=100><b>Date Release (final)</b></th>
		<th class="grid-header" width=150><b>User Release (final)</b></th>
	</tr>
		
	<?php 
	$no=1;
	foreach($gdt as $row)
	{
		$kategori=$row['ID_KATEGORI'];
	?>
	<tr>
		<td align="center"><?=$no;?></td>
		<td align="center"><button onclick="dfh('<?=$row['ID_RPSTV'];?>')">detail</button></td>
		<td align="center"><?=$row['ID_RPSTV'];?></td>
		<td align="center"><?=$row['VESSEL'];?> <?=$row['VOY_IN'];?> - <?=$row['VOY_OUT'];?> [<?=$row['ID_KATEGORI'];?>]</td>
		<td align="center"><?=$row['OPERATOR_GR'];?></td>
		<td align="center"><?=$row['RP_DATE'];?></td>
		<td align="center"><?=$row['NAME'];?></td>
	</tr>
	<?php $no++;}?>
</table>