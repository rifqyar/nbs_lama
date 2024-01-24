<div id="info_group">
<table style="font-size: 12px; font-weight: bold;">
		<tr>
			<td colspan="3">
				<table class="grid-table" border='0' cellpadding="1" cellspacing="1"  width="100%" style="border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px;">
					<tr>
					<th class="grid-header">SIZE - TYPE - STATUS</th>   
					<th class="grid-header">HZ - PLUG</th>   
					<th class="grid-header">TOTAL BOX-TEUS</th>
					<th class="grid-header">ALLOCATION REQUIRED</th>
					<th class="grid-header">ALLOCATED</th>
					<th class="grid-header">ALLOCATED LEFT</th>
					<th class="grid-header">USER ID</th>
					<th class="grid-header">DATE PLAN</th>
					<th class="grid-header">ACTION</th>
					</tr>
					<?php
						$db = getDB();
						$no_ukk = $_GET['id_vs'];
						$query_tb="SELECT a.ID_BOOK,a.TYPE_CONT, a.SIZE_CONT, a.BOX, a.TEUS, NVL(a.HEIGHT,'8.6') HEIGHT,a.ID_USER, a.CREATE_DATE, a.HZ, a.PLAN_STATUS, a.TYPE_REFFER,
						(select count(1) from yd_yard_allocation_planning b where b.ID_BOOK=a.ID_BOOK AND b.STATUS_BM='Bongkar' and b.ID_VS=a.ID_VS) AS ALLOCATED
						FROM TB_BOOKING_CONT_AREA a WHERE ID_VS='$no_ukk' and E_I='I'";
						$res_tb=$db->query($query_tb);
						$row_tb=$res_tb->getAll();

						foreach($row_tb as $rw)
						{
							$idb=$rw['ID_BOOK'];
							
							
							if ($rw['TEUS']==$rw['ALLOCATED'])
							{
								$warna='green';
							}
							else
								$warna='#fe0836';
							
					?>
					 <tr onMouseOver=this.style.backgroundColor="#BAD5FC" onMouseOut=this.style.backgroundColor="#000000">
						<td align="center" class="grid-cell" bgcolor="#ffffff"><b><?=$rw['SIZE_CONT']?> <?=$rw['TYPE_CONT']?> <?//=$rw['STATUS_CONT']?> <?=$rw['HEIGHT']?></b></td>
						<td align="center" class="grid-cell" bgcolor="#ffffff"><b><?=$rw['HZ']?></b> - <b><?=$rw['TYPE_REFFER']?></b></td>
						<td align="center" class="grid-cell" bgcolor="#ffffff"><b><?=$rw['BOX']?></b> <I>box</I> <b><?=$rw['TEUS']?></b> <i>teus</i></td>
						<td align="center" class="grid-cell" bgcolor="#ffffff"><b><?=$rw['TEUS']?></b></td>
						<td align="center" class="grid-cell" bgcolor="#ffffff"><b><?php
						if ($rw['SIZE_CONT']=='40') 
						{
							$dd=$rw['ALLOCATED'];
						}
						else if ($rw['SIZE_CONT']=='45')
						{
							$dd=$rw['ALLOCATED'];
						} 
						else {
							$dd=$rw['ALLOCATED'];
						}
						echo $dd;	
						?></b></td>
						<td align="center" class="grid-cell" bgcolor="#ffffff"><font color='<?=$warna?>'><b><?=($rw['TEUS']-$rw['ALLOCATED'])?></b></font></td>
						<td align="center" class="grid-cell" bgcolor="#ffffff"><b><?=$rw['ID_USER']?></b></td>
						<td align="center" class="grid-cell" bgcolor="#ffffff"><b><?=$rw['CREATE_DATE']?></b></td>
						<td align="center" class="grid-cell" bgcolor="#ffffff">
						<?php $selisih = $rw['TEUS']-$rw['ALLOCATED'];
						
							$ukuran = $rw['SIZE_CONT'];
							$tipe   = $rw['TYPE_CONT'];
							$status = $rw['STATUS_CONT'];
							$hz     = $rw['HZ'];
							
							$query_tier	   = "SELECT MAX(TIER_) TIER FROM YD_YARD_ALLOCATION_PLANNING WHERE SIZE_='$ukuran' AND TYPE_='$tipe' --AND STATUS_CONT = '$status' 
							AND HZ = '$hz' AND ID_VS = '$no_ukk'";			
							//print_R($query_slot_row.'<br>');
							$result_tier   = $db->query($query_tier);
							$hasil_tier    = $result_tier->fetchRow();
							$tier          = $hasil_tier['TIER'];
			
							if ($selisih <= 0)
								{?>
							<font color ='red'><i>already planed</i></font> <button onclick="plan_t('<?=$rw[SIZE_CONT]?>','<?=$rw[TYPE_CONT]?>','<?=$rw[STATUS_CONT]?>','<?=$rw[HEIGHT]?>','<?=$idb?>','<?=$rw['HZ']?>','<?=$tier?>')"><img src="images/editx.png"></button>		
						<?php		}
								else
								{
						?>
						<button onclick="plan_t('<?=$rw[SIZE_CONT]?>','<?=$rw[TYPE_CONT]?>','<?=$rw[STATUS_CONT]?>','<?=$rw[HEIGHT]?>','<?=$idb?>','<?=$rw['HZ']?>','<?=$tier?>')"><img src="images/plth.png"></button>
						<?}?>
						</td>
					</tr>
					<?php } ?>
				</table>
			</td>
        </tr>
		<tr>
			<td colspan="3">
			&nbsp;
            </td>
        </tr>
		<tr>
			<td> &nbsp; </td>
            <td> 
				Block : 
				<select name="block_id" id="block_id">
                <option value="" selected> -- choose --</option>
				<?php 
                    $query_get_block     = "SELECT a.* FROM YD_BLOCKING_AREA a, YD_YARD_AREA b WHERE a.ID_YARD_AREA = b.ID AND b.STATUS = 'AKTIF'";
                    $result_block	= $db->query($query_get_block);
                    $row_block		= $result_block->getAll();
                    foreach($row_block as $row)
                    {
                ?>
				<option value="<?php echo $row['ID']?>"><?php echo $row['NAME'] ?></option>
                <?php 
					}
                ?>
                </select>
				<button onclick="load_lp()">Go</button>
            </td>
			
        </tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
    </table>
	</div>