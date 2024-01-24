<?php

	$id_consignee	= $_POST["ID_CONSIGNEE"];
	$nm_consignee	= $_POST["CONSIGNEE"];
	$voyage		= $_POST["VOYAGE"];
	$no_booking	= $_POST["NO_BOOKING"];
	$keterangan	= $_POST["KETERANGAN"];
	$id_user	= $_SESSION["LOGGED_STORAGE"];
	$nm_user	= $_SESSION["NAME"];
	$id_yard	= $_SESSION["IDYARD_STORAGE"];
	$rec_dari	= $_POST["REC_DARI"];
	$no_ro		= $_POST["NO_RO"];
	$di		= $_POST["DI"];
	
	
	$db = getDB("storage");
	
	//-------------------------------------------------------------Untuk Interface---------------------------------------------------------------------------//
	
	//debug("no booking =".$no_booking." | id emkl =".$id_emkl." | rec dari =".$rec_dari."  |no_do =".$no_do."  |no_bl =".$no_bl."  |no_sppb =".$no_sppb);die;
	//Cek nilai request existing yang paling besar
	$query_cek	= "SELECT LPAD(NVL(MAX(SUBSTR(NO_REQUEST,8,13)),0)+1,6,0) AS JUM ,
							  TO_CHAR(SYSDATE, 'MM') AS MONTH, 
							  TO_CHAR(SYSDATE, 'YY') AS YEAR                
                      FROM REQUEST_RECEIVING 
                      WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) ";
					   
					   //select LPAD(MAX(TO_NUMBER(SUBSTR(NO_REQUEST,8,13)))+1,6,0) FROM REQUEST_RECEIVING 
                       //WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE)
				   
	$result_cek	= $db->query($query_cek);
	$jum_		= $result_cek->fetchRow();
	$jum		= $jum_["JUM"];
	$month		= $jum_["MONTH"];
	$year		= $jum_["YEAR"];
	
	$no_req_rec	= "REC".$month.$year.$jum;
	

	
		$query_req 	= "INSERT INTO REQUEST_RECEIVING(NO_REQUEST, 
													 KD_CONSIGNEE, 
													 NM_CONSIGNEE, 
													 KD_PENUMPUKAN_OLEH,
													 TGL_REQUEST, 
													 KETERANGAN, 
													 CETAK_KARTU, 
													 ID_USER,
													 ID_YARD,
													 RECEIVING_DARI,
													 NO_RO,
													 DI) 
											VALUES(	'$no_req_rec', 
													'$id_consignee',
													'$nm_consignee',
													'$id_consignee',
													SYSDATE, 
													'$keterangan', 
													0, 
													$id_user,
													1,
													'$rec_dari',
													'$no_ro',
													'$di' ) ";
													
	//	echo $query_req;exit;												
		
		if($db->query($query_req))	
		{
			$query_no	= "SELECT TO_CHAR(CONCAT('$kode_req', LPAD('$no_req',6,'0'))) AS NO_REQ 
						   FROM DUAL";
			$result_no	= $db->query($query_no);
			$no_req_	= $result_no->fetchRow();
			$no_req		= $no_req_["NO_REQ"];
			//debug($no_req_);
			header('Location: '.HOME.APPID.'/view?no_req='.$no_req_rec.'&no_req2='.$autobp);			
		}
	
?>