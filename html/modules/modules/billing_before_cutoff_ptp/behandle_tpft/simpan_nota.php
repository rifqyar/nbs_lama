<?php
$db  = getDB();
	
$id_pranota = $_GET['id_pranota'];
$user       = $_SESSION["NAMA_PENGGUNA"];

$sql_xpi = "BEGIN SP_SIMPAN_NOTA_BHD('$id_pranota','$user'); END;";
$db->query($sql_xpi);
 
header('Location: '.HOME.'billing.behandle_tpft/');
?>