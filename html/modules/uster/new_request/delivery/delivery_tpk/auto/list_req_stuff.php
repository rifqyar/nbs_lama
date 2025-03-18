<?php

$no_request		= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
//$query 		= "select a.NAMA_VESSEL, b.NO_BOOKING, b.VOYAGE from vessel a, voyage b WHERE a.KODE_VESSEL = b.KODE_VESSEL AND a.NAMA_VESSEL LIKE '%$nama%' ";

$query			= "SELECT   REQUEST_STUFFING.NO_REQUEST,
						  REQUEST_STUFFING.NM_KAPAL,
						  REQUEST_STUFFING.KD_CONSIGNEE,
						  V_MST_PBM.NM_PBM,
						  V_MST_PBM.KD_PBM,
						  REQUEST_STUFFING.NO_NPE,
						  REQUEST_STUFFING.NO_PEB,
						  V_BOOKING_STACK_TPK.NM_AGEN,
						  V_BOOKING_STACK_TPK.KD_AGEN,
						  V_BOOKING_STACK_TPK.NO_UKK,
						  REQUEST_STUFFING.NO_BOOKING,
						  REQUEST_STUFFING.VOYAGE,
						  V_BOOKING_STACK_TPK.NM_PELABUHAN_ASAL,
						  V_BOOKING_STACK_TPK.NM_PELABUHAN_TUJUAN						  
					FROM REQUEST_STUFFING
					INNER JOIN V_MST_PBM V_MST_PBM
						ON  REQUEST_STUFFING.KD_CONSIGNEE = V_MST_PBM.KD_PBM 
					INNER JOIN V_BOOKING_STACK_TPK V_BOOKING_STACK_TPK
						ON REQUEST_STUFFING.NO_BOOKING = V_BOOKING_STACK_TPK.NO_BOOKING
					WHERE NO_REQUEST LIKE '%$no_request%'";
$result		= $db->query($query);
$row		= $result->getAll();	
//echo $query;
echo json_encode($row);


?>