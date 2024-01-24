<?php
    
	//debug($_POST);
	
	$yard_ori	= $_POST["ID_YARD_ORI"];
	$yard_dest	= $_POST["ID_YARD_DEST"];
	$no_surat	= $_POST["NO_SURAT"];
	$KD_EMKL	= $_POST["KD_EMKL"];
	$RO			= $_POST["RO"];
	$id_yard	= $_SESSION["IDYARD_STORAGE"];
	$KETERANGAN = $_POST["KETERANGAN"];
	
	$ID_USER		= $_SESSION["LOGGED_STORAGE"];
	$db 			= getDB("storage");
	
	//get id emkl dan id pemilik dari nomor request receiving sebelumnya
	
//	$query_get	 = "SELECT REQUEST_RECEIVING.* FROM REQUEST_RECEIVING INNER JOIN CONTAINER_RECEIVING ON REQUEST_RECEIVING.NO_REQUEST = CONTAINER_RECEIVING.NO_REQUEST WHERE ";
	
	//insert request delivery di depo asal
	$query_cek	= "SELECT LPAD(NVL(MAX(SUBSTR(NO_REQUEST,8,13)),0)+1,6,0) AS JUM , 
                   TO_CHAR(SYSDATE, 'MM') AS MONTH, 
                   TO_CHAR(SYSDATE, 'YY') AS YEAR 
                   FROM REQUEST_DELIVERY
                   WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) ";
				   
	$result_cek	= $db->query($query_cek);
	$jum_		= $result_cek->fetchRow();
	$jum		= $jum_["JUM"];
	$month		= $jum_["MONTH"];
	$year		= $jum_["YEAR"];
	
	$no_req_del	= "DEL".$month.$year.$jum;
	
	// keluar dalam waktu 10 hari
	
	$query_req 	= "INSERT INTO REQUEST_DELIVERY(NO_REQUEST, 
												 TGL_REQUEST, 
												 KETERANGAN, 
												 CETAK_KARTU, 
												 ID_USER,
												 ID_YARD,
												 PERALIHAN,
												 TGL_REQUEST_DELIVERY,
												 PERP_KE,
												 NO_SURAT
												 ) 
										VALUES(	'$no_req_del', 
												SYSDATE, 
												'$KETERANGAN', 
												0, 
												$ID_USER,
												$id_yard,
												'RELOKASI',
												SYSDATE+10,
												0,
												'$no_surat'
												 ) ";
											
	if($db->query($query_req))	
	{
		//insert request receiving di depo tujuan
		 $query_cek	= "SELECT LPAD(NVL(MAX(SUBSTR(NO_REQUEST,8,13)),0)+1,6,0) AS JUM , 
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
													 TGL_REQUEST, 
													 KETERANGAN, 
													 CETAK_KARTU, 
													 ID_USER,
													 ID_YARD,
													 PERALIHAN,
													 EX_REQ_DELIVERY) 
											VALUES(	'$no_req_rec', 
													SYSDATE, 
													'$KETERANGAN', 
													'0', 
													$ID_USER,
													$yard_dest,
													'RELOKASI',
													'$no_req_del' ) ";
		
		if($db->query($query_req))
		{
			$query_cek	= "SELECT LPAD(COUNT(1)+1,6,0) AS JUM, 
                              TO_CHAR(SYSDATE, 'MM') AS MONTH, 
                              TO_CHAR(SYSDATE, 'YY') AS YEAR 
                               FROM REQUEST_RELOKASI
                               WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) ";
					   
			$result_cek	= $db->query($query_cek);
			$jum_		= $result_cek->fetchRow();
			$jum		= $jum_["JUM"];
			$month		= $jum_["MONTH"];
			$year		= $jum_["YEAR"];
			
			$no_req_rel	= "REL".$month.$year.$jum;
			
			$query_relokasi = "INSERT INTO REQUEST_RELOKASI(NO_REQUEST, 
													 TGL_REQUEST, 
													 KD_EMKL, 
													 KETERANGAN, 													 
													 ID_USER,
													 NO_REQUEST_RECEIVING,
													 NO_REQUEST_DELIVERY,
													 YARD_ASAL,
													 YARD_TUJUAN, 
													 TIPE_RELOKASI,
													 NO_RO) 
											VALUES(	'$no_req_rel', 
													SYSDATE, 
													'$KD_EMKL', 
													'$KETERANGAN', 
													$ID_USER,
													'$no_req_rec',
													'$no_req_del',
													'$yard_ori',
													'$yard_dest',
													'EXTERNAL',
													'$RO')";
			$db->query($query_relokasi);
			
			header('Location: '.HOME.APPID.'/view?no_req='.$no_req_rel);		
		}
	}
	
	
	
?>