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
		$KD_AGEN		= $_POST["KD_AGEN"];
		$KETERANGAN	= $_POST["keterangan"];
		$ID_USER	= $_SESSION["LOGGED_STORAGE"];
        $ID_YARD	= $_SESSION["IDYARD_STORAGE"];
	
		$db = getDB("storage");
	
            $query_req 	= "UPDATE request_delivery SET  KD_EMKL = '$ID_EMKL',KETERANGAN = '$KETERANGAN', ID_USER = '$ID_USER', ID_YARD = '$ID_YARD', KD_AGEN = '$KD_AGEN' WHERE NO_REQUEST ='$NO_REQ' ";
     //  echo "$query_req";die;
         
//echo "$query_req";die;
	if($db->query($query_req))	
	{
		header('Location: '.HOME.APPID.'/edit?no_req='.$NO_REQ);		
	}


                
        
        
?>