<?php
// --TAMBAH CONTAINER REQUEST DELIVERY KE LUAR
// --Model Dokumentasi
// -- Daftar Isi
// [1] - Update ke tabel PETIKEMAS_CABANG.TTD_CONT_EXBSPL
// [2] - Update ke tabel MASTER_CONTAINER 
// [3] - Insert ke tabel MASTER_CONTAINER 
// [4] - Update ke tabel MASTER_CONTAINER

$db 		= getDB("storage");

$no_cont	= $_POST["NO_CONT"]; 
$no_req		= $_POST["NO_REQ"]; 
$status		= $_POST["STATUS"]; 
$hz         = $_POST["HZ"]; 
$keterangan	= $_POST["KETERANGAN"]; 
$no_seal	= $_POST["NO_SEAL"]; 
$berat		= $_POST["BERAT"]; 
$via        = $_POST["VIA"]; 
$komoditi   = $_POST["KOMODITI"]; 
$start_pnkn = $_POST["start_pnkn"]; 
$end_pnkn = $_POST["end_pnkn"]; 
$size       = $_POST["SIZE"]; 
$type       = $_POST["TYPE"]; 
$id_user    = $_SESSION["LOGGED_STORAGE"];
$bp_id		= $_POST["BP_ID"];
$asal_auto_cont		= $_POST["ASAL_CONT"];



	// [start_pnkn] => 02-04-2013
    // [KETERANGAN] => 
    // [NO_SEAL] => 
    // [BERAT] => 3500
    // [VIA] => darat
    // [KOMODITI] => 
    // [NO_CONT] => KGSU2323257
    // [NO_REQ] => DEL0413000029
    // [STATUS] => MTY
    // [HZ] => N
    // [SIZE] => 20
    // [TYPE] => DRY
    // [BP_ID] => 

// debug($_POST);die;
// [1] - Update ke tabel PETIKEMAS_CABANG.TTD_CONT_EXBSPL
//	Untuk mengubah status container di TPK, sehingga bisa dilakukan request muat
	



// Melakukan konversi dari kode size TPK ke kode uster
/* if($size == '1'){
	$size = '20';
	
}
else if($size == '2'){
	$size = '40';
	
}
if($type == '08'){
	$type = 'DRY';
}  */

// echo "sampai sini";die;

$query_cek_cont			= "SELECT NO_BOOKING, COUNTER, LOCATION
							FROM  MASTER_CONTAINER
							WHERE NO_CONTAINER ='$no_cont'
							";
$result_cek_cont = $db->query($query_cek_cont);
$row_cek_cont	 = $result_cek_cont->fetchRow();
$cek_book		 = 'VESSEL_NOTHING';
$cek_counter	= $row_cek_cont["COUNTER"];
$cek_location	= $row_cek_cont["LOCATION"];

if ($cek_location != 'IN_YARD') {
	echo "con_yard";
	exit();
}

$cek_gato = "SELECT AKTIF
                FROM CONTAINER_RECEIVING
               WHERE NO_CONTAINER = '$no_cont' AND AKTIF = 'Y' ORDER BY AKTIF DESC";
$d_gato = $db->query($cek_gato);
$r_gato = $d_gato->fetchRow();
$l_gato = $r_gato["AKTIF"];
if($l_gato == 'Y'){
	echo "con_yard";
	exit();
}


if($cek_book == NULL){

	// [2] - Update ke tabel MASTER_CONTAINER 
	// Masih belum jelas, karena no_booking tidak didapat
	$q_update_book = "UPDATE MASTER_CONTAINER SET NO_BOOKING = '$no_booking' WHERE NO_CONTAINER = '$no_cont'";
	
	// echo "update booking master_cont baru";die;
	
	$db->query($q_update_book);
	
}

$query_cek_mst_cont			= "SELECT COUNT(NO_CONTAINER) JML
							FROM  MASTER_CONTAINER
							WHERE NO_CONTAINER ='$no_cont'
							";
