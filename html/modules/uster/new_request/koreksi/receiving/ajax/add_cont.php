<?php

$db 		= getDB("storage");

$no_cont	= $_POST["NO_CONT"]; 
$no_req		= $_POST["NO_REQ"]; 
$no_req2	= $_POST["NO_REQ2"]; 
$size		= $_POST["SIZE"]; 
$type		= $_POST["TYPE"]; 
$status		= $_POST["STATUS"]; 
$berbahaya	= $_POST["BERBAHAYA"];
$voyage		= $_POST["VOYAGE"];
$komoditi	= $_POST["KOMODITI"];
$tgl_bongkar= $_POST["TGL_BONGKAR"];
$no_do		= $_POST["NO_DO"];
$no_bl		= $_POST["NO_BL"];
$depo_tujuan = $_POST["DEPO_TUJUAN"];
$id_user        = $_SESSION["LOGGED_STORAGE"];

//print_r($status);die;
//Cek status kontainer, yg bisa direquest hanya yg berstatus GATO dan belum direquest (belum aktif)
$query_cek1		= "SELECT AKTIF
				   FROM CONTAINER_RECEIVING 
				   WHERE NO_CONTAINER = '$no_cont' 
				   ";

$query_rec_dari ="SELECT RECEIVING_DARI
						FROM REQUEST_RECEIVING
						WHERE NO_REQUEST = '$no_req'
						";
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
$location		= $row_cek2["LOCATION"];


//$aktif			= $row_cek["AKTIF"];
//print_r($jum.$location);die;


/*
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
			
			$sqlinsert	= "INSERT INTO TTD_DEL_REQ(NO_REQ_DEL,NO_DO,CONT_NO_BP,SEQ_DEL,NO_SEAL,NO_SP2,NO_BL,NO_BP_ID,ENTRY_BY,ENTRY_DATE,
						   COMMODITY,TGL_STACK,TGL_DISCHARGE,TGL_BERLAKU,HZ,KD_SIZE,KD_TYPE,KD_STATUS_CONT,NO_UKK,KD_PBM)    
		   VALUES(
		   '$no_req_del',
		   '$no_do', 		  		   
		   '$no_cont',  	 	   
		   '$seq',    
		   '".$_POST["NO_SEAL"]."', 
		   '$autono',
		   '$no_bl',
		   '".$_POST["BP_ID"]."',
		   '".$aclist["USERID"]."',
		   SYSDATE,
		   '$komoditi',
		   to_date('".$_POST["TGL_STACK"]."','DD-MM-YYYY'),
		   to_date('".$_POST["TGL_BONGKAR"]."','DD-MM-YYYY'),    
		   to_date('".$_POST["BAYAR_2"]."','DD-MM-YYYY'),  
		   '$berbahaya',
		   '".$_POST['SIZE']."',
		   '".$_POST['TYPE']."',
		   '03U',
		   '".$_POST['NO_UKK']."',
		   '".$_POST['NM_AGEN']."')";        
		   echo $sqlinsert; die;
		   
			if($db->query($sql))
			{		 		
			$sqlttm = "UPDATE TTM_DEL_REQ SET NO_UKK = $NO_UKK WHERE NO_REQ_DEL = '$no_req_del'";
			$db->query($sql);
			
		   $sqlttd		 	= "UPDATE TTD_BP_CONT SET    
           STATUS_CONT	    ='$updatestatus',    
		   COMMODITY 	    ='$komoditi', 
		   NO_SP2           ='$autono',
		   TGL_DELIVERY     =to_date('".$_POST["BAYAR_2"]."','DD-MM-YYYY HH24:MI:SS'),	
		   NO_REQ 		    ='$autobp',
		   NO_BL		    ='".$_POST["NO_BL"]."',
		   NO_DO		    ='".$_POST["NO_DO"]."',
		   KD_PBM		    ='".$_POST["NM_AGEN"]."',    
		   TUJUAN		    ='".$_POST["TUJUAN"]."'
		   WHERE CONT_NO_BP = '$no_cont'  AND BP_ID ='".$_POST["BP_ID"]."'";  
		   $db->query($sql);
			
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
*/

	if($berbahaya == NULL)
	{
		
			echo "BERBAHAYA";
		
	}
	else if($aktif == "Y")
	{
		echo "EXIST";	
	}
	else if($status == NULL)
	{
			echo "STATUS";
	}
	else if(($aktif != "Y") &&  ($berbahaya != NULL) && $status != NULL )
	{
		$query_tgl_bongkar ="SELECT RTA AS TGL_BONGKAR
								FROM VOYAGE
								WHERE NO_BOOKING = '$no_booking'
								";
		$result_tgl_bongkar = $db->query($query_tgl_bongkar);
		$row_tgl_bongkar	= $result_tgl_bongkar->fetchRow();
		$tgl_bongkar		= $row_tgl_bongkar["TGL_BONGKAR"];
		
		
		$query_insert	= "INSERT INTO CONTAINER_RECEIVING(NO_CONTAINER, 
														   NO_REQUEST,
														   STATUS,
														   AKTIF,
														   HZ,
														   NO_BOOKING,
														   TGL_BONGKAR,
														   KOMODITI,
														   DEPO_TUJUAN
														   ) 
													VALUES('$no_cont', 
														   '$no_req',
														   '$status',
														   'Y',
														   '$berbahaya',
														   '$no_booking',
														   '$tgl_bongkar',
														   '$komoditi',
														   '$depo_tujuan')";
		   // echo $query_insert;
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
																			LOCATION, NO_BOOKING, COUNTER)
																	 VALUES('$no_cont',
																			'$size',
																			'$type',
																			'GATO', 'VESSEL_NOTHING', 1)
												";						
						$db->query($query_insert_mstr);
					}
					else {
						$query_counter = "SELECT COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
						$r_counter = $db->query($query_counter);
						$rw_counter = $r_counter->fetchRow();
						$last_counter = $rw_counter["COUNTER"]+1;
						$q_update_book2 = "UPDATE MASTER_CONTAINER SET NO_BOOKING = 'VESSEL_NOTHING', COUNTER = '$last_counter' 
						WHERE NO_CONTAINER = '$no_cont'";
						$db->query($q_update_book2);
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
						$db->query($query_insert_history);							
		if($db->query($query_insert))
		{
			echo "OK";
		}
	}

	//echo "$aktif | $berbahaya | $status";

?>