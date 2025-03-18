
<?php
	$n_ukk=$_GET['no_ukk'];
	$db=getDb();
	$q="SELECT TGL_KIRIM FROM LOG_EMAIL WHERE NO_UKK='$n_ukk' order by ID_EMAIL ASC";
	$no=1;
	$dr=$db->query($q);
	$row=$dr->getAll();
?>
<table class="grid-table2" border='0' cellpadding="1" cellspacing="1"  width="100%">
<tr>
	<th class="grid-header2">No</th>
	<th class="grid-header2" width="200px">Keterangan</th>
	<th class="grid-header2">Tanggal Kirim</th>
</tr>
<?php foreach($row as $rowt) {?>
<tr>
	<td ><?=$no?></td>
	<td width="200px">Email Sent</td>
	<td><?=$row['TGL_KIRIM']?></td>
</tr>
<?php}?>
</table>