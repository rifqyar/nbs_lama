<?php
$db = getDB("storage");
$no_req = $_POST["NO_REQ"];
$q_save = "UPDATE PLAN_REQUEST_STRIPPING SET CLOSING = '' WHERE NO_REQUEST = '$no_req'";
if($db->query($q_save)){
	$db->query("UPDATE REQUEST_STRIPPING SET CLOSING = '' WHERE NO_REQUEST = REPLACE('$no_req', 'P' , 'S')");
	echo "OK";
	exit();
}
else{
	echo "FAIL";
	exit();
}

?>