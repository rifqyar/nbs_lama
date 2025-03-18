<?php
if($_SESSION["LOGGED_STORAGE"] == NULL)
{
	echo "LOGIN_CLEAR";
	exit();
}

$db 			= getDB("storage");
$db2 			= getDB("ora");

$no_cont		= $_POST["NO_CONT"]; //ok
$no_req_stuff	= $_POST["NO_REQ_STUFF"]; //ok
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
$status			= "FCL";
$no_booking		= $_POST["NO_BOOKING"]; //ok
$no_ukk			= $_POST["NO_UKK"];//ok
$remark_sp2			= $_POST["REMARK_SP2"];//ok
$id_user        = $_SESSION["LOGGED_STORAGE"];
$id_yard		= $_SESSION["IDYARD_STORAGE"];
$catatan        = $_POST["catatan"];
if(isset($_POST["alat"])){
	$alat = 1;
}
else{
	$alat = 0;
} 


$cekexist = $db->query("select aktif, tgl_realisasi from container_stuffing where no_request = '$no_cont' and no_container = '$no_req_stuff'");
$rowexist = $cekexist->fetchRow();

if ($rowexist["AKTIF"] == 'T' && $rowexist["TGL_REALISASI"] != NULL) {
	echo "EXECUTED";
	exit();
}

//debug($_POST);die;
//cek closing time uster
/* $qkosong = "select TO_CHAR(CLOSE,'DD/Mon/YYYY') TGL_JAM_CLOSE ,TO_CHAR(SYSDATE,'DD/Mon/YYYY') CURRENT_TIME from master_booking_time where no_booking  = '$no_booking'";
$rkosong = $db->query($qkosong);
$rkos = $rkosong->fetchRow();
$closeuster = $rowclosing['TGL_JAM_CLOSE'];
$current_time = $rowclosing['CURRENT_TIME']; */

//if($rkosong->RecordCount() == 0){
	$qclosing = "select a.NM_AGEN, a.KD_AGEN, a.KD_KAPAL, a.NM_KAPAL,a.VOYAGE_IN,a.VOYAGE_OUT,a.NO_UKK, a.NO_BOOKING,  TO_CHAR(a.tgl_jam_berangkat,'dd/mm/rrrr') TGL_BERANGKAT, 
                    a.TGL_JAM_BERANGKAT ETD, a.TGL_JAM_TIBA ETA, A.DOC_CLOSING_DATE_DRY, TGL_SKRG
                    from v_booking_stack_tpk a 
                    where TGL_SKRG <= A.DOC_CLOSING_DATE_DRY
                    and 
                    a.NO_BOOKING LIKE '%$no_booking%'";
	$rclosing = $db->query($qclosing);
	$rowclosing = $rclosing->RecordCount();
	
