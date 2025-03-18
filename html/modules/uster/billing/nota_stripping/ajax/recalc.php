<?php

$db 			= getDB("storage");

$no_nota		= $_POST["NOTA"]; 
$req		= $_POST["REQ"];

$qpost = "begin PACK_RECALC_NOTA.recalc_stripping('$req','$no_nota'); end;";

if($db->query($qpost))
{
    echo 'OK';
    die();
}

?>