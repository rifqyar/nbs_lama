<?php

$db = getDB();
$id_user = $_SESSION["ID_USER"];
$id_vs = $_POST["ID_VS"];
$id_bay = $_POST["BAY_AREA"];
$no_bay = $_POST["NO_BAY"];

if ($no_bay==1) 
{ 
	$bays = $no_bay."(".($no_bay+1).")";  
} 
else if (($no_bay-1)%4==0) 
{ 
	$bays = $no_bay."(".($no_bay+1).")"; 
} 
else 
{ 
	$bays = $no_bay; 
}
$posisi = $_POST["POSISI"];

if($id_vs==NULL)
{
	echo "NO";
}
else
{
	$cek_stack = "SELECT COUNT(*) AS JML_PLAN FROM STW_BAY_AREA A, STW_BAY_CELL B WHERE A.ID = B.ID_BAY_AREA AND B.ID_BAY_AREA = '$id_bay' AND B.STATUS_STACK = 'P'";
	$result10 = $db->query($cek_stack);
	$cek_cell = $result10->fetchRow();
	$stack_count = $cek_cell['JML_PLAN'];
	
	if($stack_count>0)
	{
		echo "PLAN";
	}
	else
	{
		if($posisi == 'above')
		{
			$update_status_bay = "UPDATE STW_BAY_AREA SET ABOVE = 'NON AKTIF' WHERE ID = '$id_bay'";
			$db->query($update_status_bay);
			
			$update_stack = "UPDATE STW_BAY_CELL SET STATUS_STACK = 'N',PLUGGING = 'T' WHERE ID_BAY_AREA = '$id_bay' AND POSISI_STACK = 'ABOVE'";
		    $db->query($update_stack);
		}
		else
		{
			$update_status_bay = "UPDATE STW_BAY_AREA SET BELOW = 'NON AKTIF' WHERE ID = '$id_bay'";
			$db->query($update_status_bay);
			
			$update_stack = "UPDATE STW_BAY_CELL SET STATUS_STACK = 'N',PLUGGING = 'T' WHERE ID_BAY_AREA = '$id_bay' AND POSISI_STACK = 'BELOW'";
		    $db->query($update_stack);
		}
		
		/*
		$cek_size = "SELECT SIZE_,CELL_NUMBER FROM STW_BAY_ALLOCATION WHERE ID_BAY_AREA = '$id_bay'";
		$result11 = $db->query($cek_size);
		$cek_ukuran = $result11->getAll();
		
		foreach($cek_ukuran as $cek)
		{
			$ukuran = $cek['SIZE_'];
			$cell_address = $cek['CELL_NUMBER'];
			
			if(($ukuran==40)||($ukuran==45))
			{
				$id_bay40 = $id_bay+1;
					
				if($posisi == 'above')
				{
					$update_status_bay = "UPDATE STW_BAY_AREA SET ABOVE = 'NON AKTIF' WHERE ID = '$id_bay'";
					$db->query($update_status_bay);
				}
				else
				{
					$update_status_bay = "UPDATE STW_BAY_AREA SET BELOW = 'NON AKTIF' WHERE ID = '$id_bay'";
					$db->query($update_status_bay);
				}
				
				$update_stack = "UPDATE STW_BAY_CELL SET STATUS_STACK = 'N' WHERE ID_BAY_AREA = '$id_bay' AND CELL_NUMBER = '$cell_address'";
				$db->query($update_stack);
				$update_stack40 = "UPDATE STW_BAY_CELL SET STATUS_STACK = 'N' WHERE ID_BAY_AREA = '$id_bay40' AND CELL_NUMBER = '$cell_address'";
				$db->query($update_stack40);
				$delete_alokasi = "DELETE FROM STW_BAY_ALLOCATION WHERE ID_BAY_AREA = '$id_bay' AND CELL_NUMBER = '$cell_address'";
				$db->query($delete_alokasi);
				$delete_alokasi40 = "DELETE FROM STW_BAY_ALLOCATION WHERE ID_BAY_AREA = '$id_bay40' AND CELL_NUMBER = '$cell_address'";
				$db->query($delete_alokasi40);				
			}
			else
			{
				if($posisi == 'above')
				{
					$update_status_bay = "UPDATE STW_BAY_AREA SET ABOVE = 'NON AKTIF' WHERE ID = '$id_bay'";
					$db->query($update_status_bay);
				}
				else
				{
					$update_status_bay = "UPDATE STW_BAY_AREA SET BELOW = 'NON AKTIF' WHERE ID = '$id_bay'";
					$db->query($update_status_bay);
				}				
				
				$update_stack = "UPDATE STW_BAY_CELL SET STATUS_STACK = 'N' WHERE ID_BAY_AREA = '$id_bay' AND CELL_NUMBER = '$cell_address'";
				$db->query($update_stack);
				$delete_alokasi = "DELETE FROM STW_BAY_ALLOCATION WHERE ID_BAY_AREA = '$id_bay' AND CELL_NUMBER = '$cell_address'";
				$db->query($delete_alokasi);			
			}
		}
		*/
		
		$pss = strtoupper($posisi);
		$cek_h = "SELECT SUM(HEIGHT_CAPACITY ) JUM_H FROM STW_BAY_CAPACITY WHERE ID_BAY_AREA = '$id_bay' AND POSISI_STACK = '$pss'";
		$result_h = $db->query($cek_h);
		$h_cell = $result_h->fetchRow();
		$jumh = $h_cell['JUM_H'];
		
		if($jumh!=NULL)
		{
			$pss = strtoupper($posisi);		
			$update_height_capacity = "UPDATE STW_BAY_CAPACITY SET HEIGHT_CAPACITY = NULL, PLAN_HEIGHT = NULL, REAL_HEIGHT = NULL WHERE ID_BAY_AREA = '$id_bay' AND POSISI_STACK = '$pss'";
			$db->query($update_height_capacity);
		}
		
		$status = "RESET ALOKASI BAY ".$bays;
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
}
?>