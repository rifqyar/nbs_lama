<?php
      
$db   			= getDB("manifest");
	 
$query 			= "select ID, NAMA from m_alat_angkut LIMIT 0,7";
$result			= $db->query($query);
$row 			= $result->getAll();	
     
//print_r($row);
      
echo json_encode($row);
     
     
?>