<?php

$db 			= getDB("storage");

$no_cont		= $_POST["NO_CONT"]; //ok
$no_req_stuff	= $_POST["NO_REQ_STUFF"]; //ok
$stuffing_dari	= $_POST["ASAL_CONT"]; //ok
$stuffing_mode	= $_POST["STUFFING_DARI"]; //ok
$nm_user		= $_SESSION["NAME"]; //ok
$no_req_del		= $_POST["NO_REQ_DEL"]; //ok
$no_req_ict		= $_POST["NO_REQ_ICT"]; //ok
$hz             = $_POST["HZ"]; //ok
$keterangan		= $_POST["KETERANGAN"]; 
$no_seal		= $_POST["NO_SEAL"]; 
$berat			= $_POST["BERAT"]; 
$via            = $_POST["VIA"]; //ok
$komoditi       = $_POST["KOMODITI"]; //ok
$kd_komoditi    = $_POST["KD_KOMODITI"]; //ok
$size			= $_POST["SIZE"]; //ok
$tipe			= $_POST["TYPE"]; //ok
$status			= "MTY";
$no_booking		= $_POST["NO_BOOKING"]; //ok
$no_ukk			= $_POST["NO_UKK"];//ok
$id_user        = $_SESSION["LOGGED_STORAGE"];


//debug($_POST);die;
$db 		= getDB("storage");
$db2 		= getDB("ora");

$no_cont	= $_POST["NO_CONT"]; 
$no_req		= $_POST["NO_REQ"];
$no_req2	= substr($no_req,3);	
$no_req2	= "UD".$no_req2;

$query_no_rec = "SELECT NO_REQUEST_RECEIVING
			FROM PLAN_REQUEST_STUFFING
			WHERE NO_REQUEST = REPLACE('$no_req_stuff','S','P')";
$result_no_rec 	= $db->query($query_no_rec);
$row_no_rec	= $result_no_rec->fetchRow();

$no_req_rec	= $row_no_rec["NO_REQUEST_RECEIVING"];

$row_result2 = substr($no_req_rec,3);
$no_req_del_ict	= "UREC".$row_result2;

$q_getcounter = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
						$r_getcounter = $db->query($q_getcounter);
						$rw_getcounter = $r_getcounter->fetchRow();
						$cur_counter = $rw_getcounter["COUNTER"];
						$cur_booking = $rw_getcounter["NO_BOOKING"];

$cek_nota_stuf = "SELECT NO_NOTA FROM NOTA_STUFFING WHERE NO_REQUEST='$no_req_stuff'";	
						$r_cek_nota_stuf = $db->query($cek_nota_stuf);
						$row_cek_nota_stuf = $r_cek_nota_stuf->fetchRow();	
$nota_stuf = $row_cek_nota_stuf["NO_NOTA"];							


