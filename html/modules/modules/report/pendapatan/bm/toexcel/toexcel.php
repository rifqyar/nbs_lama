<?php
$tanggal=date("dmY");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=ReportYard-".$tanggal.".xls");
header("Pragma: no-cache");
header("Expires: 0");

$db = getDB();
$kategori	= $_GET["kategori"]; 
$id_vessel	= $_GET["id_vessel"]; 
//echo $tgl_awal;die;

if ($kategori == 0){
         $query = "SELECT ID_BLOK, NAMA_BLOK, TOTAL_KAPASITAS, NVL(SUM(JML_TERALOKASI),0) TOTAL_ALOKASI, (TOTAL_KAPASITAS-SUM(JML_TERALOKASI)) BLM_TERALOKASI, SUM(JML_PLACEMENT) TOTAL_PLACEMENT, SUM(JML_CETAK_JOBSLIP) TOTAL_CETAK_JOBSLIP FROM REPORT_YARD
					WHERE TGL_UPDATE = (SELECT(MAX(TGL_UPDATE)) FROM REPORT_YARD) GROUP BY ID_BLOK, NAMA_BLOK, TOTAL_KAPASITAS";
		 $result = $db->query($query);
		 $row = $result->getAll();
		 $query2 = "SELECT SUM(TOTAL_KAPASITAS) GRAND_KAPASITAS, NVL(SUM(JML_TERALOKASI),0) GRAND_TOTAL_ALOKASI, (SUM(TOTAL_KAPASITAS)-SUM(JML_TERALOKASI)) GRAND_BLM_TERALOKASI, SUM(JML_PLACEMENT) GRAND_PLACEMENT, SUM(JML_CETAK_JOBSLIP) GRAND_TOTAL_CETAK_JOBSLIP FROM REPORT_YARD
                    WHERE TGL_UPDATE = (SELECT(MAX(TGL_UPDATE)) FROM REPORT_YARD)";
		 $result2 = $db->query($query2);
		 $row2 = $result2->getAll();
		 
		 $query4	= "SELECT * FROM REPORT_YARD_VESSEL_D WHERE TGL_UPDATE = (SELECT(MAX(TGL_UPDATE)) FROM REPORT_YARD)";
		 $result4 	= $db->query($query4);
		 $detail_		= $result4->getAll();
?>
	<div id="list">
	<table id="zebra" border="1" bordercolor="#FFE4C4" width="100%" style="border-collapse:collapse" align="center">
	  <tr >
		<th bgcolor='#FFE4C4' height="20" width="5">NO</th>
		<th bgcolor='#FFE4C4' height="20" width="40">NAMA BLOK</th>
        <th bgcolor='#FFE4C4'height="20" width="5">TOTAL KAPASITAS</th> 
		<th bgcolor='#FFE4C4' height="20" width="100">VESSEL</th>
        <th bgcolor='#FFE4C4' height="20" width="5">VOYAGE</th> 		
		<th  bgcolor='#FFE4C4' height="20" width="20">JML TERALOKASI</th>        
		<th bgcolor='#FFE4C4' height="20" width="20">TOTAL ALOKASI</th>
		<th bgcolor='#FFE4C4' height="20" width="20">TOTAL BLM ALOKASI</th>
		<th bgcolor='#FFE4C4' height="20" width="20">JML GATE IN</th>        
		<th bgcolor='#FFE4C4' height="20" width="20">TOTAL GATE IN</th>
		<th bgcolor='#FFE4C4' height="20" width="20">JML PLACEMENT</th>        
		<th bgcolor='#FFE4C4' height="20" width="20">TOTAL PLACEMENT</th>
     </tr>
	  <?php $i=1;?>
	<? foreach ($row as $rows) {?>
	 <tr style="cursor:pointer;" onMouseOver="red" onMouseOut="#FFE4C4" onclick="fill('{$rows.ID_BLOK}')">
		<td class="grid-cell" align ="center" valign="top" style="font-size:10;color:#3300CC"><?=$i;?></td>
		<td class="grid-cell" valign="top" style="font-size:10;color:#3300CC"><b><?=$rows['NAMA_BLOK']?></b></td>
		<td class="grid-cell" align ="center" valign="top" style="font-size:10;color:#3300CC"><?=$rows['TOTAL_KAPASITAS']?> BOX</td>
		<td colspan="9"></td>
	</tr>
	<?	
		$id_blok_ = $rows['ID_BLOK'];
			 $query = "SELECT VESSEL, VOYAGE, JML_TERALOKASI, JML_CETAK_JOBSLIP, JML_PLACEMENT FROM REPORT_YARD WHERE 
			 ID_BLOK = '$id_blok_'
			AND TGL_UPDATE = (SELECT(MAX(TGL_UPDATE)) FROM REPORT_YARD)";
		$result = $db->query($query);
	    $detail = $result->getAll();
		
		foreach ($detail as $row){
	?>
	<tr>
		<td></td><td colspan='2'></td>
		<td class="grid-cell" align ="left" valign="top" style="font-size:10;color:#3300CC"><?=$row['VESSEL']?></td>
		<td class="grid-cell" align ="center" valign="top" style="font-size:10;color:#3300CC"><?=$row['VOYAGE']?></td>
		<td class="grid-cell" align ="center" valign="top" style="font-size:10;color:#3300CC"><?=$row['JML_TERALOKASI']?> BOX</td>
		<td></td>
		<td></td>
		<td class="grid-cell" align ="center" valign="top" style="font-size:10;color:#3300CC"><?=$row['JML_CETAK_JOBSLIP']?> BOX</td>
		<td></td>
		<td class="grid-cell" align ="center" valign="top" style="font-size:10;color:#3300CC"><?=$row['JML_PLACEMENT']?> BOX</td>
		<td></td>
	</tr>
	<? } ?>
	<tr>
		<td bgcolor='#FFE4C4'></td><td bgcolor='#FFE4C4' colspan="5" class="grid-cell" align ="right" valign="top" style="font-size:12;color:red"><b> Total Per Block</b></td>
		<td bgcolor='#FFE4C4' class="grid-cell" align ="center" valign="top" style="font-size:12;color:red"><b><?=$rows['TOTAL_ALOKASI']?> BOX</b></td>
		<td bgcolor='#FFE4C4' class="grid-cell" align ="center" valign="top" style="font-size:12;color:red"><b><?=$rows['BLM_TERALOKASI']?> BOX</b></td>
		<td bgcolor='#FFE4C4'></td>
		<td bgcolor='#FFE4C4' class="grid-cell" align ="center" valign="top" style="font-size:12;color:red"><b><?=$rows['TOTAL_CETAK_JOBSLIP']?> BOX</b></td>
		<td bgcolor='#FFE4C4'></td>
		<td bgcolor='#FFE4C4' class="grid-cell" align ="center" valign="top" style="font-size:12;color:red"><b><?=$rows['TOTAL_PLACEMENT']?> BOX</b></td>
	</tr>
	<? $i++;?>	
	<? }
	
	foreach ($row2 as $row_){
	?>
	
	<tr>
		<td bgcolor='#FFE4C4' class="grid-cell" align ="center" valign="top" style="font-size:12;color:red" colspan='2'><b> GRAND TOTAL</b> </td>
		<td bgcolor='#FFE4C4' class="grid-cell" align ="center" valign="top" style="font-size:12;color:red"><b><?=$row_['GRAND_KAPASITAS']?> BOX</b></td>
		<td bgcolor='#FFE4C4' colspan='3'></td>
		<td bgcolor='#FFE4C4' class="grid-cell" align ="center" valign="top" style="font-size:12;color:red"><b><?=$row_['GRAND_TOTAL_ALOKASI']?> BOX</b></td>
		<td bgcolor='#FFE4C4' class="grid-cell" align ="center" valign="top" style="font-size:12;color:red"><b><?=$row_['GRAND_BLM_TERALOKASI']?> BOX</b></td>
		<td bgcolor='#FFE4C4'></td>
		<td bgcolor='#FFE4C4' class="grid-cell" align ="center" valign="top" style="font-size:12;color:red"><b><?=$row_['GRAND_TOTAL_CETAK_JOBSLIP']?> BOX</b></td>
		<td bgcolor='#FFE4C4'></td>
		<td bgcolor='#FFE4C4' class="grid-cell" align ="center" valign="top" style="font-size:12;color:red"><b><?=$row_['GRAND_PLACEMENT']?> BOX</b></td>
	</tr>
	<? } ?>
	</table>
	
	<table>
	<tr height="50">
		<td>
		</td>
	</tr>
	</table>
	
	<table id="zebra" border="1" bordercolor="#FFE4C4" width="100%" style="border-collapse:collapse" align="center">
                              <tr style=" font-size:10pt">
                                  <th bgcolor='#FFE4C4'  valign="top" class="grid-header"  style="font-size:10pt">No </th>
                                  <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:10pt">No.Container</th>
								   <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:10pt">Size</th>
								    <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:10pt">Type</th>
                                  <th bgcolor='#FFE4C4'  valign="top" class="grid-header"  style="font-size:10pt">Status</th>
                                  <th bgcolor='#FFE4C4'  valign="top" class="grid-header"  style="font-size:10pt">Vessel</th> 
                                  <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:10pt">Voyage</th>
                                  <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:10pt">Berat</th>
								   <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:10pt">Pel Asal</th>
								    <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:10pt">Pel Tujuan</th>
									 <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:10pt">No Polisi</th>
                                  <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:10pt">Blok Planning</th> 
								  <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:10pt">SLot Planning</th>
								  <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:10pt">Row Planning</th>
								  <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:10pt">Tier Planning</th>
								   <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:10pt">Blok Placement</th> 
								  <th bgcolor='#FFE4C4'  valign="top" class="grid-header"  style="font-size:10pt">SLot Placement</th>
								  <th bgcolor='#FFE4C4'  valign="top" class="grid-header"  style="font-size:10pt">Row Placement</th>
								  <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:10pt">Tier Placement</th>
								  <th bgcolor='#FFE4C4'  valign="top" class="grid-header"  style="font-size:10pt">Tgl Gate In</th>
								  <th bgcolor='#FFE4C4'  valign="top" class="grid-header"  style="font-size:10pt">Tgl Placement</th>
								  <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:10pt">Durasi Gate-Placement</th>
								  <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:10pt">Keterangan</th>
                              <?php $i=0; 
							  foreach($detail_ as $rows_){ $i++;?>
                              <tr bgcolor="#f9f9f3" onMouseOver=this.style.backgroundColor="#BAD5FC" onMouseOut=this.style.backgroundColor="">
                                  <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000;  font-size:9pt"><?php echo $i; ?></td>
                                  <td width="22%" align="center" valign="middle" class="grid-cell"   style=" font-size:11pt; color:#555555"><b><?php echo $rows_["NO_CONTAINER"]; ?></b></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["SIZE_"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["TYPE_"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["STATUS"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["VESSEL"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["VOYAGE"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["BERAT"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["PELABUHAN_ASAL"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["PELABUHAN_TUJUAN"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["NO_POLISI"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["BLOK_PLANNING"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["SLOT_PLANNING"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["ROW_PLANNING"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["TIER_PLANNING"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["BLOK_REALISASI"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["SLOT_REALISASI"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["ROW_REALISASI"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["TIER_REALISASI"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["TGL_GATE_IN"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["TGL_PLACEMENT"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["DURASI"]; ?></td>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt; color:red"">
								  <?php if (($rows_["BLOK_PLANNING"] == $rows_["BLOK_REALISASI"]) AND ($rows_["SLOT_PLANNING"] == $rows_["SLOT_REALISASI"]) AND ($rows_["ROW_PLANNING"] == $rows_["ROW_REALISASI"]) AND ($rows_["TIER_PLANNING"] == $rows_["TIER_REALISASI"])) {
								?> <i> Data Match </i>
								<? } else {?>
								<i> Data not Match </i>
								<?}?></td>
							<tr>
							<? }?>
        </table>
	
	</div>

<?
} else {
	     $query = "SELECT ID_BOOK, SIZE_, TIPE, STATUS, KATEGORI FROM REPORT_YARD_VESSEL WHERE ID_VS = '$id_vessel' AND
		 TGL_UPDATE = (SELECT(MAX(TGL_UPDATE)) FROM REPORT_YARD)
		 GROUP BY ID_BOOK, SIZE_, TIPE, STATUS, KATEGORI";
		 $result = $db->query($query);
		 $row = $result->getAll();
		 
		 $query4	= "SELECT * FROM REPORT_YARD_VESSEL_D WHERE ID_VS = '$id_vessel' AND TGL_UPDATE = (SELECT(MAX(TGL_UPDATE)) FROM REPORT_YARD)";
		 $result4 	= $db->query($query4);
		 $detail_		= $result4->getAll();
?>
	<div id="list">
	<table id="zebra" border="1" bordercolor="#FFFFFF" width="100%" style="border-collapse:collapse" align="center">
	  <tr>
		<th bgcolor='#FFE4C4' rowspan="2" height="20" width="5">NO</td>
		<th  bgcolor='#FFE4C4'colspan="4" height="20" width="20">DATA BOOKING</th>
        <th bgcolor='#FFE4C4' colspan="4" height="20" width="10">ALOKASI LAPANGAN</th>   
		<th bgcolor='#FFE4C4' colspan="2" height="20" width="10">JML BOOKING</th> 
		<th bgcolor='#FFE4C4' colspan="2" height="20" width="10">JML <br> ALOKASI</th> 
		<th bgcolor='#FFE4C4' colspan="2" height="20" width="10">JML <br> GATE IN</th> 
		<th bgcolor='#FFE4C4' colspan="2" height="20" width="10">JML <br> PLACEMENT</th> 
     </tr>
	 <tr>
		<th bgcolor='#FFE4C4' height="20" width="20">SIZE</th>
		<th bgcolor='#FFE4C4' height="20" width="20">TYPE</th>
		<th bgcolor='#FFE4C4' height="20" width="20">STATUS</th>
        <th bgcolor='#FFE4C4' height="20" width="10">KATEGORI BERAT</th>   
		<th bgcolor='#FFE4C4' height="20" width="10">BLOK</th>       
		<th bgcolor='#FFE4C4' height="20" width="10">JML SLOT</th> 
		<th bgcolor='#FFE4C4' height="20" width="10">JML ROW</th>  
		<th bgcolor='#FFE4C4' height="20" width="10">TIER</th>  
		<th bgcolor='#FFE4C4' height="20" width="10">BOX</th>
		<th bgcolor='#FFE4C4' height="20" width="10">TEUS</th>
		<th bgcolor='#FFE4C4' height="20" width="10">BOX</th>
		<th bgcolor='#FFE4C4' height="20" width="10">TEUS</th>
		<th bgcolor='#FFE4C4' height="20" width="10">BOX</th>
		<th bgcolor='#FFE4C4' height="20" width="10">TEUS</th>
		<th bgcolor='#FFE4C4' height="20" width="10">BOX</th>
		<th bgcolor='#FFE4C4' height="20" width="10">TEUS</th>
	 </tr>	
	  <?php $i=1;
	  foreach ($row as $rows) {?>
	
	 <tr  style="cursor:pointer;" onclick="fill_book('{$rows.ID_BOOK}','{$kategori}','{$vessel}')">
		<td class="grid-cell" align ="center" valign="top" style="font-size:10;color:#3300CC"><?=$i;?></td>
		<td class="grid-cell"  align ="center" valign="center" style="font-size:10;color:#3300CC"><?=$rows['SIZE_']?></td>
		<td class="grid-cell" align ="center" valign="top" style="font-size:10;color:#3300CC"><?=$rows['TIPE']?></td>
		<td class="grid-cell" align ="center" valign="top" style="font-size:10;color:#3300CC"><?=$rows['STATUS']?></td>
		<td class="grid-cell" align ="center" valign="top" style="font-size:10;color:#3300CC"><?=$rows['KATEGORI']?></td>
		<td colspan='12'></td>
	</tr>
	<?	
		$db 	= getDB();
		$id_book_ = $rows['ID_BOOK'];
		$query = "select a.NAME, SUM(DISTINCT(b.JML_SLOT)) JML_SLOT,SUM(DISTINCT(b.JML_ROW)) JML_ROW, b.JML_TIER, SUM(b.JML_BOOKING) JML_BOOKING, SUM(b.JML_ALOKASI) JML_ALOKASI, SUM(b.JML_CETAK_JOBSLIP) JML_CETAK_JOBSLIP, SUM(b.JML_PLACEMENT) JML_PLACEMENT, SUM(b.JML_BOOKING_TEUS) JML_BOOKING_TEUS, SUM(b.JML_ALOKASI_TEUS) JML_ALOKASI_TEUS, SUM(b.JML_PLACEMENT_TEUS) JML_PLACEMENT_TEUS, SUM(b.JML_JOBSLIP_TEUS) JML_JOBSLIP_TEUS
						 fROM report_yard_vessel b, yd_blocking_area a
						 where b.ID_VS = '$id_vessel'
						 AND a.ID = b.ID_BLOCKING_AREA
						 AND b.ID_BOOK = '$id_book_'
						 AND b.TGL_UPDATE = (SELECT(MAX(TGL_UPDATE)) FROM REPORT_YARD)
						 GROUP BY a.NAME, b.JML_SLOT,b.JML_ROW, b.JML_TIER, b.JML_BOOKING, b.JML_ALOKASI, b.JML_CETAK_JOBSLIP, b.JML_PLACEMENT, b.JML_BOOKING_TEUS, b.JML_ALOKASI_TEUS";
		$result = $db->query($query);
	    $detail = $result->getAll();
		
		foreach ($detail as $row){
	?>
	<tr>
		<td colspan='5'></td>
		<td class="grid-cell"  align ="center" valign="top" style="font-size:10;color:#3300CC"><?=$row['NAME']?></td>
		<td class="grid-cell" align ="center" valign="top" style="font-size:10;color:#3300CC"><?=$row['JML_SLOT']?></td>
		<td class="grid-cell" align ="center" valign="top" style="font-size:10;color:#3300CC"><?=$row['JML_ROW']?></td>
		<td class="grid-cell" align ="center" valign="top" style="font-size:10;color:#3300CC"><?=$row['JML_TIER']?></td>
		<td class="grid-cell"  align ="center" valign="top" style="font-size:10;color:#3300CC" height="20" width="10"><?=$row['JML_BOOKING']?></td>
		<td  class="grid-cell"  align ="center" valign="top" style="font-size:10;color:#3300CC"height="20" width="10"><?=$row['JML_BOOKING_TEUS']?></td>
		<td  class="grid-cell"  align ="center" valign="top" style="font-size:10;color:#3300CC"height="20" width="10"><?=$row['JML_ALOKASI']?></td>
		<td class="grid-cell"  align ="center" valign="top" style="font-size:10;color:#3300CC" height="20" width="10"><?=$row['JML_ALOKASI_TEUS']?></td>
		<td class="grid-cell"  align ="center" valign="top" style="font-size:10;color:#3300CC" height="20" width="10"><?=$row['JML_CETAK_JOBSLIP']?></td>
		<td class="grid-cell"  align ="center" valign="top" style="font-size:10;color:#3300CC" height="20" width="10"><?=$row['JML_JOBSLIP_TEUS']?></td>
		<td class="grid-cell"  align ="center" valign="top" style="font-size:10;color:#3300CC" height="20" width="10"><?=$row['JML_PLACEMENT']?></td>
		<td class="grid-cell"  align ="center" valign="top" style="font-size:10;color:#3300CC" height="20" width="10"><?=$row['JML_PLACEMENT_TEUS']?></td>
	</tr>
	<?}?>
	<?	
		$db 	= getDB();
		$id_book_ = $rows['ID_BOOK'];
			$query2 = "select SUM(JML_BOOKING) JML_BOOKING, SUM(JML_ALOKASI) JML_ALOKASI, SUM(JML_CETAK_JOBSLIP) JML_CETAK_JOBSLIP, SUM(JML_PLACEMENT) JML_PLACEMENT, SUM(JML_BOOKING_TEUS) JML_BOOKING_TEUS, SUM(JML_ALOKASI_TEUS) JML_ALOKASI_TEUS, SUM(JML_PLACEMENT_TEUS) JML_PLACEMENT_TEUS, SUM(JML_JOBSLIP_TEUS) JML_JOBSLIP_TEUS
						 fROM report_yard_vessel
						 where ID_VS = '$id_vessel'
						 AND ID_BOOK = '$id_book_'
						 AND TGL_UPDATE = (SELECT(MAX(TGL_UPDATE)) FROM REPORT_YARD)
						 GROUP BY JML_BOOKING, JML_ALOKASI, JML_CETAK_JOBSLIP, JML_PLACEMENT, JML_BOOKING_TEUS, JML_ALOKASI_TEUS";
	
		$result2 = $db->query($query2);
	    $detail2 = $result2->getAll();
		
		foreach ($detail2 as $row2){
	?>
	<tr>
		<td bgcolor='#FFE4C4' class="grid-cell"  align ="right" valign="top" style="font-size:10;color:red" colspan='9'><b>TOTAL PER KATEGORI <b></td>
		<td  bgcolor='#FFE4C4' class="grid-cell"  align ="center" valign="top" style="font-size:10;color:#3300CC" height="20" width="10"><?=$row2['JML_BOOKING']?></td>
		<td bgcolor='#FFE4C4'  class="grid-cell"  align ="center" valign="top" style="font-size:10;color:#3300CC"height="20" width="10"><?=$row2['JML_BOOKING_TEUS']?></td>
		<td  bgcolor='#FFE4C4' class="grid-cell"  align ="center" valign="top" style="font-size:10;color:#3300CC"height="20" width="10"><?=$row2['JML_ALOKASI']?></td>
		<td bgcolor='#FFE4C4' class="grid-cell"  align ="center" valign="top" style="font-size:10;color:#3300CC" height="20" width="10"><?=$row2['JML_ALOKASI_TEUS']?></td>
		<td bgcolor='#FFE4C4' class="grid-cell"  align ="center" valign="top" style="font-size:10;color:#3300CC" height="20" width="10"><?=$row2['JML_CETAK_JOBSLIP']?></td>
		<td bgcolor='#FFE4C4' class="grid-cell"  align ="center" valign="top" style="font-size:10;color:#3300CC" height="20" width="10"><?=$row2['JML_JOBSLIP_TEUS']?></td>
		<td bgcolor='#FFE4C4' class="grid-cell"  align ="center" valign="top" style="font-size:10;color:#3300CC" height="20" width="10"><?=$row2['JML_PLACEMENT']?></td>
		<td bgcolor='#FFE4C4' class="grid-cell"  align ="center" valign="top" style="font-size:10;color:#3300CC" height="20" width="10"><?=$row2['JML_PLACEMENT_TEUS']?></td>
	</tr>
	<?}?>
	<? $i++;?>	
	<?}?>
	</table>
	
	<table>
	<tr height="50">
		<td>
		</td>
	</tr>
	</table>
	
	<table id="zebra" border="1" bordercolor="#FFE4C4" width="100%" style="border-collapse:collapse" align="center">
                              <tr style=" font-size:10pt">
                                  <th bgcolor='#FFE4C4'  valign="top" class="grid-header"  style="font-size:10pt">No </th>
                                  <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:10pt">No.Container</th>
								   <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:10pt">Size</th>
								    <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:10pt">Type</th>
                                  <th bgcolor='#FFE4C4'  valign="top" class="grid-header"  style="font-size:10pt">Status</th>
                                  <th bgcolor='#FFE4C4'  valign="top" class="grid-header"  style="font-size:10pt">Vessel</th> 
                                  <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:10pt">Voyage</th>
                                  <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:10pt">Berat</th>
								   <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:10pt">Pel Asal</th>
								    <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:10pt">Pel Tujuan</th>
									 <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:10pt">No Polisi</th>
                                  <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:10pt">Blok Planning</th> 
								  <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:10pt">SLot Planning</th>
								  <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:10pt">Row Planning</th>
								  <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:10pt">Tier Planning</th>
								   <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:10pt">Blok Placement</th> 
								  <th bgcolor='#FFE4C4'  valign="top" class="grid-header"  style="font-size:10pt">SLot Placement</th>
								  <th bgcolor='#FFE4C4'  valign="top" class="grid-header"  style="font-size:10pt">Row Placement</th>
								  <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:10pt">Tier Placement</th>
								  <th bgcolor='#FFE4C4'  valign="top" class="grid-header"  style="font-size:10pt">Tgl Gate In</th>
								  <th bgcolor='#FFE4C4'  valign="top" class="grid-header"  style="font-size:10pt">Tgl Placement</th>
								  <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:10pt">Durasi Gate-Placement</th>
								  <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:10pt">Keterangan</th>
                              <?php $i=0; 
							  foreach($detail_ as $rows_){ $i++;?>
                              <tr bgcolor="#f9f9f3" onMouseOver=this.style.backgroundColor="#BAD5FC" onMouseOut=this.style.backgroundColor="">
                                  <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000;  font-size:9pt"><?php echo $i; ?></td>
                                  <td width="22%" align="center" valign="middle" class="grid-cell"   style=" font-size:11pt; color:#555555"><b><?php echo $rows_["NO_CONTAINER"]; ?></b></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["SIZE_"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["TYPE_"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["STATUS"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["VESSEL"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["VOYAGE"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["BERAT"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["PELABUHAN_ASAL"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["PELABUHAN_TUJUAN"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["NO_POLISI"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["BLOK_PLANNING"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["SLOT_PLANNING"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["ROW_PLANNING"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["TIER_PLANNING"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["BLOK_REALISASI"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["SLOT_REALISASI"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["ROW_REALISASI"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["TIER_REALISASI"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["TGL_GATE_IN"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["TGL_PLACEMENT"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows_["DURASI"]; ?></td>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt; color:red"">
								  <?php if (($rows_["BLOK_PLANNING"] == $rows_["BLOK_REALISASI"]) AND ($rows_["SLOT_PLANNING"] == $rows_["SLOT_REALISASI"]) AND ($rows_["ROW_PLANNING"] == $rows_["ROW_REALISASI"]) AND ($rows_["TIER_PLANNING"] == $rows_["TIER_REALISASI"])) {
								?> <i> Data Match </i>
								<? } else {?>
								<i> Data not Match </i>
								<?}?></td>
							<tr>
							<? }?>
        </table>
</div>
<?}?>

	
	