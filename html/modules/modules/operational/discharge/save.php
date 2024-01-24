<?php
$no_ukk		=	$POST['no_ukk'];
$no_cont	=	$_POST['no_cont'];
$size		=	$_POST['size'];
$tipe		=	$_POST['tipe'];
$status		=	$_POST['status'];
$hz			=	$_POST['hz'];
$berat		=	$_POST['berat'];
$vessel		=	$_POST['vessel'];
$id_vessel	=	$_POST['id_vessel'];
$id_vs		=	$_POST['id_vs'];
$voyage		=	$_POST['voyage'];
$gate_in	=	$_POST['gate_in'];
$pel_asal	=	$_POST['pel_asal'];
$id_pel_asal	=	$_POST['id_pel_asal'];
$pel_tujuan	=	$_POST['pel_tujuan'];
$id_pel_tujuan	=	$_POST['id_pel_tujuan'];

$db=getDB();

$row  = $db->query($sql)->fetchRow();
//$jobid = $row[JOBID];

/*$query="INSERT INTO TB_CONT_JOBSLIP
		(ID_JOB_SLIP, ID_VS, VESSEL, VOYAGE, NO_CONT, SIZE_, TYPE_, STATUS_, BERAT, HZ, NO_POLISI, ID_PEL_TUJ, PELABUHAN_TUJUAN, ID_PEL_ASAL, PELABUHAN_ASAL, GATE_IN) 
		VALUES ('$jobid','$id_vs','$vessel','$voyage','$no_cont','$size','$tipe','$status','$berat','$hz','$no_pol','$id_pel_tujuan','$pel_tujuan','$id_pel_asal','$pel_asal',TO_DATE('$gate_in','YYYY-MM-DD HH24:MI:SS'))";*/
		
$query = "UPDATE ISWS_LIST_CONTAINER SET DISCHARGE_CONFIRM = SYSDATE 
		  WHERE NO_CONTAINER='$no_cont' AND NO_UKK='$no_ukk' AND SIZE_='$size' AND TYPE_='$tipe' AND STATUS='$status';"		
//echo $query;die;
/*if($db->query($query))	
{
	header('Location: '.HOME.'operational.gate_in');		
}*/

	

	header('Location: '.HOME.'operational.discharge');		


?>