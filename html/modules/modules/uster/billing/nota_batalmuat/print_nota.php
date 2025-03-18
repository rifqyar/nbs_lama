<?php
	
	$tl =  xliteTemplate('print_nota.htm');

	$db = getDB("storage");
	
	//$no_nota	= $_GET["no_nota"];
	$no_req		= $_GET["no_req"];
	$koreksi		= $_GET["koreksi"];
	
	
	//Set Preview-----------------------
	
	//Membuat nomor nota
	//Format: Kode-Kode Cabang-Bulan-Tahun-No Urut
	//Ex: STR050712000001
	 
	
	//--------------------------
	$query_nota	= "SELECT c.NM_PBM AS EMKL,
                          c.NO_NPWP_PBM AS NPWP,
                          c.ALMT_PBM AS ALAMAT,
						  b.NO_REQ_BARU,
						  c.NO_ACCOUNT_PBM,
						  b.STATUS_GATE,
						  TO_CHAR(b.TGL_REQUEST,'DD-MM-RRRR') TGL_REQUEST
                   FROM REQUEST_BATAL_MUAT b INNER JOIN
                            KAPAL_CABANG.MST_PBM c ON b.KD_EMKL = c.KD_PBM AND c.KD_CABANG = '05'
                   WHERE b.NO_REQUEST = '$no_req'";
	
