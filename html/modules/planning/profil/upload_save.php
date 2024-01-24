<?php
// Test CVS
//echo $_FILES['uploadfile']['tmp_name'];die;
$id_vs = $_GET['id_vs'];
$kd_vs = $_POST['kd_kapal'];
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

error_reporting(E_ALL ^ E_NOTICE);
    
                //  echo 'dama';die;
                $db = getDB();
				if ($_POST['modus'] == 'vs_assign')
				{
					$vs_assign = "begin vessel_assign('$id_vs','$kd_vs','$id_user'); end;";
					$db->query($vs_assign);
				}
				else
				{
					if ($_POST['modus'] == 'overwrite')
					{
						$overwrite = "begin overwrite_vesprofil('$id_vs'); end;";
						$db->query($overwrite);
					}
				
				//------- info bay Per vessel ---------//
                $jml_row    = $data->sheets[0]['cells'][4][2];
                $jml_tier_ondeck   = $data->sheets[0]['cells'][4][3];
                $jml_tier_underdeck = $data->sheets[0]['cells'][4][4];
				//------- info bay Per vessel ---------//

					
	for ($i = 4; $i <= $data->sheets[0]['numRows']; $i++)
	{
		$bay_no = $data->sheets[0]['cells'][$i][6];
		$weight_on = $data->sheets[0]['cells'][$i][7];
		$weight_under = $data->sheets[0]['cells'][$i][8];
		$occu = $data->sheets[0]['cells'][$i][9];
		
		if(($bay_no!="")&&($weight_on!=NULL)&&($weight_under!=NULL)&&($jml_row!=NULL)&&($jml_tier_ondeck!=NULL)&&($jml_tier_underdeck!=NULL))
		{	
			$upload_vesprofil = "begin upload_vesprofil('$id_vs','$bay_no','$jml_row','$jml_tier_underdeck','$jml_tier_ondeck','$weight_on','$weight_under','$occu'); end;";
			$db->query($upload_vesprofil);
		}			
	}
	
	$insert_history = "INSERT INTO STW_HISTORY (ID_VS,STATUS,TGL_UPDATE,USER_UPDATE) VALUES ('$id_vs','BUAT PROFIL',SYSDATE,'$id_user')";
	$db->query($insert_history);
	
	$update_flag = "UPDATE RBM_H SET FLAG_PROFILE = 'Y' WHERE NO_UKK = '$id_vs'";
	$db->query($update_flag);

}

 header('Location: '.HOME.'planning.profil/index');	
?>
