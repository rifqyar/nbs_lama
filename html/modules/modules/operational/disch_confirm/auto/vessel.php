<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB();	
$query 			= "SELECT NO_UKK AS ID_VS,
					      NM_KAPAL,
					      VOYAGE_IN|| ' - ' ||VOYAGE_OUT AS VOYAGE,
						  TO_CHAR(TGL_JAM_TIBA,'YYYYMMDD') AS SEQ
					FROM RBM_H
                    WHERE FLAG_SYNC_BP = '1'
					AND FLAG_SYNC_CARD = '1'
					AND (NM_KAPAL LIKE '%$nama%' OR VOYAGE_IN LIKE '%$nama%')
					ORDER BY SEQ DESC";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>