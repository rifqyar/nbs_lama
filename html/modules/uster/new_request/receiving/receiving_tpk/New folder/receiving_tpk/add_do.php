<?php

	$id_emkl	= $_POST["ID_EMKL"];
	$voyage		= $_POST["VOYAGE"];
	$no_booking	= $_POST["NO_BOOKING"];
	$keterangan	= $_POST["KETERANGAN"];
	$id_user	= $_SESSION["LOGGED_STORAGE"];
	$id_yard	= $_SESSION["IDYARD_STORAGE"];
	$rec_dari	= $_SESSION["REC_DARI"];
	
	$db = getDB("storage");
	/*
	//Cari no booking berdasar voyage-nya
	$query_booking ="SELECT NO_BOOKING
					  FROM VOYAGE
					  WHERE VOYAGE ='$voyage'
					  ORDER BY NO_BOOKING DESC
						";
	$result_booking = $db->query($query_booking);
	$row_booking	= $result_booking->fetchRow();
	$no_booking		= $row_booking["NO_BOOKING"];
	
	*/
	//Cek nilai request existing yang paling besar
	$query_select ="SELECT MAX(NO_REQUEST) AS NO_REQUEST,
						   TO_CHAR(SYSDATE, 'MM') AS MONTH, 
						   TO_CHAR(SYSDATE, 'YY') AS YEAR 
					FROM REQUEST_RECEIVING
					WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE)
						";
	//Jika ada ,nilai max ditambah 1
	if($db->query($query_select))
	{
		$result_select 	= $db->query($query_select);
		$row_select 	= $result_select->fetchRow();
		$no_req			= $row_select["NO_REQUEST"];
		$month			= $row_select["MONTH"];
		$year			= $row_select["YEAR"];
		$time_req		= $month.$year;
		
		$no_req = substr($no_req,6);
		$no_req = $no_req + 1;
		//print_r($no_req);die;
		//print_r($time_req);die;
		
		$query_req 	= "INSERT INTO REQUEST_RECEIVING(NO_REQUEST, 
													 ID_EMKL, 
													 TGL_REQUEST, 
													 KETERANGAN, 
													 CETAK_KARTU, 
													 ID_USER,
													 ID_YARD,
													 NO_BOOKING,
													 RECEIVING_DARI) 
											VALUES(	TO_CHAR(CONCAT ('$time_req', LPAD('$no_req',6,'0'))), 
													$id_emkl, 
													SYSDATE, 
													'$keterangan', 
													'0', 
													'$id_user',
													'$id_yard',
													'$no_booking',
													'$rec_dari') ";
		
		if($db->query($query_req))	
		{
			$query_no	= "SELECT TO_CHAR(CONCAT('$time_req', LPAD('$no_req',6,'0'))) AS NO_REQ 
						   FROM DUAL";
			$result_no	= $db->query($query_no);
			$no_req_	= $result_no->fetchRow();
			$no_req		= $no_req_["NO_REQ"];
			debug($no_req_);
			header('Location: '.HOME.APPID.'/view?no_req='.$no_req);		
		}
	}
	//JIka tidak ada, create dari awal
	else
	{
		$query_cek	= "SELECT COUNT(1) AS JUM, 
							  TO_CHAR(SYSDATE, 'MM') AS MONTH, 
							  TO_CHAR(SYSDATE, 'YY') AS YEAR 
					   FROM REQUEST_RECEIVING 
					   WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) ";
					   
		$result_cek	= $db->query($query_cek);
		$jum_		= $result_cek->fetchRow();
		$jum		= $jum_["JUM"]+1;
		$month		= $jum_["MONTH"];
		$year		= $jum_["YEAR"];
		
		$time_req		= $month.$year;
		
		
		$query_req 	= "INSERT INTO REQUEST_RECEIVING(NO_REQUEST, 
													 ID_EMKL, 
													 TGL_REQUEST, 
													 KETERANGAN, 
													 CETAK_KARTU, 
													 ID_USER,
													 ID_YARD,
													 NO_BOOKING,
													 RECEIVING_DARI) 
											VALUES(	TO_CHAR(CONCAT ('$time_req', LPAD('$no_req',6,'0'))), 
													$id_emkl, 
													SYSDATE, 
													'$keterangan', 
													'0', 
													'$id_user',
													'$id_yard',
													'$no_booking',
													'$rec_dari') ";
		
		if($db->query($query_req))	
		{
			$query_no	= "SELECT TO_CHAR(CONCAT('$time_req', LPAD('$jum',6,'0'))) AS NO_REQ 
						   FROM DUAL";
			$result_no	= $db->query($query_no);
			$no_req_	= $result_no->fetchRow();
			$no_req		= $no_req_["NO_REQ"];
			debug($no_req_);
			header('Location: '.HOME.APPID.'/view?no_req='.$no_req);		
		}
	}

?>