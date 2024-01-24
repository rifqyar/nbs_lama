<?php
$jenis		= $_GET["jenis"];
$tanggal=date("dmY");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=LAP-".$jenis."-".$tanggal.".xls");
header("Pragma: no-cache");
header("Expires: 0");

$tgl_awal	= $_GET["tgl_awal"]; 
	$tgl_akhir	= $_GET["tgl_akhir"]; 
	
	//echo $tgl_awal;die;
	$db 	= getDB("storage");
	
	$query_list_ 	= "SELECT * FROM (
                       SELECT CONTAINER_STUFFING.NO_CONTAINER , REQUEST_STUFFING.NO_REQUEST , REQUEST_STUFFING.TGL_REQUEST, V_MST_PBM.NM_PBM, 'STUFFING' KEGIATAN, CONTAINER_STUFFING.TGL_APPROVE, 
					   CONTAINER_STUFFING.TGL_REALISASI
                       FROM REQUEST_STUFFING INNER JOIN 
                       CONTAINER_STUFFING ON REQUEST_STUFFING.NO_REQUEST = CONTAINER_STUFFING.NO_REQUEST
                       LEFT JOIN V_MST_PBM ON REQUEST_STUFFING.KD_CONSIGNEE = V_MST_PBM.KD_PBM
                       WHERE TRUNC(CONTAINER_STUFFING.TGL_APPROVE) BETWEEN TO_DATE('$tgl_awal','yyyy-mm-dd') AND TO_DATE('$tgl_akhir','yyyy-mm-dd')  
                       UNION
                       SELECT DISTINCT CONTAINER_STRIPPING.NO_CONTAINER , REQUEST_STRIPPING.NO_REQUEST , REQUEST_STRIPPING.TGL_REQUEST,  V_MST_PBM.NM_PBM, 'STRIPPING' KEGIATAN, CONTAINER_STRIPPING.TGL_APPROVE,
					   CONTAINER_STRIPPING.TGL_REALISASI
                       FROM REQUEST_STRIPPING INNER JOIN 
                       CONTAINER_STRIPPING ON REQUEST_STRIPPING.NO_REQUEST = CONTAINER_STRIPPING.NO_REQUEST
                       LEFT JOIN V_MST_PBM ON REQUEST_STRIPPING.KD_CONSIGNEE = V_MST_PBM.KD_PBM
                       WHERE TRUNC(CONTAINER_STRIPPING.TGL_APPROVE) BETWEEN TO_DATE('$tgl_awal','yyyy-mm-dd') AND TO_DATE('$tgl_akhir','yyyy-mm-dd')) A  
                       WHERE A.KEGIATAN LIKE '%$jenis%' ORDER BY NO_REQUEST DESC";
	$result_list_	= $db->query($query_list_);
	$row_list		= $result_list_->getAll();


?>

  <div id="list">
     <table class="grid-table" border='0' cellpadding="1" cellspacing="1"  width="100%" >
                              <tr style=" font-size:10pt">
                                  <th valign="top" class="grid-header"  style="font-size:8pt">No </th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">No.  Request</th>
                                  <th  valign="top" class="grid-header"  style="font-size:8pt">No.  Container</th>
                                  <th  valign="top" class="grid-header"  style="font-size:8pt">Kegiatan</th> 
                                  <th valign="top" class="grid-header"  style="font-size:8pt">Tgl Approval</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">Tgl Realisasi</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">Pemilik Barang</th>
                              </tr>
                              <?php $i=0; $before= ""; foreach($row_list as $rows) { ?>
                              <tr bgcolor="#f9f9f3" onMouseOver=this.style.backgroundColor="#BAD5FC" onMouseOut=this.style.backgroundColor="">                                  
								  <?php if($rows["NO_REQUEST"] != $before) { $i++;?>
								  <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$i?> </td>
                                  <td width="22%" align="center" valign="middle" class="grid-cell"   style="font-family:Arial; font-size:11pt; color:#555555"><b><?=$rows["NO_REQUEST"]?></b></td>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$rows["NO_CONTAINER"]?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows["KEGIATAN"]?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows["TGL_APPROVE"]?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows["TGL_REALISASI"]?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows["NM_PBM"]?></font></td>
								  <?php } else{?>
								  <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"></td>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"></td>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$rows["NO_CONTAINER"]?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows["KEGIATAN"]?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows["TGL_APPROVE"]?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows["TGL_REALISASI"]?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows["NM_PBM"]?></font></td>		
										<?php }  $before = $rows["NO_REQUEST"];
								  ?>
                                  
							</tr>
							<?php } ?>
        </table>
 </div>