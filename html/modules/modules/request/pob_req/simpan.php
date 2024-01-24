<?php

	$tgl = $_POST['tgl'];
	$periode = $_POST['periode'];
	$seri_awal = $_POST['seri_awal'];
	$seri_akhir = $_POST['seri_akhir'];
	$lembar = $_POST['lembar'];
	$keterangan = $_POST['keterangan'];
	
	$db =getDB();
	$q = "select generate_stok from dual";
	$result = $db->query($q);
	$row = $result->fetchRow();
	$no_stok = $row["GENERATE_STOK"];
	//echo "$no_stok";
	//die();
	$query = "INSERT INTO pob_request VALUES ('$no_stok',to_date('$tgl','mm/dd/yyyy'),'$periode','$seri_awal','$seri_akhir','$lembar','$keterangan')";
  $result = $db->query($query);
	//echo "$query";
	header('location:'.HOME.'request.pob_req/');

?>
