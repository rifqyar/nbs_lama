<?php

$id_bm	        =	$_POST['id_bm'];
$nm_user        =   $_SESSION['NAMA_LENGKAP'];
$id_user        =   $_SESSION['ID_USER'];
$status_gate	=	$_POST['status_gate'];
$pbm		    =	$_POST['kode_pbm'];
$jenis_bm   	=	$_POST['jenis_batalmuat'];
$vessel 		=	$_POST['vessel'];
$tgl_berangkat	=	$_POST['tgl_berangkat'];
$voyage			=	$_POST['voyage'];
$tgl_keluar		=	$_POST['tgl_keluar'];

$db = getDB();
$query = "INSERT INTO TB_BATALMUAT_H (ID_BATALMUAT,KODE_PBM,STAT_GATE,JENIS,VESSEL,VOYAGE,TGL_REQ,PENGGUNA,TGL_BERANGKAT,TGL_KELUAR,STATUS) 
VALUES ('$id_bm','$pbm','$status_gate','$jenis_bm','$vessel','$voyage',SYSDATE,'$id_user',TO_DATE('$tgl_berangkat','dd/mm/yyyy'),TO_DATE('$tgl_keluar','dd/mm/yyyy'),'NOT SAVE')";

if($db->query($query))	
	{
		header('Location: '.HOME.'request.batalmuat/edit?no_req='.$id_bm);		
	}

?>