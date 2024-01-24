<?php

$db		= getDB();
$no_req 	= $_GET["no_req"];
$jenis_nota_bm 	= $_GET["jenis_nota_bm"];
$id_user	= $_SESSION["PENGGUNA_ID"];

$sql_xpi="begin pack_nota_batmuat.proc_header_nota_batmuat('$no_req'); end;";
//print_r($sql_xpi);die;
$db->query($sql_xpi);


header('Location:'.HOME.APPID.'.print/print_nota_lunas_batal?no_req='.$no_req);

?>