$result_cek_mst_cont = $db->query($query_cek_mst_cont);
$row_cek_mst_cont	 = $result_cek_mst_cont->fetchRow();
$cek_mst		 = $row_cek_mst_cont["JML"];

// debug($cek_mst);die;

if($cek_book == NULL)
{
	$cek_book == 'VESSEL_NOTHING';
}					

if($cek_mst == 0){

	// [3] - Insert ke tabel MASTER_CONTAINER 
	// Jika container baru pertama masuk ke Uster, atau belem ada di master container, maka diinsert di master_container
	$q_insert_no_container = "INSERT INTO MASTER_CONTAINER(NO_CONTAINER, SIZE_, TYPE_, LOCATION, NO_BOOKING, COUNTER) VALUES('$no_cont','$size','$type','IN_YARD','$cek_book','1')";
	
	// echo "insert master_cont";die;
	
	$db->query($q_insert_no_container);
}
else {
	// [4] - Update ke tabel MASTER_CONTAINER
	// belum jelas peruntukkannya
	$q_update_book2 = "UPDATE MASTER_CONTAINER SET NO_BOOKING = '$cek_book', COUNTER = '$cek_counter' WHERE NO_CONTAINER = '$no_cont'";
	
	// echo "update book master_cont lama";die;
	
	$db->query($q_update_book2);
}

// $q_update_book2 = "UPDATE MASTER_CONTAINER SET NO_BOOKING = '$no_booking' WHERE NO_CONTAINER = '$no_cont'";
// $db->query($q_update_book2);

$query_cek		= "SELECT b.NO_CONTAINER, b.LOCATION, NVL((SELECT NO_CONTAINER FROM CONTAINER_DELIVERY WHERE NO_CONTAINER = '$no_cont' AND AKTIF = 'Y'), '') as STATUS FROM  MASTER_CONTAINER b WHERE  b.NO_CONTAINER = '$no_cont'";
//echo $query_cek;die;
$result_cek		= $db->query($query_cek);
$row_cek		= $result_cek->fetchRow();
$no_cont		= $row_cek["NO_CONTAINER"];
$location		= $row_cek["LOCATION"];
$req_dev                = $row_cek["STATUS"];
if ($asal_auto_cont == 'TPK') {
	$location = 'IN_YARD';
}

// debug($row_cek);die;

//ECHO $query_cek;

