<?php
/*if($_SESSION["ID_ROLE"] != 1 && $_SESSION["ID_ROLE"] != 41)
{
	exit();
}*/

$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
$query 			= "SELECT container_stripping.no_container, container_stripping.no_request, history_container.no_booking
                FROM container_stripping, request_stripping, nota_stripping, history_container
               WHERE     request_stripping.no_request = nota_stripping.no_request
                     AND request_stripping.no_request = container_stripping.no_request
                     AND container_stripping.no_request = history_container.no_request
                     AND container_stripping.no_container = history_container.no_container
                     AND history_container.kegiatan = 'REQUEST STRIPPING'
                     AND container_stripping.no_container IS NOT NULL
                     AND nota_stripping.lunas = 'YES'
                     AND nota_stripping.status <> 'BATAL'
                     AND container_stripping.no_container LIKE '%$no_cont%'
                     --AND container_stripping.no_request = :q
                     ORDER BY request_stripping.tgl_request desc";
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>