<?php 
$db = getDB("storage");

$no_req		= $_POST["no_req"];
$id_consignee		= $_POST["id_consignee"];
$consig_pern	= $_POST["consig_person"];
$no_do	= $_POST["no_do"];
$no_bl	= $_POST["no_bl"];
$no_sppb	= $_POST["no_sppb"];
$tgl_sppb	= $_POST["tgl_sppb"];
$type_s	= $_POST["type_s"];
$keterangan	= $_POST["keterangan"];

if ($tgl_sppb == NULL)
{
	$tgl_sppb = '';
}
$query_save = "UPDATE PLAN_REQUEST_STRIPPING SET KD_CONSIGNEE = '$id_consignee', KD_PENUMPUKAN_OLEH = '$id_consignee', NO_DO = '$no_do', NO_BL = '$no_bl', 
			   NO_SPPB = '$no_sppb', TGL_SPPB = TO_DATE('$tgl_sppb', 'yy-mm-dd'), TYPE_STRIPPING = '$type_s' , KETERANGAN = '$keterangan' WHERE NO_REQUEST = '$no_req'";
$query_save_ = "UPDATE REQUEST_STRIPPING SET KD_CONSIGNEE = '$id_consignee', KD_PENUMPUKAN_OLEH = '$id_consignee', NO_DO = '$no_do', NO_BL = '$no_bl', TYPE_STRIPPING = '$type_s' , KETERANGAN = '$keterangan' WHERE NO_REQUEST = REPLACE('$no_req','P','S')";
if($db->query($query_save)){
	$db->query($query_save_);
	echo "OK";
}
?>