<?php

$db 		= getDB("storage");

$no_cont	= $_POST["NO_CONT"]; 
$no_req		= $_POST["NO_REQ"]; 
$no_truck	= $_POST["NO_TRUCK"]; 
$kode_truck	= $_POST["KD_TRUCK"]; 
$no_seal	= $_POST["NO_SEAL"]; 
$status         = $_POST["STATUS"];
$masa_berlaku	= $_POST["MASA_BERLAKU"]; 
$keterangan	= $_POST["KETERANGAN"]; 

$id_user	= $_SESSION["LOGGED_STORAGE"];

$selisih        = "SELECT TRUNC(TO_DATE('$masa_berlaku','DD/MM/YYYY') - SYSDATE) SELISIH FROM dual";
$result_cek	= $db->query($selisih);
$row_cek	= $result_cek->fetchRow();
$selisih_tgl	= $row_cek["SELISIH"];

//echo $selisih_tgl;
if ($selisih_tgl < 0) {
    echo "EXPIRED";
} else {
    $query_insert	= "INSERT INTO GATE_OUT( NO_REQUEST, NO_CONTAINER, ID_USER, TGL_IN, NOPOL, STATUS, NO_SEAL, TRUCKING, KETERANGAN) VALUES('$no_req', '$no_cont', '$id_user', SYSDATE, '$no_truck', '$status','$no_seal','$kode_truck','$keterangan')";
   // echo $query_insert;
    $db->query("UPDATE MASTER_CONTAINER SET LOCATION = 'GATO' WHERE NO_CONTAINER = '$no_cont'");
    $db->query("UPDATE CONTAINER_RECEIVING SET AKTIF = 'T'");
    $db->query("UPDATE CONTAINER_DELIVERY SET AKTIF = 'T'");
    $db->query("DELETE FROM PLACEMENT WHERE NO_CONTAINER = '$no_cont'");
    if($db->query($query_insert))
    {
	echo "OK";
    }
}

?>