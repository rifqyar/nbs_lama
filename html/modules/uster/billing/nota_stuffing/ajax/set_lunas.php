<?php

$db 			= getDB("storage");

$no_nota		= $_POST["NO_NOTA"]; 

$query_update	= "UPDATE NOTA_RECEIVING SET LUNAS = 'YES' WHERE NO_NOTA = '$no_nota'";

if($db->query($query_update))
{
	echo "OK";
}

?>