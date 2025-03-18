<?php
$db  = getDB();
$db2 =  getDB('billing_obx');
	
$cust_no    = $_GET['cust_no'];
$start_date = $_GET['start_date'];
$end_date   = $_GET['end_date'];
$user       = $_SESSION["NAMA_PENGGUNA"];

$query_cust = "SELECT ACCOUNT_KEU,NAMA_PELANGGAN,ALAMAT,NPWP
  FROM MASTER_PELANGGAN
 WHERE ACCOUNT_KEU = '$cust_no'";
 
$result_cust = $db2->query($query_cust);
$row_cust    = $result_cust->fetchRow();
$cust_name   = $row_cust['NAMA_PELANGGAN'];
$cust_addr   = $row_cust['ALAMAT'];
$cust_tax_no = $row_cust['NPWP'];

$sql_xpi = "BEGIN SP_SIMPAN_PRANOTA_BHD('$cust_no','$cust_name','$cust_tax_no','$cust_addr','$start_date','$end_date','$user'); END;";
$db->query($sql_xpi);
 
header('Location: '.HOME.'request.behandle_tpft/');
?>