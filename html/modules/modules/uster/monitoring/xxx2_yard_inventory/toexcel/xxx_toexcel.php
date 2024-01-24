<?php
$tanggal=date("dmY");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=LapYardInventory-".$tanggal.".xls");
header("Pragma: no-cache");
header("Expires: 0");

    $id	= $_GET["id"]; 

	//echo $tgl_awal;die;
	$db 	= getDB("storage");

	$query_list_ 	= "SELECT a.NAME, b.NO_CONTAINER, b.SLOT_, b.ROW_, b.TIER_, c.SIZE_, c.TYPE_, c.MLO, h.STATUS_CONT STATUS, d.NAMA_YARD, TO_CHAR(b.TGL_PLACEMENT,'dd/mm/yyyy') TGL_PLACEMENT, (TO_DATE(SYSDATE,'dd/mm/yyyy')-TO_DATE(b.TGL_PLACEMENT,'dd/mm/yyyy')) DWEL
	FROM BLOCKING_AREA a, PLACEMENT b, MASTER_CONTAINER c, YARD_AREA d, HISTORY_CONTAINER h
WHERE a.ID = b.ID_BLOCKING_AREA 
AND a.ID_YARD_AREA = d.ID
AND b.NO_CONTAINER = c.NO_CONTAINER
AND c.NO_CONTAINER = h.NO_CONTAINER
AND h.TGL_UPDATE = (SELECT MAX(TGL_UPDATE) FROM HISTORY_CONTAINER WHERE NO_CONTAINER = h.NO_CONTAINER)
AND b.ID_BLOCKING_AREA = '$id'	";

	$result_list_	= $db->query($query_list_);
	$row_list		= $result_list_->getAll(); 


?>

 <div id="list">
     <table class="grid-table" border='1' cellpadding="1" cellspacing="1"  width="100%" >
                              <tr style=" font-size:10pt">
                                  <th valign="top" class="grid-header"  style="font-size:10pt">No </th>
                                  <th valign="top" class="grid-header"  style="font-size:10pt">No.Container</th>
								   <th valign="top" class="grid-header"  style="font-size:10pt">Size</th>
								    <th valign="top" class="grid-header"  style="font-size:10pt">Type</th>
                                  <th  valign="top" class="grid-header"  style="font-size:10pt">Status</th>
                                  <th  valign="top" class="grid-header"  style="font-size:10pt">Nama Lapangan</th> 
                                  <th valign="top" class="grid-header"  style="font-size:10pt">Nama Blok</th>
                                  <th valign="top" class="grid-header"  style="font-size:10pt">Posisi(S-R-T)</th>
                                  <th valign="top" class="grid-header"  style="font-size:10pt">Tgl Placement</th> 
								  <th valign="top" class="grid-header"  style="font-size:10pt">Dwelling Time</th>
								  <th valign="top" class="grid-header"  style="font-size:10pt">MLO</th>
                              </tr>
                              <?php $i=0; 
							  foreach($row_list as $rows){ $i++;?>
                              <tr bgcolor="#f9f9f3" onMouseOver=this.style.backgroundColor="#BAD5FC" onMouseOut=this.style.backgroundColor="">
                                  <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000;  font-size:9pt"><?php echo $i; ?></td>
                                  <td width="22%" align="center" valign="middle" class="grid-cell"   style=" font-size:11pt; color:#555555"><b><?php echo $rows["NO_CONTAINER"]; ?></b></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["SIZE_"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["TYPE_"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["STATUS"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["NAMA_YARD"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["NAME"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt">(<?php echo $rows["SLOT_"]; ?> - <?php echo $rows["ROW_"]; ?> - <?php echo $rows["TIER_"]; ?>)</td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["TGL_PLACEMENT"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["DWEL"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["MLO"]; ?></td>
							<tr>
							<? }?>
        </table>
 </div>