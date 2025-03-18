<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('preview_rbm_jict.htm');
	
	$db=  getDB();
 
	$no_ukk 	= $_POST['id_vessel'];
	 
	$query_h 	= "select NM_KAPAL, NM_PEMILIK, TGL_JAM_TIBA, TGL_JAM_BERANGKAT, VOYAGE_IN, VOYAGE_OUT FROM rbm_h where NO_UKK='$no_ukk'";
	$result_h   = $db->query($query_h);
	$header      = $result_h->fetchRow();

	$query_db 	= "select EI, SIZE_, TYPE_, STATUS, HZ, BOX from rbm_d where NO_UKK='$no_ukk' AND EI='I'";
	$result_db  = $db->query($query_db);
	$detail_b   = $result_db->getAll();
	
	$query_dm 	= "select EI, SIZE_, TYPE_, STATUS, HZ, BOX from rbm_d where NO_UKK='$no_ukk' AND EI='E'";
	$result_dm  = $db->query($query_dm);
	$detail_m   = $result_dm->getAll();

	$tl->assign("detail_m",$detail_m);
	$tl->assign("detail_b",$detail_b);
	$tl->assign("header",$header);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);	
	$tl->renderToScreen();
?>
