<?php

$db 		= getDB("storage");

$no_peraturan	= $_POST["no_peraturan"]; 
$tgl_peraturan	= $_POST["from"]; 
$deposit		= $_POST["deposit"]; 
print_r($no_peraturan);die();
//print_r($no_peraturan);die();

$query_cek		= "SELECT NO_PERATURAN FROM MASTER_MATERAI WHERE NO_PERATURAN IS NOT NULL";
//echo $query_cek;die();

if(isset($_GET['no_peraturan']) && $_GET['no_peraturan'] != NULL){
        $idreq = $_GET['no_peraturan'];
        $query .= " AND NO_PERATURAN = '$idreq'";
    }    
	
	if(isset($_GET['id_time']) && $_GET['id_time'] != NULL){
        $idtime = $_GET['id_time'];
        $query .= " AND TO_CHAR(TGL_PERATURAN,'YYYY-MM-DD') = '$idtime'";
    }

	
	$res = $db->query($query)->fetchRow();
$result_cek		= $db->query($query_cek);
$row_cek		= $result_cek->fetchRow();
$no_peraturan2		= $row_cek["NO_PERATURAN"];

echo "OK";

// $max_id="SELECT max(id)+1 AS id FROM MASTER_MATERAI";
// print_r($max_id);die();

       

        
        
?>