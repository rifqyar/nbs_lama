<?php

$db		= getDB("storage");

$nota	 = $_GET["n"];
$nipp	 = $_SESSION["NIPP"];
$no_req	 = $_GET["no_req"];
$no_nota = $_GET["no_nota"];

if($nota == 999)
{
	
	$no_req         = $_GET["no_req"];
	$query_cek	= "SELECT lpad((COUNT(1)+1),6,0) AS JUM, TO_CHAR(SYSDATE, 'MM') AS MONTH, TO_CHAR(SYSDATE, 'YY') AS YEAR FROM NOTA_DELIVERY WHERE TGL_NOTA BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) ";
	$result_cek	= $db->query($query_cek);
	$jum_		= $result_cek->fetchRow();
	$jum		= $jum_["JUM"];
	$month		= $jum_["MONTH"];
	$year		= $jum_["YEAR"];
        
        $no_nota	= $month.$year.$jum;
	
        $query_cek	= "SELECT lpad(COUNT(1),6,0) AS JUM,  TO_CHAR(SYSDATE, 'YY') AS YEAR FROM NOTA_DELIVERY WHERE TGL_NOTA BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) ";
	$result_cek	= $db->query($query_cek);
	$jum_		= $result_cek->fetchRow();
	$jum		= $jum_["JUM"];
	$year		= $jum_["YEAR"];
	$no_faktur	= '010.010.-'.$year.'.'.$jum;
	
        //mengecek apakah pada no request ada penggunaan jasa haulage
        $query_cek2	= "SELECT NO_CONTAINER FROM CONTAINER_DELIVERY WHERE NO_REQUEST = '$no_req' AND HAULAGE_REC = 'Y' OR HAULAGE_DEV = 'Y'";
	$result_cek2	= $db->query($query_cek2);
	$jum_		= $result_cek2->getAll();
        if (count($jum_) > 0 ){
            $hand = 'Y';
        } else {
            $hand = 'N';
        }
        
	$query_insert_nota	= "INSERT INTO NOTA_DELIVERY(NO_NOTA, NO_FAKTUR, NO_REQUEST, NIPP_USER, LUNAS, CETAK_NOTA, TGL_NOTA, HAULAGE) VALUES ('$no_nota', '$no_faktur', '$no_req', '$nipp', 'NO', 1, SYSDATE, '$hand')";	
        //echo $query_insert_nota;
       //echo $query_insert_nota;die;
        $tagihan = 0;
	if($db->query($query_insert_nota))
	{
                $query          = "select a.TGL_PLACEMENT  TGL_PLACEMENT, b.STATUS, c.SIZE_, c.TYPE_,  b.HZ, TO_CHAR(d.TGL_REQUEST_DELIVERY, 'dd/mm/yyyy') TGL_REQ_OUT, COUNT(b.STATUS) jml_cont
                                FROM PLACEMENT a, CONTAINER_DELIVERY b, MASTER_CONTAINER c, REQUEST_DELIVERY d
                                WHERE a.NO_CONTAINER = c.NO_CONTAINER AND A.NO_CONTAINER = b.NO_CONTAINER AND b.AKTIF = 'Y' AND b.NO_REQUEST = '$no_req' AND d.NO_REQUEST = b.NO_REQUEST
                                group BY a.TGL_PLACEMENT, b.STATUS, c.SIZE_, c.TYPE_, d.TGL_REQUEST_DELIVERY, b.HZ";
            //    echo $query;die;
                $result_cek	= $db->query($query);
		$rows		= $result_cek->getAll();
             // echo count($rows);die;
             // 
                //for ($i=0;$i<=count($rows);$i++){
                foreach ($rows as $rows_){
//                    $status     = $rows[$i]['STATUS'];
//                    $type       = $rows[$i]['TYPE_'];
//                    $size       = $rows[$i]['SIZE_'];
//                    $jml_cont   = $rows[$i]['JML_CONT'];
//                    $hz         = $rows[$i]['HZ'];
//                    $tgl_placement   = $rows[$i]['TGL_PLACEMENT'];
//                    $tgl_req_out     = $rows[$i]['TGL_REQ_OUT'];
                    
                    $status     = $rows_['STATUS'];
                    $type       = $rows_['TYPE_'];
                    $size       = $rows_['SIZE_'];
                    $jml_cont   = $rows_['JML_CONT'];
                    $hz         = $rows_['HZ'];
                    $tgl_placement   = $rows_['TGL_PLACEMENT'];
                    $tgl_req_out     = $rows_['TGL_REQ_OUT'];
                    
                    //echo $tgl_placement;die;
                    
                    //$update     = "UPDATE NOTA_DELIVERY SET START_STACK = TO_DATE('".$tgl_placement."','dd/mon/yyyy')+4, END_STACK = TO_DATE('".$tgl_req_out."','dd/mm/yyyy') WHERE NO_NOTA = '$no_nota'";
                    //echo $update;die;
                    $db->query($update);
                    
                    $tarif1     = " SELECT a.TARIF, b.ID_ISO 
                                        FROM MASTER_TARIF a, ISO_CODE b, GROUP_TARIF c 
                                        where b.ID_ISO = a.ID_ISO 
                                        AND a.ID_GROUP_TARIF = c.ID_GROUP_TARIF
                                        AND c.KATEGORI_TARIF = 'PENUMPUKKAN'
                                        AND b.SIZE_ = '$size' 
                                        AND b.TYPE_ = '$type'
                                        AND b.STATUS = '$status'
                                        AND TO_DATE(SUBSTR('".$tgl_placement."',1,10),'dd/mm/yyyy') BETWEEN c.start_period AND c.end_period";
                   // echo $tarif1; die;
                     $tarif_	= $db->query($tarif1);
                     $rows	= $tarif_->fetchRow();
                     $tarif     = $rows['TARIF'];
                     $id_iso    = $rows['ID_ISO'];
                    // (TO_DATE(SUBSTR('21-06-2012 13.54.13,000000',1,10),'dd/mm/yy')+4)
					 
                     $jml_hari1  = " SELECT TO_DATE('".$tgl_req_out."', 'dd/mm/yyyy') - (TO_DATE(SUBSTR('".$tgl_placement."',1,10),'dd/mm/yy')+4) jumlah_hari FROM dual";
                   //  echo $jml_hari1;
                     $jml_hari_	= $db->query($jml_hari1);
                     $rows_	= $jml_hari_->fetchRow();
                     $jml_hari  = $rows_['JUMLAH_HARI'];
                   // echo 'jml_hari'.$jml_hari;
                     
                     //hitung formulasi penumpukan
                   //  $masa11 = 0;
                     if ($jml_hari <= 5){
                         $masa12 = $jml_hari;
                         $masa2  = 0;
                     } else {
                         $masa12 = 5;
                         $masa2  = $jml_hari-5;
                     }
                     
                     if ($hz == 'Y'){
                        $tarifmasa12 = $tarif*2;
                        $biayamasa12 = $tarifmasa12*$masa12*$jml_cont;
                        $tarifmasa2  = $tarif*4;
                        $biayamasa2  = $tarifmasa2*$masa2*$jml_cont;
                     } else {
                        $tarifmasa12 = $tarif;
                        $biayamasa12 = $tarif*$masa12*$jml_cont;
                        $tarifmasa2  = $tarif*2;
                        $biayamasa2  = $tarifmasa2*$masa2*$jml_cont;
                     }
                     
                     $tagihan_ = $biayamasa12 + $biayamasa2;
                     $tagihan  = $tagihan + $tagihan_;
//                     echo 'masa12 '.$masa12;
//                     echo 'masa2 '.$masa2;
//                     echo 'tarif masa12 '.$tarifmasa12;
//                     echo 'tarif masa2 '.$tarifmasa2;
//                     echo 'biaya masa12 '.$biayamasa12;
//                     echo 'biaya masa2 '.$biayamasa2;die;
                     
                     
                     if (($masa12 <> 0) && ($masa2 <> 0)){
                         $endstack2    = "SELECT TO_DATE(SUBSTR('".$tgl_placement."',1,10),'dd/mm/yyyy')+4+$masa12 ENDSTACK1 FROM DUAL";
                         $end_stack_     = $db->query($endstack2);
                         $row_         = $end_stack_->fetchRow();
                         $end_stack1   = $row_['ENDSTACK1'];
                         
                         $startstack2  = "SELECT TO_DATE('".$end_stack1."','dd/mm/yyyy')+1 STARTSTACK2 FROM DUAL";
                         $startstack_  = $db->query($startstack2);
                         $row_         = $startstack_->fetchRow();
                         $start_stack2 = $row_['STARTSTACK2'];
                         
                         $endstack2    = "SELECT TO_DATE('".$start_stack2."','dd/mm/yyyy')+$masa2-1 ENDSTACK2 FROM DUAL";
                         $endstack_     = $db->query($endstack2);
                         $row_         = $endstack_->fetchRow();
                         $end_stack2   = $row_['ENDSTACK2'];
                         
                         $query3 = "INSERT INTO nota_delivery_d (ID_ISO, TARIF, BIAYA, KETERANGAN, ID_NOTA, JML_CONT, HZ, START_STACK, END_STACK, JML_HARI) VALUES ('$id_iso','$tarifmasa12','$biayamasa12','PENUMPUKAN MASA I.2','$no_nota','$jml_cont','$hz',TO_DATE('".$tgl_placement."','dd/mm/yyyy')+5,TO_DATE('".$end_stack1."','dd/mm/yyyy'), (TO_DATE('".$end_stack1."','dd/mm/yyyy')-(TO_DATE('".$tgl_placement."','dd/mm/yyyy')+4)))";
                         $db->query($query3);
                         $query4 = "INSERT INTO nota_delivery_d (ID_ISO, TARIF, BIAYA, KETERANGAN, ID_NOTA, JML_CONT, HZ, START_STACK, END_STACK, JML_HARI) VALUES ('$id_iso','$tarifmasa2','$biayamasa2','PENUMPUKAN MASA 2','$no_nota','$jml_cont','$hz',TO_DATE('".$start_stack2."','dd/mm/yyyy'),TO_DATE('".$end_stack2."','dd/mm/yyyy'), TO_DATE('".$end_stack2."','dd/mm/yyyy')-TO_DATE('".$start_stack2."','dd/mm/yyyy')+1)";
                         $db->query($query4);
                        // echo $query3;
                         //die;
                       //  echo $query4;
                       //  die;
                         
                         
                     } else if (($masa12 <> 0) && ($masa2 == 0)){
                         $endstack1    = "SELECT TO_DATE(SUBSTR('".$tgl_placement."',1,10),'dd/mm/yyyy')+$masa12 ENDSTACK1 FROM DUAL";
                         $end_stack_   = $db->query($endstack1);
                         $row_         = $end_stack_->fetchRow();
                         $end_stack1   = $row_['ENDSTACK1'];
                         
                         $query1 = "INSERT INTO nota_delivery_d (ID_ISO, TARIF, BIAYA, KETERANGAN, ID_NOTA, JML_CONT, HZ, START_STACK, END_STACK, JML_HARI) 
						 VALUES ('$id_iso','$tarifmasa12','$biayamasa12','PENUMPUKAN MASA I.2','$no_nota','$jml_cont','$hz',TO_DATE(SUBSTR('".$tgl_placement."',1,10),'dd/mm/yyyy')+5,TO_DATE('".$end_stack1."','dd/mm/yyyy'), TO_DATE('".$end_stack1."','dd/mm/yyyy')-TO_DATE(SUBSTR('".$tgl_placement."',1,10),'dd/mm/yyyy')+1)";
//                          echo $query1;
//                         die;
                         $db->query($query1);
                     } 
                     
                }
                
                $haulage_rec     = "  select b.id_group_tarif, count(b.id_group_tarif) jml_cont, e.id_iso, d.tarif, e.size_, e.type_, e.status, c.tgl_placement+4 TGL_START_NOTA
                                        from container_delivery a, group_tarif b, placement c, master_tarif d, iso_code e, master_container f
                                        where d.ID_ISO = e.ID_ISO 
                                        and a.no_container = f.no_container
                                        and a.status = e.status
                                        and f.size_ = e.size_
                                        and F.TYPE_ = e.type_
                                        AND a.no_container = c.no_container 
                                        and b.id_group_tarif = d.id_group_tarif 
                                        and b.kategori_tarif = 'HAULAGE' 
                                        and a.no_request = '$no_req' 
                                        and a.haulage_rec = 'Y'
                                        and c.tgl_placement+4 between b.start_period and b.end_period 
                                        group by b.id_group_tarif, c.tgl_placement, e.id_iso, b.id_group_tarif, d.tarif,e.size_, e.type_, e.status";
                //echo $haulage_rec;die;
                $haulage2     = $db->query($haulage_rec);
                $rows         = $haulage2->getAll();
                
                  $biaya_haulage_ = 0;
                  if(count($rows) > 0){
                  for($i=0;$i<count($rows);$i++){
                    $jml_cont      = $rows[$i]['JML_CONT'];
                    $tarif         = $rows[$i]['TARIF'];
                    $id_iso        = $rows[$i]['ID_ISO'];
                    $tgl_start     = $rows[$i]['TGL_START_NOTA'];
                    $biaya_haulage = $jml_cont*$tarif;
                    $biaya_haulage_ = $biaya_haulage_ + $biaya_haulage;

                    $query2 = "INSERT INTO nota_delivery_d (ID_DETAIL_NOTA,ID_ISO, TARIF, BIAYA, KETERANGAN, ID_NOTA, JML_CONT, HZ, START_STACK, END_STACK) VALUES ('','$id_iso','$tarif','$biaya_haulage','HAULAGE RECEIVING','$no_nota','$jml_cont','$hz',TO_DATE('".$tgl_start."','dd/mm/yyyy'),TO_DATE('".$tgl_req_out."','dd/mm/yyyy'))";
                    $db->query($query2);
                    } //echo 'die';die;
                  }   
                  
                   $haulage_dev     = "select b.id_group_tarif, count(b.id_group_tarif) jml_cont, e.id_iso, d.tarif, e.size_, e.type_, e.status, TO_CHAR(c.tgl_request_delivery,'dd/mm/yyyy') TGL_REQUEST_DELIVERY
                                        from container_delivery a, group_tarif b, master_tarif d, iso_code e, master_container f, request_delivery c
                                        where d.ID_ISO = e.ID_ISO 
                                        and a.no_container = f.no_container
                                        and a.no_request = c.no_request
                                        and a.status = e.status
                                        and f.size_ = e.size_
                                        and F.TYPE_ = e.type_
                                        and b.id_group_tarif = d.id_group_tarif 
                                        and b.kategori_tarif = 'HAULAGE' 
                                        and a.no_request = '$no_req' 
                                        and a.haulage_dev = 'Y'
                                        and c.tgl_request_delivery between b.start_period and b.end_period 
                                        group by b.id_group_tarif, b.id_group_tarif, e.id_iso, d.tarif,e.size_, e.type_, e.status, TO_CHAR(c.tgl_request_delivery,'dd/mm/yyyy')";
                 //  echo $haulage_dev;die;
                    $haulage3     = $db->query($haulage_dev);
                    $row2         = $haulage3->getAll();
                
                  $biaya_haulage2_ = 0;
                  if(count($row2) > 0){
                      //echo count($row2);die;
                  for($i=0;$i<count($row2);$i++){
                    $jml_cont     = $row2[$i]['JML_CONT'];
                    $tarif        = $row2[$i]['TARIF'];
                    $id_iso        = $row2[$i]['ID_ISO'];
                    $tgl_start    = $row2[$i]['TGL_REQUEST_DELIVERY'];
                    $biaya_haulage2 = $jml_cont*$tarif;
                    $biaya_haulage2_ = $biaya_haulage2_ + $biaya_haulage2;

                    $query3 = "INSERT INTO nota_delivery_d (ID_DETAIL_NOTA,ID_ISO, TARIF, BIAYA, KETERANGAN, ID_NOTA, JML_CONT, HZ, START_STACK, END_STACK) VALUES ('','$id_iso','$tarif','$biaya_haulage2','HAULAGE DELIVERY','$no_nota','$jml_cont','$hz',TO_DATE('".$tgl_start."','dd/mm/yyyy'),TO_DATE('".$start."','dd/mm/yyyy'))";
                 //   echo $query2;die;
                    $db->query($query3);
                    } //echo 'die';die;
                  }
                  
                $lolo     = " select b.id_group_tarif, count(b.id_group_tarif) jml_cont, e.id_iso, d.tarif, e.size_, e.type_, e.status, c.tgl_placement+4 TGL_START_NOTA
                                        from container_delivery a, group_tarif b, placement c, master_tarif d, iso_code e, master_container f
                                        where d.ID_ISO = e.ID_ISO 
                                        and a.no_container = f.no_container
                                        and a.status = e.status
                                        and f.size_ = e.size_
                                        and F.TYPE_ = e.type_
                                        AND a.no_container = c.no_container 
                                        and b.id_group_tarif = d.id_group_tarif 
                                        and b.kategori_tarif = 'LOLO' 
                                        and a.no_request = '$no_req' 
                                        and c.tgl_placement+4 between b.start_period and b.end_period 
                                        group by b.id_group_tarif, c.tgl_placement, e.id_iso, b.id_group_tarif, d.tarif,e.size_, e.type_, e.status";
                $lolo_     = $db->query($lolo);
                $row         = $lolo_->getAll();
                
                  $biaya_lolo_ = 0;
                  if(count($row) > 0){
                  for($i=0;$i<count($row);$i++){
                    $jml_cont      = $row[$i]['JML_CONT'];
                    $tarif         = $row[$i]['TARIF'];
                    $id_iso        = $row[$i]['ID_ISO'];
                    $tgl_start     = $row[$i]['TGL_START_NOTA'];
                    $biaya_lolo    = $jml_cont*$tarif;
                    $biaya_lolo_   = $biaya_lolo_ + $biaya_lolo;

                    $query2 = "INSERT INTO nota_delivery_d (ID_DETAIL_NOTA,ID_ISO, TARIF, BIAYA, KETERANGAN, ID_NOTA, JML_CONT, HZ, START_STACK, END_STACK) VALUES ('','$id_iso','$tarif','$biaya_lolo','LIFT ON','$no_nota','$jml_cont','$hz',TO_DATE('".$tgl_start."','dd/mm/yyyy'),TO_DATE('".$tgl_req_out."','dd/mm/yyyy'))";
                    $db->query($query2);
                    } //echo 'die';die;
                  }   
                  
                $formulir       = 1000;
                $tagihan_       = $tagihan + $biaya_haulage_ + $biaya_haulage2_ + $biaya_lolo_ + $formulir;
                $ppn            = $tagihan_ * 0.1;
                $total_tagihan  = $tagihan_+ $ppn + $formulir;
                
                $nota = "UPDATE NOTA_DELIVERY SET TAGIHAN = '$tagihan_', PPN = '$ppn', TOTAL_TAGIHAN = '$total_tagihan', STATUS = 'NEW', FORMULIR = '1000', LAIN2 = 0 WHERE NO_NOTA = '$no_nota'";
            //    echo $nota;die;
                $db->query($nota);
		header('Location:'.HOME.APPID.'/print_nota?no_nota='.$no_nota);
				
	}
}
else
{
	$no_nota		= $_GET["no_nota"];
	$nota_			= $nota + 1;
	$query_update           = "UPDATE NOTA_DELIVERY SET CETAK_NOTA = '$nota_' WHERE NO_NOTA = '$no_nota'";
	//echo $query_update;die;
	if($db->query($query_update))
	{
		//echo HOME;
		header('Location:'.HOME.APPID.'/print_nota?no_nota='.$no_nota);		
	}	
}






?>