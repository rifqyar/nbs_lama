<?php
	$db=  getDB();
	$kd_pelanggan=$_POST['kd_pelanggan'];
	
	$query="DELETE FROM MST_PELANGGAN_PPN
	WHERE KD_PELANGGAN = '$kd_pelanggan'";
	$db->query($query);
	echo "$kd_pelanggan berhasil dihapus";
?>
