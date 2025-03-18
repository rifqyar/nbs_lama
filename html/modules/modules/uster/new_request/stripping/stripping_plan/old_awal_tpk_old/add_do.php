<?php	
		
	$ID_PEMILIK		= $_POST["ID_PEMILIK"];
	$ID_EMKL		= $_POST["ID_EMKL"];
	$TYPE_S			= $_POST["TYPE_S"];
	$NO_DO			= $_POST["NO_DO"];
	$NO_BL			= $_POST["NO_BL"];
	$KETERANGAN		= $_POST["keterangan"];
	$ID_USER		= $_SESSION["LOGGED_STORAGE"];
	$id_yard		= $_SESSION["IDYARD_STORAGE"];
	
	$db = getDB("storage");
	

	// BLOK untuk ENTRY DELIVERY DI TPK --------------------------------------------------------------------------------
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//------------------------------------------------------------------------------------------------------END BLOK
	
	
	// Entry di request receiving --------------------------------------------------------------------------------------
	$query_cek	= "SELECT LPAD(COUNT(1)+1,4,0) AS JUM, 
							  TO_CHAR(SYSDATE, 'MM') AS MONTH, 
							  TO_CHAR(SYSDATE, 'YY') AS YEAR 
					   FROM REQUEST_RECEIVING 
					   WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) ";
					   
		$result_cek	= $db->query($query_cek);
		$jum_		= $result_cek->fetchRow();
		$jum		= $jum_["JUM"];
		$month		= $jum_["MONTH"];
		$year		= $jum_["YEAR"];
		
		$no_req_rec	= "REC".$month.$year.$jum;
		
		$query_req 	= "INSERT INTO REQUEST_RECEIVING(NO_REQUEST, 
													 ID_EMKL, 
													 ID_PEMILIK, 
													 TGL_REQUEST, 
													 KETERANGAN, 
													 CETAK_KARTU, 
													 ID_USER,
													 ID_YARD,
													 PERALIHAN) 
											VALUES(	'$no_req_rec', 
													$ID_EMKL, 
													$ID_PEMILIK, 
													SYSDATE, 
													'$KETERANGAN', 
													'0', 
													$ID_USER,
													$id_yard,
													'STRIPPING' ) ";
	
	// end entry ---------------------------------------------------------------------------------------------------------------
		
	if($db->query($query_req))	
	{
		
		echo "$no_req_rec";
		//die();
		//Cek nilai request existing yang paling besar
		$query_select ="SELECT LPAD(COUNT(1)+1,4,0) AS NO_REQUEST,
							   TO_CHAR(SYSDATE, 'MM') AS MONTH, 
							   TO_CHAR(SYSDATE, 'YY') AS YEAR 
						FROM REQUEST_STRIPPING
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
			$no_req_s		= "STR".$month.$year.$no_req;
			
			$query_req 	= "INSERT INTO REQUEST_STRIPPING(NO_REQUEST, 
														 ID_PEMILIK, 
														 TGL_REQUEST, 
														 KETERANGAN, 
														 ID_USER,
														 ID_YARD,
														 NO_REQUEST_RECEIVING,
														 ID_EMKL,
														 TYPE_STRIPPING,
														 NO_DO,
														 NO_BL,
														 CETAK_KARTU_SPPS,
														 PERP_KE) 
												VALUES(	'$no_req_s', 
														$ID_PEMILIK, 
														SYSDATE, 
														'$KETERANGAN',
														$ID_USER,
														$id_yard,
														'$no_req_rec',
														$ID_EMKL,
														'$TYPE_S',
														'$NO_DO',
														'$NO_BL',
														0,
														0 ) ";
			
			if($db->query($query_req))	
			{
				header('Location: '.HOME.APPID.'/view?no_req='.$no_req_s);		
			}
		}	
		//JIka tidak ada, create dari awal
		else
		{	
			$query_cek	= "SELECT LPAD(COUNT(1)+1,4,0) AS JUM, 
								  TO_CHAR(SYSDATE, 'MM') AS MONTH, 
								  TO_CHAR(SYSDATE, 'YY') AS YEAR 
						   FROM REQUEST_STRIPPING 
						   WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) ";
						   
			$result_cek	= $db->query($query_cek);
			$jum_		= $result_cek->fetchRow();
			$jum		= $jum_["JUM"]+1;
			$month		= $jum_["MONTH"];
			$year		= $jum_["YEAR"];
			
			$no_req_s	= "STR".$month.$year.$jum;
			
			
			$query_req 	= "INSERT INTO REQUEST_STRIPPING(NO_REQUEST, 
														 ID_PEMILIK, 
														 TGL_REQUEST, 
														 KETERANGAN, 
														 ID_USER,
														 ID_YARD,
														 NO_REQUEST_RECEIVING,
														 ID_EMKL,
														 CETAK_KARTU_SPPS,
														 PERP_KE) 
												VALUES(	'$no_req_s', 
														$ID_PEMILIK, 
														SYSDATE, 
														'$KETERANGAN',
														$ID_USER,
														$id_yard,
														'$no_req_rec',
														$ID_EMKL,
														0,
														0 ) ";
			
			if($db->query($query_req))
			{
				header('Location: '.HOME.APPID.'/view?no_req='.$no_req_s);		
			}
		}	
	}
?>		