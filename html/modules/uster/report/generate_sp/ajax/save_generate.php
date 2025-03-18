<?php

	$id_req		= $_POST['id_req'];
	$id_time	= $_POST['id_time'];

	$db = getDB("storage");
	
	$Query = "SELECT TAHUN, LPAD(SEQUENCE+1,'4','0') SEQ, (SEQUENCE+1) SEQUE FROM MTI_COUNTER_SP WHERE TAHUN = TO_CHAR(SYSDATE,'YYYY')";
	$NO_SP = $db->query($Query)->fetchRow();
	
	if($NO_SP['SEQ'] == NULL || $NO_SP['SEQ'] == ""){
		$db->query("INSERT INTO MTI_COUNTER_SP (TAHUN, SEQUENCE) VALUES (TO_CHAR(SYSDATE,'YYYY'), 0)");
		
		$Query = "SELECT TAHUN, LPAD(SEQUENCE+1,'4','0') SEQ, (SEQUENCE+1) SEQUE FROM MTI_COUNTER_SP WHERE TAHUN = TO_CHAR(SYSDATE,'YYYY')";
		$NO_SP = $db->query($Query)->fetchRow();
	}
	
	$NOSP = "SPPTK".$NO_SP['TAHUN']."-".$NO_SP['SEQ'];
	//
	$Query = "SELECT NO_REQUEST, TO_CHAR(TGL_SIMPAN,'DD-MM-YYYY') TGL_SIMPAN, NO_NOTA_MTI, NO_FAKTUR_MTI, SP_MTI FROM ITPK_NOTA_HEADER WHERE TO_CHAR(TGL_SIMPAN,'DD-MM-YYYY') =  '$id_time'";
	$SQL = $db->query($Query)->getAll();
	
	foreach($SQL as $SQL){	
	
		if ($SQL['SP_MTI'] != NULL) {
			$message = "NO SP Sudah Ada, ";
		}else{
			$SPSQL ="UPDATE ITPK_NOTA_HEADER SET SP_MTI = '".$NOSP."' WHERE STATUS <> 5 AND NO_REQUEST = '".$SQL['NO_REQUEST']."' AND TO_CHAR(TGL_SIMPAN,'DD-MM-YYYY') = '".$SQL['TGL_SIMPAN']."'";
			
			$Stat = $db->query( $SPSQL );
			
			$db->query("UPDATE MTI_COUNTER_SP SET SEQUENCE = '".$NO_SP['SEQUE']."' WHERE TAHUN = TO_CHAR(SYSDATE,'YYYY')");
		}
	}
	
	if(!$Stat){
		echo $message."Data Gagal Diproses !";
	}else{
		echo "Data Berhasil Diproses !";
	}
		
	
	
?>