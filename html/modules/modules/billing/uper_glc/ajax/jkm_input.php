<?php

$db 		= getDB();
$nm_user    = $_SESSION["NAMA_LENGKAP"];
$id_user    = $_SESSION["ID_USER"];
$id_req		= $_POST["ID_REQ"];
$jkm_uper	= $_POST["JKM_UPER"];
$faktur_uper = $_POST["FAKTUR_UPER"];

if(($nm_user==NULL)||($id_user==NULL)||($id_req==NULL)||($jkm_uper==NULL)||($faktur_uper==NULL))
{
	echo "NO";
}
else
{
	$insert_history = "INSERT INTO GLC_HISTORY (ID_REQ,STATUS,TGL_UPDATE,USER_UPDATE,COUNTER) VALUES ('$id_req','INVOICE UPER',SYSDATE,'$id_user','1')";
	$db->query($insert_history);
	
	$insert_lunas = "UPDATE GLC_UPER_ALAT_H SET LUNAS = 'Y', NO_FAKTUR_UPER_ALAT = '$faktur_uper', NO_JKM_UPER_ALAT = '$jkm_uper' WHERE ID_REQ = '$id_req'";	
	
	if($db->query($insert_lunas))
	{
		//============== insert report_transaction ==============//
		$query2 = "SELECT A.NO_UPER_BM, 
					  C.NAMA,
                      B.NAMA_VESSEL,
                      A.VOYAGE,
                      A.STATUS,
					  A.KADE,
					  A.TERMINAL,
					  TO_CHAR(A.TGL_REQ,'DD-MM-YYYY HH24:MI:SS') AS TGL_REQ,
					  A.REMARK
					  FROM GLC_REQUEST A, MASTER_VESSEL B, MASTER_PBM C
                      WHERE A.KODE_KAPAL = B.KODE_KAPAL
                      AND A.KODE_PBM = C.KODE_PBM
					  AND A.ID_REQ='$id_req'";
		$eksekusi2 = $db->query($query2);
		$row_preview2 = $eksekusi2->fetchRow();

		$uper_bm = $row_preview2['NO_UPER_BM'];
		$pbm = $row_preview2['NAMA'];	
		$vessel = $row_preview2['NAMA_VESSEL'];
		$voyage = $row_preview2['VOYAGE'];
		$kade = $row_preview2['KADE'];
		$terminal = $row_preview2['TERMINAL'];
		$tgl_req = $row_preview2['TGL_REQ'];
		$remark = $row_preview2['REMARK'];
		
		$query3 = "SELECT NO_UPER_ALAT, 
					  NO_FAKTUR_UPER_ALAT,
                      PPN,
                      TOTAL,
                      NO_JKM_UPER_ALAT,
					  TO_CHAR(TGL_INVOICE,'DD-MM-YYYY HH24:MI:SS') AS TGL_INVOICE
					  FROM GLC_UPER_ALAT_H
                      WHERE ID_REQ='$id_req'";
		$eksekusi3 = $db->query($query3);
		$row_preview3 = $eksekusi3->fetchRow();

		$no_uper_alat = $row_preview3['NO_UPER_ALAT'];		
		$no_faktur_uper_alat = $row_preview3['NO_FAKTUR_UPER_ALAT'];
		$ppn_uper_alat = $row_preview3['PPN'];
		$ttl_uper_alat = $row_preview3['TOTAL'];
		$no_jkm_uper_alat = $row_preview3['NO_JKM_UPER_ALAT'];
		$tgl_uper_alat = $row_preview3['TGL_INVOICE'];
		
		$insert_report = "INSERT INTO GLC_REPORT_TRANSACTION (ID_REQ,NO_UPER_BM,NO_UPER_ALAT,NO_FAKTUR_UPER_ALAT,NAMA_PBM,KADE,TERMINAL,VESSEL,VOYAGE,PPN_UPER_ALAT,TOTAL_UPER_ALAT,NO_JKM_UPER_ALAT,TGL_REQ,TGL_UPER_ALAT,REMARK) VALUES ('$id_req','$uper_bm','$no_uper_alat','$no_faktur_uper_alat','$pbm','$kade','$terminal','$vessel','$voyage',$ppn_uper_alat,'$ttl_uper_alat','$no_jkm_uper_alat',TO_DATE('$tgl_req','dd/mm/yyyy HH24:MI:SS'),TO_DATE('$tgl_uper_alat','dd/mm/yyyy HH24:MI:SS'),'$remark')";
		$db->query($insert_report);
		
		//============== insert report_transaction ==============//
		
		echo "OK";
	}
	else
	{
		echo "gagal";
	}
	
}
?>