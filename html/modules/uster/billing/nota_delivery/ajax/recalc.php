<?php

$db 			= getDB("storage");

//$no_nota		= $_POST["NOTA"]; 
$req		= $_POST["REQ"];
$no_nota		= $_POST["NOTA"];
//echo $no_nota; die();
$qpost = "begin PACK_RECALC_NOTA.recalc_deliverytpk('$req','$no_nota'); end;";

if($db->query($qpost))
{
    echo 'OK';
    die();
}

?>