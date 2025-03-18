<?php
 $tl = xliteTemplate('view.htm');
 $id_req = $_GET["id_req"]; 
 $stat = $_GET['remark']; //shift, non shift
 $remarks = "view";
 $db = getDB();
 $row = $db->query("SELECT A.ID_REQ, 
                           A.NO_UPER_BM,
                           C.NAMA,
                           B.NAMA_VESSEL,
                           A.VOYAGE,
                           A.STATUS,
						   A.REMARK,
						   A.KADE,
						   A.TERMINAL,
						   TO_CHAR(A.ETA,'DD-MM-YYYY') AS ETA_DATE,
						   TO_CHAR(A.ETA,'HH24') AS ETA_JAM,
						   TO_CHAR(A.ETA,'MI') AS ETA_MENIT,
						   TO_CHAR(A.ETD,'DD-MM-YYYY') AS ETD_DATE,
						   TO_CHAR(A.ETD,'HH24') AS ETD_JAM,
						   TO_CHAR(A.ETD,'MI') AS ETD_MENIT,
						   TO_CHAR(A.RTA,'DD-MM-YYYY') AS RTA_DATE,
						   TO_CHAR(A.RTA,'HH24') AS RTA_JAM,
						   TO_CHAR(A.RTA,'MI') AS RTA_MENIT,
						   TO_CHAR(A.RTD,'DD-MM-YYYY') AS RTD_DATE,
						   TO_CHAR(A.RTD,'HH24') AS RTD_JAM,
						   TO_CHAR(A.RTD,'MI') AS RTD_MENIT,
						   TO_CHAR(A.RTA,'DD-MM-YYYY HH24:MI:SS') AS RTA,
						   TO_CHAR(A.RTD,'DD-MM-YYYY HH24:MI:SS') AS RTD 
						   FROM GLC_REQUEST A, MASTER_VESSEL B, MASTER_PBM C
                           WHERE A.KODE_KAPAL = B.KODE_KAPAL
                           AND A.KODE_PBM = C.KODE_PBM
						   AND A.ID_REQ='$id_req'"
						   )->fetchRow();
 
 $tl->assign('request',$row);
 $tl->assign("HOME",HOME);
 $tl->assign("APPID",APPID);
 $tl->assign('status',$stat);
 $tl->assign('remark',$remarks);
 $tl->renderToScreen();
?>
