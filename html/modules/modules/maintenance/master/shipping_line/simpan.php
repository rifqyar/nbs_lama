<?php
	$id_ship = $_POST['id_ship'];
	$nama = $_POST['nama'];
	$alamat = $_POST['alamat'];
	$npwp = $_POST['npwp'];
	$coa = $_POST['coa'];
	
	$db =getDB();
	$query = "INSERT INTO tb_master_ship_line VALUES ('$id_ship','$nama','$alamat','$npwp','$coa')";
	$result = $db->query($query);
	
	header('location:'.HOME.'maintenance.master.shipping_line/');
?> 