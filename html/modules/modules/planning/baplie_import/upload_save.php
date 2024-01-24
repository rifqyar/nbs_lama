<?php
// Test CVS
//echo $_FILES['uploadfile']['tmp_name'];die;
$id_vs = $_GET['id_vs'];
				//echo $vs;die;
$id_user = $_SESSION["ID_USER"];

require_once 'excel/reader.php';


// ExcelFile($filename, $encoding);
$data = new Spreadsheet_Excel_Reader();



// Set output Encoding.
$data->setOutputEncoding('CP1251');

/***
* if you want you can change 'iconv' to mb_convert_encoding:
* $data->setUTFEncoder('mb');
*
**/

/***
* By default rows & cols indeces start with 1
* For change initial index use:
* $data->setRowColOffset(0);
*
**/



/***
*  Some function for formatting output.
* $data->setDefaultFormat('%.2f');
* setDefaultFormat - set format for columns with unknown formatting
*
* $data->setColumnFormat(4, '%.3f');
* setColumnFormat - set format for column (apply only to number fields)
*
**/
$data->read($_FILES['uploadfile']['tmp_name']);

/*


 $data->sheets[0]['numRows'] - count rows
 $data->sheets[0]['numCols'] - count columns
 $data->sheets[0]['cells'][$i][$j] - data from $i-row $j-column

 $data->sheets[0]['cellsInfo'][$i][$j] - extended info about cell
    
    $data->sheets[0]['cellsInfo'][$i][$j]['type'] = "date" | "number" | "unknown"
        if 'type' == "unknown" - use 'raw' value, because  cell contain value with format '0.00';
    $data->sheets[0]['cellsInfo'][$i][$j]['raw'] = value if cell without format 
    $data->sheets[0]['cellsInfo'][$i][$j]['colspan'] 
    $data->sheets[0]['cellsInfo'][$i][$j]['rowspan'] 
*/

