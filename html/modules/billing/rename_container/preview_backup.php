<?php
	
	$tl =  xliteTemplate('print_nota.htm');

	$db = getDB();
	
	//$no_nota	= $_GET["no_nota"];
	$no_req		= $_GET["id"];
	
	
	//Set Preview-----------------------
	
	//Membuat nomor nota
	//Format: Kode-Kode Cabang-Bulan-Tahun-No Urut
	//Ex: STR050712000001
	 
	
	//--------------------------
	$query_nota	= "SELECT b.PELANGGAN EMKL, b.ALAMAT, b.NPWP, b.TGL_RENAME, b.VESSEL, b.VOYAGE_IN, to_char(b.TGL_RENAME,'dd-mm-yyyy') TGL_REQUEST FROM REQ_RENAME b
					WHERE b.ID_REQ = '$no_req'
				   ";
	//echo $query_nota;die;			   
	$result		= $db->query($query_nota);
	$row_nota	= $result->fetchRow();
	$param_tgl  = $row_nota[TGL_REQUEST];
	//pt ptp
	//$qnama = "select nm_perusahaan from date_reference where cut_date <= to_date('$param_tgl','dd/mm/rrrr') order by cut_date desc";
	/*ipctpk*/
	//$qnama = "select FC_NM_PERUSAHAAN('$param_tgl','RENA') NM_PERUSAHAAN from dual";
	$qnama = "select nm_perusahaan from date_reference where to_date('$param_tgl','dd/mm/rrrr') between cut_date and off_date order by cut_date desc";
	/**/
	$rnama = $db->query($qnama)->fetchRow();
	$corporate_name = $rnama["NM_PERUSAHAAN"];
	$tl->assign('corporate_name',$corporate_name);
	
		
		$sql_xpi = "BEGIN NOTA_RENAMECONTAINER('$no_req'); END;";
		//echo $sql_xpi;die;
		$db->query($sql_xpi);
             
                
	$detail_nota  = " SELECT TO_CHAR(a.TARIF, '999,999,999,999') AS TARIF, 
					  TO_CHAR(a.SUB_TOTAL, '999,999,999,999') AS BIAYA, 
					  a.KETERANGAN, a.JUMLAH_CONT JML_CONT, b.UKURAN SIZE_, b.TYPE TYPE_, b.STATUS STATUS_
					  FROM NOTA_RENAME_D_TEMP A left join master_barang b on (a.ID_CONT = b.kode_barang)
					  where a.ID_REQ = '$no_req' and KETERANGAN <> 'ADM'";
	//echo $detail_nota ;die;		   
	$result       = $db->query($detail_nota);
	$row_detail   = $result->getAll();
	
	
	$total_		  = "SELECT SUM(SUB_TOTAL) TOTAL FROM NOTA_RENAME_D_TEMP WHERE ID_REQ = '$no_req'";
	//echo $total_ ;die;
				   
	$result		  = $db->query($total_);
	$total2       = $result->fetchRow();
        
    $total = $total2['TOTAL'];
	
	//$no_request = $row_nota["NO_REQUEST"];
	
	//Discount
	$discount =0;
	$query_discount		= "SELECT TO_CHAR($discount , '999,999,999,999') AS DISCOUNT FROM DUAL";
    $result_discount	= $db->query($query_discount);
	$row_discount		= $result_discount->fetchRow();
	
	
	$jml_cont   = 1;
	$adm=10000;
    //$adm   			= $jml_cont*10000;
	$query_adm		= "SELECT TO_CHAR($adm , '999,999,999,999') AS ADM FROM DUAL";
	$result_adm		= $db->query($query_adm);
	$row_adm		= $result_adm->fetchRow();
		
	//Menghitung Total dasar pengenaan pajak
    $totals 		= $total;
	$query_tot		= "SELECT TO_CHAR('$totals' , '999,999,999,999') AS TOTAL_ALL FROM DUAL";
	$result_tot		= $db->query($query_tot);
	$row_tot		= $result_tot->fetchRow();
		
	//Menghitung Jumlah PPN
	$ppn = $totals/10;
	$query_ppn		= "SELECT TO_CHAR('$ppn' , '999,999,999,999') AS PPN FROM DUAL";
	$result_ppn		= $db->query($query_ppn);
	$row_ppn		= $result_ppn->fetchRow();
		
	//Menghitung Bea Materai
	$bea_materai = 0;
	$query_materai		= "SELECT TO_CHAR('$bea_materai' , '999,999,999,999') AS MATERAI FROM DUAL";
	$result_materai		= $db->query($query_materai);
	$row_materai		= $result_materai->fetchRow();
	
	//Menghitung Jumlah dibayar
	$total_bayar        =  $totals + $ppn;
	$query_bayar        = "SELECT TO_CHAR('$total_bayar' , '999,999,999,999') AS TOTAL_BAYAR FROM DUAL";
	$result_bayar       = $db->query($query_bayar);
	$row_bayar          = $result_bayar->fetchRow();
	
	//debug($no_req);die;
	$tl->assign("row_discount",$row_discount);	
	$tl->assign("row_adm",$row_adm);
	$tl->assign("row_tot",$row_tot);
	$tl->assign("row_ppn",$row_ppn);
	$tl->assign("row_materai",$row_materai);
	$tl->assign("row_bayar",$row_bayar);
	$tl->assign("row_trf_brsh",$row_trf_brsh);
	$tl->assign("row_nota",$row_nota);
	$tl->assign("no_nota",$no_nota);
	$tl->assign("no_req",$no_req);
	$tl->assign("row_detail",$row_detail);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);

	$tl->renderToScreen();
?>
