<?php

	$db	= getDB("storage");
	$db4	= getDB("uster_ict");
	$nipp   = $_SESSION["LOGGED_STORAGE"];
	$no_req = $_GET["no_req"];
	$koreksi = $_GET["koreksi"];
	
	$query_cek_nota 	= "SELECT NO_NOTA FROM NOTA_STRIPPING WHERE NO_REQUEST = '$no_req'";
	$result_cek_nota	= $db->query($query_cek_nota);
	$nota				= $result_cek_nota->fetchRow();
	$no_nota_cek		= $nota['NO_NOTA'];
	
	//cek no nota sudah ada atau belom
	if ($no_nota_cek == NULL){
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
        $query_master	= "SELECT a.TGL_REQUEST, b.KD_PBM, b.NM_PBM, b.ALMT_PBM, c.NO_NPWP_PBM, COUNT(d.NO_CONTAINER) JUMLAH 
							FROM request_stripping a, v_mst_pbm b, v_mst_pbm c , container_stripping d 
							WHERE a.KD_CONSIGNEE = b.KD_PBM 
							AND a.KD_PENUMPUKAN_OLEH = c.KD_PBM 
							AND a.NO_REQUEST = d.NO_REQUEST 
							AND a.NO_REQUEST = '$no_req' 
							GROUP BY  a.TGL_REQUEST, b.KD_PBM, b.NM_PBM, b.ALMT_PBM, c.NO_NPWP_PBM";
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
		
       
        //$adm   = 10000;
        //$total_ = $total+$adm;
        //$ppn   = 0.1 * ($total+$adm);
        //+ $tarif_pass;
		
		IF ($koreksi <> 'Y'){
		
		$faktur	 	= "SELECT CONCAT(a.NO_FAKTUR ,(LPAD(NVL((MAX(SEQ_FAKTUR)+1),1),6,0))) FAKTUR, NVL((MAX(SEQ_FAKTUR)+1),1) SEQ FROM NOTA_ALL_H, (SELECT NO_FAKTUR FROM FAKTUR WHERE TAHUN =  to_char(sysdate, 'RRRR') AND KETERANGAN = 'NEW') a GROUP BY a.NO_FAKTUR";
		$result		= $db->query($faktur);
        $faktur_     = $result->fetchRow();
		$faktur		= $faktur_['FAKTUR'];
		$seq		= $faktur_['SEQ'];
		
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
	
		$faktur	 	= "SELECT REPLACE(NO_FAKTUR,'010.','011.') FAKTUR, NO_NOTA FROM NOTA_STRIPPING WHERE NO_REQUEST = '$no_req' AND NO_NOTA =(SELECT MAX(NO_NOTA) FROM NOTA_STRIPPING WHERE NO_REQUEST = '$no_req')";
		$result		= $db->query($faktur);
        $faktur_     = $result->fetchRow();
		$faktur		= $faktur_['FAKTUR'];
		$seq		= 0;
		$nota_lama	= $faktur_['NO_NOTA'];
		
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
	if($db->query($query_insert_nota) AND $db->query($query_nota_all))
	{
		// $db4->query($query_insert_nota);
		// $db4->query($query_nota_all);
                //echo 'dama';die;
                $query_detail	= "SELECT ID_ISO,TARIF,BIAYA,KETERANGAN,JML_CONT,HZ,TO_CHAR(START_STACK,'mm/dd/rrrr') START_STACK,TO_CHAR(END_STACK,'mm/dd/rrrr') END_STACK, JML_HARI, COA, PPN,URUT,TEKSTUAL  FROM temp_detail_nota WHERE no_request = '$no_req' ";
              //  echo $query_detail;die;
				$res		= $db->query($query_detail);
				$row		= $res->getAll();
                $i = 1;
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
											
					$db->query($query_insert_detail);
					// $db4->query($query_insert_detail);
					$i++;
                } 
                
                    $update_nota = "UPDATE NOTA_STRIPPING SET CETAK_NOTA = 'Y' WHERE NO_NOTA = '$no_nota'";
					$update_req = "UPDATE REQUEST_STRIPPING SET NOTA = 'Y' WHERE NO_REQUEST = '$no_req'";
                    $db->query($update_nota);
					// $db4->query($update_nota);
					$db->query($update_req);
                   // $update_nota1 = "UPDATE REQUEST_STRIPPING SET NOTA = 'Y' WHERE NO_REQUEST = '$no_req'";
                    //$db->query($update_nota1);
                    $delete_temp = "DELETE from temp_detail_nota WHERE no_request = '$no_req'";
                    $db->query($delete_temp);
					
					$db->query("UPDATE PLAN_CONTAINER_STRIPPING SET AKTIF = 'T' WHERE NO_REQUEST = REPLACE ('$no_req','S', 'P')");
					if($_GET['simple'] == 1){
						header('Location:'.HOME.APPID.'.print/print_nota_lunas_simple?no_nota='.$no_nota_cek."&no_req=".$no_req);
					}
					else {
						header('Location:'.HOME.APPID.'.print/print_nota_lunas?no_nota='.$no_nota."&no_req=".$no_req);	
					}
  
		}
	} else {
		if($_GET['simple'] == 1){
			header('Location:'.HOME.APPID.'.print/print_nota_lunas_simple?no_nota='.$no_nota_cek."&no_req=".$no_req);
		}
		else {
			header('Location:'.HOME.APPID.'.print/print_nota_lunas?no_nota='.$no_nota_cek."&no_req=".$no_req);	
		}
	}

?>