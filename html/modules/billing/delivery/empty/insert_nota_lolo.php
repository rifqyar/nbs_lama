<?php

$db			= getDB();
$no_req 	= $_GET["no_req"];
$id_user	= $_SESSION["PENGGUNA_ID"];

$sql_xpi="begin pack_nota_delivery_lolo.proc_header_nota_delivery('$no_req'); end;";
//print_r($sql_xpi);die;
$db->query($sql_xpi);


header('Location:'.HOME.APPID.'.print/print_nota_lunas_lolo?no_req='.$no_req);

?>