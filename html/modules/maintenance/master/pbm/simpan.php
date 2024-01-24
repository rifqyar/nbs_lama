<?php
	
	$kode_pbm = $_POST['kode_pbm'];
	$nama = $_POST['nama'];
	$alamat = $_POST['alamat'];
	$npwp = $_POST['npwp'];
	$coa = $_POST['coa'];
	$status_pbm = $_POST['status_pbm'];
	//print_r($status);die;	
	$db =getDB();
	$query = "INSERT INTO MASTER_PBM VALUES ('$kode_pbm','$nama','$alamat','$npwp','$coa','$status_pbm')";
	$result = $db->query($query);
	
	
	
	header('location:'.HOME.'maintenance.master.pbm/');
?> 