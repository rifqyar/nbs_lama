<?php

$db 		= getDB();

$id_alat	= $_POST["ID_ALAT"]; 
$id_req		= $_POST["ID_REQ"];
$stat_time  = $_POST["STATUS_TIME"]; //wrk,it,wt,not
$stat_form  = $_POST["STATUS_FORM"]; //req,realisasi
$id_detail  = $_POST["ID_DETAIL"];

if($stat_form=="req")
{
	if($stat_time=="wrk")
		{
			$query_update_time	= "UPDATE GLC_DETAIL_REQ_SHIFT SET WRK_E='0' WHERE ID_REQ = '$id_req' AND ID_ALAT = '$id_alat'";
			$db->query($query_update_time);
		}
	    else if($stat_time=="it")
		{
			$query_update_time	= "UPDATE GLC_DETAIL_REQ_SHIFT SET IT_E='0' WHERE ID_REQ = '$id_req' AND ID_ALAT = '$id_alat'";
			$db->query($query_update_time);
		}
		else if($stat_time=="wt")
		{
			$query_update_time	= "UPDATE GLC_DETAIL_REQ_SHIFT SET WT_E='0' WHERE ID_REQ = '$id_req' AND ID_ALAT = '$id_alat'";
			$db->query($query_update_time);
		}
		else if($stat_time=="not")
		{
			$query_update_time	= "UPDATE GLC_DETAIL_REQ_SHIFT SET NOT_E='0' WHERE ID_REQ = '$id_req' AND ID_ALAT = '$id_alat'";
			$db->query($query_update_time);
		}
	
	$query_cek1 = "SELECT COUNT(*) AS JUMLAH_RECORDS FROM GLC_DETAIL_REQ_SHIFT WHERE WT_E='0' AND IT_E='0' AND NOT_E='0' AND WRK_E='0' AND ID_REQ = '$id_req' AND ID_ALAT = '$id_alat'";
	$result1    = $db->query($query_cek1);
	$row4	    = $result1->fetchRow();			
	$jml_record = $row4['JUMLAH_RECORDS'];
 
 if($jml_record >0)
	{
		$query_delete = "DELETE FROM DETAIL_REQ_SHIFT WHERE ID_REQ = '$id_req' AND ID_ALAT = '$id_alat'";
		if($db->query($query_delete))
		{	
			echo "OK";
		}
		else
		{ 
			echo "gagal";exit;
		}
	}
	else
	{
		echo "OK";
		
	}
}
else if($stat_form=="realisasi")
{
	if($stat_time=="wrk")
		{
			$query_update_time	= "UPDATE GLC_DETAIL_REQ_SHIFT SET WRK_R='0',ST_WRK=NULL,END_WRK=NULL WHERE ID_REQ = '$id_req' AND ID_ALAT = '$id_alat' AND ID_DETAIL = '$id_detail'";
			$db->query($query_update_time);
		}
	    else if($stat_time=="it")
		{
			$query_update_time	= "UPDATE GLC_DETAIL_REQ_SHIFT SET IT_R='0',ST_IT=NULL,END_IT=NULL WHERE ID_REQ = '$id_req' AND ID_ALAT = '$id_alat' AND ID_DETAIL = '$id_detail'";
			$db->query($query_update_time);
		}
		else if($stat_time=="wt")
		{
			$query_update_time	= "UPDATE GLC_DETAIL_REQ_SHIFT SET WT_R='0',ST_WT=NULL,END_WT=NULL WHERE ID_REQ = '$id_req' AND ID_ALAT = '$id_alat' AND ID_DETAIL = '$id_detail'";
			$db->query($query_update_time);
		}
		else if($stat_time=="not")
		{
			$query_update_time	= "UPDATE GLC_DETAIL_REQ_SHIFT SET NOT_R='0',ST_NOT=NULL,END_NOT=NULL WHERE ID_REQ = '$id_req' AND ID_ALAT = '$id_alat' AND ID_DETAIL = '$id_detail'";
			$db->query($query_update_time);
		}
		
		$query_cek1 = "SELECT COUNT(*) AS JUMLAH_RECORDS FROM GLC_DETAIL_REQ_SHIFT WHERE WT_R='0' AND IT_R='0' AND NOT_R='0' AND WRK_R='0' AND ID_REQ = '$id_req' AND ID_ALAT = '$id_alat' AND ID_DETAIL = '$id_detail'";
		$result1    = $db->query($query_cek1);
		$row4	    = $result1->fetchRow();			
		$jml_record = $row4['JUMLAH_RECORDS'];
		
		if($jml_record >0)
	{
		$query_delete = "DELETE FROM GLC_DETAIL_REQ_SHIFT 
									WHERE ID_REQ = '$id_req' 
									AND ID_ALAT = '$id_alat'
									AND ID_DETAIL = '$id_detail'";
		if($db->query($query_delete))
		{	
			echo "OK";
		}
		else
		{ 
			echo "gagal";exit;
		}
	}
	else
	{
		echo "OK";
		
	}
}




?>