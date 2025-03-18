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

$query = "SELECT a.NO_CONTAINER, (a.SIZE_CONT||'-'||a.TYPE_CONT||'-'||a.STATUS) AS JENIS, a.HEIGHT, a.HZ, B.KODE_BARANG AS KD_BARANG
            FROM M_CYC_CONTAINER@dbint_link a left join master_barang b 
            on TRIM(a.SIZE_CONT)=TRIM(b.UKURAN) AND a.TYPE_CONT=B.TYPE AND CASE WHEN (a.STATUS='FULL')  THEN 'FCL' else 'MTY' END=b.STATUS 
            and case when a.HEIGHT<>'OOG' then 'BIASA' else 'OOG' end=B.HEIGHT_CONT
            WHERE a.no_container like '%$no_cont%'
                AND trim(a.VESSEL)=trim('$ves')
                AND a.VOYAGE_IN=trim('$vin')
                AND UPPER(a.CONT_LOCATION) = UPPER('YARD')
            AND 
               a.ACTIVE='Y'
              AND trim(a.E_I)='$ei'";
//print_r($query);DIE;
$result	= $db->query($query);
$row = $result->getAll();	

echo json_encode($row);
?>