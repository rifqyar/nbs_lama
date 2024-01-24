<?php

$db	= getDB("storage");
$db4	= getDB("uster_ict");
$nipp   = $_SESSION["LOGGED_STORAGE"];
$no_req = $_GET["no_req"];
	//Membuat nomor nota
	/*
	$query_cek	= "SELECT COUNT(1) AS JUM, 
						  TO_CHAR(SYSDATE, 'MM') AS MONTH, 
						  TO_CHAR(SYSDATE, 'YY') AS YEAR 
				   FROM NOTA_DELIVERY
				   WHERE TGL_NOTA BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) ";
	$result_cek	= $db->query($query_cek);
	$jum_		= $result_cek->fetchRow();
	$jum		= $jum_["JUM"]+1;
	$month		= $jum_["MONTH"];
	$year		= $jum_["YEAR"];
	
	$no_nota	= "DEV"."05".$month.$year;
	*/
	
	//Membuat nomor Faktur
	/*$query_cek	= "SELECT ";
	$result_cek	= $db->query($query_cek);
	$jum_		= $result_cek->fetchRow();
	$jum		= $jum_["JUM"]+1;
	$year		= $jum_["YEAR"];
        
	$no_faktur	= '010.010.-'.$year.$jum;
	*/
	//Insert ke tabel nota

        $query_cek	= "SELECT NVL(LPAD(MAX(TO_NUMBER(SUBSTR(NO_NOTA,10,15))+1),6,0), '000001') JUM_,
                              TO_CHAR(SYSDATE, 'MM') AS MONTH, 
                              TO_CHAR(SYSDATE, 'YY') AS YEAR 
                        FROM NOTA_DELIVERY 
                       WHERE NOTA_DELIVERY.TGL_NOTA BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE)";
					   
					   //select LPAD(MAX(TO_NUMBER(SUBSTR(NO_REQUEST,8,13)))+1,6,0) FROM REQUEST_RECEIVING 
                       //WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE)
				   
	$result_cek	= $db->query($query_cek);
	$jum_		= $result_cek->fetchRow();
	$jum		= $jum_["JUM_"];
	$month		= $jum_["MONTH"];
	$year		= $jum_["YEAR"];
	
	$no_nota	= "0105".$month.$year.$jum;


        //select master pbm
        $query_master	= "SELECT b.kd_pbm ,b.nm_pbm, b.almt_pbm, b.no_npwp_pbm,  TO_CHAR(a.TGL_REQUEST,'dd/Mon/yyyy') TGL_REQUEST, COUNT(c.NO_CONTAINER) JUMLAH
  					FROM REQUEST_DELIVERY a, v_mst_pbm b , CONTAINER_DELIVERY c
 					WHERE a.KD_EMKL = b.kd_pbm 
 					AND a.NO_REQUEST = c.NO_REQUEST
  					AND a.no_request = '$no_req'
   					GROUP BY b.nm_pbm, b.almt_pbm, b.no_npwp_pbm, TO_CHAR(a.TGL_REQUEST,'dd/Mon/yyyy')";
	//echo $query_master;die;
        $result_cek	= $db->query($query_master);
	$master		= $result_cek->fetchRow();
	$kd_pbm		= $master["KD_PBM"];
	$nm_pbm		= $master["NM_PBM"];
	$almt_pbm	= $master["ALMT_PBM"];
        $npwp   	= $master["NO_NPWP_PBM"];
        $jumlah_cont 	= $master["JUMLAH"];
	 $tgl_re   	= $master["TGL_REQUEST"];


	  //tarif pass
	/* $pass		  = "SELECT TO_CHAR(($jumlah_cont * a.TARIF), '999,999,999,999') PASS, ($jumlah_cont * a.TARIF) TARIF
					  FROM master_tarif a, group_tarif b
					 WHERE a.ID_GROUP_TARIF = b.ID_GROUP_TARIF
					       AND TO_DATE ('$tgl_re', 'dd/mm/yyyy') BETWEEN b.START_PERIOD
					                                                    AND b.END_PERIOD
					       AND a.ID_ISO = 'PASS'";
				   
	$result__     = $db->query($pass);
	$row_pass     = $result__->fetchRow(); */
       $tarif_pass   =0;

        
        $total_		= "SELECT SUM(BIAYA) TOTAL FROM temp_detail_nota WHERE no_request = '$no_req'";
        //echo $total_;die;
        $result		= $db->query($total_);
        $total2         = $result->fetchRow();
     
        
        $total = $total2['TOTAL'];
        $adm   = 10000;
        $total_ = $total+$adm;
        $ppn   = 0.1 * ($total+$adm);
        $tagihan = $total_ + $ppn;// + $tarif_pass;
		
		$faktur	 	= "SELECT CONCAT(a.NO_FAKTUR ,(LPAD(NVL((MAX(SEQ_FAKTUR)+1),1),6,0))) FAKTUR, NVL((MAX(SEQ_FAKTUR)+1),1) SEQ FROM NOTA_ALL_H, (SELECT NO_FAKTUR FROM FAKTUR WHERE TAHUN =  to_char(sysdate, 'RRRR') AND KETERANGAN = 'NEW') a GROUP BY a.NO_FAKTUR";
		$result		= $db->query($faktur);
        $faktur_     = $result->fetchRow();
		$faktur		= $faktur_['FAKTUR'];
		$seq		= $faktur_['SEQ'];

	$query_insert_nota	= "INSERT INTO NOTA_DELIVERY(NO_NOTA, 
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
										PASS, BAYAR) 
										VALUES('$no_nota',
										'$no_faktur',
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
										'$tarif_pass', 'PIUTANG')";
		
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
												  SEQ_FAKTUR, TRANSFER   
												) VALUES (
												   '$no_nota',
												   	'$no_req', 
													'$no_faktur',
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
												
	//echo $query_insert_nota;die;
	if($db->query($query_insert_nota))
	{
		// $db4->query($query_insert_nota);
                //echo 'dama';die;
                $query_detail	= "SELECT * FROM temp_detail_nota WHERE no_request = '$no_req' ";
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
                    $start  = $item['start_stack'];
                    $end    = $item['end_stack'];
                    $jml    = $item['JML_HARI'];
			 $coa    = $item['COA'];
			
			
                    
                    $query_insert	= "INSERT INTO nota_delivery_d
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
						   COA
                                            ) VALUES 
                                            (
                                            '$id_iso',
                                            '$tarif',
                                            '$biaya',
                                            '$ket',
                                            '$no_nota',
                                            '$jml_cont',
                                            '$hz',
                                            '$start', 
                                            '$end',
                                            '$jml',
											'$coa')";
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
					  LINE_NUMBER
					) VALUES (
											'$id_iso',
                                            '$tarif',
                                            '$biaya',
                                            '$ket',
                                            '$no_nota',
                                            '$jml_cont',
                                            '$hz',
											'$start', 
                                            '$end',
                                            '$jml',
											'$coa',
											'$i')";
					$db->query($query_insert_detail);
					// $db4->query($query_insert_detail);
					$i++;
                } 
                
                    $update_nota = "UPDATE NOTA_DELIVERY SET CETAK_NOTA = 'Y' WHERE NO_NOTA = '$no_nota'";
                    $db->query($update_nota);
					// $db4->query($update_nota);
                    $update_nota1 = "UPDATE REQUEST_DELIVERY SET NOTA = 'Y' WHERE NO_REQUEST = '$no_req'";
                    $db->query($update_nota1);
                    $delete_temp = "DELETE from temp_detail_nota WHERE no_request = '$no_req'";
                    $db->query($delete_temp);
                    header('Location:'.HOME.APPID.'.print/print_nota_lunas?no_nota='.$no_nota."&no_req=".$no_req);	
  
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