<?php

	$db = getDB();
	$id_user = $_SESSION["PENGGUNA_ID"];
	$id_vs = $_POST["id_vs"];
	$size = $_POST["size"];
	$type = $_POST["type"];
	$status = $_POST["status"];
	$box = $_POST["box"];
	$teus = $_POST["teus"];
	$id_book = $_POST["id_book"];
	$id_pel = $_POST["id_pelabuhan"];
	$pel = $_POST["pelabuhan"];

	if ($id_book == NULL){
	$query 	= "INSERT INTO TB_BOOKING_CONT_AREA
                                (      
								ID_VS,
								SIZE_CONT,
								TYPE_CONT,
								STATUS_CONT,
								BOX,
								ID_PEL_TUJ,
								TEUS,
								PELABUHAN_TUJUAN,
                                CREATE_DATE,
								ID_USER
                                ) VALUES (
                                '$id_vs',
                                '$size',
                                '$type',
                                '$status',
                                '$box',
                                '$id_pel',
                                '$teus',
								'$pel',
                                SYSDATE,
								'$id_user'
                                )";
	} else {
		$query 	= "UPDATE TB_BOOKING_CONT_AREA SET     
								SIZE_CONT = '$size',
								TYPE_CONT = '$type',
								STATUS_CONT = '$status'
								BOX = '$box',
								ID_PEL_TUJ = '$id_pel'
								TEUS = '$teus'
								PELABUHAN_TUJUAN = '$pel'
								ID_USER = '$id_user'
								WHERE ID_VS = '$id_vs'";
	}
    if ($db->query($query)){
		echo 'sukses';
	} else {
		echo 'gagal';
	}
?>