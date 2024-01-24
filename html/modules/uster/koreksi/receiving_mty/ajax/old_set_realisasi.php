<?php

$db 			= getDB("storage");

$no_cont		= $_POST["NO_CONT"]; 
$no_req			= $_POST["NO_REQ"]; 

//cek apakah container tersebut status strippingnya aktif
$query_cek2		= "SELECT COUNT(1) AS JUM FROM CONTAINER_STRIPPING WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req' AND AKTIF = 'Y'";
$result_cek2	= $db->query($query_cek2);
$row_cek2		= $result_cek2->fetchRow();
if($row_cek2["JUM"] > 0)
{ 
	//cek apakah container tersebut masa strippingnya masih berlaku
	$query_cek1		= "SELECT 
								TGL_REQUEST, 
								CASE 
								WHEN SYSDATE-1 <= TGL_REQUEST+3 THEN 'OK'
								ELSE 'NO'
								END AS STATUS 
						FROM REQUEST_STRIPPING
						WHERE NO_REQUEST = '$no_req'";
	
	$result_cek1	= $db->query($query_cek1);
	$row_cek1		= $result_cek1->fetchRow();
	
	
	if($row_cek1["STATUS"] == "OK")
	{
		
		//update status aktif
		$query_update	= "UPDATE CONTAINER_STRIPPING SET AKTIF = 'T', TGL_REALISASI = SYSDATE WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";
		if($db->query($query_update))
		{
			//update status aktif kartu yang masih Y
			$query_update2	= "UPDATE KARTU_STRIPPING SET AKTIF = 'T' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";
			$db->query($query_update2);
			
			echo "OK";
		}
	}
	else
	{
		echo "OVER";	
	}
}
else
{
	echo "NOT_AKTIF";	
}
?>