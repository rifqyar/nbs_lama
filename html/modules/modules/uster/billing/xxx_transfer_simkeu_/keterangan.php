<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('keterangan.htm');
	
	$tgl_awal	= $_GET["tgl_awal"]; 
	$tgl_akhir	= $_GET["tgl_akhir"]; 
	$db 	= getDB("storage");

	$query_jml 		= "SELECT COUNT(NO_NOTA) JUMLAH FROM nota_receiving WHERE TGL_NOTA BETWEEN TO_DATE('$tgl_awal','dd/mm/yyyy') AND TO_DATE('$tgl_akhir','dd/mm/yyyy')";
	$result_list	= $db->query($query_jml);
	$jumlah		= $result_list->getAll(); 
	
	$tl->assign("row_list",$row_list);
	$tl->assign("jumlah_nota",$jumlah);
	$tl->assign("tgl_awal",$tgl_awal);
	$tl->assign("tgl_akhir",$tgl_akhir);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
