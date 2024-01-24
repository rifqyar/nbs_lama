<?php
 $tl = xliteTemplate('v_invoice.htm');
 $id_req = $_GET["id"]; 
 $stat = $_GET['remark']; //shift, non shift 
 $remarks = "view";
 $db = getDB();
 $row11 = $db->query("SELECT A.ID_REQ, 
                           A.NO_UPER_BM,
                           C.NAMA,
						   C.STATUS_PBM,
                           B.NAMA_VESSEL,
                           A.VOYAGE,
                           A.STATUS,
						   A.REMARK,
						   A.KADE,
						   A.TERMINAL,
						   A.KODE_PBM,
						   TO_CHAR(A.ETA,'DD-MM-YYYY') AS ETA_DATE,
						   TO_CHAR(A.ETA,'HH24') AS ETA_JAM,
						   TO_CHAR(A.ETA,'MI') AS ETA_MENIT,
						   TO_CHAR(A.ETD,'DD-MM-YYYY') AS ETD_DATE,
						   TO_CHAR(A.ETD,'HH24') AS ETD_JAM,
						   TO_CHAR(A.ETD,'MI') AS ETD_MENIT,
						   TO_CHAR(A.RTA,'DD-MM-YYYY') AS RTA_DATE,
						   TO_CHAR(A.RTA,'HH24') AS RTA_JAM,
						   TO_CHAR(A.RTA,'MI') AS RTA_MENIT,
						   TO_CHAR(A.RTD,'DD-MM-YYYY') AS RTD_DATE,
						   TO_CHAR(A.RTD,'HH24') AS RTD_JAM,
						   TO_CHAR(A.RTD,'MI') AS RTD_MENIT
						   FROM GLC_REQUEST A, MASTER_VESSEL B, MASTER_PBM C
                           WHERE A.KODE_KAPAL = B.KODE_KAPAL
                           AND A.KODE_PBM = C.KODE_PBM
						   AND A.ID_REQ='$id_req'"
						   )->fetchRow();
 
 $status_pbm = $row11['STATUS_PBM'];
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
		$total[$n] = number_format($ttl[$n],2);
		$n++;
	}
	
	$jml_alat = count($id_alat);
	$jml_total = array_sum($ttl);
	$jml_ppn = round($jml_total*0.1);
	$jml_bayar = $jml_total+$jml_ppn;
	
	$uper_alat = "SELECT (TOTAL+PPN) AS JML_BAYAR, TOTAL, PPN FROM GLC_UPER_ALAT_H WHERE ID_REQ='$id_req' AND LUNAS='Y'";
	$result9 = $db->query($uper_alat);
	$row9 = $result9->fetchRow();			
	$jml_uper_alat = $row9['JML_BAYAR'];
	$total_uper = $row9['TOTAL'];
	$ppn_uper = $row9['PPN'];
	$kurang_bayar = $jml_bayar-$jml_uper_alat;
 
 $tl->assign('request',$row11);
 $tl->assign("HOME",HOME);
 $tl->assign("APPID",APPID);
 $tl->assign('status',$stat);
 $tl->assign('total',$jml_total);
 $tl->assign('ppn',$jml_ppn);
 $tl->assign('bayar',$jml_bayar);
 $tl->assign('ttl_uper',$total_uper);
 $tl->assign('ppnuper',$ppn_uper);
 $tl->assign('bayar_uper',$jml_uper_alat);
 $tl->assign('krng_byr',$kurang_bayar);
 $tl->assign('remark',$remarks);
 $tl->renderToScreen();
?>
