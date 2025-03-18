<?php
$tl = xliteTemplate('add.htm');
$id	  = $_GET["id"]; 
$nom = $_GET["nomor"]; 

$db=getDB();

$query="DELETE FROM BH_DETAIL_REQUEST WHERE ID_REQUEST='$id' AND NO_CONTAINER='$nom'";
$RES=$db->query($query);
$query2="SELECT * FROM BH_REQUEST WHERE ID_REQUEST='$id'";
$result=$db->query($query2);
$rowd=$result->getAll();
foreach($rowd as $row)
{
	$tl->assign("var","save2");
	$tl->assign("no_req",$row['ID_REQUEST']);
	$tl->assign("tipe_req",$row['TIPE_REQ']);
	$tl->assign("emkl",$row['EMKL']);
	$tl->assign("npwp",$row['NPWP']);
	$tl->assign("ves",$row['VESSEL']);
	$tl->assign("voy",$row['VOYAGE']);
	$tl->assign("alm",$row['ALAMAT']);
	$tl->assign("ship_line",$row['SHIP_LINE']);
	$tl->assign("ket",$row['KET']);
	$tl->assign("no_bc",$row['NOMOR_INSTRUKSI']);
}
$tl->renderToScreen();


?>