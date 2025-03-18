<?php
// debug ($_POST);die;
$db 			= getDB("storage");
$db2 = getDB("ora");

$nm_user		= $_SESSION["NAME"];
$no_cont		= $_POST["NO_CONT"]; 
$no_req			= $_POST["NO_REQ"]; 
$no_req2		= $_POST["NO_REQ2"]; 
$status			= $_POST["STATUS"]; 
$hz                     = $_POST["HZ"]; 
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
$jn_repo		= $_POST["JN_REPO"];
$ex_pmb		= $_POST["EX_PMB"];
$no_ukk			= $_POST["NO_UKK"];
$tgl_delivery			= $_POST["tgl_delivery"];
$id_user                = $_SESSION["LOGGED_STORAGE"];
$id_yard_	= $_SESSION["IDYARD_STORAGE"];

$asal  = $_POST["asal"];
$tgl_stack  = $_POST["tgl_stack"];

$bp_id = $ex_pmb;

$cek_tunjuk_kapal = "SELECT NO_CONTAINER, 
            CASE NO_UKK
            WHEN 'MIGRASIBOO' THEN 'NOTHING'
            WHEN 'NTH05000001' THEN 'NOTHING'
            WHEN 'NTGH17120002' THEN 'NOTHING'
            WHEN 'NTH15000001' THEN 'NOTHING'
            END NO_UKK
            , KD_PMB_DTL,STATUS_PMB_DTL,STATUS_PP
                      FROM PETIKEMAS_CABANG.TTH_CONT_EXBSPL, PETIKEMAS_CABANG.TTD_CONT_EXBSPL
                     WHERE     TTH_CONT_EXBSPL.KD_PMB = TTD_CONT_EXBSPL.KD_PMB
                           AND TTD_CONT_EXBSPL.STATUS_PMB_DTL != '4'
                           AND TTD_CONT_EXBSPL.NO_CONTAINER = '$no_cont'
                           AND TTD_CONT_EXBSPL.STATUS_PP = 'T'
                           AND TTH_CONT_EXBSPL.KD_CABANG = '05'";

$rsx = $db2->query($cek_tunjuk_kapal);
$rowx = $rsx->FetchRow();
$NO_UKKX = $rowx["NO_UKK"]; 
// NTGH17120002
// NTH15000001       
if($rsx->RecordCount()>0 && $NO_UKKX != 'NOTHING' )
{
	echo "EXIST_MUAT"; exit(); //KALO AKTIF DI SIKLUS MUAT, GA BOLEH REQUEST (EDZ)
}



$cek_gati = "SELECT AKTIF FROM CONTAINER_RECEIVING WHERE NO_CONTAINER = '$no_cont' order by AKTIF DESC";
$r_gati = $db->query($cek_gati);
$rw_gati = $r_gati->fetchRow();
$aktif_rec = $rw_gati['AKTIF'];
if($aktif_rec == 'Y'){
	echo 'EXIST_REC';
	exit();
}

$cek_req_satu_kapal = "select no_booking from container_delivery, request_delivery where container_delivery.no_request = request_delivery.no_request 
					and no_container = '$no_cont' and no_booking = '$no_booking' and aktif = 'Y'";
$rcek_kpl = $db->query($cek_req_satu_kapal);
$rw_cekkpl = $rcek_kpl->fetchRow();
$nobokk_lama =  $rw_cekkpl['NO_BOOKING'];
if($nobokk_lama != NULL){
	echo 'EXIST_DEL_BY_BOOKING';
	exit();
}

$cek_stuf = "SELECT AKTIF
                FROM CONTAINER_STUFFING
               WHERE NO_CONTAINER = '$no_cont' AND AKTIF = 'Y'";
$d_stuf = $db->query($cek_stuf);
$r_stuf = $d_stuf->fetchRow();
$l_stuf = $r_stuf["AKTIF"];
if($l_stuf == 'Y'){
	echo "EXIST_STUF";
	exit();
}

$cek_strip = "SELECT AKTIF
                FROM CONTAINER_STRIPPING
               WHERE NO_CONTAINER = '$no_cont' AND AKTIF = 'Y'";
