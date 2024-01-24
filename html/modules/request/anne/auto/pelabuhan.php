<?php
$pel		= strtoupper($_GET["term"]);

$db 			= getDB(dbint);

$query			= "SELECT CDG_PORT_CODE as ID_PEL, CDG_PORT_NAME as PELABUHAN, ' ' as NAMA_NEG FROM CDG_PORT WHERE (CDG_PORT_CODE LIKE '%$pel%' OR CDG_PORT_name LIKE '%$pel%') AND ROWNUM < 4";
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>