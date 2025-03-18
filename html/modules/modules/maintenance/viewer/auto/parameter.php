<?php
$param		= strtoupper($_GET["term"]);

$db 			= getDB();
	
$query 			= "SELECT b.ID_VS ID, a.NAMA_VESSEL NAMA, b.VOYAGE INFO FROM MASTER_VESSEL a, VESSEL_SCHEDULE b WHERE b.ID_VES = a.KODE_KAPAL AND a.NAMA_VESSEL LIKE '$param%' AND b.STATUS = 'AKTIF'
					UNION
					SELECT DISTINCT  0 ID, c.NAMA NAMA, c.KODE_PBM INFO   FROM TB_REQ_RECEIVING_H a, VESSEL_SCHEDULE b, MASTER_PBM c WHERE a.KODE_PBM = c.KODE_PBM AND a.ID_VS = b.ID_VS AND b.STATUS = 'AKTIF' AND  c.NAMA LIKE '$param%'
					";
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>