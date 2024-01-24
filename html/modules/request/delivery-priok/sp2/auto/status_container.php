<?php
//debug ($_POST);die;
$db 			= getDB('dbint');

$no_cont		= $_POST["NC"]; 
$call_sign		= $_POST["CC"]; 
$vin            = $_POST["VC"]; 

	$query = "SELECT A.NO_CONTAINER, A.ACTIVE, A.CONT_LOCATION, A.HOLD_STATUS
				   FROM M_CYC_CONTAINER A LEFT JOIN M_VSB_VOYAGE B ON A.VESSEL_CODE=B.VESSEL_CODE AND A.VOYAGE_IN=B.VOYAGE_IN
                    WHERE A.VOYAGE_IN='$vin'
                    AND A.E_I = 'I'
                    AND A.VESSEL_CODE = '$call_sign'
                    AND A.NO_CONTAINER LIKE '%$no_cont%' AND ROWNUM <= 1";
					
	
	
	//ACTIVE & CONT_LOCATION
	$result = $db->query($query);
	$row = $result->fetchRow();	
	
	$query2 = "SELECT COUNT(*) as CONT_NUMBER
					FROM PNOADM.CYM_ORDERLIST
					WHERE TRIM(CYM_OLIST_CONTNO) = TRIM('$no_cont')
					AND CYM_OLIST_COMPDATE IS NULL
					AND CYM_OLIST_COMPTIME IS NULL";
	$result2 = $db->query($query2);
	$row2 = $result2->fetchRow();	
	
	
	if($row[NO_CONTAINER]==NULL || $row[NO_CONTAINER]==""){
		$msg = "Data tidak ditemukan di OPUS";
		echo($msg);
	} else {
		$msg = "Lokasi Kontainer di ".$row[CONT_LOCATION] . " dan " ."Status Aktif Container = ".$row[ACTIVE] ." dan Hold Status=".$row[HOLD_STATUS];
		if($row2[CONT_NUMBER] + 0 > 0){
			$msg .= "\nKontainer sedang dalam Job List OPUS! (contoh: sedang behandle)";
		}
		echo($msg);
	}
	
?>