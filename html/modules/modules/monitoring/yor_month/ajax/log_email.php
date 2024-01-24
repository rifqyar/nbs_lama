<?php
	$n_ukk=$_GET['no_ukk'];
	$db=getDb();
	$q="SELECT TO_CHAR(TGL_KIRIM,'DD MON YYYY HH24:MI') TGL_KIRIM FROM LOG_EMAIL WHERE NO_UKK='$n_ukk' and MODUL='RBM' order by ID_EMAIL ASC";
	$dr=$db->query($q);
	$row=$dr->getAll();
	$no=1;
	
	$h="SELECT NM_KAPAL, VOYAGE_IN, VOYAGE_OUT FROM RBM_H WHERE NO_UKK='$n_ukk'";
	$hr=$db->query($h);
	$head=$hr->fetchRow();
?>
<font size="2px"><b>LOG EMAIL RBM CONFIRMATION</b></font>
<br>
<br>
<br>
<TABLE>
<tr>
	<tD><b>NO UKK</b></tD>
	<td> : </td>
	<td><?=$n_ukk?></td>
</tr>
<tr>
	<tD><b>Vessel</b></tD>
	<td> : </td>
	<td><?=$head['NM_KAPAL']?></td>
</tr>
<tr>
	<tD><b>Voyage</b></tD>
	<td> : </td>
	<td><?=$head['VOYAGE_IN']?> - <?=$head['VOYAGE_OUT']?></td>
</tr>
</TABLE>
<table class="grid-table" border='0' cellpadding="1" cellspacing="1"  width="100%">
<tr>
	<th class="grid-header">No</th>
	<th class="grid-header" width="100px">Keterangan</th>
	<th class="grid-header" width="100px">Tanggal Kirim</th>
</tr>
<?php 
foreach($row as $rowt) 
{?>
<tr>
	<td align="center"><?=$no?></td>
	<td width="200px">&nbsp Email Sent</td>
	<td align="center"><?=$rowt['TGL_KIRIM']?></td>
</tr>
<?php
$no++;
}?>
</table>