if($nota_stuf != '' or $stuffing_mode == 'AUTO' )	
{					
		if($stuffing_dari == "TPK"){
			//==============================================================Interface Ke ICT=============================================================================//
		//==============================================================================================================================================================================//

			$sqlbp	= "SELECT NO_BP_ID 
						FROM PETIKEMAS_CABANG.TTD_DEL_REQ 
						WHERE CONT_NO_BP = '$no_cont' 
						AND NO_REQ_DEL = '$no_req_del_ict'";
			
			$rsbp	= $db2->query($sqlbp);
			$rowbp	= $rsbp->fetchRow(); 
			$bp_id	= $rowbp["NO_BP_ID"];
			
			$sql_del_ict = "DELETE FROM PETIKEMAS_CABANG.TTD_DEL_REQ WHERE NO_REQ_DEL = '$no_req_del_ict' AND CONT_NO_BP = '$no_cont' AND NO_BP_ID = '$bp_id'  ";
			$db2->query($sql_del_ict);
			
			$query_pmb ="SELECT ptk.KD_PMB_DTL AS KD_PMB_DTL
						FROM PETIKEMAS_CABANG.TTD_CONT_EXBSPL ptk
						WHERE KD_PMB = '$no_req2' AND NO_CONTAINER = '$no_cont'";
			$result_pmb_dtl = $db2->query($query_pmb);
			$row_pmb_dtl = $result_pmb_dtl -> FetchRow();
			$kd_pmb_dtl = $row_pmb_dtl["KD_PMB_DTL"];

			$query_del_exbspl = "DELETE FROM PETIKEMAS_CABANG.TTD_CONT_EXBSPL WHERE KD_PMB = '$no_req2' AND NO_CONTAINER = '$no_cont' AND KD_PMB_DTL = '$kd_pmb_dtl'  ";
			$query_del_peb = "DELETE FROM PETIKEMAS_CABANG.TTD_CONT_PEB WHERE KD_PMB = '$no_req2' AND KD_PMB_DTL = '$kd_pmb_dtl' ";
			$sql_u= "UPDATE PETIKEMAS_CABANG.TTD_BP_CONT SET STATUS_CONT='03' WHERE BP_ID ='$bp_id' AND CONT_NO_BP='$no_cont'";
			//$db2->query($query_del_exbspl);
			//$db2->query($query_del_peb);
			$db2->query($sql_u);

		//==============================================================End Of Interface Ke ICT======================================================================//
		//==============================================================================================================================================================================//
			$update_batal_rec= "UPDATE CONTAINER_RECEIVING SET STATUS_REQ='BATAL', AKTIF='T' WHERE NO_REQUEST ='$no_req_rec' AND NO_CONTAINER='$no_cont'";
			$db->query($update_batal_rec);
			
			$update_batal_stuff= "UPDATE CONTAINER_STUFFING SET STATUS_REQ='BATAL', AKTIF='T' WHERE NO_REQUEST ='$no_req_stuff' AND NO_CONTAINER='$no_cont'";
			$db->query($update_batal_stuff);
			
			$update_batal_plan_stuff= "UPDATE PLAN_CONTAINER_STUFFING SET AKTIF='T' WHERE NO_REQUEST = REPLACE('$no_req_stuff','S','P') AND NO_CONTAINER='$no_cont'";
			$db->query($update_batal_plan_stuff);
			
			/* $update_batal_delivery= "UPDATE CONTAINER_DELIVERY SET STATUS_REQ='BATAL', AKTIF='T' WHERE NO_REQUEST ='$no_req_del' AND NO_CONTAINER='$no_cont'";
			$db->query($update_batal_delivery); */
			
			/* $db->query("UPDATE CONTAINER_STUFFING SET AKTIF='T' WHERE NO_REQUEST ='$no_req' AND NO_CONTAINER='$no_cont'");
			$db->query("UPDATE CONTAINER_RECEIVING SET AKTIF='T' WHERE NO_REQUEST ='$no_req_rec' AND NO_CONTAINER='$no_cont'");
			$db->query("UPDATE CONTAINER_DELIVERY SET AKTIF='T' WHERE NO_REQUEST ='$no_req_del' AND NO_CONTAINER='$no_cont'"); */
			
			$query_insert_history = "INSERT INTO HISTORY_CONTAINER(NO_CONTAINER,
																					   NO_REQUEST,
																					   KEGIATAN,
																					   TGL_UPDATE,
																					   ID_USER, NO_BOOKING, COUNTER, STATUS_CONT
																						)
																				VALUES('$no_cont',
																					   '$no_req',
																					   'BATAL STUFFING',
																					   SYSDATE,
																					   '$id_user', '$cur_booking', '$cur_counter', '$status'
																						)	
																						";	
			$db->query($query_insert_history);
			
			echo "OK";
		}
		else if($stuffing_dari == "DEPO"){
			$update_batal_rec= "UPDATE CONTAINER_RECEIVING SET STATUS_REQ='BATAL', AKTIF='T' WHERE NO_REQUEST ='$no_req_rec' AND NO_CONTAINER='$no_cont'";
			$db->query($update_batal_rec);
			
			$update_batal_stuff= "UPDATE CONTAINER_STUFFING SET STATUS_REQ='BATAL', AKTIF='T' WHERE NO_REQUEST ='$no_req_stuff' AND NO_CONTAINER='$no_cont'";
			$db->query($update_batal_stuff);
			
			if (count($row_no_rec)>0){
				$update_batal_plan_stuff= "UPDATE PLAN_CONTAINER_STUFFING SET AKTIF='T' WHERE NO_REQUEST = REPLACE('$no_req_stuff','S','P') AND NO_CONTAINER='$no_cont'";
				$db->query($update_batal_plan_stuff);
			}
			
			/* $update_batal_delivery= "UPDATE CONTAINER_DELIVERY SET STATUS_REQ='BATAL', AKTIF='T' WHERE NO_REQUEST ='$no_req_del' AND NO_CONTAINER='$no_cont'";
			$db->query($update_batal_delivery); */
			
			/* $db->query("UPDATE CONTAINER_STUFFING SET AKTIF='T' WHERE NO_REQUEST ='$no_req' AND NO_CONTAINER='$no_cont'");
			$db->query("UPDATE CONTAINER_RECEIVING SET AKTIF='T' WHERE NO_REQUEST ='$no_req_rec' AND NO_CONTAINER='$no_cont'");
			$db->query("UPDATE CONTAINER_DELIVERY SET AKTIF='T' WHERE NO_REQUEST ='$no_req_del' AND NO_CONTAINER='$no_cont'"); */
			
			$query_insert_history = "INSERT INTO HISTORY_CONTAINER(NO_CONTAINER,
																					   NO_REQUEST,
																					   KEGIATAN,
																					   TGL_UPDATE,
																					   ID_USER, NO_BOOKING, COUNTER,
																					   STATUS_CONT
																						)
																				VALUES('$no_cont',
																					   '$no_req_stuff',
																					   'BATAL STUFFING',
																					   SYSDATE,
																					   '$id_user', '$cur_booking', '$cur_counter',
																					   '$status'
																						)	
																						";	
			$db->query($query_insert_history);
			
			echo "OK";	
		}
}

// echo ($stuffing_mode);		
?>