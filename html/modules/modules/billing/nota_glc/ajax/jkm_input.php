<?php

$db 		= getDB();
$nm_user    = $_SESSION["NAMA_LENGKAP"];
$id_user    = $_SESSION["ID_USER"];
$id_req		= $_POST["ID_REQ"];
$jkm_nota	= $_POST["JKM_NOTA"];
$faktur_nota = $_POST["FAKTUR_NOTA"];

if(($nm_user==NULL)||($id_user==NULL)||($id_req==NULL)||($nm_user==NULL)||($jkm_nota==NULL)||($faktur_nota==NULL))
{
	echo "NO";
}
else
{
	$insert_history = "INSERT INTO GLC_HISTORY (ID_REQ,STATUS,TGL_UPDATE,USER_UPDATE,COUNTER) VALUES ('$id_req','INVOICE NOTA',SYSDATE,'$id_user','1')";
	$db->query($insert_history);
	
	$insert_lunas = "UPDATE GLC_NOTA SET LUNAS = 'Y', NO_FAKTUR_NOTA_ALAT = '$faktur_nota', NO_JKM_NOTA_ALAT = '$jkm_nota' WHERE ID_REQ = '$id_req'";	
	
	if($db->query($insert_lunas))
	{
		$query3 = "SELECT ID_NOTA, 
                      PPN,
                      TOTAL,
					  TO_CHAR(TGL_INVOICE,'DD-MM-YYYY HH24:MI:SS') AS TGL_INVOICE
					  FROM GLC_NOTA
                      WHERE ID_REQ='$id_req'";
		$eksekusi3 = $db->query($query3);
		$row_preview3 = $eksekusi3->fetchRow();

		$no_nota = $row_preview3['ID_NOTA'];
		$ppn_nota_alat = $row_preview3['PPN'];
		$ttl_nota_alat = $row_preview3['TOTAL'];
		$tgl_nota_alat = $row_preview3['TGL_INVOICE'];
		
		$query2 = "SELECT TO_CHAR(RTA,'DD-MM-YYYY HH24:MI:SS') AS RTA,
					  TO_CHAR(RTD,'DD-MM-YYYY HH24:MI:SS') AS RTD
					  FROM GLC_REQUEST 
					  WHERE ID_REQ='$id_req'";
		$eksekusi2 = $db->query($query2);
		$row_preview2 = $eksekusi2->fetchRow();

		$rta = $row_preview2['RTA'];
		$rtd = $row_preview2['RTD'];	
		
		$insert_report = "UPDATE GLC_REPORT_TRANSACTION SET NO_NOTA = '$no_nota', NO_FAKTUR_NOTA_ALAT = '$faktur_nota', RTA = TO_DATE('$rta','dd/mm/yyyy HH24:MI:SS'), RTD = TO_DATE('$rtd','dd/mm/yyyy HH24:MI:SS'), PPN_NOTA_ALAT = '$ppn_nota_alat', TOTAL_NOTA_ALAT = '$ttl_nota_alat', NO_JKM_NOTA_ALAT = '$jkm_nota', TGL_NOTA_ALAT = TO_DATE('$tgl_nota_alat','dd/mm/yyyy HH24:MI:SS')";
		$db->query($insert_report);
		
		echo "OK";
	}
	else
	{
		echo "gagal";
	}
	
}
?>