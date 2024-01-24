<?php
 $tl = xliteTemplate('cetak_time_sheet.htm');
 $id_req = $_GET['id'];
 $db = getDB();
 $row = $db->query("SELECT A.ID_REQ, 
                           A.NO_UPER_BM,
                           C.NAMA,
                           B.NAMA_VESSEL,
                           A.VOYAGE,
                           A.STATUS FROM GLC_REQUEST A, MASTER_VESSEL B, MASTER_PBM C
                           WHERE A.KODE_KAPAL = B.KODE_KAPAL
                           AND A.KODE_PBM = C.KODE_PBM
						   AND A.ID_REQ = '$id_req'")->fetchRow();
 $tl->assign('req',$row);
 $tl->renderToScreen();
?>

