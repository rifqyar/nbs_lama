<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('nota_list.htm');
	
	$tgl_awal	= $_POST["tgl_awal"]; 
	$tgl_akhir	= $_POST["tgl_akhir"]; 
	$lunas		= $_POST["lunas"]; 
	$no_nota    = $_POST["no_nota"]; 
	
	//echo $tgl_awal;die;
	$db 	= getDB("storage");
	$db2 	= getDB("ora");
	
	$query_list1 	= "SELECT TO_CHAR(TO_DATE('$tgl_awal','dd/mm/rrrr'),'dd/mon/rrrr') tgl_awal,  TO_CHAR(TO_DATE('$tgl_akhir','dd/mm/rrrr'),'dd/mon/rrrr') tgl_akhir  FROM dual";
	$result_list1	= $db->query($query_list1);
	$row_list1		= $result_list1->fetchRow(); 
	$tgl_awal_ 		= $row_list1['TGL_AWAL'];
	$tgl_akhir_		= $row_list1['TGL_AKHIR'];

	//Masukkan nota yang telah di payment cash ke tabel transfer_simkeu_ (pake underscore) di schema uster (prosedur sp_collect_data ... )
	
	// if (($lunas == 'lunas') AND ($no_nota == NULL)) {
		// $query1 = " DECLARE
				// tgl_awal DATE;
				// tgl_akhir DATE;
				// BEGIN
					// tgl_awal 	:= '$tgl_awal_';
					// tgl_akhir 	:= '$tgl_akhir_';
					// sp_collect_data_ponti(tgl_awal,tgl_akhir);
			   // END;";
	// } else if (($lunas == 'blm_lunas') AND ($no_nota == NULL)) {
		// $query1 = " DECLARE
				// tgl_awal DATE;
				// tgl_akhir DATE;
				// BEGIN
					// tgl_awal 	:= '$tgl_awal_';
					// tgl_akhir 	:= '$tgl_akhir_';
					// sp_collect_data_ponti_bl(tgl_awal,tgl_akhir);
			   // END;";
		
	// } else if (($lunas == NULL) AND ($no_nota <> NULL)) {
		// $query1 = " DECLARE
				// no_nota VARCHAR2(20);
				// BEGIN
					// no_nota 	:= '$no_nota';
					// sp_collect_data_ponti_nota(no_nota);
			   // END;";
	
	// }else if (($lunas == 'lunas') AND ($no_nota <> NULL)) {
		// $query1 = " DECLARE
				// no_nota VARCHAR2(20);
				// BEGIN
					// no_nota 	:= '$no_nota';
					// sp_collect_data_ponti_nota(no_nota);
			   // END;";
	// }
	// $db->query($query1);
	
	
	// echo $query1;
	
	//$query_list_ 	= "SELECT NO_NOTA, NO_REQUEST, TO_CHAR(TOTAL_TAGIHAN_STLH_PPN, '999,999,999,999') TOTAL_TAGIHAN, NM_EMKL, TIPE_LAYANAN NAMA_MODUL, TO_CHAR(TGL_KEGIATAN,'dd/mm/yyyy') TGL_KEGIATAN FROM TRANSFER_SIMKEU_ GROUP BY  NO_NOTA, NO_REQUEST, TOTAL_TAGIHAN_STLH_PPN, NM_EMKL, TIPE_LAYANAN,  TO_CHAR(TGL_KEGIATAN,'dd/mm/yyyy')";
	
	// $query_list_ 	= " SELECT NO_NOTA,
								 // NO_REQUEST
							// FROM TRANSFER_SIMKEU_
						// GROUP BY NO_NOTA,
								 // NO_REQUEST";
	// $result_list_	= $db->query($query_list_);
	// $row_list		= $result_list_->getAll();  
	
	
	
	//Menjalankan prosedur untuk mencek status dan kelengkapan komponen nota simop apakah sudah ada di simkeu atau belum
	//update status field SIMKEU_PROSES_INVOICE pada tabel NOTAL_ALL_H di uster (siap ditransfer .etc) 
	
	
	// foreach($row_list as $row){
	// $no_nota_ 	= $row['NO_NOTA'];
	// $no_req_ 	= $row['NO_REQUEST'];
	
			// $query1 = " DECLARE
						// no_nota VARCHAR2(20);
						// no_req VARCHAR2(20);
						// kd_cbg VARCHAR2(20);
						// BEGIN
							// no_nota 	:= '$no_nota_';
							// no_req	 	:= '$no_req_';
							// kd_cbg		:= '05';
					    // petikemas_cabang.sp_crosscek_simkeu_uster(no_nota, no_req, kd_cbg);
			   // END;";
			// $db2->query($query1);
	// }
	
	// echo "tesfd";die;
	if($no_nota != NULL)
	{
	$query_list2 			= "SELECT a.TRX_NUMBER NO_NOTA,
                             a.NO_REQUEST,
                             TO_CHAR (a.KREDIT, '999,999,999,999') TOTAL_TAGIHAN,
                             b.NM_PBM NM_EMKL,
                             d.NAMA_NOTA NAMA_MODUL,
                             TO_CHAR (a.TRX_DATE, 'dd/mm/yyyy') TGL_KEGIATAN,
                             CASE a.STATUS_AR
                              WHEN 'S' THEN 'SUKSES'
                              WHEN 'F' THEN 'GAGAL'
                              ELSE 'BELUM DITRANSFER'
                            END SIMKEU_PROSES_AR,
                            CASE a.STATUS_RECEIPT
                              WHEN 'S' THEN 'SUKSES'
                              WHEN 'F' THEN 'GAGAL'
                              ELSE 'BELUM DITRANSFER'
                            END SIMKEU_PROSES_RECEIPT
                        FROM ITPK_NOTA_HEADER a, KAPAL_CABANG.MST_PBM b, KAPAL_CABANG.mst_pelanggan c, MASTER_NOTA_ITPK d
                       WHERE  a.JENIS_NOTA = d.KODE_NOTA
                       AND a.CUSTOMER_NUMBER = b.NO_ACCOUNT_PBM
                       AND b.KD_CABANG='05' 
                       AND b.NO_ACCOUNT_PBM = c.kd_pelanggan 
                       AND b.ALMT_PBM IS NOT NULL 
                       AND c.status_pelanggan = 1
                       AND a.status in ('2','4a','4b')                  
                       AND a.TRX_NUMBER LIKE '$no_nota%'";
	}
	else
	{
	$query_list2 	= "SELECT a.TRX_NUMBER NO_NOTA,
                             a.NO_REQUEST,
                             TO_CHAR (a.KREDIT, '999,999,999,999') TOTAL_TAGIHAN,
                             b.NM_PBM NM_EMKL,
                             d.NAMA_NOTA NAMA_MODUL,
                             TO_CHAR (a.TRX_DATE, 'dd/mm/yyyy') TGL_KEGIATAN,
                             CASE a.STATUS_AR
                              WHEN 'S' THEN 'SUKSES'
                              WHEN 'F' THEN 'GAGAL'
                              ELSE 'BELUM DITRANSFER'
                            END SIMKEU_PROSES_AR,
                            CASE a.STATUS_RECEIPT
                              WHEN 'S' THEN 'SUKSES'
                              WHEN 'F' THEN 'GAGAL'
                              ELSE 'BELUM DITRANSFER'
                            END SIMKEU_PROSES_RECEIPT
                        FROM ITPK_NOTA_HEADER a, KAPAL_CABANG.MST_PBM b, KAPAL_CABANG.mst_pelanggan c, MASTER_NOTA_ITPK d
                       WHERE  a.JENIS_NOTA = d.KODE_NOTA
                       AND a.CUSTOMER_NUMBER = b.NO_ACCOUNT_PBM
                       AND b.KD_CABANG='05' 
                       AND b.NO_ACCOUNT_PBM = c.kd_pelanggan 
                       AND b.ALMT_PBM IS NOT NULL 
                       AND c.status_pelanggan = 1
                       AND TO_DATE( TRX_DATE, 'dd/mm/rrrr')  between to_date('$tgl_awal','dd/mm/rrrr') and to_date('$tgl_akhir','dd/mm/rrrr')
                       AND a.status in ('2','4a','4b') ";
	}								
	
	
	$result_list2	= $db->query($query_list2);
	
	// echo $query_list2;die;
	
	$row_list1		= $result_list2->getAll(); 
		
		
	echo $row_list1;	
	/*	
	$valid__	= "	 SELECT COUNT(b.NO_NOTA) VALID
					FROM (SELECT b.NO_NOTA 
                        FROM TRANSFER_SIMKEU_ a, PELUNASAN_SIMKEU b
                       WHERE a.NO_NOTA = b.NO_NOTA
                       AND b.SIMKEU_PROSES = 'ready to transfer'
                    GROUP BY b.NO_NOTA) b";
	$valid_		= $db->query($valid__);
	$valid2   	= $valid_->fetchRow();
	$valid3		= $valid2['VALID'];
	
	$valid___		= " SELECT COUNT(b.NO_NOTA) ANVAL
					FROM (SELECT b.NO_NOTA 
                        FROM TRANSFER_SIMKEU_ a, PELUNASAN_SIMKEU b
                       WHERE a.NO_NOTA = b.NO_NOTA
                       AND b.SIMKEU_PROSES <> 'ready to transfer'
                    GROUP BY b.NO_NOTA) b";
	$valid_		= $db->query($valid___);
	$valid   	= $valid_->fetchRow();
	$anval		= $valid['ANVAL'];
	*/
	
	$tl->assign("row_list",$row_list1);
	$tl->assign("tgl_awal",$tgl_awal);
	$tl->assign("tgl_akhir",$tgl_akhir);
	$tl->assign("anval",$anval);
	$tl->assign("valid",$valid3);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
