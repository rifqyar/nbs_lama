<?php

 $tl = xliteTemplate('print_nota.htm');
 $no_req=$_GET['id'];

 $db=  getDB();
 
	$sql_xpi = "DECLARE no_req VARCHAR2(50); BEGIN no_req :='".$no_req."'; nota_behandle(no_req); END;";
	$db->query($sql_xpi);
	$query_nota	= "SELECT a.EMKL AS EMKL, a.ALAMAT_EMKL AS ALAMAT, a.NPWP, a.VESSEL, a.VOYAGE_IN, TO_CHAR(a.TGL_REQUEST,'dd-mm-yyyy') TGL_REQUEST 
	,TGL_REQUEST TGL_REQUEST2
	FROM REQ_BEHANDLE_H a WHERE a.ID_REQ = '$no_req'";
				   
	$result		= $db->query($query_nota);
	$row_nota	= $result->fetchRow();

	$param_tgl  = $row_nota[TGL_REQUEST];
	$param_tgl2  = $row_nota[TGL_REQUEST2];
	
	//pt ptp
	/*ipctpk*/
	//$qnama = "select fc_nm_perusahaan('$param_tgl','BH') NM_PERUSAHAAN from dual";
	$qnama = "select nm_perusahaan from date_reference where to_date('$param_tgl','dd/mm/rrrr') between cut_date and off_date order by cut_date desc";
	/**/
	$rnama = $db->query($qnama)->fetchRow();
	$tl->assign('corporate_name',$rnama['NM_PERUSAHAAN']);
	
	$detail_nota  = " SELECT TO_CHAR(SUM(a.TARIF), '999,999,999,999') AS TARIF, TO_CHAR(SUM(a.SUB_TOTAL), '999,999,999,999') AS BIAYA, a.NO_CONTAINER,'BEHANDLE' AS KETERANGAN, a.HZ, a.JUMLAH_CONT JML_CONT, b.UKURAN SIZE_, b.TYPE TYPE_, b.STATUS FROM NOTA_BEHANDLE_D_TMP a, master_barang b WHERE a.ID_BARANG = b.KODE_BARANG and a.ID_REQ = '$no_req' GROUP BY  a.NO_CONTAINER, a.HZ, a.JUMLAH_CONT , b.UKURAN , b.TYPE , b.STATUS";
				 //  print_r($detail_nota);die;
	$result       = $db->query($detail_nota);
	$row_detail   = $result->getAll();
	
	foreach ($row_detail as $rr)
	{
		$ct=$rr['NO_CONTAINER'].';';
	}
	
	$total_		  = "SELECT SUM(SUB_TOTAL) TOTAL FROM NOTA_BEHANDLE_D_TMP WHERE ID_REQ = '$no_req'";
				   
	$result		  = $db->query($total_);
	$total2       = $result->fetchRow();
        
    $total = $total2['TOTAL'];
	
	//$no_request = $row_nota["NO_REQUEST"];
	
	//Discount
	$discount =0;
	$query_discount		= "SELECT TO_CHAR($discount , '999,999,999,999') AS DISCOUNT FROM DUAL";
        $result_discount	= $db->query($query_discount);
	$row_discount		= $result_discount->fetchRow();
	//Biaya Administrasi
	$jml_cont	= "SELECT COUNT(NO_CONTAINER) JML FROM REQ_BEHANDLE_D WHERE ID_REQ = '$no_req'";
        //echo $total_;die;
	$result		= $db->query($jml_cont);
	$jml     	= $result->fetchRow();
	$jml_cont   = $jml['JML'];

	// nota behandle tidak ada adm
/*	$query_adm		= "SELECT TO_CHAR(SUB_TOTAL , '999,999,999,999') AS ADM FROM BH_DETAIL_NOTA_TMP WHERE ID_REQUEST = '$no_req' AND KETERANGAN='ADM'";
	$result_adm		= $db->query($query_adm);
	$row_adm		= $result_adm->fetchRow();*/
		
	//Menghitung Total dasar pengenaan pajak
		//$total_ = $total + $adm;	//adm sudah masuk detail nota tmp
	$total_ = $total;	
	$query_tot		= "SELECT TO_CHAR('$total_' , '999,999,999,999') AS TOTAL_ALL FROM DUAL"; //ematerai (Total asli)
	$result_tot		= $db->query($query_tot);
	$row_tot		= $result_tot->fetchRow();
		
	//Menghitung Jumlah PPN
	
	//$ppn = $total_/10;
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
		$ppn = ($total_/100)*$var;
	}
	else
	{
		$ppn = $total_/10;
	}
	//print_r($row_cut_off);die;
	/* CR PPN */
	
	$query_ppn		= "SELECT TO_CHAR('$ppn' , '999,999,999,999') AS PPN FROM DUAL";
	$result_ppn		= $db->query($query_ppn);
	$row_ppn		= $result_ppn->fetchRow();
	
/*//start Implementasi einvoice ematerai, fauzan 10 Oktober 2019
	 */
	$query_mtr		= "SELECT    SUB_TOTAL 
                    FROM 
                            NOTA_BEHANDLE_D_TMP 
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
/**fauzan end modify materai 17 oktober 2019**/	
	$query_materai		= "SELECT TO_CHAR('$bea_materai' , '999,999,999,999') AS MATERAI FROM DUAL";
	$result_materai		= $db->query($query_materai);
	$row_materai		= $result_materai->fetchRow();
	
	//Menghitung Jumlah dibayar
	$total_bayar        =  $total_ + $ppn + $bea_materai; /**fauzan modify bea materai 17 oktober 2019 [ +$bea_materai ]**/
	$query_bayar        = "SELECT TO_CHAR('$total_bayar' , '999,999,999,999') AS TOTAL_BAYAR FROM DUAL";
	$result_bayar       = $db->query($query_bayar);
	$row_bayar          = $result_bayar->fetchRow();
	
	//cek sudah jadi pranota atau belum
	$q_cek="select count(*) as JUM from NOTA_BEHANDLE_H where trim(ID_REQ)=trim('".$no_req."') and STATUS='S'";
	//print_r($q_cek);die;
	$rs_cek = $db->query($q_cek);
	$row_cek = $rs_cek->fetchRow();
	$jum_cek=$row_cek['JUM'];	
	$tl->assign("row_ct",$ct);	
	$tl->assign("row_discount",$row_discount);	
	//$tl->assign("row_adm",$row_adm);
	$tl->assign("row_tot",$row_tot);
	$tl->assign("row_ppn",$row_ppn);
	$tl->assign("row_materai",$row_materai);
	$tl->assign("row_bayar",$row_bayar);
	$tl->assign("row_trf_brsh",$row_trf_brsh);
	$tl->assign("row_nota",$row_nota);
	$tl->assign("no_nota",$no_nota);
	$tl->assign("no_req",$no_req);
	$tl->assign("row_detail",$row_detail);
	$tl->assign("jum_cek",$jum_cek);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID); 
	$tl->renderToScreen();

?>