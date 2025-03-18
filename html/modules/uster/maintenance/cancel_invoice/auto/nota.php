<?php
$no_nota		= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
//echo "SELECT NO_NOTA, NO_REQUEST, EMKL, ALAMAT, NPWP, TOTAL_TAGIHAN, PPN, TAGIHAN TOTAL, NO_FAKTUR, TRANSFER, LUNAS, TO_CHAR(TANGGAL_LUNAS,'dd/mm/yyyy') TGL_LUNAS, NVL(TRANSFER, '-') TRANSFER FROM NOTA_ALL_H WHERE NO_NOTA LIKE '$no_nota%'";


$query 			= "SELECT NO_NOTA, NO_FAKTUR, NO_REQUEST, EMKL, ALAMAT, NPWP, TOTAL_TAGIHAN, PPN, TAGIHAN TOTAL, NO_FAKTUR, TRANSFER, LUNAS, TO_CHAR(TANGGAL_LUNAS,'dd/mm/yyyy') TGL_LUNAS, NVL(TRANSFER, '-') TRANSFER, 'RECEIVING' KEGIATAN FROM NOTA_RECEIVING WHERE NO_NOTA_MTI = '$no_nota' AND (STATUS = 'NEW' OR STATUS = 'PERP' OR STATUS = 'KOREKSI')
UNION
SELECT NO_NOTA, NO_FAKTUR, NO_REQUEST, EMKL, ALAMAT, NPWP, TOTAL_TAGIHAN, PPN, TAGIHAN TOTAL, NO_FAKTUR, TRANSFER, LUNAS, TO_CHAR(TANGGAL_LUNAS,'dd/mm/yyyy') TGL_LUNAS, NVL(TRANSFER, '-') TRANSFER,'DELIVERY' KEGIATAN FROM NOTA_DELIVERY WHERE NO_NOTA_MTI = '$no_nota' AND (STATUS = 'NEW' OR STATUS = 'PERP' OR STATUS = 'KOREKSI')
UNION
SELECT NO_NOTA, NO_FAKTUR, NO_REQUEST, EMKL, ALAMAT, NPWP, TOTAL_TAGIHAN, PPN, TAGIHAN TOTAL, NO_FAKTUR, TRANSFER, LUNAS, TO_CHAR(TANGGAL_LUNAS,'dd/mm/yyyy') TGL_LUNAS, NVL(TRANSFER, '-') TRANSFER, 'STUFFING' KEGIATAN FROM NOTA_STUFFING WHERE NO_NOTA_MTI = '$no_nota' AND (STATUS = 'NEW' OR STATUS = 'PERP' OR STATUS = 'KOREKSI' )
UNION
SELECT NO_NOTA, NO_FAKTUR, NO_REQUEST, EMKL, ALAMAT, NPWP, TOTAL_TAGIHAN, PPN, TAGIHAN TOTAL, NO_FAKTUR, TRANSFER, LUNAS, TO_CHAR(TANGGAL_LUNAS,'dd/mm/yyyy') TGL_LUNAS, NVL(TRANSFER, '-') TRANSFER, 'STRIPPING' KEGIATAN FROM NOTA_STRIPPING WHERE NO_NOTA_MTI = '$no_nota' AND (STATUS = 'NEW' OR STATUS = 'PERP' OR STATUS = 'KOREKSI')
UNION
SELECT NO_NOTA, NO_FAKTUR, NO_REQUEST, EMKL, ALAMAT, NPWP, TOTAL_TAGIHAN, PPN, TAGIHAN TOTAL, NO_FAKTUR, TRANSFER, LUNAS, TO_CHAR(TANGGAL_LUNAS,'dd/mm/yyyy') TGL_LUNAS, NVL(TRANSFER, '-') TRANSFER, 'RELOKASI' KEGIATAN FROM NOTA_RELOKASI WHERE NO_NOTA_MTI = '$no_nota' AND (STATUS = 'NEW' OR STATUS = 'PERP' OR STATUS = 'KOREKSI')
UNION
SELECT NO_NOTA, NO_FAKTUR, NO_REQUEST, EMKL, ALAMAT, NPWP, TOTAL_TAGIHAN, PPN, TAGIHAN TOTAL, NO_FAKTUR, TRANSFER, LUNAS, TO_CHAR(TANGGAL_LUNAS,'dd/mm/yyyy') TGL_LUNAS, NVL(TRANSFER, '-') TRANSFER, 'STRIPPING' KEGIATAN FROM NOTA_RELOKASI_MTY WHERE NO_NOTA_MTI = '$no_nota' AND (STATUS = 'NEW' OR STATUS = 'PERP' OR STATUS = 'KOREKSI')
UNION
SELECT NO_NOTA, NO_FAKTUR, NO_REQUEST, EMKL, ALAMAT, NPWP, TOTAL_TAGIHAN, PPN, TAGIHAN TOTAL, NO_FAKTUR, TRANSFER, LUNAS, TO_CHAR(TANGGAL_LUNAS,'dd/mm/yyyy') TGL_LUNAS, NVL(TRANSFER, '-') TRANSFER, 'PNKN_DEL' KEGIATAN FROM NOTA_PNKN_DEL WHERE NO_NOTA_MTI = '$no_nota' AND (STATUS = 'NEW' OR STATUS = 'PERP' OR STATUS = 'KOREKSI')
UNION
SELECT NO_NOTA, NO_FAKTUR, NO_REQUEST, EMKL, ALAMAT, NPWP, TOTAL_TAGIHAN, PPN, TAGIHAN TOTAL, NO_FAKTUR, TRANSFER, LUNAS, TO_CHAR(TANGGAL_LUNAS,'dd/mm/yyyy') TGL_LUNAS, NVL(TRANSFER, '-') TRANSFER,'PNKN_STUF' KEGIATAN FROM NOTA_PNKN_STUF WHERE NO_NOTA_MTI = '$no_nota' AND (STATUS = 'NEW' OR STATUS = 'PERP' OR STATUS = 'KOREKSI')
UNION
SELECT NO_NOTA, NO_FAKTUR, NO_REQUEST, EMKL, ALAMAT, NPWP, TOTAL_TAGIHAN, PPN, TAGIHAN TOTAL, NO_FAKTUR, TRANSFER, LUNAS, TO_CHAR(TGL_LUNAS,'dd/mm/yyyy') TGL_LUNAS, NVL(TRANSFER, '-') TRANSFER,'BATAL_MUAT' KEGIATAN FROM NOTA_BATAL_MUAT WHERE NO_NOTA_MTI = '$no_nota' AND (STATUS = 'NEW' OR STATUS = 'PERP' OR STATUS = 'KOREKSI')";

