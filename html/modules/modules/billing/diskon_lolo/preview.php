<?php
	//header('Location: '.HOME .'static/error.htm');			
	$no_req = $_GET['no_req'];
	$status = $_GET['status'];
	//print_r($status);die;
	
	$db=getDb();
	if($status=='BA')
	{
		$tl = xliteTemplate('preview_ba.htm');
		$qh = "select SZ,
					  TY,
					  ST,
					  count(NO_CONTAINER) as JUM_CONT,
					  KETERANGAN,
					  to_char(sum(TARIF), '999,999,999.99') TARIF,
					  to_char(sum(TARIF_5_PERSEN), '999,999,999.99') TARIF_5_PERSEN,
					  to_char(sum(PPN_5_PERSEN), '999,999,999.99') PPN_5_PERSEN,
					  to_char(sum(TARIF_5_PERSEN+PPN_5_PERSEN), '999,999,999.99') TTL_INSENTIF
			   from DISKON_NOTA_DEL_DTL 
			   where KD_PERMINTAAN = '$no_req'
			   group by SZ,TY,ST,KETERANGAN,TARIF,TARIF_5_PERSEN,PPN_5_PERSEN";
		$res=$db->query($qh);
		$rd=$res->getAll();
	}
	else
	{
		$tl = xliteTemplate('preview_rekap.htm');		
	}
	
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);	
	$tl->assign("req",$no_req);
	$tl->assign("row_detail",$rd);
	$tl->renderToScreen();
?>
