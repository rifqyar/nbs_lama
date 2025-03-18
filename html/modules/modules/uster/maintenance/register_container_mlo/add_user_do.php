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
$db->query("INSERT INTO MASTER_USER(NAMA_LENGKAP, NIPP, USERNAME, KELAS, JABATAN, ID_ROLE, ID_YARD, PASSWORD) VALUES ('$nama','$nipp','$username','$kelas','$jabatan','$rule',46,'$password')");

?>