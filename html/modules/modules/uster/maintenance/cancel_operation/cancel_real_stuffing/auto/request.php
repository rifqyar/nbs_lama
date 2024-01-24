<?php
/*if($_SESSION["ID_ROLE"] != 1 && $_SESSION["ID_ROLE"] != 41)
{
	exit();
}*/

$no_req		= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
$query 			= "SELECT container_stuffing.no_container, container_stuffing.no_request, history_container.no_booking
                FROM container_stuffing, request_stuffing, nota_stuffing, history_container
               WHERE     request_stuffing.no_request = nota_stuffing.no_request
                     AND request_stuffing.no_request = container_stuffing.no_request
                     AND container_stuffing.no_request = history_container.no_request
                     AND container_stuffing.no_container = history_container.no_container
                     AND history_container.kegiatan = 'REQUEST STUFFING'
                     AND container_stuffing.no_container IS NOT NULL
                     AND nota_stuffing.lunas = 'YES'
                     AND nota_stuffing.status <> 'BATAL'
                     --AND container_stuffing.no_container LIKE '$no_cont%'
                     AND container_stuffing.no_request LIKE '$no_req%'
                     ORDER BY request_stuffing.tgl_request desc";
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>