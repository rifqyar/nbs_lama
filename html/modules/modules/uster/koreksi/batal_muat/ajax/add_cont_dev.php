<?php
//debug ($_POST);die;
$db 			= getDB("storage");
$nm_user		= $_SESSION["NAME"];
$no_cont		= $_POST["NO_CONT"]; 
$no_req			= $_POST["NO_REQ"]; 
$no_req2		= $_POST["NO_REQ2"]; 
$status			= $_POST["STATUS"]; 
$hz             = $_POST["HZ"]; 
$keterangan		= $_POST["KETERANGAN"]; 
$no_seal		= $_POST["NO_SEAL"]; 
$berat			= $_POST["BERAT"]; 
$via                    = $_POST["VIA"]; 
$komoditi               = $_POST["KOMODITI"]; 
$kd_komoditi            = $_POST["KD_KOMODITI"]; 
$size			= $_POST["SIZE"];
$tipe			= $_POST["TIPE"];
$status			= $_POST["STATUS"];
$no_booking		= $_POST["NO_BOOKING"];
$no_ukk			= $_POST["NO_UKK"];
$id_user                = $_SESSION["LOGGED_STORAGE"];

/*$query_cek		= "SELECT a.NO_CONTAINER, b.LOCATION, NVL((SELECT NO_CONTAINER FROM CONTAINER_DELIVERY WHERE NO_CONTAINER = '$no_cont' AND AKTIF = 'Y'), '') as STATUS 
FROM CONTAINER_RECEIVING a, MASTER_CONTAINER b 
WHERE a.NO_CONTAINER = b.NO_CONTAINER 
AND a.NO_CONTAINER = '$no_cont'";*/
$query_cek_cont			= "SELECT NO_BOOKING, COUNTER
											FROM  MASTER_CONTAINER
											WHERE NO_CONTAINER ='$no_cont'
											";
$result_cek_cont = $db->query($query_cek_cont);
$row_cek_cont	 = $result_cek_cont->fetchRow();
$cek_book		 = $row_cek_cont["NO_BOOKING"];
$cek_counter	= $row_cek_cont["COUNTER"];

if($cek_book == NULL){	
	$q_update_book = "UPDATE MASTER_CONTAINER SET NO_BOOKING = '$no_booking' WHERE NO_CONTAINER = '$no_cont'";
	$db->query($q_update_book);
}
$q_getcounter = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
$r_getcounter = $db->query($q_getcounter);
$rw_getcounter = $r_getcounter->fetchRow();
$cur_counter = $rw_getcounter["COUNTER"];
$cur_booking = $rw_getcounter["NO_BOOKING"];

$q_update_book2 = "UPDATE MASTER_CONTAINER SET NO_BOOKING = '$no_booking' WHERE NO_CONTAINER = '$no_cont'";
$db->query($q_update_book2);					

					
$query_cek		= "SELECT b.NO_CONTAINER, b.LOCATION --, NVL((), '') as STATUS 
FROM MASTER_CONTAINER b 
WHERE b.NO_CONTAINER = '$no_cont'";
$query_cek2 = "SELECT NO_CONTAINER FROM CONTAINER_DELIVERY WHERE NO_CONTAINER = '$no_cont' AND AKTIF = 'Y'";
//echo $query_cek;die;

$result_cek		= $db->query($query_cek);
$row_cek		= $result_cek->fetchRow();

$result_cek2		= $db->query($query_cek2);
$row_cek2		= $result_cek2->fetchRow();

