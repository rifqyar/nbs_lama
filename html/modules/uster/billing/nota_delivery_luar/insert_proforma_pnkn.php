<?php

$db = getDB("storage");
//$db4  = getDB("uster_ict");
$nipp   = $_SESSION["LOGGED_STORAGE"];
$no_req = $_GET["no_req"];
$koreksi = $_GET["koreksi"];

    
    //Insert ke tabel nota

    $query_cek_nota     = "SELECT NO_NOTA,STATUS FROM NOTA_PNKN_DEL WHERE NO_REQUEST = '$no_req'";
    $result_cek_nota    = $db->query($query_cek_nota);
    $nota               = $result_cek_nota->fetchRow();
    $no_nota_cek        = $nota['NO_NOTA'];
    $nota_status        = $nota['STATUS'];
    
if (  ($no_nota_cek != NULL && $nota_status == 'BATAL') || ($no_nota_cek == NULL && $nota_status == NULL)){ 
    
        $query_cek  = "SELECT NVL(LPAD(MAX(TO_NUMBER(SUBSTR(NO_NOTA,10,15))+1),6,0), '000001') JUM_,
                              TO_CHAR(SYSDATE, 'MM') AS MONTH, 
                              TO_CHAR(SYSDATE, 'YY') AS YEAR 
                        FROM NOTA_PNKN_DEL 
                       WHERE NOTA_PNKN_DEL.TGL_NOTA BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE)";
                       
                       //select LPAD(MAX(TO_NUMBER(SUBSTR(NO_REQUEST,8,13)))+1,6,0) FROM REQUEST_RECEIVING 
                       //WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE)
                   
    $result_cek = $db->query($query_cek);
    $jum_       = $result_cek->fetchRow();
    $jum        = $jum_["JUM_"];
    $month      = $jum_["MONTH"];
    $year       = $jum_["YEAR"];
    
    $no_nota    = "1105".$month.$year.$jum;
    
    
    // Cek NO NOTA MTI
    // firman 20 agustus 2020
            $query_mti = "SELECT NVL(LPAD(MAX(TO_NUMBER(SUBSTR(NO_NOTA_MTI,10,15))+1),6,0),'000001') JUM_,
                           TO_CHAR(SYSDATE, 'YYYY') AS YEAR
                           FROM MTI_COUNTER_NOTA WHERE TAHUN =  TO_CHAR(SYSDATE,'YYYY')";

    $result_mti = $db->query($query_mti);
    $jum_mti    = $result_mti->fetchRow();
    $jum_nota_mti   = $jum_mti['JUM_'];
    $year_mti       = $jum_mti['YEAR'];

    $no_nota_mti    = "17.".$year_mti.".".$jum_nota_mti;

        //select master pbm
        $query_master   = "SELECT b.KD_PBM, 
                                  b.nm_pbm, 
                                  b.almt_pbm, 
                                  b.no_npwp_pbm,  
                                  TO_CHAR(a.TGL_REQUEST,'dd/Mon/yyyy') TGL_REQUEST, 
                                  COUNT(c.NO_CONTAINER) JUMLAH,
                                  a.DELIVERY_KE
                            FROM REQUEST_DELIVERY a, v_mst_pbm b , CONTAINER_DELIVERY c
                            WHERE a.KD_AGEN = b.kd_pbm 
                                AND a.NO_REQUEST = c.NO_REQUEST
                                AND a.no_request = '$no_req'
                            GROUP BY  b.KD_PBM, b.nm_pbm, b.almt_pbm, b.no_npwp_pbm, TO_CHAR(a.TGL_REQUEST,'dd/Mon/yyyy'), a.DELIVERY_KE";
    //echo $query_master;die;
        $result_cek = $db->query($query_master);
        $master     = $result_cek->fetchRow();
        $kd_pbm     = $master["KD_PBM"];
        $nm_pbm     = $master["NM_PBM"];
        $almt_pbm   = $master["ALMT_PBM"];
        $npwp       = $master["NO_NPWP_PBM"];
        $jumlah_cont    = $master["JUMLAH"];
        $tgl_re     = $master["TGL_REQUEST"];
        $delivery_ke = $master["DELIVERY_KE"];

    // debug($delivery_ke);die;
      //tarif pass
    $pass         = "SELECT TO_CHAR(($jumlah_cont * a.TARIF), '999,999,999,999') PASS, ($jumlah_cont * a.TARIF) TARIF
                      FROM master_tarif a, group_tarif b
                     WHERE a.ID_GROUP_TARIF = b.ID_GROUP_TARIF
                           AND TO_DATE ('$tgl_re', 'dd/mm/yyyy') BETWEEN b.START_PERIOD
                                                                        AND b.END_PERIOD
                           AND a.ID_ISO = 'PASS'";
                   
    $result__     = $db->query($pass);
    $row_pass     = $result__->fetchRow();
       $tarif_pass   = $row_pass['TARIF'];

        
        $total_     = "SELECT SUM(BIAYA) TOTAL, SUM(PPN) PPN, (SUM(BIAYA) + SUM(PPN)) TOTAL_TAGIHAN FROM temp_detail_nota_i WHERE no_request = '$no_req' AND KETERANGAN NOT IN ('MATERAI')"; /**Fauzan modif 27 AUG 2020 "AND KETERANGAN NOT IN ('MATERAI')"*/
        //echo $total_;die;
        $result     = $db->query($total_);
        $total2         = $result->fetchRow();
        
        $query_adm      = "SELECT TO_CHAR(a.TARIF , '999,999,999,999') AS ADM, a.TARIF FROM MASTER_TARIF a, GROUP_TARIF b WHERE a.ID_GROUP_TARIF = b.ID_GROUP_TARIF AND b.KATEGORI_TARIF = 'ADMIN_NOTA'";
        $result_adm     = $db->query($query_adm);
        $row_adm        = $result_adm->fetchRow();
        $adm            = $row_adm['TARIF'];
        
        /*Fauzan add materai 27 Agustus 2020*/
        $query_materai      = "SELECT SUM(BIAYA) BEA_MATERAI FROM temp_detail_nota_i WHERE no_request = '$no_req' AND KETERANGAN = 'MATERAI'";
        $result_materai     = $db->query($query_materai);
        $row_materai        = $result_materai->fetchRow();
        $materai            = $row_materai['BEA_MATERAI'];
        /*end Fauzan add materai 27 Agustus 2020*/
    
        $total = $total2['TOTAL'];
        $total_ppn = $total2['PPN'];
        $total_tagihan = $total2['TOTAL_TAGIHAN'];
       // $adm   = 10000;
        $total_ = $total;
        $ppn   = $total_ppn;
        $tagihan = $total_tagihan + $materai;// + $tarif_pass;      /**Fauzan modif 26 AUG 2020 "+ $materai"*/

        /*$itpk_flag = "select CASE WHEN to_date(tgl_request,'dd/mm/rrrr') >=kapal_prod.all_general_pkg.get_cutoff_date('USTER','05')
                                    THEN 'Y' ELSE 'N' END FLAG
                    FROM request_delivery
                    where no_request = '$no_req'";
        $rtpk_flag = $db->query($itpk_flag)->fetchRow();*/
        
        
       if($koreksi <> 'Y'){
        
             $status_nota = 'NEW';
             $nota_lama = '';
        }
        else {
            $status_nota = 'KOREKSI';
            $faktur     = "SELECT NO_NOTA, NO_FAKTUR, KD_EMKL FROM NOTA_PNKN_DEL WHERE NO_REQUEST = '$no_req' AND NO_NOTA =(SELECT MAX(NO_NOTA) FROM NOTA_PNKN_DEL WHERE NO_REQUEST = '$no_req')";
            $result     = $db->query($faktur);
            $faktur_     = $result->fetchRow();
            $nota_lama  = $faktur_['NO_FAKTUR'];
            $update = "UPDATE NOTA_PNKN_DEL SET STATUS = 'BATAL' WHERE NO_NOTA = '$nota_lama'";
            $db->query($update);
        }

        //UPDATE COUNTER MTI DAN PENAMBAHAN FIELD NO_NOTA_MTI DI HEADER DAN DETAIL
        // firman 20 agustus 2020

        $query_mti = "INSERT INTO MTI_COUNTER_NOTA
                    (
                     NO_NOTA_MTI,
                     TAHUN,
                     NO_REQUEST
                    ) 
                    VALUES 
                    (
                    '$no_nota_mti',
                    TO_CHAR(SYSDATE,'YYYY'),
                    '$no_req'
                    )";

        $db->query($query_mti);
                
        $query_insert_nota  = "INSERT INTO NOTA_PNKN_DEL(NO_NOTA, 
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
                                        NOTA_LAMA,
                                        NO_NOTA_MTI) 
                                VALUES('$no_nota',
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
                                        SYSDATE,'$nota_lama',
                                        '$no_nota_mti')";   
    //echo $query_insert_nota;die;
    if($db->query($query_insert_nota))//(TRUE)
    {           
                
                $query_detail   = "SELECT ID_ISO,
                                          TARIF,
                                          BIAYA,
                                          KETERANGAN,
                                          JML_CONT,
                                          START_STACK,
                                          END_STACK,
                                          HZ,
                                          JML_HARI,
                                          COA,
                                          PPN
                                    FROM temp_detail_nota_i 
                                    WHERE no_request = '$no_req' ";
              //  echo $query_detail;die;
                $res        = $db->query($query_detail);
                $row        = $res->getAll();
                
                
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
                    $ppn_d    = $item['PPN'];
            
            
                    
                    $query_insert   = "INSERT INTO NOTA_PNKN_DEL_D
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
                           LINE_NUMBER, PPN,
                           NO_NOTA_MTI
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
                            '$coa',
                            '$i','$ppn_d',
                            '$no_nota_mti')";
                            
                    $db->query($query_insert);
                    
                    $i++;
                    
                } 
                
                    $update_nota = "UPDATE NOTA_PNKN_DEL SET CETAK_NOTA = '1' WHERE NO_NOTA = '$no_nota'";
                    $update_req = "UPDATE request_delivery SET NOTA_PNKN = 'Y' WHERE no_request = '$no_req'";
                    $db->query($update_nota);

                     $db->query($update_req);
                    $delete_temp = "DELETE from temp_detail_nota_i WHERE no_request = '$no_req'";
                    $db->query($delete_temp);
                    header('Location:'.HOME.APPID.'.print/print_proforma_pnkn?no_nota='.$no_nota."&no_req=".$no_req."&first=1");    
            
    }

}else {
        header('Location:'.HOME.APPID.'.print/print_proforma_pnkn?no_nota='.$no_nota."&no_req=".$no_req);   
    }   
    




?>