<?php

$db = getDB();
$no_req = $_POST["no_req"];
$id_user = $_SESSION["ID_USER"]; 

$query_update = "update DISKON_NOTA_DEL_H SET FLAG_REKAP = 'Y',TGL_REKAP = sysdate, USER_REKAP = '$id_user' where NO_REQ_DEV = '$no_req'";

if($db->query($query_update))
{
	echo "sukses";		
}
else 
{
    echo "gagal";
}

?>