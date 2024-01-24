<?php
	$id = $_GET['id'];
	$db = getDB();
	$query = "DELETE FROM MASTER_KEGIATAN WHERE ID_ACT = '$id'";
	$result = $db->query($query);
	
	header('location:'.HOME.'maintenance.master.glc_activity/');
?>