<?php
$no_cont		= strtoupper($_GET["term"]);
$no_ukk		=	$_GET['no_ukk'];
$db 			= getDB();
	
$query 			= "SELECT NO_CONTAINER, SIZE_CONT, TYPE_CONT, STATUS_CONT,
					HEIGHT_CONT, HZ, E_I FROM 
					(SELECT rl.NO_CONTAINER, rl.SIZE_CONT, rl.TYPE_CONT, rl.STATUS_CONT,
						rl.HEIGHT_CONT, rl.HZ, rl.E_I FROM RBM_LIST rl WHERE rl.NO_UKK='$no_ukk'
						AND rl.NO_CONTAINER LIKE '$no_cont%')
					where rownum<10
					";
//print_r($query);die;
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($query);die;

echo json_encode($row);


?>