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
	$query_nota	= "SELECT b.EMKL, b.ALAMAT, b.NPWP, b.TGL_REQ,b.VESSEL, b.VOYAGE,
				  (SELECT KEGIATAN FROM MASTER_PROSES_KEGIATAN a WHERE b.JENIS = a.ID_KEG) KEG
				  FROM tb_batalmuat_h b
				  WHERE b.ID_BATALMUAT = '$no_req'
				   ";
				   
	$result		= $db->query($query_nota);
	$row_nota	= $result->fetchRow();
	$param_tgl  = $row_nota[TGL_REQ];
        
    //Nama Perusahaan
    $qnama = "select fc_nm_perusahaan('$notanya', 'ANNE') NM_PERUSAHAAN from dual";    
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
                                TEMP_NOTA_BATALMUAT_D a, 
                                MASTER_BARANG b 
                      WHERE 
                                a.ID_CONT = b.KODE_BARANG 
                                AND a.ID_BATALMUAT = '$no_req' 
                                AND a.KETERANGAN <> 'ADM'";
        
        //echo $detail_nota;
        //die();
				   
	$result       = $db->query($detail_nota);
	$row_detail   = $result->getAll();	
	
	$total_		  = "SELECT SUM(TOTAL) TOTAL FROM TEMP_NOTA_BATALMUAT_D WHERE ID_BATALMUAT = '$no_req' AND KETERANGAN <> 'ADM'";				   
	$result		  = $db->query($total_);
	$total2       = $result->fetchRow();        
        $total = $total2['TOTAL'];	
	
	//Discount
	$discount =0;
	$query_discount		= "SELECT TO_CHAR($discount , '999,999,999,999') AS DISCOUNT FROM DUAL";
        $result_discount	= $db->query($query_discount);
	$row_discount		= $result_discount->fetchRow();
	
	//Biaya Administrasi
	$jml_cont	= "SELECT COUNT(NO_CONTAINER) JML FROM tb_batalmuat_d WHERE ID_BATALMUAT = '$no_req'";
        //echo $total_;die;
        $result		= $db->query($jml_cont);
        $jml     	= $result->fetchRow();
		$jml_cont   = $jml['JML'];

        //$adm   			= $jml_cont*10000;
	$query_adm		= "SELECT TO_CHAR(SUM(TOTAL) , '999,999,999,999') AS ADM, SUM(TOTAL) ADM1 FROM TEMP_NOTA_BATALMUAT_D WHERE ID_BATALMUAT = '$no_req' AND KETERANGAN = 'ADM'";
	$result_adm		= $db->query($query_adm);
	$row_adm		= $result_adm->fetchRow();
		
	//Menghitung Total dasar pengenaan pajak
        $totals 		= $total + $row_adm['ADM1'];
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
