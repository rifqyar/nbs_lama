<?php
/** Error reporting */
error_reporting(E_ALL);

/** Include path **/
ini_set('include_path', ini_get('include_path').';../Classes/');

/** PHPExcel */
include 'PHPExcel.php';

/** PHPExcel_Writer_Excel2007 */
include 'PHPExcel/Writer/Excel2007.php';

// Create new PHPExcel object
echo date('H:i:s') . " Create new PHPExcel object\n";
$objPHPExcel = new PHPExcel();

// Set properties
echo date('H:i:s') . " Set properties\n";
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw");
$objPHPExcel->getProperties()->setLastModifiedBy("Maarten Balliauw");
$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");


// Add some data
echo date('H:i:s') . " Add some data\n";
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Hello');
$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'world!');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Hello');
$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'world!');

// Rename sheet
echo date('H:i:s') . " Rename sheet\n";
$objPHPExcel->getActiveSheet()->setTitle('Simple');

		
// Save Excel 2007 file
echo date('H:i:s') . " Write to Excel2007 format\n";
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));

// Echo done
echo date('H:i:s') . " Done writing file.\r\n";


$tanggal=date("dmY");
$excel = new PHPExcel();
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=ReportDwellingTime-".$tanggal.".xls");
header("Pragma: no-cache");
header("Expires: 0");

//$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
//$writer->save('/report/ReportDwellingTime.xls');

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save('some_excel_file.xlsx');

//$objWriter->save(str_replace(__FILE__,'/report/ReportDwellingTime.xls',__FILE__));

	 $db        = getDB();

	 $query 	= "SELECT a.NAME,
					  a.ID,
				      (MAX(d.SLOT_)*MAX(d.ROW_)*a.TIER) KAPASITAS,
					  (MAX(d.SLOT_)*MAX(d.ROW_)*a.TIER)-COUNT(DISTINCT(b.NO_CONTAINER)) AVA,
					  COUNT(DISTINCT(b.NO_CONTAINER)) USED
				FROM yd_blocking_area a, yd_placement_yard b, yd_yard_area c, yd_blocking_cell d
			    WHERE a.id = b.id_blocking_area(+)
					AND a.ID = d.ID_BLOCKING_AREA
					AND a.id_yard_area = c.id
					AND c.status = 'AKTIF'
					AND a.NAME <> 'NULL'
			GROUP BY a.NAME, a.ID,a.TIER";
	 $result = $db->query($query);
	 $row 	 = $result->getAll();
	
	$query2  = "SELECT COUNT(DISTINCT(b.NO_CONTAINER)) USED FROM yd_placement_yard b";
	$result2 = $db->query($query2);
	$row2 	 = $result2->fetchRow();
	$grand   = $row2['USED'];
	
