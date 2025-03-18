<?php
/*if($_SESSION["ID_ROLE"] != 1 && $_SESSION["ID_ROLE"] != 41)
{
	exit();
}*/

$no_req		= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
$query 			= "SELECT container_receiving.no_container, container_receiving.no_request, history_container.no_booking
                FROM container_receiving, request_receiving, nota_receiving, history_container
               WHERE     request_receiving.no_request = nota_receiving.no_request
                     AND request_receiving.no_request = container_receiving.no_request
                     AND container_receiving.no_request = history_container.no_request
                     AND container_receiving.no_container = history_container.no_container
                     AND history_container.kegiatan = 'REQUEST RECEIVING'
                     AND container_receiving.no_container IS NOT NULL
                     AND nota_receiving.lunas = 'YES'
                     AND nota_receiving.status <> 'BATAL'
                     --AND container_receiving.no_container = :q
                     AND container_receiving.no_request LIKE '$no_req%'
                     ORDER BY request_receiving.tgl_request desc";
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>