error_reporting(E_ALL ^ E_NOTICE);
    
                //  echo 'dama';die;
                $db = getDB();
                if ($_POST['modus'] == 'overwrite'){
                     $delete = "DELETE FROM YD_PLACEMENT_YARD";
                     $db->query($delete);
                }
				
				//------- create bay Per vessel ---------//
                $jml_bay    = $data->sheets[0]['cells'][4][1];
                $jml_row    = $data->sheets[0]['cells'][4][2];
                $jml_tier_ondeck   = $data->sheets[0]['cells'][4][3];
                $jml_tier_underdeck = $data->sheets[0]['cells'][4][4];
				//------- create bay Per vessel ---------//

					
	for($i=1;$i<=$jml_bay*2;$i+=2)
	{
	//============== insert bay ====================//		
		$insert_profil = "INSERT INTO STW_BAY_AREA (BAY,JML_ROW,JML_TIER_UNDER,JML_TIER_ON,ID_VS,ABOVE,BELOW) VALUES ('$i','$jml_row','$jml_tier_underdeck','$jml_tier_ondeck','$id_vs','NON AKTIF','NON AKTIF')";	
		$db->query($insert_profil);

		$get_id_bay_area = "SELECT MAX(ID) AS ID_BAY FROM STW_BAY_AREA";
		$result10 = $db->query($get_id_bay_area);
		$id_bay = $result10->fetchRow();
		$bay_area = $id_bay['ID_BAY'];
	//============== insert bay ====================//
	
		//============== create cell ===================//
		$width = $jml_row+1;
		$height_on = $jml_tier_ondeck+1;
		$height_under = $jml_tier_underdeck+1;
		$height = $jml_tier_ondeck+$jml_tier_underdeck+3;
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
						$posisi_bay = "ABOVE";
					 }
					 else if($row3==(($jml_row+1)/2)) /*poros on deck*/
					 {
						$row_bay = 0;
						$tier_bay = 80+(($height_on-$hsl_cek)*2);
						$posisi_bay = "ABOVE";
					 }
					 else if (($row3>(($jml_row+1)/2))&&($row3<=($jml_row+1))) /*row ganjil*/
					 {
						if($d>$jml_row)
						{
							$y = $hsl_cek*10;
							$gnjl = $ganjil-$y;
							$row_bay = $gnjl;
							$tier_bay = 80+(($height_on-$hsl_cek)*2);
							$posisi_bay = "ABOVE";
						}
						else
						{
							$row_bay = $ganjil;
							$tier_bay = 80+(($height_on-$hsl_cek)*2);
							$posisi_bay = "ABOVE";
						}
						
						$ganjil+=2;
					 }
					 else if($row3==0) /*labeling*/
					 {
						$row_bay = 0;
						$tier_bay = 0;
						$posisi_bay = "ABOVE";
					 }
				 
				}
				else if(($d>($height_on*$width))&&($d<=(($height_on+1)*$width)))  /*posisi palka*/
				{
					 $row_bay = 0;
					 $tier_bay = 0;
					 $posisi_bay = "HATCH";
				}
				else if(($d>(($height_on+1)*$width))&&($d<=$luas)) /*under deck*/
				{
					  $row3 = $z;
					  if(($row3>=1)&&($row3<=((($jml_row+1)/2)-1))) /*row genap*/
					  {
						$row_bay = ($jml_row-1)-(($z-1)*2);
						$tier_bay = ($jml_tier_underdeck*2)-(($hsl_cek-($height_on+1))*2);
						$posisi_bay = "BELOW";
					  }
					  else if($row3==(($jml_row+1)/2)) /*poros under deck*/
					  {
						$row_bay = 0;
						$tier_bay = ($jml_tier_underdeck*2)-(($hsl_cek-($height_on+1))*2);
						$posisi_bay = "BELOW";
					  }
					  else if (($row3>(($jml_row+1)/2))&&($row3<=($jml_row+1))) /*row ganjil*/
					  {
						if($d>$jml_row)
						{
							$y = ($hsl_cek-1)*10;
							$gnjl = $ganjil-$y;
							$row_bay = $gnjl;
							$tier_bay = ($jml_tier_underdeck*2)-(($hsl_cek-($height_on+1))*2);
							$posisi_bay = "BELOW";
						}
						else
						{
							$row_bay = $ganjil;
							$tier_bay = ($jml_tier_underdeck*2)-(($hsl_cek-($height_on+1))*2);
							$posisi_bay = "BELOW";
						}
						
						$ganjil+=2;
					  }
					  else if($row3==0) /*labeling*/
					  {
						$row_bay = 0;
						$tier_bay = 0;
						$posisi_bay = "BELOW";
					  }
				  	
				}
				$index_cell = $d-1;
						
				$insert_cell = "INSERT INTO STW_BAY_CELL (ID_BAY_AREA,CELL_NUMBER,ROW_,TIER_,STATUS_STACK,POSISI_STACK) VALUES ('$bay_area','$index_cell','$row_bay','$tier_bay','N','$posisi_bay')";	
				$db->query($insert_cell);
		}	
		//============== create cell ===================//
		
		//============== create capacity ===================//
		$row_ganjil_on = 1;
		$row_ganjil_under = 1;
		for($g=1;$g<=$jml_row;$g++)
		{
			if(($g>=1)&&($g<=((($jml_row+1)/2)-1))) /*row genap*/
					 {				 
						$row_capacity_on = ($jml_row-1)-(($g-1)*2);
					 }
					 else if($g==(($jml_row+1)/2)) /*poros row*/
					 {
						$row_capacity_on = 0;
					 }
					 else if (($g>(($jml_row+1)/2))&&($g<=($jml_row+1))) /*row ganjil*/
					 {						
						$row_capacity_on = $row_ganjil_on;							
						$row_ganjil_on+=2;
					 }
					 
			$insert_capacity_on = "INSERT INTO STW_BAY_CAPACITY (ID_BAY_AREA,ROW_,POSISI_STACK) VALUES ('$bay_area','$row_capacity_on','ABOVE')";	
			$db->query($insert_capacity_on);
		}
		
		for($h=1;$h<=$jml_row;$h++)
		{
			if(($h>=1)&&($h<=((($jml_row+1)/2)-1))) /*row genap*/
					 {				 
						$row_capacity_under = ($jml_row-1)-(($h-1)*2);
					 }
					 else if($h==(($jml_row+1)/2)) /*poros row*/
					 {
						$row_capacity_under = 0;
					 }
					 else if (($h>(($jml_row+1)/2))&&($h<=($jml_row+1))) /*row ganjil*/
					 {						
						$row_capacity_under = $row_ganjil_under;							
						$row_ganjil_under+=2;
					 }
					 
			$insert_capacity_under = "INSERT INTO STW_BAY_CAPACITY (ID_BAY_AREA,ROW_,POSISI_STACK) VALUES ('$bay_area','$row_capacity_under','BELOW')";	
			$db->query($insert_capacity_under);
		}
		//============== create capacity ===================//
	}
	
	$insert_history = "INSERT INTO STW_HISTORY (ID_VS,STATUS,TGL_UPDATE,USER_UPDATE) VALUES ('$id_vs','BUAT PROFIL',SYSDATE,'$id_user')";
	$db->query($insert_history);
	
	$update_flag = "UPDATE RBM_H SET FLAG_PROFILE = 'Y' WHERE NO_UKK = '$id_vs'";
	$db->query($update_flag);
	
	
