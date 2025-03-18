<?php

$db 			= getDB("storage");

$no_nota		= $_GET["no_nota"]; 

$query_update	= "UPDATE NOTA_STUFFING SET LUNAS = 'YES', TANGGAL_LUNAS = SYSDATE WHERE NO_NOTA = '$no_nota'";

        if($db->query($query_update))
	{
            header('Location:'.HOME.APPID);		
	} else {
		die();
            header('Location:'.HOME.APPID);		
        }
?>