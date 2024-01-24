<?php
$no_kartu		= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
$query 			= "SELECT GATE_IN_TRUCK.*, MASTER_CONTAINER.SIZE_, YARD_AREA.NAMA_YARD LOKASI
                   FROM GATE_IN_TRUCK INNER JOIN MASTER_CONTAINER ON GATE_IN_TRUCK.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
                   LEFT JOIN YARD_AREA ON GATE_IN_TRUCK.ID_YARD = YARD_AREA.ID
                   WHERE NO_KARTU LIKE '%$no_kartu%'  
                   AND AKTIF = 'Y' 
                   AND ROWNUM <= 5 ORDER BY NO_KARTU ASC";
				   
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>