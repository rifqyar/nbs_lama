<?php

if($_SESSION["ID_ROLE"] != NULL){
	if($_SESSION["ID_ROLE"] != 1 && $_SESSION["ID_ROLE"] != 41)
	{
		echo "UNAUTORHIZED";
		exit();
	}
}
else {
	exit();
}
$db 			= getDB("storage");

$no_cont		= $_POST["NO_CONT"]; 
$no_req			= $_POST["NO_REQ"]; 
$tgl_real		= $_POST["TGL_REAL"]; 
$id_user        = $_SESSION["LOGGED_STORAGE"];
$id_yard		= $_SESSION["IDYARD_STORAGE"];
	
	$update = "UPDATE CONTAINER_STRIPPING SET TGL_REALISASI = TO_DATE('$tgl_real','DD-MM-RRRR') WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";
	if($db->query($update)){
		$q_why = "UPDATE HISTORY_CONTAINER SET WHY = 'UPDATED_ADMIN' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req' AND KEGIATAN = 'REALISASI STRIPPING'";
		if($db->query($q_why)){
			echo "OK";
		}
	}

?>