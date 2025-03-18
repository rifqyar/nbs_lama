<?php
//utk menon-aktifkan template default
outputRaw();
$ves = strtoupper($_GET["vess"]);
$vin = strtoupper($_GET["vin"]);
$tipe = strtoupper($_GET["tipe"]);
$no_cont = strtoupper($_GET["term"]);
$db = getDB();

if($tipe=="IMPORT")	$ei='I';
else	$ei='E';

$query = "SELECT A.NO_CONTAINER, 
                (A.SIZE_CONT||'-'||A.TYPE_CONT||'-'||A.STATUS) AS JENIS, 
                 A.HEIGHT, 
                 A.HZ, 
                 B.KODE_BARANG AS KD_BARANG
          FROM M_CYC_CONTAINER@DBINT_LINK A, MASTER_BARANG B
          WHERE UPPER(TRIM(A.NO_CONTAINER)) LIKE '%$no_cont%'
                AND TRIM(A.VESSEL)=TRIM('$ves')
                AND TRIM(A.VOYAGE_IN)=TRIM('$vin')
                --AND UPPER(TRIM(A.CONT_LOCATION))='YARD'
                AND TRIM(A.ACTIVE)='Y'
                AND TRIM(A.E_I)='$ei'
                AND TRIM(A.SIZE_CONT) = TRIM(B.UKURAN)
                AND TRIM(A.TYPE_CONT) = TRIM(B.TYPE)
                AND TRIM(CASE WHEN A.STATUS = 'FULL' THEN 'FCL' 
                 WHEN A.STATUS = 'EMPTY' THEN 'MTY'
                 ELSE 'NULL' END) = TRIM(B.STATUS)
                AND TRIM(CASE WHEN A.HEIGHT = '8.6' THEN 'BIASA' 
                 WHEN A.HEIGHT = '9.6' THEN 'BIASA'
                 ELSE 'OOG' END) = TRIM(B.HEIGHT_CONT)";
//print_r($query);
$result	= $db->query($query);
$row = $result->getAll();	

echo json_encode($row);
?>