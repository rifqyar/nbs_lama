<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
$query 			= "SELECT MASTER_CONTAINER.NO_CONTAINER, 
                          MASTER_CONTAINER.SIZE_ AS SIZE_, 
                          MASTER_CONTAINER.TYPE_ AS TYPE_,
                          container_delivery.VIA AS VIA,
                          container_delivery.START_STACK AS TGL_REQUEST,
                          container_delivery.NO_REQUEST AS NO_REQUEST,
                          request_delivery.TGL_REQUEST AS TGL_REQUEST,
                          nota_delivery.LUNAS AS LUNAS                        
                   FROM MASTER_CONTAINER  
                   INNER JOIN container_delivery  
                        ON MASTER_CONTAINER.NO_CONTAINER = container_delivery.NO_CONTAINER 
                   JOIN request_delivery 
                        ON container_delivery.NO_REQUEST = request_delivery.NO_REQUEST
                  JOIN nota_delivery
                        ON request_delivery.NO_REQUEST = nota_delivery.NO_REQUEST
                   WHERE MASTER_CONTAINER.NO_CONTAINER LIKE '$no_cont%'
                        AND container_delivery.AKTIF = 'Y'
						AND master_container.location not like 'GATO'
						and request_delivery.delivery_ke = 'LUAR'
						and nota_delivery.LUNAS = 'YES'
						and nota_delivery.STATUS <> 'BATAL'"; 
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>