<?php

	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('view.htm');

	if(isset($_GET["no_req"]))
	{
		$db = getDB("storage");
		$db2 = getDB("ora");
		$no_req	 = $_GET["no_req"];
		//$no_req2 = $_GET["no_req2"];
		$no_req2	= substr($no_req,3);	
		$no_req2	= "UREC".$no_req2;
		
		$query = "SELECT NO_REQ_DEL
					FROM PETIKEMAS_CABANG.TTM_DEL_REQ
					WHERE NO_REQ_DEL ='$no_req2'";
					
		$result =$db2->query($query);
		$row_req2	= $result->fetchRow();
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
	
	if($rec_dari == "LUAR")
	{
	$query_request	= "SELECT a.NO_REQUEST AS NO_REQUEST,
							  a.KETERANGAN AS KETERANGAN,
							  a.RECEIVING_DARI AS RECEIVING_DARI,
							  a.ID_EMKL AS ID_EMKL,
							  d.NAMA AS NAMA_EMKL
					   FROM   REQUEST_RECEIVING a,
							  V_MST_PBM d
					   WHERE a.ID_EMKL = d.ID 
						AND	 a.NO_REQUEST = '$no_req'
						";
	//debug($query_request);						
	$result_request	= $db->query($query_request);
	$row_request	= $result_request->fetchRow();
	//debug($row_request);
	}
	else if($rec_dari == "TPK")
	{
	/*
	$sql = "SELECT NO_REQ_DEL FROM PETIKEMAS_CABANG.TTM_DEL_REQ WHERE NO_REQ_DEL = '$no_req2'";	
	$rowreq			= $db->query($sql);
	$row_request2	= $rowreq->fetchRow();
	*/
		
	$query_request	= "SELECT a.NO_REQUEST AS NO_REQUEST,
							  a.KETERANGAN AS KETERANGAN,
							  a.RECEIVING_DARI AS RECEIVING_DARI,
							  a.KD_CONSIGNEE AS KD_CONSIGNEE,
							  a.NO_DO AS NO_DO,
							  a.NO_BL AS NO_BL,
							  a.NO_SPPB AS NO_SPPB,
							  a.TGL_SPPB AS TGL_SPPB,
							  a.KD_PENUMPUKAN_OLEH AS KD_PENUMPUKAN_OLEH,
							  d.NM_PBM AS CONSIGNEE
							  --e.NM_PBM AS PENUMPUKAN_OLEH
					   FROM   REQUEST_RECEIVING a INNER JOIN
							  V_MST_PBM d ON a.KD_CONSIGNEE = d.KD_PBM --JOIN
							  --V_MST_PBM e ON a.KD_PENUMPUKAN_OLEH = e.KD_PBM
					   WHERE 
							 a.NO_REQUEST = '$no_req'
						";
	$result_request	= $db->query($query_request);						
	//debug($query_request);		
	
		$sql_no  		= "select AUTO_NO+1 AS AUTO_NO  from PETIKEMAS_CABANG.MST_AUTO_NO WHERE CODE='7'";
		$rs 	 		= $db2->query( $sql_no );
		$row	 		= $rs->FetchRow();
		$sp2		 	= $row['AUTO_NO'];						
		
	
	//debug($result_request);
	
	$row_request	= $result_request->fetchRow();
	//debug($row_request);
	//debug($row_request);
	}
	
	//debug($row_request);
	
	$tl->assign("row_request", $row_request);
	$tl->assign("no_req2", $no_req2);
	$tl->assign("sp2", $sp2);	
	//$tl->assign("row_request2", $row_request2);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
	
	/*
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
	
	$query_request	= "SELECT REQUEST_RECEIVING.*, 
							  emkl.NAMA AS NAMA_EMKL 
					   FROM REQUEST_RECEIVING 
					   JOIN MASTER_PBM emkl ON REQUEST_RECEIVING.ID_EMKL = emkl.ID 
					   WHERE REQUEST_RECEIVING.NO_REQUEST = '$no_req'";
								
	$result_request	= $db->query($query_request);
	$row_request	= $result_request->fetchRow();
	
	//debug($row_request);
	
	$tl->assign("row_request", $row_request);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
	*/
?>
