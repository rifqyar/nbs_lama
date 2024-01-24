<?php
	
	$kode = $_POST['KODE_KAPAL'];
	$nama = $_POST['NAMA_VESSEL'];
	$gt = $_POST['GT'];
	$loa = $_POST['LOA'];
	
	$db =getDB();
	$query = "INSERT INTO MASTER_VESSEL VALUES ('$kode', '$nama', '$gt', '$loa')";
	$result = $db->query($query);
	
	header('location:'.HOME.'maintenance.master.vessel/');
?> 