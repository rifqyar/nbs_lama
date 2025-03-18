<?php

$db			= getDB();
$no_req 	= $_POST["ID_REQ"];
$no_nota 	= $_POST["ID_NOTA"];

$id_user	= $_SESSION["PENGGUNA_ID"];

$sql_xpi="begin pack_nota_hicoscan_recalc.recalc('$id_user','$no_req','$no_nota'); end;";
//print_r($sql_xpi);die;
$db->query($sql_xpi);

echo "sukses";
?>