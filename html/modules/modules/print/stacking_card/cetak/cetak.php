<?php

$db			= getDB();
$no_req		= $_GET["no_req"];


	$query_nota	= " /* Formatted on 25-Apr-14 11:24:42 AM (QP5 v5.163.1008.3004) */
				SELECT TO_CHAR (a.CLOSSING_TIME, 'dd/mm/yyyy HH24:MI') CLOSSING_TIME,
					   a.VESSEL,
					   a.VOYAGE,
                                           b.VOYAGE_OUT,
					   b.STATUS_CONT,
					   b.SIZE_CONT,
					   b.TYPE_CONT,
					   b.NO_CONTAINER,
					   b.BERAT,
					   a.PELABUHAN_TUJUAN,
					   a.FPOD,
					   a.IPOD,
					   a.FIPOD,
					   a.PEB,
					   a.NPE,
					   a.KODE_PBM,
					   b.IMO_CLASS,
					   b.TEMP, a.TGL_REQUEST
				  FROM req_receiving_h a, req_receiving_d b
				 WHERE a.ID_REQ = b.no_req_anne AND a.ID_REQ = '$no_req'";

	$result_nota	= $db->query($query_nota);
	$row			= $result_nota->getAll();
			
	$i = 1;
	$page=0;
	$increment=1;
	foreach ($row as $row_) {
	?>
<style>
body { margin:0px; padding:0px; width:100%; height:100%;}
.txc { height:2px; padding:0px; margin:0px; line-height:9px }

</style> 
<div style="height:5px; width:767px; height:470px; border:0px solid  #FFF">          
<!--<div style="height:5px; width:767px; height:470px; border:0px solid  #FFF">          -->
<table border="0" cellpadding="1" cellspacing="1" style="margin:0px; margin-top:19px; margin-bottom:10px; border:0px solid #000">   
  <tr>
    <th class="txc" width="13%"  scope="col"></th>
    <th class="txc" width="5%" scope="col"></th>
    <th class="txc" width="5%" scope="col"></th>
    <th class="txc" width="5%" scope="col"></th>
    <th class="txc" width="5%" scope="col"></th>
    <th class="txc" width="8%" scope="col"></th>
    <th class="txc" width="15%" scope="col"></th> 
    <th class="txc" width="11%" scope="col"></th>
    <th class="txc" colspan="2" align="left" scope="col" style="font-size:11px; font-family:Arial">1111</th>
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
    <td  class="txc" colspan="2" style="font-size:11px; font-family:Arial">--paid--</td>
    <td  class="txc"></td>
  </tr>
  
  <!--added by gandul-->
  <tr>
	<td>&nbsp;</td>
  </tr>
  <!--added by gandul-->
  <tr>
    <th   class="txc" scope="row"></th>
    <td  class="txc"></td>  
    <td  class="txc"></td>
    <td  class="txc" colspan="5"><div  style="padding-right:150px; font-size:13px"><em></em><em>Clossing Time : <?=$row_['CLOSSING_TIME']?></em></div></td> 
    <td  class="txc" colspan="2" style="font-size:11px; font-family:Arial"><?=$row_['TGL_REQUEST']?>&nbsp;&nbsp;</td>
    <td  class="txc"></td>
  </tr> 
  
  <tr>
    <th height="20" colspan="8" align="left"   scope="row" style="font-size:11px; padding-left:15px; line-height:10px"><?=$row_['VOYAGE_OUT']?><br />
      <?=$row_['NO_CONTAINER']?></th>
    <td width="10%"></td>
    <td width="8%"></td> 
    <td></td>
  </tr> 
  <tr>
    <th  height="20" scope="row"></th>
    <td colspan="5" align="center"><b style="font-size:20px;"> &nbsp;&nbsp;&nbsp;<?=$row_['NO_CONTAINER']?> - <?=$row_['VOYAGE_OUT']?></b></td> 
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
    <td align="right"><?=$row_['IPOD']?>/</td>
    <td colspan="2" align="left" ><?=$row_['FIPOD']?></td>
    <td></td>
  </tr>
  <tr>
    <th  height="20" scope="row"></th>
    <td></td>
    <td colspan="4" align="center">IMO Class : <?=$row_['IMO_CLASS']?> / Gross :<?=$row_['BERAT']?></td>
    <td></td>
    <td colspan="3"><span style="font-size:9px;">(<?=$row_['PELABUHAN_TUJUAN']?>)</span></td>
    <td></td>
  </tr>
 
  <tr>
    <th  height="30" scope="row"></th>
    <td colspan="5" align="center">Temperature : <?=$row_['TEMP']?></td>
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
    <td colspan="4" valign="top"><em><?=$row_['CLOSSING_TIME']?> <br />
        <?=$row_['PEB']?>- NPE :<?=$row_['NPE']?></em></td> 
    <td></td> 
    <td colspan="3" valign="top" align="right" style="font-size:10px"><span style="font-size:14px"><br />&nbsp;<br /><?=$_SESSION['NAMA_PENGGUNA'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>  
    <td></td>
  </tr> 
  <tr> 
    <th  height="26" scope="row"></th>
    <td colspan="5"><!--NO PEB : {$rowd.NO_NPE}-->      
      <strong><span style="font-size:9px">&nbsp;&nbsp;&nbsp;ICT - KARTU MERAH</span> | NO URUT: <?=$i;?></strong></td> 
    <td></td>
    <td colspan="2" style="font-size:14px"><strong> CETAKAN KARTU KE : 1</strong></td>   
    <td style="font-size:14px">&nbsp;</td>  
    <td>&nbsp;</td>
  </tr>    
</table>
</div>
<div style=" height:5px; border:1px solid #FFF"></div>
<div style=" margin-top:50px;"></div>    

<?php
	$i++;
}
?>