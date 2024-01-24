<?php
	$id = $_GET['kode'];
	//print_r( $_GET['id']);die;

	$db = getDB();
	$query = "DELETE FROM MASTER_VESSEL WHERE KODE_KAPAL = '$id'";
	$result = $db->query($query);
	
	header('location:'.HOME.'maintenance.master.vessel/');
	
?>