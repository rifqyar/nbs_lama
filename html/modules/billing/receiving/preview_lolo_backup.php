<?php	
	$tl =  xliteTemplate('print_nota_lolo.htm');
	$db = getDB();	
	//$no_nota	= $_GET["no_nota"];
	$no_req		= $_GET["id"];
	
	//Set Preview-----------------------	
	//Membuat nomor nota
	//Format: Kode-Kode Cabang-Bulan-Tahun-No Urut
	//Ex: STR050712000001	
	//--------------------------

	$query_nota	= "SELECT 
                                    b.KODE_PBM AS EMKL, 
                                    b.ALAMAT, b.NPWP, 
                                    to_char(b.TGL_REQUEST,'dd-mm-yyyy') TGL_REQUEST, VESSEL, 
                                    VOYAGE_IN 
                           FROM 
                                    req_receiving_h b
                           WHERE 
                                    b.ID_REQ = '$no_req'";
				   
	$result		= $db->query($query_nota);
	$row_nota	= $result->fetchRow();
	$param_tgl      = $row_nota[TGL_REQUEST];
	//pt ptp
	/*ipctpk*/
	//$qnama = "select fc_nm_perusahaan('$param_tgl', 'ANNE') NM_PERUSAHAAN from dual";    
	$qnama = "select nm_perusahaan from date_reference where to_date('$param_tgl','dd/mm/rrrr') between cut_date and off_date order by cut_date desc";
	/**/
    $rnama = $db->query($qnama)->fetchRow();
        $rname=$rnama['NM_PERUSAHAAN'];

	//$no_request = $row_nota["NO_REQUEST"];
	
	$query_tgl	= "SELECT 
							TGL_STACK,  
							TGL_OPEN_STACK, 
							SHIFT_REEFER 
				   FROM 
							req_receiving_h 
                   WHERE 
							ID_REQ = '$no_req'
                            ";
	//echo $query_tgl;die;			   
	$result		= $db->query($query_tgl);
	$tgl_req	= $result->fetchRow();
    $tglstack       = $tgl_req['TGL_STACK'];
	$tglopenstack   = $tgl_req['TGL_OPEN_STACK'];
	$shift   	= $tgl_req['SHIFT_REEFER'];
	
       // echo $tgl_re;die;
        
		$parameter= array(
					'id_nota'=>1,
                    'tgl_req'=>$tgl_re,
					'no_request:20'=>$no_req,
					'err_msg:100'=>'NULL'
					);
		$sql_xpi = "BEGIN nota_anne_v4_lolo('$no_req','$tglstack','$tglopenstack'); END;";		
		$db->query($sql_xpi);
                
	$detail_nota  = "SELECT 
                                TO_CHAR(a.TARIF, '999,999,999,999') AS TARIF, 
                                TO_CHAR(a.SUB_TOTAL, '999,999,999,999') AS BIAYA, 
                                a.KETERANGAN, a.HZ, a.JUMLAH_CONT JML_CONT, 
                                TO_DATE(a.TGL_START_STACK,'dd/mm/yyyy') START_STACK, 
                                TO_DATE(a.TGL_END_STACK,'dd/mm/yyyy') END_STACK, 
                                b.UKURAN SIZE_, b.TYPE TYPE_, b.STATUS, a.JUMLAH_HARI 
                          FROM 
                                nota_receiving_d_tmp a , master_barang b 
                          WHERE 
                                a.ID_CONT = b.KODE_BARANG(+) 
                                and a.ID_REQ = '$no_req'
                                and KETERANGAN <> 'ADM'";
				   
	$result       = $db->query($detail_nota);
	$row_detail   = $result->getAll();
	
	$total_		  = "SELECT 
                                    SUM(SUB_TOTAL) TOTAL 
                             FROM 
                                    nota_receiving_d_tmp 
                             WHERE 
                                    ID_REQ = '$no_req' and keterangan<>'ADM'";
	$result		  = $db->query($total_);
	$total2       = $result->fetchRow();        
        $total = $total2['TOTAL'];	
	
	//Discount
	$discount =0;
	$query_discount		= "SELECT TO_CHAR($discount , '999,999,999,999') AS DISCOUNT FROM DUAL";
        $result_discount	= $db->query($query_discount);
	$row_discount		= $result_discount->fetchRow();
        
	//Biaya Administrasi 
        //Untuk Pontianak Flat Perdokumen 3500
        $qry_adm = "SELECT  
                            KETERANGAN, TARIF, SUB_TOTAL 
                    FROM 
                            NOTA_RECEIVING_D_TMP 
                    WHERE 
                            ID_REQ='A201412027589' 
                            AND KETERANGAN = 'ADM'";
        $result_adm		  = $db->query($qry_adm);
	$biaya_adm       = $result_adm->fetchRow();        
        $biaya_administrasi = $biaya_adm['SUB_TOTAL'];	
        
		
	//Menghitung Total dasar pengenaan pajak
        $total_ = $total + $biaya_administrasi;
	$query_tot		= "SELECT TO_CHAR('$total_' , '999,999,999,999') AS TOTAL_ALL FROM DUAL";
	$result_tot		= $db->query($query_tot);
	$row_tot		= $result_tot->fetchRow();
		
	//Menghitung Jumlah PPN
	$ppn = ceil($total_/10);
	$query_ppn		= "SELECT TO_CHAR('$ppn' , '999,999,999,999') AS PPN FROM DUAL";
	$result_ppn		= $db->query($query_ppn);
	$row_ppn		= $result_ppn->fetchRow();
		
	//Menghitung Bea Materai
	$bea_materai = 0;
	$query_materai		= "SELECT TO_CHAR('$bea_materai' , '999,999,999,999') AS MATERAI FROM DUAL";
	$result_materai		= $db->query($query_materai);
	$row_materai		= $result_materai->fetchRow();
	
	//Menghitung Jumlah dibayar
	$total_bayar        =  $total_ + $ppn;
	$query_bayar        = "SELECT TO_CHAR('$total_bayar' , '999,999,999,999') AS TOTAL_BAYAR FROM DUAL";
	$result_bayar       = $db->query($query_bayar);
	$row_bayar          = $result_bayar->fetchRow();
	
	//debug($no_req);die;
        $tl->assign("row_nama",$rname);
	$tl->assign("row_discount",$row_discount);	
	$tl->assign("row_adm",$biaya_administrasi);
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
