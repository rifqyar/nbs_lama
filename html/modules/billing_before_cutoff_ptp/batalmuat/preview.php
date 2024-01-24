<?php
	
	$tl =  xliteTemplate('print_nota.htm');

	$db = getDB();
	
	//$no_nota	= $_GET["no_nota"];
	$no_req	= $_GET["no_req"];
	
	
	//Set Preview-----------------------
	
	//Membuat nomor nota
	//Format: Kode-Kode Cabang-Bulan-Tahun-No Urut
	//Ex: STR050712000001
	 
	
	//--------------------------
	$query_nota	= "SELECT A.NAMA AS EMKL, A.ALAMAT, A.NPWP 
	               FROM TB_BATALMUAT_H B, MASTER_PBM A
					WHERE A.KODE_PBM = B.KODE_PBM
					AND B.ID_BATALMUAT = '$no_req'
				   ";
				   
	$result		= $db->query($query_nota);
	$row_nota	= $result->fetchRow();
	
	//$no_request = $row_nota["NO_REQUEST"];
	
	$query_tgl	= "SELECT TO_CHAR(TGL_BERANGKAT,'dd/mon/yyyy') TGL_BERANGKAT, TO_CHAR(TGL_KELUAR,'dd/mon/yyyy') TGL_KELUAR FROM TB_BATALMUAT_H WHERE ID_BATALMUAT = '$no_req'";
	//echo $query_tgl;die;
	
	$result	= $db->query($query_tgl);
	$tgl_req = $result->fetchRow();
    $tglberangkat = $tgl_req['TGL_BERANGKAT'];
	$tglkeluar = $tgl_req['TGL_KELUAR'];
       
		$sql_xpi = "DECLARE
                            no_req VARCHAR2(50);
                            tgl_berangkat DATE;
                            tgl_keluar DATE;
                            BEGIN 
                                 no_req := '$no_req';
                                 tgl_berangkat := '$tglberangkat';
                                 tgl_keluar := '$tglkeluar';
                                 NOTA_BMD(no_req,tgl_berangkat,tgl_keluar); 
                            END;";
                //echo $sql_xpi;die;
		$db->query($sql_xpi);
              //  echo $sql_xpi;die;
                
	$detail_nota  = "SELECT TO_CHAR(A.TARIF, '999,999,999,999') AS TARIF, TO_CHAR(A.TOTAL, '999,999,999,999') AS BIAYA, A.KETERANGAN, A.HZ, A.JUMLAH_CONT AS JML_CONT, TO_DATE(A.TGL_MULAI,'dd/mm/yyyy') START_STACK, TO_DATE(A.TGL_SELESAI,'dd/mm/yyyy') END_STACK, A.JUMLAH_HARI, B.UKURAN SIZE_, B.TYPE TYPE_, B.STATUS FROM TB_TEMP_NOTA_BM_D A, MASTER_BARANG B WHERE A.ID_CONT = B.KODE_BARANG and A.ID_BATALMUAT = '$no_req'";
				   
	$result       = $db->query($detail_nota);
	$row_detail   = $result->getAll();	
	
	$total_		  = "SELECT SUM(TOTAL) TOTAL FROM TB_TEMP_NOTA_BM_D WHERE ID_BATALMUAT = '$no_req'";
				   
	$result		  = $db->query($total_);
	$total2       = $result->fetchRow();
        
    $total = $total2['TOTAL'];
	
	//Discount
	$discount =0;
	$query_discount		= "SELECT TO_CHAR($discount , '999,999,999,999') AS DISCOUNT FROM DUAL";
    $result_discount	= $db->query($query_discount);
	$row_discount		= $result_discount->fetchRow();
	
	//Biaya Administrasi
	$jml_cont	= "SELECT COUNT(NO_CONTAINER) JML FROM TB_BATALMUAT_D WHERE ID_BATALMUAT = '$no_req'";
    $result		= $db->query($jml_cont);
    $jml     	= $result->fetchRow();
	$jml_cont   = $jml['JML'];

    $adm = 10000+($jml_cont*10000);
	$query_adm		= "SELECT TO_CHAR($adm , '999,999,999,999') AS ADM FROM DUAL";
	$result_adm		= $db->query($query_adm);
	$row_adm		= $result_adm->fetchRow();
		
	//Menghitung Total dasar pengenaan pajak
    $total_ = $total + $adm;
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
	$total_bayar        = $adm + $total_ + $ppn;
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
