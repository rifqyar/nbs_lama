<?php
$user = $_SESSION["NAMA_PENGGUNA"];
$tipe 		= $_POST['TIPE'];
$vessel 	= $_POST['VESSEL'];
$voyin 		= $_POST['VOYAGE_IN'];
$voyout 	= $_POST['VOYAGE_OUT'];
$no_ukk		= $_POST['NO_UKK'];
$carrier 	= $_POST['CARRIER'];
$pod		= $_POST['POD'];
$fpod		= $_POST['FPOD'];
$pol 		= $_POST['POL'];
$custom_no	= $_POST['CUSTNO'];
$booking_sl	= $_POST['BOOKING_SL'];
$npwp		= $_POST['NPWP'];
$custname	= $_POST['CUSTNAME'];
$custid		= $_POST['CUSTID'];
$custadd	= $_POST['CUSTADDR'];
$ket		= $_POST['KET'];
$biaya		= $_POST['BIAYA'];
$size_		= $_POST['SIZE_'];
$type_		= $_POST['TYPE_'];
$no_cont	= $_POST['NO_CONT'];
$no_ex_cont	= $_POST['NO_EX_CONT'];
$status_cont= $_POST['STATUS']; 
$kd_barang= $_POST['KD_BARANG'];
$oi= $_POST['OI'];  
$voyage		= $voyin.'/'.$voyout;

if($no_cont=="")
	echo "NO";
else
{
	
	$db=getDB();	
	
	//echo 'coba';
	
	$v_user=$_SESSION['ID_USER'];
	$v_ipaddr=$_SERVER['REMOTE_ADDR'];	
	//echo 'coba lagi';die;
	$param =array("v_kd_barang"=>"$kd_barang",
					"v_booking_sl"=>"$booking_sl",
					"v_tipe"=>"$tipe",
					"v_no_ex_ct"=>"$no_ex_cont",
					"v_size"=>"$size_",
					"v_type"=>"$type_",
					"v_status"=>"$status_cont",
					"v_vessel"=>"$vessel",
					"v_voyagein"=>"$voyin",
					"v_voyageout"=>"$voyout",
					"v_ukk"=>"$no_ukk",
					"v_custname"=>"$custname",
					"v_custid"=>"$custid",
					"v_custadd"=>"$custadd",
					"v_npwp"=>"$npwp",
					"v_remarks"=>"$ket",
					"v_biaya"=>"$biaya",
					"v_nc"=>"$no_cont",
					"v_ipaddr"=>"$v_ipaddr",
					"v_user"=>"$v_user",
					"v_custno"=>"$custom_no",
					"v_pod"=>"$pod",
					"v_fpod"=>"$fpod",
					"v_oi"=>"$oi",
					"v_msg"=>"",
					"v_response"=>"");
	
	//echo "declare begin proc_add_rena('$kd_barang','$booking_sl', '$tipe','$no_ex_cont','$size_','$type_','$status_cont','$vessel','$voyin','$voyout','$no_ukk','$custname','$custid','$custadd','$npwp','$ket','$biaya','$no_cont','$v_ipaddr','$v_user','$custom_no','','');end;";die;
	$sql="declare begin proc_add_rena(:v_kd_barang,:v_booking_sl, :v_tipe,:v_no_ex_ct,:v_size,:v_type,:v_status,:v_vessel,:v_voyagein,:v_voyageout,:v_ukk,:v_custname,:v_custid,:v_custadd,:v_npwp,:v_remarks,:v_biaya,:v_nc,:v_ipaddr,:v_user,:v_custno,:v_pod,:v_fpod,:v_oi,:v_msg,:v_response);end;";
	$db->query($sql,$param);
	$resp = $param['v_response'];	
	$msg = $param['v_msg'];
	echo "OK";
}
?>