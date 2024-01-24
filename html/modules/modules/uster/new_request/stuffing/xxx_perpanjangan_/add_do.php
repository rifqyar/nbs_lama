<?php	

//	debug ($_POST);die;		
	//$ID_PEMILIK		= $_POST["ID_PEMILIK"];
	//$ID_PEMILIK		= $_POST["ID_PEMILIK"];
	$ID_EMKL		= $_POST["ID_EMKL"];
	$ID_PENUMPUKAN	= $_POST["ID_PENUMPUKAN"];
	$NM_PENUMPUKAN	= $_POST["PENUMPUKAN"];
	$ALMT_PENUMPUKAN = $_POST["ALMT_PENUMPUKAN"];
	$NPWP_PENUMPUKAN = $_POST["NPWP_PENUMPUKAN"];
	$NO_DOC			= $_POST["NO_DOC"];
	$NO_JPB			= $_POST["NO_JPB"];
	$NO_DO			= $_POST["NO_DO"];
	$NO_BL			= $_POST["NO_BL"];
	$NO_SPPB		= $_POST["NO_SPPB"];
	$TGL_SPPB		= $_POST["TGL_SPPB"];
	$BPRP			= $_POST["BPRP"];
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
	 $NM_USER				= $_SESSION["NAME"];
     $ID_YARD				= $_SESSION["IDYARD_STORAGE"];
	 $NM_USER				= $_SESSION["NAME"];
	 $NO_UKK				= $_POST["NO_UKK"];
	 $SHIFT_RFR				= $_POST["SHIFT_RFR"];
	 $TGL_MUAT				= $_POST["TGL_MUAT"];
	 $TGL_STACKING			= $_POST["TGL_STACKING"];
	
	if($TGL_SPPB == NULL){
		$TGL_SPPB = '';
	}
	
	$db = getDB("storage");
	
		
		
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
		$no_req_ict	= "US".$month.$year.$jum;
	
	// cek closing time kapal
	/* $sqlcek3 = "SELECT TO_DATE (TGL_JAM_CLOSE,'DD-MM-YYYY HH24:MI:SS')TGL_JAM_CLOSE  FROM PETIKEMAS_CABANG.TTH_CONT_BOOKING WHERE NO_BOOKING = '$NO_BOOKING'";
	
	$rs3 = $db->query($sqlcek3);
	$rowcek3 = $rs3 -> FetchRow();
	$datedoc = $rowcek3["TGL_JAM_CLOSE"];
	
	$sqldate = "SELECT TO_DATE (SYSDATE,'DD-MM-YYYY HH24:MI:SS')HARINI FROM DUAL";
	
	$rsdate = $db->query($sqldate);
	$rowdate = $rsdate -> FetchRow();
	$sysdate = $rowdate["HARINI"]; */
	
	/* $sqlcekclosing = " SELECT
                     (SELECT TO_DATE (TGL_JAM_CLOSE,'DD-MM-YY HH24:MI:SS')-2 TGL_JAM_CLOSE  FROM PETIKEMAS_CABANG.TTH_CONT_BOOKING WHERE NO_BOOKING = '$NO_BOOKING')-
                     (SELECT TO_DATE (SYSDATE,'DD-MM-YY HH24:MI:SS') HARINI FROM DUAL) A
                      FROM DUAL";
	$rscekclosing = $db->query($sqlcekclosing);					  
					  
	$rowdate = $rscekclosing -> FetchRow();
	$sysdate = $rowdate["A"]; */

	$sqlcek3 = "SELECT TO_CHAR (TGL_JAM_CLOSE-2,'YYYY-MM-DD HH24:MI:SS')TGL_JAM_CLOSE  FROM PETIKEMAS_CABANG.TTH_CONT_BOOKING WHERE NO_BOOKING = '$NO_BOOKING'";
	
	$rs3 = $db->query($sqlcek3);
	$rowcek3 = $rs3 -> FetchRow();
	$datedoc = $rowcek3["TGL_JAM_CLOSE"];
	
	$sqldate = "SELECT TO_CHAR (SYSDATE,'YYYY-MM-DD HH24:MI:SS')HARINI FROM DUAL";
	
	$rsdate = $db->query($sqldate);
	$rowdate = $rsdate -> FetchRow();
	$sysdate = $rowdate["HARINI"];
	
	
	//echo $datedoc; echo $sysdate; die;
	
	if ($datedoc  <= $sysdate)
	{
		
		echo "Kapal Sudah Masuk Masa Closing Time, Silakan Lakukan Booking Stack Pada Kapal Lain"; exit;
	}
	else
	{
		
	
	
	//------------------------------------------------------------SIMOP ICT----------------------------------------------------------
	//Inserrt Ke Header ICT Request Delivery ICT
	  $query_cek_del	= "select LPAD(MAX(TO_NUMBER(SUBSTR(NO_REQUEST,8,13)))+1,6,0) AS JUM, 
							  TO_CHAR(SYSDATE, 'MM') AS MONTH, 
							  TO_CHAR(SYSDATE, 'YY') AS YEAR 
					   FROM REQUEST_RECEIVING 
					   WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) ";		   
					
	$result_cek_del	= $db->query($query_cek_del);
	$jum_del_		= $result_cek_del->fetchRow();
	$jum_del		= $jum_del_["JUM"];
	$month_del		= $jum_del_["MONTH"];
    $year_del		= $jum_del_["YEAR"];
        
	$autobp_del         = "UREC".$month_del.$year_del.$jum_del;	
