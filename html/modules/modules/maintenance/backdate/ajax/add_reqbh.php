<?php
$user = $_SESSION["PENGGUNA_ID"];
$nota = $_POST['NOTA'];
$jenis = $_POST['TIPE'];
$ba  = $_POST['BA'];
$ket = $_POST['KET'];

if($nota=="")
	echo "NO";
else
{
	$db=getDB();	
	
	$sql_xpi="begin pack_backdate.proc_backdate('$nota','$jenis','$ba','$ket','$user'); end;";
	$db->query($sql_xpi);
	
	echo "OK";
}
?>