<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB("storage");

$query			= "select master_container.no_container, master_container.size_, master_container.type_, container_delivery.status, container_delivery.no_request,  
case when to_date(to_char(nota_delivery.tgl_nota,'DD-MM-RRRR'),'DD-MM-RRRR') < to_date('01-06-2013','DD-MM-RRRR') then nota_delivery.no_nota
else nota_delivery.no_faktur end as no_nota 
from master_container, container_delivery, nota_delivery where master_container.no_container = container_delivery.no_container
and container_delivery.aktif = 'Y' and container_delivery.no_request = nota_delivery.no_request and nota_delivery.lunas = 'YES'
and nota_delivery.status <> 'BATAL'
and container_delivery.no_container LIKE '%$no_cont%'";

$result			= $db->query($query);
$row			= $result->getAll();	

echo json_encode($row);


?>