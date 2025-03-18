<?php
$db = getDB("storage");
$id_rule = $_POST["ID_RULE"];
$id_kategori = $_POST["ID_KATEGORI"];
$id_menu = $_POST["ID_MENU"];
$id_submenu = $_POST["ID_SUBMENU"];
if($id_submenu == 'null'){
	$query_add = "INSERT INTO RULE_MENU_(ID_ROLE, ID_KATEGORI, ID_MENU) VALUES ('$id_rule','$id_kategori','$id_menu')";
}
else{
	$query_add = "INSERT INTO RULE_MENU_(ID_ROLE, ID_KATEGORI, ID_MENU, ID_SUBMENU) VALUES ('$id_rule','$id_kategori','$id_menu','$id_submenu')";
}
if($db->query($query_add)){
	echo "Add Category Succeed";
	exit();
}
else{
	echo "Add Category Failed";
	exit();
}

?>