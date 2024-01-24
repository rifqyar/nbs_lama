<?php
	$kode_barang = $_POST['kode_barang'];
	$ukuran = $_POST['ukuran'];
	$type = $_POST['type'];
	$status = $_POST['status'];
	
	$db =getDB();
	$query = "INSERT INTO MASTER_BARANG (kode_barang, ukuran, type, status) VALUES ('$kode_barang','$ukuran','$type','$status')";
	$result = $db->query($query);
	
	header('location:'.HOME.'maintenance.master.barang/');
?> 