for ($i = 4; $i <= $data->sheets[0]['numRows']; $i++) {		
				
                $bay             = $data->sheets[0]['cells'][$i][6];			
				$get_area_bay    = "SELECT ID FROM STW_BAY_AREA WHERE BAY = '$bay' AND ID_VS = '$id_vs'";
				$result18 = $db->query($get_area_bay);
				$id_area  = $result18->fetchRow();
				$bay_area_id = $id_area['ID'];				
		
				$weight_on     = $data->sheets[0]['cells'][$i][7];
                $height_on     = $data->sheets[0]['cells'][$i][8];								
				
				if(($weight_on==0)&&($height_on==0))
				{
					$update_capacity_on = "UPDATE STW_BAY_CAPACITY SET WEIGHT_CAPACITY = '$weight_on', HEIGHT_CAPACITY = '$height_on', PLAN_WEIGHT = '$weight_on', PLAN_HEIGHT = '$height_on', REAL_WEIGHT = '$weight_on', REAL_HEIGHT = '$height_on' WHERE ID_BAY_AREA = '$bay_area_id' AND POSISI_STACK = 'ABOVE'";
					$db->query($update_capacity_on);
					
					$update_bay_area_on = "UPDATE STW_BAY_AREA SET ABOVE = 'NONE' WHERE ID = '$bay_area_id'";
					$db->query($update_bay_area_on);
				}
				else
				{
					$update_capacity_on = "UPDATE STW_BAY_CAPACITY SET WEIGHT_CAPACITY = '$weight_on', HEIGHT_CAPACITY = '$height_on', PLAN_WEIGHT = '$weight_on', PLAN_HEIGHT = '$height_on', REAL_WEIGHT = '$weight_on', REAL_HEIGHT = '$height_on' WHERE ID_BAY_AREA = '$bay_area_id' AND POSISI_STACK = 'ABOVE'";
					$db->query($update_capacity_on);
				}
				
                $weight_under  = $data->sheets[0]['cells'][$i][9];
                $height_under  = $data->sheets[0]['cells'][$i][10];

				if(($weight_under==0)&&($height_under==0))
				{
					$update_capacity_under = "UPDATE STW_BAY_CAPACITY SET WEIGHT_CAPACITY = '$weight_under', HEIGHT_CAPACITY = '$height_under', PLAN_WEIGHT = '$weight_under', PLAN_HEIGHT = '$height_under', REAL_WEIGHT = '$weight_under', REAL_HEIGHT = '$height_under' WHERE ID_BAY_AREA = '$bay_area_id' AND POSISI_STACK = 'BELOW'";
					$db->query($update_capacity_under);
					
					$update_bay_area_under = "UPDATE STW_BAY_AREA SET BELOW = 'NONE' WHERE ID = '$bay_area_id'";
					$db->query($update_bay_area_under);
				}
				else
				{
					$update_capacity_under = "UPDATE STW_BAY_CAPACITY SET WEIGHT_CAPACITY = '$weight_under', HEIGHT_CAPACITY = '$height_under', PLAN_WEIGHT = '$weight_under', PLAN_HEIGHT = '$height_under', REAL_WEIGHT = '$weight_under', REAL_HEIGHT = '$height_under' WHERE ID_BAY_AREA = '$bay_area_id' AND POSISI_STACK = 'BELOW'";
					$db->query($update_capacity_under);
				}              
                
}

 header('Location: '.HOME.'planning.profil/index');	
?>
