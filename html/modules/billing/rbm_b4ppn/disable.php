<?php

	 $db=  getDB();
 
	 $id 	= $_POST['id'];
	 
	 $query			= "UPDATE RECIPIENT_EMAIL SET STATUS = 'NOT AKTIF' WHERE ID_RECIPIENT = '$id'";
	
	if ( $db->query($query)) {
		echo "OK";
	} else {
		echo "NOT OK";
	}
?>
