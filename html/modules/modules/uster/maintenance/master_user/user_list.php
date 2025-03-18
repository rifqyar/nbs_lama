<?php
$tl = xliteTemplate('user_list.htm');
$db = getDB("storage");

$cari_ = $_POST["CARI"];
$nama_	= $_POST["NAMA"];
$nipp_ = $_POST["NIPP"];
if($cari_ == "cari"){
	if($nama_ != NULL && $nipp_ == NULL){
		$query_user = "SELECT MASTER_USER.ID, ID_ROLE, NIPP, USERNAME, NAMA_LENGKAP, KELAS, JABATAN, NAMA_ROLE ROLE FROM MASTER_USER
		  INNER JOIN ROLE ON MASTER_USER.ID_ROLE = ROLE.ID WHERE NAMA_LENGKAP = '$nama_'";
	}
	else if($nama_ == NULL && $nipp_ != NULL){
		$query_user = "SELECT MASTER_USER.ID, ID_ROLE, NIPP, USERNAME, NAMA_LENGKAP, KELAS, JABATAN, NAMA_ROLE ROLE FROM MASTER_USER
		  INNER JOIN ROLE ON MASTER_USER.ID_ROLE = ROLE.ID WHERE NIPP = '$nipp_'";
	}
	else{
		$query_user = "SELECT MASTER_USER.ID, ID_ROLE, NIPP, USERNAME, NAMA_LENGKAP, KELAS, JABATAN, NAMA_ROLE ROLE FROM MASTER_USER
		  INNER JOIN ROLE ON MASTER_USER.ID_ROLE = ROLE.ID WHERE NIPP = '$nipp_' AND NAMA_LENGKAP = '$nama_'";
	}
}
else{
	$query_user = "SELECT MASTER_USER.ID, ID_ROLE, NIPP, USERNAME, NAMA_LENGKAP, KELAS, JABATAN, NAMA_ROLE ROLE FROM MASTER_USER
		INNER JOIN ROLE ON MASTER_USER.ID_ROLE = ROLE.ID";
}

$result_user = $db->query($query_user);
$row_user = $result_user->getAll();
$tl->assign('row_user',$row_user);
$tl->renderToScreen();
?>