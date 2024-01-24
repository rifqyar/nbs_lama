<?php

$db 		= getDB("storage");
$nm_user	= $_SESSION["NAME"];
$no_cont	= $_POST["NO_CONT"]; 
$no_req		= $_POST["NO_REQ"]; 
$no_req2	= $_POST["NO_REQ2"]; 
$status		= $_POST["STATUS"]; 
$berbahaya	= $_POST["BERBAHAYA"];
$no_do		= $_POST["NO_DO"];
$no_bl		= $_POST["NO_BL"];
$size		= $_POST["SIZE"];
$type		= $_POST["TYPE"];
$voyage		= $_POST["VOYAGE"];
$vessel		= $_POST["VESSEL"];
$tgl_stack	= $_POST["TGL_STACK"];
$no_ukk		= $_POST["NO_UKK"];
$nm_agen	= $_POST["NM_AGEN"];
$komoditi	= $_POST["KOMODITI"];
$tgl_bongkar	= $_POST["TGL_BONGKAR"];
$bp_id		= $_POST["BP_ID"];
$kd_consignee	= $_POST["KD_CONSIGNEE"];
$id_user        = $_SESSION["LOGGED_STORAGE"];


//print_r($status);die;
//Cek status kontainer, yg bisa direquest hanya yg berstatus GATO dan belum direquest (belum aktif)
$query_cek1		= "SELECT a.AKTIF, b.RECEIVING_DARI, c.LOCATION
				   FROM CONTAINER_RECEIVING a, REQUEST_RECEIVING b, MASTER_CONTAINER c
				   WHERE a.NO_CONTAINER = '$no_cont'
				   AND a.NO_REQUEST = b.NO_REQUEST
				   AND a.NO_CONTAINER = c.NO_CONTAINER";

$result_cek		= $db->query($query_cek1);
$row_cek2		= $result_cek->fetchRow();
$location		= $row_cek2["LOCATION"];
$aktif			= $row_cek2["AKTIF"];
$rec_dari		= $row_cek2["RECEIVING_DARI"];
				   

/*$query_rec_dari ="SELECT RECEIVING_DARI
						FROM REQUEST_RECEIVING
						WHERE NO_REQUEST = '$no_req'";
	$result_rec_dari = $db->query($query_rec_dari);
	$row_rec_dari = $result_rec_dari->fetchRow();
	$rec_dari = $row_rec_dari["RECEIVING_DARI"];
				   
$result_cek1	= $db->query($query_cek1);
$row_cek1		= $result_cek1->fetchRow();
//$jum			= $row_cek1["JUM"];
$aktif			= $row_cek1["AKTIF"];
			

			
$query_cek2		= "SELECT LOCATION 
				   FROM MASTER_CONTAINER 
				   WHERE NO_CONTAINER = '$no_cont' ";		
$result_cek2	= $db->query($query_cek2);
$row_cek2		= $result_cek2->fetchRow();
$location		= $row_cek2["LOCATION"];*/


//$aktif			= $row_cek["AKTIF"];
//print_r($jum.$location);die;

