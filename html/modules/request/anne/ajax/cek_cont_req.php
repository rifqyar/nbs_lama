<?php
//debug ($_POST);die;
$db 			= getDB();


$size			= $_POST["SC"];
$tipe			= $_POST["TC"];
$hgc			= $_POST['HGC'];

IF($hgc=='OOG')
{
	$hgc='9.6';
	if($tipe=='FLT'){
		$hgc='8.6';
	}
}

	$query = "select m.ISO_CODE from (select ISO_CODE from master_iso_code WHERE SIZE_='$size' AND TYPE_='$tipe' AND H_ISO=$hgc) m where rownum=1";
	
	$h=$db->query($query);
	$r=$h->fetchRow();
	$msg = $r['ISO_CODE'];	
	
	//print_r($query);die;

	echo $msg;
    
?>