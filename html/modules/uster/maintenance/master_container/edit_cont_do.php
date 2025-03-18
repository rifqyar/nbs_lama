<?php
	$db = getDB("storage");
	$NO_CONT = $_POST["NO_CONT"];
	$SIZE_ = $_POST["SIZE"];
	$TYPE_ = $_POST["TIPE"];
	$LOCATION = $_POST["LOCATION"];
	$q = "UPDATE MASTER_CONTAINER SET SIZE_ = '$SIZE_', TYPE_ = '$TYPE_', LOCATION = '$LOCATION' WHERE NO_CONTAINER = '$NO_CONT'";
	if ($db->query($q)) {
		echo "OK";
		die();
	}
	else {
		echo "NOT_OK";
		die();
	}
?>