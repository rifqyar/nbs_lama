<?php

$db			= getDB();
$no_req 	= $_POST["ID_REQ"];
$no_nota 	= $_POST["ID_NOTA"];

$id_user	= $_SESSION["PENGGUNA_ID"];

$sql_xpi="begin pack_nota_batalmuat_recalc.recalc_pen_alihkapal('$no_nota','$no_req','$id_user'); end;";
//print_r($sql_xpi);die;
$db->query($sql_xpi);

echo "sukses";
?>