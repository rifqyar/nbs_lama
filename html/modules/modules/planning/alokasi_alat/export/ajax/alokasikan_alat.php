<?php

$db = getDB();
$id_user = $_SESSION["ID_USER"];
$id_vs = $_POST["ID_VS"];
$id_bay = $_POST["BAY_AREA"];
$bay_no = $_POST["NO_BAY"];
$posisi_stack = $_POST["POSISI"];
$sz = $_POST["SZ_CONT"];
$act = $_POST["ACTIVITY"];
if($act=='IMPORT')
{
	$activity = "I";
}
else
{
	$activity = "E";
}

$posisi = strtoupper($posisi_stack);
$alat = $_POST["ALAT"];

if(($id_vs==NULL)||($alat==NULL)||($activity==NULL))
{
	echo "NO";
}
else
{
	$param_b_var= array(	
				"v_sz"=>"$sz",
				"v_no_ukk"=>"$id_vs",
				"v_bay_id"=>"$id_bay",
				"v_bay_no"=>"$bay_no",
				"v_bay_pss"=>"$posisi_stack",
				"v_act"=>"$activity",
				"v_alat"=>"$alat",
				"v_id_user"=>"$id_user",
				"v_msg"=>""
						);
	//print_r($param_b_var);die;	
	$csl_allo = "declare begin csl_allocation(:v_sz,:v_no_ukk,:v_bay_id,:v_bay_no,:v_bay_pss,:v_act,:v_alat,:v_id_user,:v_msg); end;";
	$db->query($csl_allo,$param_b_var);
								
	$msg_error = $param_b_var['v_msg'];
	echo $msg_error;
	
	/*
	if($sz=='20')
	{
		$sz_pln = "20";
	}
	else
	{
		$sz_pln = "40b";
	}

	$cek_alokasi = "SELECT count(*) AS JML_ALOKASI FROM STW_BAY_CSL WHERE ID_VS = '$id_vs' AND ID_BAY_AREA = '$id_bay' AND POSISI_STACK = '$posisi' AND ID_ALAT = '$alat' AND ACTIVITY = '$activity'";
	$result15 = $db->query($cek_alokasi);
	$alokasi_cek = $result15->fetchRow();
	$alokasi_alat = $alokasi_cek['JML_ALOKASI'];
	
	$cek_jml_alat = "SELECT count(*) AS JML_ALAT FROM STW_BAY_CSL WHERE ID_VS = '$id_vs' AND ID_BAY_AREA = '$id_bay' AND POSISI_STACK = '$posisi'";
	$result17 = $db->query($cek_jml_alat);
	$alokasi_jml_cek = $result17->fetchRow();
	$alokasi_jml_alat = $alokasi_jml_cek['JML_ALAT'];
	
	if($alokasi_jml_alat < 2)
	{
	
		if($alokasi_alat == 0)
		{

			$cek_seq = "SELECT MAX(SEQ_ALAT) AS MAX_SEQ FROM STW_BAY_CSL A,STW_BAY_CELL B WHERE A.ID_BAY_AREA = B.ID_BAY_AREA AND A.ID_ALAT = '$alat' AND A.ID_VS = '$id_vs' AND A.ACTIVITY = '$activity'";
			$result11 = $db->query($cek_seq);
			$seq_cek = $result11->fetchRow();
			$seq_alat = $seq_cek['MAX_SEQ'];
			
			if($seq_alat == NULL)
			{
				$sequence = 1;
			}
			else
			{
				$sequence = $seq_alat+1;
			}
			
			if($sz=='20')
			{
				$insert_csl = "INSERT INTO STW_BAY_CSL (ID_VS,ID_BAY_AREA,POSISI_STACK,ID_ALAT,SEQ_ALAT,ACTIVITY,PRIORITY,SZ_PLAN) VALUES ('$id_vs','$id_bay','$posisi','$alat','$sequence','$activity','T','20')";
				$db->query($insert_csl);
				
				$status = "ALLOCATE ".$alat." FOR BAY ".$no_bay." ".$posisi;
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
			else
			{
				//--- BAY DEPAN---//
				$insert_csl = "INSERT INTO STW_BAY_CSL (ID_VS,ID_BAY_AREA,POSISI_STACK,ID_ALAT,SEQ_ALAT,ACTIVITY,PRIORITY,SZ_PLAN) VALUES ('$id_vs','$id_bay','$posisi','$alat','$sequence','$activity','T','40d')";
				$db->query($insert_csl);
				
				$status = "ALLOCATE ".$alat." FOR BAY ".$no_bay." ".$posisi;
				$insert_history = "INSERT INTO STW_HISTORY (ID_VS,STATUS,TGL_UPDATE,USER_UPDATE) VALUES ('$id_vs','$status',SYSDATE,'$id_user')";
							
				if($db->query($insert_history))
				{				
					echo "OK";
				}
				else
				{
					echo "NO";
				}
				
				//--- BAY BELAKANG---//
				$id_bay40 = $id_bay+1;
				$no_bay40 = $no_bay+2;
				
				$insert_csl = "INSERT INTO STW_BAY_CSL (ID_VS,ID_BAY_AREA,POSISI_STACK,ID_ALAT,SEQ_ALAT,ACTIVITY,PRIORITY,SZ_PLAN) VALUES ('$id_vs','$id_bay40','$posisi','$alat','$sequence','$activity','T','40b')";
				$db->query($insert_csl);
				
				$status = "ALLOCATE ".$alat." FOR BAY ".$no_bay40." ".$posisi;
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
		else
		{
			echo "ALOKASI";
		}
	}
	else
	{
		echo "JUMLAH";
	}
	*/
}
?>