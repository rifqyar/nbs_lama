<?php
 $tl = xliteTemplate('print_nota.htm');
 $no_req=$_GET['id'];
 //$decode=json_decode($id);
 //print_r($id);die;
 $db=  getDB();

 $sql_xpi = "DECLARE no_req VARCHAR2(50); BEGIN no_req :='".$no_req."'; nota_reexport(no_req); END;";
                //echo $sql_xpi;die;
				
 $db->query($sql_xpi);
		
		
	$query_nota	= "SELECT A.SHIPPING_LINE, A.ALAMAT, A.NPWP, B.TGL_START_STACK, B.TGL_END_STACK
					FROM REQ_REEXPORT_H A LEFT JOIN NOTA_REEXPORT_D_TMP B ON A.ID_REQ=B.ID_REQ
					WHERE A.ID_REQ = '$no_req'";
	$result		= $db->query($query_nota);
	$row_nota	= $result->fetchRow();
	
$detail_nota  = "SELECT TO_CHAR(SUM(a.TARIF), '999,999,999,999') AS TARIF, TO_CHAR(SUM(a.SUB_TOTAL), '999,999,999,999') AS BIAYA, a.KETERANGAN, a.HZ, a.JUMLAH_CONT JML_CONT, a.JUMLAH_HARI_KENA, b.UKURAN SIZE_, b.TYPE TYPE_, b.STATUS FROM NOTA_REEXPORT_D_TMP a, master_barang b WHERE a.ID_CONT = b.KODE_BARANG and a.ID_REQ = '$no_req' and a.ID_CONT IS NOT NULL GROUP BY a.KETERANGAN, a.HZ, a.JUMLAH_CONT, a.JUMLAH_HARI_KENA, b.UKURAN, b.TYPE, b.STATUS";
				 //  print_r($detail_nota);die;
	$result       = $db->query($detail_nota);
	$row_detail   = $result->getAll();
	
	
	$total_		  = "SELECT SUM(SUB_TOTAL) TOTAL FROM NOTA_REEXPORT_D_TMP WHERE ID_REQ = '$no_req'";
				   
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
	$query_adm		= "SELECT TO_CHAR(SUB_TOTAL , '999,999,999,999') AS ADM FROM NOTA_REEXPORT_D_TMP WHERE ID_REQ = '$no_req' AND KETERANGAN='ADM'";
	$result_adm		= $db->query($query_adm);
	$row_adm		= $result_adm->fetchRow();
		
	//Menghitung Total dasar pengenaan pajak
        //$total_ = $total + $adm;	//adm sudah masuk detail nota tmp
        $total_ = $total;
		$tgh_minimal = 75000;
		if($total_ < $tgh_minimal)	$total_ = $tgh_minimal;
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
	
	//cek sudah jadi pranota atau belum
	$q_cek="select count(*) as JUM from NOTA_REEXPORT_H where trim(ID_REQUEST)=trim('".$no_req."') and STATUS='S'";
	//print_r($q_cek);die;
	$rs_cek = $db->query($q_cek);
	$row_cek = $rs_cek->fetchRow();
	$jum_cek=$row_cek['JUM'];	
	
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
	$tl->assign("jum_cek",$jum_cek);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
 
 $tl->renderToScreen();

?>