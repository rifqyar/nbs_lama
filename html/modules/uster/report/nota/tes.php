<?php
	echo "string";
	die();
	$db = getDB('storage');
	if(isset($_GET['table']) {
		$table_name = $_GET['table'];
	$q = "SELECT column_name as COLUMN_NAME, nullable || '       ' as BE_NULL,
		  SUBSTR(data_type || '(' || data_length || ')', 0, 10) as TYPE
		 FROM all_tab_columns WHERE table_name = '$table_name'";
	$r = $db->query($q)->getAll();
	foreach ($r as $key) {
		echo $key['COLUMN_NAME'].",";
	}
	}
?>
