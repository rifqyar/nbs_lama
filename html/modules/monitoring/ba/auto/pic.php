<?php

//$nama			= strtoupper($_GET["term"]);
$nama			= $_GET["term"];

$db 			= getDB();
$query 			= "SELECT ID,
					      NAME,
						  NIPP
					FROM TB_USER
                    WHERE ID_GROUP IN ('1','c')
					AND NAME LIKE '%$nama%'
					ORDER BY ID DESC";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>