<?php
$user = $_SESSION["NAMA_PENGGUNA"];
$tipe 		= $_POST['TIPE'];
$vessel 	= $_POST['VESSEL'];
$voyin 		= $_POST['VOYAGE_IN'];
$voyout 	= $_POST['VOYAGE_OUT'];
$no_ukk		= $_POST['NO_UKK'];
$npwp		= $_POST['NPWP'];
$custname	= $_POST['CUSTNAME'];
$custid		= $_POST['CUSTID'];
$custadd	= $_POST['CUSTADDR'];
$ket		= $_POST['KET'];
$biaya		= $_POST['BIAYA'];
$size_		= $_POST['SIZE_'];
$type_		= $_POST['TYPE_'];
$no_cont	= $_POST['NO_CONT'];
$status_cont= $_POST['STATUS']; 
$plugin     = $_POST['PLUGIN']; 
$plugout    = $_POST['PLUGOUT']; 
$oi= $_POST['OI'];  
$voyage		= $voyin.'/'.$voyout;
$typereq    = $_POST['TYPEREQ'];
$oldreq     = $_POST['OLDREQ'];
$reefertemp = $_POST['REEFER_TEMP'];

if($no_cont=="")
	echo "NO";
else
{
	
	$db=getDB();	
	
	//echo 'coba';
	
	$v_user=$_SESSION['ID_USER'];
	$v_ipaddr=$_SERVER['REMOTE_ADDR'];	
	//echo 'coba lagi';die;
	$param =array(	"in_nocont"=>"$no_cont",
                    "in_kdemkl"=>"$custid",
                    "in_vessel"=>"$vessel",
                    "in_voyin"=>"$voyin",
                    "in_voyout"=>"$voyout",
                    "in_idvsb"=>"$no_ukk",
                    "in_userid"=>"$v_user",
                    "in_shipping"=>"$oi",
                    "in_typereq"=>"$typereq",
                    "in_oldreq"=>"$oldreq",
                    "in_ei"=>"$tipe",
                    "in_ket"=>"$ket",
					"v_msg"=>"");
	
	//echo "declare begin proc_add_rena('$kd_barang','$booking_sl', '$tipe','$no_ex_cont','$size_','$type_','$status_cont','$vessel','$voyin','$voyout','$no_ukk','$custname','$custid','$custadd','$npwp','$ket','$biaya','$no_cont','$v_ipaddr','$v_user','$custom_no','','');end;";die;
	$sql="declare begin PROC_CREATE_REEFMON(:in_nocont,:in_kdemkl,:in_vessel,:in_voyin,:in_voyout,:in_idvsb,:in_userid,:in_shipping,:in_typereq,:in_oldreq,:in_ei,:in_ket,:v_msg);end;";
	$db->query($sql,$param);
	$msg = $param['v_msg'];
	echo $msg;
}
?>