<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB();	
$query 			= "SELECT NO_UKK AS ID_VS,
					      NM_KAPAL,
					      VOYAGE_IN,
					      VOYAGE_OUT,
						  TO_CHAR(TGL_JAM_TIBA,'DD-MM-YYYY HH24:MI') AS TGL_JAM_TIBA
					FROM TR_VESSEL_SCHEDULE_ICT
                    WHERE FLAG_PROFILE = 'Y'
					AND NM_KAPAL LIKE '%$nama%'
					ORDER BY TGL_JAM_TIBA DESC";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>