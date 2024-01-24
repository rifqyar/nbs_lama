<?php

$db 			= getDB("storage");

$no_cont		= $_POST["NO_CONT"]; 
$no_req			= $_POST["NO_REQ"];
$keterangan		= $_POST["KETERANGAN"];
$id_user        = $_SESSION["LOGGED_STORAGE"];
$id_yard        = $_SESSION["IDYARD_STORAGE"];

$del = "SELECT * FROM CONTAINER_DELIVERY WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";
$rel = $db->query($del);
$rwl = $rel->fetchRow();
$bp_id = $rwl[EX_BP_ID];
$status = $rwl[STATUS];
//==============================================================Interface Ke ICT=============================================================================//
//==============================================================================================================================================================================//
	

//==============================================================End Of Interface Ke ICT======================================================================//
//==============================================================================================================================================================================//

$nonaktiv = "UPDATE CONTAINER_DELIVERY SET AKTIF = 'T', REMARK_BATAL = 'Y' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req' ";

$db->query($nonaktiv);

$g_book = "SELECT NO_BOOKING, COUNTER FROM HISTORY_CONTAINER WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";
$q_book = $db->query($g_book);
$r_book = $q_book->fetchRow();
$cur_book = $r_book[NO_BOOKING];
$cur_count = $r_book[COUNTER];

$history = "INSERT INTO HISTORY_CONTAINER(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD, STATUS_CONT, NO_BOOKING, COUNTER, WHY) VALUES ('$no_cont','$no_req','BATAL SP2', SYSDATE, '$id_user','$id_yard','$status','$cur_book','$cur_count','$keterangan')";

$db->query($history);
echo "OK";
exit();
?>