<?php

$db 		= getDB();
$nm_user	= $_SESSION["NAMA_LENGKAP"];
$remark 	= $_POST["remark"]; //req,realisasi
$id_req 	= $_POST["id_req"];
$eta    	= $_POST["eta"];
$etd    	= $_POST["etd"]; 
$rta    	= $_POST["rta"];
$rta_jam   	= $_POST["rta_jam"];
$rta_menit 	= $_POST["rta_menit"];
$rtd		= $_POST["rtd"];
$rtd_jam	= $_POST["rtd_jam"];
$rtd_menit	= $_POST["rtd_menit"];
$real_mulai	= $_POST["real_mulai"];
$real_mulai_jam	= $_POST["real_mulai_jam"];
$real_mulai_menit = $_POST["real_mulai_menit"];
$real_selesai = $_POST["real_selesai"];
$real_selesai_jam = $_POST["real_selesai_jam"];
$real_selesai_menit = $_POST["real_selesai_menit"];
$alat		= $_POST["alat"];
$keterangan = $_POST["keterangan"];
$stat_keterangan = $_POST["stat_keterangan"];
$tarif = $_POST["tarif"];
//$stat_time	= $_POST["stat_time"]; //wrk,it,not,wt
$estimasi	= $_POST["estimasi"];
$mulai  	= $_POST["mulai"];
$mulai_jam 	= $_POST["mulai_jam"];
$mulai_menit = $_POST["mulai_menit"];
$selesai	= $_POST["selesai"];
$selesai_jam = $_POST["selesai_jam"];
$selesai_menit = $_POST["selesai_menit"];

$est_mulai = $mulai." ".$mulai_jam.":".$mulai_menit.":00";
$est_selesai = $selesai." ".$selesai_jam.":".$selesai_menit.":00";
$rta_ = $rta." ".$rta_jam.":".$rta_menit.":00";
$rtd_ = $rtd." ".$rtd_jam.":".$rtd_menit.":00";
$real_strt = $real_mulai." ".$real_mulai_jam.":".$real_mulai_menit.":00";
$real_end = $real_selesai." ".$real_selesai_jam.":".$real_selesai_menit.":00";

