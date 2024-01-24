<?php

$db			= getDB();
$no_req 	= $_GET["no_req"];
$id_user	= $_SESSION["NAMA_PENGGUNA"];

$sql_xpi="begin pack_nota_transhipment.proc_header_nota_transhipment('$id_user','$no_req'); end;";
$db->query($sql_xpi);

 header('Location:'.HOME.APPID.'.print/print_nota_lunas?pl='.$no_req)



?>