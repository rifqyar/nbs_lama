<?php
$tanggal=date("dmY");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=LAP-REALISASI-".$tanggal.".xls");
header("Pragma: no-cache");
header("Expires: 0");

	$tgl_awal  = $_POST["tgl_awal"]; 
  $tgl_akhir  = $_POST["tgl_akhir"]; 
  $jenis    = $_POST["jenis"];
  $id_menu2   = $_POST['menu2'];
  
  $jum = count($id_menu2);
  $orderby = 'ORDER BY ';
  for($i=0;$i<count($id_menu2);$i++){
    $orderby .= $id_menu2[$i];
    if($i != $jum-1){
      $orderby .= ",";
    }
    
  }
  if($jum  == 0){
    $orderby= "";
  }
	//echo $tgl_awal;die;
	$db 	= getDB("storage");
	
	
	if($jenis == 'STRIPPING'){
    $query_list_ = "SELECT DISTINCT CONTAINER_STRIPPING.NO_CONTAINER , MASTER_CONTAINER.SIZE_, MASTER_CONTAINER.TYPE_, 'MTY' STATUS_CONT, REQUEST_STRIPPING.NO_REQUEST , REQUEST_STRIPPING.TGL_REQUEST,  V_MST_PBM.NM_PBM, 'STRIPPING' KEGIATAN, CONTAINER_STRIPPING.TGL_APPROVE,
                       TO_CHAR(CONTAINER_STRIPPING.TGL_REALISASI,'DD-MM-YYYY HH24:MI:SS') TGL_REALISASI, CONTAINER_STRIPPING.ID_USER_REALISASI, TO_CHAR(HISTORY_PLACEMENT.TGL_UPDATE,'DD-MM-YYYY HH24:MI:SS') TGL_PLACEMENT, 
                       MASTER_CONTAINER.NO_BOOKING, PKK.NM_KAPAL, MU.NAMA_LENGKAP, PKK.VOYAGE_IN
                       FROM REQUEST_STRIPPING INNER JOIN 
                       CONTAINER_STRIPPING ON REQUEST_STRIPPING.NO_REQUEST = CONTAINER_STRIPPING.NO_REQUEST
                       INNER JOIN MASTER_USER MU ON CONTAINER_STRIPPING.ID_USER_REALISASI = MU.ID
                       INNER JOIN MASTER_CONTAINER ON CONTAINER_STRIPPING.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
                       INNER JOIN PETIKEMAS_CABANG.TTM_BP_CONT TM ON TM.BP_ID = MASTER_CONTAINER.NO_BOOKING
                       INNER JOIN PETIKEMAS_CABANG.V_PKK_CONT PKK ON PKK.NO_UKK =  TM.NO_UKK
                       LEFT JOIN KAPAL_CABANG.MST_PBM V_MST_PBM ON REQUEST_STRIPPING.KD_CONSIGNEE = V_MST_PBM.KD_PBM AND V_MST_PBM.KD_CABANG = '05'
                       LEFT JOIN HISTORY_PLACEMENT ON CONTAINER_STRIPPING.NO_CONTAINER  = HISTORY_PLACEMENT.NO_CONTAINER AND REQUEST_STRIPPING.NO_REQUEST_RECEIVING = HISTORY_PLACEMENT.NO_REQUEST
                       WHERE TO_DATE(CONTAINER_STRIPPING.TGL_REALISASI,'dd-mm-rrrr') BETWEEN TO_DATE('$tgl_awal','yyyy-mm-dd') AND TO_DATE('$tgl_akhir','yyyy-mm-dd') ".$orderby;
  } else if($jenis == 'STUFFING'){
    $query_list_ = "SELECT CONTAINER_STUFFING.NO_CONTAINER , MC.SIZE_, MC.TYPE_, 'FCL' STATUS_CONT,  REQUEST_STUFFING.NO_REQUEST , REQUEST_STUFFING.TGL_REQUEST, V_MST_PBM.NM_PBM, 'STUFFING' KEGIATAN, CONTAINER_STUFFING.TGL_APPROVE, 
                       TO_CHAR(CONTAINER_STUFFING.TGL_REALISASI,'DD-MM-YYYY HH24:MI:SS') TGL_REALISASI, CONTAINER_STUFFING.ID_USER_REALISASI, TO_CHAR(HISTORY_PLACEMENT.TGL_UPDATE,'DD-MM-YYYY HH24:MI:SS') TGL_PLACEMENT, REQUEST_STUFFING.NO_BOOKING, VB.NM_KAPAL, VB.VOYAGE_IN, MU.NAMA_LENGKAP
                       FROM REQUEST_STUFFING INNER JOIN 
                       CONTAINER_STUFFING ON REQUEST_STUFFING.NO_REQUEST = CONTAINER_STUFFING.NO_REQUEST
                       INNER JOIN MASTER_USER MU ON CONTAINER_STUFFING.ID_USER_REALISASI = MU.ID
                       INNER JOIN MASTER_CONTAINER MC ON CONTAINER_STUFFING.NO_CONTAINER = MC.NO_CONTAINER
                       INNER JOIN V_PKK_CONT VB ON REQUEST_STUFFING.NO_BOOKING = VB.NO_BOOKING
                       LEFT JOIN KAPAL_CABANG.MST_PBM V_MST_PBM ON REQUEST_STUFFING.KD_CONSIGNEE = V_MST_PBM.KD_PBM AND V_MST_PBM.KD_CABANG = '05'
                       LEFT JOIN HISTORY_PLACEMENT ON CONTAINER_STUFFING.NO_CONTAINER  = HISTORY_PLACEMENT.NO_CONTAINER AND REQUEST_STUFFING.NO_REQUEST_RECEIVING = HISTORY_PLACEMENT.NO_REQUEST
                        WHERE TO_DATE(CONTAINER_STUFFING.TGL_REALISASI,'dd-mm-rrrr') BETWEEN TO_DATE('$tgl_awal','yyyy-mm-dd') AND TO_DATE('$tgl_akhir','yyyy-mm-dd') ".$orderby;
  }
	
  //echo $query_list_."gfgfgf"; die();

	$result_list_	= $db->query($query_list_);
	$row_list		= $result_list_->getAll();


