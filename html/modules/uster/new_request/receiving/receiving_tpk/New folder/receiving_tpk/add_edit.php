<?php

	$ID_EMKL	= $_POST["ID_EMKL"];
	$VOYAGE		= $_POST["VOYAGE"];
	$KETERANGAN	= $_POST["KETERANGAN"];
	$NO_REQUEST = $_POST["NO_REQ"];
	$ID_USER	= $_SESSION["LOGGED_STORAGE"];
	$ID_YARD	= $_SESSION["IDYARD_STORAGE"];
	
	$db = getDB("storage");
	
	$query_update	= "UPDATE REQUEST_RECEIVING 
					   SET ID_EMKL = '$ID_EMKL', 
						   VOYAGE = '$VOYAGE'
					   WHERE NO_REQUEST = '$NO_REQUEST'";
					   
	if($db->query($query_update))	
		{
			$query_no	= "SELECT TO_CHAR(CONCAT('$time_req', LPAD('$jum',6,'0'))) AS NO_REQ 
						   FROM DUAL";
			$result_no	= $db->query($query_no);
			$no_req_	= $result_no->fetchRow();
			$no_req		= $no_req_["NO_REQ"];
			debug($no_req_);
			header('Location: '.HOME.APPID.'/view?no_req='.$no_req);		
		}
		
		
		
		
		
		
	
?>