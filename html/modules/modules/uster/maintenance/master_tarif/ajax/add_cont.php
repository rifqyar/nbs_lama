<?php

$db 		= getDB("storage");

$no_cont_	= $_POST["no_cont"]; 
$size		= $_POST["size"]; 
$type		= $_POST["type"]; 

$query_cek		= "SELECT NO_CONTAINER FROM master_container WHERE NO_CONTAINER ='$no_cont'";
//echo $query_cek;die;
$result_cek		= $db->query($query_cek);
$row_cek		= $result_cek->fetchRow();
$no_cont		= $row_cek["NO_CONTAINER"];


if($no_cont <> NULL) 
{
          echo "CONT_SDH_ADA";
} else {
        $query_insert           = "INSERT INTO master_container (NO_CONTAINER, SIZE_, TYPE_, LOCATION, ID_YARD) VALUES ('$no_cont_','$size','$type','GATO','')";
        
	if($db->query($query_insert))
	{
		echo "OK";
	}
}

        
?>