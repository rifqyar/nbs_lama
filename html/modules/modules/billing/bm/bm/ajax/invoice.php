<?php

$db 		= getDB();
$nm_user    = $_SESSION["NAMA_LENGKAP"];
$id_user    = $_SESSION["ID_USER"];
$remark 	= $_POST["REMARK"]; //shift, non shift
$id_req		= $_POST["ID_REQ"]; 
$pbm		= $_POST["PBM"];
$kade   	= $_POST["KADE"];
$terminal	= $_POST["TERMINAL"];
$vessel		= $_POST["VESSEL"];
$voyage		= $_POST["VOYAGE"];
$ppn		= $_POST["PPN"];
$total		= $_POST["TOTAL"];
$ttl_uper	= $_POST["TTL_UPER"];
$status		= $_POST["STATUS"];
$status_pbm	= $_POST["STATUS_PBM"];
$rta		= $_POST["RTA"];
$rta_jam	= $_POST["RTA_JAM"];
$rta_menit	= $_POST["RTA_MENIT"];
$rtd		= $_POST["RTD"];
$rtd_jam	= $_POST["RTD_JAM"];
$rtd_menit	= $_POST["RTD_MENIT"];

$rta_ = $rta." ".$rta_jam.":".$rta_menit.":00";
$rtd_ = $rtd." ".$rtd_jam.":".$rtd_menit.":00";

