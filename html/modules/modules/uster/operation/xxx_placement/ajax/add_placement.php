<?php

$db 		= getDB("storage");

$no_cont	= strtoupper($_POST["NO_CONT"]); 
$no_request	= strtoupper($_POST["NO_REQUEST"]); 
$block		= $_POST["BLOCK"]; 
$slot		= $_POST["SLOT"];
$slot2		= $slot+1; 
$row		= $_POST["ROW"];
$tier		= $_POST["TIER"];  

$id_user	= $_SESSION["NIPP"];

if ($id_user == NULL) {
	echo "SESSION_HABIS";
	exit();
}

$query_cek_cont = "SELECT * FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
$result_cek_c	= $db->query($query_cek_cont);
$row_cek_c		= $result_cek_c->getAll();
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
				$query_update	= "UPDATE MASTER_CONTAINER SET LOCATION = 'IN_YARD' WHERE NO_CONTAINER = '$no_cont'";
				if($db->query($query_update)){
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
							echo "OK";
						}
					}
					else
					{
						echo "gagal insert placement";
					}
				}
				else
				{
					echo "gagal query update";
				}
			}
			else{			
					if(($slot <= $row_sr["MAX_SLOT"]) && ($row <= $row_sr["MAX_ROW"]))
					{
						
							$query_update	= "UPDATE MASTER_CONTAINER SET LOCATION = 'IN_YARD' WHERE NO_CONTAINER = '$no_cont'";	
							if($db->query($query_update))
							
								/* $query_cek_cell	= "SELECT COUNT(1) AS CEK FROM PLACEMENT WHERE ID_BLOCKING_AREA = '$block' AND ROW_ = '$row' AND SLOT_ = '$slot' AND TIER_ = '$tier'";
								$result_cell	= $db->query($query_cek_cell);
								$row_cell		= $result_cell->fetchRow();
								
						if($row_cell["CEK"] == 0)
						{ */
							$query_update	= "UPDATE MASTER_CONTAINER SET LOCATION = 'IN_YARD' WHERE NO_CONTAINER = '$no_cont'";	
							if($db->query($query_update))
							{
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
										echo "OK";
									}
								}
								else
								{
									echo "gagal insert placement";
								}
							}
							else
							{
								echo "gagal query update";
							}
						/* } */
						/* else
						{
							echo "STACKED";	
						} */
						
					}
					else
					{
						echo "OVER";	
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
?>