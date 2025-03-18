 <?php
//echo $_POST['NO_UKK'];die;
 
if ($_FILES[uploadfile][size] > 0) {
    //get the csv file
    $file    = $_FILES[uploadfile][tmp_name];
    $handle  = fopen($file,"r");
	$id 	 = $_POST['NO_UKK'];
	$ves 	 = $_POST['ves'];
	$vi 	 = $_POST['vi'];
	$vo 	 = $_POST['vo'];
	$vsc 	 = $_POST['vsc'];
	$csg 	 = $_POST['csg'];
	$opid 	 = $_POST['opid'];
	$opnm 	 = $_POST['opnm'];
	
	
	$modus 	 = $_POST['modus'];
	$id_user = $_SESSION["ID_USER"];
	$user    = $_SESSION["NAMA_PENGGUNA"];
	//$file_name ='uploads/csv/' . basename($_FILES[uploadfile][name]."_'".date("ymd_His")); 
    // echo $file_name;	
    //move_uploaded_file($_FILES[uploadfile][tmp_name], $file_name);
	
	
	IF ($modus == 'overwrite') {
		$db = getDB();
		$a = "begin prc_header_baplie('$id', '$ves', '$vi','$vo'); end;";	
		$db->query($a);
		$d = "COMMIT";	
		$db->query($d);
	}
	$db = getDB('dbint');
	//print_r('coba');die;
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
				$group_type		= $data[9];
				$status			= $data[10];
				$height	     	= str_replace("'", ".",$data[11]);
				$carrier		= $data[12];
				$class			= $data[13];
				$setting		= $data[14];
				$dev_port		= $data[15];
				$commo			= $data[16];
				$oh  			= $data[17];				
				$find = array("CM","CMT");				
				$os_left		= str_replace($find,'',$data[18]);
				$os_right		= str_replace($find,'',$data[19]);
				$ow             = $os_left+$os_right;
				$os_bfr         = str_replace($find,'',$data[20]);
				$os_aft         = str_replace($find,'',$data[21]);
				$ol             = $os_bfr+$os_aft;
				$handling       = $data[22];
				$spc_inst       = $data[23];
				$con_loading_remark  = $data[24];
				if($data[25]!=''){
					$hz='Y';
				}
				else
				{
					$hz='N';
				}
				//$hz	 			= $data[25];
				$imdg	 			= $data[26];
				$un_number	    = $data[27];
				$oi	    = $data[45];
				
				
	
			$query="begin upload_bapli_manual('$id',
												'$id_user',	
												'$modus',	
												'$pod',
												'$pol',
												'$opt_port',
												'$bay',
												'$slot',
												'$cont',
												'$size',
												'$weight',
												'$type',
												'$group_type',
												'$status',
												'$height',
												'$carrier',
												'$class',
												'$setting',
												'$dev_port',
												'$commo',
												'$oh',
												'$olf',
												'$org',
												'$obf',
												'$oaf',
												'$handling',
												'$spc_inst',
												'$con_loading_remark',
												'$hz',
												'$imdg',
												'$un_number',
												'$user',
												'$ol',
												'$ow'
												);  end;";
					
			//print_r($query);die;
			$db->query($query);
        }
        $i++;
    }
	
	fclose($handle);

    //redirect
	echo "data berhasil diupload";
     //header('Location: '.HOME.'planning.baplie_import');	

} else {
	echo "file not found";
}
?>