if(($nm_user==NULL)||($id_user==NULL)||($remark==NULL)||($id_req==NULL)||($pbm==NULL)||($kade==NULL)||($terminal==NULL)||($vessel==NULL)||($voyage==NULL)||($ppn==NULL)||($total==NULL)||($status==NULL))
{
	echo "NO";
}
else if(($ttl_uper==NULL))
{
	echo "UPER";
}
else if(($rtd==NULL)||($rtd_jam==NULL)||($rtd_menit==NULL)||($rta==NULL)||($rta_jam==NULL)||($rta_menit==NULL))
{
	echo "RTD";
}
else
{

	if($status=="I")
	{
		echo "SUDAH";
	}
	else
	{

//==================== get NO UPER ALAT =======================//
$get_uper  = "SELECT NO_UPER_ALAT FROM GLC_UPER_ALAT_H WHERE ID_REQ = '$id_req'";
			$result11  = $db->query($get_uper);
			$row11	   = $result11->fetchRow();			
			$uper_alat = $row11['NO_UPER_ALAT'];
//==================== get NO UPER ALAT =======================//	
	
//===================== generate ID NOTA =======================//
$seq_nota = "SELECT MAX(ID_HEADER) AS MAX_ID
					  FROM GLC_NOTA";
		$nota3 = $db->query($seq_nota);
		$sequence_nota = $nota3->fetchRow();	
		
		if($sequence_nota['MAX_ID']==NULL)
		{
		    $nomor_nota = "1";
		}
		else
		{
			$nomor_nota = $sequence_nota['MAX_ID'];
		}
		
$prefiks = "REQ";
$tglnota = date("d-m-Y H:i:s");
$tahun = date("Y");
$bulan = date("m");
$id_nota = $prefiks."-".$nomor_nota."/".$bulan."/".$tahun;

$insert_nota = "INSERT INTO GLC_NOTA (ID_NOTA,ID_REQ,NO_UPER_ALAT,NAMA_PBM,KADE,TERMINAL,VESSEL,VOYAGE,PPN,TOTAL,TOTAL_UPER_ALAT,REMARK,TGL_INVOICE,LUNAS) VALUES ('$id_nota','$id_req','$uper_alat','$pbm','$kade','$terminal','$vessel','$voyage','$ppn','$total','$ttl_uper','$remark',SYSDATE,'T')";
$db->query($insert_nota);
//===================== generate ID NOTA =======================//

//===================== update history =========================//
$update_history = "INSERT INTO GLC_HISTORY (ID_REQ,STATUS,TGL_UPDATE,USER_UPDATE,COUNTER) VALUES ('$id_req','INVOICE PRANOTA',SYSDATE,'$id_user','1')";
$db->query($update_history);
//===================== update history =========================//

//======================= Insert Detail Nota ===========================//

if($status_pbm=="SELEKSI")
	{
		$tarif_dasar=6500000;
		$td = number_format($tarif_dasar,2); 
	}
	else
	{
		$tarif_dasar=7000000;
		$td = number_format($tarif_dasar,2);
	}

$query3 = "SELECT DISTINCT ID_ALAT FROM GLC_DETAIL_REAL_SHIFT WHERE ID_REQ = '$id_req' ORDER BY ID_ALAT";
$eksekusi3 = $db->query($query3);
$col_preview3 = $eksekusi3->getAll();

	$n=0;
	foreach($col_preview3 as $b)
	{
		$id_alat[$n] = $b['ID_ALAT'];
		$hasil3[$n] = $db->query("SELECT (SUM(HRS_USED))/8 AS JAM,
								         ((SUM(MIN_USED))/60)/8 AS MENIT
    							   FROM GLC_DETAIL_REAL_SHIFT 
								   WHERE ID_REQ = '$id_req' 
								   AND ID_ALAT = '$id_alat[$n]'
								   AND TARIF = 'Y'")->fetchRow();
								   
		$hasil4[$n] = $db->query("SELECT TO_CHAR(MIN(MULAI),'DD-MM-YYYY HH24:MI:SS') AS START_WORK,
								   TO_CHAR(MAX(SELESAI),'DD-MM-YYYY HH24:MI:SS') AS END_WORK
    							   FROM GLC_DETAIL_REAL_SHIFT 
								   WHERE ID_REQ = '$id_req' 
								   AND ID_ALAT = '$id_alat[$n]'")->fetchRow();
		
		$start_work[$n] = $hasil4[$n]['START_WORK'];
		$end_work[$n] = $hasil4[$n]['END_WORK'];
		$jam_shift[$n] = $hasil3[$n]['JAM'];
		$menit_shift[$n] = $hasil3[$n]['MENIT'];
		$jam_menit[$n] = $jam_shift[$n]+$menit_shift[$n];
        $ttl[$n] = round($jam_menit[$n]*$tarif_dasar);
		
		$insert_detail_nota[$n] = "INSERT INTO GLC_DETAIL_NOTA_SHIFT (NO_NOTA,ID_ALAT,START_DATE,END_DATE,JUMLAH_SHIFT,TARIF,TOTAL) VALUES ('$id_nota','$id_alat[$n]',TO_DATE('$start_work[$n]','dd/mm/yyyy HH24:MI:SS'),TO_DATE('$end_work[$n]','dd/mm/yyyy HH24:MI:SS'),'$jam_menit[$n]','$tarif_dasar','$ttl[$n]')";
		$db->query($insert_detail_nota[$n]);
		
		//======================= Insert Report Alat ===========================//
			$jml_shift[$n] = number_format($jam_menit[$n],2);
			
			$trafik_mov[$n] = $db->query("SELECT SUM(JUMLAH_CONT) AS JML_BOX
												   FROM GLC_PRODUKSI A, GLC_DETAIL_REAL_SHIFT B
												   WHERE A.ID_REQ = '$id_req'
												   AND A.ID_REQ = B.ID_REQ
												   AND A.ID_DETAILS = B.ID_DETAILS
												   AND B.ID_ALAT = '$id_alat[$n]'")->fetchRow();		
			$move[$n] = $trafik_mov[$n]['JML_BOX'];
			
			$trafik_cont[$n] = $db->query("SELECT SUM(JUMLAH_CONT) AS JML_BOX
												   FROM GLC_PRODUKSI A, GLC_DETAIL_REAL_SHIFT B
												   WHERE A.ID_REQ = '$id_req'
												   AND A.ID_REQ = B.ID_REQ
												   AND A.ID_DETAILS = B.ID_DETAILS
												   AND B.ID_ALAT = '$id_alat[$n]'
												   AND A.ID_CONT LIKE 'CON%'")->fetchRow();		
			$cont[$n] = $trafik_cont[$n]['JML_BOX'];
		
			$insert_report[$n] = "INSERT INTO GLC_REPORT_ALAT (ID_REQ,ID_ALAT,NAMA_PBM,KADE,TERMINAL,VESSEL,VOYAGE,RTA,RTD,MULAI_KERJA,SELESAI_KERJA,JUMLAH_SHIFT,JUMLAH_BOX,JUMLAH_MOVEMENT,REMARK) VALUES ('$id_req','$id_alat[$n]','$pbm','$kade','$terminal','$vessel','$voyage',TO_DATE('$rta_','dd/mm/yyyy HH24:MI:SS'),TO_DATE('$rtd_','dd/mm/yyyy HH24:MI:SS'),TO_DATE('$start_work[$n]','dd/mm/yyyy HH24:MI:SS'),TO_DATE('$end_work[$n]','dd/mm/yyyy HH24:MI:SS'),'$jml_shift[$n]','$cont[$n]','$move[$n]','$remark')";
			$db->query($insert_report[$n]);	
		//======================= Insert Report Alat ===========================//
		
		$n++;
	}

//======================= Insert Detail Nota ===========================//

//===================== Update Status Request =====================//
$update_req_stat = "UPDATE GLC_REQUEST SET STATUS='I' WHERE ID_REQ='$id_req'";

//===================== Update Status Request =====================//

if($db->query($update_req_stat))
		{	
			echo "OK";
		}
		else
		{ 
			echo "gagal";
		}


	}
}
?>