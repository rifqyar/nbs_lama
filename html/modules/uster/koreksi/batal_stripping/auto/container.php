<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
$query 			= "SELECT MASTER_CONTAINER.NO_CONTAINER, 
                          MASTER_CONTAINER.SIZE_ AS SIZE_, 
                          MASTER_CONTAINER.TYPE_ AS TYPE_,
                          CONTAINER_STRIPPING.VIA AS VIA,
                          CONTAINER_STRIPPING.TGL_BONGKAR AS TGL_REQUEST,
                          CONTAINER_STRIPPING.NO_REQUEST AS NO_REQUEST,
                          REQUEST_STRIPPING.TGL_REQUEST AS TGL_REQUEST,
                          NOTA_STRIPPING.LUNAS AS LUNAS,
                          'TPK' AS STRIPPING_DARI,
                          REQUEST_STRIPPING.NO_REQUEST_RECEIVING AS NO_REQUEST_RECEIVING                          
                   FROM MASTER_CONTAINER  
                   INNER JOIN CONTAINER_STRIPPING 
                        ON MASTER_CONTAINER.NO_CONTAINER = CONTAINER_STRIPPING.NO_CONTAINER
                   JOIN REQUEST_STRIPPING 
                        ON CONTAINER_STRIPPING.NO_REQUEST = REQUEST_STRIPPING.NO_REQUEST
                  JOIN NOTA_STRIPPING
                        ON REQUEST_STRIPPING.NO_REQUEST = NOTA_STRIPPING.NO_REQUEST
                        AND NOTA_STRIPPING.LUNAS = 'YES'
                        AND NOTA_STRIPPING.STATUS <> 'BATAL'
                   WHERE MASTER_CONTAINER.NO_CONTAINER LIKE '%$no_cont%' 
                        AND CONTAINER_STRIPPING.AKTIF = 'Y'"; 
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>