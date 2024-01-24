<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB();	
$query 			= "SELECT ID_PEL,
						PELABUHAN
					FROM MASTER_PELABUHAN
                    WHERE PELABUHAN LIKE '$nama%'
					ORDER BY ID_PEL ASC";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>