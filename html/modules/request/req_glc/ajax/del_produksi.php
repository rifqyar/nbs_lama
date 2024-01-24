<?php

$db 		= getDB();
$id_dtl		= $_POST["ID"];

	$query_delete = "DELETE FROM GLC_PRODUKSI WHERE ID = '$id_dtl'";	
	
	if($db->query($query_delete))
	{	
		echo "OK";
	}
	else
	{ 
		echo "gagal";exit;
	}


?>