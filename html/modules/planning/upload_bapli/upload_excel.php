<?php
// Test CVS
//echo $_FILES[uploadfile];die;
echo $_POST['id_vs'];
echo $_POST['modus'];//ssecho 'dama';die;

require_once 'excel/reader.php';

$data = new Spreadsheet_Excel_Reader();

$data->setOutputEncoding('CP1251');

$data->read($_FILES['uploadfile']['tmp_name']);
error_reporting(E_ALL ^ E_NOTICE);
    
                //echo 'dama';die;
                $db   = getDB();
				$id_vs = $_POST['id_vs'];
				$id_user = $_SESSION["PENGGUNA_ID"];
                if ($_POST['modus'] == 'overwrite'){
                     $delete = "DELETE FROM UPLOAD_BAPLIE WHERE NO_UKK = '$id_vs'";
                     $db->query($delete);
                } 

for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
					$ID_BAPLIE 			= $data->sheets[0]['cells'][$i][1];
					$NO_UKK				= $data->sheets[0]['cells'][$i][2];
					$Discharge_Port 	= $data->sheets[0]['cells'][$i][3];
					$Load_port 			= $data->sheets[0]['cells'][$i][4];
					$Optional_Port 		= $data->sheets[0]['cells'][$i][5];
					$Bay 				= $data->sheets[0]['cells'][$i][6];
					$Slot 				= $data->sheets[0]['cells'][$i][7];
					$Container_Id 		= $data->sheets[0]['cells'][$i][8];
					$Size_ 				= $data->sheets[0]['cells'][$i][9];
					$Weight 			= $data->sheets[0]['cells'][$i][10];
					$Type_ 				= $data->sheets[0]['cells'][$i][11];
					$Group_Type 		= $data->sheets[0]['cells'][$i][12];
					$Loaded 			= $data->sheets[0]['cells'][$i][13];
					$Height 			= $data->sheets[0]['cells'][$i][14];
					$Carrier 			= $data->sheets[0]['cells'][$i][15];
					$Class_ 			= $data->sheets[0]['cells'][$i][16];
					$Setting 			= $data->sheets[0]['cells'][$i][17];
					$Delivery_Port 		= $data->sheets[0]['cells'][$i][18];
					$Commodity 			= $data->sheets[0]['cells'][$i][19];
					$Over_Height	    = $data->sheets[0]['cells'][$i][20];
					$Over_Size_Left	    = $data->sheets[0]['cells'][$i][21];
					$Over_Size_Right 	= $data->sheets[0]['cells'][$i][22];
					$Over_Size_Front 	= $data->sheets[0]['cells'][$i][23];
					$Over_Size_Aft 		= $data->sheets[0]['cells'][$i][24];
					$Handling_inst 		= $data->sheets[0]['cells'][$i][25];
					$Special_Instructions = $data->sheets[0]['cells'][$i][26];
					$Contr_load_remark  = $data->sheets[0]['cells'][$i][27];
					$Hazard_Code 		= $data->sheets[0]['cells'][$i][28];
					$IMDG_Page_No 		= $data->sheets[0]['cells'][$i][29];
					$UN_Number 			= $data->sheets[0]['cells'][$i][30];
					$Flash_Point 		= $data->sheets[0]['cells'][$i][31];
					$Measure_units 		= $data->sheets[0]['cells'][$i][32];
					$Packing_group 		= $data->sheets[0]['cells'][$i][33];
					$Emrg_schedule_no 	= $data->sheets[0]['cells'][$i][34];
					$Med_firstaid_guide = $data->sheets[0]['cells'][$i][35];
					$NoHazard_cardUpper = $data->sheets[0]['cells'][$i][36];
					$Hazard_card_Lower  = $data->sheets[0]['cells'][$i][37];
					$DG_label_1 		= $data->sheets[0]['cells'][$i][38];
					$DG_label_2 		= $data->sheets[0]['cells'][$i][39];
					$DG_addl_info 		= $data->sheets[0]['cells'][$i][40];
					$NET_Weight_of_DG 	= $data->sheets[0]['cells'][$i][41];
					$DG_Reference 		= $data->sheets[0]['cells'][$i][42];
					$DG_tech_name 		= $data->sheets[0]['cells'][$i][43];
					$Temp_Measure_units = $data->sheets[0]['cells'][$i][44];
					$Temp_Minimum_range = $data->sheets[0]['cells'][$i][45];
					$Maximum_range	    = $data->sheets[0]['cells'][$i][46];

                
                $db = getDB();

                  $query 	= "INSERT INTO UPLOAD_BAPLIE (
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
									Maximum_range)
                                ) VALUES (
                                '$id_vs',
								'$Discharge_Port',
								'$Load_port',
								'$Optional_Port',
								'$Bay',
								'$Slot',
								'$Container_Id',
								'$Size_',
								'$Weight',
								'$Type_',
								'$Group_Type',
								'$Loaded',
								'$Height',
								'$Carrier',
								'$Class_',
								'$Setting',
								'$Delivery_Port',
								'$Commodity',
								'$Over_Height',
								'$Over_Size_Left',
								'$Over_Size_Right',
								'$Over_Size_Front',
								'$Over_Size_Aft',
								'$Handling_inst',
								'$Special_Instructions',
								'$Contr_load_remark',
								'$Hazard_Code',
								'$IMDG_Page_No',
								'$UN_Number',
								'$Flash_Point',
								'$Measure_units',
								'$Packing_group',
								'$Emrg_schedule_no',
								'$Med_firstaid_guide',
								'$NoHazard_cardUpper',
								'$Hazard_card_Lower',
								'$DG_label_1',
								'$DG_label_2',
								'$DG_addl_info',
								'$NET_Weight_of_DG',
								'$DG_Reference',
								'$DG_tech_name',
								'$Temp_Measure_units',
								'$Temp_Minimum_range',
								'$Maximum_range'
                                )";
                $db->query($query);
                
}

 header('Location: '.HOME.'planning.upload_bapli/upload');	
?>




