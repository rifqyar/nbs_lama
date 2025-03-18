<?php

//debug ($_POST);die;
$db = getDB();

$no_cont = strtoupper($_POST["NC"]);
$iso = $_POST["ISO"];

	
	$query = "INSERT INTO MST_CONT_PONTI (NO_CONTAINER,ISO_CODE) VALUES('$no_cont','$iso')";
	$db->query($query);
	$msg='OK';
	echo $msg;
?>