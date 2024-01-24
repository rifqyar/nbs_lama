<?php
	$x=$_GET['no_cont'];
	$no_container = str_replace("'","",$x);
	$db=getDb();
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
							rbmh.VOYAGE_IN||'/'||rbmh.VOYAGE_OUT AS VOYAGE
				   FROM YD_PLACEMENT_YARD ypy, RBM_H rbmh
				   WHERE ypy.ACTIVITY = 'BONGKAR'
						AND ypy.ID_VS = rbmh.NO_UKK
						AND ypy.NO_CONTAINER = '$no_container'";
	$g = $db->query($query);
	$row = $g->fetchRow();

	if($x!=NULL)
	{
?>	
	<div id="table_placement_planning">
	<table class="grid-table" border='0' cellpadding="1" cellspacing="1"  width="100%" id="list_cont_dev">
	<tr>
		<th class="grid-header" rowspan=2>No.</th>	
		<th class="grid-header" rowspan=2>No Container</th>
		<th class="grid-header" rowspan=2>Size</th>
		<th class="grid-header" rowspan=2>Type</th>
		<th class="grid-header" rowspan=2>Status</th>
		<th class="grid-header" rowspan=2>Hz</th>
		<th class="grid-header" rowspan=2>Vessel</th>
		<th class="grid-header" colspan=2>B S R T</th>
		<th class="grid-header" rowspan=2>Action</th>
	</tr>
	<tr>
		<th class="grid-header">Before</th>
		<th class="grid-header">After</th>
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
		}
		else
		{
			$query_slot="SELECT SLOT_YARD
						FROM YD_PLACEMENT_YARD
						WHERE NO_CONTAINER = '$no_container'";
				$g_slt = $db->query($query_slot);
				$slot = $g_slt->fetchRow();
				$slots = $slot['SLOT_YARD'];
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
		<td align='center'>&nbsp;</td>
		<td align='center'>
			<input type="button" value=" Relocation " onclick="relocate('<?=$row['NO_CONTAINER']?>','<?=$row['SIZE_']?>','<?=$row['ID_BLOCKING_AREA']?>','<?=$row['NAMA_BLOCK']?>','<?=$slots?>','<?=$row['ROW_YARD']?>','<?=$row['TIER_YARD']?>')" />
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
		<th class="grid-header" rowspan=2>No.</th>	
		<th class="grid-header" rowspan=2>No Container</th>
		<th class="grid-header" rowspan=2>Size</th>
		<th class="grid-header" rowspan=2>Type</th>
		<th class="grid-header" rowspan=2>Status</th>
		<th class="grid-header" rowspan=2>Hz</th>
		<th class="grid-header" rowspan=2>Vessel</th>
		<th class="grid-header" colspan=2>B S R T</th>
		<th class="grid-header" rowspan=2>Action</th>
	</tr>
	<tr>
		<th class="grid-header">Before</th>
		<th class="grid-header">After</th>
	</tr>
	</table>
	</div>
<?	
	}
?>


