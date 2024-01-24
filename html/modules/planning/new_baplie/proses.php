<?php
$db=getDb(dbint);
$id_vessel = $_GET['id_vessel'];

//Insert Header ke Tabel M_ETV_BAPLIE
$query_header = "declare begin proc_add_cont_baplie_header('$id_vessel'); end;";
$db->query($query_header);

//menggunakan class phpExcelReader
include "excel_reader2.php";


//membaca file excel yang diupload
$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);
//membaca jumlah baris dari data excel
$baris = $data->rowcount($sheet_index = 0);
//echo $baris;

//nilai awal counter jumlah data yang sukses dan yang gagal diimport
$sukses = 0;
$gagal = 0;

//Delete Data Di M_ETV_BAPLIE_CONT

$select_kapal = "SELECT 
						vessel, 
						voyage_in
				 FROM 
						m_vsb_voyage 
				 WHERE 
						id_vsb_voyage = $id_vessel";
						
$row_vessel = $db->query($select_kapal)->fetchRow();

$vesselnya = $row_vessel['VESSEL'];
$voyinnya = $row_vessel['VOYAGE_IN'];

$delete_cont = "delete from m_etv_bapliecont where vessel = '$vesselnya' and voyage_in='$voyinnya'";
$db->query($delete_cont);						

//import data excel dari baris kedua, karena baris pertama adalah nama kolom
for ($i = 2; $i <= $baris; $i++) {
       
    //membaca data nama depan (kolom ke-1)  (No Container)
    $discharge_port = $data->val($i, 1);    
    $load_port = $data->val($i, 2);        
    $bay = $data->val($i, 3);    
    $container = $data->val($i, 4);
	$iso_code = $data->val($i,5);
	$operator = $data->val($i,6);
	$f_m = $data->val($i,7);
	$weight = $data->val($i,8);    
    $temp = $data->val($i,9);
    $hz = $data->val($i,10);
    $un_no = $data->val($i,11);	
	$oheight = $data->val($i,12);
	$oleft = $data->val($i,13);
	$oright = $data->val($i,14);
	$ofront = $data->val($i,15);
	$oback = $data->val($i,16);	
	

$param_b_var= array(	
                            "v_disc_port"=>"$discharge_port",
                            "v_load_port"=>"$load_port",
                            "v_bay"=>"$bay",                            
                            "v_container"=>"$container",
							"v_iso_code"=>"$iso_code",
							"v_operator"=>"$operator",							
                            "v_fm"=>"$f_m",
							"v_weight"=>"$weight",
							"v_temp"=>"$temp",                            
                            "v_hz"=>"$hz",      
                            "v_unno"=>"$un_no",						
							"v_oheight"=>"$oheight",
                            "v_oleft"=>"$oleft",
                            "v_oright"=>"$oright",    
                            "v_ofront"=>"$ofront",
                            "v_oback"=>"$oback",							
                            "v_msg"=>"");
$query = "declare begin proc_add_cont_baplie('$id_vessel',
											:v_disc_port,
											:v_load_port,
											:v_bay,
                                            :v_container,
											:v_iso_code,
											:v_operator,
											:v_fm,
											:v_weight,
											:v_temp,											
											:v_hz,
											:v_unno,
											:v_oheight,
											:v_oleft,
											:v_oright,
											:v_ofront,
											:v_oback,											
											:v_msg); end;";         
$db->query($query,$param_b_var); 

    //echo $query;
    //print_r ($param_b_var);
    $msg = $param_b_var['v_msg'];	
    echo $msg;

}
//die();

header("location:../planning.new_baplie");
?>
