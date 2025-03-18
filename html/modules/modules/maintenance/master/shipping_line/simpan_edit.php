<?php
	$id_ship = $_POST['id_ship'];
	$nama = $_POST['nama'];
	$alamat = $_POST['alamat'];
	$npwp = $_POST['npwp'];
	$coa = $_POST['coa'];
	$id = $_GET['id'];
	
	$db =getDB();
	$query = "UPDATE tb_master_ship_line SET ID_SHIP='$id_ship', NAMA='$nama', ALAMAT='$alamat', NPWP='$npwp', COA='$coa' WHERE ID_SHIP='$id'";
	$result = $db->query($query);
	
	header('location:'.HOME.'maintenance.master.shipping_line/');
?> 