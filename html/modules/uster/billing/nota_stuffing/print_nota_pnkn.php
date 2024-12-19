<?php

	$tl =  xliteTemplate('print_nota_pnkn.htm');

	$db = getDB("storage");
	$simple = 1;
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
                          c.NO_ACCOUNT_PBM,
                          TO_CHAR(b.TGL_REQUEST,'DD-MM-RRRR') TGL_REQUEST,
													F_CORPORATE_DUMMY(b.TGL_REQUEST) CORPORATE
                   FROM REQUEST_STUFFING b INNER JOIN
                            V_MST_PBM c ON b.ID_PENUMPUKAN = c.KD_PBM
                   WHERE b.NO_REQUEST = '$no_req'
				   ";

	$result		= $db->query($query_nota);
	$row_nota	= $result->fetchRow();

	$req_tgl 	= $row_nota[TGL_REQUEST];
	$kd_pbm 	= $row_nota[NO_ACCOUNT_PBM];
	$display 	= 1;
	//cek subsidiary

	$tl->assign('corporate_name','PT. Multi Terminal Indonesia <br>Cabang Pelabuhan Pontianak');
  	//$row_nota['CORPORATE']);


	if($row_nota["EMKL"] == NULL){
		$query_nota	= "SELECT c.NM_PBM AS EMKL,
                          c.NO_NPWP_PBM AS NPWP,
                          c.ALMT_PBM AS ALAMAT
                   FROM REQUEST_STUFFING b INNER JOIN
                            V_MST_PBM c ON b.KD_CONSIGNEE = c.KD_PBM
                   WHERE b.NO_REQUEST = '$no_req'
				   ";

		$result		= $db->query($query_nota);
		$row_nota	= $result->fetchRow();
	}

	//$no_request = $row_nota["NO_REQUEST"];

	$query_tgl	= "SELECT TO_CHAR(TGL_REQUEST,'dd/mon/yyyy') TGL_REQUEST FROM request_stuffing
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
/* echo "DECLARE
                            id_nota NUMBER;
                            tgl_req DATE;
                            no_request VARCHAR2(100);
                            jenis VARCHAR2 (100);
                            err_msg VARCHAR2(100);
                            BEGIN
                                 id_nota := 3;
                                 tgl_req := '$tgl_re';
                                 no_request := '$no_req';
                                  err_msg := 'NULL';
                                  jenis := 'stuffing';
                                create_detail_nota(id_nota,tgl_req,no_request,jenis, err_msg);
                            END;";die; */
                //echo $sql_xpi;die;
		$sql_xpi = "DECLARE id_nota NUMBER; tgl_req DATE; no_request VARCHAR2(100); jenis VARCHAR2 (100); err_msg VARCHAR2(100); BEGIN id_nota := 3; tgl_req := '$tgl_re'; no_request := '$no_req'; err_msg := 'NULL'; jenis := 'pnkn_stuffing'; pack_get_nota_stuffing_new.create_detail_nota(id_nota,tgl_req,no_request,jenis, err_msg); END;";

		$db->query($sql_xpi);
              //  echo $sql_xpi;die;

	$detail_nota  = "SELECT a.JML_HARI,
                             TO_CHAR(a.TARIF,'999,999,999,999') TARIF,
                             TO_CHAR(a.BIAYA,'999,999,999,999') BIAYA,
                             a.KETERANGAN,
                             a.HZ,a.JML_CONT,
                             TO_DATE (a.START_STACK, 'dd/mm/rrrr') START_STACK,
                             TO_DATE (a.END_STACK, 'dd/mm/rrrr') END_STACK,
                             b.SIZE_,
                             b.TYPE_,b.STATUS,urut
                        FROM temp_detail_nota_i a, iso_code b
                       WHERE a.id_iso = b.id_iso
                             AND a.no_request = '$no_req'
							 AND  a.KETERANGAN NOT IN ('ADMIN NOTA', 'MATERAI') /**modify fauzan 30 AUG 2020*/
                    ORDER BY urut ASC";

	$result       = $db->query($detail_nota);
	$row_detail   = $result->getAll();

	//jumlah container per request
	$jum		  = "SELECT COUNT(NO_CONTAINER) JUMLAH FROM container_stuffing  WHERE no_request = '$no_req'";

	$result_      = $db->query($jum);
	$jum_         = $result_->fetchRow();

    $jumlah_cont  = $jum_['JUMLAH'];

	//tarif pass
	$pass		  = "SELECT TO_CHAR(($jumlah_cont * a.TARIF), '999,999,999,999') PASS, ($jumlah_cont * a.TARIF) TARIF
					  FROM master_tarif a, group_tarif b
					 WHERE a.ID_GROUP_TARIF = b.ID_GROUP_TARIF
					       AND TO_DATE ('$tgl_re', 'dd/mm/yyyy') BETWEEN b.START_PERIOD
					                                                    AND b.END_PERIOD
					       AND a.ID_ISO = 'PASS'";

	$result__     = $db->query($pass);
	$row_pass     = $result__->fetchRow();
    $tarif_pass   = $row_pass['TARIF'];


	$total_		  = "SELECT SUM(BIAYA) TOTAL, SUM(PPN) PPN, SUM(BIAYA)+SUM(PPN) TOTAL_TAGIHAN
						FROM temp_detail_nota_i
						WHERE no_request = '$no_req' AND ID_ISO NOT IN ('MATERAI')"; /**fauzan modif 27 AUG 2020*/;

	$result		  = $db->query($total_);
	$total2       = $result->fetchRow();

    $total_  = $total2['TOTAL'];
	$ppn	 = $total2['PPN'];
	$total_bayar = $total2['TOTAL_TAGIHAN'];

	//$no_request = $row_nota["NO_REQUEST"];

	//Discount
	$discount =0;
	$query_discount		= "SELECT TO_CHAR($discount , '999,999,999,999') AS DISCOUNT FROM DUAL";
    $result_discount	= $db->query($query_discount);
	$row_discount		= $result_discount->fetchRow();
	//Biaya Administrasi
	//Biaya Administrasi

	$query_adm		= "SELECT TO_CHAR(a.TARIF , '999,999,999,999') AS ADM, a.TARIF
							FROM MASTER_TARIF a, GROUP_TARIF b
							WHERE a.ID_GROUP_TARIF = b.ID_GROUP_TARIF AND b.KATEGORI_TARIF = 'ADMIN_NOTA'";
    $result_adm	    = $db->query($query_adm);
	$row_adm		= $result_adm->fetchRow();
	$adm 			= $row_adm['TARIF'];

	//Menghitung Total dasar pengenaan pajak
  //  $total_ = $total + $adm;
	$query_tot		= "SELECT TO_CHAR('$total_' , '999,999,999,999') AS TOTAL_ALL FROM DUAL";
	$result_tot		= $db->query($query_tot);
	$row_tot		= $result_tot->fetchRow();

	//Menghitung Jumlah PPN
	//$ppn = $total_/10;
	$query_ppn		= "SELECT TO_CHAR('$ppn' , '999,999,999,999') AS PPN FROM DUAL";
	$result_ppn		= $db->query($query_ppn);
	$row_ppn		= $result_ppn->fetchRow();
	
	//Menghitung Bea Materai gagat modif 09 februari 2020 
	$sql_mtr		  = "SELECT BIAYA AS BEA_MATERAI FROM TEMP_DETAIL_NOTA_I WHERE no_request = '$no_req' AND KETERANGAN='MATERAI'";

	$rslt_mtr      = $db->query($sql_mtr);
	$row_mtr         = $rslt_mtr->fetchRow();
	if($row_mtr['BEA_MATERAI']>0){
		$bea_materai = $row_mtr['BEA_MATERAI'];
	}else{
		$bea_materai = 0;
	}
	/**end modify gagat 09 feb 2020*/
	$query_materai		= "SELECT TO_CHAR('$bea_materai' , '999,999,999,999') AS MATERAI FROM DUAL";
	$result_materai		= $db->query($query_materai);
	$row_materai		= $result_materai->fetchRow();
	
	//Menghitung pass truck
	//$bea_materai = 0;
	$query_pass 		= "SELECT TO_CHAR('$tarif_pass' , '999,999,999,999') AS PASS FROM DUAL";
	$result_pass		= $db->query($query_pass);
	$row_pass			= $result_pass->fetchRow();

	//Menghitung Jumlah dibayar
	$total_bayar        = $total_bayar + $bea_materai; /**modify Fauzan 30 Agustus 2020 '+ $bea_materai'*/
	$query_bayar        = "SELECT TO_CHAR('$total_bayar' , '999,999,999,999') AS TOTAL_BAYAR FROM DUAL";
	$result_bayar       = $db->query($query_bayar);
	$row_bayar          = $result_bayar->fetchRow();

	$pegawai    = "SELECT * FROM MASTER_PEGAWAI WHERE STATUS = 'AKTIF'";
	$result_	= $db->query($pegawai);
	$nama_peg	= $result_->fetchRow();

	/*if ($display == 1) {
	    if ($koreksi != Y) {
	      $qnota = "select kapal_prod.counter_nota_xp.invoice_num('USTER', 'STUF_PNK','05','050', 'NO',$total_bayar, 'IDR',
	                kapal_prod.counter_nota_xp.group_cust('$kd_pbm')) no_nota from dual";
	    }
	    else {
		      $cek_oldpbm = "SELECT KD_EMKL FROM NOTA_STUFFING WHERE NO_REQUEST = '$no_request' AND STATUS = 'BATAL'";
		      $rcek_    = $db->query($cek_oldpbm)->fetchRow();

		      if ($rcek_[KD_EMKL] == $row_nota[KD_PBM]) {
		        $qnota = "select kapal_prod.counter_nota_xp.cm_invoice_num('USTER', 'STUF_PNK','05','050', 'NO',$total_bayar, 'IDR',
		                kapal_prod.counter_nota_xp.group_cust('$kd_pbm')) no_nota from dual";
		      } else {
		        $qnota = "select kapal_prod.counter_nota_xp.invoice_num('USTER', 'STUF_PNK','05','050', 'NO',$total_bayar, 'IDR',
		                kapal_prod.counter_nota_xp.group_cust('$kd_pbm')) no_nota from dual";
		      }
	    }

		  $rnota = $db->query($qnota)->fetchRow();
		  $tl->assign("rnota",$rnota['NO_NOTA']);
		  $tl->assign("display",$display);
	}*/
	//debug($no_req);die;
	$tl->assign("row_discount",$row_discount);
	$tl->assign("nama_peg",$nama_peg);
	$tl->assign("tgl_nota",$tgl_re);
	$tl->assign("row_adm",$row_adm);
	$tl->assign("row_tot",$row_tot);
	$tl->assign("row_ppn",$row_ppn);
	$tl->assign("row_pass",$row_pass);
	$tl->assign("row_materai",$row_materai); /*gagat modif 10 februari 2020*/
	$tl->assign("bea_materai",$bea_materai);
	$tl->assign("row_bayar",$row_bayar);
	$tl->assign("row_trf_brsh",$row_trf_brsh);
	$tl->assign("row_nota",$row_nota);
	$tl->assign("no_nota",$no_nota);
	$tl->assign("no_req",$no_req);
	$tl->assign("row_detail",$row_detail);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	$tl->assign("koreksi",$koreksi);
	$tl->assign("simple",$simple);
	$tl->assign("pnkn","yes");


	$tl->renderToScreen();




?>

<script>
	function set_lunas($no_nota)
	{
		var url			= "<?=HOME?><?=APPID?>.ajax/set_lunas";

		$.post(url,{NO_NOTA : $no_nota},function(data){
			if(data == "OK")
			{

				$("#status_lunas").html("<h1><font color='#0000FF'>LUNAS7</font></h1><br />");
				/*
				$("#status_lunas").html("<a href="<?=HOME?><?=APPID?>.print/print_pdf?no_req={$row_nota.NO_REQUEST}&no_nota={$row_nota.NO_NOTA}" target="_blank" > PRINT </a>");
				*/
			}
		});
	}

</script>
