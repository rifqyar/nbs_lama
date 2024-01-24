<?php
        $TGL_REQ	= $_POST["tgl_dev"];
        $DEV_KE 	= $_POST["dev_ke"];
        $NO_REQ     = $_POST["NO_REQ"];
		$ID_EMKL	= $_POST["ID_EMKL"];
        $ASAL       = $_POST["ASAL"];
		$TUJUAN 	= $_POST["TUJUAN"];
        $NO_BOOKING	= $_POST["NO_BOOKING"];
        $PEB        = $_POST["peb"];
		$NPE     	= $_POST["npe"];
		$KETERANGAN	= $_POST["keterangan"];
		$ID_USER	= $_SESSION["LOGGED_STORAGE"];
        $ID_YARD	= $_SESSION["IDYARD_STORAGE"];
	
	$db = getDB("storage");
	
	$query_cek	= "SELECT LPAD((COUNT(1)+1),6,0) AS JUM, TO_CHAR(SYSDATE, 'MM') AS MONTH, TO_CHAR(SYSDATE, 'YY') AS YEAR FROM request_delivery WHERE rownum <= 10 ";
	$result_cek	= $db->query($query_cek);
	$jum_		= $result_cek->fetchRow();
	$jum		= $jum_["JUM"];
	$month		= $jum_["MONTH"];
	$year		= $jum_["YEAR"];
	
	
	$no_req		= 'DEL'.$month.$year.$jum;
	//echo $query_cek."<br/>";
	//echo $no_req.$jum."<br/>";
	
            $query_req 	= "UPDATE request_delivery SET  KD_EMKL = '$ID_EMKL', TGL_REQUEST_DELIVERY = TO_DATE('".$TGL_REQ."','yyyy/mm/dd'), KETERANGAN = '$KETERANGAN', ID_USER = '$ID_USER', ID_YARD = '$ID_YARD' WHERE NO_REQUEST ='$NO_REQ' ";
     //  echo "$query_req";die;
         
//echo "$query_req";die;
	if($db->query($query_req))	
	{
		header('Location: '.HOME.APPID.'/edit?no_req='.$NO_REQ);		
	}


                
        
        
?>