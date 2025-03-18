<?php

	$db	= getDB("storage");
	//$db2	= getDB("ora");
	//$db4	= getDB("uster_ict");
	$nipp   = $_SESSION["LOGGED_STORAGE"];
	$no_req = $_GET["no_req"];
	$koreksi = $_GET["koreksi"];
	
	$query_cek_nota 	= "SELECT NO_NOTA,STATUS FROM NOTA_STRIPPING WHERE NO_REQUEST = '$no_req'";
	$result_cek_nota	= $db->query($query_cek_nota);
	$nota				= $result_cek_nota->fetchRow();
	$no_nota_cek		= $nota['NO_NOTA'];
	$nota_status		= $nota['STATUS'];
	
	//cek no nota sudah ada atau belom
	if (  ($no_nota_cek != NULL && $nota_status == 'BATAL') || ($no_nota_cek == NULL && $nota_status == NULL)){
		//create no nota
        $query_cek	= "SELECT NVL(LPAD(MAX(TO_NUMBER(SUBSTR(NO_NOTA,10,15))+1),6,0), '000001') JUM_,
                              TO_CHAR(SYSDATE, 'MM') AS MONTH, 
                              TO_CHAR(SYSDATE, 'YY') AS YEAR 
                        FROM NOTA_STRIPPING 
                       WHERE NOTA_STRIPPING.TGL_NOTA BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE)";
					   
		$result_cek	= $db->query($query_cek);
		$jum_		= $result_cek->fetchRow();
		$jum		= $jum_["JUM_"];
		$month		= $jum_["MONTH"];
		$year		= $jum_["YEAR"];
	
		$no_nota	= "0305".$month.$year.$jum;
        //select master pbm
        $query_master	= "SELECT a.TGL_REQUEST, b.KD_PBM, b.NM_PBM, b.ALMT_PBM, b.NO_NPWP_PBM, COUNT(d.NO_CONTAINER) JUMLAH 
                            FROM request_stripping a, v_mst_pbm b, container_stripping d 
                            WHERE a.KD_CONSIGNEE = b.KD_PBM 
                            AND b.KD_CABANG = '05'
                            AND a.NO_REQUEST = d.NO_REQUEST 
                            AND a.NO_REQUEST = '$no_req' 
                            GROUP BY  a.TGL_REQUEST, b.KD_PBM, b.NM_PBM, b.ALMT_PBM, b.NO_NPWP_PBM";
		//echo $query_master;die;
        $result_cek	= $db->query($query_master);
		$master		= $result_cek->fetchRow();
		$kd_pbm		= $master["KD_PBM"];
		$nm_pbm		= $master["NM_PBM"];
		$almt_pbm	= $master["ALMT_PBM"];
		$npwp   	= $master["NO_NPWP_PBM"];
		$jumlah_cont   	= $master["JUMLAH"];
		$tgl_re		= $master["TGL_REQUEST"];
        
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
        
        $total_		= "SELECT SUM(BIAYA) TOTAL, SUM(PPN) PPN,(SUM(BIAYA)+SUM(PPN)) TOTAL_TAGIHAN FROM temp_detail_nota WHERE no_request = '$no_req'";
        //echo $total_;die;
        $result		= $db->query($total_);
        $total2         = $result->fetchRow();
		$total_ 		= $total2['TOTAL'];
		$ppn	 		= $total2['PPN'];
		$tagihan 		= $total2['TOTAL_TAGIHAN'];
     
        $query_adm		= "SELECT TO_CHAR(a.TARIF , '999,999,999,999') AS ADM, a.TARIF FROM MASTER_TARIF a, GROUP_TARIF b WHERE a.ID_GROUP_TARIF = b.ID_GROUP_TARIF AND b.KATEGORI_TARIF = 'ADMIN_NOTA'";
	    $result_adm	    = $db->query($query_adm);
		$row_adm		= $result_adm->fetchRow();
		$adm 			= $row_adm['TARIF'];
		
		$itpk_flag = "select CASE WHEN to_date(tgl_request,'dd/mm/rrrr') >=kapal_prod.all_general_pkg.get_cutoff_date@dbint_kapal('USTER','05')
							                THEN 'Y' ELSE 'N' END FLAG
							FROM request_stripping
							where no_request = '$no_req'";
		$rtpk_flag = $db->query($itpk_flag)->fetchRow();
		
		IF ($koreksi <> 'Y'){
		
		//$faktur	 	= "SELECT CONCAT(a.NO_FAKTUR ,(LPAD(NVL((MAX(SEQ_FAKTUR)+1),1),6,0))) FAKTUR, NVL((MAX(SEQ_FAKTUR)+1),1) SEQ FROM NOTA_ALL_H, (SELECT NO_FAKTUR FROM FAKTUR WHERE TAHUN =  to_char(sysdate, 'RRRR') AND KETERANGAN = 'NEW') a GROUP BY a.NO_FAKTUR";
		if($rtpk_flag[FLAG] == 'Y'){
			$faktur		= $_GET['trx'];
			$seq		= 0;
		}
		else {
			$faktur 	= "SELECT concat(CONCAT(a.no_faktur, b.kode_faktur), s.faktur) FAKTUR, s.SEQ FROM  (SELECT (LPAD (NVL ( (MAX (SEQ_FAKTUR) + 1), 1), 6, 0))
	                                        FAKTUR,
	                                     NVL ( (MAX (SEQ_FAKTUR) + 1), 1) SEQ
	                                FROM  NOTA_ALL_H
	                                       where trunc(NOTA_ALL_H.TGL_NOTA) BETWEEN TRUNC(SYSDATE,'YEAR') AND  LAST_DAY(ADD_MONTHS(TRUNC(SYSDATE , 'Year'),11))
	                                      AND trunc(NOTA_ALL_H.TGL_NOTA) >= TO_DATE('1/6/2013','DD/MM/RRRR')  ) s,
	                                      (SELECT NO_FAKTUR
	                                        FROM FAKTUR_NEW
	                                       WHERE TAHUN = TO_CHAR (SYSDATE, 'RRRR') AND KETERANGAN = 'NEW') a,
	                                      (SELECT KODE_FAKTUR FROM MASTER_NOTA WHERE ID_NOTA = 2) b";
			$result		= $db->query($faktur);
	        $faktur_     = $result->fetchRow();
			$faktur		= $faktur_['FAKTUR'];
			$seq		= $faktur_['SEQ'];
		}
		$query_insert_nota	= "INSERT INTO NOTA_STRIPPING(NO_NOTA, 
										NO_FAKTUR,
                                        TAGIHAN,
                                        PPN,
                                        TOTAL_TAGIHAN,
										NO_REQUEST, 
										NIPP_USER, 
										LUNAS, 
										CETAK_NOTA, 
										TGL_NOTA,
                                        EMKL,
                                        ALAMAT,
                                        NPWP,
                                        STATUS,
                                        ADM_NOTA,
										PASS,
										KD_EMKL,
										TGL_NOTA_1) 
										VALUES('$no_nota',
										'$faktur',
										'$total_',
										'$ppn',
										'$tagihan',
										'$no_req', 
										'$nipp', 
										'NO', 
										1, 
										SYSDATE,
                                        '$nm_pbm',
                                        '$almt_pbm',
                                        '$npwp',
                                        'NEW',
                                        '$adm',
										'$tarif_pass',
										'$kd_pbm',
										SYSDATE)";	
										
	
	
		$query_nota_all			= "INSERT INTO NOTA_ALL_H
												(
												  NO_NOTA,     
												  NO_REQUEST,
												  NO_FAKTUR,
												  TAGIHAN,
												  PPN,       
												  TOTAL_TAGIHAN,
												  NIPP_USER,      
												  NOTA_LAMA,      
												  LUNAS,    
												  STATUS,
												  EMKL,      
												  ALAMAT,       
												  NPWP,      
												  ADM_NOTA,      
												  PASS,        
												  KD_CONSIGNEE, 
												  TGL_NOTA,    
												  SEQ_FAKTUR,
												  TRANSFER												  
												) VALUES (
												   '$no_nota',
												   	'$no_req', 
													'$faktur',
			                                        '$total_',
			                                        '$ppn',
			                                        '$tagihan',
													'$nipp', 
													'-',
													'NO',
													'NEW', 
			                                        '$nm_pbm',
			                                        '$almt_pbm',
			                                        '$npwp',
			                                        '$adm',
													'$tarif_pass',
													'$kd_pbm',
													SYSDATE,
													'$seq', 'N'
												)";
												
	} ELSE {
	
		//$faktur	 	= "SELECT REPLACE(NO_FAKTUR,'010.','011.') FAKTUR, NO_NOTA FROM NOTA_STRIPPING WHERE NO_REQUEST = '$no_req' AND NO_NOTA =(SELECT MAX(NO_NOTA) FROM NOTA_STRIPPING WHERE NO_REQUEST = '$no_req')";
		$faktur	 	= "SELECT NO_NOTA, NO_FAKTUR, KD_EMKL FROM NOTA_STRIPPING WHERE NO_REQUEST = '$no_req' AND NO_NOTA =(SELECT MAX(NO_NOTA) FROM NOTA_STRIPPING WHERE NO_REQUEST = '$no_req')";
		$result		= $db->query($faktur);
        $faktur_     = $result->fetchRow();
		//$faktur		= $faktur_['FAKTUR'];		
		$nota_lama	= $faktur_['NO_FAKTUR'];
		$emkl_lama	= $faktur_['KD_EMKL'];
		if($rtpk_flag[FLAG] == 'Y'){
				$faktur		= $_GET['trx'];
				$seq		= 0;
		}
		else {
		if ($emkl_lama == $kd_pbm) {
			$faktur_koreksi = "SELECT concat(CONCAT(a.no_faktur, b.kode_faktur), s.faktur) FAKTUR, s.SEQ FROM  (SELECT (LPAD (NVL ( (MAX (SEQ_FAKTUR) + 1), 1), 6, 0))
                                        FAKTUR,
                                     NVL ( (MAX (SEQ_FAKTUR) + 1), 1) SEQ
                                FROM  NOTA_ALL_H
                                       where trunc(NOTA_ALL_H.TGL_NOTA) BETWEEN TRUNC(SYSDATE,'YEAR') AND  LAST_DAY(ADD_MONTHS(TRUNC(SYSDATE , 'Year'),11))
                                      AND trunc(NOTA_ALL_H.TGL_NOTA) >= TO_DATE('1/6/2013','DD/MM/RRRR')  ) s,
                                      (SELECT NO_FAKTUR
                                        FROM FAKTUR_NEW
                                       WHERE TAHUN = TO_CHAR (SYSDATE, 'RRRR') AND KETERANGAN = 'KOREKSI_C') a,
                                      (SELECT KODE_FAKTUR FROM MASTER_NOTA WHERE ID_NOTA = 2) b";
		} 
		else{
			$faktur_koreksi = "SELECT concat(CONCAT(a.no_faktur, b.kode_faktur), s.faktur) FAKTUR, s.SEQ FROM  (SELECT (LPAD (NVL ( (MAX (SEQ_FAKTUR) + 1), 1), 6, 0))
                                        FAKTUR,
                                     NVL ( (MAX (SEQ_FAKTUR) + 1), 1) SEQ
                                FROM  NOTA_ALL_H
                                       where trunc(NOTA_ALL_H.TGL_NOTA) BETWEEN TRUNC(SYSDATE,'YEAR') AND  LAST_DAY(ADD_MONTHS(TRUNC(SYSDATE , 'Year'),11))
                                      AND trunc(NOTA_ALL_H.TGL_NOTA) >= TO_DATE('1/6/2013','DD/MM/RRRR')  ) s,
                                      (SELECT NO_FAKTUR
                                        FROM FAKTUR_NEW
                                       WHERE TAHUN = TO_CHAR (SYSDATE, 'RRRR') AND KETERANGAN = 'KOREKSI_N') a,
                                      (SELECT KODE_FAKTUR FROM MASTER_NOTA WHERE ID_NOTA = 2) b";
		}

			$result_f = $db->query($faktur_koreksi);
			$f_result = $result_f->fetchRow();
			$faktur = $f_result["FAKTUR"];
			$seq		= $f_result["SEQ"];
		}
		$update = "UPDATE NOTA_STRIPPING SET STATUS = 'BATAL' WHERE NO_NOTA = '$nota_lama'";
		$db->query($update);
		// $db4->query($update);
		
		$query_insert_nota	= "INSERT INTO NOTA_STRIPPING(NO_NOTA, 
										NO_FAKTUR,
                                        TAGIHAN,
                                        PPN,
                                        TOTAL_TAGIHAN,
										NO_REQUEST, 
										NIPP_USER, 
										LUNAS, 
										CETAK_NOTA, 
										TGL_NOTA,
                                        EMKL,
                                        ALAMAT,
                                        NPWP,
                                        STATUS,
                                        ADM_NOTA,
										PASS,
										KD_EMKL,
										TGL_NOTA_1,
										NOTA_LAMA) 
										VALUES('$no_nota',
										'$faktur',
                                        '$total_',
                                        '$ppn',
                                        '$tagihan',
										'$no_req', 
										'$nipp', 
										'NO', 
										1, 
										SYSDATE,
                                        '$nm_pbm',
                                        '$almt_pbm',
                                        '$npwp',
                                        'KOREKSI',
                                        '$adm',
										'$tarif_pass',
										'$kd_pbm',
										SYSDATE,
										'$nota_lama')";	
	
	$query_nota_all			= "INSERT INTO NOTA_ALL_H
												(
												  NO_NOTA,     
												  NO_REQUEST,
												  NO_FAKTUR,
												  TAGIHAN,
												  PPN,       
												  TOTAL_TAGIHAN,
												  NIPP_USER,      
												  NOTA_LAMA,      
												  LUNAS,    
												  STATUS,
												  EMKL,      
												  ALAMAT,       
												  NPWP,      
												  ADM_NOTA,      
												  PASS,        
												  KD_CONSIGNEE, 
												  TGL_NOTA,    
												  SEQ_FAKTUR  
												) VALUES (
												   '$no_nota',
												   	'$no_req', 
													'$faktur',
			                                        '$total_',
			                                        '$ppn',
			                                        '$tagihan',
													'$nipp', 
													'$nota_lama',
													'NO',
													'KOREKSI', 
			                                        '$nm_pbm',
			                                        '$almt_pbm',
			                                        '$npwp',
			                                        '$adm',
													'$tarif_pass',
													'$kd_pbm',
													SYSDATE,
													'$seq'
												)";
	}

	//echo $query_insert_nota;die;
	if($db->query($query_insert_nota))
	{
		if ($rtpk_flag[FLAG] == 'N') {
			$db->query($query_nota_all);
		}
		
                $query_detail	= "SELECT ID_ISO,TARIF,BIAYA,KETERANGAN,JML_CONT,HZ,TO_CHAR(START_STACK,'mm/dd/rrrr') START_STACK,TO_CHAR(END_STACK,'mm/dd/rrrr') END_STACK, JML_HARI, COA, PPN,URUT,TEKSTUAL,RELOK,DISKON  FROM temp_detail_nota WHERE no_request = '$no_req' ";
              //  echo $query_detail;die;
				$res		= $db->query($query_detail);
				$row		= $res->getAll();
                $i = 1;
                $j = 1;
                foreach ($row as $item){
                    $id_iso = $item['ID_ISO'];
                    $tarif  = $item['TARIF'];
                    $biaya  = $item['BIAYA'];
                    $ket    = $item['KETERANGAN'];
                    $jml_cont  = $item['JML_CONT'];
                    $hz     = $item['HZ'];
                    $start  = $item['START_STACK'];
                    $end    = $item['END_STACK'];
                    $jml    = $item['JML_HARI'];
                    $coa    = $item['COA'];
					$ppn    = $item['PPN'];
					$urut    = $item['URUT'];
					$tekstual    = $item['TEKSTUAL'];
					$relok    = $item['RELOK'];
					$diskon    = $item['DISKON'];
					
						$query_insert	= "INSERT INTO nota_stripping_d
                                            (
                                             ID_ISO,
                                             TARIF,
                                             BIAYA,
                                             KETERANGAN,
                                             NO_NOTA,
                                             JML_CONT,
                                             HZ,
                                             START_STACK,
                                             END_STACK,
                                             JML_HARI,
											 COA,
											 LINE_NUMBER,
											 PPN,
											 URUT,
											 TEKSTUAL
                                            ) VALUES 
                                            (
                                            '$id_iso',
                                            '$tarif',
                                            '$biaya',
                                            '$ket',
                                            '$no_nota',
                                            '$jml_cont',
                                            '$hz',
                                            TO_DATE('$start','mm/dd/rrrr'), 
                                            TO_DATE('$end','mm/dd/rrrr'), 
                                            '$jml',
											'$coa',
											'$i',
											'$ppn',
											'$urut',
											'$tekstual')";
							$db->query($query_insert);
							// $db4->query($query_insert);
						$query_insert_detail ="INSERT INTO NOTA_ALL_D 
											(
											  ID_ISO,
											  TARIF,
											  BIAYA,
											  KETERANGAN,
											  ID_NOTA,
											  JML_CONT,
											  HZ,
											  START_STACK,
											  END_STACK,
											  JML_HARI,
											  COA,
											  LINE_NUMBER,
											  PPN,
											  URUT,
											  TEKSTUAL
											) VALUES (
											'$id_iso',
                                            '$tarif',
                                            '$biaya',
                                            '$ket',
                                            '$no_nota',
                                            '$jml_cont',
                                            '$hz',
											TO_DATE('$start','mm/dd/rrrr'), 
                                            TO_DATE('$end','mm/dd/rrrr'), 
											'$jml',
											'$coa',
											'$i',
											'$ppn',
											'$urut',
											'$tekstual')";
											
               				if ($rtpk_flag[FLAG] == 'N') {
								$db->query($query_insert_detail);
							}
							// $db4->query($query_insert_detail);
							$i++;
					}
					
                    
            	} 
                	if ($rtpk_flag[FLAG] == 'Y') {
							$sql_xpi = "BEGIN
							  USTER.ITPK_POPULATE_STAGING.INSERT_NOTA_ITPK ( '$no_req', 'UST03', '$faktur', '$no_nota', '$koreksi', '$nipp' ); 
							END; ";
              				 // echo $sql_xpi;die;
							$db->query($sql_xpi);
					} 
                    $update_nota = "UPDATE NOTA_STRIPPING SET CETAK_NOTA = '1' WHERE NO_NOTA = '$no_nota'";
					$update_req = "UPDATE REQUEST_STRIPPING SET NOTA = 'Y' WHERE NO_REQUEST = '$no_req'";
                    $db->query($update_nota);
					// $db4->query($update_nota);
					$db->query($update_req);
                   // $update_nota1 = "UPDATE REQUEST_STRIPPING SET NOTA = 'Y' WHERE NO_REQUEST = '$no_req'";
                    //$db->query($update_nota1);
                    $delete_temp = "DELETE from temp_detail_nota WHERE no_request = '$no_req'";
                    $db->query($delete_temp);
					
					$db->query("UPDATE PLAN_CONTAINER_STRIPPING SET AKTIF = 'T' WHERE NO_REQUEST = REPLACE ('$no_req','S', 'P')");
					
					header('Location:'.HOME.APPID.'.print/print_nota_lunas_simple?no_nota='.$no_nota."&no_req=".$no_req."&first=1");
	} else {
		header('Location:'.HOME.APPID.'.print/print_nota_lunas_simple?no_nota='.$no_nota."&no_req=".$no_req);
	}

?>