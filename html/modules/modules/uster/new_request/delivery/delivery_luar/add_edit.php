<?php


        
        $NO_REQ         = $_POST["NO_REQ"];
        $TGL_REQ	= $_POST["tgl_dev"];
	$ID_EMKL	= $_POST["ID_EMKL"];
	$KETERANGAN	= $_POST["keterangan"];
	$ID_USER	= $_SESSION["LOGGED_STORAGE"];
	
	$db = getDB("storage");

	$query_req 	= "UPDATE REQUEST_DELIVERY SET ID_EMKL = '$ID_EMKL', TGL_REQUEST = SYSDATE, TGL_REQUEST_DELIVERY = TO_DATE('".$TGL_REQ."','dd/mm/yyyy'), KETERANGAN = '$KET', ID_USER = '$ID_USER' WHERE NO_REQUEST = '$NO_REQ'";
//        echo $query_req;

	if($db->query($query_req))	
	{
		header('Location: '.HOME.APPID.'/edit?no_req='.$NO_REQ);		
	} else {
                header('Location: '.HOME.APPID.'/edit?no_req='.$NO_REQ);	
            
        }
        


                
        
        
?>