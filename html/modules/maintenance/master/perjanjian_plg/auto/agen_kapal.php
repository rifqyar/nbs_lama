<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB();
$query 			= "SELECT KD_AGEN,
					      NM_AGEN,
						  NO_ACCOUNT
					FROM MASTER_AGEN
                    WHERE NM_AGEN LIKE '%$nama%'";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>