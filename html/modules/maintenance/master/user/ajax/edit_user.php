<?php
$id_user = $_POST["ID_USER"];
$nama = $_POST['NAMA'];
$nipp = $_POST['NIPP'];
$divisi = $_POST['DIVISI'];
$jabatan = $_POST['JABATAN'];
//$username = $_POST['USERNAME'];
$password = $_POST['PASSWORD'];
$group = $_POST['GROUP'];
$aktif = $_POST['AKTIF'];
//echo $aktif; die();
//echo "begin user_upd(".$id_user.",'".$nama."','".$nip."','".$divisi."','".$jabatan."','".$password."','".$group."','".$aktif."'); end;"; die;
if(($id_user==""))
	echo "NO";
else
{
	$db=getDB();
	$db->query("begin user_upd(".$id_user.",'".$nama."','".$nipp."','".$divisi."','".$jabatan."','".$password."','".$group."','".$aktif."'); end;");
	echo "OK";
}
?>