<?
     $db                    = getDB();
     $yard_id               = $_GET['id'];
	 $id_block				= $_GET['id_block'];
     $query_yard            = "SELECT NAMA_YARD FROM YD_YARD_AREA WHERE ID = '$yard_id'";
     $result_yard           = $db->query($query_yard);
     $yard_name             = $result_yard->fetchRow();
     $name                  = $yard_name['NAMA_YARD'];
	 
	 $query_yard1            = "SELECT NAME FROM YD_BLOCKING_AREA WHERE ID = '$id_block'";
     $result_yard1           = $db->query($query_yard1);
     $block_name1            = $result_yard1->fetchRow();
     $block                   = $block_name1['NAME'];
	 
	 $query_yard2            = "SELECT MAX(SLOT_) SLOT, MAX(ROW_) ROW_ FROM YD_BLOCKING_CELL WHERE ID_BLOCKING_AREA = '$id_block'";
     $result_yard2           = $db->query($query_yard2);
     $block_name2            = $result_yard2->fetchRow();
     $slot                   = $block_name2['SLOT'];
	 $rew					 = $block_name2['ROW_'];
	  $tier					 =	5;
	  
	 
	 
	 


	 // echo "SELECT MAX(SLOT) SLOT, MAX(ROW) ROW FROM YD_BLOCKING_CELL WHERE ID_BLOCKING_AREA = '$id_block'";
	//  echo $slot; echo $rew; echo $tier;
?>
	<div style="margin-top:0px;border:1px solid black;width:350;height:620;overflow-y:scroll;overflow-x:scroll;">
    <h3><center>Preview Block <?=$block?></center></h3><b>
	<center>
	<table width="10%" cellspacing="3" border="0">
		<tr>
			<td>
				<table bordercolor="#037ACA" border="0" cellspacing="4" cellpadding="4" align="center">
				<tbody>
				<tr>
					<td>
						<table border ="0">
						<? for ($i=1;$i<=$slot;$i++){?>
							<tr height="10"><td bgcolor="yellow" width="400" align="center"><b>Slot <?=$i?></b></td></tr>
							<tr height="10">
								<td>
									<table border="0">
										<? for ($j=$tier+1;$j>=1;$j--){?>
											<tr height="5">
												<? for ($k=1;$k<=$rew+1;$k++){?>
												<td align="center"><b>
													<? $r = $k-1;
													   $ti = $j-1;
													$query_yard4           = " SELECT COUNT(1) JML FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_block'  AND SLOT_YARD ='$i'  AND ROW_YARD = '$r' AND  TIER_YARD = '$ti'";
													 $result_yard4           = $db->query($query_yard4);
													 $block_name4            = $result_yard4->fetchRow();
													 $jml_tumpuk             = $block_name4['JML'];
													 
													 if (($j == 1) && ($k==1)){
														echo "T/R";
													 } else if (($j > 1) && ($k==1)){
														echo $j-1;
													 } else if (($j == 1) && ($k>1)){ 
														echo $k-1;
													} else {
														 if ($jml_tumpuk >0){?>
															<image src="<?=HOME?>images/not.png" width="15" height="15">
														<?} else {?>
															<image src="<?=HOME?>images/cont_blue_alert.png" width="15" height="15">
														<?}}?>
												</b></td>
												<?}?>
											</tr>
											<?}?>
									</table>
								</td>
							</tr>
							<?	$query_yard5            = "SELECT COUNT(NO_CONTAINER) JML FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_block' AND SLOT_YARD = '$i'";
								 $result_yard5           = $db->query($query_yard5);
								 $block_name5            = $result_yard5->fetchRow();
								 $used                   = $block_name5['JML'];
								 
								 $kapasitas				= $rew* $tier;
								 $ava 					= $kapasitas - $used
								 ?>
							<tr height="10"><td><b><i>Available to plan =  <?=$ava?> TEUS</i></b></td></tr>
							<tr height="10"><td><b><i>Used =<?=$used?> TEUS</i></b></td></tr>
							<tr>
							</tr>
						<?}?>
						</table>
					</td>
				</tr>
		</tbody>
		</table>
		</td>
	</tr>
	</table>
	</center>
	</div>
    