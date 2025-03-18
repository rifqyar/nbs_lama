
<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('overview.htm');
	$db = getDB("storage");
	if(isset($_GET["no_req"]))
	{
		$no_req	= $_GET["no_req"];
		$query_rel = "SELECT REQUEST_RELOKASI.*, YARD_AREA2.NAMA_YARD NAMA_YARD_ASAL, YARD_AREA1.NAMA_YARD NAMA_YARD_TUJUAN FROM REQUEST_RELOKASI 
                      INNER JOIN YARD_AREA YARD_AREA1 ON REQUEST_RELOKASI.YARD_TUJUAN = YARD_AREA1.ID
                      INNER JOIN YARD_AREA YARD_AREA2 ON REQUEST_RELOKASI.YARD_ASAL = YARD_AREA2.ID
                      WHERE NO_REQUEST = '$no_req'";
		$res_rel = $db->query($query_rel);
		$row_rel = $res_rel->fetchRow();
		$no_req_del = $row_rel["NO_REQUEST_DELIVERY"];
		$no_req_rec = $row_rel["NO_REQUEST_RECEIVING"];
		$tl->assign('row_rel',$row_rel);
	}
	else
	{
		header('Location: '.HOME.APPID);		
	}
	
	
	$query_request_del	= "SELECT REQUEST_DELIVERY.*, YARD_AREA.NAMA_YARD
					  	 FROM REQUEST_DELIVERY INNER JOIN YARD_AREA ON REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
					  	 WHERE REQUEST_DELIVERY.NO_REQUEST = '$no_req_del'";
								
	$result_request_del	= $db->query($query_request_del);
	$row_request_del	= $result_request_del->fetchRow();
	
	$query_request_rec	= "SELECT REQUEST_RECEIVING.*, YARD_AREA.NAMA_YARD
					  	 FROM REQUEST_RECEIVING INNER JOIN YARD_AREA ON REQUEST_RECEIVING.ID_YARD = YARD_AREA.ID
					  	 WHERE REQUEST_RECEIVING.NO_REQUEST = '$no_req_rec'";
								
	$result_request_rec	= $db->query($query_request_rec);
	$row_request_rec	= $result_request_rec->fetchRow();
	
	//debug($row_request);
	
	$tl->assign("row_request_del", $row_request_del);
	$tl->assign("row_request_rec", $row_request_rec);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
