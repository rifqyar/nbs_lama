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
						   TO_CHAR(A.RTA,'DD-MM-YYYY HH24:MI:SS') AS RTA,
						   TO_CHAR(A.RTD,'DD-MM-YYYY HH24:MI:SS') AS RTD,
						   TO_CHAR(A.TGL_REQ,'DD-MM-YYYY HH24:MI:SS') AS TGL_REQ
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

$query3 = "SELECT DISTINCT ID_ALAT FROM GLC_DETAIL_REQUEST_SHIFT WHERE ID_REQ = '$id_req' AND STAT_TIME = 'WORKING' AND EST_REAL = 'REALISASI'";
$eksekusi3 = $db->query($query3);
$col_preview3 = $eksekusi3->getAll();

	$n=0;
	foreach($col_preview3 as $b)
	{
		$id_alat[$n] = $b['ID_ALAT'];
		$hasil3[$n] = $db->query("SELECT TO_CHAR(MIN(MULAI),'DD-MM-YYYY HH24:MI:SS') AS START_WORK,
								   TO_CHAR(MAX(SELESAI),'DD-MM-YYYY HH24:MI:SS') AS END_WORK,
								   ROUND((SUM(SHIFT)*(24))/8) AS JML_SHIFT
    							   FROM GLC_DETAIL_REQUEST_SHIFT 
								   WHERE ID_REQ = '$id_req' 
								   AND ID_ALAT = '$id_alat[$n]'
								   AND STAT_TIME = 'WORKING'
								   AND EST_REAL = 'REALISASI'")->fetchRow();
		
		$start_work[$n] = $hasil3[$n]['START_WORK'];
		$end_work[$n] = $hasil3[$n]['END_WORK'];
		$jumlah_shift[$n] = $hasil3[$n]['JML_SHIFT'];
        $ttl[$n] = $jumlah_shift[$n]*$tarif_dasar;		
		$total[$n] = number_format($ttl[$n],2);
		$n++;
	}
	
	$jml_alat = count($id_alat);
	$adm = $jml_alat*10000+10000;
	$jml_total = array_sum($ttl);
	$jml_ppn = $jml_total*0.1;
	$jml_bayar = $adm+$jml_total+$jml_ppn;
 
 $tl->assign('request',$row11);
 $tl->assign("HOME",HOME);
 $tl->assign("APPID",APPID);
 $tl->assign('status',$stat);
 $tl->assign('administrasi',$adm);
 $tl->assign('total',$jml_total);
 $tl->assign('ppn',$jml_ppn);
 $tl->assign('bayar',$jml_bayar);
 $tl->assign('remark',$remarks);
 $tl->renderToScreen();
?>
