<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('sent_email.htm');
	
	 $db=  getDB();
 
	 $new 	= $_POST['new'];
	 
	 $query			= "SELECT * FROM RECIPIENT_EMAIL";
	 $result_q		= $db->query($query);
	 $data			= $result_q->getAll();
	 
	 $count 		= count($data);
	 //echo $count;
	 $email = "";
	 $i = 1;
	 foreach ($data as $item){
		if ($i < $count){
			$email .= $email.'"'.$item['NAMA'].'" <'.$item['EMAIL'].'>,';
		} else {
			$email .= $email.'"'.$item['NAMA'].'" <'.$item['EMAIL'].'>';
		}
	$i++;
	 }
	 
	//echo $email;
 
	  $tl->assign("id_vessel",$id_vessel);
	 $tl->assign("email",$email);
	 

	$tl->renderToScreen();
?>
