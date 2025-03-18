<?php 
	$tl = xliteTemplate('view_operation.htm');
	$db = getDB("storage");
	$no_request = $_POST['NO_REQUEST'];
	$no_container = $_POST['NO_CONT'];
	$no_booking = $_POST['NO_BOOKING'];
	//echo $no_container; die();
	$q_operation = "SELECT X.* FROM (SELECT MC.NO_CONTAINER, HC.KEGIATAN, 
                CASE WHEN HC.KEGIATAN = 'REALISASI STUFFING'
                THEN (SELECT to_char(MAX(TGL_REALISASI),'DD-MM-YYYY hh24:mi')  FROM CONTAINER_STUFFING WHERE NO_CONTAINER = MC.NO_CONTAINER AND NO_REQUEST = RST.NO_REQUEST) 
                WHEN HC.KEGIATAN = 'BORDER GATE IN'
                THEN (SELECT to_char(MAX(TGL_IN),'DD-MM-YYYY hh24:mi') FROM BORDER_GATE_IN WHERE NO_CONTAINER = MC.NO_CONTAINER AND NO_REQUEST = RR.NO_REQUEST)
                WHEN HC.KEGIATAN = 'GATE IN'
                THEN (SELECT to_char(MAX(TGL_IN),'DD-MM-YYYY hh24:mi') FROM GATE_IN WHERE NO_CONTAINER = MC.NO_CONTAINER AND NO_REQUEST = RR.NO_REQUEST)
                WHEN HC.KEGIATAN = 'REQUEST STUFFING'
                THEN (SELECT to_char(TGL_REQUEST,'DD-MM-YYYY hh24:mi') FROM REQUEST_STUFFING WHERE NO_REQUEST = RST.NO_REQUEST)
                END TGL_UPDATE, HC.NO_BOOKING NO_BOOKING, MU.NAMA_LENGKAP, HC.NO_REQUEST
                FROM MASTER_CONTAINER MC INNER JOIN HISTORY_CONTAINER HC
                ON MC.NO_CONTAINER = HC.NO_CONTAINER 
                LEFT JOIN REQUEST_RECEIVING RR
                ON RR.NO_REQUEST = HC.NO_REQUEST
                LEFT JOIN REQUEST_STUFFING RST
                ON RST.NO_REQUEST = HC.NO_REQUEST
                LEFT JOIN MASTER_USER MU ON MU.ID = HC.ID_USER
                WHERE HC.NO_CONTAINER = '$no_container'
                AND HC.NO_BOOKING = '$no_booking'
                AND HC.KEGIATAN IN ('BORDER GATE IN','REALISASI STUFFING', 'GATE IN')
               UNION ALL   
            SELECT HP.NO_CONTAINER, 'PLACEMENT' KEGIATAN,
                TO_CHAR(HP.TGL_UPDATE,'DD-MM-YYYY hh24:mi') TGL_UPDATE, HC.NO_BOOKING, MU.NAMA_LENGKAP, HP.NO_REQUEST
                FROM HISTORY_PLACEMENT HP, HISTORY_CONTAINER HC, MASTER_USER MU
                WHERE HP.NO_CONTAINER = HC.NO_CONTAINER
                AND HP.NO_REQUEST = HC.NO_REQUEST
                AND HC.KEGIATAN = 'BORDER GATE IN'
                AND MU.USERNAME = HP.NIPP_USER
                AND HP.NO_CONTAINER = '$no_container'
                AND HC.NO_BOOKING = '$no_booking'
                UNION ALL
                SELECT HP.NO_CONTAINER, 'PLACEMENT' KEGIATAN,
                TO_CHAR(HP.TGL_UPDATE,'DD-MM-YYYY hh24:mi') TGL_UPDATE, HC.NO_BOOKING, MU.NAMA_LENGKAP, HP.NO_REQUEST
                FROM HISTORY_PLACEMENT HP, HISTORY_CONTAINER HC, MASTER_USER MU
                WHERE HP.NO_CONTAINER = HC.NO_CONTAINER
                AND HP.NO_REQUEST = HC.NO_REQUEST
                AND HC.KEGIATAN = 'BORDER GATE IN'
                AND TO_CHAR(MU.ID) = HP.NIPP_USER
                AND HP.NO_CONTAINER = '$no_container'
                AND HC.NO_BOOKING = '$no_booking'   
                ) X
                ORDER BY X.TGL_UPDATE DESC";
	$r_operation = $db->query($q_operation);
	$rw_operation = $r_operation->getAll();
	$tl->assign('rw_operation',$rw_operation);
	$tl->renderToScreen();
?>