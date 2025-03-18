<?php

$db	= getDB("storage");
$db4	= getDB("uster_ict");
$nipp   = $_SESSION["LOGGED_STORAGE"];
$no_req = $_GET["no_req"];
$koreksi = $_GET["koreksi"];

	$query_cek_nota 	= "SELECT NO_NOTA, STATUS FROM NOTA_PNKN_STUF WHERE NO_REQUEST = '$no_req'";
	$result_cek_nota	= $db->query($query_cek_nota);
	$nota				= $result_cek_nota->fetchRow();
	$no_nota_cek		= $nota['NO_NOTA'];
	$st_nota		= $nota['STATUS'];
	
	//cek no nota sudah ada atau belom
	if (($no_nota_cek == NULL && $st_nota == NULL) || ($no_nota_cek != NULL && $st_nota == 'BATAL')){
	//Insert ke tabel nota

        $query_cek	= "SELECT NVL(LPAD(MAX(TO_NUMBER(SUBSTR(NO_NOTA,10,15))+1),6,0), '000001') JUM_,
                              TO_CHAR(SYSDATE, 'MM') AS MONTH, 
                              TO_CHAR(SYSDATE, 'YY') AS YEAR 
                        FROM NOTA_PNKN_STUF 
                       WHERE NOTA_PNKN_STUF.TGL_NOTA BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE)";
					   
					   //select LPAD(MAX(TO_NUMBER(SUBSTR(NO_REQUEST,8,13)))+1,6,0) FROM REQUEST_RECEIVING 
                       //WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE)
				   
		$result_cek	= $db->query($query_cek);
		$jum_		= $result_cek->fetchRow();
		$jum		= $jum_["JUM_"];
		$month		= $jum_["MONTH"];
		$year		= $jum_["YEAR"];
		
		$no_nota	= "1205".$month.$year.$jum;


			//select master pbm
			$query_master	= "/* Formatted on 9/6/2012 3:13:17 AM (QP5 v5.163.1008.3004) */
								  SELECT b.KD_PBM,
										 b.NM_PBM,
										 b.ALMT_PBM,
										 b.NO_NPWP_PBM,
										 COUNT (d.NO_CONTAINER) JUMLAH,
										 TO_CHAR(TGL_REQUEST,'dd/mon/yyyy') TGL_REQUEST
									FROM request_stuffing a,
										 v_mst_pbm b,
										 container_stuffing d
								   WHERE     a.ID_PENUMPUKAN = b.KD_PBM
										 AND a.NO_REQUEST = d.NO_REQUEST
										 AND a.NO_REQUEST = '$no_req'
									GROUP BY b.KD_PBM,b.NM_PBM, b.ALMT_PBM, b.NO_NPWP_PBM, TO_CHAR(TGL_REQUEST,'dd/mon/yyyy')";
		//echo $query_master;die;
			$result_cek	= $db->query($query_master);
			$master		= $result_cek->fetchRow();
			
			$kd_pbm		= $master["KD_PBM"];
			$nm_pbm		= $master["NM_PBM"];
			$almt_pbm	= $master["ALMT_PBM"];
			$npwp   	= $master["NO_NPWP_PBM"];
			$jumlah_cont  	= $master["JUMLAH"];
			$tgl_re  	= $master["TGL_REQUEST"];
			
			
			  //tarif pass
		/*$pass		  = "SELECT TO_CHAR(($jumlah_cont * a.TARIF), '999,999,999,999') PASS, ($jumlah_cont * a.TARIF) TARIF
						  FROM master_tarif a, group_tarif b
						 WHERE a.ID_GROUP_TARIF = b.ID_GROUP_TARIF
							   AND TO_DATE ('$tgl_re', 'dd/mm/yyyy') BETWEEN b.START_PERIOD
																			AND b.END_PERIOD
							   AND a.ID_ISO = 'PASS'";
					   
		$result__     = $db->query($pass);
		$row_pass     = $result__->fetchRow();
		$tarif_pass   = $row_pass['TARIF'];*/
		
			 $total_		= "SELECT SUM(BIAYA) TOTAL, SUM(PPN) PPN,(SUM(BIAYA)+SUM(PPN)) TOTAL_TAGIHAN FROM temp_detail_nota_i WHERE no_request = '$no_req'";
			//echo $total_;die;
			$result		= $db->query($total_);
			$total2         = $result->fetchRow();
			$total_ 		= $total2['TOTAL'];
			$ppn	 		= $total2['PPN'];
			$tagihan 		= $total2['TOTAL_TAGIHAN'];
			//$total = $total2['TOTAL'];
			//Biaya Administrasi
		
			$query_adm		= "SELECT TO_CHAR(a.TARIF , '999,999,999,999') AS ADM, a.TARIF FROM MASTER_TARIF a, GROUP_TARIF b WHERE a.ID_GROUP_TARIF = b.ID_GROUP_TARIF AND b.KATEGORI_TARIF = 'ADMIN_NOTA'";
			$result_adm	    = $db->query($query_adm);
			$row_adm		= $result_adm->fetchRow();
			$adm 			= $row_adm['TARIF'];

		
			//$total_ = $total+$adm;
			//$ppn   = 0.1 * ($total+$adm);
			//$tagihan = $total_ + $ppn;
			$tarif_pass = 0;

			$itpk_flag = "select CASE WHEN to_date(tgl_request,'dd/mm/rrrr') >=kapal_prod.all_general_pkg.get_cutoff_date('USTER','05')
					                THEN 'Y' ELSE 'N' END FLAG
						FROM request_stuffing
						where no_request = '$no_req'";
			$rtpk_flag = $db->query($itpk_flag)->fetchRow();

		IF ($koreksi <> 'Y' ){
		//$faktur	 	= "SELECT CONCAT(a.NO_FAKTUR ,(LPAD(NVL((MAX(SEQ_FAKTUR)+1),1),6,0))) FAKTUR, NVL((MAX(SEQ_FAKTUR)+1),1) SEQ FROM NOTA_ALL_H, (SELECT NO_FAKTUR FROM FAKTUR WHERE TAHUN =  to_char(sysdate, 'RRRR') AND KETERANGAN = 'NEW') a GROUP BY a.NO_FAKTUR";
		
		if($rtpk_flag[FLAG] == 'Y'){
			$faktur		= $_GET['trx'];
			$seq		= 0;
		}
		else {
			$faktur		= "SELECT concat(CONCAT(a.no_faktur, b.kode_faktur), s.faktur) FAKTUR, s.SEQ FROM  (SELECT (LPAD (NVL ( (MAX (SEQ_FAKTUR) + 1), 1), 6, 0))
	                                        FAKTUR,
	                                     NVL ( (MAX (SEQ_FAKTUR) + 1), 1) SEQ
	                                FROM  NOTA_ALL_H
	                                     where trunc(NOTA_ALL_H.TGL_NOTA) BETWEEN TRUNC(SYSDATE,'YEAR') AND  LAST_DAY(ADD_MONTHS(TRUNC(SYSDATE , 'Year'),11))
	                                      AND trunc(NOTA_ALL_H.TGL_NOTA) >= TO_DATE('1/6/2013','DD/MM/RRRR')  ) s,
	                                      (SELECT NO_FAKTUR
	                                        FROM FAKTUR_NEW
	                                       WHERE TAHUN = TO_CHAR (SYSDATE, 'RRRR') AND KETERANGAN = 'NEW') a,
	                                      (SELECT KODE_FAKTUR FROM MASTER_NOTA WHERE ID_NOTA = 12) b";
			$result		= $db->query($faktur);
			$faktur_     = $result->fetchRow();
			$faktur		= $faktur_['FAKTUR'];
			$seq		= $faktur_['SEQ'];
		}
		$query_insert_nota	= "INSERT INTO NOTA_PNKN_STUF(NO_NOTA, 
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
														'$seq','N'
													)";
		} else {
			$faktur	 	= "SELECT NO_NOTA, NO_FAKTUR, KD_EMKL FROM NOTA_PNKN_STUF WHERE NO_REQUEST = '$no_req' AND NO_NOTA =(SELECT MAX(NO_NOTA) FROM NOTA_PNKN_STUF WHERE NO_REQUEST = '$no_req')";
			$result		= $db->query($faktur);
			$faktur_     = $result->fetchRow();
			//$faktur		= $faktur_['FAKTUR'];
			$nota_lama	= $faktur_['NO_FAKTUR'];
			$emkl_lama	= $faktur_['KD_EMKL'];
			
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
								  (SELECT KODE_FAKTUR FROM MASTER_NOTA WHERE ID_NOTA = 12) b";
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
								  (SELECT KODE_FAKTUR FROM MASTER_NOTA WHERE ID_NOTA = 12) b";
			}
			
			if($rtpk_flag[FLAG] == 'Y'){
				$faktur		= $_GET['trx'];
				$seq		= 0;
			}
			else {
				$result_f = $db->query($faktur_koreksi);
				$f_result = $result_f->fetchRow();
				$faktur = $f_result["FAKTUR"];
				$seq		= $f_result["SEQ"];
			
				if($faktur == NULL){
					$faktur	 	= "SELECT concat(CONCAT(a.no_faktur, b.kode_faktur), s.faktur) FAKTUR, s.SEQ FROM  (SELECT (LPAD (NVL ( (MAX (SEQ_FAKTUR) + 1), 1), 6, 0))
										FAKTUR,
									 NVL ( (MAX (SEQ_FAKTUR) + 1), 1) SEQ
								FROM  NOTA_ALL_H
									  where trunc(NOTA_ALL_H.TGL_NOTA) BETWEEN TRUNC(SYSDATE,'YEAR') AND  LAST_DAY(ADD_MONTHS(TRUNC(SYSDATE , 'Year'),11))
	                                      AND trunc(NOTA_ALL_H.TGL_NOTA) >= TO_DATE('1/6/2013','DD/MM/RRRR')  ) s,
									  (SELECT NO_FAKTUR
										FROM FAKTUR_NEW
									   WHERE TAHUN = TO_CHAR (SYSDATE, 'RRRR') AND KETERANGAN = 'KOREKSI_N') a,
									  (SELECT KODE_FAKTUR FROM MASTER_NOTA WHERE ID_NOTA = 12) b";
					$result		= $db->query($faktur);
					$faktur_     = $result->fetchRow();
					$faktur		= $faktur_['FAKTUR'];
					$seq		= $faktur_['SEQ'];
				}
			}
			
			$update = "UPDATE NOTA_PNKN_STUF SET STATUS = 'BATAL' WHERE NO_NOTA = '$nota_lama'";
			$db->query($update);
			// $db4->query($update);
			$query_insert_nota	= "INSERT INTO NOTA_PNKN_STUF(NO_NOTA, 
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
			
			$query_detail	= "SELECT ID_ISO,TARIF,BIAYA,KETERANGAN,JML_CONT,HZ,TO_CHAR(START_STACK,'dd/mm/rrrr') START_STACK,TO_CHAR(END_STACK,'dd/mm/rrrr') END_STACK, JML_HARI, COA, PPN, URUT,TEKSTUAL  FROM temp_detail_nota_i WHERE no_request = '$no_req' ";
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
						  $tekstual = $item['TEKSTUAL'];
						
						$query_insert	= "INSERT INTO NOTA_PNKN_STUF_D
												(
												 ID_ISO,
												 TARIF,
												 BIAYA,
												 KETERANGAN,
												 NO_NOTA,
												 JUMLAH_CONT,
												 HZ,
												 START_STACK,
												 END_STACK,
												 JML_HARI,
												 COA,
												 PPN,
												 LINE_NUMBER,
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
												TO_DATE('$start','dd/mm/yyyy'), 
												TO_DATE('$end','dd/mm/yyyy'), 
												'$jml','$coa','$ppn','$i','$urut','$tekstual')";
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
						  PPN,
						  LINE_NUMBER,
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
												TO_DATE('$start','dd/mm/yyyy'), 
                                                TO_DATE('$end','dd/mm/yyyy'), 
												'$jml',
												'$coa',
												'$ppn','$i','$urut','$tekstual')";
						
						if ($rtpk_flag[FLAG] == 'N') {
							$db->query($query_insert_detail);
						}
						$i++;
					} 

						if ($rtpk_flag[FLAG] == 'Y') {
							$sql_xpi = "BEGIN
							  USTER.ITPK_POPULATE_STAGING.INSERT_NOTA_ITPK ( '$no_req', 'UST12', '$faktur', '$no_nota', '$koreksi', '$nipp' ); 
							END; ";
              				 // echo $sql_xpi;die;
							$db->query($sql_xpi);
						} 					
						$update_nota = "UPDATE NOTA_PNKN_STUF SET CETAK_NOTA = '1' WHERE NO_NOTA = '$no_nota'";
						$db->query($update_nota);
						// $db4->query($update_nota);
						$update_aktif = "UPDATE plan_container_stuffing set AKTIF = 'T' where no_request = replace('$no_req','S','T') and tgl_approve IS NULL";
						$db->query($update_aktif);
						$update_req = "UPDATE REQUEST_STUFFING SET NOTA_PNKN = 'Y' WHERE NO_REQUEST = '$no_req'";
						$db->query($update_req);
						//$update_nota1 = "UPDATE REQUEST_STUFFING SET NOTA = 'Y' WHERE NO_REQUEST = '$no_req'";
						//$db->query($update_nota1);
						$delete_temp = "DELETE from temp_detail_nota_i WHERE no_request = '$no_req'";
						$db->query($delete_temp);
						header('Location:'.HOME.APPID.'.print/print_nota_lunas_pnkn?no_nota='.$no_nota."&no_req=".$no_req."&first=1");	
  
        }
	} else {
		
		if($_GET['simple'] == 1){
			header('Location:'.HOME.APPID.'.print/print_nota_lunas_pnkn?no_nota='.$no_nota_cek."&no_req=".$no_req."&first=1");
		}
		else {
			header('Location:'.HOME.APPID.'.print/print_nota_lunas_pnkn?no_nota='.$no_nota_cek."&no_req=".$no_req."&first=1");	
		}
		
		 // header('Location:'.HOME.APPID.'.print/print_nota_lunas?no_nota='.$no_nota_cek."&no_req=".$no_req);	
	}

/*
else
{
	$no_req	= $_GET["no_req"];
	$no_nota		= $_GET["no_nota"];
	$nota_			= $nota + 1;
	$query_update	= "UPDATE NOTA_RECEIVING SET CETAK_NOTA = '$nota_' WHERE NO_NOTA = '$no_nota'";
	
	if($db->query($query_update))
	{
		//echo HOME;
		header('Location:'.HOME.APPID.'/print_nota?no_nota='.$no_nota);		
	}	
}
*/
?>