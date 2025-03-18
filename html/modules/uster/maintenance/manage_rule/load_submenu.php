<?php
	$db 	= getDB("storage"); 
	$id_menu = $_POST["ID_MENU"];
	$q = "SELECT * FROM SUB_MENU WHERE SUB_MENU.ID_MENU = '$id_menu'";
	$r = $db->query($q);
	$row = $r->getAll();
	$html = '<select id="id_submenu_do">';
	foreach($row as $ro){
		$html .= '<option value="'.$ro["ID"].'">'.$ro["NAME"].'</option>';
	}
	$html .='</select>';
?>
<div id="sub">
	<?=$html;?>
</div>