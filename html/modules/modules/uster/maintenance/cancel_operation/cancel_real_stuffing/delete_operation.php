<?php


	$db = getDB('storage');
	$db2 = getDB("ora");

	$no_cont 	= $_POST['NO_CONTAINER'];
	$no_req 	= $_POST['NO_REQUEST'];
	$kegiatan 	= $_POST['KEGIATAN'];
	$no_booking	= $_POST['NO_BOOKING'];
	$id_user        = $_SESSION["LOGGED_STORAGE"];
	//echo $no_cont;echo $no_req;echo $no_booking;echo $kegiatan;

	$q_cek_op 	= "SELECT HC.*, CASE WHEN HC.NO_REQUEST LIKE '%DEL%' THEN 'DELIVERY'
					WHEN HC.NO_REQUEST LIKE '%TF%' THEN 'STUFFING' END CUR_OPERATION FROM HISTORY_CONTAINER HC WHERE HC.NO_CONTAINER = '$no_cont' ORDER BY HC.TGL_UPDATE DESC";
	$r_cek_op	= $db->query($q_cek_op);
	$rw_cek_op	= $r_cek_op->fetchRow();
	
	if($kegiatan == $rw_cek_op[KEGIATAN]){
		if ($kegiatan == 'REALISASI STUFFING') {
			$db->query("DELETE FROM HISTORY_CONTAINER WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req' AND NO_BOOKING = '$no_booking' AND KEGIATAN = '$kegiatan'");
			$db->query("UPDATE CONTAINER_STUFFING SET AKTIF = 'Y', TGL_REALISASI = '', ID_USER_REALISASI = '' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'");
			$db->query("INSERT INTO CANCEL_OPERATION_LOG(NO_CONTAINER,NO_REQUEST,KEGIATAN,USERS,TIMES) VALUES('$no_cont','$no_req','$kegiatan','$id_user',SYSDATE)");
		}
		else if ($kegiatan == 'BORDER GATE IN') {
			$db->query("DELETE FROM HISTORY_CONTAINER WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req' AND NO_BOOKING = '$no_booking' AND KEGIATAN = '$kegiatan'");
			$db->query("DELETE FROM BORDER_GATE_IN WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'");
			$db->query("UPDATE MASTER_CONTAINER SET LOCATION = 'GATO' WHERE NO_CONTAINER = '$no_cont'");
			$db2->query("UPDATE PETIKEMAS_CABANG.TTD_BP_CONT SET STATUS_CONT = '04',GATE_OUT = '', GATE_OUT_DATE = '', HT_NO = '' WHERE CONT_NO_BP = '$no_cont' AND BP_ID ='$no_booking'");
			$db->query("INSERT INTO CANCEL_OPERATION_LOG(NO_CONTAINER,NO_REQUEST,KEGIATAN,USERS,TIMES) VALUES('$no_cont','$no_req','$kegiatan','$id_user',SYSDATE)");
		}
		echo "Y";
		die();
	} 
	else if($kegiatan == 'PLACEMENT') {
		$db->query("DELETE FROM HISTORY_PLACEMENT WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'");
		$db->query("DELETE FROM PLACEMENT WHERE NO_CONTAINER = '$no_cont'");
		$db->query("UPDATE MASTER_CONTAINER SET LOCATION = 'GATI' WHERE NO_CONTAINER = '$no_cont'");
		$db->query("UPDATE CONTAINER_RECEIVING SET AKTIF = 'Y' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'");
		$db->query("INSERT INTO CANCEL_OPERATION_LOG(NO_CONTAINER,NO_REQUEST,KEGIATAN,USERS,TIMES) VALUES('$no_cont','$no_req','$kegiatan','$id_user',SYSDATE)");
		echo "Y";
		die();
	}
	else {
		if($rw_cek_op[CUR_OPERATION] == 'DELIVERY'){
			echo 'DL';
			die();
		}
		else if ($rw_cek_op[CUR_OPERATION] == 'STUFFING') {
			echo "ST";
			die();
		}
	}

?>