<?php
$tanggal=date("dmY");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=ReportYard-".$tanggal.".xls");
header("Pragma: no-cache");
header("Expires: 0");

 $db = getDB(); 
 
 $blok = $_POST['blok'];
 $id_vessel = $_POST['id_vessel'];
 $tgl_awal_gate = $_POST['tgl_awal_gate'];
 $tgl_akhir_gate = $_POST['tgl_akhir_gate'];
 $tgl_awal_place = $_POST['tgl_awal_place'];
 $tgl_akhir_place = $_POST['tgl_akhir_place'];

 if($blok != NULL){
	$query_blok = " AND ID_BLOCKING_AREA = '$blok'";
 } else {
	$query_blok = "";
 }
 
  if($id_vessel != NULL){
	$query_vessel = " AND ID_VS = '$id_vessel'";
 } else {
	$query_vessel = "";
 }
 
 if($tgl_awal_gate != NULL){
	$query_gate = " and to_date(tgl_gate_in,'dd/mm/rrrr') between to_date('$tgl_awal_gate', 'dd/mm/rrrr') and to_date('$tgl_akhir_gate', 'dd/mm/rrrr') ";
 } else {
	$query_gate = "";
 }
 
  if($tgl_awal_place != NULL){
	$query_place = " and to_date(tgl_placement,'dd/mm/rrrr') between to_date('$tgl_awal_place', 'dd/mm/rrrr') and to_date('$tgl_akhir_place', 'dd/mm/rrrr') ";
 } else {
	$query_place = "";
 }
 
 //   echo "SELECT * FROM REPORT_YARD_VESSEL_D WHERE TGL_UPDATE = (SELECT(MAX(TGL_UPDATE)) FROM REPORT_YARD_VESSEL_D)". $query_blok . $query_vessel . $query_gate . $query_place;die;
	$query = "SELECT * FROM REPORT_YARD_VESSEL_D WHERE TGL_UPDATE = (SELECT(MAX(TGL_UPDATE)) FROM REPORT_YARD_VESSEL_D)". $query_blok . $query_vessel . $query_gate . $query_place;
    $result = $db->query($query);
	$row = $result->getAll();
?>
<div id="list" align="center">
<table id="zebra" border="1" bordercolor="#FFE4C4" width="100%" style="border-collapse:collapse" align="center">
                              <tr style=" font-size:8pt">
                                  <th bgcolor='#FFE4C4'  valign="top" class="grid-header"  style="font-size:8pt">No </th>
                                  <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:8pt">No.Container</th>
								   <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:8pt">Size</th>
								   <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:8pt">Type</th>
								   <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:8pt">Status</th>
                                  <th bgcolor='#FFE4C4'  valign="top" class="grid-header"  style="font-size:8pt">Vessel</th>
								<th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:8pt">Voyage</th>
                                  <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:8pt">Berat</th>
								   <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:8pt">Pelabuhan Asal</th>
								   <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:8pt">Tujuan</th>
									 <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:8pt">No Polisi</th>
                                  <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:8pt">Lokasi Planning (Blok-S-R-T)</th> 
								   <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:8pt">Lokasi Placement (Blok-S-R-T)</th> 
								  <th bgcolor='#FFE4C4'  valign="top" class="grid-header"  style="font-size:8pt">Tgl Gate In</th>
								  <th bgcolor='#FFE4C4'  valign="top" class="grid-header"  style="font-size:8pt">Tgl Placement</th>
								  <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:8pt">Durasi Gate-Placement</th>
                              <?php $i=1; foreach($row as $rows){?>
                              <tr bgcolor="#f9f9f3" onMouseOver=this.style.backgroundColor="#BAD5FC" onMouseOut=this.style.backgroundColor="">
                                  <td width="1%" align="center" valign="middle" class="grid-cell" style="color:#000;  font-size:8pt"><?php echo $i; ?></td>
                                  <td width="22%" align="center" valign="middle" class="grid-cell"   style=" font-size:10pt; color:#555555"><b><?=$rows['NO_CONTAINER']?></b></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:8pt"><?=$rows['SIZE_']?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:8pt"><?=$rows['TYPE_']?></td>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:8pt"><?=$rows['STATUS']?></td>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:8pt"><?=$rows['VESSEL']?></td>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:8pt"><?=$rows['VOYAGE']?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:8pt"><?=$rows['BERAT']?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:8pt"><?=$rows['PELABUHAN_ASAL']?> </td>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:8pt"><?=$rows['PELABUHAN_TUJUAN']?> </td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:8pt"><?=$rows['NO_POLISI']?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:8pt"><?=$rows['BLOK_PLANNING']?>-<?=$rows['SLOT_PLANNING']?>-<?=$rows['ROW_PLANNING']?>-<?=$rows['TIER_PLANNING']?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:8pt"><?=$rows['BLOK_REALISASI']?>-<?=$rows['SLOT_REALISASI']?>-<?=$rows['ROW_REALISASI']?>-<?=$rows['TIER_REALISASI']?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:8pt"><?=$rows['TGL_GATE_IN']?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:8pt"><?=$rows['TGL_PLACEMENT']?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:8pt"><?=$rows['DURASI']?></td>
								<!--  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:8pt; color:red">
								  <? if (($rows_["BLOK_PLANNING"] == $rows_["BLOK_REALISASI"]) AND ($rows_["SLOT_PLANNING"] == $rows_["SLOT_REALISASI"]) AND ($rows_["ROW_PLANNING"] == $rows_["ROW_REALISASI"]) AND ($rows_["TIER_PLANNING"] == $rows_["TIER_REALISASI"])) {?> 
								  <i>Data match </i>
								<? } else if ($rows_["BLOK_REALISASI"] == NULL){?>
								<i> Data not complete </i>
								<?}  else { echo "Data not match";}$i++;?> </td>-->
							<tr>
							<? } ?>
        </table>
</div>

	
	