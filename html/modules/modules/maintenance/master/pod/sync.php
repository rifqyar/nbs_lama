<?php
	$kd_pel_ict 	= $_POST['kd_pel_ict'];
	$kd_pel_inhouse = $_POST['kd_pel_inhouse'];
	
	$db =getDB();
	$query = "UPDATE MASTER_PELABUHAN SET ID_PEL_ICT = '$kd_pel_ict' WHERE ID_PEL ='$kd_pel_inhouse'";
	
	if ($db->query($query)) {
		echo "OK";
	} else {
		echo "NOT";
	}
	
?> 