<?php

$voy			= strtoupper($_GET["term"]);

$db 			= getDB("storage");
/*
$query_booking ="SELECT NO_BOOKING
					  FROM VOYAGE
					  WHERE VOYAGE LIKE '%$voy%''
					  ORDER BY NO_BOOKING DESC
						";
$result_booking = $db->query($query_booking);
$row_booking	= $result_booking->fetchRow();
$no_booking		= $row_booking["NO_BOOKING"];

*/

$query 			= "SELECT *
					FROM (SELECT a.NAMA_VESSEL AS VESSEL,
								 b.VOYAGE AS VOYAGE,
								 b.NO_BOOKING AS NO_BOOKING
							FROM MASTER_VESSEL a, 
								 VOYAGE b
							WHERE  a.NAMA_VESSEL LIKE '%$voy%' 
								OR b.VOYAGE LIKE '%$voy%'
							   AND a.KODE_VESSEL = b.KODE_VESSEL
							   ORDER BY NO_BOOKING DESC)
							WHERE ROWNUM <= 3
							ORDER BY ROWNUM ASC
				   ";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>