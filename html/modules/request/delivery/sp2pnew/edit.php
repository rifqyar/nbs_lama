<?php
$tl = xliteTemplate('edit.htm');
//$id=$_GET['id'];
$id=$_GET['no_req'];

$db=getDB();
$query="SELECT 
				A.*,TO_CHAR(A.DISCH_DATE,'DD-MM-YYYY') TGL_BONGKAR,
				TO_CHAR(A.TGL_SPPB,'DD-MM-YYYY') SPPB_TGL,    
				TO_CHAR(A.TGL_DO,'DD-MM-YYYY') DO_TGL, 
				TO_CHAR(A.TGL_SP2,'DD-MM-YYYY') SP2_TGL, 
				COA
		FROM 
				REQ_DELIVERY_H A WHERE A.ID_REQ='$id'";
// echo $query; die;
$result_q=$db->query($query);
$row=$result_q->fetchRow();

$tl->assign("detail",$row);
$tl->assign("no",$id);
$tl->assign("tgl",date('d-m-Y H:i'));
$tl->renderToScreen();
?>