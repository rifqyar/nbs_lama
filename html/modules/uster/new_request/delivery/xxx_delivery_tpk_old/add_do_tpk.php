<?php

//	debug ($_POST);die;
	 $KD_PELANGGAN  		= $_POST["KD_PELANGGAN"]; 
	 $KD_PELANGGAN2  		= $_POST["KD_PELANGGAN2"];
	 $TGL_BERANGKAT 		= $_POST["TGL_BERANGKAT"];
         $TGL_REQ			= $_POST["TGL_REQ"];
         $PEB        			= $_POST["NO_PEB"];
	 $NPE     			= $_POST["NO_NPE"];
	 $KD_PELABUHAN_ASAL             = $_POST["KD_PELABUHAN_ASAL"];
	 $KD_PELABUHAN_TUJUAN           = $_POST["KD_PELABUHAN_TUJUAN"];
	 $NM_KAPAL   			= $_POST["NM_KAPAL"];
	 $VOYAGE_IN 			= $_POST["VOYAGE_IN"];
         $NO_BOOKING			= $_POST["NO_BOOKING"];
	 $KETERANGAN			= $_POST["KETERANGAN"];
	 $ID_USER			= $_SESSION["LOGGED_STORAGE"];
	 $NM_USER			= $_SESSION["NAME"];
         $ID_YARD			= $_SESSION["IDYARD_STORAGE"];
	 $NM_USER			= $_SESSION["NAME"];
	 $NO_UKK			= $_POST["NO_UKK"];
	 $SHIFT_RFR			= $_POST["SHIFT_RFR"];
	 $TGL_MUAT			= $_POST["TGL_MUAT"];
	 $TGL_STACKING			= $_POST["TGL_STACKING"];
	 
	$db = getDB("storage");
	//SELECT LPAD(MAX(NVL(TO_NUMBER(SUBSTR(NO_REQUEST,8,13)),0))+1,6,0)
	
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
	
	$no_req	= "DEL".$month.$year.$jum;
//-------------------------------------------------- INSERT TO TPK's RECEIVING --------------------------------------------------------//
/*TGL_MUAT,TGL_STACK,PELABUHAN_TUJUAN (diprovide),FOREIGN_DISC (diprovide),KD_PELANGGAN (diprovide), */
	$IDKDPMB 	= 'UD'.$month.$year.$jum;
//echo $IDKDPMB; die;	
// cari no_booking stack ada apa enggak ??
/*
	$sqlcek1 = "SELECT NO_BOOKING FROM PETIKEMAS_CABANG.TTH_CONT_BOOKING WHERE KD_CABANG = '05' AND NO_UKK = '$NO_UKK'";
	//echo $sqlcek1; exit;
	$rs1 = $db->query($sqlcek1);
	
	if ($rs1->RecordCount()<0)
	{
		echo "Belum Open Stack";
	}else 
	{
	$sqlcek2 = "SELECT SUM(JUMLAH_BOX) FROM PETIKEMAS_CABANG.TTD_KET_BOOKING WHERE NO_BOOKING = '$NO_BOOKING'";
	//echo $sqlcek2; exit;
	
	$rs2 = $db->query($sqlcek2);
	$rowcek2 = $rs2 -> FetchRow();
	$jum_cont_book = $rowcek2["JUMLAH_BOX"];
	}
	if ($jum_cont_book <= $jum_cont_req) 
	{
		echo "Container Request Melebihi Booking Stack";
	}else
	{
	
*/	
	
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
/*	if ($rowpbm["NO_BOOKING"] == "")
	{
		echo "EMKL Tidak Booking Stack Pada Kapal Ini"; exit;
	}
	else
		{
*/
		
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
									VALUES(
									'$IDKDPMB',
									'05',
									'$NO_UKK',
									to_date('$TGL_MUAT','DD/Mon/YY'),
									to_date('$TGL_STACKING','DD/Mon/YY'),
									SYSDATE,
									'$KD_PELABUHAN_TUJUAN',
									'$KD_PELANGGAN',
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
									'$KD_PELANGGAN2'
					)";
	/*	} */
//				echo $sqlhead;exit;	
				if ($db->query($sqlhead))
				{
/*					
*/																
//-------------------------------------------------- END INSERT TPK's RECEIVING -------------------------------------------------------//	
	
		
              $query_req    = "INSERT INTO request_delivery(NO_REQUEST, REQ_AWAL, TGL_REQUEST, TGL_REQUEST_DELIVERY, KETERANGAN, CETAK_KARTU, ID_USER, DELIVERY_KE, PERALIHAN, ID_YARD, STATUS,PEB, NPE, KD_EMKL, KD_EMKL2, VESSEL, VOYAGE, TGL_BERANGKAT, KD_PELABUHAN_ASAL, KD_PELABUHAN_TUJUAN, NO_BOOKING, NO_REQ_ICT, TGL_MUAT, TGL_STACKING) 
                                                      VALUES ('$no_req','$no_req',SYSDATE, TO_DATE('".$TGL_REQ."','yyyy/mm/dd'),'$KETERANGAN', '0', $ID_USER, 'TPK',' NOTA_KIRIM','$ID_YARD','NEW','$PEB','$NPE','$KD_PELANGGAN', '$KD_PELANGGAN2', '$NM_KAPAL','$VOYAGE_IN',TO_DATE('$TGL_BERANGKAT','dd/mon/yy'),'$KD_PELABUHAN_ASAL','$KD_PELABUHAN_TUJUAN', '$NO_BOOKING','$IDKDPMB','$TGL_MUAT','$TGL_STACKING')";
													  
			  
		//echo $query_req;die;

                                }      
			  
//echo "$query_req";die;
	if($db->query($query_req))	
	{
		header('Location: '.HOME.APPID.'/edit?no_req='.$no_req.'&no_req2='.$IDKDPMB);		
	}

//}
                
					
        
?>