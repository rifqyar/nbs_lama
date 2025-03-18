<?php
$skg = date("dmY");
$tgl_awal  = $_GET['tgl_awal'];
$tgl_akhir = $_GET['tgl_akhir'];

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=lap_bhd_tpft_".$skg.".xls");
header("Pragma: no-cache");
header("Expires: 0");
$db  = getDB();
$db2 = getDB('ora');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">



<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- TemplateBeginEditable name="doctitle" -->
<title></title>
<!-- TemplateEndEditable -->
<!-- TemplateBeginEditable name="head" -->
<!-- TemplateEndEditable -->
</head>
<body>
<table>
   <tr>
       <td colspan="15" align="center">LAPORAN PETIKEMAS BEHANDLE TPFT</td>
   </tr>
   <tr>
       <td colspan="15" align="center">PERIODE : <?=$tgl_awal?>  S.D. <?=$tgl_akhir?></td>
   </tr>
</table>
<br>
<table border="1">
  <tr align="center" valign="center">
    <td rowspan="2" bgcolor="#CCCCCC"><b>NO</b></td>
    <td rowspan="2" bgcolor="#CCCCCC"><b>NO REQUEST</b></td>
    <td rowspan="2" bgcolor="#CCCCCC"><b>TANGGAL REQUEST</b></td>
    <td rowspan="2" bgcolor="#CCCCCC"><b>NO CONTAINER</b></td>
    <td rowspan="2" bgcolor="#CCCCCC"><b>SIZE</b></td>
    <td rowspan="2" bgcolor="#CCCCCC"><b>TYPE</b></td>
    <td rowspan="2" bgcolor="#CCCCCC"><b>STATUS</b></td>
    <td rowspan="2" bgcolor="#CCCCCC"><b>HZ</b></td>
    <td rowspan="2" bgcolor="#CCCCCC"><b>VESSEL</b></td>
    <td colspan="2" bgcolor="#CCCCCC"><b>VOYAGE</b></td>
    <td rowspan="2" bgcolor="#CCCCCC"><b>TANGGAL TIBA</b></td>
    <td colspan="2" bgcolor="#CCCCCC"><b>TANGGAL DELIVERY</b></td>
    <td rowspan="2" bgcolor="#CCCCCC"><b>JUMLAH HARI</b></td>
  </tr>
  <tr align="center">
    <td bgcolor="#CCCCCC"><b>IN</b></td>
    <td bgcolor="#CCCCCC"><b>OUT</b></td>
    <td bgcolor="#CCCCCC"><b>GATE IN</b></td>
    <td bgcolor="#CCCCCC"><b>GATE OUT</b></td>
  </tr>
   <?
    $laporan_bhd_tpft    = "SELECT A.BILLING_REQUEST_ID,
								   B.CRE_DT,
								   A.NO_CONTAINER,
								   A.SIZE_CONT,
								   A.TYPE_CONT,
								   A.STATUS,
								   A.HZ,
								   A.VESSEL,
								   A.VOYAGE_IN,
								   A.VOYAGE_OUT,
								   TO_CHAR (TO_DATE (A.VESSEL_CONFIRM, 'RRRRMMDDHH24MISS'), 'DD/MM/RRRR')
									  ATA,
								   CASE
									  WHEN A.GATE_IN_DATE IS NULL
									  THEN
										 'BELUM GATE IN DELIVERY'
									  WHEN A.GATE_IN_DATE IS NOT NULL
									  THEN
										 TO_CHAR (TO_DATE (A.GATE_IN_DATE, 'RRRRMMDDHH24MISS'),
												  'DD/MM/RRRR HH24:MI:SS')
								   END
									  GATE_IN,
								   CASE
									  WHEN A.GATE_OUT_DATE IS NULL
									  THEN
										 'BELUM GATE OUT DELIVERY'
									  WHEN A.GATE_OUT_DATE IS NOT NULL
									  THEN
										 TO_CHAR (TO_DATE (A.GATE_OUT_DATE, 'RRRRMMDDHH24MISS'),
												  'DD/MM/RRRR HH24:MI:SS')
								   END
									  GATE_OUT,
								   NVL (
									  (TO_DATE (TO_DATE (A.GATE_OUT_DATE, 'RRRRMMDDHH24MISS'),
												'DD/MM/RRRR')
									   - TO_DATE (TO_DATE (A.VESSEL_CONFIRM, 'RRRRMMDDHH24MISS'),
												  'DD/MM/RRRR'))
									  + 1,0)JML_HARI
							   FROM M_CYC_CONTAINER@DBINT_LINK A, M_BILLING@DBINT_LINK B, BIL_DELSPJM_H C, BIL_DELSPJM_D D
                             WHERE     A.BILLING_REQUEST_ID = B.NO_REQUEST
                                   AND A.NO_CONTAINER = B.NO_CONTAINER
                                   AND A.BILLING_REQUEST_ID = C.NO_REQUEST
                                   AND A.NO_CONTAINER = D.NO_CONT
                                   AND C.ID_DEL = D.ID_DEL
                                   AND D.FLAG_CANCEL IS NULL
								   AND A.BILLING_REQUEST_ID LIKE 'SPJ%'
								   AND TO_DATE (B.CRE_DT, 'DD/MM/RRRR') BETWEEN TO_DATE ('".$tgl_awal."','DD/MM/RRRR')AND TO_DATE ('".$tgl_akhir."','DD/MM/RRRR')";
    $result_lap = $db->query($laporan_bhd_tpft);
    $res = $result_lap->getAll();
	$i=1;
	foreach ($res as $row){
	$no_request         = $row[BILLING_REQUEST_ID];
	$tgl_request        = $row[CRE_DT];
    $no_container       = $row[NO_CONTAINER];
	$size               = $row[SIZE_CONT];
	$type               = $row[TYPE_CONT];
	$status             = $row[STATUS];
	$hz                 = $row[HZ];
	$vessel             = $row[VESSEL];
	$voy_in             = $row[VOYAGE_IN];
	$voy_out            = $row[VOYAGE_OUT];
	$tgl_tiba           = $row[ATA];
    $in_delivery        = $row[GATE_IN];
	$out_delivery       = $row[GATE_OUT];
	$jml_hari           = $row[JML_HARI];
	?>
	<tr>
       <td align="center"><?=$i++?></td>
	   <td align="center"><?=$no_request?></td>
	   <td align="center"><?=$tgl_request?></td>
	   <td align="center"><?=$no_container?></td>
	   <td align="center"><?=$size?></td>
	   <td align="center"><?=$type?></td>
	   <td align="center"><?=$status?></td>
	   <td align="center"><?=$hz?></td>
	   <td align="center"><?=$vessel?></td>
	   <td align="center"><?=$voy_in?></td>
	   <td align="center"><?=$voy_out?></td>
	   <td align="center"><?=$tgl_tiba?></td>
	   <td align="center"><?=$in_delivery?></td>
	   <td align="center"><?=$out_delivery?></td>
	   <td align="center"><?=$jml_hari?></td>
	</tr>
   <? } ?>
</table>
</body>
</html>
