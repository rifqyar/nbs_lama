<?php
$req=$_GET['no_req'];
$db=getDb();
$qr="SELECT B.CLOSSING_TIME AS CLOSING_TIME_DOC, B.CLOSSING_TIME AS CLOSING_TIME,A.NO_CONTAINER, A.SIZE_CONT, A.TYPE_CONT, 
A.STATUS_CONT, A.HZ, A.VESSEL, A.VOYAGE_IN, A.VOYAGE_OUT, A.ID_PEL_ASAL, A.ID_PEL_TUJ,
A.PEL_TUJ NM_PELABUHAN_TUJUAN,C.NPE, C.PEB, C.KODE_PBM, A.IMO_CLASS,
       A.BERAT
  FROM REQ_RECEIVING_D A,  REQ_RECEIVING_H C, m_vsb_voyage@dbint_link B WHERE C.NO_UKK=TO_CHAR(B.ID_VSB_VOYAGE) AND TRIM(A.NO_REQ_ANNE)=TRIM(C.ID_REQ) and A.NO_REQ_ANNE='$req'";

$eqr=$db->query($qr);
$rqr=$eqr->getAll();
?>
<style>
body { margin:0px; padding:0px; width:100%; height:100%;}
.txc { height:2px; padding:0px; margin:0px; line-height:9px }

</style> 

