<?php
$id_nota=$_POST['IDN'];
$id_req=$_POST['IDR'];
$jenis=$_POST['JENIS'];
$via=$_POST['VIA'];
$cms=$_POST['CMS'];
$kd_pelunasan=$_POST['KD_PELUNASAN'];
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
else if ($jenis=='RXP')
 $v_flag='RXP';
else if ($jenis=='MONREEF')
 $v_flag='MONREEF';

//echo 'jenisnya: '.$jenis;die;
if ($jenis!='RNM' && $jenis !='MONREEF') {
	$db=getDb('dbint');
	$param_payment2= array(
						 "ID_NOTA"=>$id_nota,
						 "ID_REQ"=>$id_req,
						 "OUT"=>'',
						 "OUT_MSG"=>''
						);
	$query2="declare begin payment_opusbill(:ID_REQ,:ID_NOTA,:OUT,:OUT_MSG); end;";

	$db->query($query2,$param_payment2);
}
else
{
	$param_payment2["OUT"]='S';
}

if($param_payment2["OUT"]=='S')
{
	$db=getDb();
	$q_valid = "SELECT COUNT(NO_NOTA) JUM FROM TTH_NOTA_ALL2 WHERE NO_NOTA = '$id_nota'";
	$r_valid = $db->query($q_valid);
	$rw_val  = $r_valid->fetchRow();
	if ($rw_val['JUM'] == 0) {		
		$param_payment= array(
						 "ID_NOTA"=>$id_nota,
						 "ID_REQ"=>$id_req,
						 "JENIS"=>$jenis,
						 "VIA"=>$via,
						 "KD_PELUNASAN"=>$kd_pelunasan,
						 "USER"=>$user,
						 "OUT"=>'',
						 "OUT_MSG"=>'',
						 "VESSEL"=>$vessel,
						 "VOYIN"=>$voyin,
						 "VOYOUT"=>$voyout
						);
		$query="declare begin pack_payment_new_ipctpk.proc_payment_new(:ID_NOTA,:ID_REQ,:JENIS,:VIA,:KD_PELUNASAN,:USER,:VESSEL,:VOYIN,:VOYOUT,:OUT,:OUT_MSG); end;";
		//echo($query);
                //print_r($param_payment);
                //die();
                $db->query($query,$param_payment);
	}
	echo "Sukses";
}
ELSE
{
	echo 'failed '.$param_payment2["OUT_MSG"];
}


?>