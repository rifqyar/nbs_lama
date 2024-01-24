<?php
	
	$db 	= getDB("storage");
	
	// echo "masuk transfer";die;
	$tgl_awal	= $_POST["tgl_awal"]; 
	$tgl_akhir	= $_POST["tgl_akhir"]; 
	$no_nota	= $_POST["no_nota"]; 
	
	// echo $tgl_awal."====".$tgl_akhir."====".$no_nota;die;
	
	if($no_nota != '' or  $no_nota != NULL)
	{
		$type_transfer='SINGLE';
	}
	else
	{
		$type_transfer='TANGGAL';
	}
	
	//echo $type_transfer;die;
	
	// $db->startTransaction();
	$param_payment= array(
				     "TGL_AWAL"=>"$tgl_awal",
					 "TGL_AKHIR"=>"$tgl_akhir",
					 "NOTA_BERHASIL"=>"",
					 "NOTA_GAGAL"=>"",
					 "NOTA_SINGLE"=>"$no_nota",
					 "TYPE_TRANSFER"=>"$type_transfer"
					);
	
	  // print_r($param_payment);die;
	
	$query="declare begin USTER.uster_transfer_ipctpk(:TGL_AWAL,:TGL_AKHIR,:NOTA_BERHASIL,:NOTA_GAGAL,:NOTA_SINGLE,:TYPE_TRANSFER); end;";
	
	
	
	$db->query($query,$param_payment);
	// if ($db->endTransaction()) {
	
		
		echo 'Nota Berhasil Transfer = '.$param_payment["NOTA_BERHASIL"].'  ||  Nota Gagal Transfer = '.$param_payment["NOTA_GAGAL"];
	
		
	// }
	
	
	
		
			// $db			= getDB("ora");
			// $conn = oci_connect('xpi2','welcome1$','10.10.12.217:1521/dev') or die;
			
			// $sql_xpi = 'BEGIN XPI2.XPI2_AR_API_RECEIPT_PKG.generate_receipt_simop(:in_receipt_number,:in_receipt_status,:in_kode_cabang,:out_status,:out_message); END;'; 
					
					// $stmt = oci_parse($conn,$sql_xpi);
					// oci_bind_by_name($stmt,':in_receipt_number',$param_xpi['in_receipt_number'],355);
					// oci_bind_by_name($stmt,':in_receipt_status',$param_xpi['in_receipt_status'],355);
					// oci_bind_by_name($stmt,':in_kode_cabang',$param_xpi['in_kode_cabang'],355);
					// oci_bind_by_name($stmt,'out_status',$param_xpi["out_status"],100,SQLT_CHR);
					// oci_bind_by_name($stmt,'out_message',$param_xpi["out_message"],355,SQLT_CHR);
					
					
					
					
			
			// $db2		= getDB("storage");
			// $db_simkeu  = getDB("simkeu_prod");
			
			// $query_list 		= "SELECT trim(NO_NOTA||'-1') PARAM, NO_NOTA FROM USTER.TRANSFER_SIMKEU_ GROUP BY NO_NOTA";
			// $result_list		= $db2->query($query_list);
			// $row_list			= $result_list->getAll(); 
			
			
			// $sukses_ekse = 0;
			// $gagal_ekse = 0;
			
		// //	print_r ($row_list);
			// $conn = oci_connect('xpi2','welcome1$','10.10.12.217:1521/dev') or die;
			// foreach($row_list as $item){
					// $x= str_replace(' ','',$item['PARAM']);
					// $param_xpi = array(
								 // "in_receipt_number"=>"$x",
								// "in_receipt_status"=>"Y",
								// "in_kode_cabang"=>"PTK", 
								// "out_status"=>"",
								// "out_message"=>""
								// );
					
					// //print_r($param_xpi);
					// $sql_xpi = 'BEGIN XPI2.XPI2_AR_API_RECEIPT_PKG.generate_receipt_simop(:in_receipt_number,:in_receipt_status,:in_kode_cabang,:out_status,:out_message); END;'; 
					
					// $stmt = oci_parse($conn,$sql_xpi);
					// oci_bind_by_name($stmt,':in_receipt_number',$param_xpi['in_receipt_number'],355);
					// oci_bind_by_name($stmt,':in_receipt_status',$param_xpi['in_receipt_status'],355);
					// oci_bind_by_name($stmt,':in_kode_cabang',$param_xpi['in_kode_cabang'],355);
					// oci_bind_by_name($stmt,'out_status',$param_xpi["out_status"],100,SQLT_CHR);
					// oci_bind_by_name($stmt,'out_message',$param_xpi["out_message"],355,SQLT_CHR);
					
					
								
								
					// //$db_simkeu->query($sql_xpi, $param_xpi);
					// if(oci_execute($stmt)){
					  // print_r($param_xpi);
					  // //print_r($msg_out);
					// }
					// //oci_free_statement($stmt);
					// //oci_close($conn);
					
					// $out_status1 = $param_xpi['out_status'];
					// $out_message1 = $param_xpi['out_message'];
					
		// //			echo $out_status1;
					// //echo '<br/>';
		// //			echo $out_message; die;
					// if ($out_status1=='S')
					// {
				
							// $a = "PTK";
							// $param_xpi1= array(
											// "in_trx_number"=>$item['NO_NOTA'],
											// "in_inv_status"=>"Y",
											// "in_kode_cabang"=>"$a",
											// "out_status"=>"",
											// "out_message"=>""
											// ); 
							// //print_r($param_xpi1);			
								// $sql_xpi1	 = "BEGIN XPI2.XPI2_AR_API_PKG.generate_invoice_simop(:in_trx_number,:in_inv_status,:in_kode_cabang,:out_status,:out_message); END;";
							
								// $stmt1 = oci_parse($conn,$sql_xpi1);
								// oci_bind_by_name($stmt1,':in_trx_number',$param_xpi1['in_trx_number'],355);
								// oci_bind_by_name($stmt1,':in_inv_status',$param_xpi1['in_inv_status'],355);
								// oci_bind_by_name($stmt1,':in_kode_cabang',$param_xpi1['in_kode_cabang'],355);
								// oci_bind_by_name($stmt1,'out_status',$param_xpi1["out_status"],100,SQLT_CHR);
								// oci_bind_by_name($stmt1,'out_message',$param_xpi1["out_message"],355,SQLT_CHR);
								
								// //$db_simkeu->query($sql_xpi1,$param_xpi1);
								// if(oci_execute($stmt1)){
								  // print_r($param_xpi1);
								  // //print_r($msg_out);
								// }
								// //oci_free_statement($stmt);
								// //oci_close($conn);
								
								// $out_status_ 	= $param_xpi1['out_status'];
								// $out_message_ 	= $param_xpi1['out_message'];
								
							
						// if (($out_status_ == 'S') AND ($out_status1 == 'S')) 
						// {
							// $sql_update = "UPDATE USTER.NOTA_ALL_H set INVOICE ='".$out_status_."', SIMKEU_PROSES_RECEIPT='".$out_message1."', SIMKEU_PROSES_INVOICE='".$out_message_."', TGL_PROSES = to_date(sysdate,'DD-MM-RRRR'), PELUNASAN = '".$out_status1."', transfer='Y', tgl_transfer = to_date(sysdate,'DD-MM-RRRR') where NO_NOTA = '".$item['NO_NOTA']."'";
							// $sql_update1 = "UPDATE USTER.nota_receiving set transfer='Y', tgl_transfer = to_date(sysdate,'DD-MM-RRRR') where NO_NOTA = '".$item['NO_NOTA']."'";
							// $sql_update2 = "UPDATE USTER.nota_stripping set transfer='Y', tgl_transfer = to_date(sysdate,'DD-MM-RRRR') where NO_NOTA = '".$item['NO_NOTA']."'";
							// $sql_update3 = "UPDATE USTER.nota_stuffing set transfer='Y', tgl_transfer = to_date(sysdate,'DD-MM-RRRR') where NO_NOTA = '".$item['NO_NOTA']."'";
							// $sql_update4 = "UPDATE USTER.nota_delivery set transfer='Y', tgl_transfer = to_date(sysdate,'DD-MM-RRRR') where NO_NOTA = '".$item['NO_NOTA']."'";
							// $sql_update5 = "UPDATE USTER.nota_relokasi set transfer='Y', tgl_transfer = to_date(sysdate,'DD-MM-RRRR') where NO_NOTA = '".$item['NO_NOTA']."'";
							// $sql_update6 = "UPDATE USTER.nota_relokasi_mty set transfer='Y', tgl_transfer = to_date(sysdate,'DD-MM-RRRR') where NO_NOTA = '".$item['NO_NOTA']."'";
							// $sql_update7 = "UPDATE USTER.nota_pnkn_del set transfer='Y', tgl_transfer = to_date(sysdate,'DD-MM-RRRR') where NO_NOTA = '".$item['NO_NOTA']."'";
							// $sql_update8 = "UPDATE USTER.nota_pnkn_stuf set transfer='Y', tgl_transfer = to_date(sysdate,'DD-MM-RRRR') where NO_NOTA = '".$item['NO_NOTA']."'";
							// $sql_update9 = "UPDATE USTER.nota_batal_muat set transfer='Y', tgl_transfer = to_date(sysdate,'DD-MM-RRRR') where NO_NOTA = '".$item['NO_NOTA']."'";
							// $db->query($sql_update);
							// $db->query($sql_update1);
							// $db->query($sql_update2);
							// $db->query($sql_update3);
							// $db->query($sql_update4);
							// $db->query($sql_update5);
							// $db->query($sql_update6);
							// $db->query($sql_update7);
							// $db->query($sql_update8);
							// $db->query($sql_update9);
							// //echo "sukses";
							
							// $sukses_ekse = $sukses_ekse + 1;
							
						// } else {
							// $sql_update = "UPDATE USTER.NOTA_ALL_H set INVOICE ='".$out_status_."', SIMKEU_PROSES_RECEIPT='".$out_message1."', SIMKEU_PROSES_INVOICE='".$out_message_."', TGL_PROSES = to_date(sysdate,'DD-MM-RRRR'), PELUNASAN = '".$out_status1."', TRANSFER = 'N' where NO_NOTA = '".$item['NO_NOTA']."'";
							// $db->query($sql_update);
							// //echo "gagal";
							// $gagal_ekse = $gagal_ekse + 1;
						// }
							
						// //}
					// }
					
					
						
				
			// }
			
			//echo 'Nota Berhasil Transfer = '.$sukses_ekse.'  ||  Nota Gagal Transfer = '.$gagal_ekse;
		
		
	
	
?>
