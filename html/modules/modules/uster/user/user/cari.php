<?php
	$td = xliteTemplate("list_emkl.htm");
	$emkl = $_GET["emkl"];
	$db = getDB("storage");
	$qcek_saldo = "SELECT KD_PBM, NM_PBM, ALMT_PBM, NO_NPWP_PBM FROM V_MST_PBM WHERE NM_PBM LIKE '%$emkl%'";
	$result = $db->query($qcek_saldo);
	$row_ = $result->getAll();
	//$consignee = $row_[0]["NM_PBM"];
	
	$td->assign('row', $row_);
	$td->renderToScreen();

?>












