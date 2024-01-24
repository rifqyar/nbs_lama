<?php

$db 		= getDB("storage");
$db2 		= getDB("ora");
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
$blok_tpk		= $_POST["BLOK_TPK"];
$slot_tpk		= $_POST["SLOT_TPK"];
$row_tpk		= $_POST["ROW_TPK"];
$tier_tpk		= $_POST["TIER_TPK"];
$sp2			= $_POST["SP2"];
$NO_BOOKING			= $_POST["NO_BOOKING"];

//echo $blok_tpk;exit;
//
//echo $depo_tujuan;exit;

//print_r($status);die;
//Cek status kontainer, yg bisa direquest hanya yg berstatus GATO dan belum direquest (belum aktif)
$query_cek1		= "SELECT AKTIF
				   FROM CONTAINER_RECEIVING 
				   WHERE NO_CONTAINER = '$no_cont' 
				   ";

	/*				
	$query_rec_dari ="SELECT RECEIVING_DARI
						FROM REQUEST_RECEIVING
						WHERE NO_REQUEST = '$no_req'
						";
	$result_rec_dari = $db->query($query_rec_dari);
	$row_rec_dari = $result_rec_dari->fetchRow();
	$rec_dari = $row_rec_dari["RECEIVING_DARI"];
	*/
				   
$result_cek1	= $db->query($query_cek1);
$row_cek1		= $result_cek1->fetchRow();
//$jum			= $row_cek1["JUM"];
$aktif			= $row_cek1["AKTIF"];
			

			
$query_cek2		= "SELECT LOCATION 
				   FROM MASTER_CONTAINER 
				   WHERE NO_CONTAINER = '$no_cont' ";			
$result_cek2	= $db->query($query_cek2);
$row_cek2		= $result_cek2->fetchRow();
$location		= $row_cek2["LOCATION"];


//$aktif			= $row_cek["AKTIF"];
//print_r($jum.$location);die;


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
//---------------------------------------------------Interface Ke ICT------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------------------------

		$sql 			= "SELECT NO_REQ_DEL FROM PETIKEMAS_CABANG.TTM_DEL_REQ WHERE NO_REQ_DEL = '$no_req2'";
		$sqldb 			= $db2->query($sql);
		$row_sql		= $sqldb->fetchRow();
		$no_req_del		= $row_sql["NO_REQ_DEL"];
	
			if($berbahaya == 'N')
			{$hz1 = 'T';}else{$hz1 = 'Y';}
			if($komoditi == '')
			{$komoditi1 = 'EMPTY';}
		
		if ($no_req_del != NULL)
		{
			$result_seq = "SELECT MAX(SEQ_DEL) as SEQ from PETIKEMAS_CABANG.TTD_DEL_REQ where  NO_REQ_DEL ='$no_req_del'";
			$rs 		= $db2->query( $result_seq );
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
						   'USTER',
						   SYSDATE,
						   '$komoditi1',
						   to_date('".$tgl_stack."','DD-MM-YYYY'),
						   to_date('".$tgl_bongkar."','DD-MM-YYYY'),  
						   '$hz1',
						   '$size',
						   '$type',
						   '$status',
						   '$no_ukk',
						   '$kd_consignee')"; 

			
		   //echo $sqlinsert;// exit;
		   
				 		
   		/*	$sqlttm = "UPDATE PETIKEMAS_CABANG.TTM_DEL_REQ SET NO_UKK = '$no_ukk' WHERE NO_REQ_DEL = '$no_req_del'";
			//echo $sqlttm;exit;
			$db->query($sqlttm); */
			
		   $sqlttd		 	= "UPDATE PETIKEMAS_CABANG.TTD_BP_CONT SET    
							   STATUS_CONT	    ='04',    
							   COMMODITY 	    ='$komoditi', 
							   NO_SP2           ='$sp2',
							   NO_REQ 		    ='$no_req_del',
							   NO_BL		    ='$no_bl',
							   NO_DO		    ='$no_do',
							   KD_PBM		    ='$kd_consignee',    
							   TUJUAN		    ='USTER'
							   WHERE CONT_NO_BP = '$no_cont'  AND BP_ID ='$bp_id'";  
		   //echo $sqlttd; //exit;
		   
				
				
