<style>
		body { margin:0px; padding:0px; width:100%; height:100%;}
		.txc { height:2px; padding:0px; margin:0px; line-height:9px }
	</style>
	

<?php
	$no_req=$_GET['no_req'];

?>
<?php

$db = getDB();
	$query_nota	= "SELECT TO_CHAR (a.CLOSSING_TIME, 'dd/mm/yyyy HH24:MI') CLOSSING_TIME,
					   a.VESSEL,
					   a.VOYAGE,
					   b.VOYAGE_OUT,
					   c.STATUS_CONT,
					   c.SIZE_ SIZE_CONT,
					   c.TYPE_ AS TYPE_CONT,
					   c.NO_CONTAINER,
					   b.BERAT,
					   a.PELABUHAN_TUJUAN,
					   a.FPOD,
					   c.POD IPOD,
					   c.FPOD FIPOD,
					   a.PEB,
					   a.NPE,
					   a.KODE_PBM,
					   b.IMO_CLASS
				  FROM req_receiving_h a, req_receiving_d b, rename_container c
				 WHERE a.ID_REQ = b.no_req_anne AND C.NO_RENAME = '$no_req'
					   AND ( ( (TRIM (c.no_container) = TRIM (b.no_container))
							  OR (TRIM (c.no_ex_container) = TRIM (b.no_container))))";
	$result_nota	= $db->query($query_nota);
	$row_			= $result_nota->fetchRow();
	


	$i = 1;
	$page=0;
	
	?>
	
	
	<div style="width:767px; height:<?= $height;?>px; border:0px solid  #FFF">
	<table border="0" cellpadding="1" cellspacing="1" style="margin:0px; margin-top:0px; margin-bottom:0px; border:0px solid #000">
	<tr>
		<th class="txc" width="13%"  scope="col"></th>
		<th class="txc" width="5%" scope="col"></th>
		<th class="txc" width="5%" scope="col"></th>
		<th class="txc" width="5%" scope="col"></th>
		<th class="txc" width="5%" scope="col"></th>
		<th class="txc" width="8%" scope="col"></th>
		<th class="txc" width="15%" scope="col"></th>
		<th class="txc" width="11%" scope="col"></th>
		<th class="txc" colspan="2" align="left" scope="col" style="font-size:11px; font-family:Arial"></th>
		<th class="txc" width="1%" scope="col"></th>
	</tr>
	<tr height="35px">
		<th  class="txc"   scope="row"></th>
		<td  class="txc"></td>
		<td  class="txc"></td>
		<td  class="txc"></td>
		<td  class="txc"></td>
		<td  class="txc"></td>
		<td  class="txc"><?php if($i%2 != 1){ ?>Page : <?=$page; }?></td>
		<td  class="txc"></td>
		<td  class="txc" colspan="2" style="font-size:11px; font-family:Arial"></td>
		<td  class="txc"></td>
	</tr>
	<tr>
		<th  class="txc" scope="row"></th>
		<td  class="txc"></td>
		<td  class="txc"></td>
		<td  class="txc" colspan="5">
			
			<div  style="padding-right:150px; font-size:13px">
			<em></em><em>Clossing Time : <?=$row_['CLOSSING_TIME']?></em>
			</div>
		</td>
		<td  class="txc" colspan="2" style="font-size:11px; font-family:Arial">&nbsp;&nbsp;</td>
		<td  class="txc"></td>
	</tr>
	<tr>
		<th height="25" colspan="8" align="left"   scope="row" style="font-size:11px; padding-left:15px; line-height:10px"><?=$row_['VOYAGE'] ?><br /> <?=$row_['NO_CONTAINER']?></th>
		<td width="10%"></td>
		<td width="8%"></td>
		<td></td>
	</tr>
	<tr>
		<th height="20" scope="row"></th>
		<td colspan="5" align="center">
		<b style="font-size:20px;"> &nbsp;&nbsp;&nbsp;<?=$row_['NO_CONTAINER']?> - <?=$row_['VOYAGE_OUT']?></b></td>
		<td></td>
		<td colspan="2" align="center" style="font-size:14px"><?=$row_['VESSEL']?></td>
		<td align="left"><?=$row_['VOYAGE_OUT']?></td>
		<td></td>
	</tr>
	<tr>
		<th  height="20" scope="row"></th>
		<td align="center">&nbsp;</td>
		<td colspan="3" align="center"><?=$row_['SIZE_CONT']?> - <?=$row_['TYPE_CONT']?> - <?=$row_['STATUS_CONT']?></td>
		<td align="left">&nbsp;</td>
		<td></td>
		<td align="right"><?=$row_['IPOD']?> /</td>
		<td colspan="2" align="left" ><?=$row_['FIPOD']?></td>
		<td></td>
	</tr>
	<tr>
		<th  height="20" scope="row"></th>
		<td></td>
		<td colspan="4" align="center">IMO Class :  <?=$row_['IMO_CLASS'];?>/ Gross :<?=$row_['GROSS']?></td>
		<td></td>
		<td colspan="3"><span style="font-size:9px;">(<?=$row_['PELABUHAN_TUJUAN']?>)</span></td>
		<td></td>
	</tr>
	<tr>
		<th  height="30" scope="row"></th>
		<td colspan="5" align="center">Temperature : </td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<th  height="30" scope="row"></th>
		<td align="center">&nbsp;&nbsp;</td>
		<td align="center"></td>
		<td align="center"></td>
		<td align="center"></td>
		<td  align="center">&nbsp;</td>
		<td></td>
		<td colspan="3"><span style="font-size:10px"><?=$row_['KODE_PBM']?></span></td>
		<td></td>
	</tr>
	<tr>
		<th  height="126" scope="row"></th>
		<td></td>
		<td colspan="4" valign="top"><em> <br /> <?=$row_['PEB']?>- NPE :<?=$row_['NPE']?></em></td>
		<td></td>
		<td colspan="3" valign="top" align="right" style="font-size:10px"><span style="font-size:14px"><br />&nbsp;<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
		<td></td>
	</tr>
	<tr>
		<th  height="26" scope="row"></th>
		<td colspan="5"><!--NO PEB : 005149--><strong><span style="font-size:9px">&nbsp;&nbsp;&nbsp;NBS - KARTU MERAH</span> | NO URUT: -</strong></td>
		<td></td>
		<td colspan="2" style="font-size:14px"><strong> CETAKAN KARTU KE : 1</strong></td>
		<td style="font-size:14px">&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
</table>
</div>
