<?php
$skg = date("dmY");
$id_pranota = $_GET['id_pranota'];

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=detail_bhd_tpft_".$skg.".xls");
header("Pragma: no-cache");
header("Expires: 0");

$db = getDB();
$id_pranota = $_GET['id_pranota'];

$header = "SELECT A.PERIODE,
				   (SELECT COUNT (1)
					  FROM BIL_BHD_CONT_H
					 WHERE ID_PRANOTA = A.ID_BHD)
					  JML_CONT
			  FROM REQ_BHD_H A
			 WHERE ID_BHD = '$id_pranota'";

$result_header = $db->query($header);
$row_header    = $result_header->fetchRow();
$periode       = $row_header['PERIODE'];
$jumlah        = $row_header['JML_CONT'];
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
       <td colspan="18" align="left">RINCIAN PRANOTA TAGIHAN PETIKEMAS BEHANDLE TPFT</td>
   </tr>
   <tr>
       <td colspan="18" align="left">PERIODE : <?=$periode?></td>
   </tr>
   <tr>
       <td colspan="18" align="left">JUMLAH PETIKEMAS : <?=$jumlah?> Box</td>
   </tr>
</table>
<br>
<table width="650" border="0">
    <tr>
		<td colspan="4">Daftar Container Behandle</td>
		<td width="120">&nbsp;</td>
		<td width="11">&nbsp;</td>
		<td width="150">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="7">
		    <font size="8">
			    <table border="1">
			        <tr align="center" bgcolor="#cccccc" >
					    <td width="25">No</td>
						<td width="80">No Container</td>
						<td width="30">Size</td>
						<td width="30">Type</td>
						<td width="40">Status</td>
						<td width="25">HZ</td>
						<td width="120">Vessel</td>
						<td width="50">Voyage</td>
						<td width="70">Mulai Penumpukan</td>
						<td width="70">Selesai Penumpukan</td>
						<td width="50">Durasi</td>
						<td width="50">Masa 1.1</td>
						<td width="50">Masa 1.2</td>
						<td width="50">Masa 2</td>
						<td width="50">Biaya  Masa 1.1</td>
						<td width="50">Biaya  Masa 1.2</td>
						<td width="50">Biaya  Masa 2</td>
						<td width="60">Biaya Sharing 35%</td>
						<td width="70">Kebersihan</td>
						<td width="50">ADM</td>
						<td width="65">Tagihan</td>
					</tr>
					<?
					 $detail_nota    = "SELECT A.NO_CONTAINER,
                                               A.SIZE_CONTAINER,
                                               A.TYPE_CONTAINER,
                                               A.STS_CONTAINER,
                                               A.HZ,
                                               A.VESSEL,
                                               A.VOYAGE,
                                               TO_CHAR (A.ATA, 'DD/MM/RRRR') START_DATE,
                                               TO_CHAR (A.TGL_GATE_OUT, 'DD/MM/RRRR') END_DATE,
                                               (A.TGL_GATE_OUT - A.ATA) + 1 DURASI,
                                               NVL(B.DURASI,0)MASA_11,
                                               NVL(C.DURASI,0)MASA_12,
                                               NVL(D.DURASI,0)MASA_2,
                                               NVL(B.BIAYA,0)BIAYA_MASA_11,
                                               NVL(C.BIAYA,0)BIAYA_MASA_12,
                                               NVL(D.BIAYA,0)BIAYA_MASA_2,
                                               NVL(E.SHARING,0)SHARING,
                                               NVL(F.KEBERSIHAN,0)KEBERSIHAN,
                                               NVL(G.ADM,0)ADM,
                                               NVL(A.TAGIHAN,0)TAGIHAN
                                          FROM BIL_BHD_CONT_H A,
                                               (SELECT ID, BIAYA, DURASI 
                                                  FROM BIL_BHD_CONT_D
                                                 WHERE KETERANGAN = 'PENUMPUKAN MASA 1.1') B,
                                               (SELECT ID, BIAYA, DURASI 
                                                  FROM BIL_BHD_CONT_D
                                                 WHERE KETERANGAN = 'PENUMPUKAN MASA 1.2') C,
                                               (SELECT ID, BIAYA, DURASI 
                                                  FROM BIL_BHD_CONT_D
                                                 WHERE KETERANGAN = 'PENUMPUKAN MASA 2') D,
                                               (SELECT ID, BIAYA SHARING
                                                  FROM BIL_BHD_CONT_D
                                                 WHERE KETERANGAN = 'SHARING BEHANDLE') E,
                                               (SELECT ID, BIAYA KEBERSIHAN
                                                  FROM BIL_BHD_CONT_D
                                                 WHERE KETERANGAN = 'KEBERSIHAN') F,
                                               (SELECT ID, BIAYA ADM
                                                  FROM BIL_BHD_CONT_D
                                                 WHERE KETERANGAN = 'ADMINISTRASI') G
                                         WHERE A.ID = B.ID(+)
                                           AND A.ID = C.ID(+)
                                           AND A.ID = D.ID(+)
                                           AND A.ID = E.ID
                                           AND A.ID = F.ID
                                           AND A.ID = G.ID
										   AND A.ID_PRANOTA = '$id_pranota'";
										   
					 $data1 = $db->query($detail_nota);
					 $i = 1;
					 while($detail_nota = $data1->fetchRow()) {
							$no_cont 	  = $detail_nota[NO_CONTAINER];
							$size_cont 	  = $detail_nota[SIZE_CONTAINER];
							$type_cont 	  = $detail_nota[TYPE_CONTAINER];
							$sts_cont 	  = $detail_nota[STS_CONTAINER];
							$hz 	      = $detail_nota[HZ];
							$vessel 	  = $detail_nota[VESSEL];
							$voyage 	  = $detail_nota[VOYAGE];
							$start_date   = $detail_nota[START_DATE];
							$end_date 	  = $detail_nota[END_DATE];
							$durasi 	  = $detail_nota[DURASI];
							$masa11 	  = $detail_nota[MASA_11];
							$masa12 	  = $detail_nota[MASA_12];
							$masa2 	      = $detail_nota[MASA_2];
							$biaya_masa11 = $detail_nota[BIAYA_MASA_11];
							$biaya_masa12 = $detail_nota[BIAYA_MASA_12];
							$biaya_masa2  = $detail_nota[BIAYA_MASA_2];
							$sharing 	  = $detail_nota[SHARING];
							$kebersihan   = $detail_nota[KEBERSIHAN];
							$adm     	  = $detail_nota[ADM];
							$tagihan 	  = $detail_nota[TAGIHAN];
			        ?>				
                    <tr>
					    <td width="25" align="center"><?=$i++?></td>
						<td width="80" align="center"><?=$no_cont?></td>
						<td width="30" align="center"><?=$size_cont?></td>
						<td width="30" align="center"><?=$type_cont?></td>
						<td width="40" align="center"><?=$sts_cont?></td>
						<td width="25" align="center"><?=$hz?></td>
						<td width="120" align="center"><?=$vessel?></td>
						<td width="50" align="center"><?=$voyage?></td>
						<td width="70" align="center"><?=$start_date?></td>
						<td width="70" align="center"><?=$end_date?></td>
						<td width="50" align="center"><?=$durasi?> Hari</td>
						<td width="50" align="right"><?=$masa11?></td>
						<td width="50" align="right"><?=$masa12?></td>
						<td width="50" align="right"><?=$masa2?></td>
						<td width="50" align="right"><?=$biaya_masa11?></td>
						<td width="50" align="right"><?=$biaya_masa12?></td>
						<td width="50" align="right"><?=$biaya_masa2?></td>
						<td width="60" align="right"><?=$sharing?></td>
						<td width="70" align="right"><?=$kebersihan?></td>
						<td width="50" align="right"><?=$adm?></td>
						<td width="65" align="right"><?=$tagihan?></td>
					</tr>
					<? } ?>
					
                </table>
			</font>
		</td>
    </tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
</table>
</body>
</html>
