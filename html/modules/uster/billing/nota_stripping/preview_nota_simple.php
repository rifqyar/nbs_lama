<?php

	$tl =  xliteTemplate('print_nota.htm');
	$simple = 1;
	$db = getDB("storage");
	//$db2 = getDB("ora");

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
                          b.NOTA,
                          c.NO_ACCOUNT_PBM,
                          TO_CHAR(b.TGL_REQUEST,'DD-MM-RRRR') TGL_REQUEST,
													F_CORPORATE(b.TGL_REQUEST) CORPORATE
                   FROM REQUEST_STRIPPING b INNER JOIN
                            V_MST_PBM c ON b.KD_CONSIGNEE = c.KD_PBM
                   WHERE b.NO_REQUEST = '$no_req'
				   ";

	$result		= $db->query($query_nota);
	$row_nota	= $result->fetchRow();
	$st_nota = $row_nota["NOTA"];
	$st_nota = "T";
	$kd_pbm 	= $row_nota[NO_ACCOUNT_PBM];
	$req_tgl 	= $row_nota[TGL_REQUEST];
	$display 	= 1;
	//cek subsidiary

	$tl->assign('corporate_name',$row_nota['CORPORATE']);

	if( $st_nota == 'Y'){
		echo 'sudah di cetak';
		header('Location: '.HOME.APPID.'.print/print_proforma_strip?no_req='.$no_req);
	} else {
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
/*echo "DECLARE
                            id_nota NUMBER;
                            tgl_req DATE;
                            no_request VARCHAR2(100);
                            jenis VARCHAR2 (100);
                            err_msg VARCHAR2(100);
                            BEGIN
                                 id_nota := 2;
                                 tgl_req := '$tgl_re';
                                 no_request := '$no_req';
                                  err_msg := 'NULL';
                                  jenis := 'stripping';
                                create_detail_nota(id_nota,tgl_req,no_request,jenis, err_msg);
                            END;";die;*/
		$sql_xpi = "DECLARE id_nota NUMBER; tgl_req DATE; no_request VARCHAR2(100); jenis VARCHAR2 (100); err_msg VARCHAR2(100); BEGIN  id_nota := 2; tgl_req := '$tgl_re'; no_request := '$no_req'; err_msg := 'NULL'; jenis := 'stripping'; pack_get_nota_stripping.create_detail_nota(id_nota,tgl_req,no_request,jenis, err_msg); END;";
                //echo $sql_xpi;die;
		$db->query($sql_xpi);
              //  echo $sql_xpi;die;


	$jum		  = "SELECT COUNT(NO_CONTAINER) JUMLAH FROM container_stripping WHERE no_request = '$no_req'";

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
	//$no_request = $row_nota["NO_REQUEST"];

	//$detail_nota  = "SELECT a.JML_HARI, TO_CHAR(a.TARIF, '999,999,999,999') AS TARIF, TO_CHAR(a.BIAYA, '999,999,999,999') AS BIAYA, a.KETERANGAN, a.HZ, a.JML_CONT, TO_DATE(a.START_STACK,'dd/mm/yyyy') START_STACK, TO_DATE(a.END_STACK,'dd/mm/yyyy') END_STACK, b.SIZE_, b.TYPE_, b.STATUS FROM temp_detail_nota a, iso_code b WHERE a.KETERANGAN <> 'ADMIN NOTA' AND a.id_iso = b.id_iso and a.no_request = '$no_req' ORDER BY URUT";
	$detail_nota  = " SELECT a.JML_HARI,
                             TO_CHAR(SUM(a.TARIF),'999,999,999,999') TARIF,
                             TO_CHAR(SUM(a.BIAYA),'999,999,999,999') BIAYA,
                             a.tekstual KETERANGAN,
                             a.HZ,a.JML_CONT,
                              --case a.tekstual when 'PAKET STRIPPING'
                             --THEN (select count(no_container) from container_stripping where no_request = '$no_req')
                             --ELSE
                              --a.JML_CONT
                              --END AS JML_CONT,
                             TO_DATE (a.START_STACK, 'dd/mm/rrrr') START_STACK,
                             TO_DATE (a.END_STACK, 'dd/mm/rrrr') END_STACK,
                             b.SIZE_,
                             b.TYPE_,
                              case a.tekstual when 'PAKET STRIPPING'
                             THEN '-'
                             ELSE
                              b.STATUS
                              END AS STATUS,
                              case a.tekstual when 'PAKET STRIPPING'
                             THEN 10
                             ELSE
                              a.urut
                              END AS urut
                        FROM temp_detail_nota a, iso_code b
                       WHERE     a.KETERANGAN <> 'ADMIN NOTA' AND a.KETERANGAN NOT LIKE '%PENUMPUKAN%'
                             AND a.id_iso = b.id_iso
                             AND a.no_request = '$no_req'
                             AND a.RELOK = '1'
                    GROUP BY a.tekstual, a.jml_hari, a.hz, a.jml_cont, a.start_stack, a.end_stack, b.size_, b.type_,  case a.tekstual when 'PAKET STRIPPING'
                             THEN '-'
                             ELSE
                              b.STATUS
                              END, case a.tekstual when 'PAKET STRIPPING'
                             THEN 10
                             ELSE
                              a.urut
                              END
                   UNION ALL
                   SELECT a.JML_HARI,
                             TO_CHAR(a.TARIF,'999,999,999,999') TARIF,
                             TO_CHAR(a.BIAYA,'999,999,999,999') BIAYA,
                             a.tekstual KETERANGAN,
                             a.HZ,a.JML_CONT,
                              --case a.tekstual when 'PAKET STRIPPING'
                             --THEN (select count(no_container) from container_stripping where no_request = '$no_req')
                             --ELSE
                              --a.JML_CONT
                              --END AS JML_CONT,
                             TO_DATE (a.START_STACK, 'dd/mm/rrrr') START_STACK,
                             TO_DATE (a.END_STACK, 'dd/mm/rrrr') END_STACK,
                             b.SIZE_,
                             b.TYPE_,
                              case a.tekstual when 'PAKET STRIPPING'
                             THEN '-'
                             ELSE
                              b.STATUS
                              END AS STATUS,
                              case a.tekstual when 'PAKET STRIPPING'
                             THEN 10
                             ELSE
                              a.urut
                              END AS urut
                        FROM temp_detail_nota a, iso_code b
                       WHERE     a.KETERANGAN <> 'ADMIN NOTA' AND a.KETERANGAN LIKE '%PENUMPUKAN%'
                             AND a.id_iso = b.id_iso
                             AND a.no_request = '$no_req'
                             AND a.RELOK = '1'
                    ORDER BY urut ASC";
	$result       = $db->query($detail_nota);
	$row_detail   = $result->getAll();


	$total_		  = "SELECT SUM(BIAYA) TOTAL, SUM(PPN) PPN FROM temp_detail_nota WHERE  no_request = '$no_req' and relok = '1'";

	$result		  = $db->query($total_);
	$total2       = $result->fetchRow();

    $total_ 	  = $total2['TOTAL'];
	$ppn 	  	  = $total2['PPN'];

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

	$query_tot		= "SELECT TO_CHAR('$total_' , '999,999,999,999') AS TOTAL_ALL FROM DUAL";
	$result_tot		= $db->query($query_tot);
	$row_tot		= $result_tot->fetchRow();

	//Menghitung Jumlah PPN
	//$ppn = $total_/10;
	$query_ppn		= "SELECT TO_CHAR('$ppn' , '999,999,999,999') AS PPN FROM DUAL";
	$result_ppn		= $db->query($query_ppn);
	$row_ppn		= $result_ppn->fetchRow();

	//Menghitung Bea Materai gagat modif 09 februari 2020 
	$sql_mtr		  = "SELECT BIAYA AS BEA_MATERAI FROM TEMP_DETAIL_NOTA WHERE no_request = '$no_req' AND KETERANGAN='MATERAI'";

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

	//Menghitung Jumlah dibayar
	$total_bayar        = $total_ + $ppn + $bea_materai; //+ $tarif_pass;	/**Fauzan modif 28 AUG 2020 "+ $bea_materai"*/
	$query_bayar        = "SELECT TO_CHAR('$total_bayar' , '999,999,999,999') AS TOTAL_BAYAR FROM DUAL";
	$result_bayar       = $db->query($query_bayar);
	$row_bayar          = $result_bayar->fetchRow();

	$pegawai    = "SELECT * FROM MASTER_PEGAWAI WHERE STATUS = 'AKTIF'";
	$result_	= $db->query($pegawai);
	$nama_peg	= $result_->fetchRow();

	/*if ($display == 1) {

		if ($koreksi == N) {
		 	$qnota = "select kapal_prod.counter_nota_xp.invoice_num@dbint_kapal('USTER', 'STRIPPING','05','050', 'NO',$total_bayar, 'IDR',
		            kapal_prod.counter_nota_xp.group_cust@dbint_kapal('$kd_pbm')) no_nota from dual";
            //echo $qnota;
		}
		else {
			$cek_oldpbm = "SELECT KD_EMKL FROM NOTA_STRIPPING WHERE NO_REQUEST = '$no_request' AND STATUS = 'BATAL'";
			$rcek_		= $db->query($cek_oldpbm)->fetchRow();

			if ($rcek_[KD_EMKL] == $row_nota[KD_PBM]) {
				$qnota = "select kapal_prod.counter_nota_xp.cm_invoice_num@dbint_kapal('USTER', 'STRIPPING','05','050', 'NO',$total_bayar, 'IDR',
		            kapal_prod.counter_nota_xp.group_cust@dbint_kapal('$kd_pbm')) no_nota from dual";
			} else {
				$qnota = "select kapal_prod.counter_nota_xp.invoice_num@dbint_kapal('USTER', 'STRIPPING','05','050', 'NO',$total_bayar, 'IDR',
		            kapal_prod.counter_nota_xp.group_cust@dbint_kapal('$kd_pbm')) no_nota from dual";
			}
		}

	$rnota = $db->query($qnota)->fetchRow();
	$tl->assign("rnota",$rnota['NO_NOTA']);
	$tl->assign("display",$display);
	}*/

	//debug($no_req);die;
	$tl->assign("url_ins","insert_proforma_strip");
	$tl->assign("kegiatan","STRIPPING");
	$tl->assign("row_discount",$row_discount);
	$tl->assign("nama_peg",$nama_peg);
	$tl->assign("row_pass",$row_pass);
	$tl->assign("tgl_nota",$tgl_re);
	$tl->assign("row_adm",$row_adm);
	$tl->assign("row_tot",$row_tot);
	$tl->assign("row_ppn",$row_ppn);
	$tl->assign("row_materai",$row_materai);
	$tl->assign("bea_materai",$bea_materai);
	$tl->assign("row_bayar",$row_bayar);
	$tl->assign("row_trf_brsh",$row_trf_brsh);
	$tl->assign("row_nota",$row_nota);
	$tl->assign("no_nota",$no_nota);
	$tl->assign("no_req",$no_req);
	$tl->assign("koreksi",$koreksi);
	$tl->assign("row_detail",$row_detail);
	$tl->assign("simple",$simple);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);


	$tl->renderToScreen();

	}


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
