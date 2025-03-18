<?php
	
	$db         = getDB('storage');
	
	$status   		 = $_POST["status"];
	$id_nota    	 = $_POST["id_nota"];
	$komp_nota    	 = $_POST["komp_nota"];

	$komp_nota = "UPDATE MASTER_KOMP_NOTA SET STATUS = '$status' WHERE ID_NOTA = '$id_nota' AND ID_KOMP_NOTA = '$komp_nota'";

	if ($db->query($komp_nota)) {
		echo "sukses";
	} else {
		echo "gagal";
	}
?>
