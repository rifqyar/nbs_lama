<?php

$kapal			= TRIM(strtoupper($_GET["term"]));
$db 			= getDB('dbint');	


$query 			= "SELECT tgl_tiba,
							 nama_kapal,
							 no_voyage,
							 agen_pelayaran,
							 nobc11,
							 to_char(tgbc11,'dd/mm/rrrr') tgbc11
						FROM manifin_header@wportal
					   WHERE nama_kapal LIKE '%$kapal%'
					ORDER BY update_date DESC";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>