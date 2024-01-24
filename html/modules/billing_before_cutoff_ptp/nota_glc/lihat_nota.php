<?php

	$tl  =  xliteTemplate('lihat_nota.htm');
	$db	 = getDB();
	//$nota	 = $_GET["n"];
	//$id_user = $_SESSION["LOGGED_STORAGE"]; 
	$req	 = $_GET["id"];
	$remarks = $_GET["remark"]; //shift, non shift
	
	//=============================== Preview Nomor, No.Doc, Tgl Proses =================================//
	$prefiks = "VIEW";
	$tglproses = date("d-m-Y");
	$view = substr ($req,-8);
	$nomor = $prefiks.$view;
	//=============================== Preview Nomor, No.Doc, Tgl Proses =================================//
	
	
	//======================= Preview Perusahaan, NPWP, Alamat, Vessel/Voyage ===========================//
	$query2 = "SELECT C.NAMA,
					  C.ALAMAT,
					  C.NPWP,
					  C.STATUS_PBM,
                      B.NAMA_VESSEL,
                      A.VOYAGE,
                      A.STATUS,
					  A.REMARK FROM GLC_REQUEST A, MASTER_VESSEL B, MASTER_PBM C
                           WHERE A.KODE_KAPAL = B.KODE_KAPAL
                           AND A.KODE_PBM = C.KODE_PBM
						   AND A.ID_REQ='$req'";
    $eksekusi2 = $db->query($query2);
	$row_preview2 = $eksekusi2->fetchRow();	
	//======================= Preview Perusahaan, NPWP, Alamat, Vessel/Voyage ===========================//
	
	//======================= Preview Keterangan Alat ===========================//
	$query3 = "SELECT DISTINCT ID_ALAT FROM GLC_DETAIL_REQ_SHIFT WHERE ID_REQ = '$req'";
	$eksekusi3 = $db->query($query3);
	$col_preview3 = $eksekusi3->getAll();
	
	//======================= Preview Keterangan Alat ===========================//
	
	
	//======================= Preview TARIF DASAR ===========================//
	$status_pbm = $row_preview2['STATUS_PBM'];
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
	//======================= Preview TARIF DASAR ===========================//
	
	
	//======================= Preview START END WORK, Jumlah Shift ====================//
	$n=0;
	foreach($col_preview3 as $b)
	{
		$id_alat[$n] = $b['ID_ALAT'];		
		$hasil3[$n] = $db->query("SELECT TO_CHAR(MIN(ST_WRK),'DD-MM-YYYY HH24:MI:SS') AS START_WORK,
								   TO_CHAR(MAX(END_WRK),'DD-MM-YYYY HH24:MI:SS') AS END_WORK,
								   ROUND((SUM(WRK_R)*(24))/8) AS JML_SHIFT
    							   FROM GLC_DETAIL_REQ_SHIFT WHERE ID_REQ = '$req' AND ID_ALAT = '$id_alat[$n]'")->fetchRow();
		
		$start_work[$n] = $hasil3[$n]['START_WORK'];
		$end_work[$n] = $hasil3[$n]['END_WORK'];
		$jumlah_shift[$n] = $hasil3[$n]['JML_SHIFT'];
		$total[$n] = $jumlah_shift[$n]*$tarif_dasar;
		$ttl[$n] = number_format($total[$n],2);
		$n++;
	}
	//======================= Preview START END WORK, Jumlah Shift ====================//	
	
	//======================= Preview TOTAL, PPN, TOTAL BAYAR ===========================//	
	$jml_total = array_sum($total);
	$jml_ppn = $jml_total*0.1;
	$jml_ttl = number_format($jml_total,2);
	$ppn = number_format($jml_ppn,2);
	$jml_alat = count($total);
	$adm = $jml_alat*10000+10000;
	$jml_adm = number_format($adm,2);
	$bayar = $adm+$jml_total+$jml_ppn;
	$jml_byr = number_format($bayar,2);
	
	include_once "terbilang.php";
	$bilangan = toTerbilang($bayar);
	//======================= Preview JUMLAH SHIFT + TOTAL ===========================//
	
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	$tl->assign('id_req',$req);
	$tl->assign('remark',$remarks);
	$tl->assign('nomors',$nomor);
	$tl->assign('tnglproses',$tglproses);
	$tl->assign('row2',$row_preview2);
	$tl->assign('glc',$col_preview3);
	$tl->assign('strt',$start_work);
	$tl->assign('end',$end_work);
	$tl->assign('shift',$jumlah_shift);
	$tl->assign('tarif',$td);
	$tl->assign('jumlah',$ttl);
	$tl->assign('total',$jml_ttl);
	$tl->assign('ppns',$ppn);
	$tl->assign('adm',$jml_adm);
	$tl->assign('bayar',$jml_byr);
	$tl->assign('byr',$bayar);
	$tl->assign('terbilang',$bilangan);
	$tl->renderToScreen();

?>