//echo $query_nota;die;			   
	$result		= $db->query($query_nota);
	$row_nota	= $result->fetchRow();
	$req_tgl 	= $row_nota[TGL_REQUEST];
	$kd_pbm 	= $row_nota[NO_ACCOUNT_PBM];
	$display	= 1;
	//cek subsidiary
	$query_perusahaan = "select kapal_prod.all_general_pkg.get_subsidiary_branch_name('USTER','05',TO_DATE('$req_tgl','DD-MM-RRRR')) NAMA_PERUSAHAAN FROM DUAL";
	$rowper			  = $db->query($query_perusahaan)->fetchRow();
	$tl->assign('corporate_name',$rowper[NAMA_PERUSAHAAN]);
	if ($rowper[NAMA_PERUSAHAAN] != 'PT. IPC TPK Cabang Pontianak') {
		$tl->assign('branch_name','CABANG PONTIANAK');
		$display = 0;
	}
	
	$st_gate = $row_nota["STATUS_GATE"];
	//$no_request = $row_nota["NO_REQUEST"];
	
	$query_tgl	= "SELECT TO_CHAR(TGL_REQUEST,'dd/mon/yyyy') TGL_REQUEST FROM request_batal_muat
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
                                 id_nota := 9;
                                 tgl_req := '$tgl_re';
                                 no_request := '$no_req';
                                  err_msg := 'NULL';
                                  jenis := 'batal_muat';
                                create_detail_nota(id_nota,tgl_req,no_request,jenis, err_msg); 
                            END;";
               //echo $sql_xpi;
		$db->query($sql_xpi);
              //  echo $sql_xpi;die;
                
	$detail_nota  = "SELECT a.JML_HARI, TO_CHAR(a.TARIF, '999,999,999,999') AS TARIF, TO_CHAR(a.BIAYA, '999,999,999,999') AS BIAYA, a.KETERANGAN, a.HZ, 
	a.JML_CONT, TO_DATE(a.START_STACK,'dd/mm/yyyy') START_STACK, TO_DATE(a.END_STACK,'dd/mm/yyyy') END_STACK, b.SIZE_, b.TYPE_, b.STATUS FROM temp_detail_nota a
	, iso_code b WHERE a.id_iso = b.id_iso and a.no_request = '$no_req' AND A.KETERANGAN <> 'ADMIN NOTA'";
				   
	$result       = $db->query($detail_nota);
	$row_detail   = $result->getAll();
	

	//jumlah container per request
	$jum		  = "SELECT COUNT(NO_CONTAINER) JUMLAH FROM container_batal_muat WHERE no_request = '$no_req'";
				   
	$result_      = $db->query($jum);
	$jum_         = $result_->fetchRow();
        
    $jumlah_cont  = $jum_['JUMLAH'];
	
	//get_via
	
	/* $q_via = "SELECT NO_CONTAINER, VIA FROM CONTAINER_DELIVERY WHERE NO_REQUEST = '$no_req'";
	$r_via = $db->query($q_via);
	$row_v = $r_via->getAll(); */


	//tarif pass
	$pass		  = "SELECT TO_CHAR(($jumlah_cont * a.TARIF), '999,999,999,999') PASS, ($jumlah_cont * a.TARIF) TARIF
					  FROM master_tarif a, group_tarif b
					 WHERE a.ID_GROUP_TARIF = b.ID_GROUP_TARIF
					       AND TO_DATE ('$tgl_re', 'dd/mm/yyyy') BETWEEN b.START_PERIOD
					                                                    AND b.END_PERIOD
					       AND a.ID_ISO = 'BAMU'";
				   
	$result__     = $db->query($pass);
	$row_pass     = $result__->fetchRow();
    $tarif_pass   = $row_pass['TARIF'];


	$total_		  = "SELECT SUM(BIAYA) TOTAL, SUM(PPN) PPN, (SUM(BIAYA)+SUM(PPN)) TOTAL_TAGIHAN FROM temp_detail_nota WHERE no_request = '$no_req'";
				   
	$result		  = $db->query($total_);
	$total2           = $result->fetchRow();
        
        $total_1 = $total2['TOTAL'];
        $ppn_1 = $total2['PPN'];
        $total_tagihan_1 = $total2['TOTAL_TAGIHAN'];
	
	//$no_request = $row_nota["NO_REQUEST"];
	
	//Discount
	$discount =0;
	$query_discount		= "SELECT TO_CHAR($discount , '999,999,999,999') AS DISCOUNT FROM DUAL";
        $result_discount	= $db->query($query_discount);
	$row_discount		= $result_discount->fetchRow();
	
	//Biaya Administrasi
	$query_adm		= "SELECT TO_CHAR(a.TARIF , '999,999,999,999') AS ADM, a.TARIF FROM MASTER_TARIF a, GROUP_TARIF b WHERE a.ID_GROUP_TARIF = b.ID_GROUP_TARIF AND b.KATEGORI_TARIF = 'ADMIN_NOTA'";
    $result_adm	    = $db->query($query_adm);
	$row_adm		= $result_adm->fetchRow();
	$adm 			= $row_adm['TARIF'];
	
	//Menghitung Total dasar pengenaan pajak
    //$total_ = $total + $adm;
	$query_tot		= "SELECT TO_CHAR('$total_1' , '999,999,999,999') AS TOTAL_ALL FROM DUAL";
	$result_tot		= $db->query($query_tot);
	$row_tot		= $result_tot->fetchRow();
		
	//Menghitung Jumlah PPN
	//$ppn = $total_/10;
	$query_ppn		= "SELECT TO_CHAR('$ppn_1' , '999,999,999,999') AS PPN FROM DUAL";
	$result_ppn		= $db->query($query_ppn);
	$row_ppn		= $result_ppn->fetchRow();
		
	//Menghitung Bea Materai
	$bea_materai = 0;
	$query_materai		= "SELECT TO_CHAR('$bea_materai' , '999,999,999,999') AS MATERAI FROM DUAL";
	$result_materai		= $db->query($query_materai);
	$row_materai		= $result_materai->fetchRow();
	
	
	
	//Menghitung Jumlah dibayar
	$total_bayar        = $total_tagihan_1 ;
	$query_bayar        = "SELECT TO_CHAR('$total_bayar' , '999,999,999,999') AS TOTAL_BAYAR FROM DUAL";
	$result_bayar       = $db->query($query_bayar);
	$row_bayar          = $result_bayar->fetchRow();
	
	if ($display == 1) {
		
		if ($koreksi == N) {
		 	$qnota = "select kapal_prod.counter_nota_xp.invoice_num('USTER', 'BATAL_MUAT','05','050', 'NO',$total_bayar, 'IDR',
		            kapal_prod.counter_nota_xp.group_cust('$kd_pbm')) no_nota from dual";
		} 
		else {
			$cek_oldpbm = "SELECT KD_EMKL FROM NOTA_BATAL_MUAT WHERE NO_REQUEST = '$no_request' AND STATUS = 'BATAL'";
			$rcek_		= $db->query($cek_oldpbm)->fetchRow();

			if ($rcek_[KD_EMKL] == $row_nota[KD_PBM]) {
				$qnota = "select kapal_prod.counter_nota_xp.cm_invoice_num('USTER', 'BATAL_MUAT','05','050', 'NO',$total_bayar, 'IDR',
		            kapal_prod.counter_nota_xp.group_cust('$kd_pbm')) no_nota from dual";	
			} else {
				$qnota = "select kapal_prod.counter_nota_xp.invoice_num('USTER', 'BATAL_MUAT','05','050', 'NO',$total_bayar, 'IDR',
		            kapal_prod.counter_nota_xp.group_cust('$kd_pbm')) no_nota from dual";		
			}		
		}

		$rnota = $db->query($qnota)->fetchRow();
		$tl->assign("rnota",$rnota['NO_NOTA']);	
		$tl->assign("display",$display);
	}
	// echo ($tarif_pass);
	
	//debug($no_req);die;
	$tl->assign("row_discount",$row_discount);	
	$tl->assign("st_gate",$st_gate);	
	$tl->assign("tgl_nota",$tgl_re);
	$tl->assign("row_adm",$row_adm);
	$tl->assign("row_pass",$row_pass);
	$tl->assign("row_tot",$row_tot);
	$tl->assign("row_ppn",$row_ppn);
	$tl->assign("row_materai",$row_materai);
	$tl->assign("row_bayar",$row_bayar);
	$tl->assign("row_trf_brsh",$row_trf_brsh);
	$tl->assign("row_nota",$row_nota);
	$tl->assign("no_nota",$no_nota);
	$tl->assign("no_req",$no_req);
	$tl->assign("koreksi",$koreksi);
	$tl->assign("row_detail",$row_detail);
	$tl->assign("no_req_baru",$row_nota["NO_REQ_BARU"]);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	
	$tl->renderToScreen();

	
	

?>

<script>
	function set_lunas($no_nota)
	{
		var url			= "<?=HOME?><?=APPID?>.ajax/set_lunas";
		
		$.post(url,{NO_NOTA : $no_nota},function(data){
			if(data == "OK")
			{
				
				$("#status_lunas").html("<h1><font color='#0000FF'>LUNAS</font></h1><br />");
				/*
				$("#status_lunas").html("<a href="<?=HOME?><?=APPID?>.print/print_pdf?no_req={$row_nota.NO_REQUEST}&no_nota={$row_nota.NO_NOTA}" target="_blank" > PRINT </a>");
				*/
			}
		});	
	}
	
</script>