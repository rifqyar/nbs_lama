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
// dfgsgfsfsgfg
/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Removing Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */
 
 $db 	 = getDB("storage");
        global $no_request;
        global $dt;
		global $no_nota;
        global $no_nota_;
        $no_req = $_GET['no_req'];
		$qtime = $db->query("SELECT TO_CHAR(SYSDATE, 'DD-MM-YYYY HH24:MI:SS') TIME_ FROM DUAL");
		$rtime = $qtime->fetchRow();
		$dt    = $rtime[TIME_];
        //$dt      = date('d-M-Y H:i:s');
        
       $query_get	= "SELECT a.NOTA_LAMA, c.NO_REQUEST, a.NO_NOTA, TO_CHAR(a.ADM_NOTA,'999,999,999,999') ADM_NOTA, 
	   TO_CHAR(a.PASS,'999,999,999,999') PASS, a.EMKL NAMA, a.ALAMAT, a.NPWP, c.PERP_DARI, a.LUNAS,a.NO_FAKTUR, c.NO_DO, c.NO_BL,
	   TO_CHAR(a.TAGIHAN,'999,999,999,999') TAGIHAN, TO_CHAR(a.PPN,'999,999,999,999') PPN, 
	   TO_CHAR(a.TOTAL_TAGIHAN,'999,999,999,999') TOTAL_TAGIHAN, a.STATUS, TO_CHAR(c.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, inh.NO_NOTA_MTI, inh.NO_FAKTUR_MTI,
	   CONCAT(TERBILANG(a.TOTAL_TAGIHAN),'rupiah') TERBILANG, c.NO_DO, c.NO_BL, m.NAME NAMA_LENGKAP, CASE WHEN TRUNC(TGL_NOTA) < TO_DATE('1/6/2013','DD/MM/RRRR')
		THEN a.NO_NOTA
		ELSE A.NO_FAKTUR END NO_FAKTUR_
                            FROM nota_stripping a, request_stripping c, BILLING_NBS.tb_user m, itpk_nota_header inh where
                            a.NO_REQUEST = c.NO_REQUEST
							AND a.nipp_user = m.id(+)
							AND a.TGL_NOTA = (SELECT MAX(d.TGL_NOTA) FROM NOTA_STRIPPING d WHERE d.NO_REQUEST = '$no_req' )
                            and a.NO_REQUEST = '$no_req'
							and c.NO_REQUEST = inh.NO_REQUEST";
	
    //untuk kontainer
		$qcont = "SELECT A.NO_CONTAINER,B.SIZE_,B.TYPE_ FROM CONTAINER_STRIPPING A, MASTER_CONTAINER B WHERE A.NO_CONTAINER = B.NO_CONTAINER AND A.NO_REQUEST = '$no_req'";
		$rcont = $db->query($qcont)->getAll();
		$listcont = " ";
		foreach($rcont as $rc){
			$listcont .= "<tr>
							<td width='150'><b>".$rc[NO_CONTAINER]."-".$rc[SIZE_]."</b></td>
							<td></td>
							<td colspan='4'></td>
							<td colspan='8'></td>
						   </tr>";
		}
		

	$result		= $db->query($query_get);
	$row_nota	   = $result->fetchRow();
        $no_nota_        = $row_nota['NO_NOTA'];
		$no_nota        = $row_nota['NO_FAKTUR_'];
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
		 $nota_lama       = $row_nota['NOTA_LAMA'];
	 $terbilang	   = $row_nota['TERBILANG'];
        $total_tagihan  = $row_nota['TOTAL_TAGIHAN'];
        $no_do  = $row_nota['NO_DO'];
        $no_bl  = $row_nota['NO_BL'];
        $nm_insert  = $row_nota['NAMA_LENGKAP'];
		
		$pegawai    = "SELECT * FROM MASTER_PEGAWAI WHERE STATUS = 'AKTIF'";
		$result_	= $db->query($pegawai);
		$nama_peg	= $result_->fetchRow();
		$nama		= $nama_peg['NAMA_PEGAWAI'];
		$jabatan	= $nama_peg['JABATAN'];
		$nipp		= $nama_peg['NIPP'];
		
		$_SESSION["no_nota"] = $no_nota;
		if ($nota_lama == NULL){
			$_SESSION["nota_lama"] = "";
		} else {
			$_SESSION["nota_lama"] = "EX.".$nota_lama;
		}
		
		$_SESSION["jabatan"] = $jabatan;
		$_SESSION["nama_pegawai"] = $nama;
		$_SESSION["nipp"] = $nipp;
		$_SESSION["emkl"] = $emkl;
		$_SESSION["npwp"] = $npwp;
		$_SESSION["alamat"] = $alamat;
		$_SESSION["terbilang"] = $terbilang;
		$bilang = ucwords($_SESSION['terbilang']);
		$_SESSION["total_tagihan"] = $total_tagihan;
		$_SESSION["date"] = $dt;
		$_SESSION["NO_BL"] = $no_bl;
		$_SESSION["NO_DO"] = $no_do;
		$_SESSION["PRINTED_BY"] = $nm_insert;
		$_SESSION["KET_NOTA"] = "Nota Berlaku Sebagai Pajak Berdasarkan Peraturan Dirjen Pajak PER-13/PJ/2019";


require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');

class MYPDF extends TCPDF {

	//Page header
	public function Header() {
		// Logo
		//$image_file = 'images/bg_kanan.jpg';
		//$this->Image($image_file, 20, 10, 40, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// Set font
		global $no_request;
		global $dt;
		global $no_nota;
		global $no_nota_;

		$image_file = 'images/MTI-Logo.jpg';
		$this->Image($image_file, -10, -2, 60, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// Set font
		$this->SetFont('', 'B', 12);
		$this->SetX(40);
		$this->SetY(3);
		$this->Cell(0, 16, '', 0, false, 'L', 0, '', 0, false, 'T', 'M');
		$this->SetFont('', 'B', 12);
		$this->SetY(0);
		$this->SetX(40);
		$this->Cell(0, 16, 'PT MULTI TERMINAL INDONESIA', 0, false, 'L', 0, '', 0, false, 'T', 'M');
		$this->SetFont('', 'L', 8);
		$this->SetY(6);
		$this->SetX(40);
		$this->Cell(0, 16, 'Alamat ', 0, false, 'L', 0, '', 0, false, 'T', 'M');
		$this->SetFont('', 'L', 8);
		$this->SetY(12);
		$this->SetX(40);
		$this->Cell(0, 16, 'NPWP ', 0, false, 'L', 0, '', 0, false, 'T', 'M');

		$this->SetFont('', 'L', 8);
		$this->SetY(6);
		$this->SetX(50);
		$this->Cell(0, 16, ' : ', 0, false, 'L', 0, '', 0, false, 'T', 'M');
		$this->SetFont('', 'L', 8);
		$this->SetY(12);
		$this->SetX(50);
		$this->Cell(0, 16, ' : ', 0, false, 'L', 0, '', 0, false, 'T', 'M');
		$this->SetFont('', 'L', 8);
		$this->SetY(6);
		$this->SetX(52);
		$this->Cell(0, 16, '  Jl. Pulau Payung No. 1 Tanjung Priok ', 0, false, 'L', 0, '', 0, false, 'T', 'M');
		$this->SetFont('', 'L', 8);
		$this->SetY(12);
		$this->SetX(52);
		$this->Cell(0, 16, ' 02.106.620.4-093.000  ', 0, false, 'L', 0, '', 0, false, 'T', 'M');

		$this->SetFont('', 'L', 8);
		$this->SetY(12);
		$this->SetX(160);
		$this->Cell(0, 16, 'No. Nota ', 0, false, 'L', 0, '', 0, false, 'T', 'M');
		$this->SetFont('', 'L', 8);
		$this->SetY(16);
		$this->SetX(160);
		$this->Cell(0, 16, 'No. Faktur ', 0, false, 'L', 0, '', 0, false, 'T', 'M');
		$this->SetFont('', 'L', 8);
		$this->SetY(20);
		$this->SetX(160);
		$this->Cell(0, 16, 'No. Request ', 0, false, 'L', 0, '', 0, false, 'T', 'M');
		$this->SetFont('', 'L', 8);
		$this->SetY(24);
		$this->SetX(160);
		$this->Cell(0, 16, 'Date Of Request ', 0, false, 'L', 0, '', 0, false, 'T', 'M');

		$this->SetFont('', 'L', 8);
		$this->SetY(12);
		$this->SetX(182);
		$this->Cell(0, 16, ' : ', 0, false, 'L', 0, '', 0, false, 'T', 'M');
		$this->SetFont('', 'L', 8);
		$this->SetY(16);
		$this->SetX(182);
		$this->Cell(0, 16, ' : ', 0, false, 'L', 0, '', 0, false, 'T', 'M');
		$this->SetFont('', 'L', 8);
		$this->SetY(20);
		$this->SetX(182);
		$this->Cell(0, 16, ' : ', 0, false, 'L', 0, '', 0, false, 'T', 'M');
		$this->SetFont('', 'L', 8);
		$this->SetY(24);
		$this->SetX(182);
		$this->Cell(0, 16, ' : ', 0, false, 'L', 0, '', 0, false, 'T', 'M');

		$this->SetFont('', 'L', 8);
		$this->SetY(12);
		$this->SetX(184);
		$this->Cell(0, 16,$no_nota_, 0, false, 'L', 0, '', 0, false, 'T', 'M');
		$this->SetFont('', 'L', 8);
		$this->SetY(16);
		$this->SetX(184);
		$this->Cell(0, 16, $no_nota, 0, false, 'L', 0, '', 0, false, 'T', 'M');
		$this->SetFont('', 'L', 8);
		$this->SetY(20);
		$this->SetX(184);
		$this->Cell(0, 16, $no_request, 0, false, 'L', 0, '', 0, false, 'T', 'M');
		$this->SetFont('', 'L', 8);
		$this->SetY(24);
		$this->SetX(184);
		$this->Cell(0, 16, $dt, 0, false, 'L', 0, '', 0, false, 'T', 'M');
		// $this->SetFont('', 'L', 8);
		// $this->SetY(12);
		// //$this->Cell(0, 16, $_SESSION["no_nota"], 0, false, 'R', 0, '', 0, false, 'T', 'M');
		// $this->SetFont('', 'L', 14);
		// $this->SetY(12);
		// //$this->Cell(0, 15, $_SESSION["nota_lama"], 0, false, 'R', 0, '', 0, false, 'T', 'M');
		// $this->SetFont('', 'L', 14);
		// $this->SetY(12);
		// //$this->Cell(0, 15, $_SESSION["NO_DO"], 0, false, 'R', 0, '', 0, false, 'T', 'M');
		// $this->SetFont('', 'L', 14);
		// $this->SetY(12);
		// $this->Cell(0, 15, $_SESSION["NO_BL"], 0, false, 'R', 0, '', 0, false, 'T', 'M');
		// $this->SetFont('', 'L', 8);
		// $this->SetY(12);
		// $this->SetX(60);
		//$this->MultiCell(60, 45, $_SESSION["KET_NOTA"], 0, false, 'R', 0, '', 0, false, 'T', 'M');
		// $this->MultiCell(110,45,$_SESSION["KET_NOTA"],
		// 0,
		// 'R',
		// false,
		// 1,
		// '',
		// '',
		// true,
		// 0,
		// false,
		// true,
		// 0,
		// 'T',
		// false 
		// );
		$this->SetFont('', 'B', 12);
		$this->SetY(36);
		$this->Cell(0, 15, 'NOTA STRIPPING', 0, false, 'C', 0, '', 0, false, 'M', 'M');
		$this->SetFont('', '', 12);
		$this->SetY(42);
		$this->SetX(5);
		$this->Cell(0, 15, $_SESSION["emkl"], 0, false, '', 0, '', 0, false, 'T', 'M');
		$this->SetFont('', '', 12);
		$this->SetY(47);
		$this->SetX(5);
		$this->Cell(0, 15, $_SESSION["npwp"], 0, false, '', 0, '', 0, false, 'T', 'M');
		$this->SetFont('', '',12);
		$this->SetY(58);
		$this->SetX(5);
		$this->MultiCell(150,70,$_SESSION["alamat"],
		0,
		'L',
		false,
		1,
		'',
		'',
		true,
		0,
		false,
		true,
		0,
		'T',
		false 
		);
		$this->SetFont('', '', 11);
	}

	// Page footer
	public function Footer() {
		/*// Position at 15 mm from bottom
		$this->SetFont('Courier', 'B', 9.5);
		$this->SetY(-124);
		$this->Cell(0, 0, '' , 0, false, 'L', 0, '', 0, false, 'T', 'M');
		$this->SetFont('Courier', 'B', 9.5);
		$this->SetY(-120);
		$this->Cell(0, 0, '' , 0, false, 'L', 0, '', 0, false, 'T', 'M');
		$this->SetFont('Courier', '', 9.5);
		$this->SetY(-111);
		$this->SetX(-66);
		//$this->Cell(0, 0, 'MENGETAHUI :' , 0, false, '', 0, '', 0, false, 'T', 'M');
		$this->SetFont('Courier', 'B', 9.5);
		$this->SetY(-107);
		$this->SetX(-76);
		//$this->Cell(0, 0, $_SESSION["jabatan"] , 0, false, '', 0, '', 0, false, 'T', 'M');		
		$this->SetFont('Courier', 'B U', 9.5);
		$this->SetY(-82);
		$this->SetX(-70);
		//$this->Cell(0, 0, $_SESSION["nama_pegawai"] , 0, false, '', 0, '', 0, false, 'T', 'M');
		$this->SetFont('Courier', 'B', 9.5);
		$this->SetY(-78);
		$this->SetX(-68);
		//$this->Cell(0, 0, 'NIPP.'.$_SESSION["nipp"] , 0, false, '', 0, '', 0, false, 'T', 'M');*/		
		// $this->SetFont('', 'B', 11);
		// $this->SetY(-40);
		// $this->Cell(0, 0, $_SESSION["no_nota"] , 0, false, 'R', 0, '', 0, false, 'T', 'M');
		// $this->SetFont('', 'B', 11);
		// $this->SetY(-28);
		// $this->SetX(50);
		// $this->Cell(0, 0, $_SESSION["emkl"] , 0, false, '', 0, '', 0, false, 'T', 'M');
		// $this->SetFont('', 'B I', 11);
		// $this->SetY(-23);
		// $this->SetX(50);
		// $this->Cell(0, 0,	$_SESSION["total_tagihan"] , 0, false, '', 0, '', 0, false, 'T', 'M');
		// $this->SetFont('', 'B', 11);
		// $this->SetY(-16);
		// $this->SetX(50);
		// $this->Cell(0, 0, 'NOTA STRIPPING' , 0, false, '', 0, '', 0, false, 'T', 'M');
		// $this->SetFont('', 'B', 11);
		// $this->SetY(-12);
		// $this->Cell(0, 0, $_SESSION["date"] , 0, false, 'R', 0, '', 0, false, 'T', 'M');
		// $this->SetFont('', 'B I', 9);
		
		// $this->SetY(-7);
		// $this->SetX(50);
		// $this->Cell(0, 0, $_SESSION["terbilang"] , 0, false, '', 0, '', 0, false, 'T', 'M');		
		// $this->SetFont('Courier', 'B', 11);
		// $this->SetY(-60);
		// $this->SetX(0);
		// $this->Cell(0, 0, 'Printed by '.$_SESSION["PRINTED_BY"], 0, false, 'L', 0, '', 0, false, 'T', 'M');
		// if($_GET["first"] != 1){
		// 	$this->SetY(-55);
		// 	$this->SetX(0);
		// 	$this->Cell(0, 0, 'Reprinted by '.$_SESSION["NAME"], 0, false, 'L', 0, '', 0, false, 'T', 'M');
		// }
		
	}

}

$pdf->PageFormats=array('a3'=>array(841.89,1190.55),
'a4'=>array(610.28,893.75), 'a5'=>array(420.94,595.28),
'letter'=>array(612,792), 'legal'=>array(612,1008), 'tes'=>array(610.28,893.75));

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'tes', true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 002');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// remove default header/footer
$pdf->setPrintHeader(true);
$pdf->setPrintFooter(true);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

//set margins
/* $pdf->SetMargins($left = 0,
				 $top = 10,
				 $right = 0,
				 $keepmargins = false ); */
$pdf->SetMargins('4', '70px', '0','0');
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, '60px');

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set font
$pdf->SetFont('', 15,9.8);


        // menambahkan halaman (harus digunakan minimal 1 kali)
		$resolution= array(215,305);
		$pdf->AddPage('P', $resolution);
       // $pdf->AddPage($orientation='P',$format='',$keepmargins=false, $tocpage=false);
       	
        
//        
        ob_start();
        
        $tbl = <<<EOD
                
                <table border='0'>
				<tr height="20">
                    <td width="150" align="left"><b></b></td>
                    <td width="30"></td>
                    <td colspan="4"><b></b></td>
                    <td colspan="8"></td>
                </tr>    
                <tr>
                    <td width="150" align="left">No. DO</td>
                    <td width="10">:</td>
                    <td colspan="4">$no_do</td>
                    <td colspan="8"></td>
                </tr>    
                <tr>
                    <td width="150" align="left">No. BL </td>
                    <td width="10">:</td>
                    <td colspan="4">$no_bl</td>
                    <td colspan="2"></td>
                    <td colspan="5" align="left"></td>
                </tr>    
                <tr>
                    <td width="150" align="left">No. Document </td>
                    <td>:</td>
                    <td colspan="4">-</td>
                    <td width="150" align="left">Date Of Static </td>
                    <td width="10">:</td>
                    <td>-</td>
                </tr>  
				<tr>
                    <td width="150" align="left">Date of Document </td>
                    <td>:</td>
                    <td colspan="4">-</td>
                    <td width="150" align="left">Date Of Delivery </td>
                    <td width="10">:</td>
                    <td>-</td>
                </tr>

                <tr>
                    <td width="150" align="left"></td>
                    <td width="10"></td>
                    <td colspan="4"></td>
                    <td colspan="8"></td>
                </tr>
				
				$listcont  

                <tr height="20">
					<td colspan="14"></td>
                </tr>
                <tr>
                    <th colspan="3" width="200"><b>KETERANGAN</b></th>
					<th width="80" align="center"><b>TGL AWAL</b></th>
                    <th width="80" align="center"><b>TGL AKHIR</b></th>
                    <th width="30" align="center"><b>BOX</b></th>
                    <th width="30" align="center"><b>SIZE</b></th>
                    <th width="40" align="center"><b>TYPE</b></th>
                    <th width="50" align="center"><b>STATUS</b></th>
                    <th width="25" align="center"><b>HZ</b></th>
                    <th width="25" align="center"><b>HR</b></th>
                    <th width="70" align="center"><b>TARIF</b></th>
                    <th width="30" align="center"><b>VAL</b></th>
                    <th width="80" align="right"><b>JUMLAH</b></th>
                </tr>
                 <tr>
                        <td colspan="14"><hr></td>
                  </tr>
                </table>
EOD;
    
        
        /* $query_get2	= "SELECT TO_CHAR(a.START_STACK,'dd/mm/yyyy') START_STACK,TO_CHAR(a.END_STACK,'dd/mm/yyyy') END_STACK, a.KETERANGAN, a.JML_CONT, a.JML_HARI, b.SIZE_, b.TYPE_, b.STATUS, a.HZ, TO_CHAR(a.TARIF,'999,999,999,999') TARIF , TO_CHAR(a.BIAYA,'999,999,999,999') BIAYA FROM nota_stripping_d a, iso_code b WHERE a.KETERANGAN <> 'ADMIN NOTA' 
		AND a.ID_ISO = b.ID_ISO(+) AND a.NO_NOTA = (SELECT MAX(d.NO_NOTA) FROM NOTA_STRIPPING d WHERE d.NO_REQUEST = '$no_req') ORDER BY a.URUT"; */
		/* $query_get2 = "SELECT TO_CHAR (a.START_STACK, 'dd/mm/yyyy') START_STACK,
         TO_CHAR (a.END_STACK, 'dd/mm/yyyy') END_STACK,
         a.tekstual keterangan, a.JML_CONT,
         --case a.tekstual when 'PAKET STRIPPING'
         --THEN (select count(no_container) from container_stripping where no_request = '$no_req')
         --ELSE 
          --a.JML_CONT
          --END AS JML_CONT,
         a.JML_HARI,
         b.SIZE_,
         b.TYPE_,
         case a.tekstual when 'PAKET STRIPPING'
         THEN '-'
         ELSE 
          b.STATUS
          END AS STATUS,
         a.HZ,
         TO_CHAR(SUM(a.TARIF),'999,999,999,999') TARIF,
         TO_CHAR(SUM(a.BIAYA),'999,999,999,999') BIAYA,
         case a.tekstual when 'PAKET STRIPPING'
         THEN 10
         ELSE 
          a.urut
          END AS urut
    FROM nota_stripping_d a, iso_code b
   WHERE     a.KETERANGAN <> 'ADMIN NOTA'
         AND a.ID_ISO = b.ID_ISO(+)
         AND a.NO_NOTA = (SELECT MAX (d.NO_NOTA)
                            FROM NOTA_STRIPPING d
                           WHERE d.NO_REQUEST = '$no_req')
    GROUP BY a.tekstual, a.START_STACK, a.END_STACK,  a.JML_CONT,  b.SIZE_,  b.TYPE_, a.HZ, a.JML_HARI, case a.tekstual when 'PAKET STRIPPING'
         THEN '-'
         ELSE 
          b.STATUS
          END,
          case a.tekstual when 'PAKET STRIPPING'
         THEN 10
         ELSE 
          a.urut
          END
    ORDER BY urut"; */
	
	$query_get2 = "SELECT a.JML_HARI,
                             TO_CHAR(SUM(a.TARIF),'999,999,999,999') TARIF,
                             TO_CHAR(SUM(a.BIAYA),'999,999,999,999') BIAYA,
                             a.tekstual KETERANGAN,
                             a.HZ,a.JML_CONT,
                              --case a.tekstual when 'PAKET STRIPPING'
                             --THEN (select count(no_container) from container_stripping where no_request = '$no_req')
                             --ELSE 
                              --a.JML_CONT
                              --END AS JML_CONT,
                             TO_DATE (a.START_STACK, 'dd/mm/rrrr') START_STACK,
                             TO_DATE (a.END_STACK, 'dd/mm/rrrr') END_STACK,
                             b.SIZE_,
                             b.TYPE_,
                              case a.tekstual when 'PAKET STRIPPING'
                             THEN '-'
                             ELSE 
                              b.STATUS
                              END AS STATUS,
                              case a.tekstual when 'PAKET STRIPPING'
                             THEN 10
                             ELSE 
                              a.urut
                              END AS urut
                        FROM nota_stripping_d a, iso_code b
                       WHERE     a.KETERANGAN <> 'ADMIN NOTA' AND a.KETERANGAN NOT LIKE '%PENUMPUKAN%'
                             AND a.id_iso = b.id_iso
                             AND a.no_nota = '$no_nota_'
                    GROUP BY a.tekstual, a.jml_hari, a.hz, a.jml_cont, a.start_stack, a.end_stack, b.size_, b.type_,  case a.tekstual when 'PAKET STRIPPING'
                             THEN '-'
                             ELSE 
                              b.STATUS
                              END, case a.tekstual when 'PAKET STRIPPING'
                             THEN 10
                             ELSE 
                              a.urut
                              END
                   UNION ALL
                   SELECT a.JML_HARI,
                             TO_CHAR(a.TARIF,'999,999,999,999') TARIF,
                             TO_CHAR(a.BIAYA,'999,999,999,999') BIAYA,
                             a.tekstual KETERANGAN,
                             a.HZ,a.JML_CONT,
                              --case a.tekstual when 'PAKET STRIPPING'
                             --THEN (select count(no_container) from container_stripping where no_request = '$no_req')
                             --ELSE 
                              --a.JML_CONT
                              --END AS JML_CONT,
                             TO_DATE (a.START_STACK, 'dd/mm/rrrr') START_STACK,
                             TO_DATE (a.END_STACK, 'dd/mm/rrrr') END_STACK,
                             b.SIZE_,
                             b.TYPE_,
                              case a.tekstual when 'PAKET STRIPPING'
                             THEN '-'
                             ELSE 
                              b.STATUS
                              END AS STATUS,
                              case a.tekstual when 'PAKET STRIPPING'
                             THEN 10
                             ELSE 
                              a.urut
                              END AS urut
                        FROM nota_stripping_d a, iso_code b
                       WHERE     a.KETERANGAN <> 'ADMIN NOTA' AND a.KETERANGAN LIKE '%PENUMPUKAN%'
                             AND a.id_iso = b.id_iso
                             AND a.no_nota = '$no_nota_'
                    ORDER BY urut ASC";
	
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
                        <td width="30" align="center">{$row_detail[$i]['SIZE_']}</td>
                        <td width="40" align="center">{$row_detail[$i]['TYPE_']}</td>    
                        <td width="50" align="center">{$row_detail[$i]['STATUS']}</td>    
                        <td width="25" align="center">{$row_detail[$i]['HZ']}</td>
                        <td width="25" align="center">{$row_detail[$i]['JML_HARI']}</td>
                        <td width="70" align="right">{$row_detail[$i]['TARIF']}</td>
                        <td width="30" align="center">IDR</td>
                        <td width="80" align="right">{$row_detail[$i]['BIAYA']}</td>        
                        </tr>                        
                        </table>
EOD;
        }

        $tbl1 .=<<<EOD
