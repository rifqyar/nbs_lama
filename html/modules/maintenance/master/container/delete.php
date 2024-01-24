<?php
	$id = $_GET['id'];
	$db = getDB("manual");
	$query = "DELETE FROM tb_container_master WHERE ID_CONT = $id";
	$result = $db->query($query);
	
	header('Refresh: 3; url='.HOME.'coba/'); 
?>