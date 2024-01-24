<?php

//$query = "declare begin proc_add_cont_empty(:v_nc,:v_req,:v_type,:v_carrier,:v_iso_code,:v_height, :v_size); end;";

//menggunakan class phpExcelReader
include "excel_reader2.php";
$db=getDb();

//membaca file excel yang diupload
$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);
//membaca jumlah baris dari data excel
$baris = $data->rowcount($sheet_index = 0);
//echo $baris;

//nilai awal counter jumlah data yang sukses dan yang gagal diimport
$sukses = 0;
$gagal = 0;

//import data excel dari baris kedua, karena baris pertama adalah nama kolom
for ($i = 2; $i <= $baris; $i++) {
    //echo("ivan ganteng");
    
    //membaca data nama depan (kolom ke-1)  (No Container)
    $no_container = $data->val($i, 1);    
    $type = $data->val($i, 2);        
    $carrier = $data->val($i, 3);        
    $iso_code = $data->val($i, 4);    
    $height = $data->val($i, 5);    
    $size = $data->val($i, 6);
    $weightnpe = $data->val($i,7);
    
    
    $req = $_GET['req'];        
    $status			= "MTY"; //empty
    $hz             = "T"; // T    
    $comm			= "";
    $imo			= "";     
    $book			= "";    
    $ship			= "O";    
    $tmp			= "";
    $oh             = "";
    $ow                         = "";
    $ol                         = "";
    $unnumber			= "";
    //$weightnpe                  ="";
    $nor = "N";

$param_b_var= array(	
							"v_nc"=>"$no_container",
                                                        "v_req"=>"$req",
                                                        "v_stc"=>"$status",
                                                        "v_hc"=>"$hz",
                                                        "v_sc"=>"$size",        
							"v_tc"=>"$type",
                                                        "v_comm"=>"$comm",
                                                        "v_imo"=>"$imo",
                                                        "v_iso"=>"$iso_code",
                                                        "v_book"=>"$book",
                                                        "v_hgc"=>"$height", 
                                                        "v_ship"=>"$ship",        
                                                        "v_car"=>"$carrier",
                                                        "v_tmp"=>"$tmp",
                                                        "v_oh"=>"$oh",
							"v_ow"=>"$ow",
							"v_ol"=>"$ol",
							"v_un"=>"$unnumber",
                                                        "v_nor"=>"$nor",
                                                        "v_weightnpe"=>"$weightnpe", 
                                                        "v_msg"=>""                                                                                                                                                                            
							);
    
    $query = "declare begin proc_add_cont_anne(:v_nc,:v_req,:v_sc,:v_tc,:v_stc,:v_hc,:v_comm,:v_imo,:v_iso,:v_book,:v_hgc,:v_ship,:v_car,:v_tmp,:v_oh, :v_ow, :v_ol,:v_un,:v_nor,:v_weightnpe,:v_msg); end;";         
    $db->query($query,$param_b_var);       
    
    //echo $query;
    //print_r ($param_b_var);
    $msg = $param_b_var['v_msg'];	
    echo $msg;
    
    
    
}

header("location:../request.anne/edit_req?no_req=$req");



?>
