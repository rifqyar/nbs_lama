<?php


	$db = getDB('storage');
	$db2 = getDB("ora");
	//echo "string"; die();
	$no_cont 	= $_POST['NO_CONTAINER'];
	$no_req 	= $_POST['NO_REQUEST'];
	$kegiatan 	= $_POST['KEGIATAN'];
	$no_booking	= $_POST['NO_BOOKING'];
	$id_user        = $_SESSION["LOGGED_STORAGE"];
	//echo $no_cont;echo $no_req;echo $no_booking;echo $kegiatan;

	$q_cek_op 	= "SELECT HC.*,
				         CASE
				            WHEN HC.NO_REQUEST LIKE '%REC%' THEN 'RECEIVING'
				            WHEN HC.NO_REQUEST LIKE '%STR%' THEN 'STRIPPING'
				            WHEN HC.NO_REQUEST LIKE '%STF%' THEN 'STUFFING'
				         END
				            CUR_OPERATION
				    FROM HISTORY_CONTAINER HC
				   WHERE HC.NO_CONTAINER = '$no_cont'
				ORDER BY HC.TGL_UPDATE DESC";

	$r_cek_op	= $db->query($q_cek_op);
	$rw_cek_op	= $r_cek_op->fetchRow();

	$cek_tpk = "SELECT *
  				FROM request_delivery
 				WHERE delivery_ke = 'TPK' AND no_request = '$no_req'";
 	$rcek_tpk = $db->query($cek_tpk);
 	$rwcek_tpk = $rcek_tpk->getAll();
 	if(count($rwcek_tpk) > 0){
 		$cek_pl_tpk = "SELECT * FROM PETIKEMAS_CABANG.ttd_cont_exbspl WHERE kd_pmb = replace('$no_req','DEL','UD') AND no_container = '$no_cont' and status_pmb_dtl in ('1U','1')";
 		$rcek_pl_tpk = $db2->query($cek_pl_tpk);
 		$rwcek_pl_tpk = $rcek_pl_tpk->fetchRow();
 		if(count($rwcek_pl_tpk) > 0){
 			echo "PL";
 			die();
 		}
 	}


	
	if($kegiatan == $rw_cek_op[KEGIATAN]){
		if ($kegiatan == 'GATE OUT') {
			$db->query("DELETE FROM HISTORY_CONTAINER WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req' AND NO_BOOKING = '$no_booking' AND KEGIATAN = '$kegiatan'");
			$db->query("DELETE FROM GATE_OUT WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'");
			$db->query("UPDATE CONTAINER_DELIVERY SET AKTIF = 'Y', KELUAR = 'N' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'");
			$db->query("UPDATE MASTER_CONTAINER SET LOCATION = 'IN_YARD' WHERE NO_CONTAINER = '$no_cont'");
			$db->query("INSERT INTO CANCEL_OPERATION_LOG(NO_CONTAINER,NO_REQUEST,KEGIATAN,USERS,TIMES) VALUES('$no_cont','$no_req','$kegiatan','$id_user',SYSDATE)");
		}
		else if ($kegiatan == 'BORDER GATE OUT') {
			$db->query("DELETE FROM HISTORY_CONTAINER WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req' AND NO_BOOKING = '$no_booking' AND KEGIATAN = '$kegiatan'");
			$db->query("DELETE FROM BORDER_GATE_IN WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'");
			$db->query("UPDATE MASTER_CONTAINER SET LOCATION = 'IN_YARD' WHERE NO_CONTAINER = '$no_cont'");
			$db2->query("UPDATE PETIKEMAS_CABANG.ttd_cont_exbspl SET status_pmb_dtl = '0U'  WHERE no_container = '$no_cont' AND kd_pmb = replace('$no_req','DEL','UD')");
			$db->query("INSERT INTO CANCEL_OPERATION_LOG(NO_CONTAINER,NO_REQUEST,KEGIATAN,USERS,TIMES) VALUES('$no_cont','$no_req','$kegiatan','$id_user',SYSDATE)");
		}
		echo "Y";
		die();
	} 
	else {
		if($rw_cek_op[CUR_OPERATION] == 'RECEIVING'){
			echo 'RC';
			die();
		}
		else if ($rw_cek_op[CUR_OPERATION] == 'STRIPPING' || $rw_cek_op[CUR_OPERATION] == 'STUFFING') {
			echo "ST";
			die();
		}
	}

?>