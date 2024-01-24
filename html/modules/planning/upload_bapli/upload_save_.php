 <?php 
echo $_GET['id'];die;
if ($_FILES[uploadfile][size] > 0) {
	echo $_GET['id'];die;
	//echo $_FILES['uploadfile']['name']."_'".date("ymd_His");die;
    //get the csv file
    $file = $_FILES[uploadfile][tmp_name];
    $handle = fopen($file,"r");
	$id_vs  = $_GET['id'];
	/*$info = pathinfo($_FILES[uploadfile][name]);
	echo $info; die;
	$dirfile = 'uploads/csv/'.$file."_'".date("ymd_His").'".'".$info['extension'];
	move_uploaded_file( $_FILES["uploadfile"]["tmp_name"] , $dirfile );*/
	
	$file_name ='./uploads/csv/' . basename($_FILES['uploadfile']['name']."_'".date("ymd_His")); 
   // echo $file_name;	
    move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file_name);
   // die;
    //loop through the csv file and insert into database
    do {
        if ($data[0]) {
            mysql_query("INSERT INTO UPLOAD_BAPLIE (
									NO_UKK,
									Discharge_Port,
									Load_port,
									Optional_Port,
									Bay,
									Slot,
									Container_Id,
									Size_,
									Weight,
									Type_,
									Group_Type,
									Loaded,
									Height,
									Carrier,
									Class_,
									Setting,
									Delivery_Port,
									Commodity,
									Over_Height,
									Over_Size_Left,
									Over_Size_Right,
									Over_Size_Front,
									Over_Size_Aft,
									Handling_inst,
									Special_Instructions,
									Contr_load_remark,
									Hazard_Code,
									IMDG_Page_No,
									UN_Number,
									Flash_Point,
									Measure_units,
									Packing_group,
									Emrg_schedule_no,
									Med_firstaid_guide,
									NoHazard_cardUpper,
									Hazard_card_Lower,
									DG_label_1,
									DG_label_2,
									DG_addl_info,
									NET_Weight_of_DG,
									DG_Reference,
									DG_tech_name,
									Temp_Measure_units,
									Temp_Minimum_range,
									Maximum_range
                                ) VALUES (
					'$id_vs',
                    '".addslashes($data[0])."',
					'".addslashes($data[1])."',
					'".addslashes($data[2])."',
					'".addslashes($data[3])."',
					'".addslashes($data[4])."',
					'".addslashes($data[5])."',
					'".addslashes($data[6])."',
					'".addslashes($data[7])."',
					'".addslashes($data[8])."',
					'".addslashes($data[9])."',
					'".addslashes($data[10])."',
					'".addslashes($data[11])."',
					'".addslashes($data[12])."',
					'".addslashes($data[13])."',
					'".addslashes($data[14])."',
					'".addslashes($data[15])."',
					'".addslashes($data[16])."',
					'".addslashes($data[17])."',
					'".addslashes($data[18])."',
					'".addslashes($data[19])."',
					'".addslashes($data[20])."',
					'".addslashes($data[21])."',
					'".addslashes($data[22])."',
					'".addslashes($data[23])."',
					'".addslashes($data[24])."',
					'".addslashes($data[25])."',
					'".addslashes($data[26])."',
					'".addslashes($data[27])."',
					'".addslashes($data[28])."',
					'".addslashes($data[29])."',
					'".addslashes($data[30])."',
					'".addslashes($data[31])."',
					'".addslashes($data[32])."',
					'".addslashes($data[33])."',
					'".addslashes($data[34])."',
					'".addslashes($data[35])."',
					'".addslashes($data[36])."',
					'".addslashes($data[37])."',
					'".addslashes($data[38])."',
					'".addslashes($data[39])."',
					'".addslashes($data[40])."',
					'".addslashes($data[41])."',
					'".addslashes($data[42])."',
					'".addslashes($data[43])."'
                )
            ");
        }
    } while ($data = fgetcsv($handle,1000,",","'"));
    //

    //redirect
     header('Location: '.HOME.'planning.upload_bapli/upload?id_vessel='$id_vs'');

}

?>

