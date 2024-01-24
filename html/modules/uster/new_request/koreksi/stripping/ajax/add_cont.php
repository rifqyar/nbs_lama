<?php

$db 			= getDB("storage");

$nm_user	= $_SESSION["NAME"];
$id_user	= $_SESSION["LOGGED_STORAGE"];
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
$bp_id			= $_POST["BP_ID"];
$kd_consignee	= $_POST["KD_CONSIGNEE"];
$depo_tujuan	= $_POST["DEPO_TUJUAN"];
$sp2			= $_POST["SP2"];
$after_strip	= $_POST["AFTER_STRIP"];
$asal_cont	= $_POST["ASAL_CONT"];
$no_booking	= $_POST["NO_BOOKING"];

$no_req_rec	= substr($no_req2,4);	
$no_req_rec	= "REC".$no_req_rec;

//debug($_POST);
//die();
//print_r($status);die;
//Cek status kontainer, yg bisa direquest hanya yg berstatus di Lapangan dan sudah Gate In
//debug($_POST);
						
/*$query_cek2		= "SELECT LOCATION 
				   FROM MASTER_CONTAINER 
				   WHERE NO_CONTAINER = '$no_cont' ";			
				   
$result_cek2	= $db->query($query_cek2);
$row_cek2		= $result_cek2->fetchRow();
$location		= $row_cek2["LOCATION"];

*/
//$aktif			= $row_cek["AKTIF"];
//print_r($jum.$location);die;

//if($location == "GATO")
//{
	//berarti GATI atau sudah placement
	//cek status aktif di CONTAINER_RECEIVING
	
	//asd
//---------------------------------------------------Interface Ke ICT----------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------

	/*	$sql 			= "SELECT NO_REQ_DEL FROM PETIKEMAS_CABANG.TTM_DEL_REQ WHERE NO_REQ_DEL = '$no_req2'";
		$sqldb 			= $db->query($sql);
		$row_sql		= $sqldb->fetchRow();
		$no_req_del		= $row_sql["NO_REQ_DEL"];
		
		//echo $no_req_del;exit;
		
		//debug($no_req_del);die;
		if ($no_req_del != NULL)
		{
			$result_seq = "SELECT MAX(SEQ_DEL) as SEQ from PETIKEMAS_CABANG.TTD_DEL_REQ where  NO_REQ_DEL ='$no_req_del'";
			$rs 		= $db->query( $result_seq );
			$row		= $rs->FetchRow() ;			
			$seq	 	= $row['SEQ']+1; 
			
			$sqlinsert	= "INSERT INTO PETIKEMAS_CABANG.TTD_DEL_REQ(NO_REQ_DEL,NO_DO,CONT_NO_BP,SEQ_DEL,NO_SP2,NO_BL,NO_BP_ID,ENTRY_BY,ENTRY_DATE,
						   COMMODITY,TGL_STACK,TGL_DISCHARGE,HZ,KD_SIZE,KD_TYPE,KD_STATUS_CONT,NO_UKK,KD_PBM)    
		   VALUES(
		   '$no_req_del',
		   '$no_do', 		  		   
		   '$no_cont',  	 	   
		   '$seq',    
		   '$sp2',
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
		   //echo $sqlinsert;// exit;
		   
			if($db->query($sqlinsert))
			{		 		
			$sqlttm = "UPDATE PETIKEMAS_CABANG.TTM_DEL_REQ SET NO_UKK = '$no_ukk' WHERE NO_REQ_DEL = '$no_req_del'";
			//echo $sqlttm;exit;
			$db->query($sqlttm);
			
		   $sqlttd		 	= "UPDATE PETIKEMAS_CABANG.TTD_BP_CONT SET    
           STATUS_CONT	    ='04U',    
		   COMMODITY 	    ='$komoditi', 
		   NO_SP2           ='$autono',
		   NO_REQ 		    ='$autobp',
		   NO_BL		    ='$no_bl',
		   NO_DO		    ='$no_do',
		   KD_PBM		    ='$kd_consignee',    
		   TUJUAN		    ='USTER'
		   WHERE CONT_NO_BP = '$no_cont'  AND BP_ID ='$bp_id'";  
		   //echo $sqlttd; //exit;
		   */
		//if($db->query($sqlttd))
		//{
				
