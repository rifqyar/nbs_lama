<?php

$db 		= getDB("storage");

$no_cont	= $_POST["NO_CONT"]; 
$no_req		= $_POST["NO_REQ"]; 
$no_pol		= $_POST["NO_POL"]; 

$id_user	= $_SESSION["LOGGED_STORAGE"];

$query_rec = "SELECT COUNT(NO_CONTAINER) AS JUM
			  FROM CONTAINER_RECEIVING
			  WHERE NO_CONTAINER = '$no_cont'
			  AND NO_REQUEST = '$no_req'
				";
$result_rec	= $db->query($query_rec);
$row_rec		= $result_rec->fetchRow();
$jum_req		= $row_rec["JUM"];

$query_lunas = "SELECT CASE
					 WHEN PERALIHAN = 'STUFFING' THEN (SELECT LUNAS FROM NOTA_STUFFING INNER JOIN REQUEST_STUFFING ON NOTA_STUFFING.NO_REQUEST = REQUEST_STUFFING.NO_REQUEST WHERE REQUEST_STUFFING.NO_REQUEST_RECEIVING = '$no_req')
					 WHEN PERALIHAN = 'STRIPPING' THEN (SELECT LUNAS FROM NOTA_STRIPPING INNER JOIN REQUEST_STRIPPING ON NOTA_STRIPPING.NO_REQUEST = REQUEST_STRIPPING.NO_REQUEST WHERE REQUEST_STRIPPING.NO_REQUEST_RECEIVING = '$no_req')
					 WHEN PERALIHAN = 'RELOKASI' THEN 'YES'
					 WHEN PERALIHAN IS NULL THEN (SELECT LUNAS FROM NOTA_RECEIVING WHERE NO_REQUEST = '$no_req')
					 ELSE 'NO'
					 END AS LUNAS
				FROM REQUEST_RECEIVING WHERE NO_REQUEST = '$no_req'
				";
				
$result_lunas	= $db->query($query_lunas);
$row_lunas		= $result_lunas->fetchRow();
$lunas		= $row_lunas["LUNAS"];

$query_gati = "SELECT LOCATION
			  FROM MASTER_CONTAINER
			  WHERE NO_CONTAINER = '$no_cont'
				";
$result_gati	= $db->query($query_gati);
$row_gati		= $result_gati->fetchRow();
$gati		= $row_gati["LOCATION"];



				
				
				
if($jum_req <= 0)
{
	
	echo "NO_REQUEST";
}
else if($lunas != "YES")
{
	echo "NOT_PAID";
}
else if($no_pol	 == NULL)
{
	echo "NO_POL";
}
else if($jum_req >= 0 && $lunas == "YES" && $no_pol	 != NULL && $gati != "GATI")
{
	$query_insert	= "INSERT INTO GATE_IN(NO_CONTAINER, NO_REQUEST,NOPOL, ID_USER, TGL_IN) VALUES('$no_cont', '$no_req','$no_pol', '$id_user', SYSDATE)";
	$result_insert	= $db->query($query_insert);
	$query_upd	= "UPDATE MASTER_CONTAINER SET LOCATION = 'GATI' WHERE NO_CONTAINER = '$no_cont'";
	$db->query($query_upd);
	
	echo "OK";
}
else if($gati == "GATI")
{
	echo "EXIST";
}
?>