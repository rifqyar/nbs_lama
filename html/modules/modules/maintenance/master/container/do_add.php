<?php
	$size = $_POST['size'];
	$type = $_POST['type'];
	$status = $_POST['status'];
	
	$db =getDB("manual");
	$query = "INSERT INTO tb_container_master VALUES ('','$size','$type','$status')";
	$result = $db->query($query);
	
	header('location:'.HOME.'coba/');
?> 