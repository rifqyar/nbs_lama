<?php

$db 			= getDB("storage");

$no_nota		= $_GET["no_nota"]; 
$query_nota	= "SELECT 	  a.NO_REQUEST AS NO_REQUEST,
						  a.LUNAS AS LUNAS
				   FROM NOTA_RECEIVING a,
						REQUEST_RECEIVING b
				   WHERE a.NO_NOTA = '$no_nota'
					AND	 a.NO_REQUEST = b.NO_REQUEST
				   ";
				   
	$result		= $db->query($query_nota);
	$row_nota	= $result->fetchRow();
	
	$no_request = $row_nota["NO_REQUEST"];



//SEMENTARA PROCEDURE DI ORACLE BELUM JALAN, PAKAI INI DULU
//TARIF LOLO

	$query_trf_lo ="SELECT    a.STATUS AS STATUS,
						   b.TYPE_ AS TYPE_,
						   b.SIZE_ AS SIZE_,
						   d.ID_ISO AS ISO,
						   d.ID_GROUP_TARIF AS ID_GROUP,
                           TO_CHAR(d.TARIF, '999,999,999,999')   AS TARIFKU,
						   d.TARIF AS TARIF,
                           COUNT(c.ID_ISO) AS JUM,
						   TO_CHAR((COUNT(c.ID_ISO) * d.TARIF) , '999,999,999,999') AS TOTAL,
						   (COUNT(c.ID_ISO) * d.TARIF) AS TOTAL_CAL
				FROM    	CONTAINER_RECEIVING a, 
							MASTER_CONTAINER b,
							ISO_CODE c,
							MASTER_TARIF d,
							GROUP_TARIF e
				WHERE a.NO_REQUEST = '$no_request'
				  AND a.NO_CONTAINER = b.NO_CONTAINER
				  AND d.ID_GROUP_TARIF = '2'
				  AND b.SIZE_ = c.SIZE_
				  AND b.TYPE_ = c.TYPE_ 
				  AND a.STATUS = c.STATUS
				  AND c.ID_ISO = d.ID_ISO
				  AND d.ID_GROUP_TARIF = e.ID_GROUP_TARIF
				  GROUP BY   b.TYPE_ ,
							 b.SIZE_ ,
							 d.ID_ISO,
							 d.TARIF,
							 a.STATUS,
							 d.ID_GROUP_TARIF
				  ORDER BY d.ID_ISO" ;
	
	
	
	$result_trf_lo	= $db->query($query_trf_lo);
	$row_trf_lo	= $result_trf_lo->getALL();
	
	$no	= 1;
	
	foreach($row_trf_lo as $row_lo){
		$jumlah_lo = $row_lo["TOTAL_CAL"];
		$total_lo += $jumlah_lo;
			
	}
	
	
	foreach($row_trf_lo as $row_lo){
		$iso_lo		= $row_lo["ISO"];
		$tarif_lo		= $row_lo["TARIF"];
		$biaya_lo		= $row_lo["TOTAL_CAL"];
		$jum_cont_lo	= $row_lo["JUM"];
		$id_group_lo 	= $row_lo["ID_GROUP"];
			
		$query_insert_lo ="INSERT INTO NOTA_RECEIVING_D(NO_NOTA, 
									   ID_ISO, 
									   TARIF, 
									   BIAYA, 
									   JUMLAH_CONT,
									   ID_GROUP_TARIF) 
								VALUES('$no_nota',
									   '$iso_lo',
									   '$tarif_lo',
									   '$biaya_lo',
									   '$jum_cont_lo',
									   '$id_group_lo')
						";
						
		$result_insert_lo = $db->query($query_insert_lo);
		//$no += $no + 1;
	}
	
	//END OF TARIF LOLO
	
	//TARIF KEBERSIHAN
	$query_trf_brsh ="SELECT    a.STATUS AS STATUS,
						   b.TYPE_ AS TYPE_,
						   b.SIZE_ AS SIZE_,
						   d.ID_ISO AS ISO,
						   d.ID_GROUP_TARIF AS ID_GROUP,
                           TO_CHAR(d.TARIF, '999,999,999,999')   AS TARIFKU,
						   d.TARIF AS TARIF,
                           COUNT(c.ID_ISO) AS JUM,
						   TO_CHAR((COUNT(c.ID_ISO) * d.TARIF) , '999,999,999,999') AS TOTAL,
						   (COUNT(c.ID_ISO) * d.TARIF) AS TOTAL_CAL
				FROM    	CONTAINER_RECEIVING a, 
							MASTER_CONTAINER b,
							ISO_CODE c,
							MASTER_TARIF d,
							GROUP_TARIF e
				WHERE a.NO_REQUEST = '$no_request'
				  AND a.NO_CONTAINER = b.NO_CONTAINER
				  AND d.ID_GROUP_TARIF = '6'
				  AND b.SIZE_ = c.SIZE_
				  AND b.TYPE_ = c.TYPE_ 
				  AND a.STATUS = c.STATUS
				  AND c.ID_ISO = d.ID_ISO
				  AND d.ID_GROUP_TARIF = e.ID_GROUP_TARIF
				  GROUP BY   b.TYPE_ ,
							 b.SIZE_ ,
							 d.ID_ISO,
							 d.TARIF,
							 a.STATUS,
							 d.ID_GROUP_TARIF
				  ORDER BY d.ID_ISO" ;
	
	
	
	$result_trf_brsh	= $db->query($query_trf_brsh);
	$row_trf_brsh	= $result_trf_brsh->getALL();
	
	$no	= 1;
	
	foreach($row_trf_brsh as $row_brsh){
		$jumlah_brsh = $row_brsh["TOTAL_CAL"];
		$total_brsh += $jumlah_brsh;
			
	}
	
	
	foreach($row_trf_brsh as $row_brsh){
		$iso_brsh		= $row_brsh["ISO"];
		$tarif_brsh		= $row_brsh["TARIF"];
		$biaya_brsh		= $row_brsh["TOTAL_CAL"];
		$jum_cont_brsh	= $row_brsh["JUM"];
		$id_group_brsh 	= $row_brsh["ID_GROUP"];
			
		$query_insert_brsh ="INSERT INTO NOTA_RECEIVING_D(NO_NOTA, 
									   ID_ISO, 
									   TARIF, 
									   BIAYA, 
									   JUMLAH_CONT,
									   ID_GROUP_TARIF) 
								VALUES('$no_nota',
									   '$iso_brsh',
									   '$tarif_brsh',
									   '$biaya_brsh',
									   '$jum_cont_brsh',
									   '$id_group_brsh')
						";
						
		$result_insert_brsh = $db->query($query_insert_brsh);
		//$no += $no + 1;
	}
	//END OF TARIF KEBERSIHAN
	
	
	
	//TARIF PENUMPUKKAN
	
	$query_trf ="SELECT    a.STATUS AS STATUS,
						   b.TYPE_ AS TYPE_,
						   b.SIZE_ AS SIZE_,
						   d.ID_ISO AS ISO,
						   d.ID_GROUP_TARIF AS ID_GROUP,
                           TO_CHAR(d.TARIF, '999,999,999,999')   AS TARIFKU,
						   d.TARIF AS TARIF,
                           COUNT(c.ID_ISO) AS JUM,
						   TO_CHAR((COUNT(c.ID_ISO) * d.TARIF) , '999,999,999,999') AS TOTAL,
						   (COUNT(c.ID_ISO) * d.TARIF) AS TOTAL_CAL
				FROM    	CONTAINER_RECEIVING a, 
							MASTER_CONTAINER b,
							ISO_CODE c,
							MASTER_TARIF d,
							GROUP_TARIF e
				WHERE a.NO_REQUEST = '$no_request'
				  AND a.NO_CONTAINER = b.NO_CONTAINER
				  AND d.ID_GROUP_TARIF = '1'
				  AND b.SIZE_ = c.SIZE_
				  AND b.TYPE_ = c.TYPE_ 
				  AND a.STATUS = c.STATUS
				  AND c.ID_ISO = d.ID_ISO
				  AND d.ID_GROUP_TARIF = e.ID_GROUP_TARIF
				  GROUP BY   b.TYPE_ ,
							 b.SIZE_ ,
							 d.ID_ISO,
							 d.TARIF,
							 a.STATUS,
							 d.ID_GROUP_TARIF
				  ORDER BY d.ID_ISO" ;
	
	
	
	$result_trf	= $db->query($query_trf);
	$row_trf	= $result_trf->getALL();
	
	$no	= 1;
	
	foreach($row_trf as $row){
		$jumlah = $row["TOTAL_CAL"];
		$total_pnk += $jumlah;
			
	}
	
	foreach($row_trf as $row){
		$iso		= $row["ISO"];
		$tarif		= $row["TARIF"];
		$biaya		= $row["TOTAL_CAL"];
		$jum_cont	= $row["JUM"];
		$id_group 	= $row["ID_GROUP"];
			
		$query_insert ="INSERT INTO NOTA_RECEIVING_D(NO_NOTA, 
									   ID_ISO, 
									   TARIF, 
									   BIAYA, 
									   JUMLAH_CONT,
									   ID_GROUP_TARIF) 
								VALUES('$no_nota',
									   '$iso',
									   '$tarif',
									   '$biaya',
									   '$jum_cont',
									   '$id_group')
						";
						
		$result_insert = $db->query($query_insert);
		//$no += $no + 1;
	}
	
	
	//END OF TARIF PENUMPUKKAN

	//--------------------------
	
	
	
	//Perhitungan Biaya Total
	/*Discount
	$discount =0;
	$query_discount		= "SELECT TO_CHAR($discount , '999,999,999,999') AS DISCOUNT FROM DUAL";
		$result_discount		= $db->query($query_discount);
		$row_discount		= $result_discount->fetchRow();
	*/
	//Biaya Administrasi
	$adm =10000;
	//$query_adm		= "SELECT TO_CHAR($adm , '999,999,999,999') AS ADM FROM DUAL";
	//	$result_adm		= $db->query($query_adm);
	//	$row_adm		= $result_adm->fetchRow();
		
	//Menghitung Total dasar pengenaan pajak
	$total = $total_pnk + $total_lo + $total_brsh;
	//$query_tot		= "SELECT TO_CHAR('$total' , '999,999,999,999') AS TOTAL_ALL FROM DUAL";
	//	$result_tot		= $db->query($query_tot);
	//	$row_tot		= $result_tot->fetchRow();
		
	//Menghitung Jumlah PPN
	$ppn = $total/10;
	//$query_ppn		= "SELECT TO_CHAR('$ppn' , '999,999,999,999') AS PPN FROM DUAL";
	//	$result_ppn		= $db->query($query_ppn);
	//	$row_ppn		= $result_ppn->fetchRow();
		
	//Menghitung Bea Materai
	$bea_materai = 0;
	//$query_materai		= "SELECT TO_CHAR('$bea_materai' , '999,999,999,999') AS MATERAI FROM DUAL";
	//$result_materai		= $db->query($query_materai);
	//$row_materai		= $result_materai->fetchRow();
	
	//Menghitung Jumlah dibayar
	$total_bayar = $adm + $total + $ppn;
	//$query_bayar		= "SELECT TO_CHAR('$total_bayar' , '999,999,999,999') AS TOTAL_BAYAR FROM DUAL";
	//$result_bayar		= $db->query($query_bayar);
	//$row_bayar		= $result_bayar->fetchRow();
	//End Of Perhitungan Biaya Total
	//print_r($total_bayar);
	
	$query_update	= "UPDATE NOTA_RECEIVING 
					   SET LUNAS = 'YES', 
					   TAGIHAN = '$total',
					   PPN = '$ppn',
					   TOTAL_TAGIHAN = '$total_bayar'
					   WHERE NO_NOTA = '$no_nota'";

if($db->query($query_update))
	{
		//echo HOME;
		header('Location:'.HOME.APPID);		
	}
else {
            header('Location:'.HOME.APPID);		
}

?>