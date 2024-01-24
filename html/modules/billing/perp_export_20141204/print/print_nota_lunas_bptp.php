<?php
//require "login_check.php";

require_once(SITE_LIB.'tcpdf/config/lang/eng.php');
require_once(SITE_LIB.'tcpdf/tcpdf.php');

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

	// Page footer
	public function Footer() {
		// Position at 10 mm from bottom
		$this->SetY(-10);
		// Set font
		$this->SetFont('helvetica', 'I', 6);
		// Page number
		$this->Cell(0, 10, 'hal '.$this->getAliasNumPage().' dari '.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
	}
}

// create new PDF document
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// set header and footer fonts
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(5, 16, 8);
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->setPrintHeader(false);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

$db		 = getDB();
$p01 	 = $_GET['no_req'];
$id_user = $_SESSION['PENGGUNA_ID'];
$query   = "SELECT ID_NOTA FROM NOTA_STACKEXT_H WHERE TRIM(ID_REQUEST) = TRIM('$p01')";
$hasil   = $db->query($query);
$hasil_  = $hasil->fetchRow();
$notanya = $hasil_['ID_NOTA'];
$query   = "INSERT INTO LOG_NOTA (ID_NOTA,NO_REQUEST,KODE_MODUL,KETERANGAN,ID_USER, TANGGAL) VALUES ('$notanya','$p01','$kode','CETAK','$id_user',SYSDATE)";
$db->query($query);
$query   = "UPDATE NOTA_REEXPORT_H SET TGL_CETAK=SYSDATE WHERE ID_NOTA='$notanya'";
$db->query($query);

$p1=$notanya;
//print_r($p1);die;
$query	 = "SELECT A.*,B.*, SYSDATE AS TGL_PROSES,UPPER(B.USER_REQ) AS NAMA,to_char(A.INVOICE_DATE,'DD-MM-YYYY') TGL_NOTA2, to_char(A.ADM_NOTA,'999,999,999,999,990.00') ADM_NOTAN,to_char(A.PPN,'999,999,999,999,990.00') PPNN, to_char(A.JUMLAH,'999,999,999,999,999.00') TAGIHANN,to_char(A.TOTAL,'999,999,999,999,999.00') TOTALN
		    FROM NOTA_STACKEXT_H A, REQ_STACKEXT_H B WHERE A.ID_NOTA='$p1' AND A.ID_REQUEST=B.ID_REQ";
$data    = $db->query($query)->fetchRow();
$date    = date('d M Y H:i:s');
$query3  = "SELECT DISTINCT TGL_START_STACK, TGL_END_STACK FROM NOTA_STACKEXT_D WHERE ID_NOTA='$p1' AND ID_CONT IS NOT NULL";
$res3    = $db->query($query3);
$row3    = $res3->getAll();
foreach($row3 as $rows2) {
	$detail2 = $rows2[TGL_START_STACK]." s/d ".$rows2[TGL_END_STACK];
}
 
$query_dtl = "SELECT A.*,C.*, to_char(A.TARIF,'999,999,999,999,999.00') AS TARIFN, to_char(A.SUB_TOTAL,'999,999,999,999,999.00') AS SUB_TOTALN 
			  FROM NOTA_STACKEXT_D A, NOTA_STACKEXT_H B, MASTER_BARANG C WHERE A.ID_NOTA='$p1'  AND A.ID_NOTA=B.ID_NOTA AND C.KODE_BARANG=A.ID_CONT";
$res       = $db->query($query_dtl);
	
	// query adm
$query_adm = "SELECT to_char(TARIF,'999,999,999,999,999.00') AS ADM
			 FROM NOTA_STACKEXT_D WHERE KETERANGAN = 'ADMINISTRASI' AND  ID_REQ = '$p01'";
$ad        = $db->query($query_adm);
$adm       = $ad->fetchRow();
$biayaadm  = $adm['ADM'];
	//print_R($res);die;
$i=0;
	//unset($detail);
