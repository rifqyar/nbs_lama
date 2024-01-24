<?php
	
	$tl =  xliteTemplate('print_nota.htm');

	$db = getDB("storage");
	
	$no_nota	= $_GET["no_nota"];
	$no_req		= $_GET["no_req"];
	
	
	
	$query_nota	= "SELECT a.NO_NOTA AS NO_NOTA,
						  a.NO_FAKTUR AS NO_FAKTUR,
						  a.TGL_NOTA,
						  c.NAMA AS EMKL,
						  a.NO_REQUEST AS NO_REQUEST,
						  a.LUNAS AS LUNAS,
						  c.NPWP AS NPWP,
						  c.ALAMAT AS ALAMAT
				   FROM NOTA_RECEIVING a,
						REQUEST_RECEIVING b,
						MASTER_PBM c
				   WHERE a.NO_NOTA = '$no_nota'
					AND	 a.NO_REQUEST = b.NO_REQUEST
					AND	 b.ID_EMKL = c.ID
				   ";
				   
	$result		= $db->query($query_nota);
	$row_nota	= $result->fetchRow();
	
	$no_request = $row_nota["NO_REQUEST"];
	
	//SEMENTARA PROCEDURE DI ORACLE BELUM JALAN, PAKAI INI DULU
	//TARIF LOLO
	$query_trf_lo ="SELECT a.STATUS AS STATUS,
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
				  AND d.ID_GROUP_TARIF = '2'
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
	
	
	
	$result_trf_lo	= $db->query($query_trf_lo);
	$row_trf_lo	= $result_trf_lo->getALL();
	
	
	foreach($row_trf_lo as $row_lo){
		$jumlah = $row_lo["TOTAL_CAL"];
		$total_lo += $jumlah;
			
	}
	
	
	
	//END OF TARIF LOLO
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
	//TARIF KEBERSIHAN
	

$query_trf_brsh ="SELECT   DISTINCT     b.SIZE_ AS SIZE_,
						   a.HZ AS HZ,
                           TO_CHAR(d.TARIF, '999,999,999,999')   AS TARIFKU,
						   COUNT(c.ID_ISO) AS JUM,
						   TO_CHAR((COUNT(c.ID_ISO) * d.TARIF) , '999,999,999,999') AS TOTAL,
						   (COUNT(c.ID_ISO) * d.TARIF) AS TOTAL_CAL
					FROM    storage.CONTAINER_RECEIVING a, 
							storage.MASTER_CONTAINER b,
							storage.ISO_CODE c,
							storage.MASTER_TARIF d,
							storage.GROUP_TARIF e
					WHERE a.NO_REQUEST = '$no_request'
					  AND a.NO_CONTAINER = b.NO_CONTAINER
					  AND d.ID_GROUP_TARIF = '4'
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
	$total = $total_pnk + $total_lo + $total_brsh;
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
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();

	
	

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