?>
 
  
 <div id="list">
    <h2>LAPORAN REALISASI <?=$jenis?> Periode <?=$tgl_awal?> s/d <?=$tgl_akhir?></h2>
     <table class="grid-table" border='1' cellpadding="1" cellspacing="1"  width="100%" >
                              <tr style=" font-size:10pt">
                                  <th valign="top" class="grid-header"  style="font-size:8pt">No </th>
                                  <th  valign="top" class="grid-header"  style="font-size:8pt">No. Container</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">No. Request</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">Size</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">Type</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">Status</th>
                                  <th  valign="top" class="grid-header"  style="font-size:8pt">Kegiatan</th> 
                                  <th valign="top" class="grid-header"  style="font-size:8pt">Tgl Approval</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">Tgl Realisasi</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">Eksekutor</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">Tgl Placement</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">Pemilik Barang</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">Kapal</th>
                              </tr>
                <?php $i=1; ?>
                <?php foreach ($row_list as $rows) { ?>
                    <tr bgcolor="#f9f9f3" onMouseOver=this.style.backgroundColor="#BAD5FC" onMouseOut=this.style.backgroundColor="">
                                  <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$i?> </td>
                                  <td width="22%" align="center" valign="middle" class="grid-cell"   style="font-family:Arial; font-size:11pt; color:#555555"><b><?=$rows[NO_CONTAINER]?></b></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$rows[NO_REQUEST]?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$rows[SIZE_]?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$rows[TYPE_]?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$rows[STATUS_CONT]?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[KEGIATAN]?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[TGL_APPROVE]?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[TGL_REALISASI]?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[NAMA_LENGKAP]?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[TGL_PLACEMENT]?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[NM_PBM]?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[NM_KAPAL]?> [<?=$rows[VOYAGE_IN]?>]</font></td>
                           </tr>
                <?php $i++; }?> 
        </table>
 </div>