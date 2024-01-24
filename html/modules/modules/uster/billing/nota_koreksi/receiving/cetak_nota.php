<?php

$db		= getDB("storage");

$nota	= $_GET["n"];
$nipp	= $_SESSION["NIPP"];

if($nota == 999)
{
	
	$no_req	= $_GET["no_req"];
	//Membuat nomor nota
	$query_cek	= "SELECT COUNT(1) AS JUM, 
						  TO_CHAR(SYSDATE, 'MM') AS MONTH, 
						  TO_CHAR(SYSDATE, 'YY') AS YEAR 
				   FROM NOTA_RECEIVING 
				   WHERE TGL_NOTA BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) ";
	$result_cek	= $db->query($query_cek);
	$jum_		= $result_cek->fetchRow();
	$jum		= $jum_["JUM"]+1;
	$month		= $jum_["MONTH"];
	$year		= $jum_["YEAR"];
	
	$no_nota	= "NOTA"."05".$month.$year;
	
	//Membuat nomor Faktur
	$query_cek	= "SELECT LPAD(COUNT(1),6,0) AS JUM,  
						  TO_CHAR(SYSDATE, 'YY') AS YEAR 
				   FROM NOTA_RECEIVING 
				   WHERE TGL_NOTA BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) ";
	$result_cek	= $db->query($query_cek);
	$jum_		= $result_cek->fetchRow();
	$jum		= $jum_["JUM"]+1;
	$year		= $jum_["YEAR"];
        
	$no_faktur	= '010.010.-'.$year.$jum;
	
	//Insert ke tabel nota
	$query_insert_nota	= "INSERT INTO NOTA_RECEIVING(NO_NOTA, 
													  NO_FAKTUR,
													  NO_REQUEST, 
													  NIPP_USER, 
													  LUNAS, 
													  CETAK_NOTA, 
													  TGL_NOTA) 
											   VALUES(TO_CHAR(CONCAT ('$no_nota', LPAD('$jum',4,'0'))),
													  '$no_faktur',
											          '$no_req', 
													  '$nipp', 
													  'NO', 
													  0, 
													  SYSDATE)";	
	
	if($db->query($query_insert_nota))
	{
		$query		= "SELECT TO_CHAR(CONCAT ('$no_nota', LPAD('$jum',4,'0'))) AS NO_NOTA FROM DUAL";
		$res		= $db->query($query);
		$row		= $res->fetchRow();
		$no_nota_	= $row["NO_NOTA"]; 
		
		header('Location:'.HOME.APPID.'/print_nota?no_nota='.$no_nota_);		
	}
}

else
{
	$no_req	= $_GET["no_req"];
	$no_nota		= $_GET["no_nota"];
	$nota_			= $nota + 1;
	$query_update	= "UPDATE NOTA_RECEIVING SET CETAK_NOTA = '$nota_' WHERE NO_NOTA = '$no_nota'";
	
	if($db->query($query_update))
	{
		//echo HOME;
		header('Location:'.HOME.APPID.'/print_nota?no_nota='.$no_nota);		
	}	
}






?>