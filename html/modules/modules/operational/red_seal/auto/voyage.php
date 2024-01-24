<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB();	
$query 			= "select ID_VES, GET_NAMAKPL(ID_VES) NAMA_VESSEL, ID_VS, VOYAGE, ID_PEL_ASAL, PELABUHAN_ASAL, ID_PEL_TUJ, PELABUHAN_TUJUAN from VESSEL_SCHEDULE WHERE GET_NAMAKPL(ID_VES) LIKE '%$nama%' ";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>