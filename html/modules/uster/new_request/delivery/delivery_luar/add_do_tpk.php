<?php
        $TGL_REQ	= $_POST["tgl_dev"];
        $DEV_KE 	= $_POST["dev_ke"];
		$ID_EMKL	= $_POST["ID_EMKL"];
        $ASAL       = $_POST["ASAL"];
		$TUJUAN 	= $_POST["TUJUAN"];
        $PEB        = $_POST["peb"];
		$NPE     	= $_POST["npe"];
        $NO_BOOKING	= $_POST["NO_BOOKING"];
		$KETERANGAN	= $_POST["keterangan"];
		$ID_USER	= $_SESSION["LOGGED_STORAGE"];
        $ID_YARD	= $_SESSION["IDYARD_STORAGE"];
	
	$db = getDB("storage");
	
	$query_cek	= "SELECT LPAD((COUNT(1)+1),6,0) AS JUM, TO_CHAR(SYSDATE, 'MM') AS MONTH, TO_CHAR(SYSDATE, 'YY') AS YEAR FROM request_delivery WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE)";
	$result_cek	= $db->query($query_cek);
	$jum_		= $result_cek->fetchRow();
	$jum		= $jum_["JUM"];
	$month		= $jum_["MONTH"];
	$year		= $jum_["YEAR"];
	
	
	$no_req		= 'DEL'.$month.$year.$jum;
	
//-------------------------------------------------- INSERT TO TPK's RECEIVING --------------------------------------------------------//
/*TGL_MUAT,TGL_STACK,PELABUHAN_TUJUAN (diprovide),FOREIGN_DISC (diprovide),KD_PELANGGAN (diprovide), */
	$IDKDPMB 	= 'UST-DEL'.$month.$year.$jum;
	