?>
<div id="list" align="center">
<table id="zebra" border="1" bordercolor="#FFE4C4" width="100%" style="border-collapse:collapse" align="center">
                              <tr style=" font-size:8pt">
                                  <th bgcolor='#FFE4C4'  valign="top" class="grid-header"  style="font-size:8pt">No </th>
                                  <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:8pt">Nama Blok</th>
								   <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:8pt">Kapasitas</th>
								   <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:8pt">Used</th>
								   <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:8pt">Available</th>
                                  <th bgcolor='#FFE4C4'  valign="top" class="grid-header"  style="font-size:8pt">Vessel</th>
								<th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:8pt">Voyage</th>
                                  <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:8pt">No Container</th>
								   <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:8pt">Size-Type-Status</th>
								   <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:8pt">Slot-Row-Tier</th>
									 <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:8pt">Activity</th>
								  <th bgcolor='#FFE4C4'  valign="top" class="grid-header"  style="font-size:8pt">Tgl Gate In</th>
								  <th bgcolor='#FFE4C4'  valign="top" class="grid-header"  style="font-size:8pt">Tgl Placement</th>
								  <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:8pt">Durasi Gate-Placement</th>
								  <th bgcolor='#FFE4C4' valign="top" class="grid-header"  style="font-size:8pt"></th>
							</tr>
                              <?php $i=1; foreach($row as $rows){?>
							 <tr style="cursor:pointer;" onclick="fill('{$rows['ID']}')">
								<td class="grid-cell" align ="center" valign="top" style="font-size:10;color:#3300CC"><?=$i;?></td>
								<td class="grid-cell" align="center" valign="top" style="font-size:10;color:#3300CC"><b><?=$rows['NAME']?></b></td>
								<td class="grid-cell" align ="center" valign="top" style="font-size:10;color:#3300CC"><?=$rows['KAPASITAS']?></td>
								<td class="grid-cell" align="center" valign="top" style="font-size:10;color:#3300CC"><?=$rows['USED']?></td>
								<td class="grid-cell" align ="center" valign="top" style="font-size:10;color:#3300CC"><?=$rows['AVA']?></td>
								<td colspan="10"></td>
							</tr>
							<?	
							$db 	= getDB();
							$id_blok_ = $rows['ID'];
							
							$query = "SELECT c.ID_VS, a.NAMA_VESSEL, b.VOYAGE FROM YD_PLACEMENT_YARD c, VESSEL_SCHEDULE b, MASTER_VESSEL a WHERE c.ID_VS = b.ID_VS AND b.ID_VES = a.KODE_KAPAL AND c.ID_BLOCKING_AREA = '$id_blok_' GROUP BY c.ID_VS, a.NAMA_VESSEL, b.VOYAGE";
							$result = $db->query($query);
							$detail = $result->getAll();
							
							foreach ($detail as $row){
						?>
						<tr>
							<td colspan='5'></td>
							<td class="grid-cell" align ="left" valign="top" style="font-size:10;color:#3300CC"><?=$row['NAMA_VESSEL']?></td>
							<td class="grid-cell" align ="left" valign="top" style="font-size:10;color:#3300CC"><?=$row['VOYAGE']?></td>
							<td colspan="8"></td>
						</tr>
						<?
							$id_vs_ = $row['ID_VS'];
							$id_blok_ = $rows['ID'];
		
							$query2 = "SELECT a.NO_CONTAINER, a.ACTIVITY,a.SIZE_, a.TYPE_CONT, a.STATUS_CONT, a.SLOT_YARD, a.ROW_YARD, a.TIER_YARD, TO_DATE(b.TGL_GATEIN,'dd/mm/rrrr') TGL_GATEIN, TO_DATE(a.PLACEMENT_DATE,'dd/mm/rrrr') TGL_PLACEMENT, get_duration2(a.PLACEMENT_DATE, SYSDATE) DWELLING_TIME
						from yd_placement_yard a, tb_cont_jobslip b
						where a.id_jobslip = b.id_job_slip
						and a.id_blocking_area = '$id_blok_'
						and a.id_vs = '$id_vs_' ";

						$result2 = $db->query($query2);
						$detail2 = $result2->getAll();
						
						foreach ($detail2 as $row){
						if ($row['ACTIVITY'] == 'MUAT'){
						$act = 'EXP';
						} else {
						$act = 'IMP';
						}
	?>
	<tr>
		<td colspan='7'></td>
		<td class="grid-cell" align ="center" valign="top" style="font-size:10;color:#3300CC"><?=$row['NO_CONTAINER']?></td>
		<td class="grid-cell" align ="center" valign="top" style="font-size:10;color:#3300CC"><?=$row['SIZE_']?>-<?=$row['TYPE_CONT']?>-<?=$row['STATUS_CONT']?></td>
		<td class="grid-cell" align ="center" valign="top" style="font-size:10;color:#3300CC">S<?=$row['SLOT_YARD']?>-R<?=$row['ROW_YARD']?>-T<?=$row['TIER_YARD']?></td>
		<td class="grid-cell" align ="center" valign="top" style="font-size:10;color:#3300CC"><?=$act?></td>
		<td class="grid-cell" align ="center" valign="top" style="font-size:10;color:#3300CC"><?=$row['TGL_GATEIN']?></td>
		<td class="grid-cell" align ="center" valign="top" style="font-size:10;color:#3300CC"><?=$row['TGL_PLACEMENT']?></td>
		<td class="grid-cell" align ="center" valign="top" style="font-size:10;color:#3300CC"><?=$row['DWELLING_TIME']?></td>
		<td class="grid-cell" align ="center" valign="top" style="font-size:10;color:#3300CC"><b><a href="#">PLP</a></b></td>
	</tr>
	<?}
		$db = getDB();
		$query3 = "SELECT COUNT(DISTINCT(b.NO_CONTAINER)) USED_
        FROM yd_placement_yard b WHERE b.ID_VS = '$id_vs_' ";
		$result3 = $db->query($query3);
	    $detail3 = $result3->fetchRow();
		
		$query4 = "SELECT COUNT(DISTINCT(b.NO_CONTAINER)) USED_
        FROM yd_placement_yard b WHERE b.ID_BLOCKING_AREA = '$id_blok_' AND b.ID_VS = '$id_vs_'";
		$result4 = $db->query($query4);
	    $detail4 = $result4->fetchRow();
		
		if ($id_vs == NULL){
			$per_vessel = $detail4['USED_'];
		} else {
			$per_vessel = $detail3['USED_'];
		}
	?>
	<tr>
		<td></td><td colspan="13" class="grid-cell" align ="right" valign="top" style="font-size:10;color:red"><b> TOTAL PLACEMENT VESSEL <i><font color='blue'> <?=$row['NAMA_VESSEL']?></font></i></b></td>
		<td class="grid-cell" align ="right" valign="top" style="font-size:10;color:red"><?=$per_vessel?> BOX</td>
	</tr>
		<?}
		if ($id_blok == NULL){
			$used = $rows['NAME'];
		} else {
			$used = $rows['NAME'];
		}?>
	<tr>
		<td></td><td colspan="13" class="grid-cell" align ="right" valign="top" style="font-size:10;color:red"><b> TOTAL PLACEMENT di BLOCK <i><font color='blue'> <?=$rows['NAME']?></font></i></b></td>
		<td class="grid-cell" align ="right" valign="top" style="font-size:10;color:red"><?=$rows['USED']?> BOX </td>
	</tr>
	<? $i++; }?>	
	<tr>
		<td></td><td colspan="13" class="grid-cell" align ="right" valign="top" style="font-size:10;color:red"><b> GRAND TOTAL</b></td>
		<td class="grid-cell" align ="right" valign="top" style="font-size:10;color:red"><?=$grand?> BOX </td>
	</tr>
        </table>
</div>
