<?php

//debug ($_POST);die;
$db = getDB();

$no_cont = strtoupper($_POST["NC"]);
$iso = $_POST["ISO"];

	
	$query = "UPDATE MST_CONT_PONTI SET ISO_CODE = '$iso' WHERE NO_CONTAINER='$no_cont'";
	$db->query($query);
	$msg='OK';
	echo $msg;
?>