<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB('pnoadm');
	
$query 			= "SELECT ETG_PRE_IOMODE,
       ETG_PRE_CONTNO,
       etg_pre_vessel,
       etg_pre_voyage,
       pay_flg,
       TO_CHAR (TO_DATE (pay_thru_dt, 'yyyymmddhh24miss'), 'dd-mm-yyyy')
          paid_thru
  FROM etg_preadvice
 WHERE pay_thru_dt != '235959' AND ETG_PRE_CONTNO LIKE '%$no_cont%'";
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>