<?php

//print_R("coba");die;
$tl = xliteTemplate('add.htm');
$no_req=$_POST['no_req'];
$tipe_req=$_POST['tipe_req'];
$emkl=$_POST['emkl'];
$alm=$_POST['alm'];
$old=$_POST['old_req'];
$npwp=$_POST['npwp'];
$vessel=$_POST['ves'];
$voyage=$_POST['voy'];
$tgl_start_stack=$_POST['tgl_st_stck'];
$tgl_end_stack=$_POST['tgl_en_stck'];
$tgl_ext_stack=$_POST['tgl_ext_stck'];
$tgl_sppb=$_POST['tgl_sppb'];
$tgl_sp2=$_POST['tgl_sp2'];

$db=getDB();
$query="INSERT INTO TB_REQ_DELIVERY_H (ID_REQ,TIPE_REQ,EMKL,ALAMAT,NPWP,VESSEL,VOYAGE,TGL_START_STACK,TGL_END_STACK,TGL_EXT,TGL_SPPB,TGL_SP2,OLD_REQ) 
		VALUES ('$no_req','$tipe_req','$emkl','$alm','$npwp','$vessel','$voyage',to_date('$tgl_start_stack','yyyy/mm/dd'),to_date('$tgl_end_stack','yyyy/mm/dd'),to_date('$tgl_ext_stack','yyyy/mm/dd'),to_date('$tgl_sppb','yyyy/mm/dd'),to_date('$tgl_sp2','yyyy/mm/dd'),'$old')";
$result_q=$db->query($query);
$tl->assign("var","save2");
$tl->assign("no_req",$no_req);
$tl->assign("old_req",$old_req);
$tl->assign("tipe_req",$tipe_req);
$tl->assign("emkl",$emkl);
$tl->assign("npwp",$npwp);
$tl->assign("ves",$vessel);
$tl->assign("voy",$voyage);
$tl->assign("alm",$alm);
$tl->assign("tgl_st",$tgl_start_stack);
$tl->assign("tgl_en",$tgl_end_stack);
$tl->assign("tgl_ext",$tgl_ext_stack);
$tl->assign("tgl_sppb",$tgl_sppb);
$tl->assign("tgl_sp2",$tgl_sp2);

$tl->renderToScreen();
?>