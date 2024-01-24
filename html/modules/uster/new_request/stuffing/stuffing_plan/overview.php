<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('overview.htm');
	$db = getDB("storage");
	//$db2 		= getDB("ora");
	if(isset($_GET["no_req"]))
	{
		$no_req	= $_GET["no_req"];
		$query_r = "SELECT NO_REQUEST_RECEIVING
					FROM PLAN_REQUEST_STUFFING
					WHERE NO_REQUEST = '$no_req'";		
		$results 	= $db->query($query_r);
		$row_result	= $results->fetchRow();
		
		$no_req_rec	= $row_result["NO_REQUEST_RECEIVING"];
		
		$row_result2 = substr($no_req_rec,3);	
		$no_req2	= "UREC".$row_result2;
	}
	else
	{
		header('Location: '.HOME.APPID);		
	}
	
	
	$query_request	= "SELECT PLAN_REQUEST_STUFFING.NO_REQUEST, 
						PLAN_REQUEST_STUFFING.NO_REQUEST_RECEIVING, 
						PLAN_REQUEST_STUFFING.NO_REQUEST_DELIVERY,
                        EMKL.NM_PBM AS NAMA_EMKL,
                        EMKL.KD_PBM AS ID_EMKL,
                        PLAN_REQUEST_STUFFING.NO_DOKUMEN,
                        PLAN_REQUEST_STUFFING.NO_JPB,
                        PLAN_REQUEST_STUFFING.BPRP,
                        PLAN_REQUEST_STUFFING.NO_NPE,
                        PLAN_REQUEST_STUFFING.NO_PEB,
                        PLAN_REQUEST_STUFFING.KETERANGAN,
                        PLAN_REQUEST_STUFFING.KD_PENUMPUKAN_OLEH,
                        REQUEST_DELIVERY.TGL_MUAT,
                        REQUEST_DELIVERY.TGL_STACKING,
                        REQUEST_DELIVERY.TGL_BERANGKAT,
                        REQUEST_DELIVERY.KD_PELABUHAN_ASAL,
                        REQUEST_DELIVERY.KD_PELABUHAN_TUJUAN,
                        BOOK.NM_AGEN,
                        BOOK.NM_KAPAL,
                        BOOK.VOYAGE_IN,
                        BOOK.NO_BOOKING,
                        BOOK.PELABUHAN_ASAL NM_PELABUHAN_ASAL,
                        BOOK.PELABUHAN_TUJUAN NM_PELABUHAN_TUJUAN,
                        BOOK.NO_BOOKING PKK
               FROM PLAN_REQUEST_STUFFING
               JOIN V_MST_PBM EMKL
                    ON PLAN_REQUEST_STUFFING.KD_CONSIGNEE = emkl.KD_PBM
               JOIN V_PKK_CONT BOOK
                    ON  PLAN_REQUEST_STUFFING.NM_KAPAL = BOOK.NM_KAPAL 
                    AND PLAN_REQUEST_STUFFING.VOYAGE = BOOK.VOYAGE_IN,
               REQUEST_DELIVERY 
               WHERE PLAN_REQUEST_STUFFING.NO_REQUEST = '$no_req'";
			 
	// debug($query_request);die;	
	
	$query_list		= "SELECT DISTINCT PLAN_CONTAINER_STUFFING.*, PLAN_CONTAINER_STUFFING.COMMODITY COMMO
						   FROM PLAN_CONTAINER_STUFFING 
						   WHERE PLAN_CONTAINER_STUFFING.NO_REQUEST = '$no_req'";
						   
	$result_request	= $db->query($query_request);
	$row_request	= $result_request->fetchRow();
	
	$result_list	= $db->query($query_list);
	$row_list		= $result_list->getAll();
	$jum = count($row_list);
	
	//debug($row_request);
	
	$tl->assign("jum", $jum);	
	$tl->assign("row_list", $row_list);	
	$tl->assign("row_request", $row_request);	
	$tl->assign("row_ict", $row_ict_deliver);
	$tl->assign("no_req2",$no_req2);
	$tl->assign("sp2",$sp2);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
