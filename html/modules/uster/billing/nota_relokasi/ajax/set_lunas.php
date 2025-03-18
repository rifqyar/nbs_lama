<?php

$db 			= getDB("storage");

$no_nota		= $_POST["NO_NOTA"]; 
$query_nota	= "SELECT 	  a.NO_REQUEST AS NO_REQUEST,
						  a.LUNAS AS LUNAS
				   FROM NOTA_RELOKASI a,
						REQUEST_RELOKASI b
				   WHERE a.NO_NOTA = '$no_nota'
					AND	 a.NO_REQUEST = b.NO_REQUEST
				   ";
				   
	$result		= $db->query($query_nota);
	$row_nota	= $result->fetchRow();
	
	$no_request = $row_nota["NO_REQUEST"];

$query_update	= "UPDATE NOTA_RELOKASI SET LUNAS = 'YES' WHERE NO_NOTA = '$no_nota'";

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
		$jumlah_lo = $row_lo["TOTAL"];
		$total_lo += $jumlah_lo;
			
	}
	
	
	foreach($row_trf_lo as $row_lo){
		$iso_lo		= $row_lo["ISO"];
		$tarif_lo		= $row_lo["TARIF"];
		$biaya_lo		= $row_lo["TOTAL_CAL"];
		$jum_cont_lo	= $row_lo["JUM"];
		$id_group_lo 	= $row_lo["ID_GROUP"];
			
		$query_insert_lo ="INSERT INTO NOTA_RELOKASI_D(NO_NOTA, 
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
		$jumlah = $row["TOTAL"];
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

if($db->query($query_update))
	{
		//echo HOME;
		header('Location:'.HOME.APPID);		
	}
else {
            header('Location:'.HOME.APPID);		
}

?>