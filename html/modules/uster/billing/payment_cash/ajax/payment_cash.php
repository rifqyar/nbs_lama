<?php

$db 		= getDB("storage");
$db4	= getDB("uster_ict");

$password	= md5($_POST["PASSWORD"]);
$no_req		= $_POST["NO_REQ"];  
$id_user	= $_SESSION["LOGGED_STORAGE"];
$keg		= $_POST["KEG"];  
$id_bayar	= $_POST["ID_BAYAR"]; 
$bank_id	= $_POST["BANK_ID"];  
$no_nota	= $_POST["NO_NOTA"];
$trxnum 	= $_POST["NO_FAKTUR"];
//echo $keg;die;


	
$qcek = "SELECT count(*) jum FROM ITPK_NOTA_HEADER WHERE TRX_NUMBER ='$trxnum'";
$rcek = $db->query($qcek)->fetchRow();
if ($rcek[JUM] > 0) {
	$reciptk = "SELECT bank_account_name receipt_account,
						       bank_id,
						       CASE WHEN no = 3 THEN 'CASH' ELSE 'BANK' END receipt_method
						  FROM kapal_cabang.mst_bank_simkeu where kd_cabang = 5 and bank_id = '$bank_id'";
	$rwcitpk = $db->query($reciptk)->fetchRow();
	$receipt = $rwcitpk['RECEIPT_ACCOUNT'];
	$kode = $rwcitpk['RECEIPT_METHOD'];
}
else {	
	$reciptk = "select replace(RECEIPT_ACCOUNT, ' ', '_') ID, RECEIPT_ACCOUNT, BANK_ID 
	                            from apps.xpi2_ar_receipt_method_v@simkeu_link 
	                            WHERE bank_id = '$bank_id'";
	$rwcitpk = $db->query($reciptk)->fetchRow();
	$receipt = $rwcitpk['RECEIPT_ACCOUNT'];

	if ($id_bayar == 'CASH'){
		$kode = 'PTK CASH';
	} else if ($id_bayar == 'AUTODB'){
		$kode = 'PTK BANK';
	} else if ($id_bayar == 'BANK'){
		$kode = 'PTK BANK';
	} else if ($id_bayar == 'DEPOSIT'){
		$kode = 'PTK BANK';
	}
}
/* $query_cek		= "SELECT PASSWORD FROM master_user WHERE ID = '$id_user'";
$result_cek		= $db->query($query_cek);
$row_cek		= $result_cek->fetchRow();
$pass			= $row_cek["PASSWORD"]; */

