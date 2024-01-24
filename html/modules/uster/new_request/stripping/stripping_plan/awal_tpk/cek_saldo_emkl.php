<?php
	//$td = xliteTemplate("add.htm");
	
	// Melakukan pengecekan jumlah container emkl bersangkutan yang sedang dalam request stripping
	// Jika jumlah container lebih besar dari 3, request 
	
	$kd_consignee = $_POST["KD_CONSIGNEE"];
	$db = getDB("storage");
	$qcek_saldo = "SELECT B.NO_CONTAINER
					FROM REQUEST_STRIPPING A ,CONTAINER_STRIPPING B
					WHERE A.NO_REQUEST = B.NO_REQUEST
					AND A.KD_CONSIGNEE = '$kd_consignee' 
					AND B.NO_CONTAINER IS NOT NULL
					AND B.AKTIF = 'Y'";
	$result = $db->query($qcek_saldo);
	$row_ = $result->getAll();
	$saldo = count($row_);
	
	if($saldo > 100000 ){		
		echo "masih";
		exit(); 
	}
	else{		
		echo "kosong";
		exit(); 
	}
	//$td->renderToScrenn();

?>