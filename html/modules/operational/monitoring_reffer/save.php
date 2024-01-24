<?php
$no_cont	=	$_POST['no_cont'];
$no_pol		=	$_POST['no_pol'];
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
//ambil ID JOB SLIP yang terbaru sesuai sequence terakhirnya
$sql = "SELECT GET_IDJOBSLIP JOBID FROM DUAL";
$row  = $db->query($sql)->fetchRow();
$jobid = $row[JOBID];

$query="INSERT INTO TB_CONT_JOBSLIP
		(ID_JOB_SLIP, ID_VS, VESSEL, VOYAGE, NO_CONT, SIZE_, TYPE_, STATUS_, BERAT, HZ, NO_POLISI, ID_PEL_TUJ, PELABUHAN_TUJUAN, ID_PEL_ASAL, PELABUHAN_ASAL, GATE_IN) 
		VALUES ('$jobid','$id_vs','$vessel','$voyage','$no_cont','$size','$tipe','$status','$berat','$hz','$no_pol','$id_pel_tujuan','$pel_tujuan','$id_pel_asal','$pel_asal',TO_DATE('$gate_in','YYYY-MM-DD HH24:MI:SS'))";
//echo $query;die;
/*if($db->query($query))	
{
	header('Location: '.HOME.'operational.gate_in');		
}*/


//ID_BLOCK, NAMA_BLOCK, SLOT_, ROW_, TIER_, STATUS_STACK, ID_CELL, BOOKING_SL, 

//cek kategori
if($size==20) {
	if($berat>=2500 && $berat<=4900)		$kategori="L2";
	else if($berat>=5000 && $berat<=10900)	$kategori="L1";
	else if($berat>=11000 && $berat<=15900)	$kategori="M";
	else if($berat>=16000 && $berat<=20900)	$kategori="H";
	else if($berat>=21000 && $berat<=30000)	$kategori="XH";
}
else {
	if($berat>=3500 && $berat<=9900)		$kategori="L2";
	else if($berat>=10000 && $berat<=14900)	$kategori="L1";
	else if($berat>=15000 && $berat<=19900)	$kategori="M";
	else if($berat>=20000 && $berat<=24900)	$kategori="H";
	else if($berat>=25000 && $berat<=30000)	$kategori="XH";
}

/*if($db->query($query))	
{
	header('Location: '.HOME.'operational.gate_in');		
}*/

?>