//$no_cont		= $row_cek["NO_CONTAINER"];
$location		= $row_cek["LOCATION"];
$req_dev        = $row_cek2["NO_CONTAINER"];
//ECHO $query_cek;
if(($no_cont <> NULL) && ($location == 'IN_YARD') && ($req_dev <> NULL))
{
          echo "SDH_REQUEST";
} else if (($no_cont <> NULL) && ($location == 'GATI') && ($req_dev == NULL))
{
	echo "BLM_PLACEMENT";	
} else if (($no_cont <> NULL) && ($location == 'IN_YARD') && ($req_dev == NULL))
{
	
	// =========================================== SIMOP ==============================================//
	$sqlcek3 = "SELECT TO_CHAR (TGL_JAM_CLOSE,'DD-MM-YYYY HH24:MI:SS')TGL_JAM_CLOSE  FROM PETIKEMAS_CABANG.TTH_CONT_BOOKING WHERE NO_BOOKING = '$NO_BOOKING'";
	
	$rs3 = $db->query($sqlcek3);
	$rowcek3 = $rs3 -> FetchRow();
	$datedoc = $rowcek3["TGL_JAM_CLOSE"];
	
	$sqldate = "SELECT TO_CHAR (SYSDATE,'DD-MM-YYYY HH24:MI:SS')HARINI FROM DUAL";
	
	$rsdate = $db->query($sqldate);
	$rowdate = $rsdate -> FetchRow();
	$sysdate = $rowdate["HARINI"];
	
	
	//if ($datedoc  <= $sysdate)
	//{
	//	echo "CLOSING_TIME"; exit;
	//}else
	//{
			
	$sqlsize  	= "SELECT KD_SIZE FROM PETIKEMAS_CABANG.MST_SIZE_CONT WHERE SIZE_CONT = '$size'"; 	
	$rssize		= $db->query($sqlsize);
	$sizetpk_	= $rssize->fetchRow();
	$sizetpk	= $sizetpk_["KD_SIZE"];
	//echo $sqlsize;exit;
	
	$sqltype	= "SELECT STY_CODE FROM PETIKEMAS_CABANG.MST_TYPE_CONT WHERE STY_NAME = '$tipe'";
	$rstype		= $db->query($sqltype);
	$typetpk_	= $rstype->fetchRow();
	$typetpk	= $typetpk_["STY_CODE"];
	//echo $typetpk;
	
	$sqljenis 	= "SELECT KD_JENIS_PEMILIK FROM PETIKEMAS_CABANG.MST_JENIS_PEMILIK WHERE NM_JENIS_PEMILIK = '$status'";
	$rsjenis	= $db->query($sqljenis);
	$jenistpk_	= $rsjenis->fetchRow();
	$jenistpk	= $jenistpk_["KD_JENIS_PEMILIK"];
	//}
	
	if($typetpk=="07"){
				$sqlm = "SELECT * FROM PETIKEMAS_CABANG.V_DATA_BOOKING_RFR WHERE NO_BOOKING='$no_booking'";					
			}else{
				$sqlm = "SELECT * FROM PETIKEMAS_CABANG.V_DATA_BOOKING WHERE NO_BOOKING='$no_booking'";
			}			
	if($jenistpk=='3'){
				$sqlm = "SELECT * FROM PETIKEMAS_CABANG.V_DATA_BOOKING_MTY WHERE NO_BOOKING='$no_booking'";
			} 	
								
	//		echo $sqlm; exit;
			$rsm = $db->query($sqlm);
			if($rsm->RecordCount()<0){
				echo "Reefer Atau Empty Tidak Ada Dalam Booking Stack"; exit;
			}
			else 
			{
				
				$rsbook  = $rsm->FetchRow();
				$blk_id = $rsbook["ARE_BLOK"];
				
				$sqlxx  = "SELECT ARE_ID,ARE_TIER,ARE_BLOK,ARE_ROW,ARE_SLOT FROM PETIKEMAS_CABANG.V_MST_YARD_EX WHERE KD_STATUS_CY='0' AND KD_CABANG='05' AND 
						   ARE_BLOK='".$rsbook["ARE_BLOK"]."' AND  ARE_ROW BETWEEN '".$rsbook["M_ROW"]."' AND '".$rsbook["S_ROW"]."' 
						   AND  ARE_SLOT BETWEEN '".$rsbook["M_SLOT"]."' AND '".$rsbook["S_SLOT"]."' AND  ARE_TIER BETWEEN '".$rsbook["M_TIER"]."' 
						   AND '".$rsbook["S_TIER"]."' ORDER BY ARE_TIER,ARE_BLOK,ARE_ROW,ARE_SLOT ASC";
						   
					//echo $sqlxx; exit;
						   
					if($rsx = $db->selectLimit( $sqlxx,0,1 ))
					{
						
					$rscon  = $rsx->FetchRow();
					$yp     = $rscon["ARE_ID"];
					$slott  = $rscon["ARE_SLOT"] + 1;
					
					if($sizetpk !='1')
						{
							$sql2 = "SELECT ARE_ID FROM PETIKEMAS_CABANG.V_MST_YARD_EX WHERE ARE_TIER='".$rscon["ARE_TIER"]."' 
							AND ARE_BLOK='".$rscon["ARE_BLOK"]."' AND ARE_ROW='".$rscon["ARE_ROW"]."' AND ARE_SLOT='".$slott."' AND KD_CABANG='05'";
							if( $rss2 = $db->selectLimit( $sql2,0,1 ) )
							{
							$rscon2   = $rss2->FetchRow();
							$yp2      = $rscon2["ARE_ID"];
							}
						}
					
					
					$sqlxxx = "UPDATE PETIKEMAS_CABANG.MST_YARD_CONT_LAPANGAN_EX SET KD_STATUS_CY='1' WHERE ARE_ID='".$yp."'";
					$db->startTransaction();
					$xxxxxx = $db->query($sqlxxx);
					$db->endTransaction();
					
					$sqlxxzz = "UPDATE PETIKEMAS_CABANG.MST_YARD_CONT_LAPANGAN_EX SET KD_STATUS_CY='1' WHERE ARE_ID='".$yp2."'";
					$db->startTransaction();
					$xxxxzz = $db->query($sqlxxzz);
					$db->endTransaction();	
					
					if($yp==''){
						$yp = 'XXXX';
					}
					
				}
			}

//utang are_id,are_id2,temp,class			
			$sqldet   = "INSERT INTO PETIKEMAS_CABANG.TTD_CONT_EXBSPL(KD_PMB_DTL,KD_PMB,NO_CONTAINER,KD_SIZE,KD_TYPE_CONT,KD_JENIS_PEMILIK,KD_COMMODITY,GROSS,NO_SEAL,KETERANGAN,STATUS_PMB_DTL,STATUS_KARTU,USER_ID,VIA,HZ,STATUS_PP,ARE_ID,ARE_ID2) 
					  VALUES
					 (
						PETIKEMAS_CABANG.SEQ_TTD_CONT_EXBSPL.NEXTVAL,
						'$no_req2',						
						'$no_cont',
						'$sizetpk',
						'$typetpk',
						'$jenistpk',
						'$kd_komoditi',
						'$berat',
						'$no_seal',
						'$keterangan',
						'0U',
						'0',
						'$nm_user',
						'1',
						'$hz',
						'T',
						'$yp',
						'$yp2'
						)";
					//echo $sqldet;exit();
					/*
					if(!$db->query($sqldet))
					{
						if($jenistpk =='3')
						{
							$sqlx3   = "INSERT INTO PETIKEMAS_CABANG.TTD_CONT_PEB ( KD_PMB_DTL, NO_NPE, TGL_PEB, USER_ID, KD_CABANG, TGL_SIMPAN, KD_PMB, NO_INVOICE, NO_UKK ) VALUES
								(
									PETIKEMAS_CABANG.SEQ_TTD_CONT_EXBSPL.CURRVAL,
									'-',
									SYSDATE, 
									'$nm_user',
									'05',
									SYSDATE,
									'$no_req2',
									'',
									'$no_ukk'
								)";
							$db->query($sqlx3);
						}
					}
					*/
	
	// ===========================================  END SIMOP ==============================================//
	$query_cek		= "SELECT a.ID_YARD_AREA FROM placement b, blocking_area a 
                                    WHERE b.ID_BLOCKING_AREA = a.ID 
                                    AND b.NO_CONTAINER = '$no_cont'";
        $result_cek		= $db->query($query_cek);
        $row_cek		= $result_cek->fetchRow();
        $id_yard		= $row_cek["ID_YARD_AREA"];

        // mengetahui tanggal start_stack
        $query_cek1		= "SELECT tes.NO_REQUEST, 
                                    CASE SUBSTR(KEGIATAN,9)
                                        WHEN 'RECEIVING' THEN (SELECT CONCAT('RECEIVING_',a.RECEIVING_DARI) FROM request_receiving a WHERE a.NO_REQUEST = tes.NO_REQUEST)
                                        ELSE SUBSTR(KEGIATAN,9)
                                    END KEGIATAN FROM (SELECT TGL_UPDATE, NO_REQUEST,KEGIATAN FROM history_container WHERE no_container = '$no_cont' and kegiatan IN ('REQUEST RECEIVING','REQUEST STRIPPING','REQUEST STUFFING','REQUEST RELOKASI')) tes
                                    WHERE tes.TGL_UPDATE=(SELECT MAX(TGL_UPDATE) FROM history_container WHERE no_container = '$no_cont' and kegiatan IN ('REQUEST RECEIVING','REQUEST STRIPPING','REQUEST STUFFING','REQUEST RELOKASI'))";
        $result_cek1		= $db->query($query_cek1);
        $row_cek1		= $result_cek1->fetchRow();
        $no_request		= $row_cek1["NO_REQUEST"];
        $kegiatan		= $row_cek1["KEGIATAN"];
		
		IF ($kegiatan == 'RECEIVING_LUAR') {
				$query_cek1		= " SELECT SUBSTR(TO_CHAR(b.TGL_IN+5,'dd/mm/rrrr'),1,10) START_STACK FROM GATE_IN b WHERE b.NO_CONTAINER = '$no_cont' AND b.NO_REQUEST = '$no_request'";
				$result_cek1	= $db->query($query_cek1);
				$row_cek1		= $result_cek1->fetchRow();
				$start_stack	= $row_cek1["START_STACK"];
				$asal_cont 		= 'LUAR';
		} ELSE IF ($kegiatan == 'RECEIVING_TPK') {
				$query_cek1		= "SELECT TGL_BONGKAR START_STACK FROM container_receiving WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_request'";
				$result_cek1	= $db->query($query_cek1);
				$row_cek1		= $result_cek1->fetchRow();
				$start_stack	= $row_cek1["START_STACK"];
				$asal_cont 		= 'TPK';
		} ELSE IF ($kegiatan == 'STUFFING') {
				$query_cek1		= "SELECT SUBSTR(TO_CHAR(TGL_REALISASI,'MM/DD/YYYY'),1,9) START_STACK FROM container_stuffing WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_request'";
				$result_cek1	= $db->query($query_cek1);
				$row_cek1		= $result_cek1->fetchRow();
				$start_stack	= $row_cek1["START_STACK"];
				$asal_cont 		= 'DEPO';
		} ELSE IF ($kegiatan == 'STRIPPING') {
				$query_cek1		= "SELECT SUBSTR(TO_CHAR(TGL_REALISASI,'MM/DD/YYYY'),1,9) START_STACK FROM container_stripping WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_request'";
				$result_cek1	= $db->query($query_cek1);
				$row_cek1		= $result_cek1->fetchRow();
				$start_stack	= $row_cek1["START_STACK"];
				$asal_cont 		= 'DEPO';
		}

        $query_insert   = "INSERT INTO CONTAINER_DELIVERY(NO_CONTAINER, NO_REQUEST, STATUS, AKTIF, KELUAR,HZ, KOMODITI,KETERANGAN,NO_SEAL,BERAT,VIA, ID_YARD, NOREQ_PERALIHAN, START_STACK, ASAL_CONT) 
        VALUES('$no_cont', '$no_req', '$status','Y','N','$hz','$komoditi','$keterangan','$no_seal','$berat','$via','$id_yard','$no_request', TO_DATE('$start_stack','DD/MM/YYYY'), '$asal_cont')";
		
		//insert ke history
		$q_getc = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
						$r_getc = $db->query($q_getc);
						$rw_getc = $r_getc->fetchRow();
						$cur_c = $rw_getc["COUNTER"];
						//$cur_booking = $rw_getc["NO_BOOKING"];
        $history        = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, NO_BOOKING, COUNTER) 
                                                      VALUES ('$no_cont','$no_req','REQUEST DELIVERY',SYSDATE,'$id_user','$no_booking','$cur_c')";
       

	
	
	if($db->query($sqldet))
	{
		if($jenistpk =='3')
		{
			$sqlx3   = "INSERT INTO PETIKEMAS_CABANG.TTD_CONT_PEB ( KD_PMB_DTL, NO_NPE, TGL_PEB, USER_ID, KD_CABANG, TGL_SIMPAN, KD_PMB, NO_INVOICE, NO_UKK ) VALUES
				(
					PETIKEMAS_CABANG.SEQ_TTD_CONT_EXBSPL.CURRVAL,
					'-',
					SYSDATE, 
					'$nm_user',
					'05',
					SYSDATE,
					'$no_req2',
					'',
					'$no_ukk'
				)";
			$db->query($sqlx3);
		}
		
		if($db->query($query_insert))
		{
			if($db->query($history))
			{
				echo "OK";
			}
			else
			{
				echo "gagal insert History";exit;
			}
		}
		else
		{
			echo "gagal insert Container Delivery ";exit;
		}
	
	}
	else
	{
		echo "gagal insert simop";exit;
	}
	
	
	
	/*
	else
	{
		echo "gagal query sqldet TPK";exit;
	}
	*/
	/*
	
	*/
	
	
}else if (($no_cont <> null) && ($location == 'GATO') && ($req_dev <> NULL))
{
        echo "NOT_EXIST";
}
else
{
	echo "$no_cont | $location | $req_dev";	
	//debug($_POST);
}


 

        
?>