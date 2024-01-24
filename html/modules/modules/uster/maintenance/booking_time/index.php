<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('home.htm');
	
	$db 	= getDB("storage");
	$qsl 	= "select * from master_booking_time";
	$rsl	= $db->query($qsl);
	$rsl_	= $rsl->fetchRow();
	$query			= "select a.NM_AGEN, a.KD_AGEN, a.KD_KAPAL, a.NM_KAPAL,a.VOYAGE_IN,a.VOYAGE_OUT,a.NO_UKK, a.NO_BOOKING,  TO_CHAR(a.tgl_jam_berangkat,'dd/mm/rrrr') TGL_BERANGKAT,TO_DATE(a.tgl_stacking) TGL_STACKING, TO_DATE(a.tgl_muat,'DD/MM/RRRR') TGL_MUAT, 
					a.TGL_JAM_BERANGKAT ETD, a.TGL_JAM_TIBA ETA
					from v_booking_stack_tpk a 
					--where to_date(A.TGL_JAM_BERANGKAT) >=add_months(sysdate,-1) 
					--and a.NM_KAPAL LIKE '%$nama_kapal%'";
	$result		= $db->query($query);
	$row		= $result->getAll();
	
	$op_kpl	= "<select id='kapal' name='kapal'>";
	foreach($row as $rw){
		$op_kpl	.= "<option value='$rw[NO_BOOKING]'>$rw[NM_KAPAL]</option>";
	}
	$op_kpl	.= "</select>";	
	
	
	
	$tl->assign("op_kpl",$op_kpl);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
