<?php	

//	debug ($_POST);die;		
	$ID_PEMILIK		= $_POST["ID_PEMILIK"];
	$ID_EMKL		= $_POST["ID_EMKL"];
	$NO_BOOK		= $_POST["NO_BOOK"];
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
	 $KETERANGAN			= $_POST["KETERANGAN"];	 
	 $NM_USER				= $_SESSION["NAME"];
     $ID_YARD				= $_SESSION["IDYARD_STORAGE"];
	 $NM_USER				= $_SESSION["NAME"];
	 $NO_UKK				= $_POST["NO_UKK"];
	 $SHIFT_RFR				= $_POST["SHIFT_RFR"];
	 $TGL_MUAT				= $_POST["TGL_MUAT"];
	 $TGL_STACKING			= $_POST["TGL_STACKING"];
	
	$db = getDB("storage");
	$query_cek	= "SELECT LPAD(MAX(NVL(TO_NUMBER(SUBSTR(NO_REQUEST,8,13)),0))+1,6,0) AS JUM,
							  TO_CHAR(SYSDATE, 'MM') AS MONTH, 
							  TO_CHAR(SYSDATE, 'YY') AS YEAR                
                      FROM REQUEST_RECEIVING 
                      WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE)";
	
					   
		$result_cek	= $db->query($query_cek);
		$jum_		= $result_cek->fetchRow();
		$jum		= $jum_["JUM"];
		$month		= $jum_["MONTH"];
		$year		= $jum_["YEAR"];
		
		$no_req_del	= "REC".$month.$year.$jum;

	// BLOK untuk ENTRY DELIVERY DI TPK --------------------------------------------------------------------------------
	
	$IDKDPMB 	= 'UD'.$month.$year.$jum;
	
	$sqlcek3 = "SELECT TO_CHAR (TGL_JAM_CLOSE,'DD-MM-YYYY HH24:MI:SS')TGL_JAM_CLOSE  FROM PETIKEMAS_CABANG.TTH_CONT_BOOKING WHERE NO_BOOKING = '$NO_BOOKING'";
	
	$rs3 = $db->query($sqlcek3);
	$rowcek3 = $rs3 -> FetchRow();
	$datedoc = $rowcek3["TGL_JAM_CLOSE"];
	
	$sqldate = "SELECT TO_CHAR (SYSDATE,'DD-MM-YYYY HH24:MI:SS')HARINI FROM DUAL";
	
	$rsdate = $db->query($sqldate);
	$rowdate = $rsdate -> FetchRow();
	$sysdate = $rowdate["HARINI"];
	
	
	if ($datedoc  <= $sysdate)
	{
		echo "Masa Closing Time Sudah Habis, Silakan Lakukan Booking Stack Pada Kapal Lain"; exit;
	}else
	{
	
	$sqlpbm = "SELECT * FROM PETIKEMAS_CABANG.TTD_BOOKING_PBM where no_booking = '$NO_BOOKING' AND KD_PBM = '$KD_PELANGGAN'";
	$rspbm = $db->query($sqlpbm);
	$rowpbm = $rspbm->FetchRow();
	}
	//echo $sqlpbm;exit;
	if ($rowpbm["NO_BOOKING"] == "")
	{
		echo "EMKL Tidak Booking Stack Pada Kapal Ini"; exit;
	}
	else
		{

		
			/*		$sqlhead	= 	"INSERT INTO PETIKEMAS_CABANG.TTH_CONT_EXBSPL 
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
									VALUES(
									'$IDKDPMB',
									'05',
									'$NO_UKK',
									to_date('$TGL_MUAT','DD/Mon/YY'),
									to_date('$TGL_STACKING','DD/Mon/YY'),
									SYSDATE,
									'$KD_PELABUHAN_TUJUAN',
									'$ID_EMKL',
									'$KETERANGAN',
									'0',
									'0',
									'$PEB',
									'$IDKDPMB',
									'$NM_USER',
									'$NPE',								
									'$NO_BOOKING',
									'$SHIFT_RFR',
									'$KD_PELABUHAN_ASAL',
									'$ID_PEMILIK'
					)";
		}
//				echo $sqlhead;exit;	
				if ($db->query($sqlhead))
				{	
	
	
	//------------------------------------------------------------------------------------------------------END BLOK
	*/
	
	/*// Entry di request receiving USTER --------------------------------------------------------------------------------------
	
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
					
					$query_req_rec 	= "INSERT INTO REQUEST_RECEIVING(NO_REQUEST, 
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
																'STUFFING' ) ";
				}
	
	// end entry ---------------------------------------------------------------------------------------------------------------
	
*/
		// Entry di request receiving --------------------------------------------------------------------------------------
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
														 STRIPPING_DARI,
														 APPROVE,
														 NO_SPPB,
														 TGL_SPPB,
														 NO_REQUEST_RECEIVING
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
														'TPK',
														'NY',
														'$NO_SPPB',
														to_date('".$tgl_sppb."','yyyy-mm-dd'),
														'$no_req_rec'
														) ";
		
		// end entry ---------------------------------------------------------------------------------------------------------------
			
				if($db->query($query_req_s))	
				{
					
						// BLOK ENTRY DI REQUEST RECEIVING TPK -------------------------------------------------------------------------------------
		
		
						// end entry request receiving TPK---------------------------------------------------------------------------------------------------------------
						
						
						// BLOK ENTRY DI REQUEST MUAT TPK ------------------------------------------------------------------------------------------
						
						
						// end entry request muat TPK---------------------------------------------------------------------------------------------------------------
						
						header('Location: '.HOME.APPID.'/view?no_req='.$no_req_s);
					
				}
			}
		}	
	}
?>		