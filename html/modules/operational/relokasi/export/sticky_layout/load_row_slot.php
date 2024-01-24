<html lang="en">
<head>
        <meta charset="utf-8">
    </head>

<?
	$db		= getDB();
	$yard	=$_GET['yard_id'];
	$slot	=$_GET['slot_id'];
	$row_id	=$_GET['row_id'];
	$tier_id =$_GET['tier_id'];
	$block	=$_GET['block_id'];
	
	$query_ = "SELECT NAME, TIER, (select count(1) from YD_BLOCKING_CELL b WHERE b.ID_BLOCKING_AREA='$block' and b.SLOT_='$slot') AS JML_ROW FROM YD_BLOCKING_AREA a WHERE a.ID_YARD_AREA='$yard' AND a.ID='$block'";
	
    $result_= $db->query($query_);	
	$hasil1 = $result_->fetchRow();
	$blok = $hasil1['NAME'];
	$tier = $hasil1['TIER'];
	$row = $hasil1['JML_ROW'];
	//print_r($row);
    $width=$row;
	$heigth=$tier;
	$t=$heigth;
	$u=$width;
	$L = $width * $height;
	
	
?>
<div id="mapping2w2">
	<table>
		<tr>
			<td data-tooltip="stickyz1"><b>BLOK <?=$blok?> SLOT <?=$slot?> </b></td>
		</tr>
	</table>
	<table border="0" align="center" >
	<tbody>
	<tr>
		<td style="width:40px;height:40px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:#FFFFFF;" ALIGN="center"><b>TIER</b></td>
	</tr>
	<? 
	for($x=1;$x<=$heigth;$x++)
	{
		
	?> <tr>
		<td style="width:40px;height:40px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:#FFFFFF;" ALIGN="center"><b><?=$t?></b></td>
		<?  for($i=1;$i<=$width;$i++)
			{
				$query2 = "SELECT NO_CONTAINER, SIZE_, TYPE_CONT, STATUS_CONT FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA='$block' AND SLOT_YARD='$slot' AND ROW_YARD='$i' AND TIER_YARD='$t'";
				$result3= $db->query($query2);	
				$hasil2 = $result3->fetchRow();
				
				$no_cont=$hasil2['NO_CONTAINER'];
				$size_cont=$hasil2['SIZE_'];
				$type_cont=$hasil2['TYPE_CONT'];
				$status_cont=$hasil2['STATUS_CONT'];
				
				IF(($no_cont<>'')AND($row_id==$i)and($tier_id==$t))
				{
				?>
				<td style="width:40px;height:40px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:RED;" align="center" >
				<img src="<?=HOME?>images/row_cont2.png" width="30px" height="30px">
				</td>
				<?
				}
				ELSE IF(($no_cont=='')AND($row_id==$i)and($tier_id==$t))
				{
				?>
				<td style="width:40px;height:40px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:red;" align="center" >
				&nbsp;
				</td>
				<?
				}
				
				else if(($row_id==$i)and($tier_id==$t))
				{
				?>
				<td style="width:40px;height:40px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:red;" align="center" >
				&nbsp;
				</td>
				<?
				}
				
				else
				{
				
		?>
			<td style="width:40px;height:40px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:#86BCFF;" align="center">
			&nbsp;</td>
	<?}
	}?></tr><?$t--;
	}
	
?>
		
		
	<!-- sumbu x -->	
		<tr>
		<td style="width:30px;height:30px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:#FFFFFF;" ALIGN="center">&nbsp;</td>
		<? for($i=1;$i<=$width;$i++){?>
			
			<td style="width:30px;height:30px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:#FFFFFF;" align="center"><b>
			<?=$i?></b></td>
		
	<?}?>
			<td style="width:30px;height:30px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:#FFFFFF;" ALIGN="center"><b> ROW </b></td>
	</tr>
	<?//}?>
	</tbody>
	</table>	
</div>
</html>