<?php
	$id_js=$_POST["ID_JS"];	
	$id_alat=$_POST["ALAT"];
	$id_user=$_POST["USER"];
	$v_blok=$_POST["BLOK_ID"];
	$v_slot=$_POST["SLOT_ID"];
	$v_row=$_POST["ROW_ID"];
	$v_tier=$_POST["TIER_ID"];
	
	
	$db=getDB();
	$sql_xpi = "BEGIN placement_relocate_c_muat('$id_js','MUAT','$id_user','$id_alat', '$v_blok','$v_slot', '$v_row', '$v_tier'); END;";
	$db->query($sql_xpi);
	
	echo "alocated";
?>