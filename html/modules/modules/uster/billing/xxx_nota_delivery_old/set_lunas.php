<?php

$db 			= getDB("storage");

$no_nota		= $_GET["no_nota"]; 

$query_update	= "UPDATE NOTA_DELIVERY SET LUNAS = 'YES' WHERE NO_NOTA = '$no_nota'";

        if($db->query($query_update))
	{
            header('Location:'.HOME.APPID);		
	} else {
            header('Location:'.HOME.APPID);		
        }
?>