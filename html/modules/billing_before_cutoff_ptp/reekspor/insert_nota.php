<?php

$db			= getDB();
$no_req 	= $_GET["no_req"];
$id_user	= $_SESSION["NAMA_PENGGUNA"];

//$sql_xpi="begin ISWS_JAMBI.pack_nota_behandle.proc_header_nota_behandle('$id_user','$no_req'); end;";
//$db->query($sql_xpi);
/*

        //select master pbm
        $query_master	= "SELECT a.KODE_PBM AS EMKL, a.ALAMAT, a.NPWP,a.COA,a.TGL_REQUEST FROM req_receiving_h a
							WHERE  a.ID_REQ = '$no_req'";
		//echo $query_master;die;
        $result_cek	= $db->query($query_master);
		$master		= $result_cek->fetchRow();
		$pelanggan		= $master["EMKL"];
		$alamat		= $master["ALAMAT"];
        $npwp   	= $master["NPWP"];
		$coa=$master["COA"];
		$tgl_req=$master["TGL_REQUEST"];
	
        
        $total_		= "SELECT SUM(SUB_TOTAL) TOTAL FROM nota_receiving_d_tmp WHERE ID_REQ = '$no_req'";
        //echo $total_;die;
        $result		= $db->query($total_);
        $total2     = $result->fetchRow();
		
		$jml_cont	= "SELECT COUNT(NO_CONTAINER) JML FROM req_receiving_d WHERE NO_REQ_ANNE = '$no_req'";
        //echo $total_;die;
        $result		= $db->query($jml_cont);
        $jml     	= $result->fetchRow();
		$jml_cont   = $jml['JML'];
     
        
        $total = $total2['TOTAL'];
        $adm   = 10000+($jml_cont*10000);
        $total_ = $total+$adm;
        $ppn   = 0.1 * ($total+$adm);
        $tagihan = $total_ + $ppn;
		
		//$id_nota = date('Y').'/MNL_PTKMS/'.$no_req;

	$query_insert_nota	= "INSERT INTO nota_receiving_h( ID_REQ,
                                        TAGIHAN,
                                        PPN,
                                        TOTAL,
                                        EMKL,
                                        ALAMAT,
                                        NPWP,
                                        STATUS,
										TGL_REQ,
                                        ADM_NOTA,
										COA,
										ID_USER
										) 
							VALUES(		
										'$no_req',
                                        '$total_',
                                        '$ppn',
                                        '$tagihan',
										'$pelanggan', 
										'$alamat', 
										'$npwp', 
										'SAVED', 
										'$tgl_req',
                                        '$adm',
										'$coa',
                                        '$id_user')";	
	//echo $query_insert_nota;die;
	if($db->query($query_insert_nota))
	{
                //echo 'dama';die;
				 $query_update	= "UPDATE tb_nota_receiving_h SET STATUS = 'SAVED' WHERE ID_REQ = '$no_req'";
				 $query_update	= "UPDATE req_receiving_h SET STATUS = 'SAVED' WHERE ID_REQ = '$no_req'";
              //  echo $query_detail;die;
				 $db->query($query_update);
------------------------------------------------------------------------------------------------------				
				$query_detail	= "SELECT ID_NOTA FROM nota_receiving_h WHERE ID_REQ = '$no_req' ";
               // print_r( $query_detail);
				$res		= $db->query($query_detail);
				$row		= $res->fetchRow();
				$id_nota=$row['ID_NOTA'];
				
                $query_detail	= "SELECT * FROM nota_receiving_d_tmp WHERE ID_REQ = '$no_req' ";
             //   print_r( $query_detail);
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
					
                    $query_insert	= "INSERT INTO nota_receiving_d
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
				//	print_r($query_insert);
                } 
                
                    $delete_temp = "DELETE from nota_receiving_d_tmp WHERE ID_REQ = '$no_req'";
                    $db->query($delete_temp);
					
					$insert_hist="INSERT INTO isws_list_cont_hist (NO_UKK,NO_CONTAINER,KEGIATAN,E_I,KODE_STATUS, NM_USER) (SELECT NO_UKK,NO_CONTAINER,'ANNE','I',49,'$id_user' FROM req_receiving_d WHERE NO_REQ_ANNE='$no_req')";
					$db->query($insert_hist);
                    header('Location:'.HOME.APPID.'.print/print_nota_lunas?no_req='.$no_req);	
  
        }
 header('Location:'.HOME.APPID.'.print/print_nota_lunas?no_req='.$no_req)
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
 header('Location:'.HOME.APPID.'.print/print_nota_lunas?no_req='.$no_req)



?>