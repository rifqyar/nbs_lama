<?php

$db 		= getDB("storage");
$db2 		= getDB("ora");

$no_cont	= $_POST["NO_CONT"]; 
$no_req		= $_POST["NO_REQ"];
$ex_bp		= $_POST["EX_BP"];
$no_req2	= substr($no_req,3);	
$no_req2	= "UD".$no_req2;

/* $no_cont = 'ALCU7016268';
$no_req = 'DEL0213000010';
$no_req2 = 'UD0213000010'; */
$bp_id = $ex_bp;
if ( substr($bp_id,0,2) != 'BP' ){
	$sqlact = "UPDATE PETIKEMAS_CABANG.TTD_CONT_EXBSPL
				SET STATUS_PP = 'T',
					KETERANGAN = 'UNDO REPO MTY USTER' 
				WHERE KD_PMB = '$bp_id' 
					AND NO_CONTAINER = '$no_cont'";
	$db2->query($sqlact);
}

$query_pmb ="SELECT ptk.KD_PMB_DTL AS KD_PMB_DTL
				FROM PETIKEMAS_CABANG.TTD_CONT_EXBSPL ptk
				WHERE KD_PMB = '$no_req2' AND NO_CONTAINER = '$no_cont'";
$result_pmb_dtl = $db2->query($query_pmb);
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
$db->query($query_del);
$db->query($history);
if($db2->query($query_del_exbspl))
{
	if($db2->query($query_del_peb)){
		echo "OK";
	}
}
else 
{ 
	echo "GAGAL DEL TPK"; exit;
}

?>