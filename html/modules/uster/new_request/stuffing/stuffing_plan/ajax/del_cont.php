<?php

$db 		= getDB("storage");

$no_cont	= $_POST["NO_CONT"]; 
$no_req		= $_POST["NO_REQ"];
$no_req2	= $_POST["NO_REQ2"]; 
 

$query_master	= "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
$result 		= $db->query($query_master);
$data			= $result->fetchRow();
$counter		= $data['COUNTER'];
$no_book		= $data['NO_BOOKING'];
//ambil data kegiatan kontainer yang masih satu siklus/counter
$query_history	= "SELECT NO_REQUEST FROM history_container WHERE no_container = '$no_cont' and no_booking = '$no_book' and counter = '$counter'";
$result_ 		= $db->query($query_history);
$data_			= $result_->getAll();



//delete data seluruh kegiatan kontainer yang masih satu siklus/counter
foreach ($data_ as $row){
	$req = $row['NO_REQUEST'];
	$query_del2	= "DELETE FROM PLAN_CONTAINER_STUFFING WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$req'";
	$query_del3	= "DELETE FROM CONTAINER_STUFFING WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$req'";
	$query_del4	= "DELETE FROM CONTAINER_RECEIVING WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$req'";
	$query_del5	= "DELETE FROM CONTAINER_DELIVERY WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$req'";
	
	
	// if($db->query($query_del2)){echo "yes"; }
	$db->query($query_del2);
	// if($db->query($query_del3)){echo "yes2"; }
	$db->query($query_del3);
	// if($db->query($query_del4)){echo "yes3"; }
	$db->query($query_del4);
	// if($db->query($query_del5)){echo "yes4"; }
	$db->query($query_del5);
	
	
	
}

$query_del6	= "DELETE FROM HISTORY_CONTAINER WHERE NO_CONTAINER = '$no_cont' AND COUNTER = '$counter'";

if($no_req_sp2 != NULL){
	$aktivad = "UPDATE CONTAINER_DELIVERY SET REMARK_BATAL = 'Y' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req_sp2'";
	$db->query($aktivad);
}


//==============================================================Interface Ke ICT==========================================================================//
//======================================================================================================================================================//


//==============================================================End Of Interface Ke ICT======================================================================//
//==========================================================================================================================================================//

if($db->query($query_del6))
{
    //kembalikan counter ke semula
    $query_update_counter ="UPDATE MASTER_CONTAINER SET COUNTER = '$counter'-1 WHERE NO_CONTAINER = '$no_cont'";
    $result_update_counter = $db->query($query_update_counter);

    echo "OK";
}


// $db->endTransaction();

?>