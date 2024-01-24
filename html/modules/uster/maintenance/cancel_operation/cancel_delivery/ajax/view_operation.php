<?php 
	$tl = xliteTemplate('view_operation.htm');
	$db = getDB("storage");
	$no_request = $_POST['NO_REQUEST'];
	$no_container = $_POST['NO_CONT'];
	$no_booking = $_POST['NO_BOOKING'];
	//echo $no_container; die();
	$q_operation = "SELECT X.* FROM (SELECT MC.NO_CONTAINER, HC.KEGIATAN, 
                CASE  
                WHEN HC.KEGIATAN = 'BORDER GATE OUT'
                THEN (SELECT to_char(MAX(TGL_IN),'DD-MM-YYYY hh24:mi') FROM BORDER_GATE_OUT WHERE NO_CONTAINER = MC.NO_CONTAINER AND NO_REQUEST = RD.NO_REQUEST)
                WHEN HC.KEGIATAN = 'GATE OUT'
                THEN (SELECT to_char(MAX(TGL_IN),'DD-MM-YYYY hh24:mi') FROM GATE_OUT WHERE NO_CONTAINER = MC.NO_CONTAINER AND NO_REQUEST = RD.NO_REQUEST)
                WHEN HC.KEGIATAN = 'REQUEST DELIVERY'
                THEN (SELECT to_char(TGL_REQUEST,'DD-MM-YYYY hh24:mi') FROM REQUEST_DELIVERY WHERE NO_REQUEST = RD.NO_REQUEST)
                END TGL_UPDATE, HC.NO_BOOKING NO_BOOKING, MU.NAMA_LENGKAP, HC.NO_REQUEST
                FROM MASTER_CONTAINER MC INNER JOIN HISTORY_CONTAINER HC
                ON MC.NO_CONTAINER = HC.NO_CONTAINER 
                LEFT JOIN REQUEST_DELIVERY RD
                ON RD.NO_REQUEST = HC.NO_REQUEST
                LEFT JOIN MASTER_USER MU ON TO_CHAR(MU.ID) = HC.ID_USER
                WHERE HC.NO_CONTAINER = '$no_container'
                AND HC.NO_BOOKING = '$no_booking'
                AND HC.KEGIATAN IN ('BORDER GATE OUT', 'GATE OUT')) X
                ORDER BY X.TGL_UPDATE DESC";
	$r_operation = $db->query($q_operation);
	$rw_operation = $r_operation->getAll();
	$tl->assign('rw_operation',$rw_operation);
	$tl->renderToScreen();
?>