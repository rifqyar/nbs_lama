<?php

$db 		= getDB("storage");

$yard_id        = $_POST["yard_id"]; 
$id_cell        = $_POST["id_cell"];
//ECHO $query_cek;
$query_yard_id  = "SELECT a.ID_CELL ID FROM YD_PLACEMENT_YARD a, YD_BLOCKING_AREA b WHERE a.ID_BLOCKING_AREA = b.ID AND b.ID_YARD_AREA = '$yard_id' AND a.ID_CELL = '$id_cell'";
$result         = $db->query($query_yard_id);
$row            = $result->fetchRow();
$cek            = $row["ID"];

if($cek != NULL)
{
     echo "EXIST";
} else {
    echo "NOT EXIST";
}

        
?>