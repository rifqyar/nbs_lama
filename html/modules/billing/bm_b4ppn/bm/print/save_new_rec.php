<?php
	//header('Location: '.HOME .'static/error.htm');		
	//$tl 	=  xliteTemplate('detail.htm');
	
	 $db=  getDB();
 
	 $name 			= $_POST['name'];
	 $email 		= $_POST['email'];
	 
	 $query			= "INSERT INTO RECIPIENT_EMAIL (NAMA, EMAIL) VALUES ('$name','$email')";
	 

	if ($db->query($query)){
		echo 'OK';
	} else {
		echo 'NOT OK';
	}
?>
