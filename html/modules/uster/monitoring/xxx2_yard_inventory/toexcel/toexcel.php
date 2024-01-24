<?php
$tanggal=date("dmY");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=LapYardInventory-".$tanggal.".xls");
header("Pragma: no-cache");
header("Expires: 0");


	$db 	= getDB("storage");
	
					
	$query_list_ 	= "SELECT a.ID, a.NAME, a.YARD, a.SLOT_, a.ROW_, a.TIER_, a.GROUND_SLOT, a.CAPACITY, COUNT(b.NO_CONTAINER) USED, (a.CAPACITY-COUNT(b.NO_CONTAINER)) AVA FROM BLOCKING_AREA a, PLACEMENT b WHERE a.ID_YARD_AREA = '46' and a.id = b.id_blocking_area(+) GROUP BY a.ID, a.NAME, a.YARD, a.SLOT_, a.ROW_, a.TIER_, a.GROUND_SLOT, a.CAPACITY";
					
	// echo $query_list_;
	
	$result_list_	= $db->query($query_list_);
	$row_list		= $result_list_->getAll();


?>

 <div id="list">
     <table class="grid-table" border='1' cellpadding="1" cellspacing="1"  width="100%" >
							  <tr style=" font-size:10pt">MONITORING YARD INVENTORY</tr>
							  <tr style=" font-size:10pt"></tr>
                              <tr style=" font-size:10pt">
							  
									
                                  <th valign="top" class="grid-header"  style="font-size:8pt">No </th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">NAMA BLOK</th>
								  <th valign="top" class="grid-header"  style="font-size:8pt">YARD</th>
                                  <th  valign="top" class="grid-header"  style="font-size:8pt">SLOT</th>
                                  <th  valign="top" class="grid-header"  style="font-size:8pt">ROW</th> 
                                  <th valign="top" class="grid-header"  style="font-size:8pt">TIER</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">GROUND SLOT</th>
								  <th valign="top" class="grid-header"  style="font-size:8pt">CAPACITY</th> 
                                  <th valign="top" class="grid-header"  style="font-size:8pt">USED</th> 
								  <th valign="top" class="grid-header"  style="font-size:8pt">AVAILABLE</th> 
                              </tr>
                              <?php $i=0; 
							  foreach($row_list as $rows){ $i++;?>
                              <tr bgcolor="#f9f9f3" onMouseOver=this.style.backgroundColor="#BAD5FC" onMouseOut=this.style.backgroundColor="">
                                  <td width="10%" align="center" valign="middle" class="grid-cell" style="color:#000;  font-size:9pt"><?php echo $i; ?></td>
                                  <td width="10%" align="center" valign="middle" class="grid-cell"   style=" font-size:11pt; color:#555555"><b><?php echo $rows["NAME"]; ?></b></td>
                                  <td width="10%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["YARD_"]; ?></td>
                                  <td width="10%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["SLOT_"]; ?></td>
                                  <td width="10%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["ROW_"]; ?></td>
                                  <td width="10%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["TIER_"]; ?></td>
                                  <td width="10%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["GROUND_SLOT"]; ?></td>
                                  <td width="10%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["CAPACITY"]; ?></td>
                                  <td width="10%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["USED"]; ?></td>
                                  <td width="10%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["AVA"]; ?></td>
							</tr>
                              <?php } ?>
        </table>
 </div>