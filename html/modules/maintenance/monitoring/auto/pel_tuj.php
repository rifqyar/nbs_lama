<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB();	
$query 			= "SELECT ID_PEL_TUJ, PEL_TUJ
					FROM TR_NOTA_ANNE_ICT_H
                    WHERE PEL_TUJ LIKE '%$nama%'
					GROUP BY ID_PEL_TUJ, PEL_TUJ
					ORDER BY ID_PEL_TUJ DESC";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>