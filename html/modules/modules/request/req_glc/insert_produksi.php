<?php
 $u = $_GET['id'];
 $p = $_GET['detail'];
 $brg = $_GET['barang'];
 $tl = xliteTemplate('insert_produksi.htm');
 
 $db = getDB();
 
 if($brg=="container")
 {
	 $query="SELECT B.UKURAN,
					B.TYPE,
					B.STATUS,
					A.JUMLAH_CONT,
					A.ID
			 FROM GLC_PRODUKSI A, MASTER_BARANG B 
			 WHERE A.ID_CONT = B.KODE_BARANG 
			 AND A.ID_REQ = '$u'
			 AND A.ID_DETAILS = '$p'";
	 $result_d=$db->query($query);
	 $rowd=$result_d->getAll();
 }
 else
 {
	 $query="SELECT ID_CONT,
					JUMLAH_CONT,
					ID
			 FROM GLC_PRODUKSI 
			 WHERE ID_REQ = '$u'
			 AND ID_DETAILS = '$p'";
	 $result_d=$db->query($query);
	 $rowd=$result_d->getAll();
 }
 
 $tl->assign("list",$rowd); 
 $tl->assign("barang",$brg);
 $tl->assign("req",$u);
 $tl->assign("id_details",$p);
 $tl->assign("HOME",HOME);
 $tl->assign("APPID",APPID);
 $tl->renderToScreen();
?>

