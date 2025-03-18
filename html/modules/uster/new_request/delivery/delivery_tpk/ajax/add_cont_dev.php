<?php
// debug ($_POST);die;
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
$via            = $_POST["VIA"]; 
$komoditi       = $_POST["KOMODITI"]; 
$kd_komoditi    = $_POST["KD_KOMODITI"]; 
$size			= $_POST["SIZE"];
$tipe			= $_POST["TIPE"];
$status			= $_POST["STATUS"];
$no_booking		= $_POST["NO_BOOKING"];
$jn_repo		= $_POST["JN_REPO"];
$ex_pmb			= $_POST["EX_PMB"];
$no_ukk			= $_POST["NO_UKK"];
$tgl_delivery	= $_POST["tgl_delivery"];
$imoclass	    = $_POST["IMO"];
$unnumber   	= $_POST["UNNUMBER"];
$height     	= $_POST["HEIGHT"];
$temperature	= $_POST["TEMP"];
$carrier    	= $_POST["CARRIER"];
$oh_size    	= $_POST["OH"];
$ow_size    	= $_POST["OW"];
$ol_size       	= $_POST["OL"];
$id_user        = $_SESSION["LOGGED_STORAGE"];
$id_yard_	    = $_SESSION["IDYARD_STORAGE"];
$asal  			= $_POST["asal"];
$tgl_stack  	= $_POST["tgl_stack"];
$cont_limit		= $_POST["CONT_LIMIT"];

$bp_id = $ex_pmb;

// $cek_tpk = "select count(no_container) cek from m_cyc_container@dbint_link where no_container ='$no_cont'
// and E_I in ('I','E') AND CONT_LOCATION IN ('Yard','Chassis') and point = (select max(to_number(point)) from m_cyc_container@dbint_link where no_container ='$no_cont')";
// $rcektpk = $db->query($cek_tpk)->fetchRow();
// $exist_tpk = $rcektpk['CEK'];
// if($exist_tpk > 0){
//     echo 'EXIST_TPK';
//     exit();
// }

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
} else 
if (($no_cont <> NULL) && ($location == 'GATI') && ($req_dev == NULL))
{
	echo "BLM_PLACEMENT";	
} else if (($no_cont <> NULL) && ($location == 'IN_YARD') && ($req_dev == NULL))
{
	
    
    
    // =========================================== NBS_OPUS ==============================================//
       $di = 'D';
	   $param_detail = array('in_nocont'=>$no_cont,
                             'in_noreq'=>$no_req,
                             'in_reqnbs'=>$no_req2,
                             'in_user'=>$id_user,
                             'in_jnrepo'=>$jn_repo,
                             'in_status'=>$status,
                             'in_hz'=>$hz,
                             'in_komoditi'=>$komoditi,
                             'in_kdkomoditi'=>$kd_komoditi,
                             'in_keterangan'=>$keterangan,
                             'in_noseal'=>$no_seal,
                             'in_berat'=>$berat,
                             'in_via'=>$via,
                             'in_idyard'=>$id_yard,
                             'in_startstack'=>$tgl_stack,
                             'in_tgldelivery'=>$tgl_delivery,
                             'in_asalcont'=>$asal_cont,
                             'in_bpid'=>$bp_id,
                             'in_nobooking'=>$no_booking,
                             'in_idvsb'=>$no_ukk,
                             'in_counter'=>$cur_counter,
                             'in_imo'=>$imoclass,
                             'in_hg'=>$height,
                             'in_ship'=>$di,
                             'in_car'=>$carrier,
                             'in_temp'=>$temperature,
                             'in_oh'=>$oh_size,
                             'in_ow'=>$ow_size,
                             'in_ol'=>$ol_size,
                             'in_un'=>$unnumber,
                             'in_contlimit'=>$cont_limit,
                             'out_msgdet'=>''
                            );
           
	       $querydet = "declare begin pack_create_req_delivery_repo.create_detail_repo_praya(:in_nocont,:in_noreq,
           :in_reqnbs,:in_user,:in_jnrepo,:in_status,:in_hz,:in_komoditi,:in_kdkomoditi,:in_keterangan,:in_noseal,:in_berat,:in_via,
           :in_idyard,:in_startstack,:in_tgldelivery,:in_asalcont,:in_bpid,:in_nobooking,:in_idvsb,:in_counter,:in_imo,:in_hg,
           :in_ship,:in_car,:in_temp,:in_oh,:in_ow,:in_ol,:in_un,:in_contlimit,:out_msgdet); end;";
        //print_r($param_detail); die();

           // echo var_dump($param_detail);die;
			$db->query($querydet,$param_detail);
			$msgout = $param_detail['out_msgdet'];

			echo ($msgout);die;
			//exit();
    // $cek_nbs_exist = "SELECT count(1) FLAG from m_cyc_container@dbint_link WHERE billing_request_id = '$no_req2' AND NO_CONTAINER = '$no_cont'";
    // $r_nbs = $db->query($cek_nbs_exist)->fetchRow();
    // if($r_nbs["FLAG"] > 0){
    //     $msgout = "OK1";
    // }
    // else{
    //     echo "REQ_OPUS_FAIL";
    //     exit();
    // }
	// ===========================================  NBS_OPUS ==============================================//
if($msgout == 'OK1')  {
	
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
                                    END KEGIATAN 
                                FROM (SELECT TGL_UPDATE, NO_REQUEST,KEGIATAN FROM history_container WHERE no_container = '$no_cont' and kegiatan IN ('REQUEST RECEIVING','REQUEST STRIPPING','REQUEST STUFFING','REQUEST RELOKASI','PERPANJANGAN STUFFING','PERPANJANGAN STRIPPING','REALISASI STRIPPING', 'REALISASI STUFFING', 'REQUEST DELIVERY','PERP DELIVERY') AND AKTIF IS NULL) tes
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
      /*  $query_insert   = "INSERT INTO CONTAINER_DELIVERY(NO_CONTAINER, NO_REQUEST, STATUS, AKTIF, KELUAR,HZ, KOMODITI,KETERANGAN,NO_SEAL,BERAT,VIA, ID_YARD, NOREQ_PERALIHAN, START_STACK, TGL_DELIVERY, ASAL_CONT, EX_BP_ID) 
        VALUES('$no_cont', '$no_req', '$status','Y','N','$hz','$komoditi','$keterangan','$no_seal','$berat','$via','$id_yard_','$no_request', TO_DATE('$start_stack','dd/mm/rrrr'), TO_DATE('$tgl_delivery','dd/mm/rrrr'), '$asal_cont','$bp_id')";*/
		
		//insert ke history
		$q_getc = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont' ORDER BY COUNTER DESC";
						$r_getc = $db->query($q_getc);
						$rw_getc = $r_getc->fetchRow();
						$cur_c = $rw_getc["COUNTER"];
						//$cur_booking = $rw_getc["NO_BOOKING"];

        /*$history        = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD, NO_BOOKING, COUNTER,STATUS_CONT) 
                                                      VALUES ('$no_cont','$no_req','REQUEST DELIVERY',SYSDATE,'$id_user','$id_yard_','$no_booking','$cur_c','$status')";*/
        echo $msgout;
        exit();

    }
    else {
        echo $msgout;
        exit();
    }
        
			
		
	
	
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