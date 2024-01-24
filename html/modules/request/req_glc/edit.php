<?php
 $tl = xliteTemplate('form_req_realisasi.htm');
 $remarks = "req";
 $req = $_GET['id_req'];
 $stat = $_GET['remark']; 
 
 $db = getDB();
 $row = $db->query("SELECT A.ID_REQ,
 						   A.NO_UPER_BM,
                           C.NAMA,
                           B.NAMA_VESSEL,
                           A.VOYAGE,
						   A.KADE,
						   A.TERMINAL,
                           A.STATUS,
						   TO_CHAR(A.ETA,'DD-MM-YYYY HH24:MI:SS') AS ETA,
						   TO_CHAR(A.ETD,'DD-MM-YYYY HH24:MI:SS') AS ETD
						   FROM GLC_REQUEST A, MASTER_VESSEL B, MASTER_PBM C
                           WHERE A.KODE_KAPAL = B.KODE_KAPAL
                           AND A.KODE_PBM = C.KODE_PBM
						   AND A.ID_REQ = '$req'")->fetchRow();
						   
 $tl->assign('request',$row); 
 $tl->assign("HOME",HOME);
 $tl->assign("APPID",APPID);
 $tl->assign('remark',$remarks);
 $tl->assign('status',$stat);
 $tl->renderToScreen();
?>