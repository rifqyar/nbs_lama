<?php
	$kode= $_POST['kode_barang'];
	$ukuran = $_POST['ukuran'];
	$type = $_POST['type'];
	$status = $_POST['status'];
	$id = $_GET['id'];
	//print_r($ukuran);die;
	//print_r(UPDATE MASTER_BARANG SET KODE_BARANG='$kode_barang', UKURAN='$ukuran', TYPE='$type', STATUS='$status' WHERE KODE_BARANG='$id');die;
	//print_r ($_GET['id']);die; 
	$db =getDB();
	$query = "UPDATE MASTER_BARANG SET KODE_BARANG='$kode', UKURAN='$ukuran', TYPE='$type', STATUS='$status' WHERE KODE_BARANG='$id'";
	$result = $db->query($query);
	
	header('location:'.HOME.'maintenance.master.barang/');
?> 