//END CARI NO_REQ_DEL

	$sqlict_del 	= "INSERT INTO PETIKEMAS_CABANG.TTM_DEL_REQ
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
								   ('$autobp_del', 
		   							SYSDATE,  
								    '$ID_EMKL',
									'$ID_EMKL',  
								    '1',   
								    'USTER', 
								    '$KETERANGAN', 
								    '$NO_DO',
									'$NO_BL',
								    '05',
								    '$NO_SPPB',
								    to_date('".$TGL_SPPB."','yyyy-mm-dd'), 
								    '$NM_USER',
									SYSDATE
								    )";
	
	//Insert Ke Header ICT Request Receiving ICT
	  
	  $sqlhead	= 	"INSERT INTO PETIKEMAS_CABANG.TTH_CONT_EXBSPL 
									(KD_PMB,
								 	KD_CABANG,
									NO_UKK,
									TGL_MUAT,
									TGL_STACK,
									TGL_SIMPAN,
									PELABUHAN_TUJUAN,
									KD_PELANGGAN,
									KETERANGAN,
									STATUS_CONT_EXBSPL,
									STATUS_KARTU,
									NO_PEB,
									KD_PMB_LAMA,
									USER_ID,
									NO_NPE,
									NO_BOOKING,
									SHIFT_RFR,
									FOREIGN_DISC,
									KD_PELANGGAN2) 
							 VALUES('$no_req_ict',
									'05',
									'$NO_UKK',
									to_date('$TGL_MUAT','DD/Mon/YY'),
									to_date('$TGL_STACKING','DD/Mon/YY'),
									SYSDATE,
									'$KD_PELABUHAN_TUJUAN',
									'$ID_EMKL',
									'$KETERANGAN',
									'S',
									'0',
									'$PEB',
									'$no_req_ict',
									'$NM_USER',
									'$NPE',								
									'$NO_BOOKING',
									'$SHIFT_RFR',
									'$KD_PELABUHAN_ASAL',
									'$ID_EMKL'
								)";
		
		//		echo $sqlhead;exit;	
	
	
	//------------------------------------------------------------END SIMOP ICT------------------------------------------------------		
		//query auto request receiving uster
		$no_req_rec	= "REC".$month_del.$year_del.$jum_del;
		
		$query_req_rec 	= "INSERT INTO REQUEST_RECEIVING(NO_REQUEST, 
													 KD_CONSIGNEE, 
													 KD_PENUMPUKAN_OLEH, 
													 TGL_REQUEST, 
													 KETERANGAN, 
													 CETAK_KARTU, 
													 ID_USER,
													 ID_YARD,
													 PERALIHAN,
													 RECEIVING_DARI) 
											  VALUES('$no_req_rec', 
													'$ID_EMKL', 
													'$ID_PENUMPUKAN', 
													SYSDATE, 
													'$KETERANGAN', 
													'0', 
													$ID_USER,
													$id_yard,
													'STUFFING',
													'TPK')";
													
			//die();
	//Insert ke header request stuffing 
			//Cek nilai request existing yang paling besar
			$query_select ="SELECT LPAD(NVL(MAX(SUBSTR(NO_REQUEST,8,13)),0)+1,6,0) AS JUM ,
								   TO_CHAR(SYSDATE, 'MM') AS MONTH, 
								   TO_CHAR(SYSDATE, 'YY') AS YEAR 
							FROM PLAN_REQUEST_STUFFING
							WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE)
								";
			//Jika ada ,nilai max ditambah 1
			if($db->query($sqlict_del)){ 		//eksekusi auto request delivery ICT
				if($db->query($query_req_rec)){ // eksekusi auto request receiving USTER
					if($db->query($query_select))
					{	
						$result_select 	= $db->query($query_select);
						$row_select 	= $result_select->fetchRow();
						$no_req			= $row_select["JUM"];
						$month			= $row_select["MONTH"];
						$year			= $row_select["YEAR"];
						$no_req_s		= "PTF".$month.$year.$no_req;
						
						$query_req_s 	= "INSERT INTO PLAN_REQUEST_STUFFING(NO_REQUEST, 
																	 TGL_REQUEST, 
																	 KETERANGAN, 
																	 ID_USER,
																	 ID_YARD,
																	 KD_CONSIGNEE,
																	 KD_PENUMPUKAN_OLEH,
																	 NO_JPB,
																	 NO_DOKUMEN,
																	 NO_BOOKING,
																	 BPRP,					
																	 NM_KAPAL,
																	 NO_PEB,
																	 NO_NPE,
																	 VOYAGE,
																	 NO_REQUEST_RECEIVING,
																	 NO_REQUEST_DELIVERY,
																	 STUFFING_DARI) 
															VALUES(	'$no_req_s', 
																	SYSDATE, 
																	'$KETERANGAN',
																	$ID_USER,
																	$id_yard,
																	'$ID_EMKL',
																	'$ID_PENUMPUKAN',
																	'$NO_JPB',
																	'$NO_DOC',
																	'$NO_BOOKING',
																	'$BPRP',
																	'$NM_KAPAL',
																	'$PEB',
																	'$NPE',
																	'$VOYAGE_IN',
																	'$no_req_rec',
																	'$no_req_del',
																	'TPK') 
																	";
						
						//echo $query_req_s;exit;
						//Insert ke header request delivery
				
				
						$query_req_del    = "INSERT INTO request_delivery(NO_REQUEST, REQ_AWAL, TGL_REQUEST, TGL_REQUEST_DELIVERY, 
																	  KETERANGAN, CETAK_KARTU, ID_USER, DELIVERY_KE, 
																	  PERALIHAN, ID_YARD, STATUS,PEB,
																	  NPE, KD_EMKL, KD_EMKL2, VESSEL, 
																	  VOYAGE, TGL_BERANGKAT, KD_PELABUHAN_ASAL, KD_PELABUHAN_TUJUAN, 
																	  NO_BOOKING, NO_REQ_ICT, TGL_MUAT, TGL_STACKING) 
															  VALUES ('$no_req_del','$no_req_del',SYSDATE, TO_DATE('".$TGL_REQ."','yyyy/mm/dd'),
																	  '$KETERANGAN', '0', $ID_USER, 'TPK',
																	  'STUFFING','$ID_YARD','NEW','$PEB',
																	  '$NPE','$ID_EMKL', '$ID_EMKL', '$NM_KAPAL',
																	  '$VOYAGE_IN',TO_DATE('$TGL_BERANGKAT','dd/mon/yy'),'$KD_PELABUHAN_ASAL','$KD_PELABUHAN_TUJUAN', 
																	  '$NO_BOOKING','$no_req_ict','$TGL_MUAT','$TGL_STACKING')";
																	 
																	 
						//echo $query_req_del;exit;											
																	
																	
					
						if($db->query($query_req_s))
						{
							if($db->query($query_req_del))
							{
								if($db->query($sqlhead))
								{
									header('Location: '.HOME.APPID.'/view?no_req='.$no_req_s.'&no_req2='.$autobp_del);
								}
								else
								{
									echo "Insert SIMOP gagal";exit;
								}
								
							}
							else
							{
								echo "Insert USTER Req Header delivery gagal";exit;
							}
						}
						else
						{
							echo "Insert USTER Req Header Stuffing gagal";exit;
						}
					
						
					
					
			
			
			
					}
				}
			}
	}
?>		