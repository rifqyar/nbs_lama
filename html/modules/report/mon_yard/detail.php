<?php
 $tl = xliteTemplate('detail_yard.htm');
 
  $db = getDB(); 
 
 $blok = $_POST['blok'];
 $id_vessel = $_POST['id_vessel'];
 $tgl_awal_gate = $_POST['tgl_awal_gate'];
 $tgl_akhir_gate = $_POST['tgl_akhir_gate'];
 $tgl_awal_place = $_POST['tgl_awal_place'];
 $tgl_akhir_place = $_POST['tgl_akhir_place'];

 if($blok != NULL){
	$query_blok = " AND ID_BLOCKING_AREA = '$blok'";
 } else {
	$query_blok = "";
 }
 
  if($id_vessel != NULL){
	$query_vessel = " AND ID_VS = '$id_vessel'";
 } else {
	$query_vessel = "";
 }
 
 if($tgl_awal_gate != NULL){
	$query_gate = " and to_date(tgl_gate_in,'dd/mm/rrrr') between to_date('$tgl_awal_gate', 'dd/mm/rrrr') and to_date('$tgl_akhir_gate', 'dd/mm/rrrr') ";
 } else {
	$query_gate = "";
 }
 
  if($tgl_awal_place != NULL){
	$query_place = " and to_date(tgl_placement,'dd/mm/rrrr') between to_date('$tgl_awal_place', 'dd/mm/rrrr') and to_date('$tgl_akhir_place', 'dd/mm/rrrr') ";
 } else {
	$query_place = "";
 }
 
 //   echo "SELECT * FROM REPORT_YARD_VESSEL_D WHERE TGL_UPDATE = (SELECT(MAX(TGL_UPDATE)) FROM REPORT_YARD_VESSEL_D)". $query_blok . $query_vessel . $query_gate . $query_place;die;
	$query = "SELECT * FROM REPORT_YARD_VESSEL_D WHERE TGL_UPDATE = (SELECT(MAX(TGL_UPDATE)) FROM REPORT_YARD_VESSEL_D)". $query_blok . $query_vessel . $query_gate . $query_place;
    $result = $db->query($query);
	$row = $result->getAll();
		 
 $tl->assign('header',$row);
 $tl->renderToScreen();
?>

