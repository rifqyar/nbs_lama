<div id="load_layout" width='100%' height='100%' style="background-color:#F6F4E4;">
<table cellspacing="3" border="0">
<tbody>
<tr>
<td valign="bottom" colspan="4" align="center">
<table border="0" cellspacing="4" cellpadding="4" align="center">
<tbody>
    <tr>
         <?php
           $db = getDB();
		   
			//echo "SELECT b.NAME, c.WIDTH,a.INDEX_CELL FROM YD_BLOCKING_CELL a, YD_BLOCKING_AREA b, YD_YARD_AREA c WHERE a.ID_BLOCKING_AREA = b.ID AND b.ID_YARD_AREA = c.ID AND c.STATUS = 'AKTIF' ORDER BY a.INDEX_CELL ASC";
			$query_cell2 = "SELECT b.ID, b.NAME, c.WIDTH,a.INDEX_CELL FROM YD_BLOCKING_CELL a, YD_BLOCKING_AREA b, YD_YARD_AREA c WHERE a.ID_BLOCKING_AREA = b.ID AND b.ID_YARD_AREA = c.ID AND c.STATUS = 'AKTIF' ORDER BY a.INDEX_CELL ASC";
            $result3    = $db->query($query_cell2);
            $blok2      = $result3->getAll();
		    $i = 1;
            foreach ($blok2 as $row){
				$index_cell_ = $row['INDEX_CELL'];
				$width		 = $row['WIDTH'];
                //echo $row['INDEX_CELL'];
                if ($row['NAME'] <> 'NULL'){ 
					//echo "SELECT NVL(COUNT(NO_CONTAINER),0) JUMLAH, ID_VS, ID_BLOCKING_AREA FROM YD_PLACEMENT_YARD WHERE ID_CELL = '$index_cell_' GROUP BY ID_VS, ID_BLOCKING_AREA";
					 $query_place = "SELECT NVL(COUNT(NO_CONTAINER),0) JUMLAH, ID_VS, ID_BLOCKING_AREA FROM YD_PLACEMENT_YARD WHERE ID_CELL = '$index_cell_' GROUP BY ID_VS, ID_BLOCKING_AREA";
                     $result2     = $db->query($query_place);
                     $place_       = $result2->fetchRow();
					 
						 $id_vs       = $place_['ID_VS'];
						 $jumlah      = $place_['JUMLAH'];
						 $id_blok      = $row['ID'];
						 
						 
						 if($i == 1){
						    $war = NULL;
							$query_warna = "UPDATE KODE_WARNA SET ID_VS = '$id_vs', AKTIF = 'Y' WHERE ID = '$i'";
							$db->query($query_warna);
							$query_warna_  = "SELECT KODE_WARNA FROM KODE_WARNA WHERE ID_VS = '$id_vs' AND AKTIF = 'Y'";
							$tes		   = $db->query($query_warna_);
							$result	   	   = $tes->fetchRow();
							$war		   = $result['KODE_WARNA'];
							$i++;
						} else {
							 $query_warna_ = "SELECT KODE_WARNA FROM KODE_WARNA WHERE ID_VS = '$id_vs' AND AKTIF = 'Y'";
							 $tes		   = $db->query($query_warna_);
							 $result	   = $tes->fetchRow();
							 $war_		   = $result['KODE_WARNA'];
						     if ($war_ == NULL){
								$query_warna = "UPDATE KODE_WARNA SET ID_VS = '$id_vs', AKTIF = 'Y' WHERE ID = '$i'";
								$db->query($query_warna);
							    $query_warna_  = "SELECT KODE_WARNA FROM KODE_WARNA WHERE ID_VS = '$id_vs' AND AKTIF = 'Y'";
							    $tes		   = $db->query($query_warna_);
							    $result	   	   = $tes->fetchRow();
							    $war		   = $result['KODE_WARNA'];
								$i++;
							} else {
								$war      	   = $result['KODE_WARNA'];
							}
						  }
					
					 if ($id_vs == ''){?>
						<td  bgcolor="#ADE8E6" onMouseOver="this.style.backgroundColor='#CCFF33'"  onMouseOut="this.style.backgroundColor='#ADE8E6'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma;border:1px background-size:10px 5px" colspan='2'><a onclick="window.open('monitoring.detail?blok=<?=$id_blok?>','_self')"><? if ($jumlah == NULL) echo '0'; echo $jumlah;?></a></td>
					 <? } else {?>
						<td  bgcolor="<?=$war?>"  onMouseOver="this.style.backgroundColor='#CCFF33'"  onMouseOut="this.style.backgroundColor='<?=$war?>'" align="center" style="width:30px;height:15px;font-size:8px; font-family:Tahoma;border:1px" colspan='2'><a onclick="window.open('monitoring.detail?blok=<?=$id_blok?>','_self')"><?=$jumlah?></a></td>
					<?	} 
						
				 }  else { ?>
				 <td bgcolor="#DCDCDC" align="center" colspan=2>     </td>
				 
			<?	 }
				    if (($row['INDEX_CELL']+1) % $width == 0){ ?>
						</tr>
                    <? }
			}
		
				?>
				
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
								$query_warna_  = "SELECT a.KODE_WARNA, b.NM_KAPAL NAMA_VESSEL, b.VOYAGE_OUT VOYAGE FROM KODE_WARNA a, TR_VESSEL_SCHEDULE_ICT b WHERE 
													a.ID_VS = b.NO_UKK";
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
		
		<? 
		$query = "UPDATE KODE_WARNA SET ID_VS = '', AKTIF = 'T'";
		$db->query($query);		
		?>
</tbody>
</table>
</td>
</tr>
<tr>
<tr>
<tr>
<tr>
</tbody>
</table>
</tbody>
</center>
    
</div>