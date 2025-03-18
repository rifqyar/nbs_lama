<?php
$tl = xliteTemplate('edit.htm');
//$id=$_GET['id'];
$id=$_GET['no_req'];

$db=getDB();
if(substr($id,0,1)=='S'){
	$query="SELECT * from REQ_DELIVERY_H WHERE ID_REQ='$id'";
} else {
	$query="SELECT   ID_BATALMUAT    ,
  KODE_PBM        ,
  JENIS           ,
  VESSEL          ,
  VOYAGE AS VOYAGE_IN,
  'O' SHIP,
  TGL_REQ         ,
  PENGGUNA        ,
  STATUS          ,
  ID_DETAIL       ,
  TO_CHAR(TGL_BERANGKAT2, 'dd-mm-yyyy') AS TGL_SP2 ,
  EMKL            ,
  ALAMAT          ,
  NPWP            ,
  SHIPPING_LINE   ,
  KET AS KETERANGAN,
  'TRUCK' AS DEV_VIA,
  CUSTOM_NUMBER   ,
  VOYAGE_OUT      ,
  NPE             ,
  PEB             ,
  BOOKING_NUMB    ,
  FPOD            ,
  ID_FPOD          from TB_BATALMUAT_H WHERE ID_BATALMUAT='$id'";
}
// echo $query; die;
$result_q=$db->query($query);
$row=$result_q->fetchRow();

$tl->assign("detail",$row);
$tl->assign("no",$id);
$tl->assign("tgl",date('d-m-Y H:i'));
$tl->renderToScreen();
?>