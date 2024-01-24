<?php

$db 			= getDB("storage");
$db2 			= getDB("ora");


//echo "dama";die;
$no_cont		= $_POST["NO_CONT"]; 
$no_req			= $_POST["NO_REQ"]; 
$no_nota		= $_POST["NO_NOTA"];
$no_truck		= $_POST["NO_TRUCK"]; 
$kode_truck		= $_POST["KD_TRUCK"]; 
$no_seal		= $_POST["NO_SEAL"]; 
$status         = $_POST["STATUS"];
$tgl_gate         = $_POST["tgl_gato"];
$masa_berlaku   = $_POST["MASA_BERLAKU"]; 
$keterangan		= $_POST["KETERANGAN"];
$kd_pmb_dtl		= $_POST["KD_PMB_DTL"]; 
$gross			= $_POST["GROSS"];
$ht_op			= $_POST["HT_OP"];
$no_req_ict		= $_POST["NO_REQ_ICT"];


	$sql_S 	= "UPDATE PETIKEMAS_CABANG.TTD_CONT_EXBSPL 
						SET TGL_GATE=TO_DATE('$tgl_gate','dd-mm-rrrr'),
						NO_GATE='001', 
						NO_SEAL='$no_seal', 
						TRUCK_NO='$no_truck',
						KETERANGAN='$keterangan'
						WHERE NO_CONTAINER='$no_cont' AND KD_PMB = '$no_req_ict'";
	
	
	if($db->query($sql_S))
	{
	
		$query_insert = "UPDATE BORDER_GATE_OUT SET TGL_IN  = TO_DATE('$tgl_gate','dd-mm-rrrr'), KETERANGAN = '$keterangan', NOPOL = '$no_truck', NO_SEAL = '$no_seal' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";
		
		if($db->query($query_insert))
		{
			$q_why = "UPDATE HISTORY_CONTAINER SET WHY = 'UPDATED_ADMIN' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req' AND KEGIATAN = 'BORDER GATE OUT'";
			if($db->query($q_why)){
				echo "OK";
			}
		}
		
	}
	else
	{
		echo "gagal query simop";exit;
	}
?>