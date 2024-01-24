<?php

$req=$_GET['id'];
$db=getDB();
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

$query2="UPDATE TB_REQ_DELIVERY_H SET EMKL='$emkl' ,ALAMAT='$alm', NPWP='$npwp',VESSEL='$vessel' ,
		 VOYAGE='$voyage', TGL_START_STACK=to_date('$tgl_start_stack','yyyy/mm/dd'),
		 TGL_END_STACK=to_date('$tgl_end_stack','yyyy/mm/dd'),
		 TGL_EXT=to_date('$tgl_ext_stack','yyyy/mm/dd'),
		 TGL_SPPB=to_date('$tgl_sppb','yyyy/mm/dd'),
		 TGL_SP2=to_date('$tgl_sp2','yyyy/mm/dd')
		 WHERE ID_REQ='$req'";
$db->query($query2);

$query5="SELECT ID_NOTA FROM TB_NOTA_DELIVERY_H WHERE ID_REQ='$req' AND STATUS=''";
$res=$db->query($query5);
$row=$res->fetchRow();

$query4="DELETE FROM TB_NOTA_DELIVERY_H WHERE ID_REQ='$req' AND STATUS=''";
$db->query($query4);

$query3="DELETE FROM TB_NOTA_DELIVERY_D WHERE ID_NOTA='$row[ID_NOTA]'";
$db->query($query3);

$query="DECLARE PID_REQ VARCHAR2(20); BEGIN PID_REQ := '$req'; MANUAL_PETIKEMAS.PERHITUNGAN_SPDUA(PID_REQ);	END;";
$db->query($query);
header('Location: '.HOME.'request.delivery/');
?>