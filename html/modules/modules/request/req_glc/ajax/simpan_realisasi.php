<?php

 $db 		= getDB();
$nm_user	= $_SESSION["NAMA_LENGKAP"];
$id_user    = $_SESSION["ID_USER"];
$id_req 	= $_POST["ID_REQ"];
$rtd		= $_POST["RTD"];
$rtd_jam	= $_POST["RTD_JAM"];
$rtd_menit	= $_POST["RTD_MENIT"];

if($rtd!=NULL)
{
	$rtd_ = $rtd." ".$rtd_jam.":".$rtd_menit.":00";

	$query_rtd = "UPDATE GLC_REQUEST SET RTD = TO_DATE('$rtd_','dd/mm/yyyy HH24:MI:SS') WHERE ID_REQ = '$id_req'";
	$db->query($query_rtd);
	
	$cek_count = "SELECT MAX(COUNTER) AS MAX_COUNTER FROM GLC_HISTORY WHERE ID_REQ='$id_req' AND STATUS LIKE '%REALISASI%'";
	
	 $result1 = $db->query($cek_count);
	 $row4 = $result1->fetchRow();			
	 $max_count = $row4['MAX_COUNTER'];
	  
	 if($max_count==NULL)
	 { 
		$count = 1;
		$update_req = "REALISASI";
		$insert_history = "INSERT INTO GLC_HISTORY (ID_REQ,STATUS,TGL_UPDATE,USER_UPDATE,COUNTER) VALUES ('$id_req','$update_req',SYSDATE,'$id_user','$count')";
	 }
	 else
	 {
		$count = $max_count+1;
		$urutan = $count-1;
		$update_req = "UBAH REALISASI ".$urutan;
		$insert_history = "INSERT INTO GLC_HISTORY (ID_REQ,STATUS,TGL_UPDATE,USER_UPDATE,COUNTER) VALUES ('$id_req','$update_req',SYSDATE,'$id_user','$count')";
	 }
	 
	 if($db->query($insert_history))
	 {
		echo "OK";
	 } 
	 else
	 {
		echo "gagal";exit;
	 }
}
else
{
 $cek_count = "SELECT MAX(COUNTER) AS MAX_COUNTER FROM GLC_HISTORY WHERE ID_REQ='$id_req' AND STATUS LIKE '%REALISASI%'";
 $result1 = $db->query($cek_count);
 $row4 = $result1->fetchRow();			
 $max_count = $row4['MAX_COUNTER'];
  
 if($max_count==NULL)
 { 
	$count = 1;
	$update_req = "REALISASI";
	$insert_history = "INSERT INTO GLC_HISTORY (ID_REQ,STATUS,TGL_UPDATE,USER_UPDATE,COUNTER) VALUES ('$id_req','$update_req',SYSDATE,'$id_user','$count')";
 }
 else
 {
    $count = $max_count+1;
	$urutan = $count-1;
	$update_req = "UBAH REALISASI ".$urutan;
	$insert_history = "INSERT INTO GLC_HISTORY (ID_REQ,STATUS,TGL_UPDATE,USER_UPDATE,COUNTER) VALUES ('$id_req','$update_req',SYSDATE,'$id_user','$count')";
 }
 
 if($db->query($insert_history))
 {
	echo "OK";
 } 
 else
 {
    echo "gagal";exit;
 }
}
?>