<tr>
	<td colspan="14"><hr></td>
</tr>
EOD;
        
         $tbl3 .=<<<EOD
                                   
                <table border='1'>
                    <tr>
                        <td colspan="8"></td>
                        <td width="225" colspan="4" align="right">Discount :</td>
                        <td width="100" colspan="2" align="right"> - </td>
						<td></td>
                    </tr>
                    <tr>
                        <td colspan="8"></td>
                        <td width="225" colspan="4" align="right">Administrasi :</td>
                        <td width="100" colspan="2" align="right">$adm_nota</td><td></td>
                    </tr>
                    
                    <tr>
                        <td colspan="8"></td>
                        <td width="225" colspan="4" align="right">Dasar Pengenaan Pajak :</td>
                        <td width="100" colspan="2" align="right">$tagihan</td><td></td>
                    </tr>
                    <tr>
                        <td colspan="8"></td>
                        <td width="225" colspan="4" align="right">Jumlah PPN :</td>
                        <td width="100" colspan="2" align="right">$ppn</td><td></td>
                    </tr>
					 
                    <tr>
                        <td colspan="8"></td>
                        <td width="225" colspan="4" align="right">Jumlah Dibayar :</td>
                        <td width="100" colspan="2" align="right">$total_tagihan</td><td></td>
                    </tr>
                    </table>
