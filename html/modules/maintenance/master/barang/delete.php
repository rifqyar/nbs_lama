<?php
	$id = $_GET['id'];
	$db = getDB();
	$query = "DELETE FROM MASTER_BARANG WHERE KODE_BARANG = '$id'";
	$result = $db->query($query);
	
	header('location:'.HOME.'maintenance.master.barang/');
?>