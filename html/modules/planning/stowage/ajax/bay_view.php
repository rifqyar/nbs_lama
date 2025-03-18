<table id="table2">
<? 
	$id_vs = $_GET['id_vs'];
	$id_area = $_GET['bay_area'];
?>
						<?
							$db = getDB();
							$query_bay = "SELECT DISTINCT JML_ROW,JML_TIER_UNDER,JML_TIER_ON FROM STW_BAY_AREA WHERE ID_VS = '$id_vs'";
							$result_   = $db->query($query_bay);
							$bay_      = $result_->fetchRow();
							
							$jumlah_row = $bay_['JML_ROW'];
							$jml_tier_under = $bay_['JML_TIER_UNDER'];
							$jml_tier_on = $bay_['JML_TIER_ON'];
							$width = $jumlah_row+1;
						?>
						<tbody>
							<tr>
								<?
									 $query_cell2 = "SELECT CELL_NUMBER,ROW_,TIER_,STATUS_STACK FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$id_area' ORDER BY CELL_NUMBER ASC";
								     $result3 = $db->query($query_cell2);
									 $blok2 = $result3->getAll();
								?>
								
								<td width="60" class="mark"></td>
								<td width="60"></td>
								<td width="60"></td>
								<td width="60"><div id="x" class="drag blue"><img src="<?=HOME?>images/row_cont_2.png" height="30" width="40" data-tooltip="sticky1"></img></div></td>
								<td width="60"></td>
								<td width="60"></td>
								<td width="60"></td>
								<td width="60"></td>
								<td width="60"></td>
								<td width="60"></td>
								<td width="60"></td>
								<td width="60"></td>
								<td width="60"></td>
								<td width="60" class="mark"></td>
							</tr>
							<? for($i;$i<16;$i++) {?>
							<tr>
								<td class="mark"></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td class="mark"></td>
							</tr>
							<? } ?>							
							<tr>
								<td class="mark"></td>
								<td class="mark"><b>12</b></td>
								<td class="mark"><b>10</b></td>
								<td class="mark"><b>08</b></td>
								<td class="mark"><b>06</b></td>
								<td class="mark"><b>04</b></td>
								<td class="mark"><b>02</b></td>
								<td class="mark"><b>01</b></td>
								<td class="mark"><b>03</b></td>
								<td class="mark"><b>05</b></td>
								<td class="mark"><b>07</b></td>
								<td class="mark"><b>09</b></td>
								<td class="mark"><b>11</b></td>
								<td class="mark"></td>
							</tr>
						</tbody>
					</table>
					<br/>
					<hr width="950" size="10" style="background-color:#333" />
					<br/>
					<table id="table3">
						<tbody>
							<tr>
								<td width="60" class="mark"></td>
								<td width="60" class="mark"><b>12</b></td>
								<td width="60" class="mark"><b>10</b></td>
								<td width="60" class="mark"><b>08</b></td>
								<td width="60" class="mark"><b>06</b></td>
								<td width="60" class="mark"><b>04</b></td>
								<td width="60" class="mark"><b>02</b></td>
								<td width="60" class="mark"><b>01</b></td>
								<td width="60" class="mark"><b>03</b></td>
								<td width="60" class="mark"><b>05</b></td>
								<td width="60" class="mark"><b>07</b></td>
								<td width="60" class="mark"><b>09</b></td>
								<td width="60" class="mark"><b>11</b></td>
								<td width="60" class="mark"></td>
							</tr>
							<tr>
								<td class="mark"></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td class="mark"></td>
							</tr>
							<? for($i;$i<24;$i++) {?>
							<tr>
								<td class="mark"></td>
								<td class="mark2"></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td class="mark"></td>
							</tr>
							<? } ?>
						</tbody>
					</table>