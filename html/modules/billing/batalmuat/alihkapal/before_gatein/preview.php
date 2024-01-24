<?php
	$tl =  xliteTemplate('print_nota.htm');
	$db = getDB();	
	//$no_nota	= $_GET["no_nota"];
	$no_req		= $_GET["id"];
	$jenis		= $_GET["jenis"];
	
	//Set Preview-----------------------
	
	//Membuat nomor nota
	//Format: Kode-Kode Cabang-Bulan-Tahun-No Urut
	//Ex: STR050712000001 
	
	//--------------------------
	$query_nota	= "SELECT b.EMKL, b.ALAMAT, b.NPWP, to_char(b.TGL_REQ,'dd-mm-yyyy') TGL_REQ, b.VESSEL, b.VOYAGE_IN,b.VOYAGE_OUT,
				  (SELECT KEGIATAN FROM MASTER_PROSES_KEGIATAN a WHERE b.JENIS = a.ID_KEG) KEG,TGL_REQ TGL_REQ2
				  FROM req_batalmuat_h b
				  WHERE b.ID_REQ = '$no_req'
				   ";
				   
	$result		= $db->query($query_nota);
	$row_nota	= $result->fetchRow();
	$param_tgl  = $row_nota[TGL_REQ];
	$param_tgl2  = $row_nota[TGL_REQ2];
	
		
    //Nama Perusahaan
    /*ipctpk*/
	//$qnama = "select fc_nm_perusahaan('$param_tgl', 'ANNE') NM_PERUSAHAAN from dual";    
    $qnama = "select nm_perusahaan from date_reference where to_date('$param_tgl','dd/mm/rrrr') between cut_date and off_date order by cut_date desc"; 
	/**/
	$rnama = $db->query($qnama)->fetchRow();
    $rname=$rnama['NM_PERUSAHAAN'];
	
	//echo "BEGIN PROC_BATALMUAT('$no_req','$jenis'); END;";die;
		
		$sql_xpi = "BEGIN PROC_BATALMUAT('$no_req','$jenis'); END;";				
		$db->query($sql_xpi);            
                
	$detail_nota  = "SELECT 
                                TO_CHAR(a.TARIF, '999,999,999,999') AS TARIF, 
                                TO_CHAR(a.TOTAL, '999,999,999,999') AS BIAYA, 
                                a.KETERANGAN, 
                                a.HZ, 
                                a.JUMLAH_CONT JML_CONT, 
                                TO_DATE(a.TGL_START_STACK,'dd/mm/yyyy') START_STACK, 
                                TO_DATE(a.TGL_END_STACK,'dd/mm/yyyy') END_STACK, 
                                b.UKURAN SIZE_, 
                                b.TYPE TYPE_, 
                                b.STATUS 
                      FROM 
                                NOTA_BATALMUAT_D_TMP a, 
                                MASTER_BARANG b 
                      WHERE 
                                a.ID_CONT = b.KODE_BARANG 
                                AND a.ID_REQ = '$no_req' 
                                AND a.KETERANGAN NOT IN ('ADM','MATERAI') /**18 Oktober 2019 fauzan modify and KETERANGAN NOT IN ('MATERAI')**/";
        
        //echo $detail_nota;
        //die();
				   
	$result       = $db->query($detail_nota);
	$row_detail   = $result->getAll();	
	
	$total_		  = "SELECT SUM(TOTAL) TOTAL FROM NOTA_BATALMUAT_D_TMP WHERE ID_REQ = '$no_req' AND KETERANGAN NOT IN ('ADM','MATERAI')";/**gagat modif 20 februari 2020*/				   
	$result		  = $db->query($total_);
	$total2       = $result->fetchRow();        
        $total = $total2['TOTAL'];	
	
	//Discount
	$discount =0;
	$query_discount		= "SELECT TO_CHAR($discount , '999,999,999,999') AS DISCOUNT FROM DUAL";
        $result_discount	= $db->query($query_discount);
	$row_discount		= $result_discount->fetchRow();
	
	//Biaya Administrasi
	$jml_cont	= "SELECT COUNT(NO_CONTAINER) JML FROM REQ_batalmuat_d WHERE ID_REQ = '$no_req'";
        //echo $total_;die;
        $result		= $db->query($jml_cont);
        $jml     	= $result->fetchRow();
		$jml_cont   = $jml['JML'];

        //$adm   			= $jml_cont*10000;
	$query_adm		= "SELECT TO_CHAR(SUM(TOTAL) , '999,999,999,999') AS ADM, SUM(TOTAL) ADM1 FROM NOTA_BATALMUAT_D_TMP WHERE ID_REQ = '$no_req' AND KETERANGAN = 'ADM'";
	$result_adm		= $db->query($query_adm);
	$row_adm		= $result_adm->fetchRow();
		
	//Menghitung Total dasar pengenaan pajak
        $totals 		= $total + $row_adm['ADM1'];
	$query_tot		= "SELECT TO_CHAR('$totals' , '999,999,999,999') AS TOTAL_ALL, $totals AS TOTAL FROM DUAL"; //ematerai (Total asli)
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

/*//start Implementasi einvoice ematerai, fauzan 14 Oktober 2019
	 */
	$query_mtr		= "SELECT TOTAL AS BEA_MATERAI FROM NOTA_BATALMUAT_D_TMP WHERE ID_REQ = '$no_req' AND KETERANGAN = 'MATERAI'";
	$result_mtr		= $db->query($query_mtr);
	$row_mtr		= $result_mtr->fetchRow();	
	
	if ($row_mtr['BEA_MATERAI']>0){ 
		$bea_materai = $row_mtr['BEA_MATERAI']; 
	}else{
		$bea_materai=0;
	}
	
		
/**fauzan end modify materai 17 oktober 2019**/
	$query_materai		= "SELECT TO_CHAR('$bea_materai' , '999,999,999,999') AS MATERAI FROM DUAL";
	$result_materai		= $db->query($query_materai);
	$row_materai		= $result_materai->fetchRow();
	
	//Menghitung Jumlah dibayar
	$total_bayar        =  $totals + $ppn + $bea_materai; /**fauzan modify bea materai 17 oktober 2019 [ +$bea_materai ]**/
	$query_bayar        = "SELECT TO_CHAR('$total_bayar' , '999,999,999,999') AS TOTAL_BAYAR FROM DUAL";
	$result_bayar       = $db->query($query_bayar);
	$row_bayar          = $result_bayar->fetchRow();
	
	//debug($no_req);die;
    $tl->assign("row_nama",$rname);
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
