<?php
$pel		= strtoupper($_GET["term"]);
$vessel = $_GET["vessel"];
$db 			= getDB(dbint);

//$query			= "SELECT CDG_PORT_CODE as ID_PEL, CDG_PORT_NAME as PELABUHAN, ' ' as NAMA_NEG FROM CDG_PORT WHERE (CDG_PORT_CODE LIKE '%$pel%' OR CDG_PORT_name LIKE '%$pel%') AND ROWNUM < 4";
$query = "SELECT CDG_PORT_CODE AS ID_PEL, CDG_PORT_NAME AS PELABUHAN, ' ' AS NAMA_NEG
  FROM CDG_PORT
 WHERE     (CDG_PORT_CODE LIKE '%$pel%' OR CDG_PORT_name LIKE '%$pel%')
       AND ROWNUM < 6";
//$query 			= "SELECT PELABUHAN, ID_PEL, NAMA_NEG FROM MASTER_PELABUHAN WHERE PELABUHAN LIKE '%$pel%' OR ID_PEL LIKE '%$pel%' AND ROWNUM = 4";
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>