<?php
	$ID = $_GET['ID'];
	//print_r( $_GET['ID']);die;

	$db = getDB();
	$query = "DELETE FROM TB_USER WHERE ID = '$ID'";
	$result = $db->query($query);
	
	header('location:'.HOME.'maintenance.master.user/');
	
?>