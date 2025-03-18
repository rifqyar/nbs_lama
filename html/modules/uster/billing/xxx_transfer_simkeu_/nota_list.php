<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('nota_list.htm');
	
	$tgl_awal	= $_POST["tgl_awal"]; 
	$tgl_akhir	= $_POST["tgl_akhir"]; 
	$jenis		= $_POST["jenis"];
	//echo $tgl_awal;die;
	$db 	= getDB("storage");

	/* $query_list1 	= "SELECT TO_CHAR(TO_DATE('$tgl_awal','yyyy/mm/dd'),'dd/mon/yy') tgl_awal,  TO_CHAR(TO_DATE('$tgl_akhir','yyyy/mm/dd'),'dd/mon/yy') tgl_akhir  FROM dual";
	$result_list1	= $db->query($query_list1);
	$row_list1		= $result_list1->fetchRow(); 
	$tgl_awal_ 		= $row_list1['TGL_AWAL'];
	$tgl_akhir_		= $row_list1['TGL_AKHIR'];
	
	$query1 = " DECLARE
				tgl_awal DATE;
				tgl_akhir DATE;
				BEGIN
					tgl_awal := '$tgl_awal_';
					tgl_akhir := '$tgl_akhir_';
					TRANSFER_NOTA(tgl_awal,tgl_akhir);
			   END;";

	$db->query($query1);
	
	$query_list_ 	= "SELECT NO_NOTA, NO_REQUEST, TO_CHAR(TOTAL_TAGIHAN, '999,999,999,999') TOTAL_TAGIHAN, NM_EMKL, NAMA_MODUL, TGL_KEGIATAN FROM TRANSFER_SIMKEU GROUP BY  NO_NOTA, NO_REQUEST, TOTAL_TAGIHAN, NM_EMKL, NAMA_MODUL, TGL_KEGIATAN";
	$result_list_	= $db->query($query_list_);
	$row_list		= $result_list_->getAll();  */
	$query_list_ 	= "SELECT * FROM (
					SELECT NO_NOTA, NOTA_DELIVERY.NO_REQUEST, TRUNC(TGL_NOTA) TGL_NOTA, 'DELIVERY'  AS KEGIATAN, EMKL, TO_CHAR(TOTAL_TAGIHAN, '999,999,999,999') TOTAL_TAGIHAN, DELIVERY_KE ASAL
					FROM NOTA_DELIVERY INNER JOIN REQUEST_DELIVERY ON NOTA_DELIVERY.NO_REQUEST = REQUEST_DELIVERY.NO_REQUEST
					WHERE TRUNC(TGL_NOTA) > TO_DATE('$tgl_awal','yyyy-mm-dd') AND TRUNC(TGL_NOTA) < TO_DATE('$tgl_akhir','yyyy-mm-dd')
					UNION
					SELECT NO_NOTA, NOTA_RECEIVING.NO_REQUEST, TRUNC(TGL_NOTA) TGL_NOTA, 'RECEIVING'  AS KEGIATAN, EMKL, TO_CHAR(TOTAL_TAGIHAN, '999,999,999,999') TOTAL_TAGIHAN, RECEIVING_DARI ASAL
					FROM NOTA_RECEIVING INNER JOIN REQUEST_RECEIVING ON NOTA_RECEIVING.NO_REQUEST = REQUEST_RECEIVING.NO_REQUEST
					WHERE TRUNC(TGL_NOTA) > TO_DATE('$tgl_awal','yyyy-mm-dd') AND TRUNC(TGL_NOTA) < TO_DATE('$tgl_akhir','yyyy-mm-dd')  
					UNION
					SELECT NO_NOTA, NOTA_STRIPPING.NO_REQUEST, TRUNC(TGL_NOTA) TGL_NOTA, 'STRIPPING'  AS KEGIATAN, EMKL, TO_CHAR(TOTAL_TAGIHAN, '999,999,999,999') TOTAL_TAGIHAN, STRIPPING_DARI ASAL
					FROM NOTA_STRIPPING INNER JOIN REQUEST_STRIPPING ON NOTA_STRIPPING.NO_REQUEST = REQUEST_STRIPPING.NO_REQUEST
					WHERE TRUNC(TGL_NOTA) > TO_DATE('$tgl_awal','yyyy-mm-dd') AND TRUNC(TGL_NOTA) < TO_DATE('$tgl_akhir','yyyy-mm-dd')  
					UNION
					SELECT NO_NOTA, NOTA_STUFFING.NO_REQUEST, TRUNC(TGL_NOTA) TGL_NOTA, 'STUFFING'  AS KEGIATAN, EMKL, TO_CHAR(TOTAL_TAGIHAN, '999,999,999,999') TOTAL_TAGIHAN, STUFFING_DARI ASAL
					FROM NOTA_STUFFING INNER JOIN REQUEST_STUFFING ON NOTA_STUFFING.NO_REQUEST = REQUEST_STUFFING.NO_REQUEST
					WHERE TRUNC(TGL_NOTA) > TO_DATE('$tgl_awal','yyyy-mm-dd') AND TRUNC(TGL_NOTA) < TO_DATE('$tgl_akhir','yyyy-mm-dd')) 
					WHERE KEGIATAN LIKE '%$jenis%'";
	$result_list_	= $db->query($query_list_);
	$row_list		= $result_list_->getAll(); 

	
	$tl->assign("row_list",$row_list);
	$tl->assign("tgl_awal",$tgl_awal);
	$tl->assign("tgl_akhir",$tgl_akhir);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
