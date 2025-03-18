<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('cont_list.htm');
	
	$no_req	= $_GET["no_req"]; 
	$db 	= getDB("storage");

	if(isset($_POST["cari"]))
	{
		
	}
	else
	{
		$query_list		= "SELECT DISTINCT CONTAINER_STRIPPING.NO_CONTAINER,CONTAINER_STRIPPING.COMMODITY, 
							CONTAINER_STRIPPING.TGL_APPROVE, CONTAINER_STRIPPING.TGL_BONGKAR, CONTAINER_STRIPPING.START_PERP_PNKN,CONTAINER_STRIPPING.END_STACK_PNKN,CONTAINER_STRIPPING.TGL_APP_SELESAI, 
							 CASE WHEN CONTAINER_STRIPPING.TGL_SELESAI  IS NULL
                             THEN TO_DATE (CONTAINER_STRIPPING.TGL_BONGKAR + 4, 'dd/mm/rrrr')
                             ELSE
                              TO_DATE (CONTAINER_STRIPPING.TGL_SELESAI, 'dd/mm/rrrr')
                             END AS TGL_SELESAI,
							A.SIZE_ KD_SIZE, A.TYPE_ KD_TYPE, CONTAINER_STRIPPING.REMARK REMARK
                           FROM CONTAINER_STRIPPING
                           --INNER JOIN PETIKEMAS_CABANG.TTD_BP_CONT A ON CONTAINER_STRIPPING.NO_CONTAINER = A.CONT_NO_BP
						   INNER JOIN MASTER_CONTAINER A ON CONTAINER_STRIPPING.NO_CONTAINER = A.NO_CONTAINER
                           WHERE CONTAINER_STRIPPING.NO_REQUEST = '$no_req'
						   AND CONTAINER_STRIPPING.AKTIF = 'Y'";
	}
	
		$cek_save = "SELECT CLOSING,PERP_KE FROM REQUEST_STRIPPING WHERE NO_REQUEST = '$no_req'";
		$e_cek = $db->query($cek_save);
		$r_ce = $e_cek->fetchRow();
		$close = $r_ce["CLOSING"];
		$first = $r_ce["PERP_KE"];
		
	$result_list	= $db->query($query_list);
	$row_list		= $result_list->getAll(); 
	
	if($first <= 1){
		$tl->display('first');
	}else{
		$tl->display('second');
	}
	//echo $jumlah;
	$edit = $_GET["edit"];
	$tl->assign("edit",$edit);
	$tl->assign("first",$first);
	$tl->assign("close",$close);
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
