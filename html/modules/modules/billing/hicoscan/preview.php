<?php
 $tl = xliteTemplate('print_nota.htm');
 $no_req=$_GET['id'];

 $db=  getDB();

 $sql_xpi = "DECLARE no_req VARCHAR2(50); BEGIN no_req :='".$no_req."'; nota_hicoscan(no_req); END;";
                //echo $sql_xpi;die;
				
		$db->query($sql_xpi);
		
		
		$query_nota	= "SELECT a.EMKL AS EMKL, a.ALAMAT_EMKL AS ALAMAT, a.NPWP, a.TGL_REQUEST FROM REQ_HICOSCAN a WHERE a.ID_REQUEST = '$no_req'
				   ";
				   
	$result		= $db->query($query_nota);
	$row_nota	= $result->fetchRow();
	
	$param_tgl  = $row_nota[TGL_REQUEST];
	//pt ptp
	$qnama = "select nm_perusahaan from date_reference where cut_date <= to_date('$param_tgl','dd/mm/rrrr') order by cut_date desc";
	$rnama = $db->query($qnama)->fetchRow();
	$corporate_name = $rnama["NM_PERUSAHAAN"];
	$tl->assign('corporate_name',$corporate_name);

$detail_nota  = "SELECT TO_CHAR(a.TARIF, '999,999,999,999') AS TARIF, 
				TO_CHAR(a.SUB_TOTAL, '999,999,999,999') AS BIAYA, 
				a.KETERANGAN, 
				a.HZ, a.JUMLAH_CONT JML_CONT, 
				b.UKURAN SIZE_, b.TYPE TYPE_, 
				b.STATUS FROM nota_hicoscan_tmp a  left join
				master_barang b on a.ID_BARANG = b.KODE_BARANG WHERE 
				a.ID_REQUEST = '$no_req' AND a.KETERANGAN!='ADM'";
				 //  print_r($detail_nota);die;
	$result       = $db->query($detail_nota);
	$row_detail   = $result->getAll();
	
	
	$total_		  = "SELECT SUM(SUB_TOTAL) TOTAL FROM nota_hicoscan_tmp WHERE ID_REQUEST = '$no_req'";
				   
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
	$jml_cont	= "SELECT COUNT(NO_CONTAINER) JML FROM req_hicoscan_d WHERE ID_REQUEST = '$no_req'";
        //echo $total_;die;
        $result		= $db->query($jml_cont);
        $jml     	= $result->fetchRow();
		$jml_cont   = $jml['JML'];

    //    $adm   = 10000+($jml_cont*10000);
	//$query_adm		= "SELECT TO_CHAR($adm , '999,999,999,999') AS ADM FROM DUAL";
	$query_adm		= "SELECT TO_CHAR(SUB_TOTAL , '999,999,999,999') AS ADM, SUB_TOTAL FROM nota_hicoscan_tmp WHERE KETERANGAN='ADM' AND ID_REQUEST = '$no_req'";
	$result_adm		= $db->query($query_adm);
	$row_adm		= $result_adm->fetchRow();
	$adm = $row_adm["SUB_TOTAL"];
	$adm1 = $row_adm["ADM"];
		
	//Menghitung Total dasar pengenaan pajak
    $total_ = $total;
	$query_tot		= "SELECT TO_CHAR('$total_' , '999,999,999,999') AS TOTAL_ALL FROM DUAL";
	$result_tot		= $db->query($query_tot);
	$row_tot		= $result_tot->fetchRow();
		
	//Menghitung Jumlah PPN
	$ppn = $total_/10;
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
	
	$tl->assign("row_discount",$row_discount);	
	$tl->assign("row_adm",$adm1);
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