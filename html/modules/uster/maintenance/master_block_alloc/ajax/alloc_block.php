<?php

$db		= getDB("storage");

$block_name	= $_POST["block_name"];
$kegiatan	= $_POST["kegiatan"];
$pelayaran	= $_POST["pelayaran"];

//debug($_POST);die;

$query_id_block = "SELECT a.ID AS BLOCK_ID
				 FROM BLOCKING_AREA a
						JOIN BLOCKING_ALLOCATION b
							ON b.ID_BLOCKING_AREA = a.ID
						JOIN BLOCKING_BOOKING c
							ON C.ID_BLOCKING_AREA = a.ID
				  WHERE a.NAME='$block_name'
			  ";
$result_id_block	= $db->query($query_id_block);
$row_id_block		= $result_id_block->fetchRow();
$id_block		= $row_id_block["BLOCK_ID"];

//echo $id_block;die;

$query_update1           = "UPDATE BLOCKING_ALLOCATION SET
							KEGIATAN = '$kegiatan'
								WHERE ID_BLOCKING_AREA = $id_block";

$query_update2           = "UPDATE BLOCKING_BOOKING SET
							KD_SHIPPING_LINE = '$pelayaran'
								WHERE ID_BLOCKING_AREA = $id_block";
								
	if($db->query($query_update1) && $db->query($query_update2) )
	{
		echo "OK";
	}

?>