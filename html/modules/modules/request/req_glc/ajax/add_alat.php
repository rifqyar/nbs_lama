<?php

$db 		= getDB();
$nm_user	= $_SESSION["NAMA_LENGKAP"];
$remark 	= $_POST["remark"]; //req,realisasi
$id_req 	= $_POST["id_req"]; 
$rta    	= $_POST["rta"];
$rtd		= $_POST["rtd"];
$strt		= $_POST["strt"];
$end		= $_POST["end"];
$alat		= $_POST["alat"];
$keterangan = $_POST["keterangan"];
$stat_time	= $_POST["stat_time"]; //wrk,it,not,wt
$estimasi	= $_POST["estimasi"];

//echo $remark;exit; 
 
 //=== insert detail request ===//
 $query_cek1 = "SELECT COUNT(*) AS JUMLAH_RECORDS FROM GLC_DETAIL_REQ_SHIFT WHERE ID_REQ = '$id_req' AND ID_ALAT = '$alat'";
 $result1    = $db->query($query_cek1);
 $row4	     = $result1->fetchRow();			
 $jml_record = $row4['JUMLAH_RECORDS'];
 
 //print_r($jml_record);die;
  
 if($remark=="req")
 {
 
	if(($id_req==NULL)||($alat==NULL)||($stat_time==NULL)||($estimasi==NULL))
	{
		echo "NO";
	}
	else
 {
	if($jml_record >0)
	{
		if($stat_time=="wrk")
		 {
			$insert_detail_req = "UPDATE GLC_DETAIL_EST_SHIFT SET WRK_E = '$estimasi' WHERE ID_REQ = '$id_req' AND ID_ALAT = '$alat'";
		 }
		 else if($stat_time=="it")
		 {
			$insert_detail_req = "UPDATE GLC_DETAIL_REQ_SHIFT SET IT_E = '$estimasi' WHERE ID_REQ = '$id_req' AND ID_ALAT = '$alat'";
		 }
		 else if($stat_time=="wt")
		 {
			$insert_detail_req = "UPDATE GLC_DETAIL_REQ_SHIFT SET WT_E = '$estimasi' WHERE ID_REQ = '$id_req' AND ID_ALAT = '$alat'";
		 }
		 else if($stat_time=="not")
		 {
			$insert_detail_req = "UPDATE GLC_DETAIL_REQ_SHIFT SET NOT_E = '$estimasi' WHERE ID_REQ = '$id_req' AND ID_ALAT = '$alat'";
		 }
	}
	else
	{
		if($stat_time=="wrk")
		 {
			$insert_detail_req = "INSERT INTO GLC_DETAIL_REQ_SHIFT (ID_REQ,ID_ALAT,WRK_E) VALUES ('$id_req','$alat','$estimasi')";			
		 }
		 else if($stat_time=="it")
		 {
			$insert_detail_req = "INSERT INTO GLC_DETAIL_REQ_SHIFT (ID_REQ,ID_ALAT,IT_E) VALUES ('$id_req','$alat','$estimasi')";			
		 }
		 else if($stat_time=="wt")
		 {
			$insert_detail_req = "INSERT INTO GLC_DETAIL_REQ_SHIFT (ID_REQ,ID_ALAT,WT_E) VALUES ('$id_req','$alat','$estimasi')";			
		 }
		 else if($stat_time=="not")
		 {
			$insert_detail_req = "INSERT INTO GLC_DETAIL_REQ_SHIFT (ID_REQ,ID_ALAT,NOT_E) VALUES ('$id_req','$alat','$estimasi')";			
		 }
	}
	
if($db->query($insert_detail_req))
		{	
			echo "OK";
		}
		else
		{ 
			echo "gagal";exit;
		}
		
	}	
		
 }
 else if($remark=="realisasi")
 {
 
	if(($id_req==NULL)||($alat==NULL)||($stat_time==NULL)||($rta==NULL)||($rtd==NULL)||($strt==NULL)||($end==NULL))
	{
		echo "NO";
	}
	else
 {
    
	//=== update RTA RTD ===//
	 $query_rta_rtd = "UPDATE GLC_REQUEST SET RTA = TO_DATE('$rta','dd/mm/yyyy HH24:MI:SS'), RTD = TO_DATE('$rtd','dd/mm/yyyy HH24:MI:SS') WHERE ID_REQ = '$id_req'";
	 $db->query($query_rta_rtd); 	 
	
		if($stat_time=="wrk")
		 {
			$insert_detail_req = "INSERT INTO GLC_DETAIL_REQ_SHIFT (ID_REQ,ID_ALAT,ST_WRK,END_WRK,KETERANGAN) VALUES ('$id_req','$alat',TO_DATE('$strt','dd/mm/yyyy HH24:MI:SS'),TO_DATE('$end','dd/mm/yyyy HH24:MI:SS'),'$keterangan')";
			$db->query($insert_detail_req);
			
			$selisih_time = "SELECT END_WRK-ST_WRK AS JUMLAH_TIME FROM GLC_DETAIL_REQ_SHIFT WHERE ID_REQ = '$id_req' AND ID_ALAT = '$alat'";
		    $hasil        = $db->query($selisih_time);
		    $row8	      = $hasil->fetchRow();			
		    $jml_time     = $row8['JUMLAH_TIME'];
			
			$update_realtime = "UPDATE GLC_DETAIL_REQ_SHIFT SET WRK_R = '$jml_time' WHERE ID_REQ = '$id_req' AND ID_ALAT = '$alat'";			
		 }
		 else if($stat_time=="it")
		 {
			$insert_detail_req = "INSERT INTO GLC_DETAIL_REQ_SHIFT (ID_REQ,ID_ALAT,ST_IT,END_IT,KETERANGAN) VALUES ('$id_req','$alat',TO_DATE('$strt','dd/mm/yyyy HH24:MI:SS'),TO_DATE('$end','dd/mm/yyyy HH24:MI:SS'),'$keterangan')";
			$db->query($insert_detail_req);
			
			$selisih_time = "SELECT END_IT-ST_IT AS JUMLAH_TIME FROM GLC_DETAIL_REQ_SHIFT WHERE ID_REQ = '$id_req' AND ID_ALAT = '$alat'";
		    $hasil        = $db->query($selisih_time);
		    $row8	      = $hasil->fetchRow();			
		    $jml_time     = $row8['JUMLAH_TIME'];
			
			$update_realtime = "UPDATE GLC_DETAIL_REQ_SHIFT SET IT_R = '$jml_time' WHERE ID_REQ = '$id_req' AND ID_ALAT = '$alat'";			
		 }
		 else if($stat_time=="wt")
		 {
			$insert_detail_req = "INSERT INTO GLC_DETAIL_REQ_SHIFT (ID_REQ,ID_ALAT,ST_WT,END_WT,KETERANGAN) VALUES ('$id_req','$alat',TO_DATE('$strt','dd/mm/yyyy HH24:MI:SS'),TO_DATE('$end','dd/mm/yyyy HH24:MI:SS'),'$keterangan')";
			$db->query($insert_detail_req);
			
			$selisih_time = "SELECT END_WT-ST_WT AS JUMLAH_TIME FROM GLC_DETAIL_REQ_SHIFT WHERE ID_REQ = '$id_req' AND ID_ALAT = '$alat'";
		    $hasil        = $db->query($selisih_time);
		    $row8	      = $hasil->fetchRow();			
		    $jml_time     = $row8['JUMLAH_TIME'];
			
			$update_realtime = "UPDATE GLC_DETAIL_REQ_SHIFT SET WT_R = '$jml_time' WHERE ID_REQ = '$id_req' AND ID_ALAT = '$alat'";			
		 }
		 else if($stat_time=="not")
		 {
			$insert_detail_req = "INSERT INTO GLC_DETAIL_REQ_SHIFT (ID_REQ,ID_ALAT,ST_NOT,END_NOT,KETERANGAN) VALUES ('$id_req','$alat',TO_DATE('$strt','dd/mm/yyyy HH24:MI:SS'),TO_DATE('$end','dd/mm/yyyy HH24:MI:SS'),'$keterangan')";
			$db->query($insert_detail_req);
			
			$selisih_time = "SELECT END_NOT-ST_NOT AS JUMLAH_TIME FROM GLC_DETAIL_REQ_SHIFT WHERE ID_REQ = '$id_req' AND ID_ALAT = '$alat'";
		    $hasil        = $db->query($selisih_time);
		    $row8	      = $hasil->fetchRow();			
		    $jml_time     = $row8['JUMLAH_TIME'];
			
			$update_realtime = "UPDATE GLC_DETAIL_REQ_SHIFT SET NOT_R = '$jml_time' WHERE ID_REQ = '$id_req' AND ID_ALAT = '$alat'";			
		 }
	
if($db->query($update_realtime))
		{	
			$update_req_stat = "UPDATE GLC_REQUEST SET STATUS='R' WHERE ID_REQ='$id_req'";
			$db->query($update_req_stat);
			echo "OK";
		}
		else
		{ 
			echo "gagal";exit;
		} 
		
	}
		
 }
 
 
 
 
 
 
?>