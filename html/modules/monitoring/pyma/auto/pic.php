<?php

//$nama			= strtoupper($_GET["term"]);
$queryw='';

$terminal		= $_POST["t"];
$keg			= $_POST["kg"];
$stk			= $_POST["sk"];
if($terminal=='NON TERMINAL')
{
	$terminal=9;
}

if($terminal<>''){
	if($queryw==''){
		$queryw="and a.STATUS_AKHIR<>'X' AND a.TERMINAL='".$terminal."'";
	}
	else
	{
		$queryw=$queryw." and a.TERMINAL='".$terminal."'";
	}
}
if($keg<>''){
	if($queryw==''){
		$queryw="and a.STATUS_AKHIR<>'X' AND a.KETERANGAN='".$keg."'";
	}
	else
	{
		$queryw=$queryw." and a.KETERANGAN='".$keg."'";
	}
}
if($stk<>''){
	if($queryw==''){
		$queryw="and a.STATUS_AKHIR<>'X' AND a.STATUS_AKHIR='".$stk."'";
	}
	else
	{
		$queryw=$queryw." and a.STATUS_AKHIR='".$stk."'";
	}
}

$db 			= getDB('pyma');
$query 			= "SELECT to_char(sum(a.jumlah),'999,999,999,999,999,999.00') AMT,count(1) as JML_R FROM rka_tab_exist a where a.user_id not in ('SUWARTONO','admin') ".$queryw;
//PRINT_R($query);die;

$result			= $db->query($query);
$row			= $result->fetchRow();	
$value=str_replace('','',$row[AMT]);
$jumlah=$row[JML_R];

//echo $query;
echo "<b>Jumlah Nota : <font color='red'>".$jumlah."</font><br>Amount Value : <font color='red'>Rp. ".$value;


?>