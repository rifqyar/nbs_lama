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

$db = getDB();
$no_req = $_GET['no_req'];
$id_user=$_SESSION['PENGGUNA_ID'];
$query="SELECT ID_NOTA  FROM nota_receiving_h WHERE TRIM(ID_REQ) = TRIM('$no_req')";
$hasil=$db->query($query);
$hasil_=$hasil->fetchRow();
$notanya=$hasil_['ID_NOTA'];
$query="INSERT INTO LOG_NOTA (ID_NOTA,NO_REQUEST,KODE_MODUL,KETERANGAN,ID_USER) VALUES ('$notanya','$no_req','K','CETAK','$id_user')";

		$db->query($query);
$query="UPDATE NOTA_RECEIVING_H SET TGL_CETAK=SYSDATE WHERE ID_REQ='$no_req'";
$db->query($query);
$query="SELECT b.VESSEL, b.VOYAGE, b.TGL_STACK, b.TGL_MUAT, b.TGL_BONGKAR, a.ID_REQ, NVL(a.COA,a.ID_NOTA) ID_NOTA, NVL(a.NO_FAKTUR,'-') NO_FAKTUR, a.ID_USER, TO_CHAR(a.ADM_NOTA,'999,999,999,999') ADM_NOTA, a.EMKL, a.ALAMAT, a.NPWP,TO_CHAR(a.TAGIHAN,'999,999,999,999') TAGIHAN, TO_CHAR(a.PPN,'999,999,999,999') PPN, TO_CHAR(a.TOTAL,'999,999,999,999') TOTAL, a.STATUS, TO_CHAR(a.TGL_REQ,'dd/mm/yyyy') TGL_REQUEST 
                            FROM nota_receiving_h a, req_receiving_h b
							WHERE a.ID_REQ = '$no_req'
							AND a.ID_REQ = b.ID_REQ";
$data = $db->query($query)->fetchRow();
$date=date('d M Y H:i:s');
//print_r("SELECT * from TB_NOTA_DELIVERY_D A, TB_NOTA_DELIVERY_H B, MASTER_BARANG C WHERE A.ID_NOTA='$p1'  AND A.ID_NOTA=B.ID_NOTA AND C.KODE_BARANG=A.ID_CONT");die;
$query_dtl="SELECT a.KETERANGAN, a.TGL_START_STACK, a.TGL_END_STACK, a.JUMLAH_HARI, a.JUMLAH_CONT, b.UKURAN SIZE_, b.TYPE, b.STATUS, a.HZ, TO_CHAR(a.TARIF,'999,999,999,999') TARIF , TO_CHAR(a.SUB_TOTAL,'999,999,999,999') SUB_TOTAL FROM nota_receiving_d a, master_barang b WHERE a.ID_CONT = b.KODE_BARANG(+) AND a.ID_REQ = '$no_req'";
	$res = $db->query($query_dtl);
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
                        <td width="32" align="center"><font size="8">'.$rows[SIZE_].'</font></td>
                        <td width="32" align="center"><font size="8">'.$rows[TYPE].'</font></td>    
                        <td width="32" align="center"><font size="8">'.$rows[STATUS].'</font></td>    
                        <td width="32" align="center"><font size="8">'.$rows[HZ].'</font></td>
                        <td width="32" align="center"><font size="8">'.$rows[JUMLAH_HARI].'</font></td>
                        <td width="80" align="right"><font size="8">'.$rows[TARIF].'</font></td>
                        <td width="40" align="center">IDR</td>
                        <td width="115" align="right"><font size="8">'.$rows[SUB_TOTAL].'</font></td>        
                        </tr>                        
						
		';
		$i++;
	}


// jumlah detail barangnya
$query_jum="SELECT COUNT(1) JUM_DETAIL FROM NOTA_RECEIVING_D A WHERE A.ID_REQ='$no_req'";
$data_jum = $db->query($query_jum)->fetchRow();
$jum_data_page = 18;	//jumlah data dibatasi per page 18 data
$jum_page = ceil($data_jum['JUM_DETAIL']/$jum_data_page);	//hasil bagi pembulatan ke atas
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
                    <td COLSPAN="14" align="left"><b>CABANG TANJUNG PRIOK</b></td>
                </tr>
                <tr>
                    <td COLSPAN="14" ALIGN="RIGHT">$data[NO_FAKTUR]</td>
                    
                </tr>
                <tr>
                <td></td>
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
                    <td COLSPAN="9"></td>
                    <td COLSPAN="2" align="left">No. faktur</td>
                    <td COLSPAN="3" align="left">: $data[NO_FAKTUR]  </td>
                </tr>    
                <tr>
                <td></td>
                </tr>
                <tr>
                    <td COLSPAN="6"></td>
                    <td COLSPAN="8" align="right"><font size="11"><b>PENUMPUKAN / GERAKAN (RECEIVING PETIKEMAS)</b></font></td>
                    
                </tr>
                <tr>
                <td></td>
                </tr>
                <tr>
                    <td COLSPAN="2"></td>
                    <td COLSPAN="4" align="left">$data[EMKL]</td>
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
					<td colspan='8' width="400" align="right">$data[TGL_BONGKAR]</td>
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
                    <td width="150" align="left">PENUMPUKAN DARI </td>
                    <td width="10">:</td>
                    <td colspan="4">$data[TGL_STACK] s/d $data[TGL_MUAT]</td>
                    <td colspan="8"></td>
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
                        <td width="120" colspan="2" align="right">$data[ADM_NOTA]</td>
                    </tr>
                    <tr>
                        <td colspan="7"></td>
                        <td width="180" colspan="3" align="right">Dasar Pengenaan Pajak :</td>
                        <td width="120" colspan="2" align="right">$data[TAGIHAN]</td>
                    </tr>
                    <tr>
                        <td colspan="7"></td>
                        <td width="180" colspan="3" align="right">Jumlah PPN :</td>
                        <td width="120" colspan="2" align="right">$data[PPN]</td>
                    </tr>
                    <tr>
                        <td colspan="7"></td>
                        <td width="180" colspan="3" align="right">Jumlah PPN Subsidi :</td>
                        <td width="120" colspan="2" align="right">0.00</td>
                    </tr>
                    <tr>
                        <td colspan="7"></td>
                        <td width="180" colspan="3" align="right">Jumlah Dibayar :</td>
                        <td width="120" colspan="2" align="right">$data[TOTAL]</td>
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