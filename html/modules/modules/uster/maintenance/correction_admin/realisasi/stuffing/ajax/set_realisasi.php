<?php


if($_SESSION["ID_ROLE"] != 1 && $_SESSION["ID_ROLE"] != 41)
{
	echo "UNAUTORHIZED";
	exit();
}

$db 			= getDB("storage");
$db2 			= getDB("ora");

$no_cont		= $_POST["NO_CONT"]; //ok
$no_req_stuff	= $_POST["NO_REQ_STUFF"]; //ok
$nm_user		= $_SESSION["NAME"]; //ok
$no_req_del		= $_POST["NO_REQ_DEL"]; //ok
$no_req_ict		= $_POST["NO_REQ_ICT"]; //ok
$hz             = $_POST["HZ"]; //ok
$keterangan		= $_POST["KETERANGAN"]; 
$no_seal		= $_POST["NO_SEAL"]; 
$berat			= $_POST["BERAT"]; 
$via            = $_POST["VIA"]; //ok
$komoditi       = $_POST["KOMODITI"]; //ok
$kd_komoditi    = $_POST["KD_KOMODITI"]; //ok
$size			= $_POST["SIZE"]; //ok
$tipe			= $_POST["TYPE"]; //ok
$status			= "FCL";
$no_booking		= $_POST["NO_BOOKING"]; //ok
$no_ukk			= $_POST["NO_UKK"];//ok
$id_user        = $_SESSION["LOGGED_STORAGE"];
$id_yard		= $_SESSION["IDYARD_STORAGE"];
$tgl_real		= $_POST["TGL_REAL"];//ok

$query = "UPDATE CONTAINER_STUFFING SET TGL_REALISASI = TO_DATE('$tgl_real','DD-MM-RRRR') WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req_stuff'";
if($db->query($query)){
	$q_why = "UPDATE HISTORY_CONTAINER SET WHY = 'UPDATED_ADMIN' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req_stuff' AND KEGIATAN = 'REALISASI STUFFING'";
	if($db->query($q_why)){
		echo "OK";
	}
}

?>