<?php
	$no_ukk=$_GET['id'];
	//echo $no_ukk;
	$db=getDb();
	$query = "SELECT NM_KAPAL, VOYAGE_IN, VOYAGE_OUT FROM RBM_H WHERE NO_UKK='$no_ukk'";
	$gh=$db->query($query);
	$head=$gh->fetchRow();
	
	$query_dtl = "SELECT NO_CONTAINER, SIZE_, TYPE_, STATUS,POL, IMO, BAY, LOKASI_BP, BERAT, TO_CHAR(SYSDATE,'DD-MM-YYYY HH24:Mi') JAM_PRINT, LOKASI FROM ISWS_LIST_CONTAINER WHERE NO_UKK='$no_ukk' and E_I='I' ORDER BY NO_CONTAINER";
	$gd=$db->query($query_dtl);
	$dtl=$gd->getAll();
	$i=1;
	foreach ($dtl as $row_dt)
	{
	
?>

<div style="width:767px; height:320px; border:1px solid #fff; font-family:Arial;margin:0px; padding:0px;">
<table width="767" height="320" cellspacing="3" cellpadding="3" border="0">
	<tbody>
	<tr>
		<td valign="top">
			<table width="100%" border="0" align="left">
			<tbody>
			<tr>
				<th width="15%" scope="col"></th>
				<th scope="col" colspan="4"></th>
				<th width="19%" scope="col"></th>
				<th width="6%" scope="col"></th>
				<th align="left" style="font-size:10px" colspan="5"><?=$head['NM_KAPAL']?></th>
				<th width="6%" scope="col"></th>
			</tr>
			<tr>
				<th scope="row"></th>
				<td colspan="4"></td>
				<td></td>
				<td></td>
				<td style="font-size:10px" colspan="5"><?=$head['VOYAGE_IN']?></td>
				<td></td>
			</tr>
			<tr>
				<th style="font-size:11px" scope="row">
				<?=$head['VOYAGE_IN']?>
				<br>
				<?=$no_ukk?>
				</th>
				<td align="center" style="padding-left:20px" colspan="5">
				<div align="center" style="border:2px solid #000; padding:1px; width:110px; font-family:Arial; font-size:12px; font-weight:bold">FM.02/03/01/06</div>
				</td>
				<td></td>
				<td style="font-size:12px" colspan="5"></td>
				<td></td>
			</tr>
			<tr>
				<th valign="top" height="10" align="left" style="font-size:10px; padding-left:15px" scope="row" colspan="12"></th>
				<td></td>
			</tr>
			<tr>
				<th height="30" scope="row"> </th>
				<td valign="top" align="center" colspan="4">
				<b style="font-size:22px"><?=$row_dt['NO_CONTAINER']?></b>
				</td>
				<td> </td>
				<td colspan="3"> </td>
				<td colspan="3"> </td>
				<td> </td>
			</tr>
			<tr>
				<th height="30" scope="row"> </th>
				<td width="9%" align="right"><?=$row_dt['BAY']?></td>
				<td align="center" colspan="3"><?=$row_dt['LOKASI_BP']?></td>
				<td> </td>
				<td colspan="2"> </td>
				<td colspan="2"> </td>
				<td colspan="2"> </td>
				<td> </td>
			</tr>
			<tr>
				<th height="30" scope="row"> </th>
				<td align="right"></td>
				<td align="center" colspan="2"><?=$row_dt['SIZE_']?> <?=$row_dt['TYPE_']?></td>
				<td width="9%" align="left"><?=$row_dt['STATUS']?></td>
				<td> </td>
				<td align="center" colspan="2"></td>
				<td width="5%" align="center" COLSPAN=5>
				<b><?=$row_dt['LOKASI']?></b>
				</td>
			</tr>
			<tr>
				<th height="27" scope="row"> </th>
				<td align="center" colspan="4">Temp : </td>
				<td> </td>
				<td colspan="2"> </td>
				<td> </td>
				<td> </td>
				<td> </td>
				<td> </td>
				<td> </td>
				</tr>
				<tr>
				<th height="27" scope="row"> </th>
				<td align="center" colspan="2"></td>
				<td align="center" colspan="2"><?=$row_dt['BERAT']?></td>
				<td> </td>
				<td> </td>
				<td width="6%"> </td>
				<td colspan="2"> </td>
				<td colspan="2"> </td>
				<td> </td>
			</tr>
			<tr>
				<th height="28" scope="row"> </th>
				<td align="center" colspan="4"> </td>
				<td> </td>
				<td colspan="2"> </td>
				<td colspan="2"> </td>
				<td colspan="2"> </td>
				<td> </td>
			</tr>
			<tr>
				<th height="30" scope="row"> </th>
				<td align="center" colspan="2"><?=$row_dt['POL']?></td>
				<td align="center" colspan="2">DISCH</td>
				<td> </td>
				<td colspan="2"> </td>
				<td colspan="2"> </td>
				<td colspan="2"> </td>
				<td> </td>
			</tr>
			<tr>
				<th scope="row"></th>
				<td style="font-size:11px" colspan="4">
				<em>printing number: <?=$i?> : <?=$row_dt['JAM_PRINT']?></em>
				- ISWS
				</td>
				<td></td>
				<td colspan="6"></td>
				<td></td>
			</tr>
			</tbody>
			</table>
		</td>
	</tr>
	</tbody>
</table>
</div>
<?
	$i++;
}
?>