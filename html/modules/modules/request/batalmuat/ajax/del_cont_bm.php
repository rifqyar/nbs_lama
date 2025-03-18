<?php

$db 		= getDB();

$id_detail	= $_POST["ID_DETAIL"];

$query_del	= "DELETE FROM TB_BATALMUAT_D WHERE ID_DETAIL = '$id_detail'";

if($db->query($query_del))
{
		echo "OK";
}

?>