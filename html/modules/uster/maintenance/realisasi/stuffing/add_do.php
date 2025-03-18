<?php	
		
	$ID_PEMILIK		= $_POST["ID_PEMILIK"];
	$ID_EMKL		= $_POST["ID_EMKL"];
	$TYPE_S			= $_POST["TYPE_S"];
	$NO_DO			= $_POST["NO_DO"];
	$NO_BL			= $_POST["NO_BL"];
	$KETERANGAN		= $_POST["keterangan"];
	$ID_USER		= $_SESSION["LOGGED_STORAGE"];
	$id_yard		= $_SESSION["IDYARD_STORAGE"];
	
	 $TGL_BERANGKAT 		= $_POST["TGL_BERANGKAT"];
     $TGL_REQ				= $_POST["TGL_REQ"];
     $PEB        			= $_POST["NO_PEB"];
	 $NPE     				= $_POST["NO_NPE"];
	 $KD_PELABUHAN_ASAL     = $_POST["KD_PELABUHAN_ASAL"];
	 $KD_PELABUHAN_TUJUAN  	= $_POST["KD_PELABUHAN_TUJUAN"];
	 $NM_KAPAL   			= $_POST["NM_KAPAL"];
	 $VOYAGE_IN 			= $_POST["VOYAGE_IN"];
     $NO_BOOKING			= $_POST["NO_BOOKING"];
	 $KETERANGAN			= $_POST["KETERANGAN"];	 
	 $NM_USER				= $_SESSION["NAME"];
     $ID_YARD				= $_SESSION["IDYARD_STORAGE"];
	 $NM_USER				= $_SESSION["NAME"];
	 $NO_UKK				= $_POST["NO_UKK"];
	 $SHIFT_RFR				= $_POST["SHIFT_RFR"];
	 $TGL_MUAT				= $_POST["TGL_MUAT"];
	 $TGL_STACKING			= $_POST["TGL_STACKING"];
	
	$db = getDB("storage");
	
	// Entry di request delivery ----------------------------------
	
	// end entry ---------------------------------------------------------------------------------------------------------------
	$query_cek	= "SELECT LPAD(MAX(NVL(TO_NUMBER(SUBSTR(NO_REQUEST,8,13)),0))+1,6,0) AS JUM,
							  TO_CHAR(SYSDATE, 'MM') AS MONTH, 
							  TO_CHAR(SYSDATE, 'YY') AS YEAR                
                      FROM REQUEST_DELIVERY 
                      WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE)";
	
					   
		$result_cek	= $db->query($query_cek);
		$jum_		= $result_cek->fetchRow();
		$jum		= $jum_["JUM"];
		$month		= $jum_["MONTH"];
		$year		= $jum_["YEAR"];
		
		$no_req_del	= "DEL".$month.$year.$jum;	
		
		$query_req_del 	= "INSERT INTO REQUEST_DELIVERY(NO_REQUEST, 
													 ID_PEMILIK,
													 ID_EMKL,								
													 TGL_REQUEST, 
													 KETERANGAN, 
													 CETAK_KARTU, 
													 ID_USER,
													 ID_YARD,
													 PERALIHAN,
													 TGL_REQUEST_DELIVERY,
													 PERP_KE
													 ) 
											VALUES(	'$no_req_del', 
													$ID_PEMILIK,
													$ID_EMKL,
													SYSDATE, 
													'$KETERANGAN', 
													0, 
													$ID_USER,
													$id_yard,
													'STUFFING',
													SYSDATE+10,
													0
													 ) ";
		
		
		
		
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
														 CETAK_KARTU_SPPS) 
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
														 ID_EMKL) 
												VALUES(	'$no_req_s', 
														$ID_PEMILIK, 
														SYSDATE, 
														'$KETERANGAN',
														$ID_USER,
														$id_yard,
														'$no_req_rec',
														$ID_EMKL ) ";
			
			if($db->query($query_req))
			{
				header('Location: '.HOME.APPID.'/view?no_req='.$no_req_s);		
			}
		}	
	}
?>		