<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB("storage");

//$id_yard		= $_SESSION["IDYARD_STORAGE"];
$id_yard  		= $_GET["yard_asal"];
	
$query 			= "SELECT NO_CONTAINER, SIZE_ AS SIZE_, TYPE_ AS TYPE_ FROM MASTER_CONTAINER 
                    WHERE NO_CONTAINER LIKE '%$no_cont%' AND NO_CONTAINER IN 
                    (SELECT NO_CONTAINER FROM PLACEMENT INNER JOIN BLOCKING_AREA ON PLACEMENT.ID_BLOCKING_AREA = BLOCKING_AREA.ID 
                    AND BLOCKING_AREA.ID_YARD_AREA = '$id_yard')
                    AND NO_CONTAINER NOT IN (SELECT NO_CONTAINER FROM CONTAINER_RELOKASI WHERE CONTAINER_RELOKASI.AKTIF='Y')
                    AND NO_CONTAINER NOT IN (SELECT NO_CONTAINER FROM CONTAINER_DELIVERY WHERE AKTIF = 'Y')
					AND MASTER_CONTAINER.LOCATION ='IN_YARD'";
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>