$d_strip = $db->query($cek_strip);
$r_strip = $d_strip->fetchRow();
$l_strip = $r_strip["AKTIF"];
if($l_strip == 'Y'){
	echo "EXIST_STRIP";
	exit(); 
}


if($jn_repo == 'EMPTY'){
	if ( substr($bp_id,0,2) != 'BP' ){
		$sqlact = "UPDATE PETIKEMAS_CABANG.TTD_CONT_EXBSPL 
					SET STATUS_PP = 'Y',
						KETERANGAN = 'REPO MTY USTER' 
					WHERE KD_PMB = '$bp_id' 
						AND NO_CONTAINER = '$no_cont'";
		$db->query($sqlact);
	}
}
/* if($size == '1'){
	$size = '20';
}
else if($size == '2'){
	$size = '40';
}

if($type == '08'){
	$type == 'DRY';
} */

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

//if($cek_book == NULL){	
	$q_update_book = "UPDATE MASTER_CONTAINER SET NO_BOOKING = '$no_booking' WHERE NO_CONTAINER = '$no_cont'";
	$db->query($q_update_book);
//}
$q_getcounter = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
$r_getcounter = $db->query($q_getcounter);
$rw_getcounter = $r_getcounter->fetchRow();
$cur_counter = $rw_getcounter["COUNTER"];
$cur_booking = $rw_getcounter["NO_BOOKING"];


if($cur_booking == NULL){
	$cur_booking == 'VESSEL_NOTHING';
}	

if($cur_counter == NULL){
	
	$q_insert_no_container = "INSERT INTO MASTER_CONTAINER(NO_CONTAINER, SIZE_, TYPE_, LOCATION, NO_BOOKING, COUNTER) VALUES('$no_cont','$size','$type','IN_YARD','$no_booking','1')";
	
	$db->query($q_insert_no_container);
}
else {
	$q_update_book2 = "UPDATE MASTER_CONTAINER SET NO_BOOKING = '$no_booking', COUNTER = '$cur_counter' WHERE NO_CONTAINER = '$no_cont'";
	$db->query($q_update_book2);
}
					
$query_cek		= "SELECT b.NO_CONTAINER, b.LOCATION --, NVL((), '') as STATUS 
FROM MASTER_CONTAINER b 
WHERE b.NO_CONTAINER = '$no_cont'";
$query_cek2 = "SELECT NO_CONTAINER FROM CONTAINER_DELIVERY WHERE NO_CONTAINER = '$no_cont' AND AKTIF = 'Y'";
//echo $query_cek;die;

$result_cek		= $db->query($query_cek);
$row_cek		= $result_cek->fetchRow();

$result_cek2		= $db->query($query_cek2);
$row_cek2		= $result_cek2->fetchRow();

