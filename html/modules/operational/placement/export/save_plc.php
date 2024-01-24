<?php
$db 			= getDB();
$cell	= $_POST[''];
$slot	= $_POST[''];
$row	= $_POST[''];
$tier	= $_POST[''];
$status	= $_POST[''];
$id_cont= $_POST[''];
$id_bm	= $_POST[''];
$plc	= $_POST[''];
$id_user= $_POST[''];
$id_alat= $_POST[''];
$id_blocking_area= $_POST[''];
$size_= $_POST[''];
$id_vs= $_POST[''];

	
$query 			= "INSERT INTO YD_PLACEMENT_YARD (ID_CELL,SLOT_YARD,ROW_YARD,TIER_YARD,STATUS,STACK,ID_KEY,ID_BM,ACTIVITY,PLACEMENT_DATE,ID_USER,ID_ALAT,ID_BLOCKING_AREA,SIZE_,ID_VS,KODE_PBM,NO_CONTAINER, DEST, TON, NOPOL, SLOT2_YARD, TYPE_CONT,STATUS_CONT) VALUES
(ID_CELL,SLOT_YARD,ROW_YARD,TIER_YARD,STATUS,'STACK',ID_KEY,ID_BM,'MUAT',SYSDATE,ID_USER,ID_ALAT,ID_BLOCKING_AREA,SIZE_,ID_VS,KODE_PBM,NO_CONTAINER, DEST, TON, NOPOL, SLOT2_YARD, TYPE_CONT,STATUS_CONT)
				   ()";
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>