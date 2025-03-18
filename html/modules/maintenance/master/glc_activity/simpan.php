<?php
	$keg = strtoupper($_POST['kegiatan']);
	$stat = $_POST['status'];
	$alat = $_POST['alat'];
	$tarif = $_POST['tarif'];
	
	$db =getDB();
	$query = "INSERT INTO MASTER_KEGIATAN (KEGIATAN,STATUS,ALAT,TARIF) VALUES ('$keg','$stat','$alat','$tarif')";
	$result = $db->query($query);
	
	header('location:'.HOME.'maintenance.master.glc_activity/');
?> 