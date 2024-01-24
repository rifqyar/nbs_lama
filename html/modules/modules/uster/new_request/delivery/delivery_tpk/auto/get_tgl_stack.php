<?php
$db = getDB("storage");
$no_cont = $_POST["no_cont"];
$jn_repo = $_POST["JN_REPO"];

if($jn_repo != 'EMPTY'){
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
				$tgl_stack	= $row_cek1["START_STACK"];				
				$asal_cont 		= 'DEPO';
		} ELSE IF ($kegiatan == 'RECEIVING_TPK') {
				$query_cek1		= "SELECT TO_CHAR(TGL_BONGKAR,'dd/mm/rrrr') START_STACK FROM container_receiving WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_request'";
				$result_cek1	= $db->query($query_cek1);
				$row_cek1		= $result_cek1->fetchRow();
				$tgl_stack	= $row_cek1["START_STACK"];
				$asal_cont 		= 'TPK';
		} ELSE IF ($kegiatan == 'STUFFING') {
				$query_cek1		= "SELECT SUBSTR(TO_CHAR(TGL_REALISASI,'dd/mm/rrrr'),1,10) START_STACK FROM container_stuffing WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_request'";
				$result_cek1	= $db->query($query_cek1);
				$row_cek1		= $result_cek1->fetchRow();
				$tgl_stack	= $row_cek1["START_STACK"];
				$asal_cont 		= 'DEPO';
		} ELSE IF ($kegiatan == 'STRIPPING') {
				$query_cek1		= "SELECT SUBSTR(TO_CHAR(TGL_REALISASI,'dd/mm/rrrr'),1,10) START_STACK FROM container_stripping WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_request'";
				$result_cek1	= $db->query($query_cek1);
				$row_cek1		= $result_cek1->fetchRow();
				$tgl_stack	= $row_cek1["START_STACK"];
				$asal_cont 		= 'DEPO';
		} ELSE IF ($kegiatan == 'DELIVERY') {
				$query_cek1		= "SELECT SUBSTR(TO_CHAR(TGL_DELIVERY,'dd/mm/rrrr'),1,10) START_STACK FROM container_delivery WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_request'";
				$result_cek1	= $db->query($query_cek1);
				$row_cek1		= $result_cek1->fetchRow();
				$tgl_stack	= $row_cek1["START_STACK"];
				$asal_cont 		= 'DEPO';
				//echo $no_request;  exit();
		}
}

else {
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
		$tgl_stack = $rtgl_r["TGL_REALISASI"];
	} else if($ex_keg == "GATE IN"){
		$qtgl_r = $db->query("SELECT TGL_IN FROM GATE_IN WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_re_st'");
		$rtgl_r = $qtgl_r->fetchRow();
		$tgl_stack = $rtgl_r["TGL_IN"];
	} else if($ex_keg == "PERPANJANGAN STUFFING"){
		$qtgl_r = $db->query("SELECT END_STACK_PNKN FROM CONTAINER_STUFFING WHERE NO_REQUEST = '$no_re_st' AND NO_CONTAINER = '$no_cont'");
		$rtgl_r = $qtgl_r->fetchRow();
		$tgl_stack = $rtgl_r["END_STACK_PNKN"];
		$asal_cont 		= 'DEPO';
	} else if($ex_keg == "REQUEST STUFFING"){
		$qtgl_r = $db->query("SELECT START_PERP_PNKN FROM CONTAINER_STUFFING WHERE NO_REQUEST = '$no_re_st' AND NO_CONTAINER = '$no_cont'");
		$rtgl_r = $qtgl_r->fetchRow();
		$tgl_stack = $rtgl_r["START_PERP_PNKN"];
		$asal_cont 		= 'DEPO';
	}	

}

	$hasil = "SELECT TO_CHAR(TO_DATE('$tgl_stack','dd-mm-rrrr'),'dd-mm-rrrr') TGL_BONGKAR, TO_CHAR(TO_DATE('$tgl_stack','dd-mm-rrrr')+4,'dd-mm-rrrr') EMPTY_SD FROM DUAL";
	$hasil_ = $db->query($hasil);
	$rhasil = $hasil_->fetchRow();
	
	echo json_encode($rhasil); 
?>