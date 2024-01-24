<?php
$db = getDB();

$company_address = $_POST['COMPANY_ADDRESS'];
$company_name = $_POST['COMPANY_NAME'];
$company_phone = $_POST['COMPANY_PHONE'];
$email = $_POST['EMAIL'];
$expired_kiu = $_POST['EXPIRED_KIU'];
$expired_stnk = $_POST['EXPIRED_STNK'];
$kiu = $_POST['KIU'];
$no_stnk = $_POST['NO_STNK'];
$registrant_name = $_POST['REGISTRANT_NAME'];
$registrant_phone = $_POST['REGISTRANT_PHONE'];
$truck_number = $_POST['TRUCK_NUMBER'];

//query cek tabel master container

$param_b_var = array(
    "v_company_address" => "$company_address",
    "v_company_name" => "$company_name",
    "v_company_phone" => "$company_phone",
    "v_email" => "$email",
    "v_expired_kiu" => "$expired_kiu",
    "v_expired_stnk" => "$expired_stnk",
    "v_kiu" => "$kiu",
    "v_no_stnk" => "$no_stnk",
    "v_registrant_name" => "$registrant_name",
    "v_registrant_phone" => "$registrant_phone",
    "v_truck_number" => "$truck_number",
	"v_user_entry" => $_SESSION['NAMA_PENGGUNA'],
    "v_out" => "",
	"v_out_msg" => ""
);

$query = "declare begin proc_add_tid(:v_registrant_name,:v_registrant_phone,:v_company_name, :v_company_phone, :v_company_address,:v_email,:v_kiu,:v_expired_kiu,:v_truck_number,:v_no_stnk,:v_expired_stnk,:v_user_entry,:v_out,:v_out_msg); end;";

$db->query($query, $param_b_var);
$msg = $param_b_var['v_out'];
//echo ($query);
echo $msg;


?>