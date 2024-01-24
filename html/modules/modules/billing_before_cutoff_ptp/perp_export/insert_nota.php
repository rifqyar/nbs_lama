<?php

$db			= getDB();
$no_req 	= $_GET["no_req"];
$id_user	= $_SESSION["NAMA_PENGGUNA"];

/*$db->query("
				INSERT INTO NOTA_STACKEXT_H (ID_NOTA,
											 ID_REQUEST,
											 NO_FAKTUR,
											 JUMLAH,
											 PPN,
											 TOTAL,
											 STATUS,
											 INVOICE_DATE,
											 ADM_NOTA,
											 USER_ID,
											 CUSTOMER,
											 ALAMAT,
											 NPWP,
											 TGL_CETAK,
											 PAYMENT_VIA,
											 TGL_PAYMENT,
											 USER_ID_PAYMENT,
											 DISCOUNT,
											 COA,
											 MATERAI,
											 VAL)
				   (SELECT 1,
						   ID_REQ,
						   '',
						   '',
						   '',
						   '',
						   'S',
						   SYSDATE,
						   '',
						   '$id_user',
						   SHIPPING_LINE,
						   ALAMAT,
						   NPWP,
						   SYSDATE,
						   '',
						   '',
						   '',
						   '',
						   '',
						   '',
						   'IDR'
					  FROM REQ_STACKEXT_H
					 WHERE ID_REQ = '$no_req')");
					 
$query_getnota  = "SELECT NVL(MAX(ID_NOTA),1) ID_NOTA FROM NOTA_STACKEXT_H WHERE ID_REQUEST = '$no_req'";
$result_getnota = $db->query($query_getnota);
$hasil_getnota  = $result_getnota->fetchRow();
$no_nota        = $hasil_getnota['ID_NOTA'];

$db->query("INSERT INTO NOTA_STACKEXT_D (ID_NOTA,
										  TGL_START_STACK,
										  TGL_END_STACK,
										  ID_CONT,
										  HZ,
										  JUMLAH_CONT,
										  JUMLAH_HARI,
										  KETERANGAN,
										  TARIF,
										  SUB_TOTAL,
										  ID_REQ,
										  URUT) 
										  (SELECT '$no_nota', TGL_START_STACK,
										  TGL_END_STACK,
										  ID_CONT,
										  HZ,
										  JUMLAH_CONT,
										  JUMLAH_HARI,
										  KETERANGAN,
										  TARIF,
										  SUB_TOTAL,
										  ID_REQ,
										  URUT
										FROM NOTA_STACKEXT_D_TMP
										WHERE ID_REQ = '$no_req'
										)");
										
$query_gettotal  = "SELECT SUM(SUB_TOTAL) SUB_TOTAL, (0.1*SUM(SUB_TOTAL)) PPN, (SUM(SUB_TOTAL)+(0.1*SUM(SUB_TOTAL))) TOTAL_TAGIHAN FROM NOTA_STACKEXT_D WHERE ID_REQ = '$no_req'";
$result_gettotal = $db->query($query_gettotal);
$hasil_gettotal  = $result_gettotal->fetchRow();
$total           = $hasil_gettotal['SUB_TOTAL'];
$ppn             = $hasil_gettotal['PPN'];
$tot_tagihan     = $hasil_gettotal['TOTAL_TAGIHAN'];*/

//$db->query("UPDATE NOTA_STACKEXT_H SET JUMLAH = '$total', PPN ='$ppn', TOTAL = '$tot_tagihan' WHERE ID_REQUEST = '$no_req'");

$sql_xpi="begin pack_nota_export_extension.proc_header_export_extension('$no_req','$id_user'); end;";
$db->query($sql_xpi);

$query="INSERT INTO LOG_NOTA (ID_NOTA,NO_REQUEST,KODE_MODUL,KETERANGAN,ID_USER, TANGGAL) VALUES ('$no_nota','$no_req','X','SIMPAN','$id_user',SYSDATE)";
$db->query($query);

header('Location:'.HOME.APPID.'.print/print_nota_lunas?no_req='.$no_req)


?>