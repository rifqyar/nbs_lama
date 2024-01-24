<?php
$tl =  xliteTemplate('preview_nota.htm');
$db  =  getDB();
$db2 =  getDB('billing_obx');

$cust_no    = $_GET['cust_no'];
$start_date = $_GET['start_date'];
$end_date   = $_GET['end_date'];
$user       = $_SESSION['NAMA_PENGGUNA'];

//hitung penumpukan container
$sql_xpi = "BEGIN HITUNG_BHD_TPFT('$start_date','$end_date','$user'); END;";
$db->query($sql_xpi);

$query_cust = "SELECT ACCOUNT_KEU,NAMA_PELANGGAN,ALAMAT,NPWP
  FROM MASTER_PELANGGAN
 WHERE ACCOUNT_KEU = '$cust_no'";
 
$result_cust = $db2->query($query_cust);
$row_cust    = $result_cust->fetchRow();

$query_detail  = "SELECT ROWNUM NO,
       NO_CONTAINER,
       SIZE_CONTAINER,
       TYPE_CONTAINER,
       STS_CONTAINER,
       HZ,
       VESSEL,
       VOYAGE,
       TO_CHAR (ATA, 'DD/MM/RRRR') START_DATE,
       TO_CHAR (TGL_GATE_OUT, 'DD/MM/RRRR') END_DATE,
       (TGL_GATE_OUT - ATA) + 1 DURASI,
       TO_CHAR(TAGIHAN,'999,999,999,999') TAGIHAN
  FROM BIL_BHD_CONT_H
 WHERE TO_DATE (TGL_GATE_OUT, 'DD/MM/RRRR') BETWEEN TO_DATE('$start_date','DD/MM/RRRR') AND TO_DATE('$end_date','DD/MM/RRRR')
   AND ID_PRANOTA IS NULL";
$result_detail = $db->query($query_detail);
$row_detail = $result_detail->getAll(); 

$query_sub  = "SELECT TO_CHAR (SUM (TAGIHAN), '999,999,999,999') TAGIHAN,
       TO_CHAR (SUM (PPN), '999,999,999,999') PPN,
       TO_CHAR (SUM (TOTAL_TAGIHAN), '999,999,999,999') TOTAL_TAGIHAN
  FROM BIL_BHD_CONT_H
 WHERE TO_DATE (TGL_GATE_OUT, 'DD/MM/RRRR') BETWEEN TO_DATE ('$start_date','DD/MM/RRRR') AND TO_DATE ('$end_date','DD/MM/RRRR')
   AND ID_PRANOTA IS NULL";
$result_sub = $db->query($query_sub);
$row_sub = $result_sub->fetchRow(); 

$tl->assign("row_cust",$row_cust);
$tl->assign("start_date",$start_date);
$tl->assign("end_date",$end_date);
$tl->assign("row_detail",$row_detail);
$tl->assign("row_sub",$row_sub);
$tl->assign("HOME",HOME);
$tl->assign("APPID",APPID);
$tl->renderToScreen();
?>