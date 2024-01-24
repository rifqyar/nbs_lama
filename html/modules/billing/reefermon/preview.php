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
	$query_nota	= "SELECT b.NM_CONSIGNEE EMKL, b.ALMT_CONSIGNEE ALAMAT, b.NPWP_CONSIGNEE NPWP, b.VESSEL, b.VOYAGE_IN, TO_CHAR(b.TGL_REQ,'dd-mm-yyyy') TGL_REQUEST  
					,TGL_REQ TGL_REQUEST2
                    FROM REQ_MONREEFER b
					WHERE b.ID_REQ = '$no_req'
				   ";
	//echo $query_nota;die;			   
	$result		= $db->query($query_nota);
	$row_nota	= $result->fetchRow();
	$param_tgl  = $row_nota[TGL_REQUEST];
	$param_tgl2  = $row_nota[TGL_REQUEST2];
	//pt ptp
	//$qnama = "select nm_perusahaan from date_reference where cut_date <= to_date('$param_tgl','dd/mm/rrrr') order by cut_date desc";
	$qnama = "select FC_NM_PERUSAHAAN('$param_tgl','RFRMON') NM_PERUSAHAAN from dual";
	$rnama = $db->query($qnama)->fetchRow();
	$corporate_name = $rnama["NM_PERUSAHAAN"];
	$tl->assign('corporate_name',$corporate_name);
	
		
		$sql_xpi = "BEGIN NOTA_MONITORINGREEFER('$no_req'); END;";
		//echo $sql_xpi;die;
		$db->query($sql_xpi);
             
                
	$detail_nota  = " SELECT TO_CHAR(a.TARIF, '999,999,999,999') AS TARIF, 
					  TO_CHAR(a.SUB_TOTAL, '999,999,999,999') AS BIAYA, 
					  a.KETERANGAN, a.JUMLAH_CONT JML_CONT, b.UKURAN SIZE_, b.TYPE TYPE_, b.STATUS STATUS_, JML_SHIFT
					  FROM NOTA_MONREEFER_D_TEMP A left join master_barang b on (a.ID_CONT = b.kode_barang)
					  where a.ID_REQ = '$no_req' and KETERANGAN NOT IN ('ADM','MATERAI')";/**gagat modif 20 februari 2020*/
	//echo $detail_nota ;die;		   
	$result       = $db->query($detail_nota);
	$row_detail   = $result->getAll();
	
	
	$total_		  = "SELECT SUM(SUB_TOTAL) TOTAL FROM NOTA_MONREEFER_D_TEMP WHERE ID_REQ = '$no_req' and KETERANGAN NOT IN ('MATERAI')";/**gagat modif 20 feb 2020*/
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
	//$ppn = $totals/10;
	
	/* CR PPN */
	$query_cut_off1 ="SELECT COUNT(1) JML FROM  MASTER_CUT_OFF
			WHERE JENIS_CUT_OFF ='CUT_OFF_PPN11' AND ( to_char(to_date('$param_tgl2'),'yyyymmdd') >= to_char(start_date,'yyyymmdd') ) 
			AND ( to_char(to_date('$param_tgl2'),'yyyymmdd') <= to_char(end_date,'yyyymmdd') or end_date is null) ";
	$result_cut_off1		= $db->query($query_cut_off1);
	$row_count_off		= $result_cut_off1->fetchRow();
	
	if ($row_count_off['JML'] > 0 ) 
	{
		$query_cut_off = "select VARIABLE from  MASTER_CUT_OFF
			WHERE JENIS_CUT_OFF ='CUT_OFF_PPN11' AND ( to_char(to_date('$param_tgl2'),'yyyymmdd') >= to_char(start_date,'yyyymmdd') ) 
			AND ( to_char(to_date('$param_tgl2'),'yyyymmdd') <= to_char(end_date,'yyyymmdd') or end_date is null)  ";
		$result_cut_off		= $db->query($query_cut_off);
		$row_cut_off		= $result_cut_off->fetchRow();
		
		$var = $row_cut_off['VARIABLE'];
		$ppn = ($totals/100)*$var;
	}
	else
	{
		$ppn = $totals/10;
	}
	//print_r($row_cut_off);die;
	/* CR PPN */
	
	$query_ppn		= "SELECT TO_CHAR('$ppn' , '999,999,999,999') AS PPN FROM DUAL";
	$result_ppn		= $db->query($query_ppn);
	$row_ppn		= $result_ppn->fetchRow();
		
	//Menghitung Bea Materai
	/**gagat modify 30 oktober 2019 add materai */
	//print_r('kode cabang : '.$kd_cabang.' total all '.$total);
	$query_mtr		= "SELECT    SUB_TOTAL 
                    FROM 
                            NOTA_MONREEFER_D_TEMP 
                    WHERE 
                            ID_REQ='$no_req' 
                            AND KETERANGAN = 'MATERAI'";
	$result_mtr		= $db->query($query_mtr);
	$row_mtr		= $result_mtr->fetchRow();
	
	if($row_mtr['SUB_TOTAL'] > 0){
		$bea_materai = $row_mtr['SUB_TOTAL']; 
	}else{
		$bea_materai = 0;
	}
	/***gagat end modify materai 24 oktober 2019 **/
	$query_materai		= "SELECT TO_CHAR('$bea_materai' , '999,999,999,999') AS MATERAI FROM DUAL";
	$result_materai		= $db->query($query_materai);
	$row_materai		= $result_materai->fetchRow();
	
	//Menghitung Jumlah dibayar
	$total_bayar        =  $totals + $ppn + $bea_materai;
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
