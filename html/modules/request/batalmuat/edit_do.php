<?php

$id_bm	        =	$_POST['id_bm'];
$nm_user        =   $_SESSION['NAMA_LENGKAP'];
$status_gate	=	$_POST['status_gate'];
$pbm		    =	$_POST['kode_pbm'];
$jenis_bm   	=	$_POST['jenis_batalmuat'];
$vessel 		=	$_POST['vessel'];
$tgl_berangkat	=	$_POST['tgl_berangkat'];
$voyage			=	$_POST['voyage'];
$tgl_keluar		=	$_POST['tgl_keluar'];

$db=getDB();
$query="UPDATE TB_BATALMUAT_H
			SET KODE_PBM = '$pbm',
				STAT_GATE = '$status_gate',
				JENIS = '$jenis_bm',
				VESSEL = '$vessel',
				VOYAGE = '$voyage',
				TGL_BERANGKAT = TO_DATE('$tgl_berangkat','dd/mm/yyyy'),
				TGL_KELUAR = TO_DATE('$tgl_keluar','dd/mm/yyyy')
			WHERE ID_BATALMUAT = '$id_bm'";

if($db->query($query))	
	{
		header('Location: '.HOME.'request.batalmuat/edit?no_req='.$id_bm);		
	}

?>