<div id="load_layout" width='100%' height='100%' style="background-color:#F6F4E4;">
         <?php
           $db = getDB();
		   $id_blok = $_GET['blok'];
			//echo "SELECT b.NAME, c.WIDTH,a.INDEX_CELL FROM YD_BLOCKING_CELL a, YD_BLOCKING_AREA b, YD_YARD_AREA c WHERE a.ID_BLOCKING_AREA = b.ID AND b.ID_YARD_AREA = c.ID AND c.STATUS = 'AKTIF' ORDER BY a.INDEX_CELL ASC";
			$query_cell2 = "SELECT MAX(SLOT_) SLOT, MAX(ROW_) ROW_ FROM YD_BLOCKING_CELL WHERE ID_BLOCKING_AREA = '$id_blok'";
            $result3    = $db->query($query_cell2);
            $blok2      = $result3->fetchRow();
			$slot_		= $blok2['SLOT'];
			$row_		= $blok2['ROW_'];
			
			$query      = "SELECT NAME FROM YD_BLOCKING_AREA WHERE ID = '$id_blok'";
            $resul2     = $db->query($query);
            $blok2      = $resul2->fetchRow();
			$name		= $blok2['NAME'];
			?>
			<table>
				<tr><td colspan="<?=$slot_?>"align="center"><b>BLOK <?=$name?></b></td></tr>
				<?  $k = 1;
					for ($i=1;$i<=$row_;$i++){?>
					<tr>
						<? for ($j=1;$j<=$slot_;$j++){
							$query_place = "SELECT NVL(COUNT(NO_CONTAINER),0) JUMLAH, ID_VS, ID_BLOCKING_AREA FROM YD_PLACEMENT_YARD WHERE SLOT_YARD = '$j' AND ROW_YARD = '$i' AND ID_BLOCKING_AREA = '$id_blok' GROUP BY ID_VS, ID_BLOCKING_AREA";
							$result2     = $db->query($query_place);
							$place_       = $result2->fetchRow();
					 
							$id_vs       = $place_['ID_VS'];
							$jumlah      = $place_['JUMLAH'];
							$id_blok      = $place_['ID_BLOCKING_AREA'];
							
							
							if($k == 1){
						    $war = NULL;
							$query_warna = "UPDATE KODE_WARNA SET ID_VS = '$id_vs', AKTIF = 'Y' WHERE ID = '$k'";
							$db->query($query_warna);
							$query_warna_  = "SELECT KODE_WARNA FROM KODE_WARNA WHERE ID_VS = '$id_vs' AND AKTIF = 'Y'";
							$tes		   = $db->query($query_warna_);
							$result	   	   = $tes->fetchRow();
							$war		   = $result['KODE_WARNA'];
							$k++;
						} else {
							 $query_warna_ = "SELECT KODE_WARNA FROM KODE_WARNA WHERE ID_VS = '$id_vs' AND AKTIF = 'Y'";
							 $tes		   = $db->query($query_warna_);
							 $result	   = $tes->fetchRow();
							 $war_		   = $result['KODE_WARNA'];
						     if ($war_ == NULL){
								$query_warna = "UPDATE KODE_WARNA SET ID_VS = '$id_vs', AKTIF = 'Y' WHERE ID = '$k'";
								$db->query($query_warna);
							    $query_warna_  = "SELECT KODE_WARNA FROM KODE_WARNA WHERE ID_VS = '$id_vs' AND AKTIF = 'Y'";
							    $tes		   = $db->query($query_warna_);
							    $result	   	   = $tes->fetchRow();
							    $war		   = $result['KODE_WARNA'];
								$k++;
							} else {
								$war      	   = $result['KODE_WARNA'];
							}
						  }
						  
						   if ($id_vs == ''){?>
							<td  bgcolor="#ADE8E6" onMouseOver="this.style.backgroundColor='#CCFF33'"  onMouseOut="this.style.backgroundColor='#ADE8E6'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma;border:1px background-size:10px 5px" colspan='2'><? if ($jumlah == NULL) echo '0'; echo $jumlah;?></td>
						<? } else {?>
							<td bgcolor="<?=$war?>"  onMouseOver="this.style.backgroundColor='#CCFF33'"  onMouseOut="this.style.backgroundColor='<?=$war?>'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma;border:1px" colspan='2'><?=$jumlah?></td>
						<?	}
						} ?>
					</tr>
					<?}?>
					<tr height="20"><td></td></tr>
					<tr>
			<td colspan="30">
				<table border="0" align="center">
					<tr>
						<td class="grid-header">Kode warna
						</td>
						<td colspan="10" class="grid-header">Vessel Voyage
						</td>
					</tr>
					<?
								$query_warna_  = "SELECT a.KODE_WARNA, c.NAMA_VESSEL, b.VOYAGE FROM KODE_WARNA a, VESSEL_SCHEDULE b, MASTER_VESSEL c WHERE 
													a.ID_VS = b.ID_VS AND b.ID_VES = c.KODE_KAPAL AND a.AKTIF = 'Y'";
							    $tes		   = $db->query($query_warna_);
							    $result	   	   = $tes->getAll();
								
								foreach ($result as $row){
					?>
					<tr>
						<td bgcolor="<?=$row['KODE_WARNA']?>" class="grid-cell">
						</td>
						<td class="grid-cell" bgcolor="#E0FFFF"><b><?=$row['NAMA_VESSEL']?> - <?=$row['VOYAGE']?></b>
						</td>
					</tr>
					<?}?>
				</table>
			</td>
		</tr>
			</table>
	
</div>