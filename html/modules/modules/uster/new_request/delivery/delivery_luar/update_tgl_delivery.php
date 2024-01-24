<?php
$db 	= getDB("storage");
$no_req = $_POST["NO_REQ"];
$total = $_POST["TOTAL"];
$index = $_POST["INDEX"];
$no_cont = $_POST["NO_CONT"];
$tgl_delivery = $_POST["TGL_DELIVERY"];

$q_save = "UPDATE CONTAINER_DELIVERY SET TGL_DELIVERY = TO_DATE('$tgl_delivery','yyyy-mm-dd') WHERE NO_REQUEST = '$no_req' AND NO_CONTAINER = '$no_cont'";

if($db->query($q_save)){
	if($index == $total){
		echo "OK";
		exit();
	}
}
else{
	echo "FAIL";
	exit();
}

?>