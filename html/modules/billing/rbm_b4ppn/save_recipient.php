<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('detail.htm');
	
	 $db=  getDB();
 
	 $id_vessel 	= $_GET['id_vessel'];
	 
	 $query			= "SELECT * FROM RECIPIENT_EMAIL WHERE STATUS = 'AKTIF' AND GROUP_ LIKE '%RBM%'";
	 $result_q		= $db->query($query);
	 $data			= $result_q->getAll();
	 
	 $count 		= count($data);
	 //echo $count;
	 $email = '';
	 $i = 1;
	 foreach ($data as $item){
		if ($i <> $count){
			$email .= $email.'"'.$item['NAMA'].'" <'.$item['EMAIL'].'>,';
		} else {
			$email .= $email.'"'.$item['NAMA'].'" <'.$item['EMAIL'].'>';
		}
		$i++;
	 }
	 
	//echo $email;
	 $tl->assign("data",$data);
	 $tl->assign("id_vessel",$id_vessel);
	 $tl->assign("email",$email);
	 

	$tl->renderToScreen();
?>
