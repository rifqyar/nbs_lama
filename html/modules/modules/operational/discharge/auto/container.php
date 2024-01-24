<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB();
	

$query	= "SELECT A.NO_UKK , A.NO_CONTAINER, A.SIZE_, A.TYPE_, A.STATUS, A.HZ, A.ISO_CODE, A.HEIGHT, A.CARRIER, B.NM_KAPAL, B.VOYAGE_IN, B.VOYAGE_OUT, B.NM_PELABUHAN_ASAL, B.NM_PELABUHAN_TUJUAN, A.BERAT, A.SEAL_ID 
from ISWS_LIST_CONTAINER A, RBM_H B 
WHERE A.NO_UKK=B.NO_UKK AND A.E_I='I' AND A.NO_CONTAINER LIKE '$no_cont%' AND A.DISCHARGE_CONFIRM IS NULL";

//print_r($query);die;
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($query);die;

echo json_encode($row);


?>