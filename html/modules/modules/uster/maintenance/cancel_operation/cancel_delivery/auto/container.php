<?php
/*if($_SESSION["ID_ROLE"] != 1 && $_SESSION["ID_ROLE"] != 41)
{
	exit();
}*/

$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
$query 			= "SELECT container_delivery.no_container, container_delivery.no_request, history_container.no_booking
                FROM container_delivery, request_delivery, nota_delivery, history_container
               WHERE     request_delivery.no_request = nota_delivery.no_request
                     AND request_delivery.no_request = container_delivery.no_request
                     AND container_delivery.no_request = history_container.no_request
                     AND container_delivery.no_container = history_container.no_container
                     AND history_container.kegiatan = 'REQUEST DELIVERY'
                     AND container_delivery.no_container IS NOT NULL
                     AND nota_delivery.lunas = 'YES'
                     AND nota_delivery.status <> 'BATAL'
                     AND container_delivery.no_container LIKE '$no_cont%'
                     --AND container_delivery.no_request = :q
                     ORDER BY request_delivery.tgl_request desc";
//echo $query; die();

$result			= $db->query($query);

$row			= $result->getAll();	


//print_r($row);

echo json_encode($row);


?>