<?php
	include 'PHPExcel/Classes/PHPExcel/IOFactory.php';
	
	$path_files = $_SERVER['DOCUMENT_ROOT'] . 'html/modules/uster/report/export_copy_yard/uploads';

	$fileType = strtolower(pathinfo($_FILES["excel"]["name"],PATHINFO_EXTENSION));
	$target_file = $path_files . "/excel_copy_yard." . $fileType;
    $dataJson["message"] = "Success";

    if($_FILES["excel"]["size"] < 1 || !isset($_FILES["excel"])){
    	$dataJson["message"] = 'Please select file to upload';
		die(json_encode($dataJson));
    }
    //echo 

    if(strtolower($fileType) != "xls" && strtolower($fileType) != "xlsx"){
    	$dataJson["message"] = 'Wrong format file. Please select xls or xlsx file';
		die(json_encode($dataJson));
    }

    if (!file_exists($path_files)) {
    	echo $path_files;
		mkdir($path_files, 0777, true);	
        
    }


	if (move_uploaded_file($_FILES["excel"]["tmp_name"], $target_file)) {
        try {
		    $inputFileType = PHPExcel_IOFactory::identify($target_file);
		    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
		    $objPHPExcel = $objReader->load($target_file);
		} catch(Exception $e) {
			$dataJson["message"] = 'Error loading file "'.pathinfo($target_file,PATHINFO_BASENAME).'": '.$e->getMessage();
		    die(json_encode($dataJson));
		}

		$sheet = $objPHPExcel->getSheet(0); 
		$highestRow = $sheet->getHighestRow(); 
		$highestColumn = $sheet->getHighestColumn();

		$htmlInsert = "";
		$counter = 0;
		for ($row = 2; $row <= $highestRow; $row++){ 

		    $rowData = $sheet->rangeToArray('A' . $row . ':' . "I" . $row,
		                                    NULL,
		                                    TRUE,
		                                    FALSE);
		    if($counter == 0){
		    	$htmlInsert = $htmlInsert  . " SELECT '".$rowData[0][1]."','".$rowData[0][4]."','".$rowData[0][5]."','".$rowData[0][6]."','".$rowData[0][7]."','".$rowData[0][8]."' FROM DUAL ";
		    }
		    else{
		    	$htmlInsert = $htmlInsert  . " UNION ALL SELECT '".$rowData[0][1]."','".$rowData[0][4]."','".$rowData[0][5]."','".$rowData[0][6]."','".$rowData[0][7]."','".$rowData[0][8]."' FROM DUAL";
		    }
			$counter++;
		}
		if($counter > 0){

			$db = getDB("storage");
			$sql = "INSERT INTO TP_CONTAINER (CONT_NO,CONT_STATUS,BLOCK,SLOT,\"ROW\",TIER) ".$htmlInsert." ";
			$rs = $db->query($sql);

			if($rs == false){
				$dataJson["message"] = "Failed to insert data";
			}

		}
		else{
			$dataJson["message"] = "There is no data to insert";
			
		}
    } else {
    	$dataJson["message"] = "Sorry, there was an error uploading your file.";
		
    }
	
	die(json_encode($dataJson));
?>