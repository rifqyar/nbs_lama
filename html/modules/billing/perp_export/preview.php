<?php
 $db=  getDB();
  $tl = xliteTemplate('print_nota.htm');
 $no_req=$_GET['id']; 
 
 $qry_kapal = "SELECT 
						VESSEL, 
						VOYAGE_IN, 
						VOYAGE_OUT 				
				FROM 
						REQ_STACKEXT_H 
				WHERE 
						ID_REQ = '$no_req'";
	$result_kapal       = $db->query($qry_kapal);
	$row_kapal = $result_kapal->fetchRow();
	
	$vessel = $row_kapal['VESSEL'];
	$voyage_in = $row_kapal['VOYAGE_IN'];
	$voyage_out = $row_kapal['VOYAGE_OUT'];	

	$qry_atd = "SELECT 
 					to_date(ATD,'yyyymmddhh24miss') as ATD 
 		 	FROM
 		 	 		M_VSB_VOYAGE@dbint_link
 		 	WHERE 
 		 			VESSEL = '$vessel'
 		 			AND VOYAGE_IN = '$voyage_in'
 		 			AND VOYAGE_OUT = '$voyage_out' ";

 	$result_atd       = $db->query($qry_atd);
	$row_atd = $result_atd->fetchRow();	 			

 $sql_xpi = "BEGIN PRANOTA_STACKEXT_TEMP2('$no_req','$vessel','$voyage_in','$voyage_out'); END;";				
 $db->query($sql_xpi);

    //echo $sql_xpi; die;
	
	$query_nota	= "SELECT 
							A.VESSEL,
							A.VOYAGE_IN,
							A.VOYAGE_OUT,
							A.SHIPPING_LINE,
                         	A.ALAMAT,
                         	A.NPWP,
                         	MIN (B.TGL_START_STACK) TGL_START_STACK,
                         	MAX (B.TGL_END_STACK) TGL_END_STACK,
                         	to_char(A.TGL_REQUEST,'dd-mm-yyyy') TGL_REQUEST
							,TGL_REQUEST TGL_REQUEST2
                    FROM    REQ_STACKEXT_H A
                         	LEFT JOIN
                            NOTA_STACKEXT_D_TMP B
                         	ON TRIM (A.ID_REQ) = TRIM (B.ID_REQ)
                   	WHERE 
                   			A.ID_REQ = '$no_req'
                	GROUP BY 
                			A.VOYAGE_IN,
							A.VOYAGE_OUT,
                			A.VESSEL,
                			A.SHIPPING_LINE,
                         	A.ALAMAT,
                         	A.NPWP,
                         	A.TGL_REQUEST";		
								
					
	$result		= $db->query($query_nota);
	$row_nota	= $result->fetchRow();
	
	$param_tgl  = $row_nota[TGL_REQUEST];
	$param_tgl2  = $row_nota[TGL_REQUEST2];
	$kapal  = $row_nota[VESSEL];
	//pt ptp
	/*ipctpk*/
	//$qnama = "select nm_perusahaan from date_reference where cut_date <= to_date('$param_tgl','dd/mm/rrrr') order by cut_date desc";
	$qnama = "select nm_perusahaan from date_reference where to_date('$param_tgl','dd/mm/rrrr') between cut_date and off_date order by cut_date desc";
	/**/
	$rnama = $db->query($qnama)->fetchRow();
	$tl->assign('corporate_name',$rnama['NM_PERUSAHAAN']);

	$detail_nota  = "SELECT TO_CHAR (a.TARIF, '999,999,999,999') AS TARIF,
                             TO_CHAR (SUM (a.SUB_TOTAL), '999,999,999,999') AS BIAYA,
                             a.KETERANGAN,
                             a.HZ,
                             sum(a.JUMLAH_CONT) JML_CONT,
                             a.JUMLAH_HARI,
                             b.UKURAN SIZE_,
                             b.TYPE TYPE_,
                             b.STATUS,
                             TO_CHAR (a.TGL_START_STACK, 'dd/mm/yyyy') START_STACK,
                             TO_CHAR (a.TGL_END_STACK, 'dd/mm/yyyy') END_STACK
                        FROM NOTA_STACKEXT_D_TMP a, master_barang b
                       WHERE     a.ID_CONT = b.KODE_BARANG
                             AND a.ID_REQ = '$no_req'
                             AND a.ID_CONT IS NOT NULL
                    GROUP BY a.KETERANGAN,
                             a.HZ,
                             a.JUMLAH_CONT,
                             a.JUMLAH_HARI,
                             b.UKURAN,
                             b.TYPE,
                             b.STATUS,
                             a.TGL_START_STACK,
                             a.TGL_END_STACK,
                             a.TARIF
                    ORDER BY 
                            KETERANGAN,
                            START_STACK,
                            SIZE_,
                            TYPE_,
                            STATUS ASC";
				 //  print_r($detail_nota);die;
	$result       = $db->query($detail_nota);
	$row_detail   = $result->getAll();
	
	
	$total_		  = "SELECT SUM(SUB_TOTAL) TOTAL FROM NOTA_STACKEXT_D_TMP WHERE ID_REQ = '$no_req' and KETERANGAN NOT IN ('MATERAI')";
				   
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
	$query_adm		= "SELECT TO_CHAR(SUB_TOTAL , '999,999,999,999') AS ADM, SUB_TOTAL FROM NOTA_STACKEXT_D_TMP WHERE ID_REQ = '$no_req' AND KETERANGAN='ADM'";
	$result_adm		= $db->query($query_adm);
	$row_adm		= $result_adm->fetchRow();
    $adm            = $row_adm['SUB_TOTAL'];
	//Menghitung Total dasar pengenaan pajak
    //$total_ = $total + $adm;	//adm sudah masuk detail nota tmp
    $total_ = $total;	
	$query_tot		= "SELECT TO_CHAR('$total_' , '999,999,999,999') AS TOTAL_ALL FROM DUAL";
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
		
	//Menghitung Bea Materai
	$query_mtr		= "SELECT SUB_TOTAL FROM NOTA_STACKEXT_D_TMP 
                    WHERE ID_REQ='$no_req' AND KETERANGAN = 'MATERAI'";
	///print_r($query_mtr);die();				
	$result_mtr		= $db->query($query_mtr);
	$row_mtr		= $result_mtr->fetchRow();
	
	if($row_mtr['SUB_TOTAL'] > 0){
		$bea_materai = $row_mtr['SUB_TOTAL']; 
	}else{
		$bea_materai = 0;
	}
	
	$query_materai		= "SELECT TO_CHAR('$bea_materai' , '999,999,999,999') AS MATERAI FROM DUAL";
	$result_materai		= $db->query($query_materai);
	$row_materai		= $result_materai->fetchRow();
	
	//Menghitung Jumlah dibayar
	$total_bayar        =  $total_ + $ppn + $bea_materai; /**gagat add materai 08 feb 2020 */
	$query_bayar        = "SELECT TO_CHAR('$total_bayar' , '999,999,999,999') AS TOTAL_BAYAR FROM DUAL";
	$result_bayar       = $db->query($query_bayar);
	$row_bayar          = $result_bayar->fetchRow();
	
	//cek sudah jadi pranota atau belum
	$q_cek="select count(*) as JUM from NOTA_STACKEXT_H where trim(ID_REQUEST)=trim('".$no_req."') and STATUS='S'";
	//print_r($q_cek);die;
	$rs_cek = $db->query($q_cek);
	$row_cek = $rs_cek->fetchRow();
	$jum_cek=$row_cek['JUM'];	
	
	$tl->assign("row_discount",$row_discount);	
	$tl->assign("row_atd",$row_atd);
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
	$tl->assign("jum_cek",$jum_cek);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
 
 $tl->renderToScreen();

?>