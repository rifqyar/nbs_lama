<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('nota_list.htm');
	
	$tgl_awal	= $_POST["tgl_awal"]; 
	$tgl_akhir	= $_POST["tgl_akhir"]; 
	$jenis		= $_POST["jenis"];
	//echo $tgl_awal;die;
	$db 	= getDB("storage");
	
	/* $query_list_ 	= "SELECT * FROM (
					SELECT NO_NOTA, NOTA_STRIPPING.NO_REQUEST, TRUNC(TGL_NOTA) TGL_NOTA, 'STRIPPING'  AS KEGIATAN, EMKL, TO_CHAR(TOTAL_TAGIHAN, '999,999,999,999') TOTAL_TAGIHAN, STRIPPING_DARI ASAL
					FROM NOTA_STRIPPING INNER JOIN REQUEST_STRIPPING ON NOTA_STRIPPING.NO_REQUEST = REQUEST_STRIPPING.NO_REQUEST
					WHERE TRUNC(TGL_NOTA) > TO_DATE('$tgl_awal','yyyy-mm-dd') AND TRUNC(TGL_NOTA) < TO_DATE('$tgl_akhir','yyyy-mm-dd')  
					UNION
					SELECT NO_NOTA, NOTA_STUFFING.NO_REQUEST, TRUNC(TGL_NOTA) TGL_NOTA, 'STUFFING'  AS KEGIATAN, EMKL, TO_CHAR(TOTAL_TAGIHAN, '999,999,999,999') TOTAL_TAGIHAN, STUFFING_DARI ASAL
					FROM NOTA_STUFFING INNER JOIN REQUEST_STUFFING ON NOTA_STUFFING.NO_REQUEST = REQUEST_STUFFING.NO_REQUEST
					WHERE TRUNC(TGL_NOTA) > TO_DATE('$tgl_awal','yyyy-mm-dd') AND TRUNC(TGL_NOTA) < TO_DATE('$tgl_akhir','yyyy-mm-dd')) 
					WHERE KEGIATAN LIKE '%$jenis%'"; */
					
	$query_list_ 	= " SELECT * FROM (
                       SELECT CONTAINER_STUFFING.NO_CONTAINER , REQUEST_STUFFING.NO_REQUEST , REQUEST_STUFFING.TGL_REQUEST, V_MST_PBM.NM_PBM, 'STUFFING' KEGIATAN, CONTAINER_STUFFING.TGL_APPROVE, LOKASI_TPK,
                       CONTAINER_STUFFING.TGL_REALISASI, PLACEMENT.TGL_PLACEMENT, MASTER_CONTAINER.SIZE_, MASTER_CONTAINER.TYPE_, REQUEST_STUFFING.NM_KAPAL
                       FROM REQUEST_STUFFING INNER JOIN 
                       CONTAINER_STUFFING ON REQUEST_STUFFING.NO_REQUEST = CONTAINER_STUFFING.NO_REQUEST
                       LEFT JOIN PLAN_CONTAINER_STUFFING ON CONTAINER_STUFFING.NO_CONTAINER = PLAN_CONTAINER_STUFFING.NO_CONTAINER AND
                       CONTAINER_STUFFING.NO_REQUEST = REPLACE(PLAN_CONTAINER_STUFFING.NO_REQUEST,'P','S')
                       INNER JOIN MASTER_CONTAINER ON CONTAINER_STUFFING.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
                       LEFT JOIN V_MST_PBM V_MST_PBM ON REQUEST_STUFFING.KD_CONSIGNEE = V_MST_PBM.KD_PBM and V_MST_PBM.KD_CABANG = '05'
                       LEFT JOIN PLACEMENT ON CONTAINER_STUFFING.NO_CONTAINER  = PLACEMENT.NO_CONTAINER AND REQUEST_STUFFING.NO_REQUEST_RECEIVING = PLACEMENT.NO_REQUEST_RECEIVING
                       WHERE TRUNC(CONTAINER_STUFFING.TGL_APPROVE) BETWEEN TRUNC(TO_DATE('$tgl_awal','yyyy-mm-dd')) AND TRUNC(TO_DATE('$tgl_akhir','yyyy-mm-dd'))
					   AND CONTAINER_STUFFING.STATUS_REQ IS NULL AND REQUEST_STUFFING.NOTA = 'Y'
                       UNION
                       SELECT DISTINCT CONTAINER_STRIPPING.NO_CONTAINER , REQUEST_STRIPPING.NO_REQUEST , REQUEST_STRIPPING.TGL_REQUEST,  V_MST_PBM.NM_PBM, 'STRIPPING' KEGIATAN, CONTAINER_STRIPPING.TGL_APPROVE, LOKASI_TPK,
                       CONTAINER_STRIPPING.TGL_REALISASI, PLACEMENT.TGL_PLACEMENT, MASTER_CONTAINER.SIZE_, MASTER_CONTAINER.TYPE_, '' NM_KAPAL
                       FROM REQUEST_STRIPPING INNER JOIN 
                       CONTAINER_STRIPPING ON REQUEST_STRIPPING.NO_REQUEST = CONTAINER_STRIPPING.NO_REQUEST
                       LEFT JOIN PLAN_CONTAINER_STRIPPING ON CONTAINER_STRIPPING.NO_CONTAINER = PLAN_CONTAINER_STRIPPING.NO_CONTAINER AND
                       CONTAINER_STRIPPING.NO_REQUEST = REPLACE(PLAN_CONTAINER_STRIPPING.NO_REQUEST,'P','S')
                       INNER JOIN MASTER_CONTAINER ON CONTAINER_STRIPPING.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
                       LEFT JOIN V_MST_PBM ON REQUEST_STRIPPING.KD_CONSIGNEE = V_MST_PBM.KD_PBM
                       LEFT JOIN PLACEMENT ON CONTAINER_STRIPPING.NO_CONTAINER  = PLACEMENT.NO_CONTAINER AND REQUEST_STRIPPING.NO_REQUEST_RECEIVING = PLACEMENT.NO_REQUEST_RECEIVING
                       --WHERE TRUNC(CONTAINER_STRIPPING.TGL_APPROVE) BETWEEN TO_DATE('2013-04-01','yyyy-mm-dd') AND TO_DATE('2013-04-04','yyyy-mm-dd')
                       WHERE TRUNC(container_stripping.TGL_APPROVE) BETWEEN TRUNC(TO_DATE('$tgl_awal','yyyy/mm/dd')) AND TRUNC(TO_DATE('$tgl_akhir','yyyy/mm/dd'))                       
                       AND container_stripping.STATUS_REQ IS NULL AND REQUEST_STRIPPING.NOTA = 'Y'
                       ) A  
                       WHERE A.KEGIATAN LIKE '%$jenis%' ORDER BY NO_REQUEST DESC "; 
			echo $query_list_;
	$result_list_	= $db->query($query_list_);
	$row_list		= $result_list_->getAll(); 

	
	$tl->assign("row_list",$row_list);
	$tl->assign("tgl_awal",$tgl_awal);
	$tl->assign("tgl_akhir",$tgl_akhir);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
