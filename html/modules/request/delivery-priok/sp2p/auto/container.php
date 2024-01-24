<?php
$ship 			= $_GET["ship"];
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB();
	
$query 			= " SELECT NO_CONTAINER, SIZE_, TYPE_, STATUS, ISO_CODE, HEIGHT,
			        CARRIER, IMO, TEMP, HZ, COMODITY, NO_UKK
					FROM ISWS_LIST_CONTAINER 
					WHERE TGL_PLACEMENT IS NOT NULL 
					AND E_I = 'I'
					AND OI = '$ship'
					AND NO_CONTAINER LIKE '%$no_cont%'";
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>