<?php
	$x[]=$_GET['no_cont'];
	//print_r($x.'<br>');
	$db=getDb();
	$a=count($x);
	$b=$a-1;
	$d='';
	for ($y=0;$y<=$a;$y++)
	{
		//print_r($y.'<br>');
		
		//print_r($b.'<br>');
		
		if(($y<>$b) and ($x[$y]<>''))
		{
			$d=$d.$x[$y].',';
			
		}
		else
		{
			$d=$d.$x[$y];
		}
	}
	
	//print_r($d);
	if ($d<>'')
	{
		$query="SELECT b.NO_CONT, TO_CHAR(b.ID_JOB_SLIP) ID_JOB_SLIP, b.ID_VS, b.VESSEL, b.VOYAGE, b.SIZE_, b.TYPE_, b.STATUS_, b.ID_BLOCK, b.NAMA_BLOCK, b.SLOT_, b.ROW_, b.TIER_,b.HZ,b.STATUS_STACK,
		a.NAMA_BLOCK as NAMA_BLOCK2, a.SLOT_YARD, a.ROW_YARD, a.TIER_YARD 
		FROM TB_CONT_JOBSLIP b left JOIN YD_PLACEMENT_YARD a ON TRIM(a.ID_VS)=TRIM(b.ID_VS) AND TRIM(a.NO_CONTAINER)=TRIM(b.NO_CONT) WHERE TRIM(b.NO_CONT) IN(".$d.")
		";
	}
	else
		$query="SELECT NO_CONT, ID_JOB_SLIP, ID_VS, VESSEL, VOYAGE, SIZE_, TYPE_, STATUS_, ID_BLOCK, NAMA_BLOCK, SLOT_, ROW_, TIER_,HZ FROM TB_CONT_JOBSLIP WHERE TRIM(NO_CONT) IN('')";
	//print_r($query);DIE;
	$g= $db->query($query);
	$row= $g->getAll();	
	$A=1;
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
		<th class="grid-header" rowspan=2>Vessel/Voy</th>
		<th class="grid-header" colspan=2>B S R T</th>
		<th class="grid-header" rowspan=2>Action</th>
		</tr>
		<th class="grid-header">Planning</th>
		<th class="grid-header">After Placement</th>
	</tr>
		
	<? foreach($row as $rows)
	{
	?>
	<tr>
		<td align='center'><?=$A?></td>
		<td align='center'><?=$rows['NO_CONT']?></td>
		<td align='center'><?=$rows['SIZE_']?></td>
		<td align='center'><?=$rows['TYPE_']?></td>
		<td align='center'><?=$rows['STATUS_']?></td>
		<td align='center'><?=$rows['HZ']?></td>
		<td align='center'><?=$rows['VESSEL']?> / <?=$rows['VOYAGE']?></td>
		<td align='center'><?=$rows['NAMA_BLOCK']?> / <?=$rows['SLOT_']?> <?//=$rows['ROW_']?>  <?//=$rows['TIER_']?></td>
		<td align='center'><?=$rows['NAMA_BLOCK2']?> / <?=$rows['SLOT_YARD']?> <?//=$rows['ROW_YARD']?>  <?//=$rows['TIER_YARD']?></td>
		<td align='center'>
		<?
			$st_stc=$rows['STATUS_STACK'];
			if ($st_stc=='0')
			{
		?>
		<input type="button" value=" Placement " onclick="koreksi_placement(<?=$rows['ID_BLOCK']?>,<?=$rows['SLOT_']?>,<?=$rows['ROW_']?>,<?=$rows['TIER_']?>,<?=$rows['ID_JOB_SLIP']?>)"> &nbsp; 
		<!--<input type="button" value=" Placement " onclick="preview_blok(<?=$rows['ID_BLOCK']?>,<?=$rows['SLOT_']?>,<?=$rows['ROW_']?>,<?=$rows['TIER_']?>,<?=$rows['ID_JOB_SLIP']?>)">-->
		<?
		}
		Else
		{
		?>
			<font color="green"><b><i>sudah placement</i></b> &nbsp;</font><img src="./images/confirm.png" width="10%" height="10%">
		<?
		}
		?>
		</td>
	</tr>
	<?
	$A++;
	}?>
	</table>
</div>