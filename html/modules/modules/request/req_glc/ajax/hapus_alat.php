<?php

$db 		= getDB();

$id_req		= $_POST["ID_REQ"];
$id_details  = $_POST["ID_DETAILS"];
$stat_form  = $_POST["STAT_FORM"];

if($stat_form=="req")
{
	$query_delete = "DELETE FROM GLC_DETAIL_EST_SHIFT WHERE ID_REQ = '$id_req' AND ID_DETAILS = '$id_details'";
}
else
{
	$query_delete = "DELETE FROM GLC_DETAIL_REAL_SHIFT WHERE ID_REQ = '$id_req' AND ID_DETAILS = '$id_details'";
}	
	$query_delete2 = "DELETE FROM GLC_PRODUKSI WHERE ID_REQ = '$id_req' AND ID_DETAILS = '$id_details'";	
	$db->query($query_delete2);
	
	if($db->query($query_delete))
	{	
		echo "OK";
	}
	else
	{ 
		echo "gagal";exit;
	}


?>