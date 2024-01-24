<?php

$db = getDB();
$id_user = $_SESSION["ID_USER"];
$id_vs = $_POST["ID_VS"];
$jml_bay = $_POST["JML_BAY"];
$jml_row = $_POST["JML_ROW"];
$jml_tier_ondeck = $_POST["JML_TIER_ONDECK"];
$jml_tier_underdeck = $_POST["JML_TIER_UNDERDECK"];

if(($id_vs==NULL)||($id_user==NULL)||($jml_bay==NULL)||($jml_row==NULL)||($jml_tier_ondeck==NULL)||($jml_tier_underdeck==NULL))
{
	echo "NO";
}
else
{
	for($i=1;$i<=$jml_bay*2;$i+=2)
	{
	//============== insert bay ====================//		
		$insert_profil = "INSERT INTO STW_BAY_AREA (BAY,STATUS_BAY,JML_ROW,JML_TIER_UNDER,JML_TIER_ON,ID_VS) VALUES ('$i','NON AKTIF','$jml_row','$jml_tier_underdeck','$jml_tier_ondeck','$id_vs')";	
		$db->query($insert_profil);

		$get_id_bay_area = "SELECT MAX(ID) AS ID_BAY FROM STW_BAY_AREA";
		$result10 = $db->query($get_id_bay_area);
		$id_bay = $result10->fetchRow();
		$bay_area = $id_bay['ID_BAY'];
	//============== insert bay ====================//
	
		//============== create cell ===================//
		$width = $jml_row+1;
		$height_on = $jml_tier_ondeck+1;
		$height = $jml_tier_ondeck+$jml_tier_underdeck+2;
		$luas = $width*$height;
		$ganjil = 1;
		
		for($d=1;$d<=$luas;$d++)
		{
			$div_tr = $d/$width;
			$cek_tr = "SELECT TRUNC('$div_tr') AS HASIL FROM DUAL";
			$result3 = $db->query($cek_tr);
			$hasil_tr = $result3->fetchRow();
			$hsl_cek = $hasil_tr['HASIL'];
			$z = $d%$width;
			
				if($d<=($width*$height_on)) /*on deck*/
				{
					 $row3 = $z;
					 if(($row3>=1)&&($row3<=((($jml_row+1)/2)-1))) /*row genap*/
					 {
						$row_bay = ($jml_row-1)-(($z-1)*2);
						$tier_bay = 80+(($height_on-$hsl_cek)*2);
					 }
					 else if($row3==(($jml_row+1)/2)) /*poros on deck*/
					 {
						$row_bay = 0;
						$tier_bay = 80+(($height_on-$hsl_cek)*2);
					 }
					 else if (($row3>(($jml_row+1)/2))&&($row3<=$jml_row)) /*row ganjil*/
					 {
						if($ganjil>$jml_row)
						{
							$y = $hsl_cek*10;
							$gnjl = $ganjil-$y;
							$row_bay = $gnjl;
							$tier_bay = 80+(($height_on-$hsl_cek)*2);
						}
						else
						{
							$row_bay = $ganjil;
							$tier_bay = 80+(($height_on-$hsl_cek)*2);
						}
						
						$ganjil+=2;
					 }
					 else if($row3==0) /*labeling*/
					 {
						$row_bay = 0;
						$tier_bay = 0;
					 }
				 
				}
				else if(($d>($height_on*$width))&&($d<=(($height_on+1)*$width)))  /*posisi palka*/
				{
					 $row_bay = 0;
					 $tier_bay = 0;
				}
				else if(($d>(($height_on+1)*$width))&&($d<=$luas)) /*under deck*/
				{
					  $row3 = $z;
					  if(($row3>=1)&&($row3<=((($jml_row+1)/2)-1))) /*row genap*/
					  {
						$row_bay = ($jml_row-1)-(($z-1)*2);
						$tier_bay = ($jml_tier_underdeck*2)-(($hsl_cek-($height_on+1))*2);
					  }
					  else if($row3==(($jml_row+1)/2)) /*poros under deck*/
					  {
						$row_bay = 0;
						$tier_bay = ($jml_tier_underdeck*2)-(($hsl_cek-($height_on+1))*2);
					  }
					  else if (($row3>(($jml_row+1)/2))&&($row3<=$jml_row)) /*row ganjil*/
					  {
						if($ganjil>$jml_row)
						{
							$y = ($hsl_cek-1)*10;
							$gnjl = $ganjil-$y;
							$row_bay = $gnjl;
							$tier_bay = ($jml_tier_underdeck*2)-(($hsl_cek-($height_on+1))*2);
						}
						else
						{
							$row_bay = $ganjil;
							$tier_bay = ($jml_tier_underdeck*2)-(($hsl_cek-($height_on+1))*2);
						}
						
						$ganjil+=2;
					  }
					  else if($row3==0) /*labeling*/
					  {
						$row_bay = 0;
						$tier_bay = 0;
					  }
				  	
				}
				$index_cell = $d-1;
						
				$insert_cell = "INSERT INTO STW_BAY_CELL (ID_BAY_AREA,CELL_NUMBER,ROW_,TIER_,STATUS_STACK) VALUES ('$bay_area','$index_cell','$row_bay','$tier_bay','N')";	
				$db->query($insert_cell);
		}	
		//============== create cell ===================//
	}
	
	$insert_history = "INSERT INTO STW_HISTORY (ID_VS,STATUS,TGL_UPDATE,USER_UPDATE) VALUES ('$id_vs','BUAT PROFIL',SYSDATE,'$id_user')";
	
	if($db->query($insert_history))
	{				
		echo "OK";
	}
	else
	{
		echo "gagal";
	}
	
}
?>