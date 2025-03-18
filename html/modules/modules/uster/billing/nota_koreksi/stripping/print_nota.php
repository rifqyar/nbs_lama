<?php
	
	$tl =  xliteTemplate('print_nota.htm');

	$db = getDB("storage");
	
	$no_nota	= $_GET["no_nota"];
        $no_req 	= $_GET["no_req"];
	
        if ($no_nota == 0){
            
            
        } else {
            $query_get	= "SELECT a.NO_NOTA, TO_CHAR(a.FORMULIR,'999,999,999,999') FORMULIR, a.LUNAS,a.NO_FAKTUR, TO_CHAR(a.TAGIHAN,'999,999,999,999') TAGIHAN, TO_CHAR(a.PPN,'999,999,999,999') PPN, TO_CHAR(a.TOTAL_TAGIHAN,'999,999,999,999') TOTAL_TAGIHAN, a.STATUS, b.NAMA, b.ALAMAT, b.NPWP, TO_CHAR(c.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_CHAR(c.TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY FROM nota_delivery a, request_delivery c, master_pbm b where
                            a.no_request = c.no_request and C.ID_PEMILIK = b.id
                            and a.no_nota = '$no_nota'";
            $result     = $db->query($query_get);
            $row_nota	= $result->getAll();

            $query_get2	= "SELECT * FROM nota_delivery_d a, ISO_CODE b WHERE a.ID_ISO = b.ID_ISO(+) AND ID_NOTA = '$no_nota' ORDER BY a. ID_DETAIL_NOTA ASC";
            $result	= $db->query($query_get2);
            $row_detail	= $result->getAll();
            
            $tl->assign("row_nota",$row_nota);
            $tl->assign("no_nota",$no_nota);
            $tl->assign("row_detail",$row_detail);
            
        }
	
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();


?>