$row2=$res->getAll();
foreach($row2 as $rows) {
		$detail .= '
					<tr><td colspan="3" width="150"><font size="8">'.$rows[KETERANGAN].'</font></td>
                        <td width="65" align="center"><font size="8">'.$rows[TGL_START_STACK].'</font></td>
                        <td width="65" align="center"><font size="8">'.$rows[TGL_END_STACK].'</font></td>
                        <td width="32" align="center"><font size="8">'.$rows[JUMLAH_CONT].'</font></td>    
                        <td width="32" align="center"><font size="8">'.$rows[UKURAN].'</font></td>
                        <td width="32" align="center"><font size="8">'.$rows[TYPE].'</font></td>    
                        <td width="32" align="center"><font size="8">'.$rows[STATUS].'</font></td>    
                        <td width="32" align="center"><font size="8">'.$rows[HZ].'</font></td>
                        <td width="32" align="center"><font size="8">'.$rows[JUMLAH_HARI].'</font></td>
                        <td width="80" align="right"><font size="8">'.$rows[TARIFN].'</font></td>
                        <td width="40" align="center">IDR</td>
                        <td width="115" align="right"><font size="8">'.$rows[SUB_TOTALN].'</font></td>        
                    </tr>';
		$i++;
	}


// jumlah detail barangnya
$query_jum="SELECT COUNT(1) JUM_DETAIL FROM NOTA_STACKEXT_D A WHERE A.ID_NOTA='$p1'";
$data_jum   = $db->query($query_jum)->fetchRow();
$jum_data_page = 18;	//jumlah data dibatasi per page 18 data
$jum_page   = ceil($data_jum['JUM_DETAIL']/$jum_data_page);	//hasil bagi pembulatan ke atas
if(($data_jum[JUM_DETAIL]%$jum_data_page)>10 || ($data_jum[JUM_DETAIL]%$jum_data_page)==0)	$jum_page++;	//jika pada page terakhir jumlah data melebihi 12, tambah 1 page lagi
for($pg=1; $pg<=$jum_page; $pg++) {
	// add a page
	$pdf->AddPage();
	// set font
	$pdf->SetFont('courier', '', 9);
	
	$tbl = <<<EOD
			<table border='0'>
                <tr>
                    <td COLSPAN="14" align="left"><b>PT. PELABUHAN INDONESIA II (PERSERO)</b></td>
                </tr>
                <tr>
                    <td COLSPAN="14" align="left"><b></b></td>
                </tr>
                <tr>
                    <td COLSPAN="14" ALIGN="RIGHT"></td>
                    
                </tr>
                <tr>
                <td></td>
                </tr>
                <tr>
                    <td COLSPAN="9"></td>
                    <td COLSPAN="2" align="left">No. Nota</td>
                    <td COLSPAN="3" align="left">: $data[ID_NOTA]  </td>
                </tr>
                
                <tr>
                    <td COLSPAN="9"></td>
                    <td COLSPAN="2" align="left">No. Doc</td>
                    <td COLSPAN="3" align="left">: $data[ID_REQ]  </td>
                </tr>    
				<tr>
                    <td COLSPAN="9"></td>
                    <td COLSPAN="2" align="left">Tgl. Proses</td>
                    <td COLSPAN="3" align="left">: $date  </td>
                </tr>    
                <tr>
                <td></td>
                </tr>
                <tr>
                    <td COLSPAN="4"></td>
                    <td COLSPAN="10" align="right"><font size="11"><b>NOTA PERPANJANGAN EKSPORT</b></font></td>
                    
                </tr>
                <tr>
                <td></td>
                </tr>
                <tr>
                    <td COLSPAN="2"></td>
                    <td COLSPAN="4" align="left">$data[CUSTOMER]</td>
					<td colspan='8' width="400" align="right">MUAT/EXPORT</td>
                </tr>
                <tr>
                    <td COLSPAN="2"></td>
                    <td COLSPAN="4" align="left">$data[NPWP]</td>
                    <td colspan="7"></td>
                </tr>
                <tr>
                    <td COLSPAN="2"></td>
                    <td COLSPAN="4" align="left">$data[ALAMAT]</td>
					<td colspan='8' width="400" align="right">$data[TGL_MUAT]</td>
                </tr>
                <tr>
                    <td COLSPAN="2"></td>
                    <td COLSPAN="4" align="left">$data[VESSEL] / $data[VOYAGE]</td>
                    <td colspan="8"></td>
                </tr>
                <tr>
                    <td></td>
                </tr>
                <tr>
                <td></td>
                </tr> 
                <tr>
                <td></td>
                </tr>
                <tr>
                <td></td>
                </tr> 
                <tr>
                    <th colspan="3" width="150"><font size="8"><b>KETERANGAN</b></font></th>
                    <th width="65" align="left"><font size="8"><b>TGL AWAL</b></font></th>
                    <th width="65" align="left"><font size="8"><b>TGL AKHIR</b></font></th>
                    <th width="32" align="center"><font size="8"><b>BOX</b></font></th>
                    <th width="32" align="center"><font size="8"><b>SIZE</b></font></th>
                    <th width="32" align="center"><font size="8"><b>TYPE</b></font></th>
                    <th width="32" align="center"><font size="8"><b>STS</b></font></th>
                    <th width="32" align="center"><font size="8"><b>HZ</b></font></th>
                    <th width="32" align="center"><font size="8"><b>HARI</b></font></th>
                    <th width="80" align="center"><font size="8"><b>TARIF</b></font></th>
                    <th width="40" align="center"><font size="8"><b>VAL</b></font></th>
                    <th width="115" align="center"><font size="8" ><b>JUMLAH</b></font></th>
                </tr>
                <tr>
                    <td colspoan="14">
                        <hr style="border: 2px dashed #C0C0C0" color="#FFFFFF" size="6" width="700">
                    </td>
                </tr>
				$detail
				<tr>
                    <td colspoan="14">
                        <hr style="border: 2px dashed #C0C0C0" color="#FFFFFF" size="6" width="700">
                    </td>
                </tr>
				
                </table>
                
EOD;
$tbl .=<<<EOD
<table>                    
                    <tr>
                        <td colspan="7"></td>
                        <td width="180" colspan="3" align="right">Discount :</td>
                        <td width="120" colspan="2" align="right">0.00</td>
                    </tr>
                    <tr>
                        <td colspan="7"></td>
                        <td width="180" colspan="3" align="right">Administrasi :</td>
                        <td width="120" colspan="2" align="right">$biayaadm</td>
                    </tr>
                    <tr>
                        <td colspan="7"></td>
                        <td width="180" colspan="3" align="right">Dasar Pengenaan Pajak :</td>
                        <td width="120" colspan="2" align="right">$data[TAGIHANN]</td>
                    </tr>
                    <tr>
                        <td colspan="7"></td>
                        <td width="180" colspan="3" align="right">Jumlah PPN :</td>
                        <td width="120" colspan="2" align="right">$data[PPNN]</td>
                    </tr>
                    <tr>
                        <td colspan="7"></td>
                        <td width="180" colspan="3" align="right">Jumlah PPN Subsidi :</td>
                        <td width="120" colspan="2" align="right">0.00</td>
                    </tr>
                    <tr>
                        <td colspan="7"></td>
                        <td width="180" colspan="3" align="right">Jumlah Dibayar :</td>
                        <td width="120" colspan="2" align="right">$data[TOTALN]</td>
                    </tr>
                    </table>
<p>USER : $data[ID_USER]</p>
                <p></p>
                <p></p>
                <p></p>
                <p></p>
                <p></p>
                <p></p>
                <p></p>
                <p></p>
                <p></p>
                
EOD;
	
	$pdf->writeHTML($tbl, true, false, false, false, '');

	$limit1 = ($jum_data_page * ($pg-1)) + 1;	//limit bawah
	$limit2 = $jum_data_page * $pg;				//limit atas
	
	
	if($pg < $jum_page) {	//buat garis silang bagian bawah nota
		$style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '10,10', 'color' => array(0, 0, 0));
		$pdf->Line(10, 200, 205, 280, $style);		
		$pdf->Line(10, 280, 205, 200, $style);		
	}
}

while($i<10) {	// apabila jumlah barang kurang dari 12 pada page terakhir, ditambahkan space
	$space .= "<tr><td></td><tr>";
	$i++;
}

$pdf->ln();
$pdf->SetFont('courier', '', 8);
$pdf->Write(0, 'Nota sebagai faktur pajak berdasarkan Peraturan Dirjen Pajak', '', 0, 'L', true, 0, false, false, 0);


$pdf->Write(0, 'Nomor 10/PJ/2010 tanggal 9 Maret 2010', '', 0, 'L', true, 0, false, false, 0);$pdf->ln();
$pdf->ln();
$pdf->SetFont('courier', '', 9);
$pdf->Write(0, $data[TGL_NOTA], '', 0, 'R', true, 0, false, false, 0);

$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();
$pdf->SetFont('helvetica', 'B', 9);
//Close and output PDF document
$pdf->Output('sample.pdf', 'I');
?>