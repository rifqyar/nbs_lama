<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
$query 			= "select d.no_container, d.size_cont, trim(d.type_cont) type_cont, d.vessel||'|'||d.voyage||' '||d.voyage_out as vessel, d.no_ukk
					from billing.req_delivery_h h, billing.req_delivery_d d where trim(h.id_req) = trim(d.no_req_dev) and trunc(h.tgl_request) = trunc(sysdate-30)
					and no_container like '%$no_cont%'";
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>