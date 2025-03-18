<?php 
	$tl = xliteTemplate("jobslip.htm");
	$no_cont = $_GET["no_cont"];
	$no_kartu = $_GET["no_req"];
	$no_req_ = explode('-', $no_kartu);
	$no_req = $no_req_[0];
	$lokasi = $_GET["lokasi"];
	$pos = strrpos($no_req, "STR");
	if($pos === false){
		$kegiatan = 'STUFFING';
	}
	else{
		$kegiatan = 'STRIPPING';
	}
	
	$tl->assign('no_cont', $no_cont);
	$tl->assign('no_req', $no_req);
	$tl->assign('lokasi', $lokasi);
	$tl->assign('kegiatan', $kegiatan);
	$tl->renderToScreen();
?>
