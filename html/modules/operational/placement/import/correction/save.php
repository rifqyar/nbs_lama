<?php
	$no_cont=$_POST["NO_CONT"];	
	$no_ukk=$_POST["NO_UKK"];	
	$id_alat=$_POST["ALAT"];
	$id_user=$_SESSION["NAMA_PENGGUNA"];
	$v_blok=$_POST["BLOK_ID"];
	$v_slot=$_POST["SLOT_ID"];
	$v_row=$_POST["ROW_ID"];
	
	$v_tier=$_POST["TIER_ID"];
	$remark=$_POST["REMARKS"];
	
	$db=getDB();
	
	$q_cek1="select NAME from yd_blocking_area where ID='$v_blok' ";
	$hasil1=$db->query($q_cek1);
	$row_hs1=$hasil1->fetchRow();
	$name_blok = $row_hs1['NAME'];
	
	$q_cek="select count(ID_PLACEMENT) AS JM_C from yd_placement_yard where ID_BLOCKING_AREA='$v_blok' and SLOT_YARD='$v_slot' AND ROW_YARD='$v_row' AND TIER_YARD='$v_tier' ";
	$hasil=$db->query($q_cek);
	$row_hs=$hasil->fetchRow();
	
	if($row_hs['JM_C']==0)
	{
	
		$sql_xpi = "BEGIN placement_relocate_c_bongkar('$no_cont','$no_ukk','$id_user','$id_alat', '$v_blok','$v_slot', '$v_row', '$v_tier','$remark'); END;";
		$db->query($sql_xpi);
		
		$q_dek="UPDATE ISWS_LIST_CONTAINER SET kode_status = '03' WHERE NO_CONTAINER = '$no_cont' AND NO_UKK = '$no_ukk'";
		$db->query($q_dek);
	
		echo "alocated";
	}
	else
		echo "maaf block, slot, row, tier, yang anda pilih sudah terisi";
?>