<?php	
	
	$ID_CONSIGNEE	= $_POST["ID_CONSIGNEE"];
	$ID_PENUMPUKAN	= $_POST["ID_PENUMPUKAN"];
	$NM_PENUMPUKAN	= $_POST["PENUMPUKAN"];
	$TYPE_S			= $_POST["TYPE_S"];
	$NO_DO			= $_POST["NO_DO"];
	$NO_BL			= $_POST["NO_BL"];
	$tgl_awal		= $_POST["TGL_AWAL"];
	$NO_SPPB		= $_POST["NO_SPPB"];
	$tgl_sppb		= $_POST["TGL_SPPB"];
	$tgl_akhir		= $_POST["TGL_AKHIR"];
	$KETERANGAN		= $_POST["keterangan"];
	$CONSIGNEE_PERSONAL		= $_POST["CONSIGNEE_PERSONAL"];
	$ID_USER		= $_SESSION["LOGGED_STORAGE"];
	$nm_user		= $_SESSION["NAME"];
	$id_yard		= $_SESSION["IDYARD_STORAGE"];
	
	$db = getDB("storage");
	
	$query_cek	= "select NVL(LPAD(MAX(TO_NUMBER(SUBSTR(NO_REQUEST,8,13)))+1,6,0),'000001') AS JUM,
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
	$autobp 	= "UREC".$month.$year.$jum;	
	
	
	// Entry Request Delivery di TPK -----------------------------------------------------------------------------------
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
								    '$ID_CONSIGNEE',
									'$NM_PENUMPUKAN',  
								    '1',   
								    'USTER', 
								    '$KETERANGAN', 
								    '$NO_DO',
									'$NO_BL',
								    '05',
								    '$NO_SPPB',
								    to_date('".$TGL_SPPB."','yyyy-mm-dd'), 
								    '$nm_user',
									SYSDATE
								    )";
	
	if($db->query($sql))
	{
		//echo "Query ke SIMOP berhasil";
		//------------------------------------------------------------------------------------------------------------------
	
	
		// Entry di request receiving --------------------------------------------------------------------------------------
	
			
			
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
														 TGL_SPPB,
														 PERALIHAN
														 ) 
												VALUES(	'$no_req_rec', 
														'$ID_CONSIGNEE',
														'$ID_PENUMPUKAN',
														SYSDATE, 
														'$KETERANGAN', 
														0, 
														$ID_USER,
														$id_yard,
														'TPK',
														'$NO_DO',
														'$NO_BL',
														'$NO_SPPB',
														to_date('".$TGL_SPPB."','yyyy-mm-dd'),
														'STRIPPING'
														 ) ";
		
		// end entry ---------------------------------------------------------------------------------------------------------------
		if($db->query($query_req))	
		{
			//debug($_POST);
			//die();
			//Cek nilai request existing yang paling besar
			
			// Entry Request Plan Stripping ----------------------------------------------------------------------------------------
			$query_cek	= "select NVL(LPAD(MAX(TO_NUMBER(SUBSTR(NO_REQUEST,8,13)))+1,6,0),'000001') AS JUM, 
									  TO_CHAR(SYSDATE, 'MM') AS MONTH, 
									  TO_CHAR(SYSDATE, 'YY') AS YEAR 
							   FROM PLAN_REQUEST_STRIPPING
							   WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) ";
							   
			
			$result_select 	= $db->query($query_cek);
			
			
			$row_select 	= $result_select->fetchRow();
			
			
			$no_req			= $row_select["JUM"];
			$month			= $row_select["MONTH"];
			$year			= $row_select["YEAR"];
			$no_req_s		= "PTR".$month.$year.$no_req;
			
			
			$query_req 	= "INSERT INTO PLAN_REQUEST_STRIPPING(NO_REQUEST, 
														 KD_CONSIGNEE, 
														 KD_PENUMPUKAN_OLEH,
														 KETERANGAN,
														 TGL_REQUEST, 
														 TGL_AWAL, 
														 TGL_AKHIR,
														 ID_USER,
														 TYPE_STRIPPING,
														 NO_DO,
														 NO_BL,
														 APPROVE,
														 NO_SPPB,
														 TGL_SPPB,
														 NO_REQUEST_RECEIVING,
														 CONSIGNEE_PERSONAL
														 ) 
												VALUES(	'$no_req_s', 
														'$ID_CONSIGNEE', 
														'$ID_PENUMPUKAN', 
														'$KETERANGAN',
														SYSDATE,
														to_date('".$tgl_awal."','yyyy-mm-dd'),
														to_date('".$tgl_akhir."','yyyy-mm-dd'),
														'$ID_USER',
														'$TYPE_S',
														'$NO_DO',
														'$NO_BL',
														'NY',
														'$NO_SPPB',
														to_date('".$tgl_sppb."','yyyy-mm-dd'),
														'$no_req_rec',
														'$CONSIGNEE_PERSONAL'
														) ";
			
			// ----------------------------------------------------------------------------------------------------------------------
			if($db->query($query_req))	
			{
				echo $no_req_s;	
				exit();
				
			}
			else
			{
				echo "not ok";	
				exit();
			}
		}
		else
		{
			echo "query insert receiving gagal";
			exit();
		}
	}	
	else
	{
		echo "query insert delivery gagal";
		exit();
	}
?>		