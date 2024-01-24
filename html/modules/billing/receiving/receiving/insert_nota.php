<?php

$db			= getDB();
$no_req 	= $_GET["no_req"];
$id_user	= $_SESSION["NAMA_PENGGUNA"];


        //select master pbm
        $query_master	= "SELECT a.NAMA AS EMKL, a.ALAMAT, a.NPWP FROM tb_req_receiving_h b, master_pbm a
							WHERE a.KODE_PBM = b.KODE_PBM
							AND b.ID_REQ = '$no_req'";
		//echo $query_master;die;
        $result_cek	= $db->query($query_master);
		$master		= $result_cek->fetchRow();
		$emkl		= $master["EMKL"];
		$alamat		= $master["ALAMAT"];
        $npwp   	= $master["NPWP"];
	
        
        $total_		= "SELECT SUM(SUB_TOTAL) TOTAL FROM tb_temp_detail_nota WHERE ID_REQ = '$no_req'";
        //echo $total_;die;
        $result		= $db->query($total_);
        $total2     = $result->fetchRow();
		
		$jml_cont	= "SELECT COUNT(NO_CONTAINER) JML FROM tb_req_receiving_d WHERE ID_REQ = '$no_req'";
        //echo $total_;die;
        $result		= $db->query($jml_cont);
        $jml     	= $result->fetchRow();
		$jml_cont   = $jml['JML'];
     
        
        $total = $total2['TOTAL'];
        $adm   = 10000+($jml_cont*10000);
        $total_ = $total+$adm;
        $ppn   = 0.1 * ($total+$adm);
        $tagihan = $total_ + $ppn;
		
		$id_nota = date('Y').'/MNL_PTKMS/'.$no_req;

	$query_insert_nota	= "INSERT INTO tb_nota_receiving_h(ID_NOTA, ID_REQ,
                                        TAGIHAN,
                                        PPN,
                                        TOTAL,
                                        EMKL,
                                        ALAMAT,
                                        NPWP,
                                        STATUS,
										TGL_REQ,
                                        ADM_NOTA,
										ID_USER) 
							VALUES(		'$id_nota',
										'$no_req',
                                        '$total_',
                                        '$ppn',
                                        '$tagihan',
										'$emkl', 
										'$alamat', 
										'$npwp', 
										'SAVED', 
										SYSDATE,
                                        '$adm',
                                        '$id_user')";	
	//echo $query_insert_nota;die;
	if($db->query($query_insert_nota))
	{
                //echo 'dama';die;
				 $query_update	= "UPDATE tb_nota_receiving_h SET STATUS = 'SAVED' WHERE ID_REQ = '$no_req'";
				 $query_update	= "UPDATE tb_req_receiving_h SET STATUS = 'SAVED' WHERE ID_REQ = '$no_req'";
              //  echo $query_detail;die;
				 $db->query($query_update);
				
                $query_detail	= "SELECT * FROM tb_temp_detail_nota WHERE ID_REQ = '$no_req' ";
              //  echo $query_detail;die;
				$res		= $db->query($query_detail);
				$row		= $res->getAll();
                
                foreach ($row as $item){
                    $id_cont 		= $item['ID_CONT'];
                    $tarif  		= $item['TARIF'];
                    $biaya  		= $item['SUB_TOTAL'];
                    $ket    		= $item['KETERANGAN'];
                    $jml_cont  		= $item['JUMLAH_CONT'];
                    $hz     		= $item['HZ'];
					$start_stack  	= $item['TGL_START_STACK'];
                    $end_stack    	= $item['TGL_END_STACK'];
					$jml_hari		= $item['JUMLAH_HARI'];
					
                    $query_insert	= "INSERT INTO tb_nota_receiving_d
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
											 SUB_TOTAL,
											 ID_REQ
                                            ) VALUES 
                                            (
                                            '$id_nota',
                                            '$start_stack',
                                            '$end_stack',
                                            '$id_cont',
                                            '$hz',
                                            '$jml_cont',
                                            '$jml_hari',
											'$ket',
											'$tarif',
											'$biaya',
											'$no_req')";
                    $db->query($query_insert);
                } 
                
                    $delete_temp = "DELETE from tb_temp_detail_nota WHERE ID_REQ = '$no_req'";
                    $db->query($delete_temp);
                    header('Location:'.HOME.APPID.'.print/print_nota_lunas?no_req='.$no_req);	
  
        }


?>