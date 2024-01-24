<?php 
$db = getDB("storage");
$no_cont = $_POST["NO_CONT"];
$cek_stuf = "select no_container,TO_CHAR(tgl_realisasi,'DD-MM-YYYY') tgl_bongkar, TO_CHAR(tgl_realisasi+4,'DD-MM-YYYY') TGL_MASAI  
              from container_stuffing where no_container = '$no_cont'
              order by tgl_realisasi desc";
$rd 	= $db->query($cek_stuf);
$rdw 	= $rd->fetchRow($rd);
echo json_encode($rdw);
die();
?>