<?php
$db = getDB("storage");
$no_cont = $_POST["no_cont"];
$query_tgl_stack_depo = "SELECT TGL_UPDATE , NO_REQUEST, KEGIATAN
                                            FROM HISTORY_CONTAINER 
                                            WHERE no_container = '$no_cont' 
                                            AND kegiatan IN ('GATE IN','REALISASI STRIPPING')
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
}
$hasil = "SELECT TO_CHAR(TO_DATE('$tgl_stack','dd-mm-rrrr'),'dd-mm-rrrr') TGL_BONGKAR, TO_CHAR(TO_DATE('$tgl_stack','dd-mm-rrrr')+4,'dd-mm-rrrr') EMPTY_SD FROM DUAL";
$hasil_ = $db->query($hasil);
$rhasil = $hasil_->fetchRow();
echo json_encode($rhasil);
//exit();
?>