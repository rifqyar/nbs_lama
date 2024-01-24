<?php

$db 		= getDB("storage");

$no_cont	= $_POST["NO_CONT"]; 
$no_req		= $_POST["NO_REQ"]; 
$no_truck	= $_POST["NO_TRUCK"]; 
$kode_truck	= $_POST["KD_TRUCK"]; 
$no_seal	= $_POST["NO_SEAL"]; 
$status     = $_POST["STATUS"];
$masa_berlaku = $_POST["MASA_BERLAKU"]; 
$keterangan	  = $_POST["KETERANGAN"]; 
$tgl_gate	  = $_POST["tgl_gate"]; 
$id_user	= $_SESSION["LOGGED_STORAGE"];
$id_yard	= $_SESSION["IDYARD_STORAGE"];
$selisih    = "SELECT TRUNC(TO_DATE('$masa_berlaku','DD/MM/YYYY') - SYSDATE) SELISIH FROM dual";
$result_cek	= $db->query($selisih);
$row_cek	= $result_cek->fetchRow();
$selisih_tgl	= 1;
//CEK GATO
$cek_gato = "SELECT COUNT(*) JUM FROM GATE_OUT WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";
$r_gate = $db->query($cek_gato);
$row_gate = $r_gate->fetchRow();
$gate_cont = $row_gate["JUM"];
if($gate_cont > 0) {
	echo "Container Sudah Gate Out";
	exit();
}
//cek request relokasi internal
$q_cek_relokasi = "SELECT REQUEST_RELOKASI.NO_REQUEST NOREQ, REQUEST_RELOKASI.* FROM REQUEST_RELOKASI, CONTAINER_RELOKASI WHERE REQUEST_RELOKASI.NO_REQUEST = CONTAINER_RELOKASI.NO_REQUEST 
					AND NO_CONTAINER = '$no_cont' AND NO_REQUEST_DELIVERY = '$no_req'";
$res_cek = $db->query($q_cek_relokasi);
$row_cek = $res_cek->fetchRow();
$no_req_relokasi = $row_cek["NOREQ"];
if($row_cek["TIPE_RELOKASI"] == 'INTERNAL'){
	$q_insert_lolo = "INSERT INTO HANDLING_PIUTANG(NO_CONTAINER, KEGIATAN, STATUS_CONT, TANGGAL, KETERANGAN, NO_REQUEST, ID_YARD)
				 VALUES('$no_cont','DELIVERY','$status',TO_DATE('$tgl_gate','yy-mm-dd'),'LOLO','$no_req','$id_yard')";
	$q_insert_haulage = "INSERT INTO HANDLING_PIUTANG(NO_CONTAINER, KEGIATAN, STATUS_CONT, TANGGAL, KETERANGAN, NO_REQUEST, ID_YARD)
				 VALUES('$no_cont','DELIVERY','$status',TO_DATE('$tgl_gate','yy-mm-dd'),'HAULAGE','$no_req','$id_yard')";
	$db->query($q_insert_lolo);
	$db->query($q_insert_haulage);
	
	$query_insert	= "INSERT INTO GATE_OUT( NO_REQUEST, NO_CONTAINER, ID_USER, TGL_IN, NOPOL, STATUS, NO_SEAL, TRUCKING, ID_YARD, KETERANGAN) VALUES('$no_req', '$no_cont', '$id_user', TO_DATE('$tgl_gate','yy-mm-dd'), '$no_truck', '$status','$no_seal','$kode_truck','$id_yard','$keterangan')";
   // echo $query_insert;
 //  $id_user        = $_SESSION["LOGGED_STORAGE"];
	$id_yard	= $_SESSION["IDYARD_STORAGE"];
	
	$q_getcounter1 = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont' ORDER BY COUNTER DESC";
	$r_getcounter1 = $db->query($q_getcounter1);
	$rw_getcounter1 = $r_getcounter1->fetchRow();
	$cur_booking1  = $rw_getcounter1["NO_BOOKING"];
	$cur_counter1  = $rw_getcounter1["COUNTER"];
	
	$history        = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD, STATUS_CONT, NO_BOOKING, COUNTER) 
													  VALUES ('$no_cont','$no_req','GATE OUT',SYSDATE,'$id_user', '$id_yard','$status','$cur_booking1','$cur_counter1')";
	   
	$db->query($history);
	$db->query("UPDATE MASTER_CONTAINER SET LOCATION = 'GATO' WHERE NO_CONTAINER = '$no_cont'");
	$db->query("UPDATE CONTAINER_DELIVERY SET AKTIF = 'T' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'");
	$db->query("UPDATE CONTAINER_RELOKASI SET AKTIF = 'T' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req_relokasi'");
	$db->query("DELETE FROM PLACEMENT WHERE NO_CONTAINER = '$no_cont'");
	if($db->query($query_insert))
	{
	echo "OK";
	}
		
}
else{
//echo $selisih_tgl;
		$query_insert	= "INSERT INTO GATE_OUT( NO_REQUEST, NO_CONTAINER, ID_USER, TGL_IN, NOPOL, STATUS, NO_SEAL, TRUCKING, ID_YARD, KETERANGAN,ENTRY_DATE) VALUES('$no_req', '$no_cont', '$id_user', TO_DATE('$tgl_gate','dd-mm-rrrr'), '$no_truck', '$status','$no_seal','$kode_truck','$id_yard','$keterangan',SYSDATE)";
	   // echo $query_insert;
	 //  $id_user        = $_SESSION["LOGGED_STORAGE"];
		$id_yard	= $_SESSION["IDYARD_STORAGE"];
		
		/* $q_getcounter1 = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont' ORDER BY COUNTER DESC";
		$r_getcounter1 = $db->query($q_getcounter1);
		$rw_getcounter1 = $r_getcounter1->fetchRow();
		$cur_booking1  = $rw_getcounter1["NO_BOOKING"];
		$cur_counter1  = $rw_getcounter1["COUNTER"]; */
		$qbook = "SELECT NO_BOOKING, COUNTER, TO_CHAR (TGL_UPDATE + interval '1' minute, 'MM/DD/YYYY HH:MI:SS AM') TGL_UPDATE, STATUS_CONT FROM HISTORY_CONTAINER WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";
		$rbook = $db->query($qbook);
		$rwbook = $rbook->fetchRow();
		$cur_booking1 = $rwbook["NO_BOOKING"];
		$cur_counter1 = $rwbook["COUNTER"];
		$tgl_update = $rwbook["TGL_UPDATE"];
		$status_ = $rwbook["STATUS_CONT"];
		
		$history        = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD, STATUS_CONT, NO_BOOKING, COUNTER) 
														  VALUES ('$no_cont','$no_req','GATE OUT',TO_DATE ('$tgl_update', 'MM/DD/YYYY HH:MI:SS AM'),'$id_user', '$id_yard','$status_','$cur_booking1','$cur_counter1')";
		   
		$db->query($history);
		$db->query("UPDATE MASTER_CONTAINER SET LOCATION = 'GATO' WHERE NO_CONTAINER = '$no_cont'");
		$db->query("UPDATE CONTAINER_DELIVERY SET AKTIF = 'T' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'");
		$db->query("UPDATE CONTAINER_RELOKASI SET AKTIF = 'T' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req_relokasi'");
		$db->query("DELETE FROM PLACEMENT WHERE NO_CONTAINER = '$no_cont'");
		if($db->query($query_insert))
		{
			echo "OK";
		}
	
}
?>