<div style="height:5px; width:767px; height:320px; border:1px solid  #FFF">   
<table border="0" cellpadding="3" cellspacing="3" style="margin:0px; margin-top:10px; margin-bottom:10px">
<?php 
	foreach($rqr as $row)
	{
?>
  <!--<tr>
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
  <tr>
    <th  class="txc"   scope="row"></th>
    <td  class="txc"></td>
    <td  class="txc"></td>
    <td  class="txc"></td>
    <td  class="txc"></td>
    <td  class="txc"></td>
    <td  class="txc"></td>
    <td  class="txc"></td>
    <td  class="txc" colspan="2" style="font-size:11px; font-family:Arial"></td>
    <td  class="txc"></td>
  </tr>
  <tr>
    <th   class="txc" scope="row"></th>
    <td  class="txc"></td>
    <td  class="txc"></td>
    <td  class="txc" colspan="5"><div  style="padding-right:150px; font-size:13px"><em>Clossing Time : <?=$row[CLOSING_TIME]?></em></div></td>
    <td  class="txc" colspan="2" style="font-size:11px; font-family:Arial"></td>
    <td  class="txc"></td>
  </tr> 
  <tr>
    <th height="20" colspan="8" align="left"   scope="row" style="font-size:11px; padding-left:15px; line-height:10px"><br /></th>
    <td width="10%"></td>
    <td width="8%"></td> 
    <td></td>
  </tr>
  <tr>
    <th  height="30" scope="row"></th>
    <td colspan="5" align="center"><b style="font-size:20px; line-height:5px"><?=$row[NO_CONTAINER];?></b></td>
    <td></td>
    <td colspan="2" align="center" style="font-size:14px"><?=$row[VESSEL];?></td>
    <td align="left"><?=$row[VOYAGE_IN];?> - <?=$row[VOYAGE_OUT];?></td>
    <td></td>
  </tr>
  <tr>
    <th  height="30" scope="row"></th>
    <td align="center"><?=$row[SIZE_CONT];?></td>
    <td colspan="3" align="center"><?=$row[TYPE_CONT];?></td>
    <td align="left"><?=$row[STATUS_CONT];?></td>
    <td></td>
    <td align="center"><?=$row[NM_PELABUHAN_TUJUAN];?></td>
    <td colspan="2"></td>
    <td></td>
  </tr>
  <tr>
	<th  height="30" scope="row"></th>
    <td align="center">IMO:</td>
    <td colspan="3" align="center">/</td>
    <td align="left">GROSS :</td>
    <td></td>
    <td align="center"><?=$row[NM_PELABUHAN_TUJUAN];?></td>
    <td colspan="2"></td>
    <td></td>
  </tr>
  <tr>
    <th  height="30" scope="row"></th>
    <td></td>
    <td colspan="3" align="center"></td>
    <td></td>
    <td></td>
    <td colspan="3"></td>
    <td></td>
  </tr>
  <tr>
    <th  height="33" scope="row"></th>
    <td colspan="3"></td>
    <td colspan="2"></td>
    <td></td>
    <td></td>
    <td></td> 
    <td></td>
    <td></td>
  </tr>
  <tr>
    <th  height="30" scope="row"></th>
    <td colspan="5" align="center">&nbsp;</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <th  height="30" scope="row"></th>
    <td align="center"></td> 
    <td align="center"></td>
    <td align="center"></td>
    <td align="center"></td>
    <td  align="center"></td>
    <td></td>
    <td colspan="3"></td>
    <td></td>
  </tr>
  <tr>
    <th  height="26" scope="row"></th>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td colspan="3" style="font-size:10px"><?=$row[KODE_PBM];?></td>
    <td></td>
  </tr>
  <tr>
    <th  height="26" scope="row"></th>
    <td colspan="5"><!--NO PEB : {$rowd.NO_NPE}--><!--OPUS - NO URUT</td> 
    <td></td>
    <td style="font-size:14px"></td>
    <td style="font-size:14px"></td>
    <td style="font-size:14px"><?=$SESSION[NAMA_PENGGUNA];?></td> 
    <td>&nbsp;</td>
  </tr> -->
  <tr>
		<th class="txc" width="13%"  scope="col"></th><th class="txc" width="5%" scope="col"></th>
		<th class="txc" width="5%" scope="col"></th><th class="txc" width="5%" scope="col"></th>
		<th class="txc" width="5%" scope="col"></th><th class="txc" width="8%" scope="col"></th>
		<th class="txc" width="15%" scope="col"></th><th class="txc" width="11%" scope="col"></th>
		<th class="txc" colspan="2" align="left" scope="col" style="font-size:11px; font-family:Arial">959905</th>
		<th class="txc" width="1%" scope="col"></th>
	</tr>
	<tr><th  class="txc"   scope="row"></th>
		<td  class="txc"></td><td  class="txc"></td><td  class="txc"></td><td  class="txc"></td>
		<td  class="txc"></td><td  class="txc"></td><td  class="txc"></td><td  class="txc" colspan="2" style="font-size:11px; font-family:Arial"></td><td  class="txc"></td>
	</tr>
	<tr>
		<th   class="txc" scope="row"></th>
			<td  class="txc"></td>
			<td  class="txc"></td>
			<td  class="txc" colspan="5">
				<div  style="padding-right:150px; font-size:13px"><em></em><em>Clossing Time : <?=$row[CLOSING_TIME]?></em></div>
			</td>
			<td  class="txc" colspan="2" style="font-size:11px; font-family:Arial">&nbsp;&nbsp;</td>
			<td  class="txc"></td>
	</tr>
	<tr>
		<th height="20" colspan="8" align="left"   scope="row" style="font-size:11px; padding-left:15px; line-height:10px"><?=$row[VOYAGE_IN];?> - <?=$row[VOYAGE_OUT];?><br /> <?=$row[NO_CONTAINER];?></th>
			<td width="10%"></td>
			<td width="8%"></td>
			<td></td>
	</tr>
	<tr>
		<th  height="20" scope="row"></th>
		<td colspan="5" align="center"><b style="font-size:20px;"> &nbsp;&nbsp;&nbsp;<?=$row[NO_CONTAINER];?> - <?=$row[VOYAGE_IN];?> - <?=$row[VOYAGE_OUT];?></b></td>
		<td></td>
		<td colspan="2" align="center" style="font-size:14px"><?=$row[VESSEL];?></td>
		<td align="left" style="font-size:14px"><?=$row[VOYAGE_IN];?> - <?=$row[VOYAGE_OUT];?></td>
		<td></td>
	</tr>
	<tr>
		<th  height="20" scope="row"></th>
		<td align="center">&nbsp;</td>
		<td colspan="3" align="center"><?=$row[SIZE_CONT];?> - <?=$row[TYPE_CONT];?> - <?=$row[STATUS_CONT];?></td>
		<td align="left">&nbsp;</td>
		<td></td>
		<td align="right"><?=$row[ID_PEL_ASAL];?> /</td>
		<td colspan="2" align="left" ><?=$row[ID_PEL_TUJ];?></td>
		<td></td>
	</tr>
	<tr>
		<th  height="20" scope="row"></th>
		<td></td>
		<td colspan="4" align="center">IMO Class <?=$row[IMO_CLASS];?> :  / Gross : <?=$row[BERAT];?></td>
		<td></td>
		<td colspan="3">
			<span style="font-size:9px;">(<?=$row[NM_PELABUHAN_TUJUAN];?>)</span>
		</td>
		<td></td>
	</tr>
	<tr><th  height="30" scope="row"></th><td colspan="5" align="center">Temperature : </td><td></td><td></td><td></td><td></td><td></td></tr>
	<tr><th  height="30" scope="row"></th><td align="center">&nbsp;&nbsp;</td><td align="center"></td><td align="center"></td><td align="center"></td><td  align="center">&nbsp;</td><td></td><td colspan="3"><span style="font-size:10px"><?=$row[KODE_PBM];?></span></td><td></td></tr>
	<tr>
		<th  height="126" scope="row"></th>
		<td></td>
		<td colspan="4" valign="top"><em> <br /> 744334- NPE :697711</em></td>
		<td></td>
		<td colspan="3" valign="top" align="right" style="font-size:10px">
			<span style="font-size:14px"><br />&nbsp;<br />ganda&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
		</td>
		<td></td>
	</tr>
	<tr>
		<th  height="26" scope="row"></th>
		<td colspan="5"><!--NO PEB : 697711--><strong><span style="font-size:9px">&nbsp;&nbsp;&nbsp;OPUS</span> | NO URUT: </strong></td>
		<td></td>
		<td colspan="2" style="font-size:14px"><strong> CETAKAN KARTU KE : </strong></td>
		<td style="font-size:14px">&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
  <?php }?>
</table>
</div>
<div style=" height:5px; border:1px solid #FFF"></div>
<div style=" margin-top:20px;"></div>



  