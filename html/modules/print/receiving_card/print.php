<style>
body { margin:0px; padding:0px; width:100%; height:100%;}
.txc { height:2px; padding:0px; margin:0px; line-height:9px }

</style> 
<fill src="data" var="rowd">
<div style="height:5px; width:767px; height:320px; border:1px solid  #FFF">   
<table border="0" cellpadding="3" cellspacing="3" style="margin:0px; margin-top:10px; margin-bottom:10px">
  <tr>
    <th class="txc" width="13%"  scope="col"></th>
    <th class="txc" width="5%" scope="col"></th>
    <th class="txc" width="5%" scope="col"></th>
    <th class="txc" width="5%" scope="col"></th>
    <th class="txc" width="5%" scope="col"></th>
    <th class="txc" width="8%" scope="col"></th>
    <th class="txc" width="15%" scope="col"></th> 
    <th class="txc" width="11%" scope="col"></th>
    <th class="txc" colspan="2" align="left" scope="col" style="font-size:11px; font-family:Arial">{$rowd.KD_PMB_DTL}</th>
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
    <td  class="txc" colspan="2" style="font-size:11px; font-family:Arial">{$rowd.NO_NOTA}</td>
    <td  class="txc"></td>
  </tr>
  <tr>
    <th   class="txc" scope="row"></th>
    <td  class="txc"></td>
    <td  class="txc"></td>
    <td  class="txc" colspan="5"><div  style="padding-right:150px; font-size:13px"><em>Clossing Time : {$rowd.CLOSING_DATE}</em></div></td>
    <td  class="txc" colspan="2" style="font-size:11px; font-family:Arial">{$rowd.TGL_BAYAR}&nbsp;&nbsp;{$rowd.NO}</td>
    <td  class="txc"></td>
  </tr> 
  <tr>
    <th height="20" colspan="8" align="left"   scope="row" style="font-size:11px; padding-left:15px; line-height:10px">{$rowd.VOYAGE_IN}<br />{$rowd.NO_CONTAINER}</th>
    <td width="10%"></td>
    <td width="8%"></td> 
    <td></td>
  </tr>
  <tr>
    <th  height="30" scope="row"></th>
    <td colspan="5" align="center"><b style="font-size:20px; line-height:5px">{$rowd.NO_CONTAINER}</b> {$rowd.KET_VIA} </td>
    <td></td>
    <td colspan="2" align="center" style="font-size:14px">{$rowd.NM_KAPAL}</td>
    <td align="left">{$rowd.VOYAGE_OUT}</td>
    <td></td>
  </tr>
  <tr>
    <th  height="30" scope="row"></th>
    <td align="center">{$rowd.SIZE_CONT}</td>
    <td colspan="3" align="center">{$rowd.NM_JENIS_PEMILIK}</td>
    <td align="left">{$rowd.NM_TYPE_CONT}</td>
    <td></td>
    <td align="center">{$rowd.PELABUHAN_TUJUAN}</td>
    <td colspan="2">{$rowd.NO_SEAL}</td>
    <td></td>
  </tr>
  <tr>
    <th  height="30" scope="row"></th>
    <td></td>
    <td colspan="3" align="center">{$rowd.GROSS}</td>
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
    <td align="center">&nbsp;&nbsp;{$rowd.NM_CY}</td> 
    <td align="center">{$rowd.BLK_NAME}</td>
    <td align="center">{$rowd.ARE_SLOT}</td>
    <td align="center">{$rowd.ARE_ROW}</td>
    <td  align="center">{$rowd.ARE_TIER} </td>
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
    <td colspan="3" style="font-size:10px">{$rowd.NM_PBM}</td>
    <td></td>
  </tr>
  <tr>
    <th  height="26" scope="row"></th>
    <td colspan="5"><!--NO PEB : {$rowd.NO_NPE}-->ICT - {$rowd.KD_PMB}</td> 
    <td></td>
    <td style="font-size:14px"></td>
    <td style="font-size:14px"></td>
    <td style="font-size:14px">{$userid}</td> 
    <td>&nbsp;</td>
  </tr> 
</table>
</div>
<div style=" height:5px; border:1px solid #FFF"></div>
<div style=" margin-top:20px;"></div>
</fill>  


  