// $query 			=	"SELECT CASE WHEN B.CM_FLAG = 'Y' THEN 'SUDAH DI BATALKAN'
                     // WHEN B.CM_FLAG = 'N' THEN 'BELUM DI BATALKAN'
								// END  CM, 
								// A.NO_NOTA, A.NO_FAKTUR, A.NO_REQUEST, A.EMKL, A.ALAMAT, A.NPWP, A.TOTAL_TAGIHAN, A.PPN, A.TAGIHAN TOTAL, A.NO_FAKTUR, A.TRANSFER, A.LUNAS, TO_CHAR(A.TANGGAL_LUNAS,'dd/mm/yyyy') TGL_LUNAS, NVL(A.TRANSFER, '-') TRANSFER, 'RECEIVING' KEGIATAN 
					// FROM NOTA_RECEIVING A JOIN APPS.XPI2_AR_NOTA_SIMPO_V@SIMKEU_PROD B ON A.NO_FAKTUR = B.NOMOR_NOTA
					// WHERE NO_FAKTUR = '$no_nota' AND (STATUS = 'NEW' OR STATUS = 'PERP' OR STATUS = 'KOREKSI')
					// UNION
					// SELECT CASE WHEN B.CM_FLAG = 'Y' THEN 'SUDAH DI BATALKAN'
										 // WHEN B.CM_FLAG = 'N' THEN 'BELUM DI BATALKAN'
								// END  CM, 
								// A.NO_NOTA, A.NO_FAKTUR, A.NO_REQUEST, A.EMKL, A.ALAMAT, A.NPWP, A.TOTAL_TAGIHAN, A.PPN, A.TAGIHAN TOTAL, A.NO_FAKTUR, A.TRANSFER, A.LUNAS, TO_CHAR(A.TANGGAL_LUNAS,'dd/mm/yyyy') TGL_LUNAS, NVL(A.TRANSFER, '-') TRANSFER, 'RECEIVING' KEGIATAN 
					// FROM NOTA_DELIVERY A JOIN APPS.XPI2_AR_NOTA_SIMPO_V@SIMKEU_PROD B ON A.NO_FAKTUR = B.NOMOR_NOTA
					// WHERE NO_FAKTUR = '$no_nota' AND (STATUS = 'NEW' OR STATUS = 'PERP' OR STATUS = 'KOREKSI')
					// UNION
					// SELECT CASE WHEN B.CM_FLAG = 'Y' THEN 'SUDAH DI BATALKAN'
										 // WHEN B.CM_FLAG = 'N' THEN 'BELUM DI BATALKAN'
								// END  CM,
								 // A.NO_NOTA, A.NO_FAKTUR, A.NO_REQUEST, A.EMKL, A.ALAMAT, A.NPWP, A.TOTAL_TAGIHAN, A.PPN, A.TAGIHAN TOTAL, A.NO_FAKTUR, A.TRANSFER, A.LUNAS, TO_CHAR(A.TANGGAL_LUNAS,'dd/mm/yyyy') TGL_LUNAS, NVL(A.TRANSFER, '-') TRANSFER, 'RECEIVING' KEGIATAN
					// FROM NOTA_STUFFING A JOIN APPS.XPI2_AR_NOTA_SIMPO_V@SIMKEU_PROD B ON A.NO_FAKTUR = B.NOMOR_NOTA
					// WHERE NO_FAKTUR = '$no_nota' AND (STATUS = 'NEW' OR STATUS = 'PERP' OR STATUS = 'KOREKSI' )
					// UNION
					// SELECT CASE WHEN B.CM_FLAG = 'Y' THEN 'SUDAH DI BATALKAN'
										 // WHEN B.CM_FLAG = 'N' THEN 'BELUM DI BATALKAN'
								// END  CM,
								 // A.NO_NOTA, A.NO_FAKTUR, A.NO_REQUEST, A.EMKL, A.ALAMAT, A.NPWP, A.TOTAL_TAGIHAN, A.PPN, A.TAGIHAN TOTAL, A.NO_FAKTUR, A.TRANSFER, A.LUNAS, TO_CHAR(A.TANGGAL_LUNAS,'dd/mm/yyyy') TGL_LUNAS, NVL(A.TRANSFER, '-') TRANSFER, 'RECEIVING' KEGIATAN
					// FROM NOTA_STRIPPING A JOIN APPS.XPI2_AR_NOTA_SIMPO_V@SIMKEU_PROD B ON A.NO_FAKTUR = B.NOMOR_NOTA
					// WHERE NO_FAKTUR = '$no_nota' AND (STATUS = 'NEW' OR STATUS = 'PERP' OR STATUS = 'KOREKSI')
					// UNION
					// SELECT CASE WHEN B.CM_FLAG = 'Y' THEN 'SUDAH DI BATALKAN'
										 // WHEN B.CM_FLAG = 'N' THEN 'BELUM DI BATALKAN'
								// END  CM,
								 // A.NO_NOTA, A.NO_FAKTUR, A.NO_REQUEST, A.EMKL, A.ALAMAT, A.NPWP, A.TOTAL_TAGIHAN, A.PPN, A.TAGIHAN TOTAL, A.NO_FAKTUR, A.TRANSFER, A.LUNAS, TO_CHAR(A.TANGGAL_LUNAS,'dd/mm/yyyy') TGL_LUNAS, NVL(A.TRANSFER, '-') TRANSFER, 'RECEIVING' KEGIATAN 
					// FROM NOTA_RELOKASI A JOIN APPS.XPI2_AR_NOTA_SIMPO_V@SIMKEU_PROD B ON A.NO_FAKTUR = B.NOMOR_NOTA
					// WHERE NO_FAKTUR = '$no_nota' AND (STATUS = 'NEW' OR STATUS = 'PERP' OR STATUS = 'KOREKSI')
					// UNION
					// SELECT CASE WHEN B.CM_FLAG = 'Y' THEN 'SUDAH DI BATALKAN'
										 // WHEN B.CM_FLAG = 'N' THEN 'BELUM DI BATALKAN'
								// END  CM,
								 // A.NO_NOTA, A.NO_FAKTUR, A.NO_REQUEST, A.EMKL, A.ALAMAT, A.NPWP, A.TOTAL_TAGIHAN, A.PPN, A.TAGIHAN TOTAL, A.NO_FAKTUR, A.TRANSFER, A.LUNAS, TO_CHAR(A.TANGGAL_LUNAS,'dd/mm/yyyy') TGL_LUNAS, NVL(A.TRANSFER, '-') TRANSFER, 'RECEIVING' KEGIATAN
					// FROM NOTA_RELOKASI_MTY A JOIN APPS.XPI2_AR_NOTA_SIMPO_V@SIMKEU_PROD B ON A.NO_FAKTUR = B.NOMOR_NOTA
					// WHERE NO_FAKTUR = '$no_nota' AND (STATUS = 'NEW' OR STATUS = 'PERP' OR STATUS = 'KOREKSI')
					// UNION
					// SELECT CASE WHEN B.CM_FLAG = 'Y' THEN 'SUDAH DI BATALKAN'
										 // WHEN B.CM_FLAG = 'N' THEN 'BELUM DI BATALKAN'
								// END  CM
								// , A.NO_NOTA, A.NO_FAKTUR, A.NO_REQUEST, A.EMKL, A.ALAMAT, A.NPWP, A.TOTAL_TAGIHAN, A.PPN, A.TAGIHAN TOTAL, A.NO_FAKTUR, A.TRANSFER, A.LUNAS, TO_CHAR(A.TANGGAL_LUNAS,'dd/mm/yyyy') TGL_LUNAS, NVL(A.TRANSFER, '-') TRANSFER, 'RECEIVING' KEGIATAN
					// FROM NOTA_PNKN_DEL A JOIN APPS.XPI2_AR_NOTA_SIMPO_V@SIMKEU_PROD B ON A.NO_FAKTUR = B.NOMOR_NOTA
					// WHERE NO_FAKTUR = '$no_nota' AND (STATUS = 'NEW' OR STATUS = 'PERP' OR STATUS = 'KOREKSI')
					// UNION
					// SELECT CASE WHEN B.CM_FLAG = 'Y' THEN 'SUDAH DI BATALKAN'
										 // WHEN B.CM_FLAG = 'N' THEN 'BELUM DI BATALKAN'
								// END  CM
								// , A.NO_NOTA, A.NO_FAKTUR, A.NO_REQUEST, A.EMKL, A.ALAMAT, A.NPWP, A.TOTAL_TAGIHAN, A.PPN, A.TAGIHAN TOTAL, A.NO_FAKTUR, A.TRANSFER, A.LUNAS, TO_CHAR(A.TANGGAL_LUNAS,'dd/mm/yyyy') TGL_LUNAS, NVL(A.TRANSFER, '-') TRANSFER, 'RECEIVING' KEGIATAN
					// FROM NOTA_PNKN_STUF A JOIN APPS.XPI2_AR_NOTA_SIMPO_V@SIMKEU_PROD B ON A.NO_FAKTUR = B.NOMOR_NOTA
					// WHERE NO_FAKTUR = '$no_nota' AND (STATUS = 'NEW' OR STATUS = 'PERP' OR STATUS = 'KOREKSI')
					// UNION
					// SELECT CASE WHEN B.CM_FLAG = 'Y' THEN 'SUDAH DI BATALKAN'
										 // WHEN B.CM_FLAG = 'N' THEN 'BELUM DI BATALKAN'
								// END  CM
								// , A.NO_NOTA, A.NO_FAKTUR, A.NO_REQUEST, A.EMKL, A.ALAMAT, A.NPWP, A.TOTAL_TAGIHAN, A.PPN, A.TAGIHAN TOTAL, A.NO_FAKTUR, A.TRANSFER, A.LUNAS, TO_CHAR(A.TGL_LUNAS,'dd/mm/yyyy') TGL_LUNAS, NVL(A.TRANSFER, '-') TRANSFER, 'RECEIVING' KEGIATAN 
					// FROM NOTA_BATAL_MUAT A JOIN APPS.XPI2_AR_NOTA_SIMPO_V@SIMKEU_PROD B ON A.NO_FAKTUR = B.NOMOR_NOTA
					// WHERE NO_FAKTUR = '$no_nota' AND (STATUS = 'NEW' OR STATUS = 'PERP' OR STATUS = 'KOREKSI')";

$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>