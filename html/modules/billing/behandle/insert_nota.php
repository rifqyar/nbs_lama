<?php

$db			= getDB();
$no_req 	= $_GET["no_req"];
$id_user	= $_SESSION["NAMA_PENGGUNA"];

$sql_xpi="begin pack_nota_behandle.proc_header_nota_behandle('$id_user','$no_req'); end;";
//print_r($sql_xpi);die;
$db->query($sql_xpi);

$q_cek_nota = "select ID_PROFORMA from  NOTA_BEHANDLE_H WHERE ID_REQ = '$no_req' and status!='X'";
$r_cek_nota = $db->query($q_cek_nota)->fetchRow();

$no_nota = $r_cek_nota['ID_PROFORMA'];
header('Location:'.HOME.'print/print_nota_behandle?pl='.$no_nota)



?>