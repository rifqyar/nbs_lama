 <?php
	$id_vessel = $_POST['KODE_KAPAL'];
	$nama = $_POST['NAMA_VESSEL'];
	$gt = $_POST['GT'];
	$loa = $_POST['LOA'];
	$id = $_GET['kode'];
	//print_r( $_GET['id']);die;
	$db =getDB();
	$query = "UPDATE MASTER_VESSEL SET KODE_KAPAL='$id_vessel', NAMA_VESSEL='$nama', GT='$gt', LOA='$loa' WHERE KODE_KAPAL='$id'";
	$result = $db->query($query);
	
	header('location:'.HOME.'maintenance.master.vessel/');
?> 