//}
if($rowclosing == 0)
{
	echo "CLOSING_TIME";

} else 
{
	//Cek posisi container GATI, GATO, IN_YARD
	$query_posisi	="SELECT LOCATION FROM MASTER_CONTAINER
						WHERE NO_CONTAINER = '$no_cont'";
	$result_posisi	= $db->query($query_posisi);
	$row_posisi		= $result_posisi->fetchRow();
	$posisi			= $row_posisi["LOCATION"];
	
	if($posisi == 'IN_YARD')
	{
	//cek apakah container tersebut status stuffingnya aktif
	$query_cek2		= "SELECT COUNT(1) AS JUM FROM CONTAINER_STUFFING WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req_stuff' AND AKTIF = 'Y'";
	$result_cek2	= $db->query($query_cek2);
	$row_cek2		= $result_cek2->fetchRow();
		if($row_cek2["JUM"] > 0)
		{ 
			$q_perp = "SELECT STATUS_REQ, STUFFING_DARI FROM REQUEST_STUFFING WHERE NO_REQUEST = '$no_req_stuff'";
			$r_prep = $db->query($q_perp);
			$rpre = $r_prep->fetchRow();
			if($rpre["STATUS_REQ"] == 'PERP'){
				$query_cek1		= "SELECT 
                                        CASE 
                                        WHEN TO_DATE(SYSDATE,'DD-MM-RRRR') <= END_STACK_PNKN THEN 'OK'
                                        ELSE 'NO'
                                        END AS STATUS 
                                FROM CONTAINER_STUFFING
                                WHERE NO_REQUEST = '$no_req_stuff' AND NO_CONTAINER = '$no_cont'";
			}
			else {
			//cek apakah container tersebut masa stuffingnya masih berlaku
				if($remark_sp2  == 'Y'){
					$query_cek1		= "SELECT 
                                        CASE 
                                        WHEN TO_DATE(SYSDATE,'DD-MM-RRRR') <= END_STACK_PNKN THEN 'OK'
                                        ELSE 'NO'
                                        END AS STATUS 
                                FROM CONTAINER_STUFFING
                                WHERE NO_REQUEST = '$no_req_stuff' AND NO_CONTAINER = '$no_cont'";				
				}
				else {
						$query_cek1		= "SELECT 
                                        CASE 
                                        WHEN TO_DATE(SYSDATE,'DD-MM-RRRR') <= START_PERP_PNKN THEN 'OK'
                                        ELSE 'NO'
                                        END AS STATUS 
                                FROM CONTAINER_STUFFING
                                WHERE NO_REQUEST = '$no_req_stuff' AND NO_CONTAINER = '$no_cont'";
				}
			}
			$result_cek1	= $db->query($query_cek1);
			$row_cek1		= $result_cek1->fetchRow();
			// $row_perp 		= $row_cek1['STATUS_REQ'];
			$row_perp 		= $rpre['STATUS_REQ'];

			$stuf_dari 		= $rpre['STUFFING_DARI'];

			if ($stuf_dari != 'AUTO') {
				$cek_nota 	= $db->query("SELECT LUNAS FROM NOTA_STUFFING WHERE NO_REQUEST = '$no_req_stuff'");
				$rceknota	= $cek_nota->fetchRow();
				if ($rceknota["LUNAS"] != 'YES') {
						echo "NOTA_BLM_LUNAS";
						exit();
				}
			}
			else {
				$cek_nota_batal = $db->query("SELECT LUNAS, BIAYA FROM REQUEST_BATAL_MUAT, NOTA_BATAL_MUAT
									WHERE REQUEST_BATAL_MUAT.NO_REQUEST = NOTA_BATAL_MUAT.NO_REQUEST(+) AND
									NO_REQ_BARU = '$no_req_stuff'");
				$rcekbatal		= $cek_nota_batal->fetchRow();
				if ($rcekbatal["LUNAS"] != 'YES' && $rcekbatal["BIAYA"] == 'Y') {
					echo "NOTA_BLM_LUNAS";
					exit();
				}
			}
			// =========================================== SIMOP ==============================================//
			// $sqlcek3 = "SELECT TO_CHAR (TGL_JAM_CLOSE,'DD-MM-YYYY HH24:MI:SS')TGL_JAM_CLOSE  FROM PETIKEMAS_CABANG.TTH_CONT_BOOKING WHERE NO_BOOKING = '$no_booking'";
			
			// $rs3 = $db->query($sqlcek3);
			// $rowcek3 = $rs3 -> FetchRow();
			// $datedoc = $rowcek3["TGL_JAM_CLOSE"];
			
			// $sqldate = "SELECT TO_CHAR (SYSDATE,'DD-MM-YYYY HH24:MI:SS')HARINI FROM DUAL";
			
			// $rsdate = $db->query($sqldate);
			// $rowdate = $rsdate -> FetchRow();
			// $sysdate = $rowdate["HARINI"];
			
			
			// //if ($datedoc  <= $sysdate)
			// //{
			// //	echo "CLOSING_TIME"; exit;
			// //}else
			// //{
					
			// $sqlsize  	= "SELECT KD_SIZE FROM PETIKEMAS_CABANG.MST_SIZE_CONT WHERE SIZE_CONT = '$size'"; 	
			// $rssize		= $db->query($sqlsize);
			// $sizetpk_	= $rssize->fetchRow();
			// $sizetpk	= $sizetpk_["KD_SIZE"];
			// //echo $sqlsize;exit;
			
			// $sqltype	= "SELECT STY_CODE FROM PETIKEMAS_CABANG.MST_TYPE_CONT WHERE STY_NAME = '$tipe'";
			// $rstype		= $db->query($sqltype);
			// $typetpk_	= $rstype->fetchRow();
			// $typetpk	= $typetpk_["STY_CODE"];
			// //echo $typetpk;
			
			// $sqljenis 	= "SELECT KD_JENIS_PEMILIK FROM PETIKEMAS_CABANG.MST_JENIS_PEMILIK WHERE NM_JENIS_PEMILIK = '$status'";
			// $rsjenis	= $db->query($sqljenis);
			// $jenistpk_	= $rsjenis->fetchRow();
			// $jenistpk	= $jenistpk_["KD_JENIS_PEMILIK"];
			// //}
			
			// if($typetpk=="07"){
						// $sqlm = "SELECT * FROM PETIKEMAS_CABANG.V_DATA_BOOKING_RFR WHERE NO_BOOKING='$no_booking'";					
					// }else{
						// $sqlm = "SELECT * FROM PETIKEMAS_CABANG.V_DATA_BOOKING WHERE NO_BOOKING='$no_booking'";
					// }			
			// if($jenistpk=='3'){
						// $sqlm = "SELECT * FROM PETIKEMAS_CABANG.V_DATA_BOOKING_MTY WHERE NO_BOOKING='$no_booking'";
					// } 	
										
			// //		echo $sqlm; exit;
					// $rsm = $db->query($sqlm);
					// if($rsm->RecordCount()<0){
						// echo "Reefer Atau Empty Tidak Ada Dalam Booking Stack"; exit;
					// }
					// else 
					// {
						
						// $rsbook  = $rsm->FetchRow();
						// $blk_id = $rsbook["ARE_BLOK"];
						
						// $sqlxx  = "SELECT ARE_ID,ARE_TIER,ARE_BLOK,ARE_ROW,ARE_SLOT FROM PETIKEMAS_CABANG.V_MST_YARD_EX WHERE KD_STATUS_CY='0' AND KD_CABANG='05' AND 
								   // ARE_BLOK='".$rsbook["ARE_BLOK"]."' AND  ARE_ROW BETWEEN '".$rsbook["M_ROW"]."' AND '".$rsbook["S_ROW"]."' 
								   // AND  ARE_SLOT BETWEEN '".$rsbook["M_SLOT"]."' AND '".$rsbook["S_SLOT"]."' AND  ARE_TIER BETWEEN '".$rsbook["M_TIER"]."' 
								   // AND '".$rsbook["S_TIER"]."' ORDER BY ARE_TIER,ARE_BLOK,ARE_ROW,ARE_SLOT ASC";
								   
							// //echo $sqlxx; exit;
								   
							// if($rsx = $db->selectLimit( $sqlxx,0,1 ))
							// {
								
							// $rscon  = $rsx->FetchRow();
							// $yp     = $rscon["ARE_ID"];
							// $slott  = $rscon["ARE_SLOT"] + 1;
							
							// if($sizetpk !='1')
								// {
									// $sql2 = "SELECT ARE_ID FROM PETIKEMAS_CABANG.V_MST_YARD_EX WHERE ARE_TIER='".$rscon["ARE_TIER"]."' 
									// AND ARE_BLOK='".$rscon["ARE_BLOK"]."' AND ARE_ROW='".$rscon["ARE_ROW"]."' AND ARE_SLOT='".$slott."' AND KD_CABANG='05'";
									// if( $rss2 = $db->selectLimit( $sql2,0,1 ) )
									// {
									// $rscon2   = $rss2->FetchRow();
									// $yp2      = $rscon2["ARE_ID"];
									// }
								// }
							
							
							// $sqlxxx = "UPDATE PETIKEMAS_CABANG.MST_YARD_CONT_LAPANGAN_EX SET KD_STATUS_CY='1' WHERE ARE_ID='".$yp."'";
							// $db->startTransaction();
							// $xxxxxx = $db->query($sqlxxx);
							// $db->endTransaction();
							
							// $sqlxxzz = "UPDATE PETIKEMAS_CABANG.MST_YARD_CONT_LAPANGAN_EX SET KD_STATUS_CY='1' WHERE ARE_ID='".$yp2."'";
							// $db->startTransaction();
							// $xxxxzz = $db->query($sqlxxzz);
							// $db->endTransaction();	
							
							// if($yp==''){
								// $yp = 'XXXX';
							// }
							
						// }
					// }

		// //utang are_id,are_id2,temp,class			
					// $sqldet   = "INSERT INTO PETIKEMAS_CABANG.TTD_CONT_EXBSPL(KD_PMB_DTL,KD_PMB,NO_CONTAINER,KD_SIZE,KD_TYPE_CONT,KD_JENIS_PEMILIK,KD_COMMODITY,GROSS,NO_SEAL,KETERANGAN,STATUS_PMB_DTL,STATUS_KARTU,USER_ID,VIA,HZ,STATUS_PP,ARE_ID,ARE_ID2) 
							  // VALUES
							 // (
								// PETIKEMAS_CABANG.SEQ_TTD_CONT_EXBSPL.NEXTVAL,
								// '$no_req_ict',						
								// '$no_cont',
								// '$sizetpk',
								// '$typetpk',
								// '$jenistpk',
								// '$kd_komoditi',
								// '$berat',
								// '$no_seal',
								// '$keterangan',
								// '0US',
								// '0',
								// '$nm_user',
								// '1',
								// '$hz',
								// 'T',
								// '$yp',
								// '$yp2'
								// )";
							// //echo $sqldet;exit();
							
							
								// $sqlc   = "SELECT STATUS_CONT_EXBSPL FROM PETIKEMAS_CABANG.TTH_CONT_EXBSPL WHERE KD_CABANG = '05' AND KD_PMB = '".$no_req_ict."' ";
								// $rsc	= $db->query($sqlc);
								// $tthc_	= $rsc->fetchRow();
								// $tthc   = $tthc_["STATUS_CONT_EXBSPL"];
								
							
				
			// if($tthc=="S")
			// {
			// $sqlstat = "UPDATE PETIKEMAS_CABANG.TTH_CONT_EXBSPL SET STATUS_CONT_EXBSPL = '0' WHERE KD_PMB = '".$no_req_ict."' ";
			// $db->query($sqlstat);
			// }
							
			
			// ===========================================  END SIMOP ==============================================//
			

			
			
			if($row_cek1["STATUS"] == "OK")
			{	
				
					//insert ke container delivery
					// mengetahui tanggal start_stack
					$query_cek1		= "SELECT tes.NO_REQUEST, 
												CASE SUBSTR(KEGIATAN,9)
													WHEN 'RECEIVING' THEN (SELECT CONCAT('RECEIVING_',a.RECEIVING_DARI) FROM request_receiving a WHERE a.NO_REQUEST = tes.NO_REQUEST)
													ELSE SUBSTR(KEGIATAN,9)
												END KEGIATAN FROM (SELECT TGL_UPDATE, NO_REQUEST,KEGIATAN FROM history_container WHERE no_container = '$no_cont' and kegiatan IN ('REQUEST RECEIVING','REQUEST STRIPPING','REQUEST STUFFING','REQUEST RELOKASI')) tes
												WHERE tes.TGL_UPDATE=(SELECT MAX(TGL_UPDATE) FROM history_container WHERE no_container = '$no_cont' and kegiatan IN ('REQUEST RECEIVING','REQUEST STRIPPING','REQUEST STUFFING','REQUEST RELOKASI'))";
					$result_cek1		= $db->query($query_cek1);
					$row_cek1		= $result_cek1->fetchRow();
					$no_request		= $row_cek1["NO_REQUEST"];
					$kegiatan		= $row_cek1["KEGIATAN"];
					
					IF ($kegiatan == 'RECEIVING_LUAR') {
							$query_cek1		= "SELECT SUBSTR(TO_CHAR(b.TGL_IN, 'MM/DD/YYYY'),1,10) START_STACK FROM GATE_IN b WHERE b.NO_CONTAINER = '$no_cont' AND b.NO_REQUEST = '$no_request'";
							$result_cek1	= $db->query($query_cek1);
							$row_cek1		= $result_cek1->fetchRow();
							$start_stack	= $row_cek1["START_STACK"];
							$asal_cont 		= 'LUAR';
					} ELSE IF ($kegiatan == 'RECEIVING_TPK') {
							$query_cek1		= "SELECT TGL_BONGKAR START_STACK FROM container_receiving WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_request'";
							$result_cek1	= $db->query($query_cek1);
							$row_cek1		= $result_cek1->fetchRow();
							$start_stack	= $row_cek1["START_STACK"];
							$asal_cont 		= 'TPK';
					} ELSE IF ($kegiatan == 'STUFFING') {
							$query_cek1		= "SELECT SUBSTR(TO_CHAR(TGL_REALISASI,'MM/DD/YYYY'),1,10) START_STACK FROM container_stuffing WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_request'";
							$result_cek1	= $db->query($query_cek1);
							$row_cek1		= $result_cek1->fetchRow();
							$start_stack	= $row_cek1["START_STACK"];
							$asal_cont 		= 'DEPO';
					} ELSE IF ($kegiatan == 'STRIPPING') {
							$query_cek1		= "SELECT SUBSTR(TO_CHAR(TGL_REALISASI,'MM/DD/YYYY'),1,10) START_STACK FROM container_stripping WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_request'";
							$result_cek1	= $db->query($query_cek1);
							$row_cek1		= $result_cek1->fetchRow();
							$start_stack	= $row_cek1["START_STACK"];
							$asal_cont 		= 'DEPO';
					}
					
					// $query_insert   = "INSERT INTO CONTAINER_DELIVERY(NO_CONTAINER, NO_REQUEST, 
																		// STATUS, AKTIF, 
																		// KELUAR,HZ, 
																		// KOMODITI,KETERANGAN,
																		// NO_SEAL,BERAT,
																		// VIA, ID_YARD, 
																		// NOREQ_PERALIHAN,
																		// START_STACK, 
																		// ASAL_CONT) 
															  // VALUES('$no_cont', '$no_req_del', 
																	 // '$status','Y',
																	 // 'N','$hz',
																	 // '$komoditi','$keterangan',
																	 // '$no_seal','$berat',
																	 // '$via','$id_yard',
																	 // '$no_request',
																	 // TO_DATE('$start_stack','DD/MON/RR'),
																	 // '$asal_cont')";
					
					
					//echo $query_insert;die;	
					//$db->query($query_insert);die;
					$q_getcounter4 = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont' ORDER BY COUNTER DESC";
					$r_getcounter4 = $db->query($q_getcounter4);
					$rw_getcounter4 = $r_getcounter4->fetchRow();
					$cur_booking4  = $rw_getcounter4["NO_BOOKING"];
					$cur_counter4  = $rw_getcounter4["COUNTER"];
					
					$history        = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD, STATUS_CONT, NO_BOOKING, COUNTER, WHY) 
																  VALUES ('$no_cont','$no_req_stuff','REALISASI STUFFING',SYSDATE,'$id_user','$id_yard','$status', '$no_booking', '$cur_counter4','$catatan')";
				   

				//update status aktif
					$query_update		= "UPDATE CONTAINER_STUFFING SET AKTIF = 'T', TGL_REALISASI = SYSDATE, ID_USER_REALISASI = '$id_user', PEMAKAIAN_ALAT = '$alat' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req_stuff'";
					$query_update_plan	= "UPDATE PLAN_CONTAINER_STUFFING SET AKTIF = 'T' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = REPLACE('$no_req_stuff','S','P')";
					$db->query($query_update_plan);
					
					$req_receiving ="SELECT NO_REQUEST_RECEIVING FROM REQUEST_STUFFING WHERE NO_REQUEST = '$no_req_stuff'";
					$req_rec = $db->query($req_receiving);
					$no_req_r = $req_rec->fetchRow();
					$no_rr = $no_req_r["NO_REQUEST_RECEIVING"];
					$aktif_rec = "SELECT AKTIF FROM CONTAINER_RECEIVING WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_rr'";
					$raktif_rec = $db->query($aktif_rec);
					$aktifa = $raktif_rec->fetchRow();
					$recaktif = $aktifa["AKTIF"];
					if($recaktif == "Y"){
						$db->query("UPDATE CONTAINER_RECEIVING WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_rr'");
					}
					
					
				//update status aktif kartu yang masih Y
				// $query_update2	= "UPDATE KARTU_STUFFING SET AKTIF = 'T' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req_stuff'";
				
					
				// if($db->query($sqldet))
				// {
					// if($jenistpk =='3')
					// {
						// $sqlx3   = "INSERT INTO PETIKEMAS_CABANG.TTD_CONT_PEB ( KD_PMB_DTL, NO_NPE, TGL_PEB, USER_ID, KD_CABANG, TGL_SIMPAN, KD_PMB, NO_INVOICE, NO_UKK ) VALUES
							// (
								// PETIKEMAS_CABANG.SEQ_TTD_CONT_EXBSPL.CURRVAL,
								// '-',
								// SYSDATE, 
								// '$nm_user',
								// '05',
								// SYSDATE,
								// '$no_req_ict',
								// '',
								// '$no_ukk'
							// )";
						// $db->query($sqlx3);
					// }
					
					
					
					// if($db->query($query_insert))
					// {
						$db->startTransaction();
						if($db->query($history))
						{
							if($db->query($query_update))
							{
								
								if($row_perp != 'PERP' && $stuf_dari != 'AUTO' && $remark_sp2 != 'Y'){
								
									if($db->query($query_update_plan))
									{
										echo "OK";
									}
									else
									{
										echo "gagal update plan stuff";exit;
									}
								}
								else {
									echo "OK";						
								}
							}
							else
							{
								echo "gagal update cont stuff";exit;
							}
						}
						else
						{
							echo "gagal insert History";exit;
						}
						$db->endTransaction();
					// }
					// else
					// {
						// echo "gagal insert Container Delivery ";exit;
					// }
					
					
					
				
				// }
				// else
				// {
					// echo "gagal insert simop";exit;
				// }
					
					
					
					
					
			}
			else
			{
				echo "OVER";	
			}
		}
		else
		{
			echo "NOT_AKTIF";	
		}
	}
	else
	{
		echo "NOT_IN_YARD";
	}
}
?>