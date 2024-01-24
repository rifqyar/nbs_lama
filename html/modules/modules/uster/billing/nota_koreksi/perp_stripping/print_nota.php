<?php
	
	$tl =  xliteTemplate('print_nota.htm');

	$db = getDB("storage");
	
	//$no_nota	= $_GET["no_nota"];
	$no_req		= $_GET["no_req"];
	
	
	//Set Preview-----------------------
	
	//Membuat nomor nota
	//Format: Kode-Kode Cabang-Bulan-Tahun-No Urut
	//Ex: STR050712000001
	 
	
	//--------------------------
	$query_nota	= "SELECT c.NM_PBM AS EMKL,
                          c.NO_NPWP_PBM AS NPWP,
                          c.ALMT_PBM AS ALAMAT
                   FROM request_stripping b INNER JOIN
                            V_MST_PBM c ON b.KD_PENUMPUKAN_OLEH = c.KD_PBM
                   WHERE b.NO_REQUEST = '$no_req'";
	
//echo $query_nota;die;			   
	$result		= $db->query($query_nota);
	$row_nota	= $result->fetchRow();
	
	//$no_request = $row_nota["NO_REQUEST"];
	
	$query_tgl	= "SELECT TO_CHAR(TGL_REQUEST,'dd/mon/yyyy') TGL_REQUEST FROM request_stripping
                             WHERE NO_REQUEST = '$no_req'
                            ";
	//echo $query_tgl;die;			   
	$result		= $db->query($query_tgl);
	$tgl_req	= $result->fetchRow();
        $tgl_re         = $tgl_req['TGL_REQUEST'];
       // echo $tgl_re;die;
        
		$parameter= array(
					'id_nota'=>2,
                    'tgl_req'=>$tgl_re,
					'no_request:20'=>$no_req,
					'err_msg:100'=>'NULL'
					);
		//debug($parameter);

		$sql_xpi = "DECLARE
                            id_nota NUMBER;
                            tgl_req DATE;
                            no_request VARCHAR2(100);
                            jenis VARCHAR2 (100);
                            err_msg VARCHAR2(100);
                            BEGIN 
                                  id_nota := 6;
                                  tgl_req := '$tgl_re';
                                  no_request := '$no_req';
                                  err_msg := 'NULL';
                                  jenis := 'stripping';
                                create_detail_nota(id_nota,tgl_req,no_request,jenis, err_msg); 
                            END;";
              // echo $sql_xpi;die;
		$db->query($sql_xpi);
		
		
				$sql_xpi1 = "DECLARE
                            tgl_req DATE;
                            no_request VARCHAR2(100);
                            jenis VARCHAR2 (100);
                            begin 
								no_request := '".$no_req."';
								tgl_req := '".$tgl_re."';
								jenis := 'ext_stripping';
								perp_pnkn_stufstrip(no_request,tgl_req,jenis);
			                end;";
              // echo $sql_xpi;die;
		$db->query($sql_xpi1);

              //  echo $sql_xpi;die;
                
	$detail_nota  = "SELECT a.JML_HARI, TO_CHAR(a.TARIF, '999,999,999,999') AS TARIF, TO_CHAR(a.BIAYA, '999,999,999,999') AS BIAYA, a.KETERANGAN, a.HZ, a.JML_CONT, TO_DATE(a.START_STACK,'dd/mm/yyyy') START_STACK, TO_DATE(a.END_STACK,'dd/mm/yyyy') END_STACK, b.SIZE_, b.TYPE_, b.STATUS FROM temp_detail_nota a, iso_code b WHERE a.id_iso = b.id_iso and a.no_request = '$no_req'";
				   
	$result       = $db->query($detail_nota);
	$row_detail   = $result->getAll();
	
	
	$total_		  = "SELECT SUM(BIAYA) TOTAL FROM temp_detail_nota WHERE no_request = '$no_req'";
				   
	$result		  = $db->query($total_);
	$total2           = $result->fetchRow();
        
        $total = $total2['TOTAL'];
	
	//$no_request = $row_nota["NO_REQUEST"];
	
	//Discount
	$discount =0;
	$query_discount		= "SELECT TO_CHAR($discount , '999,999,999,999') AS DISCOUNT FROM DUAL";
        $result_discount	= $db->query($query_discount);
	$row_discount		= $result_discount->fetchRow();
	//Biaya Administrasi
	//Biaya Administrasi
	
	$query_adm		= "SELECT TO_CHAR(a.TARIF , '999,999,999,999') AS ADM, a.TARIF FROM MASTER_TARIF a, GROUP_TARIF b WHERE a.ID_GROUP_TARIF = b.ID_GROUP_TARIF AND b.KATEGORI_TARIF = 'ADMIN_NOTA'";
    $result_adm	    = $db->query($query_adm);
	$row_adm		= $result_adm->fetchRow();
	$adm 			= $row_adm['TARIF'];
	
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
	$total_bayar        = $total_ + $ppn;
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