<?php

$db 		= getDB();
$id_user    = $_SESSION["ID_USER"];
$id_alat	= $_POST["ALAT"]; 
$id_vs		= $_POST["ID_VS"];
$no_bay		= $_POST["NO_BAY"];
$bay_area	= $_POST["BAY_AREA"];
$posisi		= $_POST["POSISI"];
$seq_alat   = $_POST["SEQ_ALAT"];

if($id_vs==NULL)
{
	echo "NO";
}
else
{
	$delete_seq_alat = "DELETE STW_BAY_CSL WHERE ID_VS = '$id_vs' AND ID_ALAT = '$id_alat' AND SEQ_ALAT = '$seq_alat'";
	$db->query($delete_seq_alat);
	
	//=================== CEK SEQUENCE ALAT ======================//
	$query_seq = "SELECT ID_BAY_AREA, TRIM(SZ_PLAN) SZ_PLAN FROM STW_BAY_CSL WHERE ID_ALAT = '$id_alat' AND ID_VS = '$id_vs' AND TRIM(SZ_PLAN) IN ('20','40d','45d') ORDER BY ID ASC";
    $jml_seq   = $db->query($query_seq);
    $jml_hsl   = $jml_seq->getAll();
	
	$n=1;
	foreach ($jml_hsl as $seq)
	{
		$id_bay = $seq['ID_BAY_AREA'];
		$id_bay40 = $seq['ID_BAY_AREA']+1;
		
		if(($seq['SZ_PLAN']=='40d')||($seq['SZ_PLAN']=='45d'))
		{
			$update_seq_alat = "UPDATE STW_BAY_CSL SET SEQ_ALAT = '$n' WHERE ID_VS = '$id_vs' AND ID_ALAT = '$id_alat' AND ID_BAY_AREA = '$id_bay'";
			$db->query($update_seq_alat);
			
			$update_seq_alatb = "UPDATE STW_BAY_CSL SET SEQ_ALAT = '$n' WHERE ID_VS = '$id_vs' AND ID_ALAT = '$id_alat' AND ID_BAY_AREA = '$id_bay40'";
			$db->query($update_seq_alatb);
		}
		else
		{
			$update_seq_alat = "UPDATE STW_BAY_CSL SET SEQ_ALAT = '$n' WHERE ID_VS = '$id_vs' AND ID_ALAT = '$id_alat' AND ID_BAY_AREA = '$id_bay'";
			$db->query($update_seq_alat);
		}	
		
		$n++;
	}
	//=================== CEK SEQUENCE ALAT ======================//

	$status = "DEALLOCATE ".$id_alat." FOR BAY ".$no_bay." ".$posisi;
	$insert_history = "INSERT INTO STW_HISTORY (ID_VS,STATUS,TGL_UPDATE,USER_UPDATE) VALUES ('$id_vs','$status',SYSDATE,'$id_user')";
				
		if($db->query($insert_history))
		{				
			echo "OK";
		}
		else
		{
			echo "NO";
		}
}

?>