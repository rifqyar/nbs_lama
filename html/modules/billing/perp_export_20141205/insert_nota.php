<?php

$db			= getDB();
$no_req 	= $_GET["no_req"];
$id_user	= $_SESSION["NAMA_PENGGUNA"];

$sql_xpi="begin pack_nota_export_extension.proc_header_export_extension('$no_req','$id_user'); end;";
$db->query($sql_xpi);

$query="INSERT INTO LOG_NOTA (ID_NOTA,NO_REQUEST,KODE_MODUL,KETERANGAN,ID_USER, TANGGAL) VALUES ('$no_nota','$no_req','X','SIMPAN','$id_user',SYSDATE)";
$db->query($query);

header('Location:'.HOME.APPID.'.print/print_nota_lunas?no_req='.$no_req)


?>