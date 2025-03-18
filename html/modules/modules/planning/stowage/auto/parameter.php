<?php
$param		= strtoupper($_GET["term"]);

$db 			= getDB("dblocal");
	
$query 			= "SELECT b.ID_VS ID, a.NAMA_VESSEL NAMA, b.VOYAGE INFO FROM MASTER_VESSEL a, VESSEL_SCHEDULE b WHERE b.ID_VES = a.KODE_KAPAL AND a.NAMA_VESSEL LIKE '$param%' AND b.STATUS = 'AKTIF'
					UNION
					SELECT  0 ID, c.NAMA NAMA, c.KODE_PBM INFO   FROM MASTER_PBM c WHERE c.STATUS_PBM = 'AKTIF' AND c.NAMA LIKE '$param%'
					";
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>