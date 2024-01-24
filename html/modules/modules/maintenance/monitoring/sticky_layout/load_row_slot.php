<html lang="en">
	<script src="<?=HOME?>yard/src/js/jquery-1.7.min.js" type="text/javascript"></script>
	<script src="<?=HOME?>yard/src/js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?=HOME?>tooltip/stickytooltip.js"></script>
    <link rel="stylesheet" type="text/css" href="<?=HOME?>tooltip/stickytooltip.css" />
    
<head>
        <meta charset="utf-8">
    </head>

<?
	$db		= getDB();
	$yard	=$_GET['yard_id'];
	$slot	=$_GET['slot_id'];
	//$row	=$_GET['row_id'];
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
		<td style="width:80px;height:80px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:#FFFFFF;" ALIGN="center"><b>TIER</b></td>
	</tr>
	<? 
	for($x=1;$x<=$heigth;$x++)
	{
		
	?> <tr>
		<td style="width:80px;height:80px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:#FFFFFF;" ALIGN="center"><b><?=$t?></b></td>
		<?  for($i=1;$i<=$width;$i++)
			{
				$query2 = "SELECT ID_CELL,NO_CONTAINER, SIZE_, TYPE_CONT, STATUS_CONT, ID_PEL_ASAL, ID_PEL_TUJ   FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA='$block' AND SLOT_YARD='$slot' AND ROW_YARD='$i' AND TIER_YARD='$t'";
				$result3= $db->query($query2);	
				$hasil2 = $result3->fetchRow();
				$idc=$hasil2['ID_CELL'];
				$no_cont=$hasil2['NO_CONTAINER'];
				$size_cont=$hasil2['SIZE_'];
				$type_cont=$hasil2['TYPE_CONT'];
				$status_cont=$hasil2['STATUS_CONT'];
				$idp=$hasil2['ID_PEL_ASAL'];
				$idt=$hasil2['ID_PEL_TUJ'];
				IF($no_cont<>'')
				{
				?>
				<!--<td data-tooltip="stickycoba" onMouseOver="visit(<?=$idc?>)" style="width:40px;height:40px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:#86BCFF;" align="center" >
				<img src="<?=HOME?>images/row_cont2.png" width="40px" height="40px">
				</td>-->
				<td style="width:80px;height:80px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:#eaeaea;" align="center" >
				<table frame="border" style="font-weight:bold;">
				<tr>
					<td><?=$idp?></td>
					<td>&nbsp;</td>
					<td align="right"><?=$idt?></td>
				</tr>
				<tr>
					<td colspan=3><?=$no_cont?>&nbsp;&nbsp;</td>
				</tr>
				<tr>
					<td>MSK</td>
					<td>&nbsp;</td>
					<td align='right'>30</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td colspan=2 align='right'><?=$size_cont?><?=$type_cont?></td>
				</tr>
				<tr>
					<td>259</td>
					<td>&nbsp;</td>
					<td align='right'>&nbsp;</td>
				</tr>
				</table>
				</td>
				<?
				}
				else
				{
				
		?>
			<td style="width:80px;height:80px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:#86BCFF;" align="center">
			&nbsp;</td>
	<?}
	}?></tr><?$t--;
	}
	
?>
		
		
	<!-- sumbu x -->	
		<tr>
		<td style="width:80px;height:80px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:#FFFFFF;" ALIGN="center">&nbsp;</td>
		<? for($i=1;$i<=$width;$i++){?>
			
			<td style="width:80px;height:80px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:#FFFFFF;" align="center"><b>
			<?=$i?></b></td>
		
	<?}?>
			<td style="width:80px;height:80px;font-size:8px; font-family:Tahoma; border:1px solid #ffffff;background-color:#FFFFFF;" ALIGN="center"><b> ROW </b></td>
	</tr>
	<?//}?>
	</tbody>
	</table>	
</div>
</html>