<?php
	$nota=$_POST['id'];
	$req=$_POST['req'];
	$jenis=$_POST['jn'];
	
	$user=$_SESSION['PENGGUNA_ID'];

	$db=getDb();
	$param_payment= array(
					 "ID_NOTA"=>$nota,
					 "ID_REQ"=>$req,
					 "JENIS"=>$jenis,
					 "USER"=>$user,
					 "OUT"=>'',
					 "OUT_MSG"=>''
					);
 //$query="declare begin proc_transfer_invoice('$nota','$req','$jenis','$user','',''); end;";
 //print_r($query);die;
 /*
	declare 
v_out varchar2(1000);
v_outm varchar2(1000);
begin proc_transfer_invoice('010.100-13.65.000001','BHD201306000001','BH','81',v_out,v_outm); 
dbms_output.put_line(v_out);
dbms_output.put_line(v_outm);
end;
 */
$query="declare begin proc_transfer_invoice(:ID_NOTA,:ID_REQ,:JENIS,:USER,:OUT,:OUT_MSG); end;";
$db->query($query,$param_payment);
ECHO $param_payment["OUT"];
/*if ($db->endTransaction()) {
	if($param_payment["OUT"]=='S')
		echo "sukses";
	else
		echo "failed";
}*/
?>