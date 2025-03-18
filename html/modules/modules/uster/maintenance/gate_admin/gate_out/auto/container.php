<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB("storage");
$id_yard		= $_SESSION["IDYARD_STORAGE"];
	
/*$query 			= "SELECT a.NO_CONTAINER, a.NO_REQUEST, b.SIZE_, a.STATUS , b.TYPE_, c.NO_NOTA, TO_CHAR(c.END_STACK, 'dd/mm/yyyy') END_STACK , e.NAMA
                            FROM MASTER_CONTAINER b, CONTAINER_DELIVERY a, NOTA_DELIVERY c, REQUEST_DELIVERY d, MASTER_PBM e
                            WHERE a.NO_CONTAINER = b.NO_CONTAINER
                            AND a.NO_REQUEST = c.NO_REQUEST
                            AND a.NO_REQUEST = d.NO_REQUEST
                            AND d.ID_PEMILIK = e.ID
                            AND b.LOCATION = 'IN_YARD'
                            AND b.NO_CONTAINER LIKE '%$no_cont%'
							AND a.AKTIF = 'Y' 
                            ORDER BY a.NO_REQUEST";
*/

$query			= "SELECT DISTINCT a.NO_CONTAINER, a.NO_REQUEST, b.SIZE_, A.STATUS , b.TYPE_, A.TGL_DELIVERY TGL_REQUEST_DELIVERY, n.NO_NOTA, n.EMKL nm_pbm
                            FROM MASTER_CONTAINER b, CONTAINER_DELIVERY a, REQUEST_DELIVERY d, nota_delivery n
                            WHERE a.NO_CONTAINER = b.NO_CONTAINER
                            AND a.NO_REQUEST = d.NO_REQUEST
                            --AND a.NO_CONTAINER (+) = P.NO_CONTAINER
                            AND n.no_request = d.no_request
                            AND b.LOCATION = 'IN_YARD'
                            AND a.AKTIF = 'Y'
                            AND a.NO_CONTAINER LIKE '%$no_cont%' AND d.DELIVERY_KE = 'LUAR'
                            --AND a.ID_YARD = '46'
                            and N.LUNAS = 'YES' and N.STATUS <> 'BATAL'
                            ORDER BY a.NO_REQUEST";
							
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
//print_r($row);

echo json_encode($row);


?>