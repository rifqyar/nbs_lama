<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('nota_list.htm');
	
	$tgl_awal	= $_POST["tgl_awal"]; 
	$tgl_akhir	= $_POST["tgl_akhir"]; 
	$jenis		= $_POST["jenis"];
	$pembayaran	= $_POST["pembayaran"];
	$status_bayar = $_POST["status_bayar"];
	$status_nota = $_POST["status_nota"];
	$corporatetype = $_POST["corporatetype"];
	
	if($status_nota == 'NEW'){
		$status_nota = " AND STATUS IN ('NEW', 'KOREKSI', 'PERP') ";
	}
	else if($status_nota == 'BATAL') {
		$status_nota = " AND STATUS IN ('BATAL') ";
	}
	else{
		$status_nota = " AND STATUS IN ('NEW', 'KOREKSI', 'PERP','BATAL') ";
	}
	
	if($status_bayar == "YES"){
		$status_bayar = "AND LUNAS = 'YES'";
	}
	else if($status_bayar == "NO"){
		$status_bayar = "AND LUNAS = 'NO'";
	}
	else{
		$status_bayar = "";
	}
	
	if($pembayaran == ''){
		$pembayaran = "";
	}
	else if($pembayaran == 'BANK'){
		$pembayaran = "AND BAYAR LIKE '%BANK%'";
	}
	else if($pembayaran == 'CASH'){
		$pembayaran = "AND BAYAR LIKE '%CASH%'";
	}
	else if($pembayaran == 'AUTODB'){
		$pembayaran = "AND BAYAR LIKE '%AUTODB%'";
	}
	else if($pembayaran == 'DEPOSIT'){
		$pembayaran = "AND BAYAR LIKE '%DEPOSIT%'";
	}
	
	debug($_POST);
	
	//echo $tgl_awal;die;
	$db 	= getDB("storage"); 
	//$db_uster_ict 	= getDB("uster_ict"); 
	
	
	if($corporatetype=='IPC'){
	$query_list_ = "SELECT * FROM (
					    SELECT TRANSFER,
					       NO_NOTA_MTI,
					       NO_FAKTUR_MTI,
					       NOTA_ALL_H.NO_REQUEST,
					       TRUNC (TGL_NOTA) TGL_NOTA,
					       CASE WHEN SUBSTR(NO_REQUEST,0,3) = 'DEL' THEN 'DELIVERY'
					                WHEN SUBSTR(NO_REQUEST,0,3) = 'STP' THEN 'STRIPPING'
					                WHEN SUBSTR(NO_REQUEST,0,3) = 'STR' THEN 'STRIPPING'
					                WHEN SUBSTR(NO_REQUEST,0,3) = 'STF' THEN 'STUFFING'
					                WHEN SUBSTR(NO_REQUEST,0,3) = 'SFP' THEN 'STUFFING'
					                WHEN SUBSTR(NO_REQUEST,0,3) = 'REC' THEN 'RECEIVING'
					                WHEN SUBSTR(NO_REQUEST,0,3) = 'BMU' THEN 'BATALMUAT'
					       END AS KEGIATAN,
					       EMKL,
					       TO_CHAR (TOTAL_TAGIHAN, '999,999,999,999') TOTAL_TAGIHAN,
					       BAYAR,
					       NOTA_ALL_H.STATUS STATUS,
					       LUNAS,
					       RECEIPT_ACCOUNT
					  FROM    NOTA_ALL_H
					 WHERE (TRUNC (TGL_NOTA) BETWEEN TO_DATE ('$tgl_awal', 'yyyy-mm-dd')
					                             AND TO_DATE ('$tgl_akhir', 'yyyy-mm-dd')))                             
					  WHERE KEGIATAN LIKE '%$jenis%' ".$pembayaran." ".$status_nota." ".$status_bayar;
	// }
		$result_list_	= $db->query($query_list_);
		$row_list		= $result_list_->getAll(); 
	}
	else
	{
		$query_list_ = "select CASE WHEN A.STATUS='3b' THEN 'Y' ELSE 'N' END AS TRANSFER,
						A.NO_NOTA_MTI, 
						A.NO_FAKTUR_MTI,
						A.NO_REQUEST,
						TRUNC (A.TRX_DATE) TGL_NOTA,
						CASE WHEN SUBSTR(A.NO_REQUEST,0,3) = 'DEL' THEN 'DELIVERY'
													WHEN SUBSTR(A.NO_REQUEST,0,3) = 'STP' THEN 'STRIPPING'
													WHEN SUBSTR(A.NO_REQUEST,0,3) = 'STR' THEN 'STRIPPING'
													WHEN SUBSTR(A.NO_REQUEST,0,3) = 'STF' THEN 'STUFFING'
													WHEN SUBSTR(A.NO_REQUEST,0,3) = 'SFP' THEN 'STUFFING'
													WHEN SUBSTR(A.NO_REQUEST,0,3) = 'REC' THEN 'RECEIVING'
													WHEN SUBSTR(A.NO_REQUEST,0,3) = 'BMU' THEN 'BATALMUAT'
										   END AS KEGIATAN,
						B.NM_PBM EMKL, 
						TO_CHAR (A.KREDIT, '999,999,999,999') TOTAL_TAGIHAN,          
						A.RECEIPT_METHOD BAYAR,
						CASE WHEN A.STATUS_NOTA =1 THEN 'KOREKSI' ELSE 'NEW' END AS STATUS,  
						CASE WHEN A.TGL_PELUNASAN IS NOT NULL THEN 'YES' ELSE 'NO' END AS LUNAS,        
						A.RECEIPT_ACCOUNT      
						from ITPK_NOTA_HEADER A JOIN MST_PELANGGAN B ON A.CUSTOMER_NUMBER=B.NO_ACCOUNT_PBM(+)
						WHERE (TRUNC (TRX_DATE) BETWEEN TO_DATE ('$tgl_awal', 'yyyy-mm-dd')
																 AND TO_DATE ('$tgl_akhir', 'yyyy-mm-dd'))";
																 
						$result_list_	= $db->query($query_list_);
						$row_list		= $result_list_->getAll(); 
						}
	//echo $query_list_;	DIE;
	//print_r($row_list);
	//die();
	//$jumlah=count($row_list);DIE;
	$tl->assign("jmlk",$jumlah);
	$tl->assign("row_list",$row_list);
	$tl->assign("tgl_awal",$tgl_awal);
	$tl->assign("tgl_akhir",$tgl_akhir);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
