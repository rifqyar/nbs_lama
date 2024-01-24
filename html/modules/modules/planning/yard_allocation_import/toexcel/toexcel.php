<?php

	$tanggal=date("dmY");
	
	header("Content-type: application/x-msdownload");
	header("Content-Disposition: attachment; filename=Reportyardalokasiimport-".$tanggal.".xls");
	header("Pragma: no-cache");
	header("Expires: 0");

	/*header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=BPE_".$tanggal.".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
*/
	$db 	= getDB();
	
	$id_vs	= $_GET["id_vs"]; 

	
	$query_list_ = "select nm_kapal, voyage_in, voyage_out, TO_CHAR(tgl_jam_tiba,'dd Mon rrrr hh24:ii:ss') TIBA,  TO_CHAR(tgl_jam_berangkat,'dd Mon rrrr hh24:ii:ss') BERANGKAT from rbm_h where no_ukk = '$id_vs'";
	
	$result 	= $db->query($query_list_);
	$row_list 	= $result->fetchRow();
	$kapal		= $row_list['NM_KAPAL'];
	$voyagein   = $row_list['VOYAGE_IN'];
	$voyageout	= $row_list['VOYAGE_OUT'];
	$tiba		= $row_list['TIBA'];
	$berangkat	= $row_list['BERANGKAT'];


	$query_list4 = "select id_kategori from tb_booking_cont_area where id_vs = '$id_vs' order by id_kategori asc";
	
	$result4	= $db->query($query_list4);
	$row_list4 	= $result4->getAll();
	
?>

 <div id="list">
	
	 <table class="grid-table" border='0' cellpadding="1" cellspacing="1"  width="100%" >
	 <tr>
		<td colspan="16" align="center"><b>REPORT YARD ALLOCATION PLANNING </b></td>
	 </tr>
	 
	   <tr>
		<td colspan="16"  align="center"><b>TERMINAL OPERASI III CABANG TANJUNG PRIOK </b></td>
	 </tr>
	  <tr>
		<td colspan="16"  align="center"><b>VESSEL <?=$kapal?> VOYAGE <?=$voyagein?> - <?=$voyageout?> </b></td>
	 </tr>
	   <tr>
		<td colspan="16"  align="center"><b>Tanggal Tiba :  <?=$tiba?> </b></td>
	 </tr>
	  <tr>
		<td colspan="16"  align="center"><b>Tanggal Berangkat :  <?=$berangkat?> </b></td>
	 </tr>
	  <tr>
		<td colspan="16"></td>
	 </tr>
	</table> 
     <table class="grid-table" border='1' cellpadding="1" cellspacing="1"  width="100%" >
                              <tr style=" font-size:10pt">
                                  <th valign="top" class="grid-header"  style="font-size:10pt">No </th>
								   <th valign="top" class="grid-header"  style="font-size:10pt">Size</th>
								    <th valign="top" class="grid-header"  style="font-size:10pt">Type</th>
                                  <th  valign="top" class="grid-header"  style="font-size:10pt">Status</th>
								   <th  valign="top" class="grid-header"  style="font-size:10pt">Height</th>
                                  <th  valign="top" class="grid-header"  style="font-size:10pt">HZ</th>
									<th valign="top" class="grid-header"  style="font-size:10pt">Reffer</th>								  
								  <th valign="top" class="grid-header"  style="font-size:10pt">Blok</th>
                                  <th valign="top" class="grid-header"  style="font-size:10pt">Slot Awal</th>
								   <th valign="top" class="grid-header"  style="font-size:10pt">Slot Akhir</th>
                                  <th valign="top" class="grid-header"  style="font-size:10pt">Row Awal</th>
								   <th valign="top" class="grid-header"  style="font-size:10pt">Row Akhir</th>
                                  <th valign="top" class="grid-header"  style="font-size:10pt">Total TEUS
								  </th>
                              </tr>
                              <?php $i=0; 
							  foreach($row_list4 as $rows){
									$id_kategori = $rows['ID_KATEGORI'];
									$query_list2 = "select a.id_blocking_area, b.name from yd_yard_allocation_planning a, yd_blocking_area b where  a.id_blocking_area = b.id and a.id_book = '$id_kategori' group by  a.id_blocking_area, b.name order by b.name asc ";
									$result2 	= $db->query($query_list2);
									$row_list2 	= $result2->getAll();
									 foreach($row_list2 as $rows1){
										$id_block = $rows1['ID_BLOCKING_AREA'];
										$query_list3 = "select c.name, a.size_cont, a.type_cont, a.status_cont, a.hz, a.height, a.type_reffer, min(slot_) minslot, min(row_) minrow, max(slot_) maxslot, max(row_) maxrow, count(1) TEUS from tb_booking_cont_area a, yd_yard_allocation_planning b, yd_blocking_area c where 
										a.id_kategori = b.id_book and a.id_kategori = '$id_kategori' and b.id_blocking_area = '$id_block'
										and b.id_blocking_area = c.id
										group by a.size_cont, a.type_cont, a.status_cont, a.hz, a.height, a.type_reffer, c.name
										order by c.name asc";
										$result3 	= $db->query($query_list3);
										$row_list3 	= $result3->getAll();
									 foreach($row_list3 as $rows2){ $i++;	
									 
									 ?>
		                              <tr bgcolor="#f9f9f3">
		                                  <td width="4%" align="center" valign="middle" style="color:#000;  font-size:9pt"><?php echo $i; ?></td>
		                                  <td width="22%" align="center" valign="middle"   style=" font-size:11pt; color:#555555"><b><?php echo $rows2["SIZE_CONT"]; ?></b></td>
		                                  <td width="15%" align="center" valign="middle"  style="font-size:9pt"><?php echo $rows2["TYPE_CONT"]; ?></td>
		                                  <td width="15%" align="center" valign="middle"  style="font-size:9pt"><?php echo $rows2["STATUS_CONT"]; ?></td>
		                                  <td width="15%" align="center" valign="middle"  style="font-size:9pt"><?php echo $rows2["HZ"]; ?></td>
										  <td width="15%" align="center" valign="middle"  style="font-size:9pt"><?php echo $rows2["HEIGHT"]; ?></td>
		                                  <td width="15%" align="center" valign="middle"  style="font-size:9pt"><?php echo $rows2["TYPE_REFFER"]; ?></td>
										  <td width="20%" align="center" valign="middle"  style="font-size:9pt"><?php echo $rows2["NAME"]; ?></td>
										  <td width="20%" align="center" valign="middle"  style="font-size:9pt"><?php echo $rows2["MINSLOT"]; ?></td>
		                                  <td width="30%" align="center" valign="middle"  style="font-size:9pt"><?php echo $rows2["MAXSLOT"]; ?> </td>
		                                  <td width="15%" align="center" valign="middle"  style="font-size:9pt"><?php echo $rows2["MINROW"]; ?></td>
										   <td width="20%" align="center" valign="middle"  style="font-size:9pt"><?php echo $rows2["MAXROW"]; ?></td>
		                                  <td width="30%" align="center" valign="middle"  style="font-size:9pt"><?php echo $rows2["TEUS"]; ?> </td>
									</tr>
							<? }}}?>
        </table>
 </div>