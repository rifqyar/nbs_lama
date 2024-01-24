<?php
	$x=$_GET['no_cont'];
	$bukk=$_GET['vukk'];
	$blok=$_GET['block_id'];
	$arrblok= explode(',',$blok);
	$r=$arrblok[0];
	$slot=$_GET['slot_id'];
	
	//print_r($x.'<br>');
	$db=getDb();
	$a=count($x);
	$b=$a-1;
	
	//print_r($d);
	$query="SELECT A.NO_CONTAINER NO_CONT, A.NO_UKK ID_VS, A.SIZE_, A.TYPE_, A.STATUS AS STATUS_, 
	A.LOKASI AS ID_BLOCK,
	(SELECT C.ID FROM YD_BLOCKING_AREA C WHERE C.NAME=(SELECT * FROM TABLE (CAST (explode(',',A.LOKASI) AS listtable)) WHERE ROWNUM=1) AND ID_YARD_AREA='23') AS ID_BLOCK2,
						  substr(A.LOKASI, 5, 1) AS SLOT_,
	A.HZ,
	 B.NM_KAPAL AS VESSEL, 
						  B.VOYAGE_IN AS VOYAGE, A.TGL_PLACEMENT
                    FROM ISWS_LIST_CONTAINER A, RBM_H B 
					WHERE TRIM(A.NO_UKK)=TRIM(B.NO_UKK) AND
	A.NO_CONTAINER='$x' and A.E_I='I' AND A.NO_UKK='$bukk'";
	//print_r($query);
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
		<td align='center'><?=$rows['ID_BLOCK']?> / <?=$rows['SLOT_']?> <?//=$rows['ROW_']?>  <?//=$rows['TIER_']?></td>
		<td align='center'><?//=$rows['NAMA_BLOCK2']?> / <?//=$rows['SLOT_YARD']?> <?//=$rows['ROW_YARD']?>  <?//=$rows['TIER_YARD']?></td>
		<td align='center'>
		<?
			$st_stc=$rows['PLACEMENT_DATE'];
			if ($st_stc=='')
			{
		?>
		<input type="button" value=" Placement " onclick="koreksi_placement('<?=$rows['ID_BLOCK2']?>','<?=$slot?>','<?=$x?>','<?=$bukk?>')"> &nbsp; 
		<?
		}
		Else
		{
		?>
			<font color="green"><b><i>placement <?=$rows['TGL_PLACEMENT']?></i></b> &nbsp;</font><img src="./images/confirm.png" width="10%" height="10%">
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