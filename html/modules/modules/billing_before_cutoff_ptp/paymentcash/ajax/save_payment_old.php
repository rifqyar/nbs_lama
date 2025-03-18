<?php
$id_nota=$_POST['IDN'];
$id_req=$_POST['IDR'];
$jenis=$_POST['JENIS'];
$via=$_POST['VIA'];
$cms=$_POST['CMS'];
$vessel=$_POST['VESSEL'];
$voyin=$_POST['VOYIN'];
$voyout=$_POST['VOYOUT'];
$user=$_SESSION['PENGGUNA_ID'];
if ($jenis=='ANNE')
 $v_flag='REC';
else if ($jenis=='SP2'){
	if(substr($id_req, 0, 3) == 'SP2'){
		$v_flag='SEI';
	} else {
		$v_flag='DEL';
	}
}
else if ($jenis=='HICO')
 $v_flag='HICO';
else if ($jenis=='TRANS')
 $v_flag='TRS';
else if ($jenis=='BH')
 $v_flag='BHD';
else if ($jenis=='REEX')
 $v_flag='REX';
 
$db=getDb();

$db->startTransaction();
$param_payment= array(
					 "ID_NOTA"=>$id_nota,
					 "ID_REQ"=>$id_req,
					 "JENIS"=>$jenis,
					 "VIA"=>$via,
					 "CMS"=>$cms,
					 "USER"=>$user,
					 "OUT"=>'',
					 "OUT_MSG"=>'',
					 "VESSEL"=>$vessel,
					 "VOYIN"=>$voyin,
					 "VOYOUT"=>$voyout
					);
//print_r($param_payment);die; 
//$query="declare begin pack_payment.proc_payment('$id_nota','$id_req','$jenis','$via','$cms','$user','$out','$out_msg'); end;";
//echo $query;die;
$query="declare begin pack_payment.proc_payment(:ID_NOTA,:ID_REQ,:JENIS,:VIA,:CMS,:USER,:VESSEL,:VOYIN,:VOYOUT,:OUT,:OUT_MSG); end;";
//print_r($param_payment);
//die();

$db->query($query,$param_payment);

$db=getDb();
$db->startTransaction();
$param_payment2= array(
					 "ID_NOTA"=>$id_nota,
					 "ID_REQ"=>$id_req,
					 "FLAG"=>$v_flag,
					 "OUT"=>'',
					 "OUT_MSG"=>''
					);
//print_r($param_payment2);die; 
$query="declare begin pack_payment.proc_payment('$id_nota','$id_req','$jenis','$via','$cms','$user','$out','$out_msg'); end;";

$query="declare begin payment_opus(:ID_REQ,:ID_NOTA,:FLAG,:OUT,:OUT_MSG); end;";
//echo $query;die;

$db->query($query,$param_payment2);


if ($db->endTransaction()) {
	if(($param_payment2["OUT"]=='OK') && ($param_payment["OUT"]=='S'))
		echo "sukses";
	else
		echo "failed - ".$param_payment["OUT"]." - ".$param_payment2["OUT"];
}
?>