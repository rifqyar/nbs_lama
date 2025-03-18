<?php
	$user = $_POST['username'];
	$pass = $_POST['password'];
	
	$db = getDB();
	$query = "SELECT A.*, B.* FROM TB_USER A, TB_GROUP B WHERE A.USERNAME = '$user' AND A.ID_GROUP=B.ID_GROUP";
	$result = $db->query($query);
	//echo $result->RecordCount()."-";die;
 	if($result->RecordCount() >0)
	{
		$row = $result->fetchRow();
		$md5_string = md5($pass.$row['ID']);
		if($row['PASSWORD_ENC']==$md5_string){
			$_SESSION["PENGGUNA_ID"] = $row['ID'];
			$_SESSION["KATA_KUNCI"] = 'PASSWORD';
			$_SESSION["ID_GROUP"] = $row['ID_GROUP'];
			$_SESSION["ATURAN"] = $row['NAME_GROUP'];
			/*
			if($row['ID_GROUP']=='1')
			{
				$_SESSION["ATURAN"] = "ADMIN";
			}
			else if($row['ID_GROUP']=='7')
			{
				$_SESSION["ATURAN"] = "BILLING";
			}
			else if($row['ID_GROUP']=='9')
			{
				$_SESSION["ATURAN"] = "KEUANGAN";
			}
			else if($row['ID_GROUP']=='8')
			{
				$_SESSION["ATURAN"] = "PERALATAN TO2";
			}
			else if($row['ID_GROUP']=='6')
			{
				$_SESSION["ATURAN"] = "RENDAL TO2";
			}
			*/
			
			if ($row['ID_GROUP']=='NULL')
			{
				print_r("Anda Bukan User... Hahaha!!!");
				header('Refresh: 3; url='.HOME.'login/logout');
			}
			
			$_SESSION["NAMA_PENGGUNA"] = $row['USERNAME'];
			$_SESSION["NAMA_LENGKAP"] = $row['NAME'];
			$_SESSION["ID_USER"] = $row['ID'];
			$_SESSION["LOGGED_STORAGE"] = $row['ID'];

			
			$query2 = "UPDATE TB_USER SET LAST_LOGIN=SYSDATE WHERE ID = '".$row['ID']."'";
			$db->query($query2);
	
			header('location:'.HOME.'main/');
		} else {
			echo "<b> Wrong username and password!! </b>";
			header('Refresh: 3; url='.HOME.'login/'); 

		}
		
	}
	else{
		echo "<b> Wrong username and password!! </b>";
		header('Refresh: 3; url='.HOME.'login/'); 
	}
	
?> 