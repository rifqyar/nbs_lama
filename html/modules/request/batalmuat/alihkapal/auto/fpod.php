<?php
//utk menon-aktifkan template default
outputRaw();
$pod = strtoupper($_GET["term"]);
$db = getDB("dbint");

$query = "SELECT CDG_PORT_NAME,
                 CDG_PORT_CODE
            FROM CDG_PORT
            WHERE CDG_PORT_NAME LIKE '%$pod%'OR CDG_PORT_CODE LIKE '%$pod%'";
			
$result = $db->query($query);
$row = $result->getAll();	

echo json_encode($row);
?>