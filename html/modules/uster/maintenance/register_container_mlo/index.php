<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('home.htm');
	
	$db 	= getDB("storage"); 
	$query_rulee = "SELECT * FROM ROLE";
	$res_rulee = $db->query($query_rulee);
	$rowra = $res_rulee->getAll();
	$rule_option = "<select id='role_add'>";								
	foreach($rowra as $rowra){ 
		$rule_option .= "<option value='".$rowra["ID"]."'>".$rowra["NAMA_ROLE"]."</option>";
	}
	$rule_option .= "</select>";
	
	$tl->assign("rule_option",$rule_option);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
