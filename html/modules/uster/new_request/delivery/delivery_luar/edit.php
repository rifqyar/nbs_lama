<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('edit.htm');
	
	if(isset($_GET["no_req"]))
	{
		$no_req	= $_GET["no_req"];
	}
	else
	{
		header('Location: '.HOME.APPID);		
	}
	$db = getDB("storage");
	
	$query_request	= "SELECT  REQUEST_DELIVERY.KETERANGAN, REQUEST_DELIVERY.NO_RO, REQUEST_DELIVERY.NO_REQUEST, agen.NM_PBM NM_AGEN, REQUEST_DELIVERY.KD_AGEN, TO_CHAR(REQUEST_DELIVERY.TGL_REQUEST_DELIVERY,'yyyy/mm/dd') TGL_REQUEST_DELIVERY, emkl.NM_PBM AS NAMA_EMKL, emkl.KD_PBM ID,  emkl.ALMT_PBM ALAMAT,  emkl.NO_NPWP_PBM NPWP, request_delivery.VESSEL, request_delivery.VOYAGE
        FROM request_delivery, V_MST_PBM emkl, V_MST_PBM agen
		WHERE request_delivery.KD_EMKL = emkl.KD_PBM
		AND request_delivery.KD_AGEN = agen.KD_PBM
		AND REQUEST_DELIVERY.NO_REQUEST = '$no_req'";
	$result_request	= $db->query($query_request);
	$row_request	= $result_request->fetchRow();
	
	$query_list = "SELECT MASTER_CONTAINER.*, CONTAINER_DELIVERY.*, YARD_AREA.NAMA_YARD 
FROM MASTER_CONTAINER LEFT JOIN CONTAINER_DELIVERY ON MASTER_CONTAINER.NO_CONTAINER = CONTAINER_DELIVERY.NO_CONTAINER 
LEFT JOIN YARD_AREA ON CONTAINER_DELIVERY.ID_YARD= YARD_AREA.ID
WHERE CONTAINER_DELIVERY.NO_REQUEST = '$no_req'";
	
	$result_list	= $db->query($query_list);
	$row_list	= $result_list->getAll(); 
	
	//debug($row_request);
	
	$tl->assign("row_list", $row_list);
	$tl->assign("row_request", $row_request);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
