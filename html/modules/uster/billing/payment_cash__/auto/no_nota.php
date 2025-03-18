<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
/*$query 			= "select ID, NAMA from MASTER_PBM WHERE NAMA LIKE '%$nama%' "; */

$query 			= "SELECT NO_NOTA, NO_REQUEST, EMKL, 'RECEIVING' KEGIATAN FROM nota_receiving WHERE NO_NOTA LIKE '%$nama%'
UNION
SELECT NO_NOTA, NO_REQUEST, EMKL, 'STUFFING' KEGIATAN FROM nota_stuffing WHERE NO_NOTA LIKE '%$nama%'
UNION 
SELECT NO_NOTA, NO_REQUEST, EMKL,  'STRIPPING' KEGIATAN  FROM nota_stripping WHERE NO_NOTA LIKE '%$nama%'
UNION
SELECT NO_NOTA, NO_REQUEST, EMKL,  'DELIVERY' KEGIATAN  FROM nota_delivery WHERE NO_NOTA LIKE '%$nama%'"; 

$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>