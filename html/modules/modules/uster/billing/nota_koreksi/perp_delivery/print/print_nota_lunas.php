<?php
//============================================================+
// File name   : example_002.php
// Begin       : 2008-03-04
// Last Update : 2010-08-08
//
// Description : Example 002 for TCPDF class
//               Removing Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               Manor Coach House, Church Hill
//               Aldershot, Hants, GU12 4RQ
//               UK
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Removing Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');

class MYPDF extends TCPDF {

	//Page header
	/* public function Header() {
		// Logo
		$image_file = 'images/bg_kanan.jpg';
		$this->Image($image_file, 20, 10, 40, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// Set font
		$this->SetFont('helvetica', 'B', 12);
		// Title
		$this->Cell(0, 15, 'PT PELABUHAN INDONESIA II (PERSERO)', 0, false, 'C', 0, '', 0, false, 'M', 'M');
	} */

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 0, 'Printed by '.$_SESSION["NAME"], 0, false, 'L', 0, '', 0, false, 'T', 'M');
	}
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 002');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// remove default header/footer
$pdf->setPrintHeader(false);
//$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

//set margins
$pdf->SetMargins($left = 0,
				 $top = 10,
				 $right = 0,
				 $keepmargins = false );
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set font
$pdf->SetFont('Courier', 15,9.5);


        // menambahkan halaman (harus digunakan minimal 1 kali)
        $pdf->AddPage($orientation='P',$format='',$keepmargins=false, $tocpage=false);
        
        $db 	 = getDB("storage");
        
        $no_nota = $_GET['no_nota'];
        $dt      = date('d-M-Y H:i:s');
        
       $query_get	= "SELECT c.NO_REQUEST, a.NO_NOTA, TO_CHAR(a.ADM_NOTA,'999,999,999,999') ADM_NOTA, TO_CHAR(a.PASS,'999,999,999,999') PASS, a.EMKL NAMA, a.ALAMAT, a.NPWP, c.PERP_DARI, a.LUNAS,a.NO_FAKTUR, TO_CHAR(a.TAGIHAN,'999,999,999,999') TAGIHAN, TO_CHAR(a.PPN,'999,999,999,999') PPN, TO_CHAR(a.TOTAL_TAGIHAN,'999,999,999,999') TOTAL_TAGIHAN, a.STATUS, TO_CHAR(c.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, CONCAT(TERBILANG(a.TOTAL_TAGIHAN),'rupiah') TERBILANG 
                            FROM nota_delivery a, request_delivery c where
                            a.NO_REQUEST = c.NO_REQUEST
                            and a.NO_NOTA = '$no_nota'";
	$result		= $db->query($query_get);
	$row_nota	   = $result->fetchRow();
        $no_nota        = $row_nota['NO_NOTA'];
        $no_request     = $row_nota['NO_REQUEST'];
        $no_faktur      = $row_nota['NO_FAKTUR'];
        $emkl           = $row_nota['NAMA'];
        $npwp           = $row_nota['NPWP'];
        $perp_dari      = $row_nota['PERP_DARI'];
        $alamat         = $row_nota['ALAMAT'];
        $status         = $row_nota['STATUS'];
        $tagihan        = $row_nota['TAGIHAN'];
        $formulir       = $row_nota['FORMULIR'];
        $ppn            = $row_nota['PPN'];
		$pass           = $row_nota['PASS'];
        $adm_nota       = $row_nota['ADM_NOTA'];
	 $terbilang	   = $row_nota['TERBILANG'];
        $total_tagihan  = $row_nota['TOTAL_TAGIHAN'];
		
			$pegawai    = "SELECT * FROM MASTER_PEGAWAI WHERE STATUS = 'AKTIF'";
		$result_	= $db->query($pegawai);
		$nama_peg	= $result_->fetchRow();
		$nama		= $nama_peg['NAMA_PEGAWAI'];
		$jabatan	= $nama_peg['JABATAN'];
		$nipp		= $nama_peg['NIPP'];	
        
