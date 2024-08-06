<?php
	$db = getDB("storage");
	$no_cont = $_POST['NO_CONT'];
    $no_request = $_POST['NO_REQUEST'];
    $status = $_POST['AKTIF'];

	$q = "UPDATE CONTAINER_RECEIVING SET AKTIF = '$status' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_request'";
	if ($db->query($q)) {
		echo "OK";
		die();
	}
	else {
		echo "NOT_OK";
		die();
	}
?>