<?php
$tl = xliteTemplate('add.htm');
$id	  = $_GET["id"]; 
$nom = $_GET["nomor"]; 

$db=getDB();

$query="DELETE FROM TB_REQ_DELIVERY_D WHERE ID_REQ='$id' AND NO='$nom'";
$RES=$db->query($query);
//PRINT_R("SELECT * FROM TB_REQ_DELIVERY_H WHERE ID_REQ='$id'");die;
$query2="SELECT * FROM TB_REQ_DELIVERY_H WHERE ID_REQ='$id'";
$result=$db->query($query2);
$rowd=$result->getAll();
foreach($rowd as $row)
{
	$tl->assign("var","save2");
	$tl->assign("no_req",$row['ID_REQ']);
	$tl->assign("tipe_req",$row['TIPE_REQ']);
	$tl->assign("emkl",$row['EMKL']);
	$tl->assign("npwp",$row['NPWP']);
	$tl->assign("ves",$row['VESSEL']);
	$tl->assign("voy",$row['VOYAGE']);
	$tl->assign("alm",$row['ALAMAT']);
	$tl->assign("tgl_st",$row['TGL_START_STACK']);
	$tl->assign("tgl_en",$row['TGL_END_STACK']);
	$tl->assign("tgl_ext",$row['TGL_EXT']);
}
$tl->renderToScreen();


?>