//        
        ob_start();
        
        $tbl = <<<EOD
                
                <table border='1'>
                <tr>
                    <td COLSPAN="14" align="right"><font size="14"><b>$no_nota</b></font></td>
                </tr>
                <tr>
                    <td COLSPAN="14" align="left"></td>
                </tr>
                <tr>
                    <td COLSPAN="12"></td>
                    <td COLSPAN="2" align="right"></td>
                </tr>
                <tr>
                <td></td>
                </tr>
                <tr>
                    <td COLSPAN="9"></td>
                    <td COLSPAN="2" align="left"></td>
                    <td COLSPAN="3" align="left"></td>
                </tr>
                <tr>
                    <td COLSPAN="9"></td>
                    <td COLSPAN="2" align="left"></td>
                    <td COLSPAN="3" align="left"></td>
                </tr>    

                <tr>
                <td></td>
                </tr>
                <tr>
                    <td COLSPAN="7"></td>
                    <td COLSPAN="7" align="right"><font size="14"><b>NOTA PERPANJANGAN DELIVERY</b></font></td>
                  
                </tr>
                <tr>
                <td></td>
                </tr>
                <tr>
                    <td COLSPAN="2"></td>
                    <td COLSPAN="4" align="left"><b>$emkl</b></td>
                    <td colspan="8"></td>
                </tr>
                <tr>
                    <td COLSPAN="2"></td>
                    <td COLSPAN="4" align="left"><b>$npwp</b></td>
                    <td colspan="8"></td>
                </tr>
                <tr>
                    <td COLSPAN="2"></td>
                    <td COLSPAN="4" align="left"><b>$alamat</b></td>
                    <td colspan="8"></td>
                </tr>
                <tr>
                    <td COLSPAN="2"></td>
                    <td COLSPAN="4" align="left"></td>
                    <td colspan="8"></td>
                </tr>
                 <tr height="20">
					<td colspan="14"></td>
                </tr>
                 <tr>
                    <td width="150" align="left"><b>No. Doc</b></td>
                    <td width="30">:</td>
                    <td colspan="4"><b>$no_request</b></td>
                    <td colspan="8"></td>
                </tr>    
                <tr>
                    <td width="150" align="left"><b>Tgl. Proses</b> </td>
                    <td>:</td>
                    <td colspan="4"><b>$dt</b></td>
                    <td></td>
                    <td colspan="2"></td>
                    <td colspan="5" align="left"></td>
                </tr>    
                <tr>
                    <td width="150" align="left"><b>No. Faktur </b></td>
                    <td>:</td>
                    <td colspan="4"><b>$no_faktur</b></td>
                    <td colspan="8"></td>
                </tr>  
				<tr>
                    <td width="150" align="left"><b>No. Dokumen Lama </b></td>
                    <td>:</td>
                    <td colspan="4"><b>$perp_dari</b></td>
                    <td colspan="8"></td>
                </tr>  
                <tr height="20">
					<td colspan="14"></td>
                </tr>
                <tr>
                    <th colspan="3" width="200"><b>KETERANGAN</b></th>
					<th width="80" align="center"><b>TGL AWAL</b></th>
                    <th width="80" align="center"><b>TGL AKHIR</b></th>
                    <th width="30" align="center"><b>BOX</b></th>
                    <th width="25" align="center"><b>SZ</b></th>
                    <th width="25" align="center"><b>TY</b></th>
                    <th width="25" align="center"><b>ST</b></th>
                    <th width="25" align="center"><b>HZ</b></th>
                    <th width="25" align="center"><b>HR</b></th>
                    <th width="70" align="center"><b>TARIF</b></th>
                    <th width="30" align="center"><b>VAL</b></th>
                    <th width="88" align="right"><b>JUMLAH</b></th>
                </tr>
                 <tr>
                        <td colspan="14"><hr></td>
                  </tr>
                </table>
