<?php
$no_ukk	= strtoupper($_GET["term"]);

$db 			= getDB();

//debug($nama_kapal);die;
	
$query 			= "SELECT DISTINCT VESSEL AS VESSEL, 
						  NO_UKK,
						  VOYAGE_IN,
						  NM_AGEN 
						FROM TR_NOTA_ANNE_ICT_H
						WHERE NO_UKK LIKE '$no_ukk%'
						";
$result			= $db->query($query);
$row			= $result->getAll();	

//debug($row);die;

echo json_encode($row);


?>