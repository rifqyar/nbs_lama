<?php

//koneksi db oracle
$db ="(DESCRIPTION=
    (ADDRESS=
      (PROTOCOL=TCP)
      (HOST=Pelindo2-VAIO)
      (PORT=1521)
    )
    (CONNECT_DATA=
      (SERVER=dedicated)
      (SERVICE_NAME=XE)
    )
  )";	
$conn = ocilogon("STORAGE", "123456","$db")or die ("can't connect to server");

$no_nota	= $_GET["no_nota"];
$no_req		= $_GET["no_req"];

$query_nota	= "SELECT a.NO_NOTA AS NO_NOTA,
                      a.TGL_NOTA AS TGL_NOTA,
                      c.NAMA AS EMKL,
                      a.NO_REQUEST AS NO_REQUEST,
                      d.NO_CONTAINER AS NO_CONTAINER,
                      e.SIZE_ AS SIZE_,
                      e.TYPE_ AS TYPE_,
                      d.STATUS,
					  b.CETAK_KARTU AS CETAK_KARTU
               FROM storage.NOTA_RECEIVING a,
                    storage.REQUEST_RECEIVING b,
                    storage.MASTER_PBM c,
                    storage.CONTAINER_RECEIVING d,
                    storage.MASTER_CONTAINER e
                   WHERE a.NO_REQUEST = '$no_req' 
                    AND a.NO_REQUEST = b.NO_REQUEST
                    AND     b.ID_EMKL = c.ID
                    AND d.NO_CONTAINER = e.NO_CONTAINER
                    AND d.NO_REQUEST = b.NO_REQUEST
				   ";
	   
$result_nota = OCIparse($conn, $query_nota);
ociexecute($result_nota);
//OCIFetchInto ($result_nota, $row_nota, OCI_ASSOC);
	

while (OCIFetchInto ($result_nota, $row_nota, OCI_ASSOC)) {
?>
		<style>

body { margin:0px; padding:0px; width:100%; height:100%;}

.txc { height:2px; padding:0px; margin:0px; line-height:9px }



</style>
<div style="height:5px; width:767px; height:470px; border:0px solid  #FFF"><table border="0" cellpadding="1" cellspacing="1" style="margin:0px; margin-top:19px; margin-bottom:10px; border:0px solid #000"><tr><th class="txc" width="13%"  scope="col"></th><th class="txc" width="5%" scope="col"></th><th class="txc" width="5%" scope="col"></th><th class="txc" width="5%" scope="col"></th><th class="txc" width="5%" scope="col"></th><th class="txc" width="8%" scope="col"></th><th class="txc" width="15%" scope="col"></th><th class="txc" width="11%" scope="col"></th><th class="txc" colspan="2" align="left" scope="col" style="font-size:11px; font-family:Arial"><!--489019--></th><th class="txc" width="1%" scope="col"></th></tr><tr><th  class="txc"   scope="row"></th><td  class="txc"></td><td  class="txc"></td><td  class="txc"></td><td  class="txc"></td><td  class="txc"></td><td  class="txc"></td><td  class="txc"></td><td  class="txc" colspan="2" style="font-size:11px; font-family:Arial"></td><td  class="txc"></td></tr><tr><th   class="txc" scope="row"></th><td  class="txc"></td><td  class="txc"></td><td  class="txc" colspan="5"><div  style="padding-right:150px; font-size:13px"><em></em><em><? echo $row_nota['TGL_NOTA']; ?></em></div></td><td  class="txc" colspan="2" style="font-size:11px; font-family:Arial">&nbsp;&nbsp;</td><td  class="txc"></td></tr><tr><th height="20" colspan="8" align="left"   scope="row" style="font-size:11px; padding-left:15px; line-height:10px"><br /> <? echo $row_nota['NO_CONTAINER']; ?></th><td width="10%"></td><td width="8%"></td><td></td></tr><tr><th  height="20" scope="row"></th><td colspan="5" align="center"><b style="font-size:20px;"> &nbsp;&nbsp;&nbsp;<? echo $row_nota['NO_CONTAINER']; ?></b></td><td></td><td colspan="2" align="center" style="font-size:16px">PENUMPUKKAN</td><td align="left"></td><td></td></tr><tr><th  height="20" scope="row"></th><td align="center">&nbsp;</td><td colspan="3" align="center"></td><td align="left">&nbsp;</td><td></td><td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(RECEIVING)</td><td colspan="2" align="left" ></td><td></td></tr><tr><th  height="20" scope="row"></th><td></td><td colspan="4" align="center"><? echo $row_nota['SIZE_'].'-'.$row_nota['TYPE_'].'-'.$row_nota['STATUS']; ?></td><td></td><td colspan="3"><span style="font-size:9px;"></span></td><td></td></tr><tr><th  height="30" scope="row"></th><td colspan="5" align="center"> </td><td></td><td></td><td></td><td></td><td></td></tr><tr><th  height="30" scope="row"></th><td align="center">&nbsp;&nbsp;</td><td align="center"></td><td align="center"></td><td align="center"></td><td  align="center">&nbsp;</td><td></td><td colspan="3"><span style="font-size:10px"><? echo $row_nota['EMKL']; ?></span></td><td></td></tr><tr><th  height="126" scope="row"></th><td></td><td colspan="4" valign="top"><em> <br /><? echo $row_nota['NO_REQUEST']; ?></em></td><td></td><td colspan="3" valign="top" align="right" style="font-size:10px"><span style="font-size:14px"><br />&nbsp;<br />anton&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td><td></td></tr><tr><th  height="26" scope="row"></th><td colspan="5"><!--NO PEB : 000253--><strong><span style="font-size:9px">&nbsp;&nbsp;&nbsp;</span>  KARTU PENUMPUKKAN</strong></td><td></td><td colspan="2" style="font-size:14px"><strong> CETAKAN KARTU KE : <? echo $row_nota['CETAK_KARTU']; ?></strong></td><td style="font-size:14px">&nbsp;</td><td>&nbsp;</td></tr></table></div><div style=" height:5px; border:1px solid #FFF"></div><div style=" margin-top:70px;"></div><div style="height:5px; width:767px; height:470px; border:0px solid  #FFF"><table border="0" cellpadding="1" cellspacing="1" style="margin:0px; margin-top:19px; margin-bottom:10px; border:0px solid #000"><tr><th class="txc" width="13%"  scope="col"></th><th class="txc" width="5%" scope="col"></th><th class="txc" width="5%" scope="col"></th><th class="txc" width="5%" scope="col"></th><th class="txc" width="5%" scope="col"></th><th class="txc" width="8%" scope="col"></th><th class="txc" width="15%" scope="col"></th><th class="txc" width="11%" scope="col"></th>

 <?}	
	?>

