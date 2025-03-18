<?php

$db 			= getDB("storage");

$no_nota		= $_GET["no_nota"]; 
$keg			= $_GET["keg"]; 

		if ($keg == 'RECEIVING'){
			$query_update	= " UPDATE NOTA_RECEIVING SET LUNAS = 'YES' , TANGGAL_LUNAS = SYSDATE WHERE NO_NOTA = '$no_nota' ";
			$query_update2	= "UPDATE NOTA_ALL_H SET LUNAS = 'YES' , TANGGAL_LUNAS = SYSDATE WHERE NO_NOTA = '$no_nota'; COMMIT;";
		} else if ($keg == 'STUFFING'){
			$query_update	= " UPDATE NOTA_STUFFING SET LUNAS = 'YES' , TANGGAL_LUNAS = SYSDATE WHERE NO_NOTA = '$no_nota' ";
			$query_update2	= "UPDATE NOTA_ALL_H SET LUNAS = 'YES' , TANGGAL_LUNAS = SYSDATE WHERE NO_NOTA = '$no_nota' ";
		} else if ($keg == 'STRIPPING'){
			$query_update	= " UPDATE NOTA_STRIPPING SET LUNAS = 'YES' , TANGGAL_LUNAS = SYSDATE WHERE NO_NOTA = '$no_nota' ";
			$query_update2	= "UPDATE NOTA_ALL_H SET LUNAS = 'YES' , TANGGAL_LUNAS = SYSDATE WHERE NO_NOTA = '$no_nota' ";
		} else if ($keg == 'DELIVERY'){
			$query_update	= " UPDATE NOTA_DELIVERY SET LUNAS = 'YES' , TANGGAL_LUNAS = SYSDATE WHERE NO_NOTA = '$no_nota' ";
			$query_update2	= "UPDATE NOTA_ALL_H SET LUNAS = 'YES' , TANGGAL_LUNAS = SYSDATE WHERE NO_NOTA = '$no_nota' ";
		}
		
        if(($db->query($query_update)) AND ($db->query($query_update2)))
		{
             header('Location:'.HOME.APPID.'');		
		} else {
			 header('Location:'.HOME.APPID.'');		
        }
?>