<?php

$db 		= getDB("storage");

$no_cont	= strtoupper($_POST["NO_CONT"]); 
$no_req		= $_POST["NO_REQ"]; 
$no_pol		= $_POST["NO_POL"]; 
$no_seal		= $_POST["NO_SEAL"]; 
$status		= $_POST["STATUS"]; 
$tgl_gate	= $_POST["tgl_gate"]; 
$keterangan	= $_POST["KETERANGAN"]; 

$id_user	= $_SESSION["LOGGED_STORAGE"];
$id_yard    = $_SESSION["IDYARD_STORAGE"];

if($id_user == NULL){
	echo "Session Habis, Harap Login Kembali";
	exit();
}

$q_updt = $db->query("UPDATE GATE_IN SET TGL_IN = to_date('$tgl_gate','dd-mm-rrrr'), KETERANGAN = '$keterangan', NO_SEAL = '$no_seal', NOPOL = '$no_pol' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'");
$q_why = "UPDATE HISTORY_CONTAINER SET WHY = 'UPDATED_ADMIN' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req' AND KEGIATAN = 'GATE IN'";
if($db->query($q_why)){
	echo "OK";
}

?>