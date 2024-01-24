<?php

$db			= getDB();
$no_req 	= $_GET["no_req"];
$id_user	= $_SESSION["ID_USER"];


        //select master pbm
        $query_temp	= "SELECT ID_CONT,
		                      TGL_MULAI,
							  TGL_SELESAI,
							  HZ,
							  JUMLAH_CONT,
							  JUMLAH_HARI,
							  TARIF,
							  TOTAL,
							  KETERANGAN 
		                    FROM TB_TEMP_NOTA_BM_D
							WHERE ID_BATALMUAT = '$no_req'";
		//echo $query_master;die;
        $result_cek	= $db->query($query_temp);
		$temp		= $result_cek->fetchRow();
		$id_cont	= $temp["ID_CONT"];
		$tglmulai	= $temp["TGL_MULAI"];
        $tglselesai	= $temp["TGL_SELESAI"];
		$hz     	= $temp["HZ"];
		$jml_cont   = $temp["JUMLAH_CONT"];
		$jml_hari   = $temp["JUMLAH_HARI"];
		$tarif      = $temp["TARIF"];
		$total      = $temp["TOTAL"];
		$keterangan = $temp["KETERANGAN"];
		
        
        $total_		= "SELECT SUM(TOTAL) TOTAL FROM TB_TEMP_NOTA_BM_D WHERE ID_BATALMUAT = '$no_req'";
        //echo $total_;die;
        $result		= $db->query($total_);
        $total2     = $result->fetchRow();
		
		$jml_cont	= "SELECT COUNT(NO_CONTAINER) JML FROM TB_BATALMUAT_D WHERE ID_BATALMUAT = '$no_req'";
        //echo $total_;die;
        $result		= $db->query($jml_cont);
        $jml     	= $result->fetchRow();
		$jml_cont   = $jml['JML'];
     
        
        $total = $total2['TOTAL'];
        $adm   = 10000+($jml_cont*10000);
        $ttl   = $total+$adm;
        $ppn   = 0.1 * ($total+$adm);
        $tagihan = $ttl + $ppn;
		
		$id_nota = date('Y').'/MNL_PTKMS/'.$no_req;

	$query_insert_nota	= "INSERT INTO TB_NOTA_BM_H(ID_NOTA, 
										ID_BATALMUAT,
                                        TAGIHAN,
                                        PPN,
                                        TOTAL,
                                        ADM_NOTA,
                                        TGL_NOTA,
                                        ID_USER) 
							VALUES(		'$id_nota',
										'$no_req',
                                        '$ttl',
                                        '$ppn',
                                        '$tagihan',
										'$adm', 
										SYSDATE, 
										'$id_user'
										)";	
	//echo $query_insert_nota;die;
	if($db->query($query_insert_nota))
	{
                //echo 'dama';die;
				 $query_update1	= "UPDATE TB_NOTA_BM_H SET STATUS = 'SAVED' WHERE ID_BATALMUAT = '$no_req'";
				 $query_update2	= "UPDATE TB_BATALMUAT_H SET STATUS = 'SAVED' WHERE ID_BATALMUAT = '$no_req'";
              //  echo $query_detail;die;
				 $db->query($query_update1);
				 $db->query($query_update2);
				
                $query_detail	= "SELECT ID_CONT,
										  TGL_MULAI,
										  TGL_SELESAI,
										  HZ,
										  JUMLAH_CONT,
										  JUMLAH_HARI,
										  TARIF,
										  TOTAL,
										  KETERANGAN
				                  FROM TB_TEMP_NOTA_BM_D 
								  WHERE ID_BATALMUAT = '$no_req'";
              //  echo $query_detail;die;
				$res		= $db->query($query_detail);
				$row		= $res->getAll();
                
                foreach ($row as $item){
                    $id_cont 		= $item['ID_CONT'];
                    $tarif  		= $item['TARIF'];
                    $biaya  		= $item['TOTAL'];
                    $ket    		= $item['KETERANGAN'];
                    $jml_cont  		= $item['JUMLAH_CONT'];
                    $hz     		= $item['HZ'];
					$tglmulai   	= $item['TGL_MULAI'];
                    $tglselesai    	= $item['TGL_SELESAI'];
					$jml_hari		= $item['JUMLAH_HARI'];
					
                    $query_insert6	= "INSERT INTO TB_NOTA_BM_D
                                            (
											 ID_NOTA,
											 TGL_START_STACK,
											 TGL_END_STACK,
											 ID_CONT,
											 HZ,
											 JUMLAH_CONT,
											 JUMLAH_HARI,
											 KETERANGAN,
											 TARIF,
											 TOTAL,
											 ID_BATALMUAT
                                            ) VALUES 
                                            (
                                            '$id_nota',
                                            '$tglmulai',
                                            '$tglselesai',
                                            '$id_cont',
                                            '$hz',
                                            '$jml_cont',
                                            '$jml_hari',
											'$ket',
											'$tarif',
											'$biaya',
											'$no_req')";
                    $db->query($query_insert6);
                } 
                
                    $delete_temp = "DELETE FROM TB_TEMP_NOTA_D WHERE ID_BATALMUAT = '$no_req'";
                    $db->query($delete_temp);
                    header('Location:'.HOME.APPID.'.print/print_nota_lunas?no_req='.$no_req);	
  
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