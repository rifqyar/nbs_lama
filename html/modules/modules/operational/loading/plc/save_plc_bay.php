<?
	$bay_area = $_POST["BAY_AREA"];
	$cell_address = $_POST["ADDRESS_CELL"];	
	$no_ukk = $_POST["NO_UKK"];
	$id_user = $_SESSION["ID_USER"];
	$nm_user = $_SESSION["NAMA_PENGGUNA"];
	$no_container = $_POST["NO_CONT"];
	$bay_no = $_POST["BAY_NO"];
	$sz_cont = $_POST["SZ_CONT"];
	$alat = $_POST["ALAT"];
	$op_alat = $_POST["OP_ALAT"];
	$ht = $_POST["HT"];
	$op_ht = $_POST["OP_HT"];
	$soa = $_POST["SOA"];
	$woa = "";
	
	$db=getDB();	
	
	//-------------- cek posisi cell -------------//
	$get_id_cell = "SELECT B.ID AS ID_CELL, A.BAY, B.ROW_, B.TIER_, B.POSISI_STACK, A.OCCUPY FROM STW_BAY_AREA A, STW_BAY_CELL B WHERE A.ID = B.ID_BAY_AREA AND B.ID_BAY_AREA = '$bay_area' AND B.CELL_NUMBER = '$cell_address'";
	$result2 = $db->query($get_id_cell);
	$id_cell = $result2->fetchRow();
	$cell_id = $id_cell['ID_CELL'];
	$cell_bay = $id_cell['BAY'];
	$cell_row = $id_cell['ROW_'];
	$cell_tier = $id_cell['TIER_'];
	$cell_posisi = $id_cell['POSISI_STACK'];
	$cell_occu = $id_cell['OCCUPY'];
							
	$load = "declare begin loading_confirm_correction('$cell_id','$cell_bay','$cell_row','$cell_tier','$no_ukk','$no_container','$sz_cont','$id_user','$alat','$bay_area','$cell_posisi','$cell_address','$bay_no','$op_alat','$op_ht','$ht','$soa','$woa','$cell_occu'); end;";
	$db->query($load);
	
	$get_id_cell2 = "SELECT REMARK FROM ISWS_LIST_CONTAINER WHERE NO_CONTAINER = '$no_container' AND NO_UKK = '$no_ukk'";
	$result3 = $db->query($get_id_cell2);
	$id_cell3 = $result3->fetchRow();
	//$msg_capacity  = $id_cell3['REMARK_ICT'];	
	$msg_capacity  = $id_cell3['REMARK'];	
	echo $msg_capacity;
?>