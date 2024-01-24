<?php
$yard_id	= $_SESSION["IDYARD_STORAGE"];
$db			= getDB("storage");

$query_yard		= "SELECT * FROM YARD_AREA WHERE ID = '$yard_id'";
$result_yard	= $db->query($query_yard);
$row_yard		= $result_yard->fetchRow();

$date		= date('dmy');

$namaFile = $date."-".$yard_id.".xls";
// Function penanda awal file (Begin Of File) Excel
function xlsBOF() {
	echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
	return;
}

// Function penanda akhir file (End Of File) Excel
function xlsEOF() {
	echo pack("ss", 0x0A, 0x00);
	return;
}

// Function untuk menulis data (angka) ke cell excel
function xlsWriteNumber($Row, $Col, $Value) {
	echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
	echo pack("d", $Value);
	return;
}

// Function untuk menulis data (text) ke cell excel
function xlsWriteLabel($Row, $Col, $Value ) {
	$L = strlen($Value);
	echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
	echo $Value;
	return;
}

// header file excel
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
 
// header untuk nama file
header("Content-Disposition: attachment; filename=".$namaFile."");
header("Content-Transfer-Encoding: binary ");

// memanggil function penanda awal file excel
xlsBOF();

// ------ membuat kolom pada excel --- //


// mengisi pada cell A1 (baris ke-0, kolom ke-0)
xlsWriteLabel(0,0,"NAMA LAPANGAN : ".$row_yard["NAMA_YARD"]);              

// mengisi pada cell A1 (baris ke-0, kolom ke-0)
xlsWriteLabel(1,0,"NO");              

// mengisi pada cell A2 (baris ke-0, kolom ke-1)
xlsWriteLabel(1,1,"NO_CONTAINER");             

// mengisi pada cell A3 (baris ke-0, kolom ke-2)
xlsWriteLabel(1,2,"NAMA BLOK");

// mengisi pada cell A4 (baris ke-0, kolom ke-3)
xlsWriteLabel(1,3,"SLOT");  

// mengisi pada cell A5 (baris ke-0, kolom ke-4)
xlsWriteLabel(1,4,"ROW");

// mengisi pada cell A6 (baris ke-0, kolom ke-4)
xlsWriteLabel(1,5,"TIER");

// -------- menampilkan data --------- //
// koneksi ke mysql
$db		= getDB("storage");

// query menampilkan semua data
$query 	= "SELECT PLACEMENT.NO_CONTAINER, PLACEMENT.SLOT_, PLACEMENT.ROW_, PLACEMENT.TIER_, BLOCKING_AREA.NAME FROM PLACEMENT INNER JOIN BLOCKING_AREA ON BLOCKING_AREA.ID = PLACEMENT.ID_BLOCKING_AREA WHERE BLOCKING_AREA.ID_YARD_AREA = '$yard_id'";
$result = $db->query($query);
$row	= $result->getAll();

// nilai awal untuk baris cell
$noBarisCell = 2;

// nilai awal untuk nomor urut data
$noData = 1;

foreach ($row as $data)
{

	// menampilkan no. urut data
	xlsWriteNumber($noBarisCell,0,$noData);
	
	// menampilkan data nim
	xlsWriteLabel($noBarisCell,1,$data['NO_CONTAINER']);
	
	// menampilkan data nama mahasiswa
	xlsWriteLabel($noBarisCell,2,$data['NAME']);
	
	// menampilkan data nilai
	xlsWriteNumber($noBarisCell,3,$data['SLOT_']);
	
	// menampilkan data nilai
	xlsWriteNumber($noBarisCell,4,$data['ROW_']);
	
	// menampilkan data nilai
	xlsWriteNumber($noBarisCell,5,$data['TIER_']);
	
	// increment untuk no. baris cell dan no. urut data
	$noBarisCell++;
	$noData++;
}

// memanggil function penanda akhir file excel
xlsEOF();
exit();

?>