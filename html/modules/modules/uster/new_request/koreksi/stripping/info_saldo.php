<?php
	$td = xliteTemplate("info_saldo.htm");
	$kd_consignee = $_GET["KD_CONSIGNEE"];
	$db = getDB("storage");
	$qcek_saldo = "SELECT CONTAINER_STRIPPING.NO_CONTAINER, REQUEST_STRIPPING.KD_CONSIGNEE, V_MST_PBM.NM_PBM ,CONTAINER_STRIPPING.TGL_APPROVE, 
                    CONTAINER_STRIPPING.TGL_REALISASI FROM REQUEST_STRIPPING JOIN CONTAINER_STRIPPING
                    ON REQUEST_STRIPPING.NO_REQUEST = CONTAINER_STRIPPING.NO_REQUEST
                    LEFT JOIN V_MST_PBM ON REQUEST_STRIPPING.KD_CONSIGNEE = V_MST_PBM.KD_PBM
                    WHERE CONTAINER_STRIPPING.TGL_APPROVE IS NOT NULL
                    AND CONTAINER_STRIPPING.TGL_REALISASI IS NULL
                    AND REQUEST_STRIPPING.KD_CONSIGNEE = '$kd_consignee'";
	$result = $db->query($qcek_saldo);
	$row_ = $result->getAll();
	//$row_2 = $result->fetchRow();
	$saldo = count($row_);
	
	$consignee = $row_[0]["NM_PBM"];
	
	$td->assign('consignee', $consignee);
	$td->assign('kd_consignee', $kd_consignee);
	$td->assign('saldo', $saldo);
	$td->renderToScreen();

?>