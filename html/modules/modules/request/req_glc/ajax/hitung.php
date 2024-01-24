<?php

$db 		   = getDB();
$mulai 	       = $_POST["MULAI"];
$mulai_jam     = $_POST["MULAI_JAM"];
$mulai_menit   = $_POST["MULAI_MENIT"]; 
$selesai  	   = $_POST["SELESAI"];
$selesai_jam   = $_POST["SELESAI_JAM"];
$selesai_menit = $_POST["SELESAI_MENIT"];
 
 //=== hitung shift ===//
 $est_mulai = $mulai." ".$mulai_jam.":".$mulai_menit.":00";
 $est_selesai = $selesai." ".$selesai_jam.":".$selesai_menit.":00";
 
 $querynya   = "SELECT CEIL(((TO_DATE('$est_selesai','dd/mm/yyyy HH24:MI:SS') - TO_DATE('$est_mulai','dd/mm/yyyy HH24:MI:SS'))*(24))/8) AS JML_SHIFT FROM DUAL";
 $result1    = $db->query($querynya);
 $row4	     = $result1->fetchRow();			
 $jml_shift  = $row4['JML_SHIFT'];
 
 echo $jml_shift;
 
 
?>