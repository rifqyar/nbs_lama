<?php

$db 		= getDB("storage");

$password	= md5($_POST["PASSWORD"]);
$no_req		= $_POST["NO_REQ"];  
$id_user	= $_SESSION["LOGGED_STORAGE"];
$keg		= $_POST["KEG"]; 
$id_bayar	= $_POST["ID_BAYAR"]; 
$receipt	= $_POST["RECEIPT"];  

if ($id_bayar == 'CASH'){
		$kode = 'PTK CASH'
	} else if ($id_bayar == 'AUTODB'){
		$kode = 'PTK BANK'
	} else if ($id_bayar == 'BANK'){
		$kode = 'PTK BANK'
	} else if ($id_bayar == 'DEPOSIT'){
		$kode = 'PTK BANK'
	}

$query_cek		= "SELECT PASSWORD FROM master_user WHERE ID = '$id_user'";
$result_cek		= $db->query($query_cek);
$row_cek		= $result_cek->fetchRow();
$pass			= $row_cek["PASSWORD"];

if($pass == $password)
{
	    if ($keg == 'RECEIVING'){
			$query_update	= " UPDATE NOTA_RECEIVING SET LUNAS = 'YES', TANGGAL_LUNAS = SYSDATE, RECEIPT_METHOD = '$kode', RECEIPT_ACCOUNT = '$receipt', BAYAR='$id_bayar' WHERE NO_REQUEST= '$no_req' ";
		} else if ($keg == 'STUFFING'){
			$query_update	= " UPDATE NOTA_STUFFING SET LUNAS = 'YES' , TANGGAL_LUNAS = SYSDATE , RECEIPT_METHOD = '$kode', RECEIPT_ACCOUNT = '$receipt', BAYAR='$id_bayar' WHERE NO_REQUEST= '$no_req' ";
		} else if ($keg == 'STRIPPING'){
			$query_update	= " UPDATE NOTA_STRIPPING SET LUNAS = 'YES' , TANGGAL_LUNAS = SYSDATE, RECEIPT_METHOD = '$kode', RECEIPT_ACCOUNT = '$receipt', BAYAR='$id_bayar' WHERE NO_REQUEST= '$no_req' ";
		} else if ($keg == 'DELIVERY'){
			$query_update	= " UPDATE NOTA_DELIVERY SET LUNAS = 'YES' , TANGGAL_LUNAS = SYSDATE, RECEIPT_METHOD = '$kode', RECEIPT_ACCOUNT = '$receipt', BAYAR='$id_bayar' WHERE NO_REQUEST= '$no_req' ";
		}

		if($db->query($query_update))
		{
			echo "OK";
		}
}
else
{
	echo 'WRONG';
}
?>