if(($no_cont <> NULL) && ($location == 'IN_YARD') && ($req_dev <> NULL))
{
          echo "SDH_REQUEST";
		  exit();
} else if (($no_cont <> NULL) && ($location == 'GATI') && ($req_dev == NULL))
{
	echo "BLM_PLACEMENT";	
	exit();
} else  if (($no_cont <> NULL) && ($location == 'IN_YARD') && ($req_dev == NULL))
{
	    $query_cek		= "SELECT a.ID_YARD_AREA FROM placement b, blocking_area a 
                        WHERE b.ID_BLOCKING_AREA = a.ID 
                        AND b.NO_CONTAINER = '$no_cont'";
        $result_cek		= $db->query($query_cek);
        $row_cek		= $result_cek->fetchRow();
        $id_yard		= $row_cek["ID_YARD_AREA"];
		
		$q_getc = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
						$r_getc = $db->query($q_getc);
						$rw_getc = $r_getc->fetchRow();
						$cur_book = $rw_getc["NO_BOOKING"];
						$cur_c = $rw_getc["COUNTER"];
		
		if ($asal_auto_cont == 'TPK') {
			if($cur_book == NULL){
			$cur_book = "VESSEL_NOTHING";
			$db->query("UPDATE MASTER_CONTAINER SET NO_BOOKING = 'VESSEL_NOTHING', LOCATION = 'IN_YARD' WHERE NO_CONTAINER = '$no_cont'");
			}
		}
		else{
			if($cur_book == NULL){
			$cur_book = "VESSEL_NOTHING";
			$db->query("UPDATE MASTER_CONTAINER SET NO_BOOKING = 'VESSEL_NOTHING' WHERE NO_CONTAINER = '$no_cont'");
			}
		}

		
	
        $history  = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD, NO_BOOKING, COUNTER, STATUS_CONT) 
                                                      VALUES ('$no_cont','$no_req','REQUEST DELIVERY',SYSDATE,'$id_user','$id_yard','VESSEL_NOTHING','$cur_c','$status')";
       
        $db->query($history);

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
				$query_cek1		= " SELECT SUBSTR(TO_CHAR(b.TGL_IN,'dd/mm/rrrr'),1,10) START_STACK FROM GATE_IN b WHERE b.NO_CONTAINER = '$no_cont' AND b.NO_REQUEST = '$no_request'";
				$result_cek1	= $db->query($query_cek1);
				$row_cek1		= $result_cek1->fetchRow();
				$start_stack	= $row_cek1["START_STACK"];
				$asal_cont 		= 'DEPO';
		} ELSE IF ($kegiatan == 'RECEIVING_TPK') {
				$query_cek1		= "SELECT TGL_BONGKAR START_STACK FROM container_receiving WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_request'";
				$result_cek1	= $db->query($query_cek1);
				$row_cek1		= $result_cek1->fetchRow();
				$start_stack	= $row_cek1["START_STACK"];
				$asal_cont 		= 'TPK';
		} ELSE IF ($kegiatan == 'STUFFING') {
				$query_cek1		= "SELECT SUBSTR(TO_CHAR(TGL_REALISASI,'MM/DD/YYYY'),1,10) START_STACK FROM container_stuffing WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_request'";
				$result_cek1	= $db->query($query_cek1);
				$row_cek1		= $result_cek1->fetchRow();
				$start_stack	= $row_cek1["START_STACK"];
				$asal_cont 		= 'DEPO';
		} ELSE IF ($kegiatan == 'STRIPPING') {
				$query_cek1		= "SELECT SUBSTR(TO_CHAR(TGL_REALISASI,'MM/DD/YYYY'),1,10) START_STACK FROM container_stripping WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_request'";
				$result_cek1	= $db->query($query_cek1);
				$row_cek1		= $result_cek1->fetchRow();
				$start_stack	= $row_cek1["START_STACK"];
				$asal_cont 		= 'DEPO';
		}
		
				$query_tgl_stack_depo = "SELECT TGL_UPDATE , NO_REQUEST, KEGIATAN, NO_BOOKING
                                            FROM HISTORY_CONTAINER 
                                            WHERE no_container = '$no_cont' 
                                            AND kegiatan IN ('GATE IN','REALISASI STRIPPING')
                                            ORDER BY TGL_UPDATE DESC";
				
				$tgl_stack_depo	= $db->query($query_tgl_stack_depo);
				$row_tgl_stack_depo		= $tgl_stack_depo->fetchRow();
				$start_stack	= $row_tgl_stack_depo["TGL_STACK"];	
				$ex_keg	= $row_tgl_stack_depo["KEGIATAN"];	
				$no_re_st	= $row_tgl_stack_depo["NO_REQUEST"];	
				$no_booking_l	= $row_tgl_stack_depo["NO_BOOKING"];	
				if($ex_keg == "REALISASI STRIPPING"){
					$qtgl_r = $db->query("SELECT TGL_REALISASI FROM CONTAINER_STRIPPING WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_re_st'");
					$rtgl_r = $qtgl_r->fetchRow();
					$start_stack = $rtgl_r["TGL_REALISASI"];
					$asal_cont 		= 'DEPO';
				} else if ($ex_keg == "GATE IN"){
					$qtgl_r = $db->query("SELECT SUBSTR(TO_CHAR(b.TGL_IN,'dd/mm/rrrr'),1,10) START_STACK FROM GATE_IN b WHERE b.NO_CONTAINER = '$no_cont' AND b.NO_REQUEST = '$no_re_st'");
					$rtgl_r = $qtgl_r->fetchRow();
					$start_stack = $rtgl_r["START_STACK"];	
					$asal_cont 		= 'DEPO';					
				} else if ($ex_keg == "BORDER GATE IN"){
					$qtgl_r = $db->query("SELECT SUBSTR(TO_CHAR(b.TGL_IN,'dd/mm/rrrr'),1,10) START_STACK FROM BORDER_GATE_IN b WHERE b.NO_CONTAINER = '$no_cont' AND b.NO_REQUEST = '$no_re_st'");
					$rtgl_r = $qtgl_r->fetchRow();
					$start_stack = $rtgl_r["START_STACK"];				
					$asal_cont 		= 'DEPO';
				}
		
        $query_insert   = "INSERT INTO CONTAINER_DELIVERY(NO_CONTAINER, NO_REQUEST, STATUS, AKTIF, KELUAR,HZ, KOMODITI,KETERANGAN,NO_SEAL,BERAT,VIA, ID_YARD, NOREQ_PERALIHAN, START_STACK, ASAL_CONT, TGL_DELIVERY) 
        VALUES('$no_cont', '$no_req', '$status','Y','N','$hz','$komoditi','$keterangan','$no_seal','$berat','$via','$id_yard','$no_request',TO_DATE('$start_stack','dd/mm/rrrr'),'$asal_cont', TO_DATE('$end_pnkn','dd/mm/rrrr'))";
		//Masa akhir penumpukan delivery, di-default masa 1.1(+4 hari dari awal stack), tapi bisa diinput sendiri
        //echo $start_stack;exit();
		
         
        
