<?php
$db = getDB("storage");
$nama = $_POST['name'];
$nipp = $_POST['nipp'];
$username = $_POST['username'];
$kelas = $_POST['kelas'];
$jabatan = $_POST['jabatan'];
$rule = $_POST['rule'];
$id = $_POST['id'];
$r_cek = $db->query("SELECT COUNT(NIPP) JUM FROM MASTER_USER WHERE NIPP = '$nipp' AND ID NOT IN ('$id')");
$row_cek = $r_cek->fetchRow();
$cek = $row_cek["JUM"];
if($cek > 0){
	echo 'nipp exist';
	exit();
}
else{
	$db->query("UPDATE MASTER_USER SET NAMA_LENGKAP = '$nama', NIPP = '$nipp', USERNAME = '$username', 
	KELAS = '$kelas',  JABATAN = '$jabatan', ID_ROLE = '$rule' WHERE ID = '$id'");
	echo 'ok';
	exit();
}
?>