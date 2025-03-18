<?php

$idves  		= $_POST['idves'];
$nobc11			= $_POST['nobc11'];
$tgbc11		    = $_POST['tgbc11'];
$vesselbc11		= trim(strtoupper($_POST['vesselbc11']));
$voyagebc11		= trim(strtoupper($_POST['voyagebc11']));
$user		    = $_SESSION['NAMA_PENGGUNA'];
$confirm        = $_POST['confirm'];

$db = getDB('dbint');

//cek data existing

$query = "SELECT NOBC11 FROM M_VSB_VOYAGE WHERE ID_VSB_VOYAGE = TRIM('$idves')";
$hasil = $db->query($query)->FetchRow();

if (($hasil['NOBC11'] <> '') AND ($confirm == 'N')){

	echo $hasil['NOBC11'];
	
} else if (($hasil['NOBC11'] <> '') AND ($confirm == 'Y')){

	//$query ="UPDATE M_VSB_VOYAGE SET NOBC11 = TRIM('$nobc11'),  TGLBC11 = TO_DATE('$tgbc11','dd/mm/rrrr'), UPD_USER = '$user', UPD_DATE= SYSDATE WHERE ID_VSB_VOYAGE = TRIM('$idves') ";
	$query ="UPDATE M_VSB_VOYAGE SET NOBC11 = TRIM('$nobc11'),  TGLBC11 = '$tgbc11', VESSELBC11 = '$vesselbc11', VOYAGEBC11 = '$voyagebc11', UPD_USER = '$user', UPD_DATE= SYSDATE WHERE ID_VSB_VOYAGE = TRIM('$idves') ";
	
	if ($db->query($query)) {
	echo 'OK';
	} else {
		echo 'NOT';
	}
	
} else {

	//$query ="UPDATE M_VSB_VOYAGE SET NOBC11 = TRIM('$nobc11'),  TGLBC11 = TO_DATE('$tgbc11','dd/mm/rrrr'), UPD_USER = '$user', UPD_DATE= SYSDATE WHERE ID_VSB_VOYAGE = TRIM('$idves')";
	$query ="UPDATE M_VSB_VOYAGE SET NOBC11 = TRIM('$nobc11'),  TGLBC11 = '$tgbc11', VESSELBC11 = '$vesselbc11', VOYAGEBC11 = '$voyagebc11', UPD_USER = '$user', UPD_DATE= SYSDATE WHERE ID_VSB_VOYAGE = TRIM('$idves')";
	
	if ($db->query($query)) {
	echo 'OK';
	} else {
		echo 'NOT';
	}
	
}



?>