<?php

$db = getDB();
$id_user = $_SESSION["ID_USER"];
$id_vs = $_POST["ID_VS"];
$bay_area = $_POST["BAY_AREA"];
$bay_width = $_POST["WIDTH"];
$posisi = $_POST["POSISI"];
$palka = $_POST["PALKA"];
//echo $id_vs;exit;

		$arr = $_REQUEST['p'];
		// open loop through each array element
		$n=0;
		foreach ($arr as $p)
		{
			// detach values from each parameter
			list($id, $row, $cell) = explode('_', $p);
			// instead of print, you can store accepted parameteres to the database
			if(($posisi=='ALL')||($posisi=='ABOVE'))
			{
				$alamat_cell = (($row*$bay_width)+($cell+1))-1;
			}
			else
			{
				$alamat_cell = (($row*$bay_width)+($cell+1))-1+($palka*$bay_width);
			}			
			
			list($vs, $no_container, $size_, $type_, $status_, $hz_, $gross_, $pel_asal, $pod, $keg, $kode_pbm, $no_booking, $id_plc, $tinggi, $iso_code, $imo_class, $celcius, $yd_b, $yd_s, $yd_r, $yd_t, $yd_stat) = explode(',', $id); 
			
			//echo " Id=$id Row=$row Cell=$cell Alamat=$alamat_cell";
			
			//-------------- cek posisi cell -------------//
			$get_id_cell = "SELECT B.ID AS ID_CELL, A.BAY, B.ROW_, B.TIER_, B.POSISI_STACK, A.OCCUPY FROM STW_BAY_AREA A, STW_BAY_CELL B WHERE A.ID = B.ID_BAY_AREA AND B.ID_BAY_AREA = '$bay_area' AND B.CELL_NUMBER = '$alamat_cell'";
			$result2 = $db->query($get_id_cell);
			$id_cell = $result2->fetchRow();
			$cell_id = $id_cell['ID_CELL'];
			$cell_bay = $id_cell['BAY'];
			$cell_row = $id_cell['ROW_'];
			$cell_tier = $id_cell['TIER_'];
			$cell_posisi = $id_cell['POSISI_STACK'];
			$cell_occu = $id_cell['OCCUPY'];
			
			//-------------- cek alat -------------//
			$get_id_alat = "SELECT ID_ALAT FROM STW_BAY_CSL WHERE ID_BAY_AREA = '$bay_area' AND POSISI_STACK = '$cell_posisi' AND ACTIVITY = '$keg'";
			$result6 = $db->query($get_id_alat);
			$id_alat = $result6->fetchRow();
			$alat = $id_alat['ID_ALAT'];
	
							
								$param_b_var= array(	
													"v_cell_id"=>"$cell_id",
													"v_cell_bay"=>"$cell_bay",
													"v_cell_row"=>"$cell_row",
													"v_cell_tier"=>"$cell_tier",
													"v_vs"=>"$vs",
													"v_no_container"=>"$no_container",
													"v_size_"=>"$size_",
													"v_type_"=>"$type_",
													"v_status_"=>"$status_",
													"v_hz_"=>"$hz_",
													"v_gross_"=>"$gross_",
													"v_keg"=>"$keg",
													"v_id_user"=>"$id_user",
													"v_alat"=>"$alat",
													"v_pel_asal"=>"$pel_asal",
													"v_pod"=>"$pod",
													"v_no_booking"=>"$no_booking",
													"v_kode_pbm"=>"$kode_pbm",
													"v_iso_code"=>"$iso_code",
													"v_imo_class"=>"$imo_class",
													"v_celcius"=>"$celcius",
													"v_bay_area"=>"$bay_area",
													"v_id_plc"=>"$id_plc",
													"v_cell_posisi"=>"$cell_posisi",
													"v_tinggi"=>"$tinggi",
													"v_alamat_cell"=>"$alamat_cell",
													"v_yard_b"=>"$yd_b",
													"v_yard_s"=>"$yd_s",
													"v_yard_r"=>"$yd_r",
													"v_yard_t"=>"$yd_t",
													"v_yard_stat"=>"$yd_stat",
													"v_occupy"=>"$cell_occu",
													"v_msg"=>""
													);
													
								//print_r($param_b_var);die;
								/*
								$stowage_plan = "declare begin ISWS.stowage_plan('$cell_id','$cell_bay','$cell_row','$cell_tier','$vs','$no_container','$size_','$type_','$status_','$hz_','$gross_','$keg','$id_user','$alat','$pel_asal','$pod','$no_booking','$kode_pbm','$iso_code','$imo_class','$celcius','$cek_statalokasi','$bay_area','$id_plc','$cell_posisi','$tinggi','$alamat_cell','$yd_b','$yd_s','$yd_r','$yd_t',''); end;";
								$db->query($stowage_plan);
								*/
								
								$stowage_plan = /*"declare begin ISWS.stowage_plan(:v_cell_id,:v_cell_bay,:v_cell_row,:v_cell_tier,:v_vs,:v_no_container,:v_size_,:v_type_,:v_status_,:v_hz_,:v_gross_,:v_keg,:v_id_user,:v_alat,:v_pel_asal,:v_pod,:v_no_booking,:v_kode_pbm,:v_iso_code,:v_imo_class,:v_celcius,:v_cek_statalokasi,:v_id_plc,:v_cell_posisi,:v_cell_posisi,:v_tinggi,:v_alamat_cell,:v_yard_b,:v_yard_s,:v_yard_r,:v_yard_t,:v_msg); end;";*/
								"declare begin stowage_plan_capacity(:v_cell_id,:v_cell_bay,:v_cell_row,:v_cell_tier,:v_vs,:v_no_container,:v_size_,:v_type_,:v_status_,:v_hz_,:v_gross_,:v_keg,:v_id_user,:v_alat,:v_pel_asal,:v_pod,:v_no_booking,:v_kode_pbm,:v_iso_code,:v_imo_class,:v_celcius,:v_bay_area,:v_id_plc,:v_cell_posisi,:v_tinggi,:v_alamat_cell,:v_yard_b,:v_yard_s,:v_yard_r,:v_yard_t,:v_yard_stat,:v_occupy,:v_msg); end;";
								$db->query($stowage_plan,$param_b_var);
								
								$msg_capacity = $param_b_var['v_msg'];
								echo $msg_capacity;								
							
			$n++;
		} 
		//exit;	
	
	$get_bay = "SELECT BAY FROM STW_BAY_AREA WHERE ID = '$bay_area'";
	$result3 = $db->query($get_bay);
	$no_bay = $result3->fetchRow();
	$number_bay = $no_bay['BAY'];
	if ($number_bay==1) 
	{ 
		$bay_no = $number_bay."(".($number_bay+1).")";  
	} 
	else if (($number_bay-1)%4==0) 
	{ 
		$bay_no = $number_bay."(".($number_bay+1).")"; 
	} 
	else 
	{ 
		$bay_no = $number_bay; 
	} 
		
	$status = "PLANNING BAY ".$bay_no;
								
	//-------------- history stowage -------------//
	$query_hist_stw = "INSERT INTO STW_HISTORY (ID_VS,STATUS,TGL_UPDATE,USER_UPDATE) VALUES ('$id_vs','$status',SYSDATE,'$id_user')";
								
	if($db->query($query_hist_stw))
	{				
		echo "OK_";
	}
	else
	{
	    echo "gagal_";
	}
?>