// cari no_booking stack ada apa enggak ??

	$sqlcek1 = "SELECT NO_BOOKING FROM PETIKEMAS_CABANG.TTH_CONT_BOOKING WHERE KD_CABANG = '05' AND NO_UKK = '$NO_UKK'";
	
	$rs1 = $db->query($sqlcek1);
	
	if ($rs1->RecordCount()<0)
	{
		echo "Belum Open Stack";
	}else 
	{
	$sqlcek2 = "SELECT SUM(JUMLAH_BOX) FROM PETIKEMAS_CABANG.TTD_KET_BOOKING WHERE NO_BOOKING = '$NO_BOOKING'";
	
	$rs2 = $db->query($sqlcek2);
	$rowcek2 = $rs2 -> FetchRow();
	$jum_cont_book = $rowcek2["JUMLAH_BOX"];
	}
	if ($jum_cont_book <= $jum_cont_req) 
	{
		echo "Container Request Melebihi Booking Stack";
	}else
	{
	$sqlcek3 = "SELECT TO_CHAR (TGL_JAM_CLOSE,'DD-MM-YYYY HH24:MI:SS')TGL_JAM_CLOSE  FROM PETIKEMAS_CABANG.TTH_CONT_BOOKING WHERE NO_BOOKING = '$NO_BOOKING'";
	
	$rs3 = $db->query($sqlcek3);
	$rowcek3 = $rs3 -> FetchRow();
	$datedoc = $rowcek3["TGL_JAM_CLOSE"];
	
	$sqldate = "SELECT TO_CHAR (SYSDATE,'DD-MM-YYYY HH24:MI:SS')SYSDATE FROM DUAL";
	
	$rsdate = $db->query($sqldate);
	$rowdate = $rsdate -> FetchRow();
	$sysdate = $rowdate["SYSDATE"];
	
	}
	if ($close_time <= $sysdate)
	{
		echo "Masa Closing Time Sudah Habis";
	}else
	{
	
		
						$sqlhead	= 	"INSERT INTO PETIKEMAS_CABANG.TTH_CONT_EXBSPL (KD_PMB,
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
									KD_PELANGGAN2,
									NO_BOOKING_SHIP) VALUES(
									'$IDKDPMB',
									'05',
									'".addslashes(strtoupper($_POST['NO_UKK']))."',
									to_date('".$_POST['TGL_MUAT']."','YYYY-MM-DD HH24:MI:SS'),
									to_date('".$_POST['TGL_STACK']."','YYYY-MM-DD HH24:MI:SS'),
									SYSDATE ,
									'".addslashes(strtoupper($_POST['PELABUHAN_TUJUAN']))."',
									'".addslashes(strtoupper($_POST['KD_PELANGGAN']))."',
									'$KETERANGAN',
									'0',
									'0',
									'$PEB',
									'".addslashes($IDKDPMB)."',
									'".addslashes($aclist["USERID"])."',
									'$NPE',								
									'".addslashes(strtoupper($_POST['NO_BOOKING']))."',
									".addslashes($_POST['SHIFT_RFR']).",
									'".addslashes(strtoupper($_POST['KD_FOREIGN_DISC']))."',
									'".addslashes(strtoupper($_POST['KD_PELANGGAN2']))."',
									'".addslashes(strtoupper($_POST['NO_BOOKING_SHIP']))."'
					)";
				if ($db->query($sqlhead))
				{
					$sqldet   = "INSERT INTO TTD_CONT_EXBSPL(KD_PMB_DTL,KD_PMB,ARE_ID,NO_CONTAINER,KD_SIZE,KD_TYPE_CONT,KD_JENIS_PEMILIK,KD_COMMODITY,GROSS,NO_SEAL,KETERANGAN,STATUS_PMB_DTL,STATUS_KARTU,USER_ID,VIA,HZ,ARE_ID2,ARE_BLOK,STATUS_PP,TEMP,CLASS) 
					  VALUES
					 (
						SEQ_TTD_CONT_EXBSPL.NEXTVAL,
						'".addslashes($IDKDPMB)."',
						'".addslashes($yp)."',
						'".addslashes(strtoupper($_POST['PBS_NOCONT'][$i]))."',
						'".addslashes(strtoupper($_POST['I_KD_SIZE'][$i]))."',
						'".addslashes(strtoupper($_POST['I_CONT_TYPE_NAME'][$i]))."',
						'".addslashes(strtoupper($_POST['I_JENIS_PEMILIK'][$i]))."',
						'".addslashes(strtoupper($_POST['I_COMMODITY'][$i]))."',
						 ".addslashes(strtoupper($_POST['PBS_GROSS'][$i])).",
						'".addslashes(strtoupper($_POST['PBS_SEAL'][$i]))."',
						'".addslashes(strtoupper($_POST['PBS_KETERANGAN'][$i]))."',
						'0',
						'0',
						'".addslashes($aclist["USERID"])."',
						'".addslashes(strtoupper($_POST['I_KD_VIA'][$i]))."',
						'".addslashes(strtoupper($_POST['HZ'][$i]))."',
						'".addslashes(strtoupper($yp2))."',
						'".addslashes(strtoupper($blk_id))."',
						'T',
						'".addslashes(strtoupper($_POST['PBS_TEMP'][$i]))."',
						'".addslashes(strtoupper($_POST['PBS_CLASS'][$i]))."'
					)";
					//echo $sqlx;exit();
					if(!$db->query($sqldet))
					{
						if($_POST['I_JENIS_PEMILIK'][$i]=='3')
						{
							$sqlx3   = "INSERT INTO TTD_CONT_PEB ( KD_PMB_DTL, NO_NPE, TGL_PEB, USER_ID, KD_CABANG, TGL_SIMPAN, KD_PMB, NO_INVOICE, NO_UKK ) VALUES
								(
									SEQ_TTD_CONT_EXBSPL.CURRVAL,
									'-',
									SYSDATE, 
									'".addslashes($aclist["USERID"])."',
									'".addslashes($KD_CABANG)."',
									SYSDATE,
									'".	addslashes($IDKDPMB)."',
									'',
									'".	addslashes($_POST['NO_UKK'])."'
								)";
							$db->query($sqlx3);
						}
					}
				}
	
	
		
	}
					
//-------------------------------------------------- END INSERT TPK's RECEIVING -------------------------------------------------------//	
	
	
	
	//echo $query_cek."<br/>";
	//echo $no_req.$jum."<br/>";
            $query_req 	= "INSERT INTO request_delivery(NO_REQUEST, ID_EMKL, REQ_AWAL, TGL_REQUEST, TGL_REQUEST_DELIVERY, KETERANGAN, CETAK_KARTU, ID_USER, DELIVERY_KE, ID_VOYAGE, PERALIHAN, ID_YARD, STATUS,PEB, NPE, NOTA) 
                                                      VALUES ('$no_req', $ID_EMKL, '$no_req',SYSDATE, TO_DATE('".$TGL_REQ."','yyyy/mm/dd'),'$KETERANGAN', '0', $ID_USER, '$DEV_KE',$NO_BOOKING,'T','$ID_YARD','NEW','$PEB','$NPE','T')";

            
              $history  = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD) 
                                                      VALUES ('$item','$no_req','DELIVERY',SYSDATE,'$id_user','$ID_YARD' )";
           // echo $history;die;
              $db->query($history);
//echo "$query_req";die;
	if($db->query($query_req))	
	{
		header('Location: '.HOME.APPID.'/edit?no_req='.$no_req);		
	}


                
					
        
?>