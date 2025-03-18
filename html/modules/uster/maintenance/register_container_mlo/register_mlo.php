<?php
	$db 			= getDB("storage");
	$no_cont = $_POST["no_container"];
	$query = "UPDATE MASTER_CONTAINER SET MLO = 'MLO' WHERE NO_CONTAINER = '$no_cont'";
	if($db->query($query)){
		echo "ok";
		exit();
	}
	else{
		echo "not_ok";
		exit();
	}
?>