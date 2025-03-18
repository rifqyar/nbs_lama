<?php

	$id_emkl	= $_POST["ID_EMKL"];
	$voyage		= $_POST["VOYAGE"];
	//$no_booking	= $_POST["NO_BOOKING"];
	$keterangan	= $_POST["KETERANGAN"];
	$id_user	= $_SESSION["LOGGED_STORAGE"];
	$id_yard	= $_SESSION["IDYARD_STORAGE"];
	$rec_dari	= $_POST ["REC_DARI"];
	$no_request = $_POST["NO_REQ"];
	$no_do		= $_POST["NO_DO"];
	$no_bl		= $_POST["NO_BL"];
	$no_sppb	= $_POST["NO_SPPB"];
	$tgl_sppb	= $_POST["TGL_SPPB"];
	
	
	$db = getDB("storage");
	//debug($no_booking);die;
	//debug($id_emkl);die;
	//debug("no booking =".$no_booking." | id emkl =".$id_emkl." | rec dari =".$rec_dari."  |no_do =".$no_do."  |no_bl =".$no_bl);die;
	
/*	$sql 	= "UPDATE  PETIKEMAS_CABANG.TTM_DEL_REQ SET  
		   TGL_BAYAR2	 	    = to_date('".$_POST["BAYAR_2"]."','DD-MM-YYYY'), 
		   KD_PBM			 	= '$id_emkl',
		   NM_AGEN			 	= '".$_POST["NM_AGEN"]."',     		  		   
		   KETERANGAN 			= '$keterangan',
		   NO_DO				= '$no_do',
		   NO_BL				= '$no_bl',
		   NO_SPPB				= '$no_sppb',
		   TGL_SPPB				= TO_DATE ( '$tgl_sppb', 'DD-MM-YYYY '),  
		   MODIFY_BY			= '".$aclist["USERID"]."',
		   MODIFY_DATE			= SYSDATE 
		   WHERE NO_REQ_DEL  	= '$autobp'";      
		   //echo $sql;	exit();	   
		   $db->query($sql);	 
*/
	$query_update	= "UPDATE REQUEST_RECEIVING 
					   SET ID_EMKL 		= '$id_emkl', 
						RECEIVING_DARI = '$rec_dari',
						NO_SPPB	   = '$no_sppb',
						TGL_SPPB   = TO_DATE ( '$tgl_sppb', 'YYYY-MM-DD '),
						NO_DO	   = '$no_do',
						NO_BL	   = '$no_bl',
						KETERANGAN = '$keterangan'
					   WHERE NO_REQUEST = '$no_request'";
	//$tes = $db->query($query_update);
	//debug($query_update);
	if($db->query($query_update))	
		{
			$query_no	= "SELECT TO_CHAR(CONCAT('$time_req', LPAD('$jum',6,'0'))) AS NO_REQ 
						   FROM DUAL";
			$result_no	= $db->query($query_no);
			$no_req_	= $result_no->fetchRow();
			$no_req		= $no_req_["NO_REQ"];
			//debug($no_req_);
			header('Location: '.HOME.APPID.'/view?no_req='.$no_request);		
		}
		
		
		
		
		
		
	
?>