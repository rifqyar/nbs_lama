<?php
//debug ($_POST);die;
$db 			= getDB();

$no_cont		= strtoupper($_POST["no_cont"]); 
$no_req			= $_POST["no_req"]; 
$status			= $_POST["status"]; 
$hz             = $_POST["hz"]; 
$size			= $_POST["size"];
$tipe			= $_POST["tipe"];

//query cek tabel master container

$cek 		= "SELECT NO_CONTAINER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
$rs 		= $db->query($cek);
$row 		= $rs->fetchRow();

if ($row['NO_CONTAINER'] == NULL){
	$insert = "INSERT INTO MASTER_CONTAINER (NO_CONTAINER, UKURAN, TYPE_) VALUES ('$no_cont','$size','$tipe')";
    $db->query($insert);
}

$query_insert   = "INSERT INTO TB_BATALMUAT_D (ID_BATALMUAT, NO_CONTAINER, STATUS, HZ) VALUES ('$no_req','$no_cont','$status','$hz')";

if($db->query($query_insert))
{
	echo "OK";
}
else
{
	echo "gagal";
}
    
?>