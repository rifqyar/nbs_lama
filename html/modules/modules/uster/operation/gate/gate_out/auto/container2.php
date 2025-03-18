<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB("storage");


	
$query 			= "SELECT a.NO_CONTAINER, a.NO_REQUEST, b.SIZE_, a.STATUS , b.TYPE_, c.NO_NOTA, TO_CHAR(c.END_STACK, 'dd/mm/yyyy') END_STACK , e.NAMA, TO_CHAR(d.TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY
                            FROM MASTER_CONTAINER b, CONTAINER_DELIVERY a, NOTA_DELIVERY c, REQUEST_DELIVERY d, MASTER_PBM e
                            WHERE a.NO_CONTAINER = b.NO_CONTAINER
                            AND d.NO_REQUEST = c.NO_REQUEST(+)
                            AND a.NO_REQUEST = d.NO_REQUEST
                            AND d.ID_EMKL = e.ID
                            AND b.LOCATION = 'IN_YARD'
                            AND b.NO_CONTAINER LIKE '%$no_cont%'
                           AND d.NO_REQUEST IN (SELECT MAX(d.NO_REQUEST) FROM container_delivery d WHERE d.NO_CONTAINER LIKE '%$no_cont%' GROUP BY d.NO_CONTAINER)
                            ORDER BY a.NO_REQUEST";
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>