<?php

	$id_vsb=$_POST['ID_VSB'];
	$nocont=$_POST['NOCONT'];
	$db=getDb();
	
	
	$q_cek = "SELECT HZ, LABEL, IMO_CLASS FROM BIL_STV_LIST WHERE ID_VSB_VOYAGE = '$id_vsb' AND NO_CONTAINER = '$nocont'";
	$r_cek = $db->query($q_cek)->fetchRow();
	$imoclass = $r_cek[IMO_CLASS];

	if ($r_cek[HZ] == 'N') {
		echo "Z";
		die();
	}

	if ($imoclass == NULL) {
		$imo_new = $r_cek[LABEL];
	}
	else {
		$imo_new = NULL;
	}
	$q_set = "UPDATE BIL_STV_LIST SET IMO_CLASS = '$imo_new' WHERE ID_VSB_VOYAGE = '$id_vsb' AND NO_CONTAINER = '$nocont'";

	if ($db->query($q_set)) {
		if ($imo_new == NULL ) {
			echo "Y";
		}
		else {
			echo "N";
		}
		die();
	}

?>