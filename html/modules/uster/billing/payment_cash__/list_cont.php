<?php

	$tl =  xliteTemplate('list_cont.htm');
	$no_request	= $_GET["no_req"];
	$id_bayar	= $_GET["id_bayar"];
	
	
	if ($id_bayar == 2){
		$kode = 'CASH'
	} else if ($id_bayar == 1){
		$kode = 'BANK'
	} else if ($id_bayar == 3){
		$kode = 'BANK'
	} else if ($id_bayar == 4){
		$kode = 'BANK'
	}
	
	//$db2			= getDB("ora");
	$query_simkeu	= "   SELECT kode_cabang,
         receipt_mehtod,
         currency_code,
         receipt_account,
         alternate_name,
         REPLACE (receipt_mehtod, kode_cabang || ' ') melalui,
         REPLACE (
            REPLACE (REGEXP_REPLACE (receipt_account, '[0-9]', ''),
                     kode_cabang || ' '),
            ' ' || currency_code)
            bool
    FROM apps.xpi2_ar_receipt_method_v@simkeu_link
   WHERE currency_code = 'IDR'
         AND kode_cabang IN
                (SELECT simop.cabang_cms
                   FROM kapal_cabang.mst_cabang simop,
                        apps.xpi2_ar_org_id_v@simkeu_link simkeu
                  WHERE simop.kd_cabang = '05'
                        AND receipt_account NOT LIKE '%Rek%'
                        AND simop.cabang_cms = simkeu.kode_cabang)
ORDER BY REPLACE (
            REPLACE (REGEXP_REPLACE (receipt_account, '[0-9]', ''),
                     kode_cabang || ' '),
            ' ' || currency_code)";
	
	$simkeu 		= $db2->query($query_simkeu);
	$row_simkeu		= $simkeu->getAll();
	
	$db				= getDB("storage");
	$query_cont		= "SELECT NO_NOTA, TO_CHAR(TOTAL_TAGIHAN,'999,999,999,999') TOTAL_TAGIHAN, NO_REQUEST FROM NOTA_RECEIVING WHERE NO_REQUEST = '$no_request'
                        UNION
                        SELECT NO_NOTA,TO_CHAR(TOTAL_TAGIHAN,'999,999,999,999') TOTAL_TAGIHAN, NO_REQUEST FROM NOTA_STUFFING WHERE NO_REQUEST = '$no_request'
                        UNION
                        SELECT NO_NOTA,TO_CHAR(TOTAL_TAGIHAN,'999,999,999,999') TOTAL_TAGIHAN, NO_REQUEST FROM NOTA_STRIPPING WHERE NO_REQUEST = '$no_request'
                        UNION
                        SELECT NO_NOTA, TO_CHAR(TOTAL_TAGIHAN,'999,999,999,999') TOTAL_TAGIHAN, NO_REQUEST FROM NOTA_DELIVERY WHERE NO_REQUEST = '$no_request'
						";
	$result_cont	= $db->query($query_cont);
	$row_cont		= $result_cont->getAll();
	
	$tl->assign("row_simkeu",$row_simkeu);
	$tl->assign("id_bayar",$id_bayar);
	$tl->assign("row_cont",$row_cont);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
