<?php
	$db=getDB();
	$query = "SELECT * FROM TB_USER";
	$res=$db->query($query);
	while($row=$res->fetchRow()){
		//echo($row['NAME']);
		//echo($row['PASSWORD'].$row['ID']);
		$md5_string = md5($row['PASSWORD'].$row['ID']);
		$query2 = "UPDATE TB_USER SET PASSWORD_ENC='$md5_string' WHERE ID='".$row['ID']."'";
		echo($query2);
		echo("<br>");
		$db->query($query2);
	}
	die();
?>