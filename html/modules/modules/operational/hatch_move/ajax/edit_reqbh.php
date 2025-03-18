<?php
$vessel = $_POST['VESSEL'];
$voyage = $_POST['VOYAGE'];
$no_ukk = $_POST['NO_UKK'];
$emkl = $_POST['EMKL'];
$tgl_tiba = $_POST['TGL_TIBA'];
$tgl_brgkt = $_POST['TGL_BRGKT'];
$jum_detail = $_POST['JUM_DETAIL'];
$counter = explode("&",$_POST['COUNTER']);
$bay = explode("&",$_POST['BAY']);
$alat = explode("&",$_POST['ALAT']);
$open = explode("&",$_POST['OPEN']);
$move_date = explode("&",$_POST['MOVE_DATE']);
$opr = explode("&",$_POST['OPR']);
$oi = explode("&",$_POST['OI']);
$jmlh = explode("&",$_POST['JMLH']);

$voy = explode("/", $voyage);
$voy_in = $voy[0];
$voy_out = $voy[1];


if($no_ukk=="")
	echo "NO";
else
{
	$db=getDB();
	
	//DELETE DULU KLO UDA ADA DI HATCH_D
	$query_d = "SELECT NO_UKK FROM HATCH_H WHERE NO_UKK = '$no_ukk'";
	$hasil   = $db->query($query_d);
	
	if ($hasil != NULL){
	$sql_d = "DELETE FROM HATCH_H WHERE NO_UKK = '$no_ukk'";
	$db->query($sql_d);
	}
	
	
	$sql = "INSERT INTO HATCH_H (NO_UKK, VESSEL, VOYAGE_IN, VOYAGE_OUT, EMKL, TGL_TIBA, TGL_BERANGKAT) VALUES (TRIM('$no_ukk'), '$vessel', '$voy_in', '$voy_out', '$emkl', to_date('	$tgl_tiba', 'DD-Mon-YY HH:MI:SS'), to_date('$tgl_brgkt', 'DD-Mon-YY HH:MI:SS'))"; 
	$db->query($sql);
	$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$no_ukk."','".$_SESSION["ID_USER"]."','HATCH_H','INSERT HEADER HATCH','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
	$db->query($sql_h);
	
	//DELETE DULU KLO UDA ADA DI HATCH_D
	$query_d = "SELECT NO_UKK FROM HATCH_D WHERE NO_UKK = '$no_ukk'";
	$hasil   = $db->query($query_d);
	
	if ($hasil != NULL){
	$sql_d = "DELETE FROM HATCH_D WHERE NO_UKK = '$no_ukk'";
	$db->query($sql_d);
	}
	
	
	for($i=0; $i<$jum_detail; $i++) {
		$sql = "INSERT INTO HATCH_D (ID, NO_UKK, BAY, KODE_ALAT, OC, MOVE_TIME, OPERATOR, OI, TOTAL_UNIT) VALUES ('".$counter[$i]."','".$no_ukk."','".$bay[$i]."','".$alat[$i]."','".$open[$i]."',to_date('".$move_date[$i]."','DD-MM-YY'),'".$opr[$i]."', '".$oi[$i]."', '".$jmlh[$i]."')";		
		$db->query($sql);		
		$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$no_ukk."~".$counter[$i]."','".$_SESSION["ID_USER"]."','HATCH_D','ENTRY DETAIL HATCH','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
		$db->query($sql_h);
	}

	echo "OK";
}
?>