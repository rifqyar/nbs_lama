<?php
	
	
	$nama = $_POST['nama'];
	$alamat = $_POST['alamat'];
	$npwp = $_POST['npwp'];
	$coa = $_POST['coa'];
	$status_pbm = $_POST['status_pbm'];
	$id = $_GET['id'];
	//print_r("UPDATE MASTER_PBM SET NAMA='$nama', ALAMAT='$alamat', NPWP='$npwp', COA='$coa', STATUS_PBM='$status_pbm' WHERE KODE_PBM='$id'");die;
	$db =getDB();
	
	$query = "UPDATE MASTER_PBM SET NAMA='$nama', ALAMAT='$alamat', NPWP='$npwp', COA='$coa', STATUS_PBM='$status_pbm' WHERE KODE_PBM='$id'";
		
	$result = $db->query($query);	
	header('location:'.HOME.'maintenance.master.pbm/');
?> 