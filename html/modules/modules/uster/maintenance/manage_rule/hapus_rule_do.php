<?php 
$db 	= getDB("storage"); 
$id_rule = $_POST["ID_RULE"];
$id_kategori = $_POST["ID_KATEGORI"];
$id_menu = $_POST["ID_MENU"];
$id_submenu = $_POST["ID_SUBMENU"];
if($id_submenu == NULL){
	$query_delete = "DELETE FROM RULE_MENU_ WHERE RULE_MENU_.ID_ROLE = '$id_rule' AND RULE_MENU_.ID_KATEGORI = '$id_kategori' AND 
RULE_MENU_.ID_MENU = '$id_menu'";
}else{
	$query_delete = "DELETE FROM RULE_MENU_ WHERE RULE_MENU_.ID_ROLE = '$id_rule' AND RULE_MENU_.ID_KATEGORI = '$id_kategori' AND 
RULE_MENU_.ID_MENU = '$id_menu' AND RULE_MENU_.ID_SUBMENU = '$id_submenu'";
}
//echo $query_delete;die;
if($db->query($query_delete)){
	echo "Delete Succeed";
	die;
}
else{
	echo "FAILED";
	die;
}
?>
