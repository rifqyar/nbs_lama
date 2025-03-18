<?php

$db 		= getDB("storage");

$no_cont	= strtoupper($_POST["NO_CONT"]); 
$no_request	= strtoupper($_POST["NO_REQUEST"]); 
$block		= $_POST["BLOCK"]; 
$slot		= $_POST["SLOT"];
$slot2		= $slot+1; 
$row		= $_POST["ROW"];
$tier		= $_POST["TIER"];  

// print_r($slot2);die;
// print_r($_POST);die;

// print_r($block);die;

$q_optrgate = "declare begin PROC_UPD_GATEOPTR('$no_cont','$no_request'); end;";
if(!$db->query($q_optrgate)){
    //echo 'FAILED';
    //die();
}

$id_user	= $_SESSION["LOGGED_STORAGE"];

if ($id_user == NULL) {
	echo "SESSION_HABIS";
	exit();
}

if($block != 6 ||  $block != 7 ||  $block != 8 ||  $block != 9 ||  $block != 10 ||  $block != 11 ||  $block != 12 ||  $block != 13 ||  $block != 14 ||  $block != 21)
{
		// echo "tanpa validasi";die;
		
	$query_cek_cont = "SELECT * FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
	$result_cek_c	= $db->query($query_cek_cont);
	$row_cek_c		= $result_cek_c->fetchRow();
	$count_c		= count($row_cek_c);
	$size			= $row_cek_c['SIZE_'];

	$query_get_noreq	= "SELECT CONTAINER_RECEIVING.NO_REQUEST, MASTER_CONTAINER.LOCATION
							 FROM  CONTAINER_RECEIVING
							 INNER JOIN MASTER_CONTAINER
									ON CONTAINER_RECEIVING.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
							  WHERE CONTAINER_RECEIVING.NO_CONTAINER = '$no_cont' 
								AND CONTAINER_RECEIVING.AKTIF = 'Y'
								AND MASTER_CONTAINER.LOCATION = 'GATI'";
	$result_noreq		= $db->query($query_get_noreq);
	$row_noreq			= $result_noreq->fetchRow();
	$no_req_rec			= $row_noreq["NO_REQUEST"];
	$location			= $row_noreq["LOCATION"];

	//echo $no_req_rec;die;

	$query_get_rec	= "SELECT * FROM CONTAINER_RECEIVING WHERE NO_CONTAINER = '$no_cont' AND AKTIF = 'Y'";
	$result_req		= $db->query($query_get_rec);
	$row_req		= $result_req->fetchRow();
	$req_rec		= $row_req["NO_REQUEST"];
	$status			= $row_req["STATUS"];
	if($count_c > 0)
	{
		if($location == "GATI")
		{
			
				//$query_insert_placement = "INSERT INTO PLACEMENT(NO_CONTAINER, ID_BLOCKING_AREA, SLOT_, ROW_, TIER_, TGL_UPDATE, USER_NAME, TGL_PLACEMENT, NO_REQUEST_RECEIVING) VALUES('$no_cont', '$block', '$slot', '$row', '$tier', SYSDATE, '$id_user', SYSDATE, '$no_req_rec')";
				
				$query_cek_sr	= "SELECT MAX(SLOT_) AS MAX_SLOT, MAX(ROW_) AS MAX_ROW FROM BLOCKING_CELL WHERE ID_BLOCKING_AREA = '$block'";
				$result_sr		= $db->query($query_cek_sr);
				$row_sr			= $result_sr->fetchRow();
				
				if($result_sr->RecordCount == 0){ // placement untuk di nipah atau potensi
					    if ($size == '20'){
							$query_insert_placement = "INSERT INTO PLACEMENT(NO_CONTAINER, ID_BLOCKING_AREA, SLOT_, ROW_, TIER_, TGL_UPDATE, USER_NAME, TGL_PLACEMENT, NO_REQUEST_RECEIVING, STATUS) VALUES('$no_cont', '$block', '$slot', '$row', '$tier', SYSDATE, '$id_user', SYSDATE, '$no_request', '$status')";
						} else {
							$query_insert_placement = "INSERT INTO PLACEMENT(NO_CONTAINER, ID_BLOCKING_AREA, SLOT_, ROW_, TIER_, TGL_UPDATE, USER_NAME, TGL_PLACEMENT, NO_REQUEST_RECEIVING, STATUS) VALUES('$no_cont', '$block', '$slot', '$row', '$tier', SYSDATE, '$id_user', SYSDATE, '$no_request', '$status')";
							$query_insert_placement_ = "INSERT INTO PLACEMENT(NO_CONTAINER, ID_BLOCKING_AREA, SLOT_, ROW_, TIER_, TGL_UPDATE, USER_NAME, TGL_PLACEMENT, NO_REQUEST_RECEIVING, STATUS) VALUES('$no_cont', '$block', '$slot2', '$row', '$tier', SYSDATE, '$id_user', SYSDATE, '$no_request', '$status')";
						}
						if($db->query($query_insert_placement))
						{
							if($size == '40')
							{ 
								$db->query($query_insert_placement_);
							}
							$query_update_rec	= "UPDATE CONTAINER_RECEIVING SET AKTIF = 'T' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_request'";
							$db->query($query_update_rec);
							
							$no_req					= $row_req["NO_REQUEST"];
							$query_insert_history	= "INSERT INTO HISTORY_PLACEMENT(NO_CONTAINER, NO_REQUEST, ID_BLOCKING_AREA, SLOT_, ROW_, TIER_, TGL_UPDATE, NIPP_USER, BAYAR_LOLO,KETERANGAN) VALUES('$no_cont', '$no_request', '$block', '$slot', '$row', '$tier', SYSDATE, '$id_user', 'N', 'PLACEMENT' )";
							if($db->query($query_insert_history))
							{	
								$query_update_rec	= "UPDATE CONTAINER_RECEIVING SET AKTIF = 'T' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_request'";
								$db->query($query_update_rec);	
								$db->query("UPDATE MASTER_CONTAINER SET LOCATION = 'IN_YARD' WHERE NO_CONTAINER = '$no_cont'");
								echo "OK";
							}
						}
						else
						{
							// echo "gagal insert placement";
							echo "DB_ERROR";
						}
				}
				else{			
						if(($slot <= $row_sr["MAX_SLOT"]) && ($row <= $row_sr["MAX_ROW"]))
						{
							
								/*$query_update	= "UPDATE MASTER_CONTAINER SET LOCATION = 'IN_YARD' WHERE NO_CONTAINER = '$no_cont'";	
								if($db->query($query_update))*/
								
									/* $query_cek_cell	= "SELECT COUNT(1) AS CEK FROM PLACEMENT WHERE ID_BLOCKING_AREA = '$block' AND ROW_ = '$row' AND SLOT_ = '$slot' AND TIER_ = '$tier'";
									$result_cell	= $db->query($query_cek_cell);
									$row_cell		= $result_cell->fetchRow();
									
							if($row_cell["CEK"] == 0)
							{ */
								/*$query_update	= "UPDATE MASTER_CONTAINER SET LOCATION = 'IN_YARD' WHERE NO_CONTAINER = '$no_cont'";*/	
								//hist 
								$db->query("INSERT INTO HIST_LOCATION(NO_CONTAINER, NO_REQUEST, LOCATION) VALUES('$no_cont','$no_request','IN_YARD')");
									if ($size == '20'){
										$query_insert_placement = "INSERT INTO PLACEMENT(NO_CONTAINER, ID_BLOCKING_AREA, SLOT_, ROW_, TIER_, TGL_UPDATE, USER_NAME, TGL_PLACEMENT, NO_REQUEST_RECEIVING, STATUS) VALUES('$no_cont', '$block', '$slot', '$row', '$tier', SYSDATE, '$id_user', SYSDATE, '$no_request', '$status')";
									} else {
										$query_insert_placement = "INSERT INTO PLACEMENT(NO_CONTAINER, ID_BLOCKING_AREA, SLOT_, ROW_, TIER_, TGL_UPDATE, USER_NAME, TGL_PLACEMENT, NO_REQUEST_RECEIVING, STATUS) VALUES('$no_cont', '$block', '$slot', '$row', '$tier', SYSDATE, '$id_user', SYSDATE, '$no_request', '$status')";
										$query_insert_placement_ = "INSERT INTO PLACEMENT(NO_CONTAINER, ID_BLOCKING_AREA, SLOT_, ROW_, TIER_, TGL_UPDATE, USER_NAME, TGL_PLACEMENT, NO_REQUEST_RECEIVING, STATUS) VALUES('$no_cont', '$block', '$slot2', '$row', '$tier', SYSDATE, '$id_user', SYSDATE, '$no_request', '$status')";
									}
									if($db->query($query_insert_placement))
									{
										if($size == '40'){
											$db->query($query_insert_placement_);
										}	
										$query_update_rec	= "UPDATE CONTAINER_RECEIVING SET AKTIF = 'T' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_request'";
										$db->query($query_update_rec);
										
										$no_req					= $row_req["NO_REQUEST"];
										$query_insert_history	= "INSERT INTO HISTORY_PLACEMENT(NO_CONTAINER, NO_REQUEST, ID_BLOCKING_AREA, SLOT_, ROW_, TIER_, TGL_UPDATE, NIPP_USER, BAYAR_LOLO,KETERANGAN) VALUES('$no_cont', '$no_request', '$block', '$slot', '$row', '$tier', SYSDATE, '$id_user', 'N', 'PLACEMENT' )";
										if($db->query($query_insert_history))
										{		
											$query_update_rec	= "UPDATE CONTAINER_RECEIVING SET AKTIF = 'T' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_request'";
											$db->query($query_update_rec);	
											$db->query("UPDATE MASTER_CONTAINER SET LOCATION = 'IN_YARD' WHERE NO_CONTAINER = '$no_cont'");
											echo "OK";
										}
									}
									else
									{
										// echo "gagal insert placement";
										echo "DB_ERROR";
									}
								
							/* } */
							/* else
							{
								echo "SLOT_ISI";	
							} */
							
						}
						else
						{
							echo "SLOT_NA";	
						}
				}
			}
			else {
				echo "blm gati";
			}
	}
	else
	{
		echo "NOT_EXIST";	
	}
}
else
{
	// echo "dengan validasi";die;
	
	
		
	$query_cek_cont = "SELECT * FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
	$result_cek_c	= $db->query($query_cek_cont);
	$row_cek_c		= $result_cek_c->fetchRow();
	
	// print_r($row_cek_c);die;
	
	$count_c		= count($row_cek_c);
	$size			= $row_cek_c['SIZE_'];
	
	// print_r($size);die;
	
	$query_get_noreq	= "SELECT CONTAINER_RECEIVING.NO_REQUEST, MASTER_CONTAINER.LOCATION
							 FROM  CONTAINER_RECEIVING
							 INNER JOIN MASTER_CONTAINER
									ON CONTAINER_RECEIVING.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
							  WHERE CONTAINER_RECEIVING.NO_CONTAINER = '$no_cont' 
								AND CONTAINER_RECEIVING.AKTIF = 'Y'
								AND MASTER_CONTAINER.LOCATION = 'GATI'";
	$result_noreq		= $db->query($query_get_noreq);
	$row_noreq			= $result_noreq->fetchRow();
	$no_req_rec			= $row_noreq["NO_REQUEST"];
	$location			= $row_noreq["LOCATION"];

	//echo $no_req_rec;die;

	$query_get_rec	= "SELECT * FROM CONTAINER_RECEIVING WHERE NO_CONTAINER = '$no_cont' AND AKTIF = 'Y'";
	$result_req		= $db->query($query_get_rec);
	$row_req		= $result_req->fetchRow();
	$req_rec		= $row_req["NO_REQUEST"];
	$status			= $row_req["STATUS"];
	if($count_c > 0)
	{
		if($location == "GATI")
		{
			//cek container sudah placement ato belum
			$query_place	= "SELECT * FROM PLACEMENT WHERE NO_CONTAINER = '$no_cont'";
			$result_place	= $db->query($query_place);
			$row_place		= $result_place->fetchRow();
			$place			= $row_place["NO_CONTAINER"];
			
			if($place == NULL)
			{
			
						
					//$query_insert_placement = "INSERT INTO PLACEMENT(NO_CONTAINER, ID_BLOCKING_AREA, SLOT_, ROW_, TIER_, TGL_UPDATE, USER_NAME, TGL_PLACEMENT, NO_REQUEST_RECEIVING) VALUES('$no_cont', '$block', '$slot', '$row', '$tier', SYSDATE, '$id_user', SYSDATE, '$no_req_rec')";
					
					// $query_cek_sr	= "SELECT MAX(SLOT_) AS MAX_SLOT, MAX(ROW_) AS MAX_ROW FROM BLOCKING_CELL WHERE ID_BLOCKING_AREA = '$block'";
					// $result_sr		= $db->query($query_cek_sr);
					// $row_sr			= $result_sr->fetchRow();
					
					// if($result_sr->RecordCount == 0){ //untuk depo nipah kuning dan potensi
					
						
						// if($db->query($query_update)){
					
						
				//cek slot row yang bener2 ada
				$cek_slot_row = "SELECT MAX(SLOT_) SLOT_, MAX(ROW_) ROW_, MAX(TIER_) TIER_ 
									FROM BLOCKING_AREA
									WHERE ID = $block ";
				$result_cek_slot_row 		= $db->query($cek_slot_row);
				$row_cek_slot_row	= $result_cek_slot_row->fetchRow();
				
				$slot_cek = $row_cek_slot_row['SLOT_'];
				$row_cek  = $row_cek_slot_row['ROW_'];
				$tier_cek = $row_cek_slot_row['TIER_'];
				
				
				if($slot <= $slot_cek && $row <= $row_cek && $tier <= $tier_cek  )
				{
				
					//cek slot ke-1 sudah dipakai belum
					$cek_slot_1 = "SELECT ID 
									FROM PLACEMENT
									WHERE SLOT_   = $slot
									AND     ROW_  = $row
									AND     TIER_ = $tier
									AND ID_BLOCKING_AREA = $block ";
					$result_slot_1 = $db->query($cek_slot_1);
					$row_slot_1	   = $result_slot_1->getAll();
					$count_slot_1  = count($row_slot_1);
					
					// echo $cek_slot_1;die;
					// echo $count_slot_1;die;
					
					//validasi container melayang slot pertama (untuk 20 dan 40 feet)
					$cek_tier = "SELECT ID 
									FROM PLACEMENT
									WHERE SLOT_   = $slot
									AND     ROW_  = $row
									AND     TIER_ = ($tier-1)
									AND ID_BLOCKING_AREA = $block ";
					$result_tier = $db->query($cek_tier);
					$row_tier	   = $result_tier->getAll();
					$tier_bawah  = count($row_tier);
					
					if($tier_bawah > 0 || $tier == 1)
					{
						if($count_slot_1 == 0)
						{
							if ($size == '20')
							{
							
									$query_insert_placement = "INSERT INTO PLACEMENT(NO_CONTAINER, ID_BLOCKING_AREA, SLOT_, ROW_, TIER_, TGL_UPDATE, USER_NAME, TGL_PLACEMENT, NO_REQUEST_RECEIVING, STATUS) VALUES('$no_cont', '$block', '$slot', '$row', '$tier', SYSDATE, '$id_user', SYSDATE, '$req_rec', '$status')";
									
									/*$query_update	= "UPDATE MASTER_CONTAINER SET LOCATION = 'IN_YARD' WHERE NO_CONTAINER = '$no_cont'";
									$db->query($query_update);*/
								
							}
							else 
							{
								//validasi container melayang untuk slot kedua (untuk 40 feet)
								$cek_tier_40 = "SELECT ID 
										FROM PLACEMENT
										WHERE SLOT_   = $slot2
										AND     ROW_  = $row
										AND     TIER_ = ($tier-1)
										AND ID_BLOCKING_AREA = $block ";
								$result_tier_40 = $db->query($cek_tier_40);
								$row_tier_40	   = $result_tier_40->getAll();
								$tier_bawah_40  = count($row_tier_40);
								
								if($tier_bawah_40 > 0 || $tier == 1)
								{	
										//cek slot ke-2 ada ga
										$cek_slot_exist = "SELECT SLOT_ 
															FROM BLOCKING_AREA 
															WHERE ID='$block'";
										$result_slot_exist = $db->query($cek_slot_exist);
										$row_slot_exist	   = $result_slot_exist->fetchRow();
										$row_slot_exist_isi = $row_slot_exist['SLOT_'];
									
										if($slot2 <= $row_slot_exist_isi)
										{
											//cek slot ke-2 sudah dipakai belum
											$cek_slot_2 = "SELECT ID 
															FROM PLACEMENT
															WHERE SLOT_   = $slot2
															AND     ROW_  = $row
															AND     TIER_ = $tier
															AND ID_BLOCKING_AREA = $block ";
											$result_slot_2 = $db->query($cek_slot_2);
											$row_slot_2	   = $result_slot_2->getAll();
											$count_slot_2  = count($row_slot_2);
											
											// echo $cek_slot_2;die;
											// echo $count_slot_2;die;
											
											if($count_slot_2 == 0)
											{
												$query_insert_placement = "INSERT INTO PLACEMENT(NO_CONTAINER, ID_BLOCKING_AREA, SLOT_, ROW_, TIER_, TGL_UPDATE, USER_NAME, TGL_PLACEMENT, NO_REQUEST_RECEIVING, STATUS) VALUES('$no_cont', '$block', '$slot', '$row', '$tier', SYSDATE, '$id_user', SYSDATE, '$req_rec', '$status')";
												$query_insert_placement_ = "INSERT INTO PLACEMENT(NO_CONTAINER, ID_BLOCKING_AREA, SLOT_, ROW_, TIER_, TGL_UPDATE, USER_NAME, TGL_PLACEMENT, NO_REQUEST_RECEIVING, STATUS) VALUES('$no_cont', '$block', '$slot2', '$row', '$tier', SYSDATE, '$id_user', SYSDATE, '$req_rec', '$status')";
												
												/*$query_update	= "UPDATE MASTER_CONTAINER SET LOCATION = 'IN_YARD' WHERE NO_CONTAINER = '$no_cont'";
												$db->query($query_update);*/
											}
											else
											{
												// $tl = xliteTemplate('gagal.htm');
												// $tl->assign('result','<b>Gagal insert placement, Slot kedua sudah terpakai</b>');
												// $tl->renderToScreen();
												// exit();
												echo "SLOT_DUA_ISI";
											}
										}
										else
										{
											// $tl = xliteTemplate('gagal.htm');
											// $tl->assign('result','<b>Gagal insert placement, Slot kedua tidak tersedia untuk container 40 </b>');
											// $tl->renderToScreen();
											// exit();
											echo "SLOT_DUA_NA_40";
										}
								}
								else
								{
									// $tl = xliteTemplate('gagal.htm');
									// $tl->assign('result','<b>Gagal insert placement, Slot dibawah kosong (40 feet) </b>');
									// $tl->renderToScreen();
									// exit();
									echo "SLOT_BAWAH_MTY";
									
								}
							}
							
							if($db->query($query_insert_placement))
							{
								
								
								if ($size == '40'){
									// echo "ok";die;
									$db->query($query_insert_placement_);
									// echo "masuk slot kedua";
								}
								// echo "OK";
								// die;//asasd
								
								$query_update_rec	= "UPDATE CONTAINER_RECEIVING SET AKTIF = 'T' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req_rec'";
								$db->query($query_update_rec);
								
								//$no_req					= $row_req["NO_REQUEST"];
								$query_insert_history	= "INSERT INTO HISTORY_PLACEMENT(NO_CONTAINER, NO_REQUEST, ID_BLOCKING_AREA, SLOT_, ROW_, TIER_, TGL_UPDATE, NIPP_USER, BAYAR_LOLO,KETERANGAN) VALUES('$no_cont', '$req_rec', '$block', '$slot', '$row', '$tier', SYSDATE, '$id_user', 'N', 'PLACEMENT' )";
								if($db->query($query_insert_history))
								{		
									// $tl = xliteTemplate('sukses.htm');
									// $tl->renderToScreen();
									$db->query("UPDATE MASTER_CONTAINER SET LOCATION = 'IN_YARD' WHERE NO_CONTAINER = '$no_cont'");
									echo "OK";
								}
								else
								{
									// $tl = xliteTemplate('gagal.htm');
									// $tl->assign('result','<b>Gagal insert placement </b>');
									// $tl->renderToScreen();
									echo "DB_ERROR";
								}
							}
							else
							{
								// $tl = xliteTemplate('gagal.htm');
								// $tl->assign('result','<b>Gagal insert placement </b>');
								// $tl->renderToScreen();
								 echo "DB_ERROR";
							}
								// }
						}
						else
						{
							// $tl = xliteTemplate('gagal.htm');
							// $tl->assign('result','<b>Gagal insert placement, Slot sudah terpakai </b>');
							// $tl->renderToScreen();
							// exit();
							echo "SLOT_ISI";
						}
					}
					else
					{
						// $tl = xliteTemplate('gagal.htm');
						// $tl->assign('result','<b>Gagal insert placement, Slot dibawah kosong </b>');
						// $tl->renderToScreen();
						// exit();
						echo "SLOT_BAWAH_MTY";
					}
				}
				else
				{
					// $tl = xliteTemplate('gagal.htm');
					// $tl->assign('result','<b>Gagal insert placement, Slot tidak tersedia/invalid </b>');
					// $tl->renderToScreen();
					// exit();
					echo "SLOT_NA";
				}

			
			}
			else
			{
				// $tl = xliteTemplate('gagal.htm');
				// $tl->assign('result','<b>Gagal insert placement, Container sudah placement </b>');
				// $tl->renderToScreen();
				// exit();
				echo "PLACEMENT";
			}
		}
			else 
			{
				echo "blm gati";
			}
	
	}
	else
	{
		echo "NOT_EXIST";	
	}
}
?>