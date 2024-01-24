<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
$query 			= "select cr.no_container, mc.type_, mc.size_, rr.receiving_dari, nr.no_nota, rr.no_request, rr.tgl_request, nr.lunas
					from master_container mc
					join container_receiving cr on mc.no_container = cr.no_container
					join request_receiving rr on cr.no_request = rr.no_request
					join nota_receiving nr on rr.no_request = nr.no_request
					and rr.receiving_dari = 'LUAR' AND cr.aktif = 'Y'
					and mc.location = 'GATO' and nr.status <> 'BATAL' and cr.STATUS_REQ is null
					and cr.no_container like '%$no_cont%'"; 
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>