EOD;
         /*
 <tr>
                        <td colspan="8"></td>
                        <td width="170" colspan="3" align="right">Pass Truck :</td>
                        <td width="100" colspan="2" align="right">$pass</td><td></td>
                    </tr>

*/		 
        $tbl5 .= <<<EOD
                
                <table border='1'>
				<tr height="20">
                    <td width="500" align="left"><b></b></td>
                </tr>    
                <tr>
                    <td width="500" align="left">Nota sebagai faktur pajak berdasarkan Peraturan Dirjen Pajak</td>
                </tr>    
                <tr>
                    <td width="500" align="left">Per - 27/PJ/2011 tanggal 19 September 2011</td>
                </tr>    
                <tr>
                    <td width="500" align="left"># $bilang </td>
                </tr>  
				<tr>
                    <td width="500" align="left">Ketentuan : </td>
                </tr>
				<tr>
                    <td width="500" align="left">1. Pengajuan keberatan hanya dapat dilakukan dalam waktu 14 hari setelah tanggal nota </td>
                </tr>
				<tr>
                    <td width="500" align="left">2. Terhadap nota yang diajukan keberatan harus dilunasi terlebih dahulu</td>
                </tr>
                </table>
EOD;

        $tbl4 .=<<<EOD
                <p></p>
                <table>
					<tr>
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
                        <td width="200"></td>
                        <td width="700" align="center"><b><u>$nama</u></b></td>
                    </tr>
                    <tr>
                        <td width="200"></td>
                        <td width="700" align="center"><b>NIPP.$nipp</b></td>
                    </tr>
					 <tr height="200">
                        <td colspan="2"></td>
                    </tr>
                    </table>
EOD;
     
        ob_end_clean();

        $pdf->writeHTML($tbl, true, false, false, false, '');
        $pdf->writeHTML($tbl1, true, false, false, false, '');
        $pdf->writeHTML($tbl2, true, false, false, false, '');
        $pdf->writeHTML($tbl3, true, false, false, false, '');     
        $pdf->writeHTML($tbl5, true, false, false, false, '');
        $pdf->writeHTML($tbl4, true, false, false, false, '');
        
        // Print text using writeHTMLCell()
        //$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);


        //Menutup dan menampilkan dokumen PDF
        $pdf->Output('nota_stripping.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+
        
        $_SESSION["nota_lama"] = '';
		$_SESSION["no_nota"] = '';
		$_SESSION["jabatan"] = '';
		$_SESSION["nama_pegawai"] = '';
		$_SESSION["nipp"] = '';
		$_SESSION["emkl"] = '';
		$_SESSION["npwp"] = '';
		$_SESSION["alamat"] = '';
		$_SESSION["terbilang"] = ''; 
		$_SESSION["total_tagihan"] = '';
			$_SESSION["date"] = '';
			$_SESSION["PRINTED_BY"] = '';
        ?>