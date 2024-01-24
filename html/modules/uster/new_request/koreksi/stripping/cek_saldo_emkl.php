<?php
	//$td = xliteTemplate("add.htm");
	$kd_consignee = $_POST["KD_CONSIGNEE"];
	$db = getDB("storage");
	$qcek_saldo = "SELECT CONTAINER_STRIPPING.NO_CONTAINER, REQUEST_STRIPPING.KD_CONSIGNEE, CONTAINER_STRIPPING.TGL_APPROVE, 
					CONTAINER_STRIPPING.TGL_REALISASI FROM REQUEST_STRIPPING JOIN CONTAINER_STRIPPING
					ON REQUEST_STRIPPING.NO_REQUEST = CONTAINER_STRIPPING.NO_REQUEST
					WHERE CONTAINER_STRIPPING.TGL_APPROVE IS NOT NULL
					AND CONTAINER_STRIPPING.TGL_REALISASI IS NULL
					AND REQUEST_STRIPPING.KD_CONSIGNEE = '$kd_consignee'";
	$result = $db->query($qcek_saldo);
	$row_ = $result->getAll();
	$saldo = count($row_);
	
	if($saldo > 0 ){		
		echo "masih";
		exit(); 
	}
	else{		
		echo "kosong";
		exit(); 
	}
	//$td->renderToScrenn();

?>