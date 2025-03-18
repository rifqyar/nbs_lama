<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('edit.htm');
	
	if(isset($_GET["no_req"]))
	{
		$no_req	= $_GET["no_req"];
		$no_req_old	= $_GET["no_req_old"];
	}
	else
	{
		header('Location: '.HOME.APPID);		
	}
	$db = getDB("storage");
	
	$query_request	= "SELECT a.NO_REQUEST, b.NM_PBM FROM REQUEST_STRIPPING a, V_MST_PBM b WHERE a.KD_CONSIGNEE = b.KD_PBM AND a.NO_REQUEST = '$no_req'";
	$result_request	= $db->query($query_request);
	$row_request	= $result_request->fetchRow();
	
	 $detail_cont	= "SELECT a.NO_REQUEST,
       a.NO_CONTAINER,
       a.STATUS,
       b.SIZE_,
       b.TYPE_
  FROM CONTAINER_DELIVERY a, MASTER_CONTAINER b
 WHERE a.NO_CONTAINER = b.NO_CONTAINER
       AND a.NO_REQUEST = '$no_req_old'
       AND AKTIF = 'Y'";
	$result_detail	= $db->query($detail_cont);
	$row_detail	= $result_detail->getAll();
        //debug($row_detail);

        $count  	= "SELECT COUNT(a.NO_CONTAINER) JUMLAH FROM CONTAINER_DELIVERY a WHERE a.NO_REQUEST = '$no_req_old' AND AKTIF = 'Y'";
	$result_count	= $db->query($count);
	$row_count	= $result_count->fetchRow();
	
	//debug($row_request);
	$tl->assign("no_req", $no_req_old);
	$tl->assign("row_request", $row_request);
    $tl->assign("row_detail", $row_detail);
    $tl->assign("row_count", $row_count);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
