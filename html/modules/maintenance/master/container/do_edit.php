<?php
	$size = $_POST['size'];
	$type = $_POST['type'];
	$status = $_POST['status'];
	$id = $_GET['id'];
	
	$db =getDB("manual");
	$query = "UPDATE tb_container_master SET SIZE='$size', TYPE='$type', STATUS='$status' WHERE ID_CONT='$id'";
	$result = $db->query($query);
	
	header('location:'.HOME.'coba/'); 
?> 