<?php

$db		= getDB("storage");

$nota	 = $_GET["n"];
$nipp	 = $_SESSION["NIPP"];
$no_req	 = $_GET["no_req"];
$no_nota = $_GET["no_nota"];

if($nota == 999)
{
	
	$no_req     = $_GET["no_req"];
	$query_cek	= "SELECT lpad((COUNT(1)+1),6,0) AS JUM, TO_CHAR(SYSDATE, 'MM') AS MONTH, TO_CHAR(SYSDATE, 'YY') AS YEAR FROM NOTA_STRIPPING WHERE TGL_NOTA BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) ";
	$result_cek	= $db->query($query_cek);
	$jum_		= $result_cek->fetchRow();
	$jum		= $jum_["JUM"];
	$month		= $jum_["MONTH"];
	$year		= $jum_["YEAR"];
        
    $no_nota	= "STR05".$month.$year.$jum;
	
    $query_cek	= "SELECT lpad(COUNT(1),6,0) AS JUM,  TO_CHAR(SYSDATE, 'YY') AS YEAR FROM NOTA_STRIPPING WHERE TGL_NOTA BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) ";
	$result_cek	= $db->query($query_cek);
	$jum_		= $result_cek->fetchRow();
	$jum		= $jum_["JUM"];
	$year		= $jum_["YEAR"];
	$no_faktur	= '010.010.-'.$year.'.'.$jum;

	$query_insert_nota	= "INSERT INTO NOTA_STRIPPING(NO_NOTA, NO_FAKTUR, NO_REQUEST, NIPP_USER, LUNAS, CETAK_NOTA, TGL_NOTA) VALUES ('$no_nota', '$no_faktur', '$no_req', '$nipp', 'NO', 1, SYSDATE)";	

	if($db->query($query_insert_nota))
	{
		header('Location:'.HOME.APPID.'/print_nota?no_nota='.$no_nota);
				
	}
}
else
{
	$no_nota		= $_GET["no_nota"];
	$nota_			= $nota + 1;
	$query_update           = "UPDATE NOTA_STRIPPING SET CETAK_NOTA = '$nota_' WHERE NO_NOTA = '$no_nota'";
	//echo $query_update;die;
	if($db->query($query_update))
	{
		//echo HOME;
		header('Location:'.HOME.APPID.'/print_nota?no_nota='.$no_nota);		
	}	
}






?>