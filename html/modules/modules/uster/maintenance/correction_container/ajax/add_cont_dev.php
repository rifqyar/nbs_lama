<?php

$db 		= getDB("storage");

$no_cont	= $_POST["NO_CONT"]; 
$no_req	= $_POST["NO_REQ"]; 
$status	= $_POST["STATUS"]; 
$hz           = $_POST["HZ"]; 
$keterangan	= $_POST["KETERANGAN"]; 
$no_seal	= $_POST["NO_SEAL"]; 
$berat		= $_POST["BERAT"]; 
$via             = $_POST["VIA"]; 
$komoditi       = $_POST["KOMODITI"]; 
$id_user        = $_SESSION["LOGGED_STORAGE"];

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

$q_update_book2 = "UPDATE MASTER_CONTAINER SET NO_BOOKING = '$no_booking' WHERE NO_CONTAINER = '$no_cont'";
$db->query($q_update_book2);

$query_cek		= "SELECT b.NO_CONTAINER, b.LOCATION, NVL((SELECT NO_CONTAINER FROM CONTAINER_DELIVERY WHERE NO_CONTAINER = '$no_cont' AND AKTIF = 'Y'), '') as STATUS FROM  MASTER_CONTAINER b WHERE  b.NO_CONTAINER = '$no_cont'";
//echo $query_cek;die;
$result_cek		= $db->query($query_cek);
$row_cek		= $result_cek->fetchRow();
$no_cont		= $row_cek["NO_CONTAINER"];
$location		= $row_cek["LOCATION"];
$req_dev                = $row_cek["STATUS"];

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
		
		$q_getc = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
						$r_getc = $db->query($q_getc);
						$rw_getc = $r_getc->fetchRow();
						$cur_book = $rw_getc["NO_BOOKING"];
						$cur_c = $rw_getc["COUNTER"];
	
        $history  = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, NO_BOOKING, COUNTER) 
                                                      VALUES ('$no_cont','$no_req','REQUEST DELIVERY',SYSDATE,'$id_user','$cur_book','$cur_c')";
       
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
				$query_cek1		= "SELECT SUBSTR(TO_CHAR(b.TGL_IN, 'MM/DD/YYYY'),1,10) START_STACK FROM GATE_IN b WHERE b.NO_CONTAINER = '$no_cont' AND b.NO_REQUEST = '$no_request'";
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
        
        $query_insert   = "INSERT INTO CONTAINER_DELIVERY(NO_CONTAINER, NO_REQUEST, STATUS, AKTIF, KELUAR,HZ, KOMODITI,KETERANGAN,NO_SEAL,BERAT,VIA, ID_YARD, NOREQ_PERALIHAN, START_STACK, ASAL_CONT) 
        VALUES('$no_cont', '$no_req', '$status','Y','N','$hz','$komoditi','$keterangan','$no_seal','$berat','$via','$id_yard','$no_request',TO_DATE('$start_stack','dd/mm/rrrr'),'$asal_cont')";
        
		
         
        
// echo $query_insert;
       //$update         = "UPDATE MASTER_CONTAINER SET LOCATION = 'REQ DELIVERY' WHERE NO_CONTAINER = '$no_cont'";
       // echo $query_insert;
       //$db->query($update);
        
	if($db->query($query_insert))
	{
		echo "OK";
	}
} else if (($no_cont <> null) && ($location == 'GATO') && ($req_dev <> NULL))
{
        echo "NOT_EXIST";
}

        
?>