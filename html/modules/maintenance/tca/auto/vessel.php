<?php

$vessel			= strtoupper($_GET["term"]);
$db 	       	= getDB();	


$query 			= "select * from m_vsb_voyage@dbint_link where (vessel like '$vessel%' or voyage_in like '$vessel%') order by eta desc";
$result			= $db->query($query);
$row			= $result->getAll();	

//echo $query;
echo json_encode($row);


?>