$no_cont		= $row_cek["NO_CONTAINER"];
$location		= $row_cek["LOCATION"];
if($asal == 'TPK')
{
	$location = 'IN_YARD';
}
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
	
	
	$query_cek		= "SELECT a.ID_YARD_AREA FROM placement b, blocking_area a 
                                    WHERE b.ID_BLOCKING_AREA = a.ID 
                                    AND b.NO_CONTAINER = '$no_cont'";
        $result_cek		= $db->query($query_cek);
        $row_cek		= $result_cek->fetchRow();
        $id_yard		= $row_cek["ID_YARD_AREA"];

        // mengetahui tanggal start_stack ,
		//dimana kegiatan sebelum delivery bisa beraneka rupa
        /**/
		if($jn_repo == 'EMPTY'){
			$query_tgl_stack_depo = "SELECT TGL_UPDATE , NO_REQUEST, KEGIATAN
	                                            FROM HISTORY_CONTAINER 
	                                            WHERE no_container = '$no_cont' 
	                                            AND kegiatan IN ('GATE IN','REALISASI STRIPPING','PERPANJANGAN STUFFING','REQUEST STUFFING')
	                                            ORDER BY TGL_UPDATE DESC";
						
			$tgl_stack_depo	= $db->query($query_tgl_stack_depo);
			$row_tgl_stack_depo		= $tgl_stack_depo->fetchRow();
			//$tgl_stack	= $row_tgl_stack_depo["TGL_STACK"];	
			$ex_keg	= $row_tgl_stack_depo["KEGIATAN"];	
			$no_re_st	= $row_tgl_stack_depo["NO_REQUEST"];	
			if($ex_keg == "REALISASI STRIPPING"){
				$qtgl_r = $db->query("SELECT TGL_REALISASI FROM CONTAINER_STRIPPING WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_re_st'");
				$rtgl_r = $qtgl_r->fetchRow();
				$start_stack = $rtgl_r["TGL_REALISASI"];
				$asal_cont 		= 'DEPO';
			} else if($ex_keg == "GATE IN"){
				$qtgl_r = $db->query("SELECT TGL_IN FROM GATE_IN WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_re_st'");
				$rtgl_r = $qtgl_r->fetchRow();
				$start_stack = $rtgl_r["TGL_IN"];
				$asal_cont 		= 'DEPO';
			} else if($ex_keg == "PERPANJANGAN STUFFING"){
				$qtgl_r = $db->query("SELECT END_STACK_PNKN FROM CONTAINER_STUFFING WHERE NO_REQUEST = '$no_re_st' AND NO_CONTAINER = '$no_cont'");
				$rtgl_r = $qtgl_r->fetchRow();
				$start_stack = $rtgl_r["END_STACK_PNKN"];
				$asal_cont 		= 'DEPO';
			} else if($ex_keg == "REQUEST STUFFING"){
				$qtgl_r = $db->query("SELECT START_PERP_PNKN FROM CONTAINER_STUFFING WHERE NO_REQUEST = '$no_re_st' AND NO_CONTAINER = '$no_cont'");
				$rtgl_r = $qtgl_r->fetchRow();
				$start_stack = $rtgl_r["START_PERP_PNKN"];
				$asal_cont 		= 'DEPO';
			}	
		}
		else {

			$query_cek1		= "SELECT tes.NO_REQUEST, 
                                    CASE SUBSTR(KEGIATAN,9)
                                        WHEN 'RECEIVING' THEN (SELECT CONCAT('RECEIVING_',a.RECEIVING_DARI) FROM request_receiving a WHERE a.NO_REQUEST = tes.NO_REQUEST)
										WHEN 'NGAN STUFFING' THEN
										SUBSTR(KEGIATAN,14)
										WHEN 'NGAN STRIPPING' THEN
										SUBSTR(KEGIATAN,14)
										WHEN 'I STRIPPING' THEN
                                        SUBSTR(KEGIATAN,11)
										WHEN 'I STUFFING' THEN
                                        SUBSTR(KEGIATAN,11)
                                        WHEN 'IVERY' THEN
							            SUBSTR (KEGIATAN, 6)
                                        ELSE SUBSTR(KEGIATAN,9)
                                    END KEGIATAN FROM (SELECT TGL_UPDATE, NO_REQUEST,KEGIATAN FROM history_container WHERE no_container = '$no_cont' and kegiatan IN ('REQUEST RECEIVING','REQUEST STRIPPING','REQUEST STUFFING','REQUEST RELOKASI','PERPANJANGAN STUFFING','PERPANJANGAN STRIPPING','REALISASI STRIPPING', 'REALISASI STUFFING', 'REQUEST DELIVERY','PERP DELIVERY') AND AKTIF IS NULL) tes
                                    WHERE tes.TGL_UPDATE=(SELECT MAX(TGL_UPDATE) FROM history_container WHERE no_container = '$no_cont' and kegiatan IN ('REQUEST RECEIVING','REQUEST STRIPPING','REQUEST STUFFING','REQUEST RELOKASI','PERPANJANGAN STUFFING','PERPANJANGAN STRIPPING','REALISASI STRIPPING', 'REALISASI STUFFING', 'REQUEST DELIVERY','PERP DELIVERY') AND AKTIF IS NULL)
									ORDER BY KEGIATAN DESC";
	        $result_cek1		= $db->query($query_cek1);
	        $row_cek1		= $result_cek1->fetchRow();
	        $no_request		= $row_cek1["NO_REQUEST"];
	        $kegiatan		= $row_cek1["KEGIATAN"];
			
			IF ($kegiatan == 'RECEIVING_LUAR') {
					$query_cek1		= " SELECT SUBSTR(TO_CHAR(b.TGL_IN,'dd/mm/rrrr'),1,10) START_STACK FROM GATE_IN b WHERE b.NO_CONTAINER = '$no_cont' AND b.NO_REQUEST = '$no_request'";
					$result_cek1	= $db->query($query_cek1);
					$row_cek1		= $result_cek1->fetchRow();
					$start_stack	= $row_cek1["START_STACK"];				
					$asal_cont 		= 'DEPO';
			} ELSE IF ($kegiatan == 'RECEIVING_TPK') {
					$query_cek1		= "SELECT TO_CHAR(TGL_BONGKAR,'dd/mm/rrrr') START_STACK FROM container_receiving WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_request'";
					$result_cek1	= $db->query($query_cek1);
					$row_cek1		= $result_cek1->fetchRow();
					$start_stack	= $row_cek1["START_STACK"];
					$asal_cont 		= 'TPK';
			} ELSE IF ($kegiatan == 'STUFFING') {
					$query_cek1		= "SELECT SUBSTR(TO_CHAR(TGL_REALISASI,'dd/mm/rrrr'),1,10) START_STACK FROM container_stuffing WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_request'";
					$result_cek1	= $db->query($query_cek1);
					$row_cek1		= $result_cek1->fetchRow();
					$start_stack	= $row_cek1["START_STACK"];
					$asal_cont 		= 'DEPO';
			} ELSE IF ($kegiatan == 'STRIPPING') {
					$query_cek1		= "SELECT SUBSTR(TO_CHAR(TGL_REALISASI,'dd/mm/rrrr'),1,10) START_STACK FROM container_stripping WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_request'";
					$result_cek1	= $db->query($query_cek1);
					$row_cek1		= $result_cek1->fetchRow();
					$start_stack	= $row_cek1["START_STACK"];
					$asal_cont 		= 'DEPO';
			} ELSE IF ($kegiatan == 'DELIVERY') {
					$query_cek1		= "SELECT SUBSTR(TO_CHAR(TGL_DELIVERY,'dd/mm/rrrr'),1,10) START_STACK FROM container_delivery WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_request'";
					$result_cek1	= $db->query($query_cek1);
					$row_cek1		= $result_cek1->fetchRow();
					$start_stack	= $row_cek1["START_STACK"];
					$asal_cont 		= 'DEPO';
			}
 
		}

		if($asal == 'TPK'){
			$start_stack = $tgl_stack;
			$asal_cont 		= 'TPK';
			
			$query_cek_cont			= "SELECT NO_CONTAINER 
											FROM  MASTER_CONTAINER
											WHERE NO_CONTAINER ='$no_cont'
											";
					$result_cek_cont = $db->query($query_cek_cont);
					$row_cek_cont	 = $result_cek_cont->fetchRow();
					$cek_cont		 = $row_cek_cont["NO_CONTAINER"];
					
					if($cek_cont == NULL)
					{
						
						$query_insert_mstr	= "INSERT INTO MASTER_CONTAINER(NO_CONTAINER,
																			SIZE_,
																			TYPE_,
																			LOCATION, NO_BOOKING, COUNTER)
																	 VALUES('$no_cont',
																			'$size',
																			'$tipe',
																			'IN_YARD', '$no_booking', 1)
												";						
						$db->query($query_insert_mstr);
					}
					else {
						$query_counter = "SELECT COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
						$r_counter = $db->query($query_counter);
						$rw_counter = $r_counter->fetchRow();
						$last_counter = $rw_counter["COUNTER"];
						if ($jn_repo == "EKS_STUFFING") {	
							$last_counter = $last_counter;
						}
						else{
							$last_counter = $last_counter+1;
						}
						
						$q_update_book2 = "UPDATE MASTER_CONTAINER SET NO_BOOKING = '$no_booking', TYPE_ = '$tipe', COUNTER = '$last_counter', LOCATION = 'IN_YARD' 
						WHERE NO_CONTAINER = '$no_cont'";
						$db->query($q_update_book2);
					}
		}
		else{
			$q_getc1 = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont' ORDER BY COUNTER DESC";
						$r_getc1 = $db->query($q_getc1);
						$rw_getc1 = $r_getc1->fetchRow();
						if ($jn_repo == "EKS_STUFFING") {
        					$cur_c1 = $rw_getc1["COUNTER"];
        				}
        				else{
        					$cur_c1 = $rw_getc1["COUNTER"]+1;
        				}
			$q_update_book3 = "UPDATE MASTER_CONTAINER SET NO_BOOKING = '$no_booking', TYPE_ = '$tipe', COUNTER = '$cur_c1' 
						WHERE NO_CONTAINER = '$no_cont'";
						$db->query($q_update_book3);
		}
        $query_insert   = "INSERT INTO CONTAINER_DELIVERY(NO_CONTAINER, NO_REQUEST, STATUS, AKTIF, KELUAR,HZ, KOMODITI,KETERANGAN,NO_SEAL,BERAT,VIA, ID_YARD, NOREQ_PERALIHAN, START_STACK, TGL_DELIVERY, ASAL_CONT, EX_BP_ID) 
        VALUES('$no_cont', '$no_req', '$status','Y','N','$hz','$komoditi','$keterangan','$no_seal','$berat','$via','$id_yard_','$no_request', TO_DATE('$start_stack','dd/mm/rrrr'), TO_DATE('$tgl_delivery','dd/mm/rrrr'), '$asal_cont','$bp_id')";
		
		//insert ke history
		$q_getc = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont' ORDER BY COUNTER DESC";
						$r_getc = $db->query($q_getc);
						$rw_getc = $r_getc->fetchRow();
						$cur_c = $rw_getc["COUNTER"];
						//$cur_booking = $rw_getc["NO_BOOKING"];

        $history        = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD, NO_BOOKING, COUNTER,STATUS_CONT) 
                                                      VALUES ('$no_cont','$no_req','REQUEST DELIVERY',SYSDATE,'$id_user','$id_yard_','$no_booking','$cur_c','$status')";
       
        
	
	// =========================================== SIMOP ==============================================//
