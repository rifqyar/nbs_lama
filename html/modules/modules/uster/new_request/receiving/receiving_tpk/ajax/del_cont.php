<?php
//debug ($_POST);die;

$no_cont	= $_POST["NO_CONT"]; 
$no_req		= $_POST["NO_REQ"]; 
$no_cont	= $_POST["NO_CONT"];
//$sp2		= $_POST["NO_CONT"]; 
$no_req2	= $_POST["NO_REQ2"]; 

$db 		= getDB("storage");
$db2		= getDB("ora");
//==============================================================Interface Ke ICT==========================================================================//
//======================================================================================================================================================//

$sqlbp	= "SELECT NO_BP_ID FROM PETIKEMAS_CABANG.TTD_DEL_REQ WHERE CONT_NO_BP = '$no_cont' AND NO_REQ_DEL = '$no_req2'";
$rsbp	= $db2->query($sqlbp);
$rowbp	= $rsbp->FetchRow(); 
$bp_id	= $rowbp["NO_BP_ID"];

$sql= "DELETE FROM PETIKEMAS_CABANG.TTD_DEL_REQ WHERE NO_REQ_DEL = '$no_req2' AND CONT_NO_BP = '$no_cont' AND NO_BP_ID = '$bp_id'  ";

//$db2->query($sql);

$sql_u= "UPDATE PETIKEMAS_CABANG.TTD_BP_CONT SET STATUS_CONT='03' WHERE BP_ID ='$bp_id' AND CONT_NO_BP='$no_cont'";

//$db2->query($sql_u);

//==============================================================End Of Interface Ke ICT======================================================================//
//==========================================================================================================================================================//

$query_del	= "DELETE FROM CONTAINER_RECEIVING WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";
$query_del_history	= "DELETE FROM HISTORY_CONTAINER WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";

//if(($db->query($query_del)) && ($db->query($query_del_history)) )
//{
//	echo "OK";
//}

if($db2->query($sql))
{
	if($db2->query($sql_u))
	{
		if($db->query($query_del))
		{
			if($db->query($query_del_history))
			{
				echo "OK";
			}
			else
			{
				echo "gagal delete history cont";exit;
			}
		}
		else
		{
			echo "gagal del CONTAINER_RECEIVING";exit;
		}
	}
	else
	{
		echo "gagal update TTD_BP_CONT";exit;
	}
	
}
else
{
	echo "gagal del TTD_DEL_REQ";exit;
}

?>