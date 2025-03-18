<?php

	$db 		= getDB();

	$query1     = "begin proc_opname_ict(); end;";
	$db->query($query1);
	
	echo "OK";
	
?> 


