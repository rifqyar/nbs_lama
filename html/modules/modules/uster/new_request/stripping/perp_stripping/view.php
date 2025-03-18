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
	
	$query_request	= "SELECT request_stripping.NO_REQUEST,  TO_DATE(container_stripping.TGL_APPROVE,'dd/mm/yyyy') TGL_APPROVE,  TO_DATE(container_stripping.TGL_APPROVE,'dd/mm/yyyy')+3 TGL_END_STRIPPING, emkl.NM_PBM AS NAMA_EMKL 
        FROM request_stripping INNER JOIN v_mst_pbm emkl ON request_stripping.KD_CONSIGNEE = emkl.KD_PBM
        INNER JOIN container_stripping ON request_stripping.NO_REQUEST = container_stripping.NO_REQUEST
		WHERE request_stripping.NO_REQUEST = '$no_req'";
	$result_request	= $db->query($query_request);
	$row_request	= $result_request->fetchRow();
        
    $detail_cont	= "/* Formatted on 9/29/2012 7:36:41 AM (QP5 v5.163.1008.3004) */
SELECT a.NO_REQUEST,
       a.NO_CONTAINER,
       'MTY' STATUS,
       b.SIZE_,
       b.TYPE_
  FROM CONTAINER_STRIPPING a, MASTER_CONTAINER b
 WHERE a.NO_CONTAINER = b.NO_CONTAINER
       AND a.NO_REQUEST = '$no_req'
       AND a.TGL_REALISASI IS NULL
       AND a.NO_CONTAINER NOT IN (SELECT c.NO_CONTAINER FROM container_stripping c, request_stripping d where c.no_request = d.no_request and d.STATUS_REQ = 'EXT PNKN' AND d.PERP_DARI = '$no_req')";
	$result_detail	= $db->query($detail_cont);
	$row_detail	= $result_detail->getAll();
        //debug($row_detail);

        $count  	= "SELECT COUNT(a.NO_CONTAINER) JUMLAH FROM CONTAINER_STRIPPING a WHERE a.NO_REQUEST = '$no_req' AND TGL_REALISASI IS NULL
       AND a.NO_CONTAINER NOT IN (SELECT c.NO_CONTAINER FROM container_stripping c, request_stripping d where c.no_request = d.no_request and d.STATUS_REQ = 'EXT PNKN' AND d.PERP_DARI = '$no_req')";
	$result_count	= $db->query($count);
	$row_count	= $result_count->fetchRow();
	
	//debug($row_request);
	
	$tl->assign("row_request", $row_request);
        $tl->assign("row_detail", $row_detail);
        $tl->assign("row_count", $row_count);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
