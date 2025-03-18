<?php
	$x=$_GET['no_cont'];
	$no_container = str_replace("'","",$x);
	$db=getDb();
	/*
	$query="SELECT DISTINCT ypy.NO_CONTAINER,
							ypy.SIZE_, 
							ypy.TYPE_CONT, 
							ypy.STATUS_CONT, 
							ypy.TON,
							ypy.ID_PEL_TUJ AS POD,
							ypy.HZ,
							ypy.NAMA_BLOCK,
							ypy.ROW_YARD,
							ypy.TIER_YARD,
							ypy.ID_BLOCKING_AREA,
							rbmh.NM_KAPAL AS VESSEL,
							rbmh.VOYAGE_IN||'/'||rbmh.VOYAGE_OUT AS VOYAGE,
							ypy.ID_VS
				   FROM YD_PLACEMENT_YARD ypy, RBM_H rbmh, STW_PLACEMENT_BAY stw
				   WHERE ypy.ACTIVITY = 'MUAT'
						AND ypy.STOWAGE = 'P'
						AND stw.STATUS_PLC = 'PLANNING'
						AND ypy.ID_VS = stw.ID_VS
						AND stw.ID_VS = rbmh.NO_UKK
						AND ypy.ID_VS = rbmh.NO_UKK
						AND ypy.NO_CONTAINER = stw.NO_CONTAINER
						AND ypy.NO_CONTAINER = '$no_container'";
	*/
	
	$query="SELECT DISTINCT ypy.NO_CONTAINER,
							ypy.SIZE_, 
							ypy.TYPE_CONT, 
							ypy.STATUS_CONT, 
							ypy.TON,
							ypy.ID_PEL_TUJ AS POD,
							ypy.HZ,
							ypy.NAMA_BLOCK,
							ypy.ROW_YARD,
							ypy.TIER_YARD,
							ypy.ID_BLOCKING_AREA,
							rbmh.NM_KAPAL AS VESSEL,
							rbmh.VOYAGE_IN||'/'||rbmh.VOYAGE_OUT AS VOYAGE,
							ypy.ID_VS
				   FROM YD_PLACEMENT_YARD ypy, RBM_H rbmh
				   WHERE ypy.ACTIVITY = 'MUAT'
						AND ypy.STOWAGE IN ('T','P')
						AND TRIM(ypy.ID_VS) = TRIM(rbmh.NO_UKK)
						AND ypy.NO_CONTAINER = '$no_container'";
	
	$g = $db->query($query);
	$row = $g->fetchRow();

	if($x!=NULL)
	{
?>	
	<div id="table_placement_planning">
	<table class="grid-table" border='0' cellpadding="1" cellspacing="1"  width="100%" id="list_cont_dev">
	<tr>
		<th class="grid-header">No.</th>	
		<th class="grid-header">No Container</th>
		<th class="grid-header">Size</th>
		<th class="grid-header">Type</th>
		<th class="grid-header">Status</th>
		<th class="grid-header">Hz</th>
		<th class="grid-header">Vessel</th>
		<th class="grid-header">YARD<br/>B S R T</th>
		<th class="grid-header">BAYPLAN<br/>B R T</th>
		<th class="grid-header">Action</th>
	</tr>
		
	<?  
		if($row['SIZE_']=='40')
		{
			$query_slot="SELECT SLOT_YARD
						FROM YD_PLACEMENT_YARD
						WHERE NO_CONTAINER = '$no_container'";
				$g_slt = $db->query($query_slot);
				$slot = $g_slt->getAll();
				
					$n=0;
					foreach($slot as $r)
					{
					  $a[$n]=$r['SLOT_YARD'];
					  $n++;
					}		
					$slots = implode("-",$a);
			
			$no_ukk = $row['ID_VS'];
			$query_stw="SELECT BAY,ROW_,TIER_
						FROM STW_PLACEMENT_BAY
						WHERE ID_VS = '$no_ukk'
							AND NO_CONTAINER = '$no_container'";
			$g_stw = $db->query($query_stw);
			$stw = $g_stw->getAll();

			foreach($stw as $rw_stow)
			{
				$bay_no = $rw_stow['BAY'];				
				if(($bay_no==1)||(($bay_no-1)%4==0))
				{
					$bays = $bay_no+1;
					$brt = $bays." ".$rw_stow['ROW_']." ".$rw_stow['TIER_'];
				}
			}
			
		}
		else
		{
			$query_slot="SELECT SLOT_YARD
						FROM YD_PLACEMENT_YARD
						WHERE NO_CONTAINER = '$no_container'";
				$g_slt = $db->query($query_slot);
				$slot = $g_slt->fetchRow();
				$slots = $slot['SLOT_YARD'];
				
			$no_ukk = $row['ID_VS'];
			$query_stw="SELECT BAY,ROW_,TIER_
						FROM STW_PLACEMENT_BAY
						WHERE ID_VS = '$no_ukk'
							AND NO_CONTAINER = '$no_container'";
			$g_stw = $db->query($query_stw);
			$stw = $g_stw->fetchRow();
			$brt = $stw['BAY']." ".$stw['ROW_']." ".$stw['TIER_'];
		}
		
		
	
	?>
	<tr>
		<td align='center'>1</td>
		<td align='center'><?=$row['NO_CONTAINER']?></td>
		<td align='center'><?=$row['SIZE_']?></td>
		<td align='center'><?=$row['TYPE_CONT']?></td>
		<td align='center'><?=$row['STATUS_CONT']?></td>
		<td align='center'><?=$row['HZ']?></td>
		<td align='center'><?=$row['VESSEL']?> [ <?=$row['VOYAGE']?> ]</td>
		<td align='center'><?=$row['NAMA_BLOCK']?>&nbsp;<?=$slots?>&nbsp;<?=$row['ROW_YARD']?>&nbsp;<?=$row['TIER_YARD']?></td>
		<td align='center'><?=$brt?></td>
		<td align='center'>
			<!-- update by gandul 26 April 2013, by pass position should be right?-->
			<!--<button onclick="loading_confirm('<?=$no_ukk?>','<?=$row['NO_CONTAINER']?>')" title="loading confirm"><img src="<?=HOME?>images/confirm.png" width="10px" height="10px" border="0"></button>&nbsp;&nbsp;-->
			<button onclick="plc_bay('<?=$no_ukk?>','<?=$row['NO_CONTAINER']?>')" title="placement bay"><img src="<?=HOME?>images/yardmap.png" width="10px" height="10px" border="0"></button>
		</td>
	</tr>
	</table>
	</div>
<?	
	}
	else
	{
?>	
		<div id="table_placement_planning">
	<table class="grid-table" border='0' cellpadding="1" cellspacing="1"  width="100%" id="list_cont_dev">
	<tr>
		<th class="grid-header">No.</th>	
		<th class="grid-header">No Container</th>
		<th class="grid-header">Size</th>
		<th class="grid-header">Type</th>
		<th class="grid-header">Status</th>
		<th class="grid-header">Hz</th>
		<th class="grid-header">Vessel</th>
		<th class="grid-header">YARD<br/>B S R T</th>
		<th class="grid-header">BAYPLAN<br/>B R T</th>
		<th class="grid-header">Action</th>
	</tr>
	</table>
	</div>
<?	
	}
?>


