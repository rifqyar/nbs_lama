<?php
	$id = $_GET['id'];
	$db = getDB();
	$query = "DELETE FROM tb_master_ship_line WHERE ID_SHIP = '$id'";
	$result = $db->query($query);
	
	header('location:'.HOME.'maintenance.master.shipping_line/');
?>