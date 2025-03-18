<?php
$db = getDB("storage");
$no_req = $_POST["NO_REQ"];
$remark = $_POST["REMARK"];
$no_cont = $_POST["NO_CONT"];

$db->query("UPDATE PLAN_CONTAINER_STRIPPING SET REMARK = '$remark' WHERE NO_REQUEST = '$no_req' AND NO_CONTAINER = '$no_cont'");
$db->query("UPDATE CONTAINER_STRIPPING SET REMARK = '$remark' WHERE NO_REQUEST = REPLACE('$no_req', 'P' , 'S') AND NO_CONTAINER = '$no_cont'");

$q_save = "UPDATE PLAN_REQUEST_STRIPPING SET CLOSING = 'CLOSED' WHERE NO_REQUEST = '$no_req'";
if($db->query($q_save)){
	$db->query("UPDATE REQUEST_STRIPPING SET CLOSING = 'CLOSED' WHERE NO_REQUEST = REPLACE('$no_req', 'P' , 'S')");
	echo "OK";
	exit();
}
else{
	echo "FAIL";
	exit();
}

?>