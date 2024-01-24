<?php

$db 		= getDB("storage");

$no_cont	= $_POST["NO_CONT"]; 
$block		= $_POST["BLOCK"]; 
$slot		= $_POST["SLOT"]; 
$row		= $_POST["ROW"];
$tier		= $_POST["TIER"];  

$id_user	= $_SESSION["NIPP"];
$id_yard	= $_SESSION["IDYARD_STORAGE"];

$query_cek		= "SELECT * FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
$result_cek		= $db->query($query_cek);
$row_cek		= $result_cek->fetchRow();

if($row_cek["LOCATION"] == "GATI")
{
	$query_update	= "UPDATE MASTER_CONTAINER SET LOCATION = 'IN_YARD' WHERE NO_CONTAINER = '$no_cont'";
	
	if($db->query($query_update))
	{
		$query_cek_sr	= "SELECT MAX(SLOT_) AS MAX_SLOT, MAX(ROW_) AS MAX_ROW FROM BLOCKING_CELL WHERE ID_BLOCKING_AREA = '$block'";
		$result_sr		= $db->query($query_cek_sr);
		$row_sr			= $result_sr->fetchRow();
		
		if(($slot <= $row_sr["MAX_SLOT"]) && ($row <= $row_sr["MAX_ROW"]))
		{
			/* $query_cek_cell	= "SELECT COUNT(1) AS CEK FROM PLACEMENT WHERE ID_BLOCKING_AREA = '$block' AND ROW_ = '$row' AND SLOT_ = '$slot' AND TIER_ = '$tier'";
			$result_cell	= $db->query($query_cek_cell);
			$row_cell		= $result_cell->fetchRow(); */
			
			/* if($row_cell["CEK"] == 0)
			{ */
				$query_insert_placement = "INSERT INTO PLACEMENT(NO_CONTAINER, ID_BLOCKING_AREA, SLOT_, ROW_, TIER_, TGL_UPDATE, USER_NAME, TGL_PLACEMENT) VALUES('$no_cont', '$block', '$slot', '$row', '$tier', SYSDATE, '$id_user', SYSDATE)";
				if($db->query($query_insert_placement))
				{
					$query_get_req	= "SELECT NO_REQUEST FROM CONTAINER_RECEIVING
                                        WHERE NO_CONTAINER = '$no_cont' 
                                        AND DEPO_TUJUAN = '$id_yard'
                                        AND ROWNUM <= 1 
                                        ORDER BY NO_REQUEST DESC";
					
					echo $query_get_req;
										
					$result_req		= $db->query($query_get_req);
					$row_req		= $result_req->fetchRow();
						
					$no_req					= $row_req["NO_REQUEST"];
					$query_insert_history	= "INSERT INTO HISTORY_PLACEMENT(NO_CONTAINER, NO_REQUEST, ID_BLOCKING_AREA, SLOT_, ROW_, TIER_, TGL_UPDATE, NIPP_USER, BAYAR_LOLO, KETERANGAN) VALUE('$no_cont', '$no_req', '$block', '$slot', '$row', '$tier', SYSDATE, '$id_user', 'N','RELOKASI' )";
					if($db->query($query_insert_history))
					{		
						echo "OK";
					}
				}
			/*}
			 else
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
else
{
	$query_cek_sr	= "SELECT MAX(SLOT_) AS MAX_SLOT, MAX(ROW_) AS MAX_ROW FROM BLOCKING_CELL WHERE ID_BLOCKING_AREA = '$block'";
	$result_sr		= $db->query($query_cek_sr);
	$row_sr			= $result_sr->fetchRow();
	
	if(($slot <= $row_sr["MAX_SLOT"]) && ($row <= $row_sr["MAX_ROW"]))
	{
		/* $query_cek_cell	= "SELECT COUNT(1) AS CEK FROM PLACEMENT WHERE ID_BLOCKING_AREA = '$block' AND ROW_ = '$row' AND SLOT_ = '$slot' AND TIER_ = '$tier'";
		$result_cell	= $db->query($query_cek_cell);
		$row_cell		= $result_cell->fetchRow(); */
		
		/* if($row_cell["CEK"] == 0)
		{ */
			$query_update	= "UPDATE PLACEMENT SET ID_BLOCKING_AREA = '$block', SLOT_ = '$slot', ROW_ = '$row', TIER_ = '$tier', TGL_UPDATE = SYSDATE WHERE NO_CONTAINER = '$no_cont'";	
			if($db->query($query_update))
			{
				$query_get_req	= "SELECT NO_REQUEST FROM CONTAINER_RECEIVING
                                        WHERE NO_CONTAINER = '$no_cont' 
                                        AND DEPO_TUJUAN = '$id_yard'
                                        AND ROWNUM <= 1 
                                        ORDER BY NO_REQUEST DESC";
				
				$result_req		= $db->query($query_get_req);
				$row_req		= $result_req->fetchRow();
				
				if($_POST["LOLO"] == 'Y')
					$lolo					= $_POST["LOLO"];
				else
					$lolo					= "N";
					
				$no_req					= $row_req["NO_REQUEST"];
				$query_insert_history	= "INSERT INTO HISTORY_PLACEMENT(NO_CONTAINER, NO_REQUEST, ID_BLOCKING_AREA, SLOT_, ROW_, TIER_, TGL_UPDATE, NIPP_USER, BAYAR_LOLO, KETERANGAN) VALUES('$no_cont', '$no_req', '$block', '$slot', '$row', '$tier', SYSDATE, '$id_user', '$lolo' ,'RELOKASI')";
				if($db->query($query_insert_history))
				{		
					echo "OK";
				}
			}
		/* }
		else
		{
			echo "STACKED";	
		} */
	}
	else
	{
		echo "OVER";	
	}
}
?>