EOD;
    
        
        $query_get2	= "SELECT TO_CHAR(a.START_STACK,'dd/mm/yyyy') START_STACK,TO_CHAR(a.END_STACK,'dd/mm/yyyy') END_STACK, a.KETERANGAN, a.JML_CONT, a.JML_HARI, b.SIZE_, b.TYPE_, b.STATUS, a.HZ, TO_CHAR(a.TARIF,'999,999,999,999') TARIF , TO_CHAR(a.BIAYA,'999,999,999,999') BIAYA FROM nota_delivery_d a, iso_code b WHERE a.ID_ISO = b.ID_ISO(+) AND a.ID_NOTA = '$no_nota'";
	$result		= $db->query($query_get2);
	$row_detail	= $result->getAll();
        for($i=0;$i<count($row_detail);$i++){
                $tbl1 .=<<< EOD
                        <table>                        
                        <tr>
                        <td colspan="3" width="200">{$row_detail[$i]['KETERANGAN']}</td>
						<th width="80" align="center">{$row_detail[$i]['START_STACK']}</th>
						<th width="80" align="center">{$row_detail[$i]['END_STACK']}</th>
                        <td width="25" align="center">{$row_detail[$i]['JML_CONT']}</td>    
                        <td width="25" align="center">{$row_detail[$i]['SIZE_']}</td>
                        <td width="25" align="center">{$row_detail[$i]['TYPE_']}</td>    
                        <td width="25" align="center">{$row_detail[$i]['STATUS']}</td>    
                        <td width="25" align="center">{$row_detail[$i]['HZ']}</td>
                        <td width="25" align="center">{$row_detail[$i]['JML_HARI']}</td>
                        <td width="70" align="right">{$row_detail[$i]['TARIF']}</td>
                        <td width="30" align="center">IDR</td>
                        <td width="88" align="right">{$row_detail[$i]['BIAYA']}</td>        
                        </tr>                        
                        </table>
EOD;
        }
        
         $tbl3 .=<<<EOD
                                   
                <table border='1'>  
                     <tr>
                        <td colspan="13"><hr></td><td></td>

                  </tr>
                    <tr>
                        <td colspan="8"></td>
                        <td width="170" colspan="3" align="right">Discount :</td>
                        <td width="100" colspan="2" align="right"> - </td>
						<td></td>
                    </tr>
                    <tr>
                        <td colspan="8"></td>
                        <td width="170" colspan="3" align="right">Administrasi :</td>
                        <td width="100" colspan="2" align="right">$adm_nota</td><td></td>
                    </tr>
                    
                    <tr>
                        <td colspan="7"></td>
                        <td width="225" colspan="4" align="right">Dasar Pengenaan Pajak :</td>
                        <td width="100" colspan="2" align="right">$tagihan</td><td></td>
                    </tr>
                    <tr>
                        <td colspan="8"></td>
                        <td width="170" colspan="3" align="right">Jumlah PPN :</td>
                        <td width="100" colspan="2" align="right">$ppn</td><td></td>
                    </tr>
					  <tr>
                        <td colspan="8"></td>
                        <td width="170" colspan="3" align="right">Pass Truck :</td>
                        <td width="100" colspan="2" align="right">$pass</td><td></td>
                    </tr>
                    <tr>
                        <td colspan="8"></td>
                        <td width="170" colspan="3" align="right">Jumlah Dibayar :</td>
                        <td width="100" colspan="2" align="right">$total_tagihan</td><td></td>
                    </tr>
                    </table>
EOD;
                    
        $tbl4 .=<<<EOD
                <p></p>
                <p></p>
                <p></p>
                <p></p>
                
                <table>  
					<tr>
                        <td colspan="2"><b>Nota sebagai Faktur Pajak berdasarkan Peraturan Dirjen Pajak <br>
				Nomor 10/PJ/2010 Tanggal 9 Maret 2010</b></td>
                    </tr>		
					<tr height="20">
                        <td colspan="2"></td>
                    </tr>							
                    <tr>
                        <td width="200"></td>
                        <td width="700" align="center">MENGETAHUI :</td>
                    </tr>
                    <tr>
                         <td width="200"></td>
                        <td width="700" align="center"><b>$jabatan</b></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td width="200"></td>
                        <td width="700" align="center"><b><u>$nama</u></b></td>
                    </tr>
                    <tr>
                        <td width="200"></td>
                        <td width="700" align="center"><b>NIPP.$nipp</b></td>
                    </tr>
					 <tr>
                        <td colspan="2"></td>
                    </tr>
					 <tr>
                        <td colspan="2"></td>
                    </tr>
					 <tr>
                        <td colspan="2"></td>
                    </tr>
						<tr>
                        <td colspan="2"><b>$emkl</b></td>
                    </tr>
                    <tr>
                        <td colspan="2"><b><i> $terbilang </i></b></td>
                    </tr>
					                    </tr>
						<tr>
                        <td colspan="2"><b>NOTA PERPANJANGAN DELIVERY</b></td>
                    </tr>

                    </table>
EOD;
     
        ob_end_clean();

        $pdf->writeHTML($tbl, true, false, false, false, '');
        $pdf->writeHTML($tbl1, true, false, false, false, '');
        $pdf->writeHTML($tbl2, true, false, false, false, '');
        $pdf->writeHTML($tbl3, true, false, false, false, '');
        $pdf->writeHTML($tbl4, true, false, false, false, '');
        
        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);


        //Menutup dan menampilkan dokumen PDF
        $pdf->Output('example_001.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+
        
        
        ?>