//----------------------------------------------End Of Interface Ke ICT-------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------				
	
		//Insert Uster
				/*$query_tgl_bongkar ="SELECT RTA AS TGL_BONGKAR
							FROM VOYAGE
							WHERE NO_BOOKING = '$no_booking'
							";
				$result_tgl_bongkar = $db->query($query_tgl_bongkar);
				$row_tgl_bongkar	= $result_tgl_bongkar->fetchRow();
				$tgl_bongkar_		= $row_tgl_bongkar["TGL_BONGKAR"];
			
				$query_insert_rec	= "INSERT INTO CONTAINER_RECEIVING(NO_CONTAINER, 
													   NO_REQUEST,
													   STATUS,
													   AKTIF,
													   HZ,
													   TGL_BONGKAR,
													   KOMODITI,
													   DEPO_TUJUAN) 
												VALUES('$no_cont', 
													   '$no_req_rec',
													   '$status',
													   'Y',
													   '$berbahaya',
														TO_DATE('$tgl_bongkar','dd-mm-yyyy'),
													   '$komoditi',
													   '$depo_tujuan')";
				//echo $query_insert;exit;			
				
				$query_cek_cont			= "SELECT NO_CONTAINER 
										FROM  MASTER_CONTAINER
										WHERE NO_CONTAINER ='$no_cont'
										";
				$result_cek_cont = $db->query($query_cek_cont);
				$row_cek_cont	 = $result_cek_cont->fetchRow();
				$cek_cont		 = $row_cek_cont["NO_CONTAINER"];
				
				if($cek_cont == NULL)
				{
					$query_insert_mstr	= "INSERT INTO MASTER_CONTAINER(NO_CONTAINER,
																		SIZE_,
																		TYPE_,
																		LOCATION)
																 VALUES('$no_cont',
																		'$size',
																		'$type',
																		'GATO')
											";						
					$db->query($query_insert_mstr);
				}*/													   
		// ------------------------------------------------------------------------------
		
		//if($db->query($query_insert_rec))
		//{
			// Insert Plan Container Stripping ----------------------------------------------
			$query_insert_strip	= "INSERT INTO PLAN_CONTAINER_STRIPPING(NO_CONTAINER, 
															   NO_REQUEST,
															   AKTIF,
															   TGL_BONGKAR,
															   HZ,
															   ID_YARD,
															   VOYAGE,
															   AFTER_STRIP,
															   COMMODITY,
															   UKURAN,
															   TYPE, ASAL_CONT, NO_BOOKING
															  ) 
														VALUES('$no_cont', 
															   '$no_req',
															   'Y',
															   TO_DATE('$tgl_bongkar','dd-mm-yyyy'),
															   '$berbahaya',
															   '$depo_tujuan',
															   '$voyage',
															   '$after_strip',
															   '$komoditi',
															   '$size',
															   '$type',
															   '$asal_cont', '$no_booking'
															   )";
			// echo $query_insert;
			// ----------------------------------------------------------------------------
			/* $q_getcounter2 = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
			$r_getcounter2 = $db->query($q_getcounter2);
			$rw_getcounter2 = $r_getcounter2->fetchRow();
			$cur_counter2 = $rw_getcounter2["COUNTER"];
			$cur_booking2 = $rw_getcounter2["NO_BOOKING"];	 */						
			if($db->query($query_insert_strip))
			{																   
					
					$history_del        = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER) 
																	  VALUES ('$no_cont','$no_req','PLAN REQUEST STRIPPING',SYSDATE,'$id_user')";
					$db->query($history_del);
				echo "OK";
			}
		//}
		//echo "XX";
	//}
	//echo "CC";
			//}
		//	echo "AA";
	//	}
	//echo "AS";
/*}
else 
{
	echo "OUTSIDE";	
}*/
?>