// echo $query_insert;
       //$update         = "UPDATE MASTER_CONTAINER SET LOCATION = 'REQ DELIVERY' WHERE NO_CONTAINER = '$no_cont'";
       // echo $query_insert;
       //$db->query($update);
    /* if (($no_cont <> null) AND ($location == 'GATO') AND ($req_dev <> NULL) AND ($start_stack <> NULL))
	{
        echo "NOT_EXIST";
	} else  */
	if ( substr($bp_id,0,2) != 'BP' ){
	$sqlact = "UPDATE PETIKEMAS_CABANG.TTD_CONT_EXBSPL 
				SET STATUS_PP = 'Y',
					KETERANGAN = 'DELIVERY DEPO USTER' 
				WHERE KD_PMB = '$bp_id' 
					AND NO_CONTAINER = '$no_cont'";
	$db->query($sqlact);
	}

	
	
	if($start_stack == NULL)
	{	
		// $start_pnkn = '1/4/2013';
		//echo "TGL_TUMPUK";
		$history  = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, NO_BOOKING, COUNTER, STATUS_CONT) 
                                                      VALUES ('$no_cont','$no_req','REQUEST DELIVERY',SYSDATE,'$id_user','VESSEL_NOTHING','$cur_c','$status')";
       
        //$db->query($history);
		
		 $query_insert   = "INSERT INTO CONTAINER_DELIVERY(NO_CONTAINER, NO_REQUEST, STATUS, AKTIF, KELUAR,HZ, KOMODITI,KETERANGAN,NO_SEAL,BERAT,VIA, ID_YARD, NOREQ_PERALIHAN, START_STACK, ASAL_CONT, TGL_DELIVERY, EX_BP_ID) 
        VALUES('$no_cont', '$no_req', '$status','Y','N','$hz','$komoditi','$keterangan','$no_seal','$berat','$via','$id_yard','$no_request',TO_DATE('$start_pnkn','dd/mm/rrrr'),'DEPO', TO_DATE('$end_pnkn','dd/mm/rrrr'), '$bp_id')";
		
		$db->query($query_insert);
		echo "OK";
		exit();
	}   
	else if($db->query($query_insert))
	{
		echo "OK";
		exit();
	}

	/* else {
		echo "GAGAL";
	} */

}
        
?>