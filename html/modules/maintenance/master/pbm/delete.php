	<?php
	$id = $_GET['id'];
	$db = getDB();
	$query = "DELETE FROM MASTER_PBM WHERE KODE_PBM = '$id'";
	$result = $db->query($query);
	
	header('location:'.HOME.'maintenance.master.pbm/');
?>