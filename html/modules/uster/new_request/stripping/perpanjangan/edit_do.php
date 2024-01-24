<?php
        
		$db = getDB("storage");
		
		//$KETERANGAN		= $_POST["keterangan"].' ';
		$ID_USER		= $_SESSION["LOGGED_STORAGE"];
		$id_yard		= $_SESSION["IDYARD_STORAGE"];
		
		
	   // $perp_ke     	= $_POST['PERP_KE'];
		$no_cont     	= $_POST['NO_CONT'];
		$no_req	        = $_POST['NO_REQ'];
		$keterangan	    = $_POST['KETERANGAN'];
	
	
		$get_jumlah		= "SELECT COUNT(NO_CONTAINER) COUNT FROM CONTAINER_STRIPPING WHERE NO_REQUEST = '$no_req' AND AKTIF = 'Y'";
		$result_cont_ 	= $db->query($get_jumlah);
		$count			= $result_cont_->fetchRow();
		
		$jml 			= $count['COUNT'];
		
		for($i=1;$i<=$jml;$i++){
			if($_POST['TGL_PERP_'.$i] != NULL){
			$NO_CONT[$i] = $_POST['NO_CONT_'.$i];
			$TGL_PERP[$i] = $_POST['TGL_PERP_'.$i];
			}
		}
	
		//insert container_stripping satu persatu	
		for($i=1;$i<=$jml;$i++)
		{
                if($_POST['TGL_PERP_'.$i] != NULL){
					$query_insert_strip	= "UPDATE CONTAINER_STRIPPING SET END_STACK_PNKN = TO_DATE('$TGL_PERP[$i]','rrrr-mm-dd')
												WHERE NO_CONTAINER = '$NO_CONT[$i]' AND NO_REQUEST = '$no_req'";					
					$db->query($query_insert_strip);
                }
		}
        
		
		header('Location: '.HOME.APPID.'/edit?no_req='.$no_req);
	
?>