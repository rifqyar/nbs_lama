<?php
	$noreq=$_POST['noreq'];
	$db=getDb();

	$query = "SELECT IS_EDIT FROM REQ_RECEIVING_H WHERE ID_REQ = '$noreq'";
	$db->query($query);
	
	$ret=$db->query($query);
	$row=$ret->fetchRow();
	
	echo($row[IS_EDIT]);
?>