<?php

$cont  = $_POST["CONTAINER"];
$db   = getDB("storage");

if($cont == ''){
    echo json_encode(array('success' => false, 'message' => 'Container Tidak Boleh Kosong'));
    die();
}else{
    $query1 = "UPDATE CONTAINER_RECEIVING SET AKTIF = 'T' WHERE NO_CONTAINER = '$cont' ";
    $query2 = "UPDATE CONTAINER_DELIVERY SET AKTIF = 'T' WHERE NO_CONTAINER = '$cont' ";
    $query3 = "UPDATE CONTAINER_STRIPPING SET AKTIF = 'T' WHERE NO_CONTAINER = '$cont' ";
    $query4 = "UPDATE CONTAINER_STUFFING SET AKTIF = 'T' WHERE NO_CONTAINER = '$cont' ";
    $query5 = "UPDATE MASTER_CONTAINER SET LOCATION = 'GATO' WHERE NO_CONTAINER = '$cont' ";
    
    $success1 = $db->query($query1);
    $success2 = $db->query($query2);
    $success3 = $db->query($query3);
    $success4 = $db->query($query4);
    $success5 = $db->query($query5);

    
     // Check if all queries were successful
     echo json_encode(array('success' => true, 'message' => 'Container Berhasil Di Update'));
}


  