//----------------------------------------------End Of Interface Ke ICT----------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------------------------				
				
			//Insert Uster

		
			$query_insert	= "INSERT INTO CONTAINER_RECEIVING(NO_CONTAINER, 
												   NO_REQUEST,
												   STATUS,
												   AKTIF,
												   HZ,
												   TGL_BONGKAR,
												   KOMODITI,
												   DEPO_TUJUAN,
												   BLOK_TPK,
												   SLOT_TPK,
												   ROW_TPK,
												   TIER_TPK) 
											VALUES('$no_cont', 
												   '$no_req',
												   '$status',
												   'Y',
												   '$berbahaya',
												   TO_DATE('$tgl_bongkar','dd-mm-yyyy'),
												   '$komoditi',
												   '$depo_tujuan',
												   '$blok_tpk',
												   '$slot_tpk',
												   '$row_tpk',
												   '$tier_tpk')";
			//echo $query_insert;exit;	
			
			
			$query_cek_cont			= "SELECT NO_CONTAINER 
									FROM  MASTER_CONTAINER
									WHERE NO_CONTAINER ='$no_cont'
									";
			$result_cek_cont = $db->query($query_cek_cont);
			$row_cek_cont	 = $result_cek_cont->fetchRow();
			$cek_cont		 = $row_cek_cont["NO_CONTAINER"];
			
			if($cek_cont == NULL){			
				
				$query_insert_mstr	= "INSERT INTO MASTER_CONTAINER(NO_CONTAINER,
																	SIZE_,
																	TYPE_,
																	LOCATION, NO_BOOKING, COUNTER)
															 VALUES('$no_cont',
																	'$size',
																	'$type',
																	'GATO',
																	'$NO_BOOKING',
																	1)
										";						
				$db->query($query_insert_mstr);
			}
			else{
				$query_counter = "SELECT COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
				$r_counter = $db->query($query_counter);
				$rw_counter = $r_counter->fetchRow();
				$last_counter = $rw_counter["COUNTER"]+1;
				$q_update_book = "UPDATE MASTER_CONTAINER SET NO_BOOKING = '$NO_BOOKING', COUNTER = '$last_counter' WHERE NO_CONTAINER = '$no_cont'";
				$db->query($q_update_book);
			}

			//Insert ke history container
				$q_getcounter = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
				$r_getcounter = $db->query($q_getcounter);
				$rw_getcounter = $r_getcounter->fetchRow();
				$cur_counter = $rw_getcounter["COUNTER"];
				$cur_booking = $rw_getcounter["NO_BOOKING"];
				$query_insert_history = "INSERT INTO HISTORY_CONTAINER(NO_CONTAINER,
																	   NO_REQUEST,
																	   KEGIATAN,
																	   TGL_UPDATE,
																	   ID_USER,
																	   ID_YARD,
																	   STATUS_CONT, NO_BOOKING, COUNTER
																		)
																VALUES('$no_cont',
																	   '$no_req',
																	   'REQUEST RECEIVING',
																	   SYSDATE,
																	   '$id_user',
																	   '$depo_tujuan',
																	   '$status', '$cur_booking', '$cur_counter'
																		)	
																		";	
					

			
				
			
			if($db->query($query_insert))
			{
				if($db2->query($sqlttd))
				{
					if($db2->query($sqlinsert))
					{
						if($db->query($query_insert_history))
						{
							echo "OK";
						}
						else
						{
							echo "gagal INSERT HISTORY";exit;
						}
					}
					else
					{
						echo "gagal CONTAINER_RECEIVING";exit;
					}
				}
				else
				{
					echo "gagal TTD_BP_CONT";exit;
				}
			}
			else
			{
				echo "gagal TTD_DEL_REQ";exit;
			}
			
			
					
				//}
			//}
		}
		else
		{ 
			echo "gagal awal";exit;
		}
		
								
		
	}
	else{
	echo "EXIST";
	}



?>