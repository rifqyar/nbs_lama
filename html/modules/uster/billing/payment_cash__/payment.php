<?php

	$tl =  xliteTemplate('list_cont.htm');
	$no_nota	= $_GET["no_nota"];
	$id_bayar	= $_GET["bayar"];
	$byr_melalui = $_GET["melalui"];
	
	if ($id_bayar == 'CASH'){
		$kode = 'CASH'
	} else if ($id_bayar == 'BNI'){
		$kode = 'BANK'
	} else if ($id_bayar == 'BRI'){
		$kode = 'BANK'
	} else if ($id_bayar == 'MANDIRI'){
		$kode = 'BANK'
	}
	
	//$db2			= getDB("ora");
	$query_simkeu	= "SELECT kode_cabang,
         receipt_mehtod,
         currency_code,
         receipt_account,
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
                        AND simop.cabang_cms = simkeu.kode_cabang)
         AND REPLACE (receipt_mehtod, kode_cabang || ' ') = '$kode'
ORDER BY REPLACE (
            REPLACE (REGEXP_REPLACE (receipt_account, '[0-9]', ''),
                     kode_cabang || ' '),
            ' ' || currency_code)";
	
	$simkeu 		= $db2->query($query_simkeu);
	$db				= getDB("storage");
	$query_cont		= "SELECT * FROM CONTAINER_STRIPPING WHERE NO_REQUEST = '$no_request'";
	$result_cont	= $db->query($query_cont);
	$row_cont		= $result_cont->getAll();
	
	
							
	
	
	$tl->assign("row_cont",$row_cont);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
