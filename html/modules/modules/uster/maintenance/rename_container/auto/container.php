<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
$query 			= "select no_container, size_, type_, master_container.no_booking--, q.*
				from master_container
				--JOIN (SELECT A.NO_UKK, A.NM_KAPAL, A.VOYAGE_IN, A.VOYAGE_OUT, TB.NO_BOOKING, '' BP_ID, A.NM_PEMILIK, A.NM_AGEN FROM --petikemas_cabang.v_pkk_cont A
				--JOIN PETIKEMAS_CABANG.TTH_CONT_BOOKING TB ON A.NO_UKK = TB.NO_UKK
				--UNION
				--SELECT  A.NO_UKK, A.NM_KAPAL, A.VOYAGE_IN, A.VOYAGE_OUT, '' NO_BOOKING, TM.BP_ID,  A.NM_PEMILIK, A.NM_AGEN FROM --petikemas_cabang.v_pkk_cont A
				--RIGHT JOIN PETIKEMAS_CABANG.TTM_BP_CONT TM ON A.NO_UKK = TM.NO_UKK) Q
				--on REPLACE(master_container.no_booking,'VESSEL_NOTHING','BSK100000023') = q.no_booking or --REPLACE(master_container.no_booking,'VESSEL_NOTHING','BSK100000023') = q.bp_id
				where no_container = '$no_cont'";
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>