/* if($pass == $password)
{ */
//cek ipctpk staging
$query_cek 	= "SELECT COUNT(*) FLAG FROM ITPK_NOTA_HEADER WHERE NO_REQUEST = '$no_req'";
$rquery		= $db->query($query_cek)->fetchRow();
if ($rquery[FLAG] > 0) {
	$query_update2 = "UPDATE ITPK_NOTA_HEADER SET STATUS = '2', TGL_PELUNASAN = SYSDATE, RECEIPT_METHOD = '$kode', RECEIPT_ACCOUNT='$receipt',BANK_ID = '$bank_id', USERID_LUNAS = '$id_bayar' WHERE TRX_NUMBER = '$trxnum'";
	if ($keg == 'RECEIVING'){
		$query_update	= "UPDATE NOTA_RECEIVING SET LUNAS = 'YES', TANGGAL_LUNAS = SYSDATE, RECEIPT_METHOD = '$kode', RECEIPT_ACCOUNT = '$receipt', BAYAR='$id_bayar' WHERE NO_REQUEST= '$no_req' ";
	} else if ($keg == 'STUFFING'){
		$query_update	= " UPDATE NOTA_STUFFING SET LUNAS = 'YES' , TANGGAL_LUNAS = SYSDATE , RECEIPT_METHOD = '$kode', RECEIPT_ACCOUNT = '$receipt', BAYAR='$id_bayar' WHERE NO_REQUEST= '$no_req' ";
		/*auto payment ict*/
	} else if ($keg == 'STRIPPING'){
		//echo "UPDATE NOTA_STRIPPING SET LUNAS = 'YES' , TANGGAL_LUNAS = SYSDATE, RECEIPT_METHOD = '$kode', RECEIPT_ACCOUNT = '$receipt', BAYAR='$id_bayar' WHERE NO_REQUEST= '$no_req'";
		$query_update	= " UPDATE NOTA_STRIPPING SET LUNAS = 'YES' , TANGGAL_LUNAS = SYSDATE, RECEIPT_METHOD = '$kode', RECEIPT_ACCOUNT = '$receipt', BAYAR='$id_bayar' WHERE NO_REQUEST= '$no_req' ";
							
	} else if ($keg == 'DELIVERY'){
		$query_update	= " UPDATE NOTA_DELIVERY SET LUNAS = 'YES' , TANGGAL_LUNAS = SYSDATE, RECEIPT_METHOD = '$kode', RECEIPT_ACCOUNT = '$receipt', BAYAR='$id_bayar' WHERE NO_REQUEST= '$no_req' ";
		
	} else if ($keg == 'RELOKASI'){
		$query_update	= " UPDATE NOTA_RELOKASI SET LUNAS = 'YES' , TANGGAL_LUNAS = SYSDATE, RECEIPT_METHOD = '$kode', RECEIPT_ACCOUNT = '$receipt', BAYAR='$id_bayar' WHERE NO_REQUEST= '$no_req' ";
		
	} else if ($keg == 'BATALMUAT'){
		$query_update	= " UPDATE NOTA_BATAL_MUAT SET LUNAS = 'YES' , TGL_LUNAS = SYSDATE, RECEIPT_METHOD = '$kode', RECEIPT_ACCOUNT = '$receipt', BAYAR='$id_bayar' WHERE NO_REQUEST= '$no_req' ";
		
	} else if ($keg == 'RELOKASI_MTY'){
		$query_update	= " UPDATE NOTA_RELOKASI_MTY SET LUNAS = 'YES' , TANGGAL_LUNAS = SYSDATE, RECEIPT_METHOD = '$kode', RECEIPT_ACCOUNT = '$receipt', BAYAR='$id_bayar' WHERE NO_REQUEST= '$no_req' ";
		
	} else if ($keg == 'PNKN_DELIVERY'){
		$query_update	= " UPDATE NOTA_PNKN_DEL SET LUNAS = 'YES' , TANGGAL_LUNAS = SYSDATE, RECEIPT_METHOD = '$kode', RECEIPT_ACCOUNT = '$receipt', BAYAR='$id_bayar' WHERE NO_REQUEST= '$no_req' ";
	
	} else if ($keg == 'PNKN_STUFFING'){
		$query_update	= " UPDATE NOTA_PNKN_STUF SET LUNAS = 'YES' , TANGGAL_LUNAS = SYSDATE, RECEIPT_METHOD = '$kode', RECEIPT_ACCOUNT = '$receipt', BAYAR='$id_bayar' WHERE NO_REQUEST= '$no_req' ";
	
	}	
}
else {

	if ($keg == 'RECEIVING'){
		$query_update	= "UPDATE NOTA_RECEIVING SET LUNAS = 'YES', TANGGAL_LUNAS = SYSDATE, RECEIPT_METHOD = '$kode', RECEIPT_ACCOUNT = '$receipt', BAYAR='$id_bayar' WHERE NO_REQUEST= '$no_req' ";
		$query_update2	= "UPDATE NOTA_ALL_H SET LUNAS = 'YES', TANGGAL_LUNAS = SYSDATE, RECEIPT_METHOD = '$kode', RECEIPT_ACCOUNT = '$receipt', BAYAR='$id_bayar' WHERE NO_REQUEST= '$no_req' ";
	} else if ($keg == 'STUFFING'){
		$query_update	= " UPDATE NOTA_STUFFING SET LUNAS = 'YES' , TANGGAL_LUNAS = SYSDATE , RECEIPT_METHOD = '$kode', RECEIPT_ACCOUNT = '$receipt', BAYAR='$id_bayar' WHERE NO_REQUEST= '$no_req' ";
		$query_update2	= "UPDATE NOTA_ALL_H SET LUNAS = 'YES', TANGGAL_LUNAS = SYSDATE, RECEIPT_METHOD = '$kode', RECEIPT_ACCOUNT = '$receipt', BAYAR='$id_bayar' WHERE NO_REQUEST= '$no_req' ";
		/*auto payment ict*/
	} else if ($keg == 'STRIPPING'){
		//echo "UPDATE NOTA_STRIPPING SET LUNAS = 'YES' , TANGGAL_LUNAS = SYSDATE, RECEIPT_METHOD = '$kode', RECEIPT_ACCOUNT = '$receipt', BAYAR='$id_bayar' WHERE NO_REQUEST= '$no_req'";
		$query_update	= " UPDATE NOTA_STRIPPING SET LUNAS = 'YES' , TANGGAL_LUNAS = SYSDATE, RECEIPT_METHOD = '$kode', RECEIPT_ACCOUNT = '$receipt', BAYAR='$id_bayar' WHERE NO_REQUEST= '$no_req' ";
		$query_update2	= "UPDATE NOTA_ALL_H SET LUNAS = 'YES', TANGGAL_LUNAS = SYSDATE, RECEIPT_METHOD = '$kode', RECEIPT_ACCOUNT = '$receipt', BAYAR='$id_bayar' WHERE NO_REQUEST= '$no_req' ";					
	} else if ($keg == 'DELIVERY'){
		$query_update	= " UPDATE NOTA_DELIVERY SET LUNAS = 'YES' , TANGGAL_LUNAS = SYSDATE, RECEIPT_METHOD = '$kode', RECEIPT_ACCOUNT = '$receipt', BAYAR='$id_bayar' WHERE NO_REQUEST= '$no_req' ";
		$query_update2	= "UPDATE NOTA_ALL_H SET LUNAS = 'YES', TANGGAL_LUNAS = SYSDATE, RECEIPT_METHOD = '$kode', RECEIPT_ACCOUNT = '$receipt', BAYAR='$id_bayar' WHERE NO_REQUEST= '$no_req' ";
	} else if ($keg == 'RELOKASI'){
		$query_update	= " UPDATE NOTA_RELOKASI SET LUNAS = 'YES' , TANGGAL_LUNAS = SYSDATE, RECEIPT_METHOD = '$kode', RECEIPT_ACCOUNT = '$receipt', BAYAR='$id_bayar' WHERE NO_REQUEST= '$no_req' ";
		$query_update2	= "UPDATE NOTA_ALL_H SET LUNAS = 'YES', TANGGAL_LUNAS = SYSDATE, RECEIPT_METHOD = '$kode', RECEIPT_ACCOUNT = '$receipt', BAYAR='$id_bayar' WHERE NO_REQUEST= '$no_req' ";
	} else if ($keg == 'BATALMUAT'){
		$query_update	= " UPDATE NOTA_BATAL_MUAT SET LUNAS = 'YES' , TGL_LUNAS = SYSDATE, RECEIPT_METHOD = '$kode', RECEIPT_ACCOUNT = '$receipt', BAYAR='$id_bayar' WHERE NO_REQUEST= '$no_req' ";
		$query_update2	= "UPDATE NOTA_ALL_H SET LUNAS = 'YES', TANGGAL_LUNAS = SYSDATE, RECEIPT_METHOD = '$kode', RECEIPT_ACCOUNT = '$receipt', BAYAR='$id_bayar' WHERE NO_REQUEST= '$no_req' ";
	} else if ($keg == 'RELOKASI_MTY'){
		$query_update	= " UPDATE NOTA_RELOKASI_MTY SET LUNAS = 'YES' , TANGGAL_LUNAS = SYSDATE, RECEIPT_METHOD = '$kode', RECEIPT_ACCOUNT = '$receipt', BAYAR='$id_bayar' WHERE NO_REQUEST= '$no_req' ";
		$query_update2	= "UPDATE NOTA_ALL_H SET LUNAS = 'YES', TANGGAL_LUNAS = SYSDATE, RECEIPT_METHOD = '$kode', RECEIPT_ACCOUNT = '$receipt', BAYAR='$id_bayar' WHERE NO_REQUEST= '$no_req' ";
	} else if ($keg == 'PNKN_DELIVERY'){
		$query_update	= " UPDATE NOTA_PNKN_DEL SET LUNAS = 'YES' , TANGGAL_LUNAS = SYSDATE, RECEIPT_METHOD = '$kode', RECEIPT_ACCOUNT = '$receipt', BAYAR='$id_bayar' WHERE NO_REQUEST= '$no_req' ";
		$query_update2	= "UPDATE NOTA_ALL_H SET LUNAS = 'YES', TANGGAL_LUNAS = SYSDATE, RECEIPT_METHOD = '$kode', RECEIPT_ACCOUNT = '$receipt', BAYAR='$id_bayar' WHERE NO_REQUEST= '$no_req' ";
	} else if ($keg == 'PNKN_STUFFING'){
		$query_update	= " UPDATE NOTA_PNKN_STUF SET LUNAS = 'YES' , TANGGAL_LUNAS = SYSDATE, RECEIPT_METHOD = '$kode', RECEIPT_ACCOUNT = '$receipt', BAYAR='$id_bayar' WHERE NO_REQUEST= '$no_req' ";
		$query_update2	= "UPDATE NOTA_ALL_H SET LUNAS = 'YES', TANGGAL_LUNAS = SYSDATE, RECEIPT_METHOD = '$kode', RECEIPT_ACCOUNT = '$receipt', BAYAR='$id_bayar' WHERE NO_REQUEST= '$no_req' ";
	}
}		
		// $no_nota_ 	= $row['NO_NOTA'];
	// $no_req_ 	= $row['NO_REQUEST'];
	
	// echo "masuk";
			//#1
			/*
			$parameter = array(
						"no_nota"=>"$no_nota_ ",
						"no_req"=>"$no_req_",
						"kode_cabang"=>"05"
						);
			
			$query2 = 'BEGIN petikemas_cabang.sp_crosscek_simkeu_uster(:no_nota,:no_req,:kode_cabang); END;'; 
			$db2->query($query2,$parameter);
			*/
			 $query_cek = "BEGIN sp_crosscek_simkeu_prod(TRIM('$no_nota'),TRIM('$no_req')); END;"; 
			//$query_cek = "BEGIN sp_crosscek_simkeu_prod('$no_nota','$no_req'); END;";
			// $query2 = "BEGIN petikemas_cabang.sp_crosscek_simkeu_uster(TRIM('$no_nota_'),TRIM('$no_req_')); END;"; 
			//echo "BEGIN petikemas_cabang.sp_crosscek_simkeu_uster(TRIM('$no_nota_'),TRIM('$no_req_')); END;";die;
			// echo $query2;
			// $db2->query($query2);
			//#2
			// $query2 = " DECLARE 
							// no_nota VARCHAR2(20); 
							// no_req VARCHAR2(20); 
							// BEGIN 
								// no_nota := '$no_nota_'; 
								// no_req:= '$no_req_'; 
							// sp_crosscek_simkeu_prod(no_nota, no_req); END;";
			
			// $query1 ="exec petikemas_cabang.sp_crosscek_simkeu_uster('03050413000014', 'STR0413000017', '05');";
			// echo $query2;
			
			// echo $i;
			// $i++;
			
			$db->query($query_cek);

		if(($db->query($query_update)) AND ($db->query($query_update2)))
		{
			// $db4->query($query_update);
			// $db4->query($query_update2);
			echo "OK";
		}
/* }
else
{
	echo 'WRONG';
} */
?>