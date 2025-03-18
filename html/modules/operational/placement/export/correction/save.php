<?php
	$id_js=$_POST["ID_JS"];	
	$id_alat=$_POST["ALAT"];
	$id_user=$_SESSION["NAMA_PENGGUNA"];
	$v_blok=$_POST["BLOK_ID"];
	$v_slot=$_POST["SLOT_ID"];
	$v_row=$_POST["ROW_ID"];
	$v_tier=$_POST["TIER_ID"];
	$remark=$_POST["REMARKS"];
	$db=getDB();
	
	$seq_plc1   = "SELECT a.NAME FROM YD_BLOCKING_AREA a WHERE a.ID = '$v_blok'";
	$jml_plc1   = $db->query($seq_plc1);
	$jml_hsl1   = $jml_plc1->fetchRow();
					
	$name	= $jml_hsl1['NAME'];
	
	$seq_plc2   = " SELECT NO_CONT, ID_VS FROM TB_CONT_JOBSLIP WHERE ID_JOB_SLIP = '$id_js'";
	$jml_plc2   = $db->query($seq_plc2);
	$jml_hsl2   = $jml_plc2->fetchRow();
					
	$no_cont	= $jml_hsl2['NO_CONT'];
	$no_ukk		= $jml_hsl2['ID_VS'];
	
	$q_cek="select count(ID_PLACEMENT) AS JM_C from yd_placement_yard where ID_BLOCKING_AREA='$v_blok' and SLOT_YARD='$v_slot' AND ROW_YARD='$v_row' AND TIER_YARD='$v_tier' ";
	$hasil=$db->query($q_cek);
	$row_hs=$hasil->fetchRow();
	
	if($row_hs['JM_C']==0)
	{
	
		$sql_xpi = "BEGIN placement_relocate_c_muat('$id_js','MUAT','$id_user','$id_alat', '$v_blok','$v_slot', '$v_row', '$v_tier','$remark'); END;";
		$db->query($sql_xpi);
		
		echo "alocated";
	}
	else
		echo "maaf block, slot, row, tier, yang anda pilih sudah terisi";
?>