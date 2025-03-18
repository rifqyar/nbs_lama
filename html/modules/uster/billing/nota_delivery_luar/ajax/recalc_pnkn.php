<?php

$db 			= getDB("storage");

//$no_nota		= $_POST["NOTA"]; 
$req		= $_POST["REQ"];
$ceknota = "select no_nota from nota_pnkn_del where no_request = '$req' and status <> 'BATAL'";
$rnota = $db->query($ceknota)->fetchRow();
$no_nota = $rnota['NO_NOTA'];
$qpost = "begin PACK_RECALC_NOTA.recalc_pnkndelivery('$req','$no_nota'); end;";

if($db->query($qpost))
{
    echo 'OK';
    die();
}

?>