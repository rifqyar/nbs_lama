<?php
$tanggal=date("dmY");
$block_name = $_GET['name'];
// print_r($block_name);
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=LapYardInventory-".$tanggal."-blok-".$block_name.".xls");
header("Pragma: no-cache");
header("Expires: 0");





	$db 	= getDB("storage");
	
					
	$query_list_ 	= "SELECT b.NO_CONTAINER, b.SLOT_, b.ROW_, b.TIER_, b.TGL_PLACEMENT, b.NO_REQUEST_RECEIVING, b.STATUS 
							FROM BLOCKING_AREA a, PLACEMENT b
							WHERE a.NAME='$block_name'
								AND a.ID = b.ID_BLOCKING_AREA";
					
	// echo $query_list_;
	
	$result_list_	= $db->query($query_list_);
	$row_list		= $result_list_->getAll();


?>

 <div id="list">
     <table class="grid-table" border='1' cellpadding="1" cellspacing="1"  width="100%" >
							  <tr style=" font-size:10pt">MONITORING YARD INVENTORY - BLOK <?php echo $block_name; ?></tr>
							  <tr style=" font-size:10pt"></tr>
                              <tr style=" font-size:10pt">
							  
									
                                  <th valign="top" class="grid-header"  style="font-size:8pt">No </th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">NO CONTAINER</th>
                                  <th  valign="top" class="grid-header"  style="font-size:8pt">SLOT</th>
                                  <th  valign="top" class="grid-header"  style="font-size:8pt">ROW</th> 
                                  <th valign="top" class="grid-header"  style="font-size:8pt">TIER</th>
								  <th valign="top" class="grid-header"  style="font-size:8pt">STATUS</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">TANGGAL</th>
								  <th valign="top" class="grid-header"  style="font-size:8pt">NO REQUEST</th> 
                              </tr>
                              <?php $i=0; 
							  foreach($row_list as $rows){ $i++;?>
                              <tr bgcolor="#f9f9f3" onMouseOver=this.style.backgroundColor="#BAD5FC" onMouseOut=this.style.backgroundColor="">
                                  <td width="10%" align="center" valign="middle" class="grid-cell" style="color:#000;  font-size:9pt"><?php echo $i; ?></td>
                                  <td width="10%" align="center" valign="middle" class="grid-cell"   style=" font-size:11pt; color:#555555"><b><?php echo $rows["NO_CONTAINER"]; ?></b></td>
                                  <td width="10%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["SLOT_"]; ?></td>
                                  <td width="10%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["ROW_"]; ?></td>
                                  <td width="10%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["TIER_"]; ?></td>
                                  <td width="10%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["STATUS"]; ?></td>
                                  <td width="10%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["TGL_PLACEMENT"]; ?></td>
                                  <td width="10%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["NO_REQUEST_RECEIVING"]; ?></td>
							</tr>
                              <?php } ?>
        </table>
 </div>