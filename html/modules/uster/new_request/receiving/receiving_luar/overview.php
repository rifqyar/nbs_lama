<?php

	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('overview.htm');

	if(isset($_GET["no_req"]))
	{
		$db = getDB("storage");
		$no_req	 = $_GET["no_req"];
		$no_req2 = $_GET["no_req2"];
		
		/*$query = "SELECT NO_REQ_DEL
					FROM PETIKEMAS_CABANG.TTM_DEL_REQ
					WHERE NO_REQ_DEL ='$no_req2'";
					
		$result =$db->query($query);
		$row_req2	= $result->fetchRow();*/
		//$no_req2 = $row_req2['NO_REQ_DEL'];
	}
	
	else
	{
		header('Location: '.HOME.APPID);		
	}
	//debug($no_req);
	$db = getDB("storage");
	
	$query_rec_dari ="SELECT RECEIVING_DARI
						FROM REQUEST_RECEIVING
						WHERE NO_REQUEST = '$no_req'
						";
	$result_rec_dari = $db->query($query_rec_dari);
	$row_rec_dari = $result_rec_dari->fetchRow();
	$rec_dari = $row_rec_dari["RECEIVING_DARI"];
	
	
	//$rec_dari = to_string($rec_dari);
	//echo "[$rec_dari]"; //cek apakah ada spasi di tabel
	//isset($rec_dari)
	//if($rec_dari=="LUAR")
	
	/*
	SELECT a.NO_REQUEST AS NO_REQUEST,
							  a.KETERANGAN AS KETERANGAN,
							  a.RECEIVING_DARI AS RECEIVING_DARI,
							  a.KD_CONSIGNEE AS KD_CONSIGNEE,
							  a.NO_DO AS NO_DO,
							  a.NO_BL AS NO_BL,
							  a.NO_SPPB AS NO_SPPB,
							  a.TGL_SPPB AS TGL_SPPB,
							  a.KD_PENUMPUKAN_OLEH AS KD_PENUMPUKAN_OLEH,
							  d.NM_PBM AS CONSIGNEE,
							  e.NM_PBM AS PENUMPUKAN_OLEH
					   FROM   REQUEST_RECEIVING a INNER JOIN
							  V_MST_PBM d ON a.KD_CONSIGNEE = d.KD_PBM JOIN
							  V_MST_PBM e ON a.KD_PENUMPUKAN_OLEH = e.KD_PBM
					   WHERE 
							 a.NO_REQUEST = '$no_req'
							 */
	
	$query_request	= "SELECT a.NO_REQUEST AS NO_REQUEST,
							  a.KETERANGAN AS KETERANGAN,
							  a.RECEIVING_DARI AS RECEIVING_DARI,
							  a.KD_CONSIGNEE AS KD_CONSIGNEE,
							  d.NM_PBM AS CONSIGNEE,
							  d.NO_NPWP_PBM AS NO_NPWP_PBM,
							  d.ALMT_PBM AS ALMT_PBM
					   FROM   REQUEST_RECEIVING a INNER JOIN
							  V_MST_PBM d ON a.KD_CONSIGNEE = d.KD_PBM
					   WHERE a.NO_REQUEST = '$no_req'
						";
	//debug($query_request);						
	$result_request	= $db->query($query_request);
	$row_request	= $result_request->fetchRow();
	//debug($row_request);
	
	
	//debug($row_request);
	
	$tl->assign("row_request", $row_request);
	$tl->assign("row_req2", $row_req2);
	//$tl->assign("row_request2", $row_request2);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
	
	
?>