if($rec_dari == "TPK")
{
	if($status == NULL)
	{
		
			echo "STATUS";
			
		
	}
	else if($berbahaya == NULL)
	{
		
			echo "BERBAHAYA";
		
	}
	
	else if($voyage == NULL)
	{
			echo "VOYAGE";
	}
	
	else if(($aktif != "Y") && ($status != NULL)&& ($berbahaya != NULL) )
	{
		//Insert Ke ICT
		$sql 			= "SELECT NO_REQ_DEL FROM PETIKEMAS_CABANG.TTM_DEL_REQ WHERE NO_REQ_DEL = '$no_req2'";
		$sqldb 			= $db->query($sql);
		$row_sql		= $sqldb->fetchRow();
		$no_req_del		= $row_sql["NO_REQ_DEL"];
		
		//debug($no_req_del);die;
		if ($no_req_del != NULL)
		{
			$result_seq = "SELECT MAX(SEQ_DEL) as SEQ from PETIKEMAS_CABANG.TTD_DEL_REQ where  NO_REQ_DEL ='$no_req_del'";
			$rs 		= $db->query( $result_seq );
			$row		= $rs->FetchRow() ;			
			$seq	 	= $row['SEQ']+1; 
			
			$sql_no  	= "select AUTO_NO+1 AS AUTO_NO  from PETIKEMAS_CABANG.MST_AUTO_NO WHERE CODE='7'";
			$rs 	 	= $db->query( $sql_no );
			$row	 	= $rs->FetchRow();
			$autono  	= $row['AUTO_NO'];	
			
			
			$sqlinsert	= "INSERT INTO PETIKEMAS_CABANG.TTD_DEL_REQ(NO_REQ_DEL,NO_DO,CONT_NO_BP,SEQ_DEL,NO_SP2,NO_BL,NO_BP_ID,ENTRY_BY,ENTRY_DATE,
						   COMMODITY,TGL_STACK,TGL_DISCHARGE,HZ,KD_SIZE,KD_TYPE,KD_STATUS_CONT,NO_UKK,KD_PBM)    
		   VALUES(
		   '$no_req_del',
		   '$no_do', 		  		   
		   '$no_cont',  	 	   
		   '$seq',    
		   '$autono',
		   '$no_bl',
		   '$bp_id',
		   '$nm_user',
		   SYSDATE,
		   '$komoditi',
		   to_date('".$tgl_stack."','DD-MM-YYYY'),
		   to_date('".$tgl_bongkar."','DD-MM-YYYY'),  
		   '$berbahaya',
		   '$size',
		   '$type',
		   '04U',
		   '$no_ukk',
		   '$kd_consignee')";        
		   //echo $sqlinsert; exit;
		   
			if($db->query($sqlinsert))
			{		 		
			$sqlttm = "UPDATE PETIKEMAS_CABANG.TTM_DEL_REQ SET NO_UKK = '$no_ukk' WHERE NO_REQ_DEL = '$no_req_del'";
			//echo $sqlttm;exit;
			$db->query($sqlttm);
			
		   $sqlttd		 	= "UPDATE PETIKEMAS_CABANG.TTD_BP_CONT SET    
           STATUS_CONT	    ='$updatestatus',    
		   COMMODITY 	    ='$komoditi', 
		   NO_SP2           ='$autono',
		   NO_REQ 		    ='$no_req_del',
		   NO_BL		    ='$no_bl',
		   NO_DO		    ='$no_do',
		   KD_PBM		    ='$kd_consignee',    
		   TUJUAN		    ='USTER'
		   STATUS_CONT		= '04U'
		   WHERE CONT_NO_BP = '$no_cont'  AND BP_ID ='$bp_id'";  
		   //echo $sqlttd; exit;
		   $db->query($sqlttd);
			
			}else{ 
			echo "gagal";exit;
			}
		}
		
		//Insert Ke Uster

		$query_tgl_bongkar ="SELECT RTA AS TGL_BONGKAR
								FROM VOYAGE
								WHERE NO_BOOKING = '$no_booking'
								";
		$result_tgl_bongkar = $db->query($query_tgl_bongkar);
		$row_tgl_bongkar	= $result_tgl_bongkar->fetchRow();
		$tgl_bongkar		= $row_tgl_bongkar["TGL_BONGKAR"];
                
                $history        = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER) 
                                                      VALUES ('$no_cont','$no_req','REQUEST RECEIVING',SYSDATE,'$id_user')";
       
                $db->query($history);
                
		$query_insert	= "INSERT INTO CONTAINER_RECEIVING(NO_CONTAINER, 
														   NO_REQUEST,
														   STATUS,
														   AKTIF,
														   HZ,
														   TGL_BONGKAR,
														   KOMODITI) 
													VALUES('$no_cont', 
														   '$no_req',
														   '$status',
														   'Y',
														   '$berbahaya',
														   '$tgl_bongkar',
														   '$komoditi')";
		   // echo $query_insert;
								
		if($db->query($query_insert))
		{
			echo "OK";
		}
	}
	else{
	echo "EXIST";
	}
}

else if($rec_dari == 'LUAR')
{
	if($status == NULL)
	{
		
			echo "STATUS";
		
	}
	else if($berbahaya == NULL)
	{
		
			echo "BERBAHAYA";
		
	}
	else if(($aktif != "Y") && ($status != NULL) && ($location == "GATO") && ($berbahaya != NULL) )
	{
		$query_tgl_bongkar ="SELECT RTA AS TGL_BONGKAR
								FROM VOYAGE
								WHERE NO_BOOKING = '$no_booking'
								";
		$result_tgl_bongkar = $db->query($query_tgl_bongkar);
		$row_tgl_bongkar	= $result_tgl_bongkar->fetchRow();
		$tgl_bongkar		= $row_tgl_bongkar["TGL_BONGKAR"];
		
		//$id_user        = $_SESSION["LOGGED_STORAGE"];
		$history        = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER) 
                                                      VALUES ('$no_cont','$no_req','REQUEST RECEIVING',SYSDATE,'$id_user')";
       
                $db->query($history);
                
		$query_insert	= "INSERT INTO CONTAINER_RECEIVING(NO_CONTAINER, 
														   NO_REQUEST,
														   STATUS,
														   AKTIF,
														   HZ,
														   NO_BOOKING,
														   TGL_BONGKAR,
														   KOMODITI) 
													VALUES('$no_cont', 
														   '$no_req',
														   '$status',
														   'Y',
														   '$berbahaya',
														   '$no_booking',
														   '$tgl_bongkar',
														   '$komoditi')";
		   // echo $query_insert;
								
		
        
                if($db->query($query_insert))
		{
			echo "OK";
		}
	} else {
		echo "EXIST";
	
	}
}

?>