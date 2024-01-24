<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('view.htm');
	
	if(isset($_GET["no_req"]))
	{
		$no_req	= $_GET["no_req"];
	}
	else
	{
		header('Location: '.HOME.APPID);		
	}
	$db = getDB("storage");
	
	$query_request	= "SELECT REQUEST_DELIVERY.KETERANGAN, REQUEST_DELIVERY.NO_RO, request_delivery.DELIVERY_KE, REQUEST_DELIVERY.NO_REQUEST, TO_CHAR(REQUEST_DELIVERY.TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM AS NAMA_EMKL, emkl.ALMT_PBM, emkl.NO_NPWP_PBM, request_delivery.VESSEL, request_delivery.VOYAGE, emkll.NM_PBM AS NM_PENUMPUKAN
        FROM REQUEST_DELIVERY INNER JOIN v_mst_pbm emkl ON REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
		inner join v_mst_pbm emkll on request_delivery.kd_agen = emkll.kd_pbm
        WHERE REQUEST_DELIVERY.NO_REQUEST = '$no_req'";
	$result_request	= $db->query($query_request);
	$row_request	= $result_request->fetchRow();
	
	//debug($row_request);
	
	$tl->assign("row_request", $row_request);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
