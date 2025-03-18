<?php

$db 		= getDB("storage");

$no_peraturan	= $_POST["no_peraturan"]; 
$tgl_peraturan	= $_POST["from"]; 
$deposit		= $_POST["deposit"]; 
//print_r($no_peraturan);die();

$query_cek		= "SELECT NO_PERATURAN FROM MASTER_MATERAI WHERE NO_PERATURAN ='$no_peraturan'";
//echo $query_cek;die();
$result_cek		= $db->query($query_cek);
$row_cek		= $result_cek->fetchRow();

// $max_id="SELECT max(id)+1 AS id FROM MASTER_MATERAI";
// print_r($max_id);die();


if($row_cek < 1)
{

	if ($no_peraturan !='' && $tgl_peraturan !='' && $deposit!='') {
		$query_insert="INSERT INTO MASTER_MATERAI (NO_PERATURAN, TGL_PERATURAN, NOMINAL,STATUS,SALDO,TERPAKAI) VALUES ('$no_peraturan', TO_DATE('$tgl_peraturan', 'YYYY-MM-DD HH24:MI:SS'), '$deposit','N','$deposit','0')";
		$db->query($query_insert);
	}
		
}
else
{
	echo "EXIST";	
}

       

        
        
?>