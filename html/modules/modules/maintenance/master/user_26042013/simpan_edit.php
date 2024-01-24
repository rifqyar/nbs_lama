 <?php
	$ID = $_POST['ID'];
	$name = $_POST['NAME'];
	$nipp = $_POST['NIPP'];
	$divisi = $_POST['DIVISI'];
	$jabatan = $_POST['JABATAN'];
	$username = $_POST['USERNAME'];
	$name_group = $_POST['NAME_GROUP'];
	$password = $_POST['PASSWORD'];
	$id = $_GET['ID'];
	//$pass = $_GET('PASSWORD');
	//print_r( $_GET['id']);die;
	$db =getDB();
	
	
	
	$query = "UPDATE TB_USER SET PASSWORD='$password' WHERE ID='$id'";
	$result = $db->query($query);
	
	
	
	header('location:'.HOME.'maintenance.master.user/');
?> 