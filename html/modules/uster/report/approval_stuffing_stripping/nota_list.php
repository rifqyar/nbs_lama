<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('nota_list.htm');
	
	$tgl_awal	= $_POST["tgl_awal"]; 
	$tgl_akhir	= $_POST["tgl_akhir"]; 
	$jenis		= $_POST["jenis"];
	$status_req	= $_POST["status_req"]; 
	if($status_req == 'PERP'){
		$status_req1 = " AND REQUEST_STUFFING.STATUS_REQ = 'PERP'";
		$status_req = " AND REQUEST_STRIPPING.STATUS_REQ = 'PERP'";
	}else if($status_req == 'NEW'){
		$status_req1 = " AND REQUEST_STUFFING.STATUS_REQ IS NULL";
		$status_req = " AND REQUEST_STRIPPING.STATUS_REQ IS NULL";
	}else{
		$status_req1 = "";
		$status_req = "";
	}
	//echo $tgl_awal;die;
	$db 	= getDB("storage");
						
	$query_list_ 	= "SELECT * FROM (
                       SELECT CONTAINER_STUFFING.NO_CONTAINER , REQUEST_STUFFING.NO_REQUEST , REQUEST_STUFFING.TGL_REQUEST, V_MST_PBM.NM_PBM, 'STUFFING' KEGIATAN, CONTAINER_STUFFING.TGL_APPROVE, LOKASI_TPK,
                       CONTAINER_STUFFING.TGL_REALISASI, PLACEMENT.TGL_PLACEMENT, MASTER_CONTAINER.SIZE_, MASTER_CONTAINER.TYPE_, REQUEST_STUFFING.NM_KAPAL, REQUEST_STUFFING.VOYAGE, REQUEST_STUFFING.NO_REQUEST_RECEIVING, CONTAINER_STUFFING.COMMODITY, container_stuffing.TYPE_STUFFING, 
                       CASE WHEN REMARK_SP2 = 'Y' THEN CONTAINER_STUFFING.END_STACK_PNKN 
                       ELSE CONTAINER_STUFFING.START_PERP_PNKN END ACTIVE_TO, RD.PIN_NUMBER
                       FROM REQUEST_STUFFING INNER JOIN 
                       CONTAINER_STUFFING ON REQUEST_STUFFING.NO_REQUEST = CONTAINER_STUFFING.NO_REQUEST
                       LEFT JOIN PLAN_CONTAINER_STUFFING ON CONTAINER_STUFFING.NO_CONTAINER = PLAN_CONTAINER_STUFFING.NO_CONTAINER AND
                       CONTAINER_STUFFING.NO_REQUEST = REPLACE(PLAN_CONTAINER_STUFFING.NO_REQUEST,'P','S')
                       INNER JOIN MASTER_CONTAINER ON CONTAINER_STUFFING.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
                       LEFT JOIN V_MST_PBM ON REQUEST_STUFFING.KD_CONSIGNEE = V_MST_PBM.KD_PBM and V_MST_PBM.KD_CABANG = '05'
                       LEFT JOIN PLACEMENT ON CONTAINER_STUFFING.NO_CONTAINER  = PLACEMENT.NO_CONTAINER AND REQUEST_STUFFING.NO_REQUEST_RECEIVING = PLACEMENT.NO_REQUEST_RECEIVING
                       LEFT JOIN BILLING_NBS.REQ_DELIVERY_D RD ON REQUEST_STUFFING.O_REQNBS = trim(RD.ID_REQ) AND CONTAINER_STUFFING.NO_CONTAINER = RD.NO_CONTAINER 
                       WHERE TRUNC(TO_DATE(REQUEST_STUFFING.TGL_REQUEST,'dd-mm-yy'))  BETWEEN TRUNC(TO_DATE('$tgl_awal','yyyy-mm-dd')) AND TRUNC(TO_DATE('$tgl_akhir','yyyy-mm-dd'))
					   AND CONTAINER_STUFFING.STATUS_REQ IS NULL AND REQUEST_STUFFING.NOTA = 'Y' $status_req1
                       UNION
                       SELECT DISTINCT CONTAINER_STRIPPING.NO_CONTAINER , REQUEST_STRIPPING.NO_REQUEST , REQUEST_STRIPPING.TGL_REQUEST,  V_MST_PBM.NM_PBM, 'STRIPPING' KEGIATAN, CONTAINER_STRIPPING.TGL_APPROVE, LOKASI_TPK,
                       CONTAINER_STRIPPING.TGL_REALISASI, PLACEMENT.TGL_PLACEMENT, MASTER_CONTAINER.SIZE_, MASTER_CONTAINER.TYPE_, VP.NM_KAPAL NM_KAPAL, VP.VOYAGE_IN VOYAGE, REQUEST_STRIPPING.NO_REQUEST_RECEIVING, CONTAINER_STRIPPING.COMMODITY, '' TYPE_STUFFING, CONTAINER_STRIPPING.TGL_SELESAI ACTIVE_TO, RD.PIN_NUMBER
                       FROM REQUEST_STRIPPING INNER JOIN 
                       CONTAINER_STRIPPING ON REQUEST_STRIPPING.NO_REQUEST = CONTAINER_STRIPPING.NO_REQUEST
                       LEFT JOIN PLAN_CONTAINER_STRIPPING ON CONTAINER_STRIPPING.NO_CONTAINER = PLAN_CONTAINER_STRIPPING.NO_CONTAINER AND
                       CONTAINER_STRIPPING.NO_REQUEST = REPLACE(PLAN_CONTAINER_STRIPPING.NO_REQUEST,'P','S')
                       INNER JOIN HISTORY_CONTAINER ON CONTAINER_STRIPPING.NO_REQUEST = HISTORY_CONTAINER.NO_REQUEST AND CONTAINER_STRIPPING.NO_CONTAINER = HISTORY_CONTAINER.NO_CONTAINER
                       AND HISTORY_CONTAINER.KEGIATAN IN ('REQUEST STRIPPING','PERPANJANGAN STRIPPING')
                       INNER JOIN MASTER_CONTAINER ON CONTAINER_STRIPPING.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
                       LEFT JOIN V_MST_PBM ON REQUEST_STRIPPING.KD_CONSIGNEE = V_MST_PBM.KD_PBM AND V_MST_PBM.KD_CABANG = '05'
                       LEFT JOIN PLACEMENT ON CONTAINER_STRIPPING.NO_CONTAINER  = PLACEMENT.NO_CONTAINER AND REQUEST_STRIPPING.NO_REQUEST_RECEIVING = PLACEMENT.NO_REQUEST_RECEIVING
                       LEFT JOIN V_PKK_CONT VP ON VP.NO_BOOKING = HISTORY_CONTAINER.NO_BOOKING
                       LEFT JOIN BILLING_NBS.REQ_DELIVERY_D RD ON REQUEST_STRIPPING.O_REQNBS = trim(RD.ID_REQ) AND CONTAINER_STRIPPING.NO_CONTAINER = RD.NO_CONTAINER 
                       WHERE TRUNC(TO_DATE(REQUEST_STRIPPING.TGL_REQUEST,'dd-mm-yy')) BETWEEN TRUNC(TO_DATE('$tgl_awal','yyyy/mm/dd')) AND TRUNC(TO_DATE('$tgl_akhir','yyyy/mm/dd'))                       
                       AND container_stripping.STATUS_REQ IS NULL AND REQUEST_STRIPPING.NOTA = 'Y' $status_req
                       ) A  
                       WHERE A.KEGIATAN LIKE '%$jenis%' ORDER BY NO_REQUEST DESC ";
    //echo $query_list_; die();
	$result_list_	= $db->query($query_list_);
	$row_list		= $result_list_->getAll(); 
    //print_r($row_list); die();
	
	$tl->assign("row_list",$row_list);
	$tl->assign("tgl_awal",$tgl_awal);
	$tl->assign("tgl_akhir",$tgl_akhir);
	$tl->assign("jenis",$jenis);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
