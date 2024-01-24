<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB("dbint");
	

$query 			= "SELECT
                        TML_CD,
                        CDG_PORT_CODE,
                        CDG_PORT_NAME
                    FROM
                        CDG_PORT_PALAPA cpp
                    WHERE 
                        TML_CD = 'PNK'
                        AND cpp.CDG_PORT_NAME LIKE '%$nama%'";

$result		= $db->query($query);
$row		= $result->getAll();	
// echo $query;
echo json_encode($row);


?>