/*	$sqlcek3 = "SELECT TO_CHAR (TGL_JAM_CLOSE,'DD-MM-YYYY HH24:MI:SS')TGL_JAM_CLOSE  FROM PETIKEMAS_CABANG.TTH_CONT_BOOKING WHERE NO_BOOKING = '$NO_BOOKING'";
	
	$rs3 = $db->query($sqlcek3);
	$rowcek3 = $rs3 -> FetchRow();
	$datedoc = $rowcek3["TGL_JAM_CLOSE"];
	
	$sqldate = "SELECT TO_CHAR (SYSDATE,'DD-MM-YYYY HH24:MI:SS')HARINI FROM DUAL";
	
	$rsdate = $db->query($sqldate);
	$rowdate = $rsdate -> FetchRow();
	$sysdate = $rowdate["HARINI"];
	
*/	
	//if ($datedoc  <= $sysdate)
	//{
	//	echo "CLOSING_TIME"; exit;
	//}else
	//{
			
	$sqlsize  	= "SELECT KD_SIZE FROM PETIKEMAS_CABANG.MST_SIZE_CONT WHERE SIZE_CONT = '$size'"; 	
	$rssize		= $db2->query($sqlsize);
	$sizetpk_	= $rssize->fetchRow();
	$sizetpk	= $sizetpk_["KD_SIZE"];
	//echo $sqlsize;exit;
	
	$sqltype	= "SELECT STY_CODE FROM PETIKEMAS_CABANG.MST_TYPE_CONT WHERE STY_NAME = '$tipe'";
	$rstype		= $db2->query($sqltype);
	$typetpk_	= $rstype->fetchRow();
	$typetpk	= $typetpk_["STY_CODE"];
	//echo $typetpk;
	
	$sqljenis 	= "SELECT KD_JENIS_PEMILIK FROM PETIKEMAS_CABANG.MST_JENIS_PEMILIK WHERE NM_JENIS_PEMILIK = '$status'";
	$rsjenis	= $db2->query($sqljenis);
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
			$rsm = $db2->query($sqlm);
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
						   
					if($rsx = $db2->selectLimit( $sqlxx,0,1 ))
					{
						
						$rscon  = $rsx->FetchRow();
						$yp     = $rscon["ARE_ID"];
						$slott  = $rscon["ARE_SLOT"] + 1;
						
						if($sizetpk !='1')
							{
								$sql2 = "SELECT ARE_ID FROM PETIKEMAS_CABANG.V_MST_YARD_EX WHERE ARE_TIER='".$rscon["ARE_TIER"]."' 
								AND ARE_BLOK='".$rscon["ARE_BLOK"]."' AND ARE_ROW='".$rscon["ARE_ROW"]."' AND ARE_SLOT='".$slott."' AND KD_CABANG='05'";
								if( $rss2 = $db2->selectLimit( $sql2,0,1 ) )
								{
								$rscon2   = $rss2->FetchRow();
								$yp2      = $rscon2["ARE_ID"];
								}
							}
						
						
						$sqlxxx = "UPDATE PETIKEMAS_CABANG.MST_YARD_CONT_LAPANGAN_EX SET KD_STATUS_CY='1' WHERE ARE_ID='".$yp."'";
						$db2->startTransaction();
						$xxxxxx = $db2->query($sqlxxx);
						$db2->endTransaction();
						
						$sqlxxzz = "UPDATE PETIKEMAS_CABANG.MST_YARD_CONT_LAPANGAN_EX SET KD_STATUS_CY='1' WHERE ARE_ID='".$yp2."'";
						$db2->startTransaction();
						$xxxxzz = $db2->query($sqlxxzz);
						$db2->endTransaction();	
						
						if($yp==''){
							$yp = 'XXXX';
						}
					
					}
			}

