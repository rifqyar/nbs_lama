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
	
	//============= Pacth ICT =============//

	$cek = "BEGIN loading_confirm_ict('$no_container','$cell_bay','$cell_row','$cell_tier','$op_alat','$soa','$op_ht','$alat','$ht','$nm_user','$no_ukk'); END;";
	$db->query($cek);
	
	//============= Pacth ICT =============//
	
	$param_b_var= array(	
						"v_cell_id"=>"$cell_id",
						"v_cell_bay"=>"$cell_bay",
						"v_cell_row"=>"$cell_row",
						"v_cell_tier"=>"$cell_tier",
						"v_vs"=>"$no_ukk",
						"v_no_container"=>"$no_container",
						"v_size_"=>"$sz_cont",
						"v_id_user"=>"$id_user",
						"v_alat"=>"$alat",
						"v_bay_area"=>"$bay_area",
						"v_cell_posisi"=>"$cell_posisi",
						"v_alamat_cell"=>"$cell_address",
						"v_bay_no"=>"$bay_no",
						"v_op_alat"=>"$op_alat",
						"v_op_ht"=>"$op_ht",
						"v_ht"=>"$ht",
						"v_soa"=>"$soa",
						"v_woa"=>"$woa",
						"v_occu"=>"$cell_occu",
						"v_msg"=>""
						);
								
	$load = "declare begin ISWS.loading_confirm_correction(:v_cell_id,:v_cell_bay,:v_cell_row,:v_cell_tier,:v_vs,:v_no_container,:v_size_,:v_id_user,:v_alat,:v_bay_area,:v_cell_posisi,:v_alamat_cell,:v_bay_no,:v_op_alat,:v_op_alat,:v_op_ht,:v_ht,:v_soa,:v_woa,:v_occu,:v_msg); end;";
	$db->query($load,$param_b_var);
								
	$msg_capacity = $param_b_var['v_msg'];
	echo $msg_capacity;
?>