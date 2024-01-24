<style> 
body { margin:0px; padding:0px; width:100%; height:100%; font-family:Arial}
</style>     
<?php
	$no_ukk=$_GET['id'];
	//echo $no_ukk;
	$db=getDb();
	$query = "SELECT NM_KAPAL, VOYAGE_IN, VOYAGE_OUT FROM RBM_H WHERE NO_UKK='$no_ukk'";
	$gh=$db->query($query);
	$head=$gh->fetchRow();
	
	$query_dtl = "SELECT ALAT, ISO_CODE, IMO, NO_CONTAINER, SIZE_, TYPE_, STATUS,POL, IMO, BAY, LOKASI_BP, BERAT, TO_CHAR(SYSDATE,'DD-MM-YYYY HH24:Mi') JAM_PRINT, case when (substr(lokasi,1,2) is null) THEN substr(lokasi,1,5) ELSE  substr(lokasi,1,6) END LOKASI, CASE WHEN TEMP IS NULL THEN '-' ELSE TEMP END TEMP, HZ FROM ISWS_LIST_CONTAINER WHERE NO_UKK='$no_ukk' and E_I='I' AND KODE_STATUS <> 'NA' ORDER BY BAY, LOKASI_BP ASC";
	$gd=$db->query($query_dtl);
	$dtl=$gd->getAll();
	$i=1;
	foreach ($dtl as $row_dt)
	{
	
?>
<div style="width:767px; height:320px; border:1px solid  #fff; font-family:Arial;margin:0px; padding:0px;">    
<table border="0" width="767" height="320" cellpadding="3" cellspacing="3" style="//background:url(<?=HOME?>images/KI.jpg) no-repeat; margin:2px; padding:0px;"> 
<tr>                       	
<td valign="top">             
<!-- START DATA -->      
<table align="left" width="100%" border="0">      
  <tr>
    <th width="20%"  scope="col"></th> 
    <th colspan="4" scope="col"></th>
    <th width="19%" scope="col"></th>
    <th width="6%" scope="col"></th>
    <th colspan="5" align="left" style="font-size:10px" ><?=$head['NM_KAPAL']?></th>  
    <th width="6%" scope="col"></th>
  </tr>
  <tr>
    <th   scope="row"></th>
    <td colspan="4"></td>
    <td></td>
    <td></td>
    <td colspan="5" style="font-size:10px"><?=$head['VOYAGE_IN']?></td>
    <td></td>
  </tr>
  <tr>
    <th   scope="row" style="font-size:11px"><?=$head['VOYAGE_IN']?><br /><?=$row_dt['NO_CONTAINER']?></th> 
    <td colspan="5" align="center" style="padding-left:20px"><div align="center" style="border:2px solid #000; padding:1px; width:110px; font-family:Arial; font-size:12px; font-weight:bold">FM.02/03/01/06</div></td>
    <td></td>
    <td colspan="5" style="font-size:12px"></td>
    <td></td>
  </tr>
  <tr>
    <th height="10" colspan="12" align="left" valign="top"   scope="row" style="font-size:10px; padding-left:15px"></th>
    <td></td>
  </tr>
  <tr>
    <th  height="30" scope="row">&nbsp;</th>
    <td colspan="4" align="center" valign="top">&nbsp; <b style="font-size:22px"><?=$row_dt['NO_CONTAINER']?></b></td>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
    <td colspan="3">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <th height="30"   scope="row">&nbsp;</th>
    <td width="9%" align="left"><?=$i?></td>
    <td colspan="3" align="left"><?=$row_dt['LOKASI_BP']?></td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <th height="30"   scope="row">&nbsp;</th>
    <td align="left">&nbsp; <?=$row_dt['ISO_CODE']?></td>
    <td colspan="2" align="left"><?=$row_dt['SIZE_']?>-<?=$row_dt['TYPE_']?></td>
    <td width="9%" align="left"><?=$row_dt['STATUS']?>- <?=$row_dt['HZ']?></td>
    <td>&nbsp;</td>
    <td colspan="2"  align="left"><b>CY Jambi</b></td>
    <td width="5%"  align="left"><b><?=$row_dt['LOKASI']?></b></td>
    <td width="5%"  align="left"></td>
    <td width="4%"  align="right"><!--{$rowd.ARE_ROW}--></td> 
    <td width="4%"  align="right"><!--{$rowd.ARE_TIER}--></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <th height="27"   scope="row">&nbsp;</th>
    <td colspan="4" align="center"> &nbsp; &nbsp; &nbsp;Temp : <?=$row_dt['TEMP']?></td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <th height="42"   scope="row">&nbsp;</th>
    <td colspan="2" align="center"><?=$row_dt['IMO']?></td>
    <td colspan="2" align="center"> &nbsp &nbsp  &nbsp  &nbsp  <?=$row_dt['BERAT']?></td>
    <td>&nbsp;</td>
    <td><?=$row_dt['ALAT'];?></td>
    <td width="6%"></td>
    <td colspan="2"></td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <th  height="28" scope="row">&nbsp;</th> 
    <td colspan="4" align="center">&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td colspan="2">&nbsp;</td> 
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
  </tr> 
  <tr>
    <th height="30"   scope="row">&nbsp;</th>
    <td align="center"  colspan="2"> &nbsp; <?=$row_dt['POL']?></td>
    <td align="center"  colspan="2"> &nbsp; &nbsp;DISC</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <th    scope="row"></th>
    <td colspan="5" style="font-size:11px"><em>printing page: <?=$i?>  : <?=$row_dt['JAM_PRINT']?></em> -&nbsp;<b> ISWS </b></td>
    <td colspan="6"></td>
    <td></td>
  </tr> 
</table><!-- END DATA -->
</td>
</tr> 
</table>     
</div>    
<div style="margin-top:24px;width:767px; border:0px solid #000"></div>  
  
<?
	$i++;
}
?>