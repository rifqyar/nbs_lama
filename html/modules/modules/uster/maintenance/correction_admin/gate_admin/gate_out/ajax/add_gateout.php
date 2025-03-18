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

$q_update = "UPDATE GATE_OUT SET TGL_IN = TO_DATE('$tgl_gate','dd-mm-rrrr'), KETERANGAN = '$keterangan', NO_SEAL = '$no_seal', NOPOL = '$no_truck' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";
if($db->query($q_update)){
	$q_why = "UPDATE HISTORY_CONTAINER SET WHY = 'UPDATED_ADMIN' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req' AND KEGIATAN = 'GATE OUT'";
	if($db->query($q_why)){
		echo "OK";
	}
}
?>