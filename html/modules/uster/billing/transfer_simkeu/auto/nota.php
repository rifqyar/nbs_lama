<?php
$no_nota		= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
// $query 			= "SELECT NO_NOTA, NO_REQUEST, EMKL, ALAMAT, NPWP, TOTAL_TAGIHAN, PPN, TAGIHAN TOTAL, NO_FAKTUR, TRANSFER, LUNAS, TO_CHAR(TANGGAL_LUNAS,'dd/mm/yyyy') TGL_LUNAS, NVL(TRANSFER, '-') FROM NOTA_ALL_H WHERE NO_FAKTUR LIKE '$no_nota%'";

$query 			= "SELECT a.TRX_NUMBER NO_NOTA,
                             a.NO_REQUEST,
                             TO_CHAR (a.KREDIT, '999,999,999,999') TOTAL_TAGIHAN,
                             b.NM_PBM NM_EMKL,
                             d.NAMA_NOTA NAMA_MODUL,
                             TO_CHAR (a.TRX_DATE, 'dd/mm/yyyy') TGL_KEGIATAN,
                             a.STATUS_ARMSG SIMKEU_PROSES
                        FROM ITPK_NOTA_HEADER a, KAPAL_CABANG.MST_PBM b, KAPAL_CABANG.mst_pelanggan c, MASTER_NOTA_ITPK d
                       WHERE  a.JENIS_NOTA = d.KODE_NOTA
                       AND a.CUSTOMER_NUMBER = b.NO_ACCOUNT_PBM
                       AND b.KD_CABANG='05' 
                       AND b.NO_ACCOUNT_PBM = c.kd_pelanggan 
                       AND b.ALMT_PBM IS NOT NULL 
                       AND c.status_pelanggan = 1
                       AND a.status=2                  
                       AND a.TRX_NUMBER LIKE '$no_nota%'";



$result			= $db->query($query);
$row			= $result->getAll();	

// print_r($result);
// print_r($row);

echo json_encode($row);


?>