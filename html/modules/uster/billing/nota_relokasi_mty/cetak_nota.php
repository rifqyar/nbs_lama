<?php

$db	 = getDB("storage");

$nota	 = $_GET["n"];
$nipp	 = $_SESSION["NIPP"];
$no_req	 = $_GET["no_req"];
$no_nota = $_GET["no_nota"];

if($nota == 999)
{
	$no_req         = $_GET["no_req"];
	$query_cek      = "select LPAD(MAX(TO_NUMBER(SUBSTR(NO_NOTA,10,15)))+1,6,0) AS JUM, 
                            TO_CHAR(SYSDATE, 'MM') AS MONTH, 
                            TO_CHAR(SYSDATE, 'YY') AS YEAR 
                            FROM nota_stripping
                            WHERE TGL_NOTA BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) ";
					
	$result_cek	= $db->query($query_cek);
	$jum_		= $result_cek->fetchRow();
	$jum		= $jum_["JUM"];
	$month		= $jum_["MONTH"];
	$year		= $jum_["YEAR"];
	
	$no_nota	= "STR05".$month.$year.$jum;
		
        $query_cek	= "SELECT lpad(COUNT(1),6,0) AS JUM,  TO_CHAR(SYSDATE, 'YY') AS YEAR FROM NOTA_STRIPPING WHERE TGL_NOTA BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) ";
	$result_cek	= $db->query($query_cek);
	$jum_		= $result_cek->fetchRow();
	$jum		= $jum_["JUM"];
	$year		= $jum_["YEAR"];
	$no_faktur	= '010.010.-'.$year.'.'.$jum;

	$query_insert_nota	= "INSERT INTO NOTA_STRIPPING(NO_NOTA, NO_FAKTUR, NO_REQUEST, NIPP_USER, LUNAS, CETAK_NOTA, TGL_NOTA) VALUES ('$no_nota', '$no_faktur', '$no_req', '$nipp', 'NO', 1, SYSDATE)";	
	$set_nota		= "UPDATE request_stripping SET NOTA = 'Y' WHERE NO_REQUEST = '$no_req'";
	$db->query($set_nota);
	//===================================================================================================================================================================================
	
	if($db->query($query_insert_nota))
	{
	
		//===========================detail nota stripping======================================================================================================================================
	//--------------------------
	$query_emkl	= " SELECT c.NM_PBM AS EMKL,
                          c.NO_NPWP_PBM AS NPWP,
                          c.ALMT_PBM AS ALAMAT
                   FROM request_stripping b,
                        v_mst_pbm c
                   WHERE b.NO_REQUEST = '$no_req'
                    AND  b.KD_PENUMPUKAN_OLEH = c.KD_PBM
				   ";
				   
	$result		= $db->query($query_nota);
	$row_nota	= $result->fetchRow();
	
	//$no_request = $row_nota["NO_REQUEST"];
	
	//SEMENTARA PROCEDURE DI ORACLE BELUM JALAN, PAKAI INI DULU
	//TARIF LOLO
	$query_trf_lo ="SELECT 'FULL' as STATUS,
                       a.HZ AS HZ,
                       b.TYPE_ AS TYPE_,
                       b.SIZE_ AS SIZE_,
                       d.ID_ISO AS ISO,
                       TO_CHAR(d.TARIF, '999,999,999,999') AS TARIFKU,
                       COUNT(c.ID_ISO) AS JUM,
                       TO_CHAR((COUNT(c.ID_ISO) * d.TARIF) , '999,999,999,999') AS TOTAL,
                       (COUNT(c.ID_ISO) * d.TARIF) AS TOTAL_CAL
                FROM   CONTAINER_STRIPPING a, 
                       MASTER_CONTAINER b,
                       ISO_CODE c,
                       MASTER_TARIF d,
                       GROUP_TARIF e
                WHERE a.NO_REQUEST = '$no_req'
                  AND a.NO_CONTAINER = b.NO_CONTAINER
                  AND d.ID_GROUP_TARIF = '2'
                  AND b.SIZE_ = c.SIZE_
                  AND b.TYPE_ = c.TYPE_ 
                  AND c.ID_ISO = d.ID_ISO
                  AND d.ID_GROUP_TARIF = e.ID_GROUP_TARIF
                  GROUP BY   a.HZ ,
                             b.TYPE_ ,
                             b.SIZE_ ,
                             d.ID_ISO,
                             d.TARIF
                  ORDER BY d.ID_ISO" ;
	
	
	
	$result_trf_lo	= $db->query($query_trf_lo);
	$row_trf_lo	= $result_trf_lo->getALL();
	
	
	foreach($row_trf_lo as $row_lo){
		$jumlah = $row_lo["TOTAL_CAL"];
		$total_lo += $jumlah;
			
	}
	
	
	
	//END OF TARIF LOLO
	/*
	//TARIF PENUMPUKKAN
	$query_trf ="SELECT    a.STATUS AS STATUS,
					   a.HZ AS HZ,
					   b.TYPE_ AS TYPE_,
					   b.SIZE_ AS SIZE_,
					   d.ID_ISO AS ISO,
					   TO_CHAR(d.TARIF, '999,999,999,999')   AS TARIFKU,
					   COUNT(c.ID_ISO) AS JUM,
					   TO_CHAR((COUNT(c.ID_ISO) * d.TARIF) , '999,999,999,999') AS TOTAL,
					   (COUNT(c.ID_ISO) * d.TARIF) AS TOTAL_CAL
			FROM    	storage.CONTAINER_RECEIVING a, 
						storage.MASTER_CONTAINER b,
						storage.ISO_CODE c,
						storage.MASTER_TARIF d,
						storage.GROUP_TARIF e
			WHERE a.NO_REQUEST = '$no_request'
			  AND a.NO_CONTAINER = b.NO_CONTAINER
			  AND d.ID_GROUP_TARIF = '1'
			  AND b.SIZE_ = c.SIZE_
			  AND b.TYPE_ = c.TYPE_ 
			  AND a.STATUS = c.STATUS
			  AND c.ID_ISO = d.ID_ISO
			  AND d.ID_GROUP_TARIF = e.ID_GROUP_TARIF
			  GROUP BY   a.HZ ,
						 b.TYPE_ ,
						 b.SIZE_ ,
						 d.ID_ISO,
						 d.TARIF,
						 a.STATUS
			  ORDER BY d.ID_ISO" ;

	
	
	
	$result_trf	= $db->query($query_trf);
	$row_trf	= $result_trf->getALL();
	
	foreach($row_trf as $row){
		$jumlah = $row["TOTAL_CAL"];
		$total_pnk += $jumlah;
			
	}
	
	//END OF TARIF PENUMPUKKAN
	*/
	//TARIF KEBERSIHAN
	

$query_trf_brsh =" SELECT   DISTINCT     b.SIZE_ AS SIZE_,
                           a.HZ AS HZ,
                           TO_CHAR(d.TARIF, '999,999,999,999')   AS TARIFKU,
                           COUNT(c.ID_ISO) AS JUM,
                           TO_CHAR((COUNT(c.ID_ISO) * d.TARIF) , '999,999,999,999') AS TOTAL,
                           (COUNT(c.ID_ISO) * d.TARIF) AS TOTAL_CAL
                    FROM  CONTAINER_STRIPPING a, 
                            MASTER_CONTAINER b,
                            ISO_CODE c,
                            MASTER_TARIF d,
                            GROUP_TARIF e
                    WHERE a.NO_REQUEST = '$no_req'
                      AND a.NO_CONTAINER = b.NO_CONTAINER
                      AND d.ID_GROUP_TARIF = '3'
                      AND b.SIZE_ = c.SIZE_
                      AND c.ID_ISO = d.ID_ISO
                      AND d.ID_GROUP_TARIF = e.ID_GROUP_TARIF
                      GROUP BY  a.HZ ,
                                b.SIZE_ ,
                                c.ID_ISO,
                                d.TARIF
                      ORDER BY b.SIZE_" ;



$result_trf_brsh	= $db->query($query_trf_brsh);
$row_trf_brsh		= $result_trf_brsh->getALL();
	
	foreach($row_trf_brsh as $row){
		$jumlah = $row["TOTAL_CAL"];
		$total_brsh += $jumlah;
			
	}


//END OF TARIF KEBERSIHAN
	//--------------------------
	
	//$query_req = ""
	
	
	//debug($row_trf_brsh);
	//debug($no_request);
	//debug($row_trf);
	
	
	//Discount
	$discount =0;
	$query_discount		= "SELECT TO_CHAR($discount , '999,999,999,999') AS DISCOUNT FROM DUAL";
		$result_discount		= $db->query($query_discount);
		$row_discount		= $result_discount->fetchRow();
	//Biaya Administrasi
	$adm =10000;
	$query_adm		= "SELECT TO_CHAR($adm , '999,999,999,999') AS ADM FROM DUAL";
		$result_adm		= $db->query($query_adm);
		$row_adm		= $result_adm->fetchRow();
		
	//Menghitung Total dasar pengenaan pajak
	$total = /*$total_pnk + */$total_lo + $total_brsh;
	$query_tot		= "SELECT TO_CHAR('$total' , '999,999,999,999') AS TOTAL_ALL FROM DUAL";
		$result_tot		= $db->query($query_tot);
		$row_tot		= $result_tot->fetchRow();
		
	//Menghitung Jumlah PPN
	$ppn = $total/10;
	$query_ppn		= "SELECT TO_CHAR('$ppn' , '999,999,999,999') AS PPN FROM DUAL";
		$result_ppn		= $db->query($query_ppn);
		$row_ppn		= $result_ppn->fetchRow();
		
	//Menghitung Bea Materai
	$bea_materai = 0;
	$query_materai		= "SELECT TO_CHAR('$bea_materai' , '999,999,999,999') AS MATERAI FROM DUAL";
	$result_materai		= $db->query($query_materai);
	$row_materai		= $result_materai->fetchRow();
	
	//Menghitung Jumlah dibayar
	$total_bayar = $adm + $total + $ppn;
	$query_bayar		= "SELECT TO_CHAR('$total_bayar' , '999,999,999,999') AS TOTAL_BAYAR FROM DUAL";
	$result_bayar		= $db->query($query_bayar);
	$row_bayar		= $result_bayar->fetchRow();
	
	//debug($no_req);die;
	$tl->assign("row_discount",$row_discount);	
	$tl->assign("row_adm",$row_adm);
	$tl->assign("row_tot",$row_tot);
	$tl->assign("row_ppn",$row_ppn);
	$tl->assign("row_materai",$row_materai);
	$tl->assign("row_bayar",$row_bayar);
	$tl->assign("row_trf_brsh",$row_trf_brsh);
	$tl->assign("row_trf_lo",$row_trf_lo);
	$tl->assign("row_trf",$row_trf);
	$tl->assign("row_nota",$row_nota);
	$tl->assign("no_nota",$no_nota);
	$tl->assign("no_req",$no_req);
	$tl->assign("no_faktur",$no_faktur);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	
	$tl->renderToScreen();
		
	header('Location:'.HOME.APPID.'/print_nota?no_nota='.$no_nota);
				
	}
}
else
{
	$no_nota		= $_GET["no_nota"];
	$nota_			= $nota + 1;
	$query_update           = "UPDATE NOTA_STRIPPING SET CETAK_NOTA = '$nota_' WHERE NO_NOTA = '$no_nota'";
	//echo $query_update;die;
	if($db->query($query_update))
	{
		//echo HOME;
		header('Location:'.HOME.APPID.'/print_nota?no_nota='.$no_nota);		
	}	
}
	
	

?>

<script>
	function set_lunas($no_nota)
	{
		var url			= "<?=HOME?><?=APPID?>.ajax/set_lunas";
		
		$.post(url,{NO_NOTA : $no_nota},function(data){
			if(data == "OK")
			{
				
				$("#status_lunas").html("<h1><font color='#0000FF'>LUNAS7</font></h1><br />");
				/*
				$("#status_lunas").html("<a href="<?=HOME?><?=APPID?>.print/print_pdf?no_req={$row_nota.NO_REQUEST}&no_nota={$row_nota.NO_NOTA}" target="_blank" > PRINT </a>");
				*/
			}
		});	
	}
	
</script>
	
	
	





?>