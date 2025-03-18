<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('home.htm');
	
	/* $db 	= getDB("storage"); 
	$query_rulee = "SELECT * FROM ROLE";
	$res_rulee = $db->query($query_rulee);
	$rowra = $res_rulee->getAll();
	$rule_option = "<select id='role_add'>";								
	foreach($rowra as $rowra){ 
		$rule_option .= "<option value='".$rowra["ID"]."'>".$rowra["NAMA_ROLE"]."</option>";
	}
	$rule_option .= "</select>";
	
	$query_view = "SELECT ROLE.ID, ROLE.NAMA_ROLE, KATEGORI_MENU.ID ID_KATEGORI, KATEGORI_MENU.NAME KATEGORI
				FROM RULE_MENU INNER JOIN ROLE ON RULE_MENU.ID_ROLE = ROLE.ID
				INNER JOIN KATEGORI_MENU ON RULE_MENU.ID_KATEGORI = KATEGORI_MENU.ID
				ORDER BY ROLE.ID, ID_KATEGORI";
	$res_view = $db->query($query_view);
	$view_rule = 	$res_view->getAll();
	
	$tl->assign("rule_option",$rule_option);
	$tl->assign("view_rule",$view_rule); */
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
