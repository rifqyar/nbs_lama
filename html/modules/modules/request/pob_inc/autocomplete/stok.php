<?php

$no			= $_GET["term"];

$db 			  = getDB();
$query 			= "SELECT NO_STOK,
						  TGL_STOK,
						  PERIODE,
						  NO_SERIX,
						  NO_SERIY,
						  JUMLAH_LEMBAR 
					    FROM POB_REQUEST 
					    WHERE NO_STOK LIKE '%$no%'
              AND FLAG = 'N'"; 
					    
$result			= $db->query($query);
$row			  = $result->getAll();	
//echo $query;
echo json_encode($row);

?>
