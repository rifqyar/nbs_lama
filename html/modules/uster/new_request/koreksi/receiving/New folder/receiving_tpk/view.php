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
	
	
	
	$query_request	= "SELECT a.NO_REQUEST AS NO_REQUEST,
							  a.KETERANGAN AS KETERANGAN,
							  b.NAMA_VESSEL AS VESSEL,
							  c.VOYAGE AS VOYAGE,
							  d.NAMA AS NAMA_EMKL
					   FROM   REQUEST_RECEIVING a,
							  MASTER_VESSEL b,
							  VOYAGE c,
							  MASTER_PBM d
					   WHERE a.ID_EMKL = d.ID 
						AND	 a.NO_REQUEST = '$no_req'
						AND  a.NO_BOOKING = c.NO_BOOKING
						AND  b.KODE_VESSEL = c.KODE_VESSEL
						";
								
	$result_request	= $db->query($query_request);
	$row_request	= $result_request->fetchRow();
	
	//debug($row_request);
	
	$tl->assign("row_request", $row_request);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
