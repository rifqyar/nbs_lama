<?php
$nama			= strtoupper($_GET["term"]);

$db 			= getDB();
	
$query 			= " SELECT * FROM (select NO_UKK, NM_KAPAL, CONCAT(CONCAT(VOYAGE_IN,'/'),VOYAGE_OUT) VOYAGE, VOYAGE_IN, VOYAGE_OUT from RBM_H WHERE NM_KAPAL LIKE '%$nama%' ORDER BY NO_UKK DESC) WHERE ROWNUM <= 5";
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>