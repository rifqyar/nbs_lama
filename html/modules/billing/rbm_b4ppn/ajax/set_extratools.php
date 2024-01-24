<?php

	$id_vsb=$_POST['ID_VSB'];
	$nocont=$_POST['NOCONT'];
	$db=getDb();
	
	
	$q_cek = "SELECT EXTRA_TOOLS FROM BIL_STV_LIST WHERE ID_VSB_VOYAGE = '$id_vsb' AND NO_CONTAINER = '$nocont'";
	$r_cek = $db->query($q_cek)->fetchRow();
	$exttools = $r_cek[EXTRA_TOOLS];
	if ($exttools == 'Y') {
		$ext_new = NULL;
	}
	else {
		$ext_new = 'Y';
	}
	$q_set = "UPDATE BIL_STV_LIST SET EXTRA_TOOLS = '$ext_new' WHERE ID_VSB_VOYAGE = '$id_vsb' AND NO_CONTAINER = '$nocont'";

	if ($db->query($q_set)) {
		if ($ext_new == 'Y') {
			echo "Y";
		}
		else {
			echo "N";
		}
		die();
	}

?>