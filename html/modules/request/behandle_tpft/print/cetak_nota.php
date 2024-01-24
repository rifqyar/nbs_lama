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

$db = getDB();
// 
$id_nota    = $_GET["id_nota"];
$username   = $_SESSION["NAMA_PENGGUNA"];

$header = "SELECT A.ID,
       A.NO_NOTA, 
       TO_CHAR (A.TGL_NOTA, 'DD/MM/RRRR HH:MI') TGL_NOTA,
       A.PERIODE,
       A.CUST_NAME,
	   A.CUST_TAX_NO,
	   A.CUST_ADDR,
       (SELECT COUNT (1)
                  FROM BIL_BHD_CONT_H
                 WHERE ID_NOTA = A.ID)JML_CONT,
      A.TAGIHAN,
      A.PPN,
      A.TOTAL_TAGIHAN
  FROM BIL_NOTA_BHD_H A
 WHERE A.ID = '$id_nota'";
$data = $db->query($header);
$row = $data->fetchRow();
		
$no_nota    	= $row['NO_NOTA'];
$tgl_nota   	= $row['TGL_NOTA'];
$cust_nama    	= $row['CUST_NAME'];
$cust_tax_no    = $row['CUST_TAX_NO'];
$cust_addr  	= $row['CUST_ADDR'];
$periode    	= $row['PERIODE'];
$jumlah_cont    = $row['JML_CONT'];
$dpp			= number_format($row['TAGIHAN'],0,',','.');
$ppn			= number_format($row['PPN'],0,',','.');
$total_tagihan	= number_format($row['TOTAL_TAGIHAN'],0,',','.');
$rupiah			= $row['TOTAL_TAGIHAN'];
$terbilang		= toTerbilang($rupiah);
	
$_SESSION["no_nota"]     = $no_nota;
$_SESSION["tgl_nota"]    = $tgl_nota;
$_SESSION["cust_nama"]   = $cust_nama;
$_SESSION["cust_tax_no"] = $cust_tax_no;
$_SESSION["cust_addr"]   = $cust_addr;
$_SESSION["periode"]     = $periode;
$_SESSION["jumlah_cont"] = $jumlah_cont;	
 
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');


class MYPDF extends TCPDF {

	//Page header
	public function Header() {
	    $this->SetFont('Courier', '', 9);
		    
		$html = '<table width="650" border="0">
			      <tr>
					<td colspan="7"></td>
				  </tr>
				  <tr>
					<td colspan="7"></td>
				  </tr>
				  <tr>
					<td colspan="7"></td>
				  </tr>
				  <tr>
					<td colspan="7"></td>
				  </tr>
				  <tr>
					<td width="120">&nbsp;</td>
					<td width="8">&nbsp;</td>
					<td width="230">&nbsp;</td>
					<td width="10">&nbsp;</td>
					<td width="120">NO FAKTUR PAJAK</td>
					<td width="11">:</td>
					<td width="150"><b>'.$_SESSION["no_nota"].'</b></td>
				  </tr>
				  <tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>TANGGAL NOTA </td>
					<td>:</td>
					<td>'.$_SESSION["tgl_nota"].'</td>
				  </tr>
				  <tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				  </tr>
				  <tr>
					<td colspan="7">&nbsp;</td>
				  </tr>
				  <tr>
					<td colspan="7"><hr /></td>
				  </tr>
				  <tr>
					<td colspan="7" align="center"><font size="12"><b>NOTA TAGIHAN PETIKEMAS BEHANDLE TPFT</b></font></td>
				  </tr>
				  <tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				  </tr>
				  <tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				  </tr>
				  <tr>
					<td>Perusahaan</td>
					<td>:</td>
					<td>'.$_SESSION["cust_nama"].'</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				  </tr>
				  <tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				  </tr>
				  <tr>
					<td>NPWP</td>
					<td>:</td>
					<td>'.$_SESSION["cust_tax_no"].'</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				  </tr>
				   <tr>
					<td>Alamat</td>
					<td>:</td>
					<td colspan="5">'.$_SESSION["cust_addr"].'</td>
				  </tr>
				  <tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				  </tr>
				  <tr>
					<td colspan="7"><hr /></td>
				  </tr>
				  <tr>
					<td>Periode Kegiatan</td>
					<td>:</td>
					<td><b>'.$_SESSION["periode"].'</b></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				  </tr>
				  <tr>
					<td>Jumlah Container</td>
					<td>:</td>
					<td>'.$_SESSION["jumlah_cont"].' Box</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				  </tr> 
				  <tr>
					<td colspan="7"><hr /></td>
				  </tr>
			</table>';
            
        $this->writeHTML($html, true, false, false, false, '');
	}

}

