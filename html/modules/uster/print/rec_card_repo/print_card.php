<?php

$db			= getDB("storage");

$no_request	= $_GET["no_req"];

$query_upd	= "UPDATE REQUEST_RECEIVING SET CETAK_KARTU = CETAK_KARTU + 1 WHERE NO_REQUEST = '$no_request'";

if($db->query($query_upd))
{
		echo "HALAMAN CETAK KARTU";
}


?>