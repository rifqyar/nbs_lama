<?php

$db 			= getDB("storage");
$db2 			= getDB("ora");
//debug ($_POST);die;
$no_cont		= $_POST["NO_CONT"]; 
$no_req			= $_POST["NO_REQ"]; 
$no_truck		= $_POST["NO_TRUCK"]; 
$kode_truck		= $_POST["KD_TRUCK"]; 
$no_seal		= $_POST["NO_SEAL"]; 
$status         = $_POST["STATUS"];
$masa_berlaku	= $_POST["MASA_BERLAKU"]; 
$keterangan		= $_POST["KETERANGAN"]; 
$id_yard        = $_SESSION["IDYARD_STORAGE"];
$id_user		= $_SESSION["LOGGED_STORAGE"];
$nm_user		= $_SESSION["NAME"];
$kd_pmb_dtl		= $_POST["KD_PMB_DTL"];
$fdtt_no		= $_POST["FDTT_NO"];
$fdtt_op		= $_POST["FDTT_OP"];
$yoa			= $_POST["YOA"];
$selisih        = "SELECT TRUNC(TO_DATE('$masa_berlaku','DD/MM/YYYY') - SYSDATE) SELISIH FROM dual";
$result_cek		= $db->query($selisih);
$row_cek		= $result_cek->fetchRow();
$selisih_tgl	= $row_cek["SELISIH"];

//============================ SIMOP GATE OUT =============//

	$sql = "INSERT INTO PETIKEMAS_CABANG.TTD_CONT_TRUCKOUT( 
					KD_PMB_DTL, 
					TGL, 
					KD_TT_OP_ALAT, 
					NM_OPERATOR, 
					CATATAN, 
					STATUS_CONT_TALLY, 
					YOA, 
					USER_ID ) 
			VALUES (
					'$kd_pmb_dtl',
					SYSDATE,
					'$fdtt_no',
					'$fdtt_op',
					'$keterangan',
					'0',
					'$yoa',
					'$nm_user'
					)";


//============================END SIMOP GATE OUT ==================//
//echo $sql;exit;

if($db2->query($sql))
{
	$query_insert	= "INSERT INTO border_gate_in( NO_REQUEST, NO_CONTAINER, ID_USER, TGL_IN, NOPOL, STATUS, NO_SEAL, TRUCKING, KETERANGAN, ID_YARD) 
		VALUES('$no_req', '$no_cont', '$id_user', SYSDATE, '$no_truck', '$status','$no_seal','$kode_truck','$keterangan','$id_yard')";
    if($db->query($query_insert))
    {
	echo "OK";
    }
}
else
{
	echo "query simop gagal";exit;
}
?>