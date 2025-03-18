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
	
	$query_request	= "SELECT REQUEST_DELIVERY.NO_REQUEST, TO_CHAR(REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, emkl.NM_PBM AS NAMA_EMKL, REQUEST_DELIVERY.NO_RO 
		FROM REQUEST_DELIVERY INNER JOIN KAPAL_CABANG.MST_PBM emkl ON REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
		WHERE REQUEST_DELIVERY.NO_REQUEST = '$no_req'";
	$result_request	= $db->query($query_request);
	$row_request	= $result_request->fetchRow();
        
    $detail_cont	= "SELECT a.NO_REQUEST,
       a.NO_CONTAINER,
       a.STATUS,
       b.SIZE_,
       b.TYPE_
  FROM CONTAINER_DELIVERY a, MASTER_CONTAINER b
 WHERE a.NO_CONTAINER = b.NO_CONTAINER
       AND a.NO_REQUEST = '$no_req'
       AND AKTIF = 'Y'
       AND a.NO_CONTAINER NOT IN (SELECT c.NO_CONTAINER FROM container_delivery c, request_delivery d where c.no_request = d.no_request and d.STATUS = 'EXT' AND d.PERP_DARI = '$no_req')";
	$result_detail	= $db->query($detail_cont);
	$row_detail	= $result_detail->getAll();
        //debug($row_detail);

        $count  	= "SELECT COUNT(a.NO_CONTAINER) JUMLAH FROM CONTAINER_DELIVERY a WHERE a.NO_REQUEST = '$no_req' AND AKTIF = 'Y'";
	$result_count	= $db->query($count);
	$row_count	= $result_count->fetchRow();
	
	$get_tgl 	= "SELECT a.NO_CONTAINER, a.TGL_DELIVERY TGL_DELIVERY FROM CONTAINER_DELIVERY a WHERE a.NO_REQUEST = '$no_req' AND AKTIF = 'Y' ORDER BY a.NO_CONTAINER";
	$result_tgl	= $db->query($get_tgl);
	$row_tgl	= $result_tgl->getAll();
	
	//debug($row_request);
	$tl->assign("no_req", $no_req_old);
	$tl->assign("row_tgl", $row_tgl);
	$tl->assign("row_request", $row_request);
    $tl->assign("row_detail", $row_detail);
    $tl->assign("row_count", $row_count);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
