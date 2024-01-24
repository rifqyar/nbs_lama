<?php
$ship 			= $_GET["ship"];
$call_sign 		= $_GET["call_sign"];
$vin 			= $_GET["vin"];
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB('dbint');
	
$query 			= "SELECT A.NO_CONTAINER, A.SIZE_CONT, A.TYPE_CONT, A.STATUS, A.ISO_CODE,case when A.EXTRA_TOOLS='Y' then 'OOG' else A.HEIGHT end as HEIGHT,A.CARRIER,A.IMO, A.REEFER_TEMP AS TEMP, A.HZ, COMODITY, B.ID_VSB_VOYAGE AS NO_UKK,WEIGHT, OVER_LENGTH, OVER_HEIGHT, OVER_WIDTH, UN_NUMBER, BOOKING_SL, A.POD, A.POL,TO_CHAR(TO_DATE(A. PLUG_IN,'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:MI') PLUG_IN FROM M_CYC_CONTAINER A LEFT JOIN M_VSB_VOYAGE B ON A.VESSEL_CODE=B.VESSEL_CODE AND A.VOYAGE_IN=B.VOYAGE_IN AND A.VOYAGE_OUT=B.VOYAGE_OUT WHERE A.VOYAGE_IN='$vin'AND A.E_I = 'I'AND A.CALL_SIGN = '$call_sign' AND A.NO_CONTAINER = '$no_cont' AND A.ACTIVE='Y' AND (UPPER(A.CONT_LOCATION)=UPPER('YARD') OR UPPER(A.CONT_LOCATION)=UPPER('CHASSIS'))  AND (HOLD_STATUS <> 'Y' or hold_status is null)";
					//Chassis
$result			= $db->query($query);
$row			= $result->getAll();	

//echo $query;
//print_r($row);

echo json_encode($row);


?>