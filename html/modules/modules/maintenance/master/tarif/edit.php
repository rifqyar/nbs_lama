<?php
	$tl = xliteTemplate('form_edit.htm');
	$id = $_GET['id'];
	$jenis = $_GET['jenis'];
	$val = $_GET['val'];
	$db = getDB();
	$query = "SELECT ID_CONT,JENIS_BIAYA,TARIF,VAL,OI,TO_CHAR(START_PERIOD,'DD-MM-YYYY') START_PERIOD, TO_CHAR(END_PERIOD,'DD-MM-YYYY') END_PERIOD, B.UKURAN||' '||B.TYPE||' '||B.STATUS||' '||B.HEIGHT_CONT AS DESCR 
	FROM MASTER_TARIF_CONT A LEFT JOIN MASTER_BARANG B ON A.ID_CONT=B.KODE_BARANG WHERE ID_CONT = '$id' AND JENIS_BIAYA = '$jenis' AND VAL = '$val'";
	$result = $db->query($query);	
	$row = $result->fetchRow();
	$tl->assign('ubah',$row);
	$stat = "ubah" ;
    $tl->assign('status',$stat);
	$tl->renderToScreen(); 
?>