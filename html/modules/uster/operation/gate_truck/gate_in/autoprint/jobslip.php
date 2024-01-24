<?php 
	$tl = xliteTemplate("jobslip.htm");
	$no_cont = $_GET["no_cont"];
	$no_req = $_GET["no_req"];
	$lokasi = $_GET["lokasi"];
	$kegiatan = $_GET["kegiatan"];
	$blok = $_GET["blok"];
	$slot = $_GET["slot"];
	$row = $_GET["row"];
	$tier = $_GET["tier"];
	$tl->assign('no_cont', $no_cont);
	$tl->assign('no_req', $no_req);
	$tl->assign('lokasi', $lokasi);
	$tl->assign('kegiatan', $kegiatan);
	$tl->assign('blok', $blok);
	$tl->assign('slot', $slot);
	$tl->assign('row', $row);
	$tl->assign('tier', $tier);
	$tl->renderToScreen();
?>