// create new PDF document
$pdf = new MYPDF('P','mm','A4', true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('ISWS');
$pdf->SetTitle('Nota '.$no_nota);

// remove default header/footer
$pdf->setPrintHeader(true);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

//set margins
$pdf->SetMargins($left = 10,
				 $top = 88,
				 $right = 4,
				 $keepmargins = true );
$pdf->SetHeaderMargin(5);
$pdf->SetFooterMargin(100);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set font
$pdf->SetFont('courier', '', 9);

// add a page
$pdf->AddPage();
       	
if($username==NULL)
{
	header('Location: '.HOME.'login/');
}
else
{ 
    
$html = '<table width="650" border="0">
		  <tr>
			<td colspan="4">Daftar Container Behandle</td>
			<td width="120">&nbsp;</td>
			<td width="11">&nbsp;</td>
			<td width="150">&nbsp;</td>
		  </tr>
		  <tr>
			<td colspan="7"><font size="8"><table border="1">
			        <tr align="center" bgcolor="#cccccc" >
					    <td width="25">No</td>
						<td width="80">No Container</td>
						<td width="30">Size</td>
						<td width="30">Type</td>
						<td width="40">Status</td>
						<td width="30">HZ</td>
						<td width="120">Vessel</td>
						<td width="50">Voyage</td>
						<td width="70">Mulai Penumpukan</td>
						<td width="70">Selesai Penumpukan</td>
						<td width="50">Durasi</td>
						<td width="60">Tagihan</td>
					</tr>';
					 $detail_nota  = "SELECT NO_CONTAINER,
											   SIZE_CONTAINER,
											   TYPE_CONTAINER,
											   STS_CONTAINER,
											   HZ,
											   VESSEL,
											   VOYAGE,
											   TO_CHAR (ATA, 'DD/MM/RRRR') START_DATE,
											   TO_CHAR (TGL_GATE_OUT, 'DD/MM/RRRR') END_DATE,
											   (TGL_GATE_OUT - ATA) DURASI,
											   TAGIHAN
										  FROM BIL_BHD_CONT_H
										 WHERE ID_NOTA = '$id_nota'";
					 $data1 = $db->query($detail_nota);
					 $i = 1;
					 while($detail_nota = $data1->fetchRow()) {
							$no_cont 	= $detail_nota[NO_CONTAINER];
							$size_cont 	= $detail_nota[SIZE_CONTAINER];
							$type_cont 	= $detail_nota[TYPE_CONTAINER];
							$sts_cont 	= $detail_nota[STS_CONTAINER];
							$hz 	    = $detail_nota[HZ];
							$vessel 	= $detail_nota[VESSEL];
							$voyage 	= $detail_nota[VOYAGE];
							$start_date = $detail_nota[START_DATE];
							$end_date 	= $detail_nota[END_DATE];
							$durasi 	= $detail_nota[DURASI];
							$tagihan 	= number_format($detail_nota[TAGIHAN],0,',','.');
							
$html .='           <tr>
					    <td width="25" align="center">'.$i++.'</td>
						<td width="80" align="center">'.$no_cont.'</td>
						<td width="30" align="center">'.$size_cont.'</td>
						<td width="30" align="center">'.$type_cont.'</td>
						<td width="40" align="center">'.$sts_cont.'</td>
						<td width="30" align="center">'.$hz.'</td>
						<td width="120" align="center">'.$vessel.'</td>
						<td width="50" align="center">'.$voyage.'</td>
						<td width="70" align="center">'.$start_date.'</td>
						<td width="70" align="center">'.$end_date.'</td>
						<td width="50" align="center">'.$durasi.' Hari</td>
						<td width="60" align="right">'.$tagihan.'</td>
					</tr>';
					}
$html .='			   </table></font>
			</td>
		  </tr>
		  <tr>
			<td colspan="8"><hr /></td>
		  </tr>
		  <tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td width="65">&nbsp;</td>
			<td width="150">Jumlah Sebelum PPN </td>
			<td>:</td>
			<td width="30">Rp.</td>
			<td align="right" width="120">'.$dpp.'</td>
		  </tr>
		   <tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>PPN 10% </td>
			<td>:</td>
			<td width="30">Rp.</td>
			<td align="right" width="120">'.$ppn.'</td>
		  </tr>
		  <tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>Jumlah Dibayarkan </td>
			<td>:</td>
			<td width="30">Rp.</td>
			<td align="right" width="120"><font size="12"><b>'.$total_tagihan.'</b></font></td>
		  </tr>
		  <tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		  </tr>
		  <tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		  </tr>
		   <tr>
			<td colspan="8">&nbsp;</td>
		   </tr>
		   <tr>
				<td colspan="10" width=""><font size="8">Nota Sebagai Faktur Pajak Berdasarkan Peraturan Dirjen Pajak PER-27/PJ./2011 Tanggal 19 September 2011</font></td>
			</tr>
			<tr>
			<td colspan="8">&nbsp;</td>
		   </tr>
			<tr>
			<td colspan="8"><font size="10"><b><i># '.$terbilang.' rupiah</i></b></font></td>
		   </tr>
		</table>';      



              
// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);


//Close and output PDF document
$pdf->Output('nota_behandle_tpft.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+
}     
?>