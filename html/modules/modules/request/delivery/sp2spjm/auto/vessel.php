<?php
//utk menon-aktifkan template default
outputRaw();
$vessel		= strtoupper($_GET["term"]);

	$db = getDB('ora');
	/*$query = "select VESSEL, 
					 VOYAGE_IN AS VOY_IN,
					 VOYAGE_OUT AS VOY_OUT,
					 TPL1,
					 TPL2,
					 BC_NUMB,
					 TO_CHAR(BC_DATE,'DD-MM-YYYY') BC_DATE,
					 ID_PLP,
					 TO_CHAR(ATA,'DD-MM-YYYY') ATA,
					 TO_CHAR(ATD,'DD-MM-YYYY') ATD,
					 TRIM(NO_PLP) PLP_NUMB
			   from SPJM_APPROVAL_H 
			   WHERE FLAG_USED = '0' 
			   AND TRIM(ID_PLP) LIKE '%$plpno%'
			   ORDER BY ID_PLP DESC";*/
	
	//$query = "SELECT CAR, NM_ANGKUT AS VESSEL, NO_VOY_FLIGHT AS VOYAGE, JML_CONT FROM SPJMHDR WHERE TRIM(CAR) LIKE '%$plpno%'";
	
	$query = "select * from v_pkk_cont where NM_KAPAL like '%$vessel%' and kd_cabang='01' order by tgl_jam_tiba desc";
	

$result	= $db->query($query);
$row = $result->getAll();	

echo json_encode($row);
?>