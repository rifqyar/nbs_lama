<?php

$db 		= getDB("storage");
//$db2 		= getDB("ora");

$no_cont	= $_POST["NO_CONT"]; 
$no_req		= $_POST["NO_REQ"];
$no_req2	= substr($no_req,3);	
$no_req2	= "UD".$no_req2;	


$query_pmb ="SELECT ptk.KD_PMB_DTL AS KD_PMB_DTL
				FROM PETIKEMAS_CABANG.TTD_CONT_EXBSPL ptk
				WHERE KD_PMB = '$no_req2' AND NO_CONTAINER = '$no_cont'";
$result_pmb_dtl = $db->query($query_pmb);
$row_pmb_dtl = $result_pmb_dtl -> FetchRow();
$kd_pmb_dtl = $row_pmb_dtl["KD_PMB_DTL"];

//echo $kd_pmb_dtl;exit;
//$no_req2 = $_POST[""];
//EXBSPL
$query_del_exbspl = "DELETE FROM PETIKEMAS_CABANG.TTD_CONT_EXBSPL WHERE KD_PMB = '$no_req2' AND NO_CONTAINER = '$no_cont' AND KD_PMB_DTL = '$kd_pmb_dtl'  ";
$query_del_peb = "DELETE FROM PETIKEMAS_CABANG.TTD_CONT_PEB WHERE KD_PMB = '$no_req2' AND KD_PMB_DTL = '$kd_pmb_dtl' ";

$query_del	= "DELETE FROM CONTAINER_DELIVERY WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";
$history        = "DELETE FROM history_container 
							WHERE NO_CONTAINER = '$no_cont' 
								AND NO_REQUEST = '$no_req' 
								AND KEGIATAN = 'REQUEST DELIVERY'";

if(($db2->query($query_del_exbspl)) && ($db2->query($query_del_peb)))
{
	$db->query($query_del);
	$db->query($history);
		echo "OK";
}
else 
{ 
	echo "GAGAL DEL TPK"; exit;
}

?>