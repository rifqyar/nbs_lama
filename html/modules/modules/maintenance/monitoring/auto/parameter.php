<?php
$param		= strtoupper($_GET["term"]);

$db 			= getDB();
	
$query 			= "SELECT NO_UKK AS ID_VS,
					      NM_KAPAL,
					      VOYAGE_IN|| ' - ' ||VOYAGE_OUT AS VOYAGE
					FROM RBM_H
                    WHERE NM_KAPAL LIKE '%$param%'
					ORDER BY TGL_JAM_TIBA DESC";

$result			= $db->query($query);
$row			= $result->getAll();		

//print_r($row);

echo json_encode($row);


?>