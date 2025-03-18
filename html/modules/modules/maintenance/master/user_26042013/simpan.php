<?php
	
	
	
	$id = $_POST['ID'];
	$name = $_POST['NAME'];
	$nipp = $_POST['NIPP'];
	$username = $_POST['USERNAME'];
	$password = $_POST['PASSWORD'];
	$id_group = $_POST['ID_GROUP'];
	$divisi = $_POST['DIVISI'];
	$jabatan = $_POST['JABATAN'];
	
	$db =getDB();
	$query = "INSERT INTO TB_USER (ID,NAME,NIPP,USERNAME,PASSWORD,ID_GROUP,DIVISI,JABATAN) VALUES ('$id','$name','$nipp','$username','$password','$id_group','$divisi','$jabatan')";
	$result = $db->query($query);
	
	header('location:'.HOME.'maintenance.master.user/');
?> 