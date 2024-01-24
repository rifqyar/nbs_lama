<?php
$id_nota=$_POST['IDN'];
$id_req=$_POST['IDR'];

$db=getDb('dbint');
$param_payment2= array(
					 "ID_NOTA"=>$id_nota,
					 "ID_REQ"=>$id_req,
					 "OUT"=>'',
					 "OUT_MSG"=>''
					);
$query2="declare begin payment_opusbill(:ID_REQ,:ID_NOTA,:OUT,:OUT_MSG); end;";

$db->query($query2,$param_payment2);

if($param_payment2["OUT"]=='S')
{
	echo "Sukses";
}
ELSE
{
	echo 'failed '.$param_payment2["OUT_MSG"];
}
?>