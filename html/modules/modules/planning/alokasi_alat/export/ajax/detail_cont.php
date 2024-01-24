<div id="cont_detail">
<?
	$id_vs = $_GET['vs'];
	$no_bay_area = $_GET['bay_area'];
	$posisi = $_GET['posisi'];
	$alat = $_GET['alat'];
?>
<br>
<h2>ALAT <?=$alat?></h2>
<br>
<table class="grid-table" border='0' cellpadding="1" cellspacing="1" width="100%">
	<tr>
		<th class="grid-header" width='20' align="center"><font size="2px"><b>NO</b></font></th>
	    <th class="grid-header" width='40' align="center"><font size="2px"><b>TUJUAN</b></font></th>
		<th class="grid-header" width='40' align="center"><font size="2px"><b>SZ/TY/ST</b></font></th>
		<th class="grid-header" width='40' align="center"><font size="2px"><b>HZ</b></font></th>
		<th class="grid-header" width='40' align="center"><font size="2px"><b>WEIGHT</b></font></th>
		<th class="grid-header" width='30' align="center"><font size="2px"><b>JUMLAH</b></font></th>
	</tr>
	<?				
		$db = getDB();
		$query_cell = "SELECT COUNT(*) AS JML,A.TUJUAN,A.SIZE_,A.TYPE_,A.STATUS_,A.HZ_,A.KATEGORI FROM STW_PLACEMENT_BAY A, STW_BAY_CELL B WHERE B.ID_BAY_AREA = '$no_bay_area' AND A.ID_CELL = B.ID AND A.STATUS_PLC = 'PLANNING' GROUP BY A.TUJUAN,A.SIZE_,A.TYPE_,A.STATUS_,A.HZ_,A.KATEGORI";
		$cell_ = $db->query($query_cell);
		$hasil_cell = $cell_->getAll();
	
		$z = 1;
		foreach ($hasil_cell as $row6)
		{
			$jml_cell[$z] = $row6['JML'];
			$tujuan_cell[$z] = $row6['TUJUAN'];
			$size_cell[$z] = $row6['SIZE_'];
			$type_cell[$z] = $row6['TYPE_'];
			$status_cell[$z] = $row6['STATUS_'];
			$hz_cell[$z] = $row6['HZ_'];
			$kategori_cell[$z] = $row6['KATEGORI'];
			
	?>						
	<tr>
		<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor="#12e0fa"><font size="1px"><?=$z;?></font></td>		
		<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='40' align="center" bgcolor="#12e0fa"><font size="1px"><?=$tujuan_cell[$z];?></font></td>
		<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='40' align="center" bgcolor="#12e0fa"><font size="1px"><?=$size_cell[$z];?>/<?=$type_cell[$z];?>/<?=$status_cell[$z];?></font></td>
		<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='40' align="center" bgcolor="#12e0fa"><font size="1px"><?=$hz_cell[$z];?></font></td>
		<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='40' align="center" bgcolor='#12e0fa'><font size="1px"><?=$kategori_cell[$z];?></font></td>
		<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='30' align="center" bgcolor="#12e0fa"><font size="1px"><?=$jml_cell[$z];?></font></td>
	</tr>
	<? $z++;
		} ?>						
	</table>
</div>