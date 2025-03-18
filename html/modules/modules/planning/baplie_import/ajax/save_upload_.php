 <?php
//echo $_POST['NO_UKK'];die;
 
if ($_FILES[uploadfile][size] > 0) {
    //get the csv file
    $file    = $_FILES[uploadfile][tmp_name];
    $handle  = fopen($file,"r");
	$id 	 = $_POST['NO_UKK'];
	$modus 	 = $_POST['modus'];
	$id_user = $_SESSION["ID_USER"];
	$user    = $_SESSION["USERNAME"];
	//$file_name ='uploads/csv/' . basename($_FILES[uploadfile][name]."_'".date("ymd_His")); 
    // echo $file_name;	
    //move_uploaded_file($_FILES[uploadfile][tmp_name], $file_name);
	$db = getDB();
	
	IF ($modus == 'overwrite') {
	
			$a = "DELETE FROM ISWS_LIST_CONTAINER
            WHERE NO_UKK = '$id' AND E_I = 'I'";	
			$db->query($a);
			
			$b = "DELETE FROM stw_placement_bay
            WHERE ID_VS = '$id' AND ACTIVITY = 'BONGKAR'";	
			$db->query($b);
			
			$c = " DELETE FROM stw_placement_bay
            WHERE ID_VS = '$id' AND ACTIVITY = 'IMPORT'";	
			$db->query($c);
			
			$d = "COMMIT;";	
			$db->query($d);
	}
				
	
    //loop through the csv file and"=>"sert"=>"to database
    $i = 0;
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        if($i>0) {
            	$pod 			= $data[0];
				$pol 			= $data[1];
                $opt_port    	= $data[2];
				$bay			= $data[3];
				$slot			= $data[4];
				$row 			= substr($data[4],-4,2);
				$tier 			= substr($data[4],-2,2);
				$cont			= str_replace(' ', '',$data[5]);
				$size			= $data[6];
				$weight			= $data[7];
				$type			= $data[8];
				$status			= $data[10];
				$height	     	= str_replace("'", "",$data[11]);
				$carrier		= $data[12];
				$class			= $data[13];
				$setting		= $data[14];
				$dev_port		= $data[15];
				$commo			= $data[16];
				$oh  			= $data[17];
				$hz	 			= $data[25];
				
				
					
		/*		$param_b_var= array("v_ukk"=>"$id",
								"v_id_user"=>"$id_user",	
								"v_modus"=>"$modus",	
								"v_pod"=>"$pod",
								"v_pol"=>"$pol",
								"v_bay"=>"$bay",
								"v_slotbp"=>"$slot",
								"v_container_no"=>"$cont",
								"v_container_size"=>"$size",
								"v_container_wg"=>"$weight",
								"v_container_type"=>"$type",
								"v_container_hg"=>"$height",
								"v_container_status"=>"$status",
								"v_delport"=>"$dev_port",
								"v_carrier"=>"$carrier",
								"v_user"=>"$user",
								"v_container_ic"=>"",
								"v_imo"=>"$class",
								"v_hz"=>"$hz",
								"v_rbp"=>"$row",
								"v_tbp"=>"$tier");
			$db=getdb();
			
			
		
			echo "begin 
						ISWS.upload_bapli_manual('$id',
												'$id_user',	
												'$modus',	
												'$pod',
												'$pol',
												'$bay',
												'$slot',
												'$cont',
												'$size',
												'$weight',
												'$type',
												'$height',
												'$status',
												'$dev_port',
												'$carrier',
												'$user',
												'',
												'$class',
												'$hz',
												'$row',
												'$tier') END;";die;*/
			$query="begin 
						ISWS.upload_bapli_manual('$id',
												'$id_user',	
												'$modus',	
												'$pod',
												'$pol',
												'$bay',
												'$slot',
												'$cont',
												'$size',
												'$weight',
												'$type',
												'$height',
												'$status',
												'$dev_port',
												'$carrier',
												'$user',
												'',
												'$class',
												'$hz',
												'$row',
												'$tier',
												'$setting');
	   			    end;";
			$db->query($query);
        }
        $i++;
    }
	
	fclose($handle);

    //redirect
	echo "data berhasil diupload";
     header('Location: '.HOME.'planning.baplie_import');	

} else {
	echo "file not found";
}
?>