//utang are_id,are_id2,temp,class			

if ($komoditi == '' )
   {$kd_komoditi = 'C000000293';}
if ($berat == '' )
   {$beratict = '0';}  {$beratict = $berat;}
if ($hz == 'N')   
   {$hzict = 'T';}else{$hzict = 'Y';}
			$sqldet   = "INSERT INTO PETIKEMAS_CABANG.TTD_CONT_EXBSPL(KD_PMB_DTL,KD_PMB,NO_CONTAINER,KD_SIZE,KD_TYPE_CONT,KD_JENIS_PEMILIK,KD_COMMODITY,GROSS,NO_SEAL,KETERANGAN,STATUS_PMB_DTL,STATUS_KARTU,USER_ID,VIA,HZ,STATUS_PP,ARE_ID,ARE_ID2,TGL_AWAL,TGL_AKHIR) 
					  VALUES
					 (
						PETIKEMAS_CABANG.SEQ_TTD_CONT_EXBSPL.NEXTVAL,
						'$no_req2',						
						'$no_cont',
						'$sizetpk',
						'$typetpk',
						'$jenistpk',
						'$kd_komoditi',
						'$beratict',
						'$no_seal',
						'$keterangan',
						'0U',
						'0',
						'USTER',
						'1',
						'$hzict',
						'T',
						'$yp',
						'$yp2',
						TO_DATE('$start_stack','dd/mm/rrrr'),
						TO_DATE('$tgl_delivery','dd/mm/rrrr')
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
	
	if($db2->query($sqldet))
	{
		if($jenistpk =='3')
		{
			$sqlx3   = "INSERT INTO PETIKEMAS_CABANG.TTD_CONT_PEB ( KD_PMB_DTL, NO_NPE, TGL_PEB, USER_ID, KD_CABANG, TGL_SIMPAN, KD_PMB, NO_INVOICE, NO_UKK ) VALUES
				(
					PETIKEMAS_CABANG.SEQ_TTD_CONT_EXBSPL.CURRVAL,
					'-',
					SYSDATE, 
					'USTER',
					'05',
					SYSDATE,
					'$no_req2',
					'',
					'$no_ukk'
				)";
			$db2->query($sqlx3);
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