<?php

	$no_stok = $_POST['STOK'];
	$periode = $_POST['PERIODE'];
	$serix = $_POST['SERIX'];
	$seriy = $_POST['SERIY'];
	$lembar = $_POST['LEMBAR'];
	$tgl = $_POST['TGL'];
	$keterangan = $_POST['KETERANGAN'];
	$total = $_POST['TOTAL'];
	$ppn = $_POST['PPN'];
	$pendapatan = $_POST['PENDAPATAN'];
	
	$db =getDB();
	$q = "select generate_nota from dual";
	$result = $db->query($q);
	$row = $result->fetchRow();
	$no_nota = $row["GENERATE_NOTA"];
	//echo "$no_stok";
	//die();
	$query = "INSERT INTO pob_pendapatan VALUES ('$no_nota',to_date('$tgl','mm/dd/yyyy'),'$no_stok','$lembar','$serix','$seriy','10000',
                                              '$total','$ppn','$pendapatan','$keterangan','N')";
  $result = $db->query($query);
	//echo "$query";
	
	$update = "UPDATE pob_request
            SET FLAG='N'
            WHERE NO_STOK = '$no_stok'";
  $result = $db->query($update);
	header('location:'.HOME.'request.pob_inc/');

?>
