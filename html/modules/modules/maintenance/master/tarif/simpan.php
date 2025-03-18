<?php	
	//var_dump($_POST);die();
	
	$pkCon = $_POST['pkcon'];
	$idCon = $_POST['idcon'];
	$jenis_biaya = $_POST['jenis_biaya'];
	$jenis_biaya2 = $_POST['jenis_biaya2'];
	$pkjnsBiaya = $_POST['pkjnsBiaya'];
	$id_val = $_POST['id_VAL'];
	$pkVal = $_POST['pkVal'];
	$tarif = $_POST['tarif'];
	$s_period = $_POST['s_period'];
	$e_period = $_POST['e_period'];
	
	$ukuran=$_POST['id_UKURAN'];
    $type=$_POST['id_TYPE'];
    $status=$_POST['id_STATUS'];
    $height=$_POST['id_HEIGHT'];
	
	if (id_VAL == 'IDR') 
	{	
		$io = 'I';
	}
	else {
	$io = 'O';
	}
	
	
	
	$db =getDB();
	if ($pkCon == '' )
	{
	
		$q2 = "SELECT KODE_BARANG
  FROM MASTER_BARANG
 WHERE     TRIM (UKURAN) = TRIM ('$ukuran')
       AND TRIM (TYPE) = TRIM ('$type')
       AND TRIM (STATUS) = TRIM ('$status')
       AND TRIM (HEIGHT_CONT) = TRIM ('$height')";
			
		$result2 = $db->query($q2);
		$row2 = $result2->fetchRow();
		
		$idCon=$row2['KODE_BARANG'];
		$query = "INSERT INTO MASTER_TARIF_CONT(ID_CONT,JENIS_BIAYA,TARIF,VAL,JENIS_BIAYA2,OI,START_PERIOD,END_PERIOD) 
		VALUES('".$idCon."','".$jenis_biaya."','".$tarif."','".$id_val."','".$jenis_biaya2."','".$io."',TO_DATE('" . $_POST['s_period'] . "','DD-MM-YYYY'),TO_DATE('" . $_POST['e_period'] . "','DD-MM-YYYY'))";
		
		if($idCon==null || $idCon==''){
			echo("ID Container tidak valid");
			die();
		}
	}
	else
	{
		
		$query = "UPDATE MASTER_TARIF_CONT SET ID_CONT = '".$idCon."',JENIS_BIAYA='".$jenis_biaya."',TARIF='".$tarif."',VAL='".$id_val."',JENIS_BIAYA2='".$jenis_biaya2."',OI='".$io."',START_PERIOD=TO_DATE('" . $_POST['s_period'] . "','DD-MM-YYYY'),END_PERIOD=TO_DATE('" . $_POST['e_period'] . "','DD-MM-YYYY') WHERE ID_CONT='".$pkCon."' AND JENIS_BIAYA ='".$pkjnsBiaya."' AND VAL='".$pkVal."'";

	}
	
	//print_r($query);die();
	//$query = "INSERT INTO MASTER_BARANG (kode_barang, ukuran, type, //status) VALUES ('$kode_barang','$ukuran','$type','$status')";
	$result = $db->query($query);
	
	header('location:'.HOME.'maintenance.master.tarif/');
?> 