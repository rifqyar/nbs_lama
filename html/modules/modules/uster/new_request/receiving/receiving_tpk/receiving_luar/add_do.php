<?php

	$id_consignee	= $_POST["ID_CONSIGNEE"];
	$id_penumpukan	= $_POST["ID_PENUMPUKAN"];
	$nm_consignee	= $_POST["CONSIGNEE"];
	$nm_penumpukan	= $_POST["PENUMPUKAN"];
	$voyage		= $_POST["VOYAGE"];
	$no_booking	= $_POST["NO_BOOKING"];
	$keterangan	= $_POST["KETERANGAN"];
	$id_user	= $_SESSION["LOGGED_STORAGE"];
	$nm_user	= $_SESSION["NAME"];
	$id_yard	= $_SESSION["IDYARD_STORAGE"];
	$rec_dari	= $_POST["REC_DARI"];
	//$no_do		= $_POST["NO_DO"];
	//$no_bl		= $_POST["NO_BL"];
	//$no_sppb	= $_POST["NO_SPPB"];
	//$tgl_sppb	= $_POST["TGL_SPPB"];
	
	
	$db = getDB("storage");
	
	//debug($_POST);
	
	//die();
	//-------------------------------------------------------------Untuk Interface---------------------------------------------------------------------------//
	
	//debug("no booking =".$no_booking." | id emkl =".$id_emkl." | rec dari =".$rec_dari."  |no_do =".$no_do."  |no_bl =".$no_bl."  |no_sppb =".$no_sppb);die;
	//Cek nilai request existing yang paling besar
	$query_cek	= "select LPAD(MAX(TO_NUMBER(SUBSTR(NO_REQUEST,8,13)))+1,6,0) AS JUM, 
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
	
	//debug($no_req);die;
	
	//--------------------------------------------------------- DELIVERY FROM TPK -------------------------------------------------//
		
//CARI NO_REQ_DEL VERSI TPK   
	$autobp = "UREQ".$month.$year.$jum;		
//END CARI NO_REQ_DEL

	$sql 	= "INSERT INTO PETIKEMAS_CABANG.TTM_DEL_REQ
								  (NO_REQ_DEL, 
								   TGL_REQ,
								   KD_PBM,   
								   NM_AGEN,    
								   DEL_VIA_STATUS,
								   TUJUAN,     
								   KETERANGAN, 
								   NO_DO,
								   NO_BL,
								   KD_CABANG,
								   NO_SPPB, 
								   TGL_SPPB,
								   ENTRY_BY,   
								   ENTRY_DATE
								   ) 
		   						   VALUES
								   ('$autobp', 
		   							SYSDATE,  
								    '$id_consignee',
									'$nm_penumpukan',  
								    '1',   
								    'USTER', 
								    '$keterangan', 
								    '$no_do',
									'$no_bl',
								    '05',
								    '$no_sppb',
								    to_date('".$tgl_sppb."','yyyy-mm-dd'), 
								    '$nm_user',
									SYSDATE
								    )";
//	echo $sql; exit;								
										

	if($db->query($sql))
	{
		//echo "Query ke SIMOP berhasil";
		//-------------------------------END INSERT DELIVERY SP2 FROM TPK----------------------------//
	
		$query_req 	= "INSERT INTO REQUEST_RECEIVING(NO_REQUEST, 
													 KD_CONSIGNEE, 
													 KD_PENUMPUKAN_OLEH,
													 TGL_REQUEST, 
													 KETERANGAN, 
													 CETAK_KARTU, 
													 ID_USER,
													 ID_YARD,
													 RECEIVING_DARI,
													 NO_DO,
													 NO_BL,
													 NO_SPPB,
													 TGL_SPPB) 
											VALUES(	'$no_req_rec', 
													'$id_consignee',
													'$id_penumpukan',
													SYSDATE, 
													'$keterangan', 
													0, 
													$id_user,
													$id_yard,
													'$rec_dari',
													'$no_do',
													'$no_bl',
													'$no_sppb',
													to_date('".$tgl_sppb."','yyyy-mm-dd') ) ";
													
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
	}
	else
	{
		echo "Query ke SIMOP gagal";
	}
?>