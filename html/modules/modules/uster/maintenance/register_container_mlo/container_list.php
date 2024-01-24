<?php
$tl = xliteTemplate('container_list.htm');
$db = getDB("storage");

/* $cari_ = $_POST["CARI"];
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
} */
	$query_mlo = "SELECT * FROM MASTER_CONTAINER WHERE MLO = 'MLO'";

$result_mlo = $db->query($query_mlo);
$row_mlo = $result_mlo->getAll();
$tl->assign('row_mlo',$row_mlo);
$tl->renderToScreen();
?>