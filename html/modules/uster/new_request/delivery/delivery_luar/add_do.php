<?php
        $TGL_REQ	= $_POST["tgl_dev"];
        $DEV_KE 	= $_POST["dev_ke"];

	//$ID_PBM	= $_POST["ID_PBM"];
		$EMKL		= $_POST["ID_EMKL"];
		$ALMT_PBM	= $_POST["ALMT_PBM"];
		$NPWP		= $_POST["NPWP"];
        $ASAL       = $_POST["ASAL"];
		$TUJUAN 	= $_POST["TUJUAN"];
        $PEB        = $_POST["peb"];
		$NPE     	= $_POST["npe"];
        $NO_BOOKING	= $_POST["NO_BOOKING"];
        $NO_RO		= $_POST["NO_RO"];
        $AC_EMKL		= $_POST["AC_EMKL"];		
        $KD_AGEN		= $_POST["KD_AGEN"];
		$KETERANGAN	= $_POST["keterangan"];
		$ID_USER	= $_SESSION["LOGGED_STORAGE"];
        $ID_YARD	= $_SESSION["IDYARD_STORAGE"];
	
		$db = getDB("storage");
		
			$query_cek	= "SELECT LPAD(NVL(MAX(SUBSTR(NO_REQUEST,8,13)),0)+1,6,0) AS JUM , 
				   TO_CHAR(SYSDATE, 'MM') AS MONTH, 
				   TO_CHAR(SYSDATE, 'YY') AS YEAR 
				   FROM REQUEST_DELIVERY
				   WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) ";
					
	$result_cek	= $db->query($query_cek);
	$jum_		= $result_cek->fetchRow();
	$jum		= $jum_["JUM"];
	$month		= $jum_["MONTH"];
	$year		= $jum_["YEAR"];
	
	$no_req	= "DEL".$month.$year.$jum;

    $query_req 	= "INSERT INTO request_delivery(NO_REQUEST, REQ_AWAL, TGL_REQUEST, TGL_REQUEST_DELIVERY, KETERANGAN, DELIVERY_KE, CETAK_KARTU, ID_USER,  PERALIHAN, ID_YARD, STATUS,KD_EMKL, NO_RO, KD_AGEN) 
                                          VALUES ('$no_req', '$no_req',SYSDATE, TO_DATE('".$TGL_REQ."','yyyy/mm/dd'),'$KETERANGAN', 'LUAR','0', $ID_USER,'T','$ID_YARD','NEW','$EMKL', '$NO_RO', '$KD_AGEN')";
    $db->query($history);
//echo "$query_req";die;
	if($db->query($query_req))	
	{
		header('Location: '.HOME.APPID.'/edit?no_req='.$no_req);		
	}


                
					
        
?>