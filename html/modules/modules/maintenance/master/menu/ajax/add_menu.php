<?php//$id_user = $_SESSION["ID_USER"];$menu = $_POST['MENU'];$otorisasi = $_POST['OTORISASI'];$url = $_POST['URLNYA'];$parent_id = $_POST['PARENT_ID'];$menu_order = $_POST['MENU_ORDER'];//echo $otorisasi;die;//echo "begin menu_ins('".$menu."','".$otorisasi."','".$url."',".$parent_id.",".$menu_order."); end;"; die;if(($menu==""))	echo "NO";else{	$db=getDB();	//$db->query("INSERT INTO TR_USER 	//			(ID_USER, NAME, NIP, USERNAME, PASSWORD, ID_GROUP, AKTIF) 	//			VALUES 	//			('$ref_humas','$plg','$simop','$terminal')");	$db->query("begin menu_ins('".$menu."','".$otorisasi."','".$url."',".$parent_id.",".$menu_order."); end;");	echo "OK";}?>