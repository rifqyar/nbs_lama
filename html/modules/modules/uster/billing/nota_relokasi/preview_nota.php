<?php

$tl      =  xliteTemplate('preview_nota.htm');

$db	 = getDB("storage");

$nota	 = $_GET["n"];
$id_user = $_SESSION["LOGGED_STORAGE"]; 
$no_req	 = $_GET["no_req"];
$no_nota = $_GET["no_nota"];

	//=======================================preview EMKL=======================================
	$query_emkl	= " SELECT  c.NM_PBM AS EMKL,
                                    c.NO_NPWP_PBM AS NPWP,
                                    c.ALMT_PBM AS ALAMAT
                            FROM    request_stripping b,
                                    v_mst_pbm c
                            WHERE   b.NO_REQUEST = '$no_req'
                            AND     b.KD_PENUMPUKAN_OLEH = c.KD_PBM
                          ";
				   
	$result		= $db->query($query_emkl);
	$row_nota	= $result->fetchRow();
	
        //====================================end EMKL===============================================
        
        //====================================preview tarif penumpukan===============================
                $tagihan = 0;
                $query          = " select c.SIZE_, c.TYPE_,  b.HZ, TO_CHAR(d.TGL_AKHIR, 'dd/mm/yyyy') TGL_AKHIR, TO_CHAR(b.TGL_BONGKAR, 'dd/mm/yyyy') TGL_BONGKAR, COUNT(c.SIZE_) JML_CONT
                                    FROM CONTAINER_STRIPPING b, MASTER_CONTAINER c, REQUEST_STRIPPING d
                                    WHERE b.AKTIF = 'Y' AND b.NO_REQUEST = '$no_req' AND d.NO_REQUEST = b.NO_REQUEST AND c.NO_CONTAINER = b.NO_CONTAINER
                                    group BY c.SIZE_, c.TYPE_, d.TGL_AKHIR, b.TGL_BONGKAR, b.HZ";
            //    echo $query;die;
                $result_cek	= $db->query($query);
		$rows		= $result_cek->getAll();

                foreach ($rows as $rows_){
//                    $status     = $rows[$i]['STATUS'];
//                    $type       = $rows[$i]['TYPE_'];
//                    $size       = $rows[$i]['SIZE_'];
//                    $jml_cont   = $rows[$i]['JML_CONT'];
//                    $hz         = $rows[$i]['HZ'];
//                    $tgl_placement   = $rows[$i]['TGL_PLACEMENT'];
//                    $tgl_req_out     = $rows[$i]['TGL_REQ_OUT'];
                    
                    $status     = 'FULL';
                    $type       = $rows_['TYPE_'];
                    $size       = $rows_['SIZE_'];
                    $hz         = $rows_['HZ'];
                    $jml_cont   = $rows_['JML_CONT'];
                    $tgl_placement   = $rows_['TGL_AKHIR'];
                    $tgl_req_out     = $rows_['TGL_BONGKAR'];
                    
                    //echo $tgl_placement;die;
                    
                    //$update     = "UPDATE NOTA_DELIVERY SET START_STACK = TO_DATE('".$tgl_placement."','dd/mon/yyyy')+4, END_STACK = TO_DATE('".$tgl_req_out."','dd/mm/yyyy') WHERE NO_NOTA = '$no_nota'";
                    //echo $update;die;
                    $db->query($update);
                    
                    $tarif1     = " SELECT a.TARIF, b.ID_ISO 
                                        FROM MASTER_TARIF a, ISO_CODE b, GROUP_TARIF c 
                                        where b.ID_ISO = a.ID_ISO 
                                        AND a.ID_GROUP_TARIF = c.ID_GROUP_TARIF
                                        AND c.ID_GROUP_TARIF = '3'
                                        AND b.SIZE_ = '$size' 
                                        AND b.TYPE_ = '$type'
                                        AND b.STATUS = '$status'
                                        AND TO_DATE('".$tgl_bongkar."','dd/mm/yyyy') BETWEEN c.START_PERIOD AND c.END_PERIOD";
                   // echo $tarif1; die;
                     $tarif_	= $db->query($tarif1);
                     $rows	= $tarif_->fetchRow();
                     $tarif     = $rows['TARIF'];
                     $id_iso    = $rows['ID_ISO'];
                     
                     
                     
                     
                     $jml_hari1  = " SELECT TO_DATE('".$tgl_akhir."', 'dd/mm/yyyy') - (TO_DATE('".$tgl_bongkar."','dd/mm/yyyy'))+1 jumlah_hari FROM dual";
                   //  echo $jml_hari1;
                     $jml_hari_	= $db->query($jml_hari1);
                     $rows_	= $jml_hari_->fetchRow();
                     $jml_hari  = $rows_['JUMLAH_HARI'];
                   // echo 'jml_hari'.$jml_hari;
                     
                     //hitung formulasi penumpukan
                   //  $masa11 = 0;
                     if ($jml_hari <= 5){
                         $masa11  = $jml_hari;
                         $masa12  = 0;
                         $masa2   = 0;
                     } else if (($jml_hari > 5) && ($jml_hari >= 10)) {
                         $masa11 = 5;
                         $masa12 = $jml_hari - 5;
                         $masa2  = 0;
                     } else {
                         $masa11 = 5;
                         $masa12 = 5;
                         $masa2  = $jml_hari - 10;
                     }
                     
                     if ($hz == 'Y'){
                        $tarifmasa11 = $tarif*2;
                        $biayamasa11 = $tarifmasa11*1*$jml_cont;
                        $tarifmasa12 = $tarif*2;
                        $biayamasa12 = $tarifmasa12*$masa12*$jml_cont;
                        $tarifmasa2  = $tarif*4;
                        $biayamasa2  = $tarifmasa2*$masa2*$jml_cont;
                     } else {
                        $biayamasa11 = $tarif*1*$jml_cont;
                        $tarifmasa12 = $tarif;
                        $biayamasa12 = $tarif*$masa12*$jml_cont;
                        $tarifmasa2  = $tarif*2;
                        $biayamasa2  = $tarifmasa2*$masa2*$jml_cont;
                     }
                     
                     
                     $tagihan_ = $biayamasa11 + $biayamasa12 + $biayamasa2;
                     $tagihan  = $tagihan + $tagihan_;
//                     echo 'masa12 '.$masa12;
//                     echo 'masa2 '.$masa2;
//                     echo 'tarif masa12 '.$tarifmasa12;
//                     echo 'tarif masa2 '.$tarifmasa2;
//                     echo 'biaya masa12 '.$biayamasa12;
//                     echo 'biaya masa2 '.$biayamasa2;die;
                     
                     if (($masa11 <> 0) && ($masa12 <> 0) && ($masa2 <> 0)){
                         $endstack1_    = "SELECT TO_DATE('".$tgl_bongkar."','dd/mm/yyyy')+$masa11-1 ENDSTACK1 FROM DUAL";
                         $end_stack_     = $db->query($endstack1_);
                         $row_         = $end_stack_->fetchRow();
                         $end_stack1   = $row_['ENDSTACK1'];
                         
                         $startstack2  = "SELECT TO_DATE('".$tgl_bongkar."','dd/mm/yyyy')+$masa11 STARTSTACK2 FROM DUAL";
                         $startstack_  = $db->query($startstack2);
                         $row_         = $startstack_->fetchRow();
                         $start_stack2 = $row_['STARTSTACK2'];
                         
                         $endstack2    = "SELECT TO_DATE('".$start_stack2."','dd/mm/yyyy')+$masa12-1 ENDSTACK2 FROM DUAL";
                         $end_stack_   = $db->query($endstack2);
                         $row_         = $end_stack_->fetchRow();
                         $end_stack2   = $row_['ENDSTACK2'];
                         
                         $startstack3  = "SELECT TO_DATE('".$end_stack2."','dd/mm/yyyy')+1 STARTSTACK3 FROM DUAL";
                         $startstack_  = $db->query($startstack3);
                         $row_         = $startstack_->fetchRow();
                         $start_stack3 = $row_['STARTSTACK3'];
                         
                         $endstack3    = "SELECT TO_DATE('".$start_stack3."','dd/mm/yyyy')+$masa2-1 ENDSTACK3 FROM DUAL";
                         $endstack_     = $db->query($endstack3);
                         $row_         = $endstack_->fetchRow();
                         $end_stack3   = $row_['ENDSTACK3'];
                         
//                         $query3 = "INSERT INTO nota_delivery_d (ID_ISO, TARIF, BIAYA, KETERANGAN, ID_NOTA, JML_CONT, HZ, START_STACK, END_STACK, JML_HARI) VALUES ('$id_iso','$tarifmasa12','$biayamasa12','PENUMPUKAN MASA I.2','$no_nota','$jml_cont','$hz',TO_DATE('".$tgl_placement."','dd/mm/yyyy')+5,TO_DATE('".$end_stack1."','dd/mm/yyyy'), (TO_DATE('".$end_stack1."','dd/mm/yyyy')-(TO_DATE('".$tgl_placement."','dd/mm/yyyy')+4)))";
//                         $db->query($query3);
//                         $query4 = "INSERT INTO nota_delivery_d (ID_ISO, TARIF, BIAYA, KETERANGAN, ID_NOTA, JML_CONT, HZ, START_STACK, END_STACK, JML_HARI) VALUES ('$id_iso','$tarifmasa2','$biayamasa2','PENUMPUKAN MASA 2','$no_nota','$jml_cont','$hz',TO_DATE('".$start_stack2."','dd/mm/yyyy'),TO_DATE('".$end_stack2."','dd/mm/yyyy'), TO_DATE('".$end_stack2."','dd/mm/yyyy')-TO_DATE('".$start_stack2."','dd/mm/yyyy')+1)";
//                         $db->query($query4);
//                        // echo $query3;
//                         //die;
//                       //  echo $query4;
//                       //  die; 
                     } else if (($masa11 <> 0) && ($masa12 == 0) && ($masa2 == 0)){
                         $endstack1_    = "SELECT TO_DATE('".$tgl_bongkar."','dd/mm/yyyy')+$masa11-1 ENDSTACK1 FROM DUAL";
                         $end_stack_     = $db->query($endstack1_);
                         $row_         = $end_stack_->fetchRow();
                         $end_stack1   = $row_['ENDSTACK1'];
                         
                     } else if (($masa11 <> 0) && ($masa12 <> 0) && ($masa2 == 0)){
                         $endstack1_    = "SELECT TO_DATE('".$tgl_bongkar."','dd/mm/yyyy')+$masa11-1 ENDSTACK1 FROM DUAL";
                         $end_stack_     = $db->query($endstack1_);
                         $row_         = $end_stack_->fetchRow();
                         $end_stack1   = $row_['ENDSTACK1'];
                         
                         $startstack2  = "SELECT TO_DATE('".$tgl_bongkar."','dd/mm/yyyy')+$masa11 STARTSTACK2 FROM DUAL";
                         $startstack_  = $db->query($startstack2);
                         $row_         = $startstack_->fetchRow();
                         $start_stack2 = $row_['STARTSTACK2'];
                         
                         $endstack2    = "SELECT TO_DATE('".$start_stack2."','dd/mm/yyyy')+$masa12-1 ENDSTACK2 FROM DUAL";
                         $end_stack_   = $db->query($endstack2);
                         $row_         = $end_stack_->fetchRow();
                         $end_stack2   = $row_['ENDSTACK2'];
                         
                     }                    
                }
                
        //=======================================end tarif penumpukan================================
        
        //====================================preview tarif stripping================================
                
                     $total_cont       = " SELECT COUNT(NO_CONTAINER) jml
                                            FROM CONTAINER_STRIPPING 
                                            where NO_REQUEST= '$no_req'"; 
                   // echo $tarif1; die;
                     $tarif_            = $db->query($total_cont);
                     $rows_             = $tarif_->fetchRow();
                     $jml_cont_strip    = $rows_['jml'];
                     
                
                     $tarif_strip       = " SELECT TARIF 
                                            FROM MASTER_TARIF 
                                            where ID_ISO = 'STRIP'"; 
                   // echo $tarif1; die;
                     $tarif_            = $db->query($tarif_strip);
                     $rows_             = $tarif_->fetchRow();
                     $tarif_strip       = $rows_['TARIF'];
                     
                     $total_strip       = $jml_cont_strip*$tarif_strip;
                
        //=======================================end tarif stripping=================================
        
        //===================================preview total tagihan===================================
                $formulir       = 1000;
                $tagihan_       = $tagihan + $total_strip;
                $ppn            = $tagihan_ * 0.1;
                $total_tagihan  = $tagihan_+ $ppn + $formulir;
        
        //======================================end preview total tagihan==========================================
       
        $tl->assign("tarif_strip",$tarif_strip);
	$tl->assign("total_strip",$total_strip);
        $tl->assign("formulir",$formulir);
	$tl->assign("tagihan",$tagihan_);        
                
        $tl->assign("tarif_strip",$tarif_strip);
	$tl->assign("total_strip",$total_strip);
        $tl->assign("formulir",$formulir);
	$tl->assign("tagihan",$tagihan_);
        $tl->assign("ppn",$ppn);
	$tl->assign("total_tagihan",$total_tagihan);
        $tl->assign("no_req",$no_req);
	$tl->assign("row_nota",$row_nota);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	
	$tl->renderToScreen();

?>