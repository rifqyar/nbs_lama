<?php
$db = getDB("storage");
$nama = $_POST['name'];
$nipp = $_POST['nipp'];
$username = $_POST['username'];
$kelas = $_POST['kelas'];
$jabatan = $_POST['jabatan'];
$rule = $_POST['rule'];
$password_ = $_POST['password'];
$password = md5($password_);
$r_cek = $db->query("SELECT COUNT(NIPP) JUM FROM MASTER_USER WHERE NIPP = '$nipp'");
$row_cek = $r_cek->fetchRow();
$cek = $row_cek["JUM"];
if($cek > 0){
	echo 'nipp exist';
	exit();
}
else{
	$db->query("INSERT INTO MASTER_USER(NAMA_LENGKAP, NIPP, USERNAME, KELAS, JABATAN, ID_ROLE, ID_YARD, PASSWORD) VALUES ('$nama','$nipp','$username','$kelas','$jabatan','$rule',46,'$password')");
	echo 'ok';
	exit();
}


?>