//echo $remark;exit; 
    
 if($remark=="req")
 {
 
	if(($id_req==NULL)||($alat==NULL)||($estimasi==NULL)||($mulai==NULL)||($mulai_jam==NULL)||($mulai_menit==NULL)||($selesai==NULL)||($selesai_jam==NULL)||($selesai_menit==NULL))
	{
		echo "NO";
	}	
	else
	{
		$cek_date1 = "SELECT TO_DATE('$est_mulai','dd/mm/yyyy HH24:MI:SS') - TO_DATE('$eta','dd/mm/yyyy HH24:MI:SS') AS SELISIH_DATE FROM DUAL";
		$result1  = $db->query($cek_date1);
		$row4	  = $result1->fetchRow();			
		$cek_tgl_mulai = $row4['SELISIH_DATE'];
		
		$cek_date2 = "SELECT TO_DATE('$etd','dd/mm/yyyy HH24:MI:SS') - TO_DATE('$est_selesai','dd/mm/yyyy HH24:MI:SS') AS SELISIH_DATE FROM DUAL";
		$result1  = $db->query($cek_date2);
		$row4	  = $result1->fetchRow();			
		$cek_tgl_selesai = $row4['SELISIH_DATE'];
		
		if(($cek_tgl_mulai>=0)&&($cek_tgl_selesai>=0))
		{
			$insert_detail_req = "INSERT INTO GLC_DETAIL_EST_SHIFT (ID_REQ,ID_ALAT,SHIFT,MULAI,SELESAI,STAT_TIME) VALUES ('$id_req','$alat','$estimasi',TO_DATE('$est_mulai','dd/mm/yyyy HH24:MI:SS'),TO_DATE('$est_selesai','dd/mm/yyyy HH24:MI:SS'),'WORKING')";		 
	
			if($db->query($insert_detail_req))
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
			echo "TANGGAL";
		}
		
	}	
		
 }
 else if($remark=="realisasi")
 {
   if($real_end>$real_strt)	
   {
	$real_strt_jam = $real_mulai_jam.":".$real_mulai_menit.":00";
	$real_end_jam = $real_selesai_jam.":".$real_selesai_menit.":00"; 
 
	$awal_shift1 = "08:00:01";
	$akhir_akhir1 = "16:00:00";
	$awal_shift2 = "16:00:01";
	$akhir_akhir2 = "24:00:00";
	$awal_shift3 = "00:00:01";
	$akhir_akhir3 = "08:00:00";
 
	if(($id_req==NULL)||($alat==NULL)||($keterangan==NULL)||($stat_keterangan==NULL)||($rta==NULL)||($rta_jam==NULL)||($rta_menit==NULL)||($real_mulai==NULL)||($real_mulai_jam==NULL)||($real_mulai_menit==NULL)||($real_selesai==NULL)||($real_selesai_jam==NULL)||($real_selesai_menit==NULL))
	{
		echo "NO";
	}
	else
 {
    
	if((($real_strt_jam>=$awal_shift1)&&($akhir_akhir1>=$real_strt_jam)&&($real_end_jam>=$awal_shift1)&&($akhir_akhir1>=$real_end_jam))||(($real_strt_jam>=$awal_shift2)&&($akhir_akhir2>=$real_strt_jam)&&($real_end_jam>=$awal_shift2)&&($akhir_akhir2>=$real_end_jam))||(($real_strt_jam>=$awal_shift3)&&($akhir_akhir3>=$real_strt_jam)&&($real_end_jam>=$awal_shift3)&&($akhir_akhir3>=$real_end_jam)))
   {
	
	//=== insert RTA ===//
	$cek_date1 = "SELECT TO_DATE('$real_strt','dd/mm/yyyy HH24:MI:SS') - TO_DATE('$rta_','dd/mm/yyyy HH24:MI:SS') AS SELISIH_DATE FROM DUAL";
	$result1  = $db->query($cek_date1);
	$row4 = $result1->fetchRow();			
	$cek_tgl_mulai = $row4['SELISIH_DATE'];
	
	$max_real ="SELECT MAX(ID_DETAILS) AS MAX_ID_DTL FROM GLC_DETAIL_REAL_SHIFT WHERE ID_REQ = '$id_req' AND ID_ALAT = '$alat'";
	$result2  = $db->query($max_real);
	$row5 = $result2->fetchRow();			
	$real_max = $row5['MAX_ID_DTL'];
	
	if($real_max==NULL)
  {
	
	if($cek_tgl_mulai>=0)
	{
	 $query_rta = "UPDATE GLC_REQUEST SET RTA = TO_DATE('$rta_','dd/mm/yyyy HH24:MI:SS') WHERE ID_REQ = '$id_req'";
	 $db->query($query_rta);
	 
    if(($real_strt_jam>=$awal_shift1)&&($akhir_akhir1>=$real_strt_jam)&&($real_end_jam>=$awal_shift1)&&($akhir_akhir1>=$real_end_jam))
    {
     $cek_tgl_shift = "SELECT TO_DATE('$real_end','dd/mm/yyyy HH24:MI:SS') - TO_DATE('$real_strt','dd/mm/yyyy HH24:MI:SS') AS SELISIH, TO_DATE('$real_mulai','dd/mm/yyyy') AS TGL_SHIFT FROM DUAL";
	 $result9  = $db->query($cek_tgl_shift);
	 $row9 = $result9->fetchRow();			
	 $selisih = $row9['SELISIH'];
	 $tgl_shift = $row9['TGL_SHIFT'];
		
	 $insert_detail_req = "INSERT INTO GLC_DETAIL_REAL_SHIFT (ID_REQ,ID_ALAT,SELISIH,MULAI,SELESAI,STAT_TIME,KETERANGAN,TGL_SHIFT,NO_SHIFT,HRS_USED,MIN_USED,TARIF) VALUES 
	 ('$id_req','$alat','$selisih',TO_DATE('$real_strt','dd/mm/yyyy HH24:MI:SS'),TO_DATE('$real_end','dd/mm/yyyy HH24:MI:SS'),'$stat_keterangan','$keterangan','$tgl_shift','1',MOD(TRUNC($selisih*24),24),MOD(TRUNC($selisih*1440),60),'$tarif')";
	}
	else if(($real_strt_jam>=$awal_shift2)&&($akhir_akhir2>=$real_strt_jam)&&($real_end_jam>=$awal_shift2)&&($akhir_akhir2>=$real_end_jam))
	{
	 $cek_tgl_shift = "SELECT TO_DATE('$real_end','dd/mm/yyyy HH24:MI:SS') - TO_DATE('$real_strt','dd/mm/yyyy HH24:MI:SS') AS SELISIH, TO_DATE('$real_mulai','dd/mm/yyyy') AS TGL_SHIFT FROM DUAL";
	 $result9  = $db->query($cek_tgl_shift);
	 $row9 = $result9->fetchRow();			
	 $selisih = $row9['SELISIH'];
	 $tgl_shift = $row9['TGL_SHIFT'];
		
	 $insert_detail_req = "INSERT INTO GLC_DETAIL_REAL_SHIFT (ID_REQ,ID_ALAT,SELISIH,MULAI,SELESAI,STAT_TIME,KETERANGAN,TGL_SHIFT,NO_SHIFT,HRS_USED,MIN_USED,TARIF) VALUES 
	 ('$id_req','$alat','$selisih',TO_DATE('$real_strt','dd/mm/yyyy HH24:MI:SS'),TO_DATE('$real_end','dd/mm/yyyy HH24:MI:SS'),'$stat_keterangan','$keterangan','$tgl_shift','2',MOD(TRUNC($selisih*24),24),MOD(TRUNC($selisih*1440),60),'$tarif')";
	}
	else if(($real_strt_jam>=$awal_shift3)&&($akhir_akhir3>=$real_strt_jam)&&($real_end_jam>=$awal_shift3)&&($akhir_akhir3>=$real_end_jam))
	{
	 $cek_tgl_shift = "SELECT TO_DATE('$real_end','dd/mm/yyyy HH24:MI:SS') - TO_DATE('$real_strt','dd/mm/yyyy HH24:MI:SS') AS SELISIH, TO_DATE('$real_mulai','dd/mm/yyyy')-1 AS TGL_SHIFT FROM DUAL";
	 $result9  = $db->query($cek_tgl_shift);
	 $row9 = $result9->fetchRow();			
	 $selisih = $row9['SELISIH'];
	 $tgl_shift = $row9['TGL_SHIFT'];
		
	 $insert_detail_req = "INSERT INTO GLC_DETAIL_REAL_SHIFT (ID_REQ,ID_ALAT,SELISIH,MULAI,SELESAI,STAT_TIME,KETERANGAN,TGL_SHIFT,NO_SHIFT,HRS_USED,MIN_USED,TARIF) VALUES 
	 ('$id_req','$alat','$selisih',TO_DATE('$real_strt','dd/mm/yyyy HH24:MI:SS'),TO_DATE('$real_end','dd/mm/yyyy HH24:MI:SS'),'$stat_keterangan','$keterangan','$tgl_shift','3',MOD(TRUNC($selisih*24),24),MOD(TRUNC($selisih*1440),60),'$tarif')";
	}		
		
		if($db->query($insert_detail_req))
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
	else
	{					
		echo "TANGGAL";
	}
   }
   else
   {
		$query_rta = "UPDATE GLC_REQUEST SET RTA = TO_DATE('$rta_','dd/mm/yyyy HH24:MI:SS') WHERE ID_REQ = '$id_req'";
		$db->query($query_rta);
		
		$get_tgl_selesai = "SELECT TO_CHAR(SELESAI,'DD-MM-YYYY HH24:MI:SS') AS SELESAI FROM GLC_DETAIL_REAL_SHIFT WHERE ID_DETAILS = '$real_max'";
		$result6  = $db->query($get_tgl_selesai);
		$row6 = $result6->fetchRow();			
		$tgl_selesai = $row6['SELESAI'];
		
		$cek_date10 = "SELECT TO_DATE('$real_strt','dd/mm/yyyy HH24:MI:SS') - TO_DATE('$tgl_selesai','dd/mm/yyyy HH24:MI:SS') AS SELISIH_DATE FROM DUAL";
		$result10  = $db->query($cek_date10);
		$row10 = $result10->fetchRow();			
		$cek_tgl_real = $row10['SELISIH_DATE'];
		
		if($cek_tgl_real>=0)
	{
	 	if(($real_strt_jam>=$awal_shift1)&&($akhir_akhir1>=$real_strt_jam)&&($real_end_jam>=$awal_shift1)&&($akhir_akhir1>=$real_end_jam))
		{
		 $cek_tgl_shift = "SELECT TO_DATE('$real_end','dd/mm/yyyy HH24:MI:SS') - TO_DATE('$real_strt','dd/mm/yyyy HH24:MI:SS') AS SELISIH, TO_DATE('$real_mulai','dd/mm/yyyy') AS TGL_SHIFT FROM DUAL";
		 $result9  = $db->query($cek_tgl_shift);
		 $row9 = $result9->fetchRow();			
		 $selisih = $row9['SELISIH'];
		 $tgl_shift = $row9['TGL_SHIFT'];
			
		 $insert_detail_req = "INSERT INTO GLC_DETAIL_REAL_SHIFT (ID_REQ,ID_ALAT,SELISIH,MULAI,SELESAI,STAT_TIME,KETERANGAN,TGL_SHIFT,NO_SHIFT,HRS_USED,MIN_USED,TARIF) VALUES 
		 ('$id_req','$alat','$selisih',TO_DATE('$real_strt','dd/mm/yyyy HH24:MI:SS'),TO_DATE('$real_end','dd/mm/yyyy HH24:MI:SS'),'$stat_keterangan','$keterangan','$tgl_shift','1',MOD(ROUND($selisih*24),24),MOD(ROUND($selisih*1440),60),'$tarif')";
		}
		else if(($real_strt_jam>=$awal_shift2)&&($akhir_akhir2>=$real_strt_jam)&&($real_end_jam>=$awal_shift2)&&($akhir_akhir2>=$real_end_jam))
		{
		 $cek_tgl_shift = "SELECT TO_DATE('$real_end','dd/mm/yyyy HH24:MI:SS') - TO_DATE('$real_strt','dd/mm/yyyy HH24:MI:SS') AS SELISIH, TO_DATE('$real_mulai','dd/mm/yyyy') AS TGL_SHIFT FROM DUAL";
		 $result9  = $db->query($cek_tgl_shift);
		 $row9 = $result9->fetchRow();			
		 $selisih = $row9['SELISIH'];
		 $tgl_shift = $row9['TGL_SHIFT'];
			
		 $insert_detail_req = "INSERT INTO GLC_DETAIL_REAL_SHIFT (ID_REQ,ID_ALAT,SELISIH,MULAI,SELESAI,STAT_TIME,KETERANGAN,TGL_SHIFT,NO_SHIFT,HRS_USED,MIN_USED,TARIF) VALUES 
		 ('$id_req','$alat','$selisih',TO_DATE('$real_strt','dd/mm/yyyy HH24:MI:SS'),TO_DATE('$real_end','dd/mm/yyyy HH24:MI:SS'),'$stat_keterangan','$keterangan','$tgl_shift','2',MOD(ROUND($selisih*24),24),MOD(ROUND($selisih*1440),60),'$tarif')";
		}
		else if(($real_strt_jam>=$awal_shift3)&&($akhir_akhir3>=$real_strt_jam)&&($real_end_jam>=$awal_shift3)&&($akhir_akhir3>=$real_end_jam))
		{
		 $cek_tgl_shift = "SELECT TO_DATE('$real_end','dd/mm/yyyy HH24:MI:SS') - TO_DATE('$real_strt','dd/mm/yyyy HH24:MI:SS') AS SELISIH, TO_DATE('$real_mulai','dd/mm/yyyy')-1 AS TGL_SHIFT FROM DUAL";
		 $result9  = $db->query($cek_tgl_shift);
		 $row9 = $result9->fetchRow();			
		 $selisih = $row9['SELISIH'];
		 $tgl_shift = $row9['TGL_SHIFT'];
			
		 $insert_detail_req = "INSERT INTO GLC_DETAIL_REAL_SHIFT (ID_REQ,ID_ALAT,SELISIH,MULAI,SELESAI,STAT_TIME,KETERANGAN,TGL_SHIFT,NO_SHIFT,HRS_USED,MIN_USED,TARIF) VALUES 
		 ('$id_req','$alat','$selisih',TO_DATE('$real_strt','dd/mm/yyyy HH24:MI:SS'),TO_DATE('$real_end','dd/mm/yyyy HH24:MI:SS'),'$stat_keterangan','$keterangan','$tgl_shift','3',MOD(ROUND($selisih*24),24),MOD(ROUND($selisih*1440),60),'$tarif')";
		}		
			
			if($db->query($insert_detail_req))
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
	else
	{					
		echo "TANGGAL";
	}		
		
   }
   
   }
   else
   {
		echo "SHIFT";
   }   
		
  }
 }
 else
 {					
	echo "TANGGAL";
 }		
		
}
 
 
 
 
 
 
?>