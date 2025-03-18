<?php

	$db	= getDB("storage");
	//$db4	= getDB("uster_ict");
	$nipp   = $_SESSION["LOGGED_STORAGE"];
	$no_req = $_GET["no_req"];
	$koreksi = $_GET["koreksi"];
	
	$query_cek_nota 	= "SELECT NO_NOTA,STATUS FROM NOTA_RELOKASI_MTY WHERE NO_REQUEST = '$no_req'";
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
                        FROM NOTA_RELOKASI_MTY 
                       WHERE NOTA_RELOKASI_MTY.TGL_NOTA BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE)";
					   
		$result_cek	= $db->query($query_cek);
		$jum_		= $result_cek->fetchRow();
		$jum		= $jum_["JUM_"];
		$month		= $jum_["MONTH"];
		$year		= $jum_["YEAR"];
	
		$no_nota_relok	= "1005".$month.$year.$jum;


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
        
        $total_		= "SELECT SUM(BIAYA) TOTAL, SUM(PPN) PPN,(SUM(BIAYA)+SUM(PPN)) TOTAL_TAGIHAN FROM temp_detail_nota_i WHERE no_request = '$no_req'";
        //echo $total_;die;
        $result		= $db->query($total_);
        $total2         = $result->fetchRow();
		$total_ 		= $total2['TOTAL'];
		$ppn	 		= $total2['PPN'];
		$tagihan 		= $total2['TOTAL_TAGIHAN'];
		
		$total_r		= "SELECT SUM(BIAYA) TOTAL, SUM(PPN) PPN, SUM(DISKON) DISKON, ((SUM(BIAYA)+SUM(PPN))-SUM(DISKON)) TOTAL_TAGIHAN FROM temp_detail_nota_i WHERE no_request = '$no_req'";
        //echo $total_;die;
        $result_r		= $db->query($total_r);
        $total2_r        = $result_r->fetchRow();
		$total_relok 		= $total2_r['TOTAL'];
		$ppn_relok		= $total2_r['PPN'];
		$tagihan_relok		= $total2_r['TOTAL_TAGIHAN'];
		$total_diskon		= $total2_r['DISKON'];
     
        $query_adm		= "SELECT TO_CHAR(TARIF , '999,999,999,999') AS ADM, TARIF FROM temp_detail_nota_i WHERE KETERANGAN = 'ADMIN NOTA' AND NO_REQUEST = '$no_req'";
	    $result_adm	    = $db->query($query_adm);
		$row_adm		= $result_adm->fetchRow();
		$adm 			= $row_adm['TARIF'];
		
		$itpk_flag = "select CASE WHEN to_date(tgl_request,'dd/mm/rrrr') >=kapal_prod.all_general_pkg.get_cutoff_date@dbint_kapal('USTER','05')
							                THEN 'Y' ELSE 'N' END FLAG
							FROM request_stripping
							where no_request = '$no_req'";
		$rtpk_flag = $db->query($itpk_flag)->fetchRow();
       
        //$adm   = 10000;
        //$total_ = $total+$adm;
        //$ppn   = 0.1 * ($total+$adm);
        //+ $tarif_pass;
		
		
		$q_rl	 	= "SELECT NO_NOTA, NO_FAKTUR, KD_EMKL FROM NOTA_RELOKASI_MTY WHERE NO_REQUEST = '$no_req' AND NO_NOTA =(SELECT MAX(NO_NOTA) FROM NOTA_RELOKASI_MTY WHERE NO_REQUEST = '$no_req')";
		$resrl = $db->query($q_rl);
		$rowrl = $resrl->fetchRow();
		$nota_rellama = $rowrl["NO_FAKTUR"];
		$emkl_lama 	  = $rowrl["KD_EMKL"];
		//$faktur	 	= "SELECT CONCAT(a.NO_FAKTUR ,(LPAD(NVL((MAX(SEQ_FAKTUR)+1),1),6,0))) FAKTUR, NVL((MAX(SEQ_FAKTUR)+1),1) SEQ FROM NOTA_ALL_H, (SELECT NO_FAKTUR FROM FAKTUR WHERE TAHUN =  to_char(sysdate, 'RRRR') AND KETERANGAN = 'NEW') a GROUP BY a.NO_FAKTUR";
		
		if ($koreksi <> 'Y'){
			$faktur = "SELECT concat(CONCAT(a.no_faktur, b.kode_faktur), s.faktur) FAKTUR, s.SEQ FROM  (SELECT (LPAD (NVL ( (MAX (SEQ_FAKTUR) + 1), 1), 6, 0))
                                        FAKTUR,
                                     NVL ( (MAX (SEQ_FAKTUR) + 1), 1) SEQ
                                FROM  NOTA_ALL_H
                                      where trunc(NOTA_ALL_H.TGL_NOTA) BETWEEN TRUNC(SYSDATE,'YEAR') AND  LAST_DAY(ADD_MONTHS(TRUNC(SYSDATE , 'Year'),11))
                                      AND trunc(NOTA_ALL_H.TGL_NOTA) >= TO_DATE('1/6/2013','DD/MM/RRRR')  ) s,
                                      (SELECT NO_FAKTUR
                                        FROM FAKTUR_NEW
                                       WHERE TAHUN = TO_CHAR (SYSDATE, 'RRRR') AND KETERANGAN = 'NEW') a,
                                      (SELECT KODE_FAKTUR FROM MASTER_NOTA WHERE ID_NOTA = 10) b";		
						
		}
		else{
			if ($emkl_lama == $kd_pbm) {
				$faktur = "SELECT concat(CONCAT(a.no_faktur, b.kode_faktur), s.faktur) FAKTUR, s.SEQ FROM  (SELECT (LPAD (NVL ( (MAX (SEQ_FAKTUR) + 1), 1), 6, 0))
                                        FAKTUR,
                                     NVL ( (MAX (SEQ_FAKTUR) + 1), 1) SEQ
                                FROM  NOTA_ALL_H
                                       where trunc(NOTA_ALL_H.TGL_NOTA) BETWEEN TRUNC(SYSDATE,'YEAR') AND  LAST_DAY(ADD_MONTHS(TRUNC(SYSDATE , 'Year'),11))
                                      AND trunc(NOTA_ALL_H.TGL_NOTA) >= TO_DATE('1/6/2013','DD/MM/RRRR')  ) s,
                                      (SELECT NO_FAKTUR
                                        FROM FAKTUR_NEW
                                       WHERE TAHUN = TO_CHAR (SYSDATE, 'RRRR') AND KETERANGAN = 'KOREKSI_C') a,
                                      (SELECT KODE_FAKTUR FROM MASTER_NOTA WHERE ID_NOTA = 10) b";
				} 
				else{
					$faktur = "SELECT concat(CONCAT(a.no_faktur, b.kode_faktur), s.faktur) FAKTUR, s.SEQ FROM  (SELECT (LPAD (NVL ( (MAX (SEQ_FAKTUR) + 1), 1), 6, 0))
                                        FAKTUR,
                                     NVL ( (MAX (SEQ_FAKTUR) + 1), 1) SEQ
                                FROM  NOTA_ALL_H
                                       where trunc(NOTA_ALL_H.TGL_NOTA) BETWEEN TRUNC(SYSDATE,'YEAR') AND  LAST_DAY(ADD_MONTHS(TRUNC(SYSDATE , 'Year'),11))
                                      AND trunc(NOTA_ALL_H.TGL_NOTA) >= TO_DATE('1/6/2013','DD/MM/RRRR')  ) s,
                                      (SELECT NO_FAKTUR
                                        FROM FAKTUR_NEW
                                       WHERE TAHUN = TO_CHAR (SYSDATE, 'RRRR') AND KETERANGAN = 'KOREKSI_N') a,
                                      (SELECT KODE_FAKTUR FROM MASTER_NOTA WHERE ID_NOTA = 10) b";
				}
		}
			if($rtpk_flag[FLAG] == 'Y'){
				$faktur_relok		= $_GET['trx'];
				$seq_relok		= 0;
			}
			else {
				$result		= $db->query($faktur);
				$faktur_     = $result->fetchRow();
				$faktur_relok		= $faktur_['FAKTUR'];
				$seq_relok		= $faktur_['SEQ'];
			}
				/* $get_container = $db->query("select distinct master_container.no_booking, container_stripping.no_container from container_stripping, master_container where container_stripping.no_container = master_container.no_container and container_stripping.no_request = '$no_req'");
				$r_cont = $get_container->fetchRow();
				$bp_id = $r_cont["NO_BOOKING"];
				$no_contain = $r_cont["NO_CONTAINER"]; */
				/* $q_de_pel = "select td.bp_id, TD.CONT_NO_BP, pkk.NM_AGEN, PKK.ALMT_AGEN, PKK.NO_NPWP_AGEN from petikemas_cabang.ttd_bp_cont td, petikemas_cabang.ttm_bp_cont tm, petikemas_cabang.v_pkk_cont pkk
							   where td.bp_id = tm.bp_id and tm.no_ukk = pkk.no_ukk
							   and td.bp_id = '$bp_id' and td.cont_no_bp = '$no_contain'";
				$r_pela = $db2->query($q_de_pel);
				$rpela = $r_pela->fetchRow();
				$nm_pelayaran = $rpela["NM_AGEN"];
				$almt_agen = $rpela["ALMT_AGEN"];
				$npwp_agen = $rpela["NO_NPWP_AGEN"]; */
				IF ($koreksi <> 'Y'){			   
				$query_insert_nota_relok	= "INSERT INTO NOTA_RELOKASI_MTY(NO_NOTA, 
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
										TOTAL_DISKON) 
										VALUES('$no_nota_relok',
										'$faktur_relok',
										'$total_relok',
										'$ppn_relok',
										'$tagihan_relok',
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
										SYSDATE,
										'$total_diskon')";	
				$db->query($query_insert_nota_relok);
				// $db4->query($query_insert_nota_relok);
				
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
												  TRANSFER,
												  TOTAL_DISKON
												) VALUES (
												   '$no_nota_relok',
												   	'$no_req', 
													'$faktur_relok',
			                                        '$total_relok',
			                                        '$ppn_relok',
			                                        '$tagihan_relok',
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
													'$seq_relok', 'N',													
													'$total_diskon'
												)";
				} ELSE {
					$query_insert_nota_relok	= "INSERT INTO NOTA_RELOKASI_MTY(NO_NOTA, 
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
										NOTA_LAMA, TOTAL_DISKON, TRANSFER) 
										VALUES('$no_nota_relok',
										'$faktur_relok',
                                        '$total_relok',
                                        '$ppn_relok',
                                        '$tagihan_relok',
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
										'$nota_rellama','$total_diskon', 'N')";	
				
				$db->query($query_insert_nota_relok);
				// $db4->query($query_insert_nota_relok);
				
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
												  TOTAL_DISKON
												) VALUES (
												   '$no_nota_relok',
												   	'$no_req', 
													'$faktur_relok',
			                                        '$total_relok',
			                                        '$ppn_relok',
			                                        '$tagihan_relok',
													'$nipp', 
													'$nota_rellama',
													'NO',
													'KOREKSI', 
			                                        '$nm_pbm',
			                                        '$almt_pbm',
			                                        '$npwp',
			                                        '$adm',
													'$tarif_pass',
													'$kd_pbm',
													SYSDATE,
													'$seq_relok',
													'$total_diskon'
												)";
				}
				if ($rtpk_flag[FLAG] == 'N') {
					$db->query($query_nota_all);
				}
				// $db4->query($query_nota_all);
				
                $query_detail	= "SELECT ID_ISO,TARIF,BIAYA,KETERANGAN,JML_CONT,HZ,TO_CHAR(START_STACK,'mm/dd/rrrr') START_STACK,TO_CHAR(END_STACK,'mm/dd/rrrr') END_STACK, JML_HARI, COA, PPN,URUT,TEKSTUAL,RELOK,DISKON  FROM temp_detail_nota_i WHERE no_request = '$no_req' ";
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
					
					$query_insert	= "INSERT INTO nota_relokasi_mty_d
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
											 TEKSTUAL,
											 DISKON
                                            ) VALUES 
                                            (
                                            '$id_iso',
                                            '$tarif',
                                            '$biaya',
                                            '$ket',
                                            '$no_nota_relok',
                                            '$jml_cont',
                                            '$hz',
                                            TO_DATE('$start','mm/dd/rrrr'), 
                                            TO_DATE('$end','mm/dd/rrrr'), 
                                            '$jml',
											'$coa',
											'$j',
											'$ppn',
											'$urut',
											'$tekstual',
											'$diskon')";
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
											  TEKSTUAL,
											  DISKON
											) VALUES (
											'$id_iso',
                                            '$tarif',
                                            '$biaya',
                                            '$ket',
                                            '$no_nota_relok',
                                            '$jml_cont',
                                            '$hz',
											TO_DATE('$start','mm/dd/rrrr'), 
                                            TO_DATE('$end','mm/dd/rrrr'), 
											'$jml',
											'$coa',
											'$j',
											'$ppn',
											'$urut',
											'$tekstual',
											'$diskon')";
											
							if ($rtpk_flag[FLAG] == 'N') {
								$db->query($query_insert_detail);
							}	
							// $db4->query($query_insert_detail);
						$j++;
              		  } 
              		  
              		if ($rtpk_flag[FLAG] == 'Y') {
							$sql_xpi = "BEGIN
							  USTER.ITPK_POPULATE_STAGING.INSERT_NOTA_ITPK ( '$no_req', 'UST10', '$faktur_relok', '$no_nota_relok', '$koreksi', '$nipp' ); 
							END; ";
              				 // echo $sql_xpi;die;
							$db->query($sql_xpi);
					} 
                
                    $update_nota = "UPDATE NOTA_RELOKASI_MTY SET CETAK_NOTA = '1' WHERE NO_NOTA = '$no_nota_relok'";
					$update_req = "UPDATE REQUEST_STRIPPING SET NOTA_PNKN = 'Y' WHERE NO_REQUEST = '$no_req'";
                    $db->query($update_nota);
					// $db4->query($update_nota);
					$db->query($update_req);
                   // $update_nota1 = "UPDATE REQUEST_STRIPPING SET NOTA = 'Y' WHERE NO_REQUEST = '$no_req'";
                    //$db->query($update_nota1);
                    /*$delete_temp = "DELETE from temp_detail_nota_i WHERE no_request = '$no_req'";
                    $db->query($delete_temp);*/
					
					$db->query("UPDATE PLAN_CONTAINER_STRIPPING SET AKTIF = 'T' WHERE NO_REQUEST = REPLACE ('$no_req','S', 'P')");
					
					header('Location:'.HOME.APPID.'.print/print_nota_lunas_simple_relokasi?no_nota='.$no_nota_relok."&no_req=".$no_req."&first=1");
						
	} else {
			header('Location:'.HOME.APPID.'.print/print_nota_lunas_simple_relokasi?no_nota='.$no_nota_relok."&no_req=".$no_req);
	}

?>