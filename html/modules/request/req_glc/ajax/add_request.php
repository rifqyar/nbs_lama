<?php

$db 		= getDB();
$nm_user	= $_SESSION["NAMA_LENGKAP"];
$id_user    = $_SESSION["ID_USER"];
$no_uper	= $_POST["no_uper"];
$remark 	= $_POST["remark"]; 
$kode_kapal	= $_POST["kode_kapal"]; 
$voyage  	= $_POST["voyage"]; 
$kode_pbm	= $_POST["kode_pbm"]; 
$eta    	= $_POST["eta"];
$eta_jam	= $_POST["eta_jam"];
$eta_menit	= $_POST["eta_menit"];
$etd		= $_POST["etd"];
$etd_jam	= $_POST["etd_jam"];
$etd_menit	= $_POST["etd_menit"];
$terminal	= $_POST["terminal"];
$kade       = $_POST["kade"];

if(($no_uper==NULL)||($remark==NULL)||($kode_kapal==NULL)||($voyage==NULL)||($kode_pbm==NULL)||($eta==NULL)||($etd==NULL)||($terminal==NULL)||($kade==NULL)||($eta_jam==NULL)||($eta_menit==NULL)||($etd_jam==NULL)||($etd_menit==NULL))
{
	echo "NO";
}
else
{
//=== generate ID_REQ ====//
$prefiks = "REQ";
$thn = date("Y");
$req_uper = substr ($no_uper,-4);
$id_req = $prefiks.$thn.$req_uper;

//=== insert Request ===//
$est_datang = $eta." ".$eta_jam.":".$eta_menit.":00";
$est_berangkat = $etd." ".$etd_jam.":".$etd_menit.":00";

$insert_req = "INSERT INTO GLC_REQUEST (ID_REQ,KODE_PBM,KADE,TERMINAL,KODE_KAPAL,VOYAGE,ETA,ETD,TGL_REQ,REMARK,NO_UPER_BM) VALUES 
               ('$id_req','$kode_pbm','$kade','$terminal','$kode_kapal','$voyage',TO_DATE('$est_datang','dd/mm/yyyy HH24:MI:SS'),TO_DATE('$est_berangkat','dd/mm/yyyy HH24:MI:SS'),SYSDATE,'$remark','$no_uper')";

if($db->query($insert_req))
		{	
			$count = 1;
			$insert_history = "INSERT INTO GLC_HISTORY (ID_REQ,STATUS,TGL_UPDATE,USER_UPDATE,COUNTER) VALUES ('$id_req','PERMINTAAN',SYSDATE,'$id_user','$count')";
			$db->query($insert_history);
			$update_req_stat = "UPDATE GLC_REQUEST SET STATUS='N' WHERE ID_REQ='$id_req'";
			$db->query($update_req_stat);
			echo "OK";
		}
		else
		{ 
			echo "gagal";exit;
		}
}
 
?>