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
 
 $db   = getDB("storage");
        


        $no_req = $_GET['no_req'];

$query_nota   = "SELECT LUNAS FROM NOTA_RECEIVING WHERE NO_REQUEST = '$no_req' ORDER BY NO_NOTA DESC";
$result_nota  = $db->query($query_nota);
$row_nota   = $result_nota->fetchRow();

if ($row_nota[LUNAS] !='YES') {
    echo "NOTA BELUM LUNAS";
    die();
}
    $qtime = $db->query("SELECT TO_CHAR(SYSDATE, 'DD/MM/YYYY HH24:MI:SS') TIME_ FROM DUAL");
    $rtime = $qtime->fetchRow();
    $dt    = $rtime[TIME_];
        //$dt      = date('d-M-Y H:i:s');
             $query_nota =  "SELECT NOTA_RECEIVING.NO_NOTA, TO_CHAR(NOTA_RECEIVING.TGL_NOTA, 'DD-MM-YYYY') TGL_NOTA, V_MST_PBM.NM_PBM EMKL, REQ.NO_REQUEST, CONTAINER_RECEIVING.NO_CONTAINER,
                MASTER_CONTAINER.SIZE_, MASTER_CONTAINER.TYPE_, CONTAINER_RECEIVING.STATUS STATUS, REQ.CETAK_KARTU, 
                REQ.RECEIVING_DARI, YARD_AREA.NAMA_YARD, MASTER_CONTAINER.NO_BOOKING NM_KAPAL, CASE WHEN CONTAINER_RECEIVING.VIA IS NULL THEN 'DARAT' ELSE 'TONGKANG' END VIA, CASE WHEN MASTER_CONTAINER.MLO = 'MLO' THEN '03'
                ELSE '02' END AREA_, CONTAINER_RECEIVING.EX_KAPAL
                FROM NOTA_RECEIVING INNER JOIN REQUEST_RECEIVING REQ ON NOTA_RECEIVING.NO_REQUEST = REQ.NO_REQUEST
                INNER JOIN CONTAINER_RECEIVING ON REQ.NO_REQUEST = CONTAINER_RECEIVING.NO_REQUEST
                INNER JOIN MASTER_CONTAINER ON CONTAINER_RECEIVING.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER                
                LEFT JOIN KAPAL_CABANG.MST_PBM V_MST_PBM ON REQ.KD_CONSIGNEE = V_MST_PBM.KD_PBM and V_MST_PBM.kd_cabang = '05'
                LEFT JOIN YARD_AREA ON CONTAINER_RECEIVING.DEPO_TUJUAN = YARD_AREA.ID
                WHERE NOTA_RECEIVING.TGL_NOTA = (SELECT MAX(NOTA.TGL_NOTA) FROM NOTA_RECEIVING NOTA WHERE NOTA.NO_REQUEST =  REQ.NO_REQUEST)
                AND REQ.NO_REQUEST = '$no_req'";
  $result   = $db->query($query_nota);
  $row_nota    = $result->getAll();
        $no_nota        = $row_nota['NO_FAKTUR_'];
        $no_nota_        = $row_nota['NO_NOTA'];
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
    $terbilang     = $row_nota['TERBILANG'];
        $total_tagihan  = $row_nota['TOTAL_TAGIHAN'];
    $vessel  = $row_nota['VESSEL'];
        $voyage  = $row_nota['VOYAGE'];
        $nama_lengkap  = $row_nota['NAMA_LENGKAP'];
    
      $pegawai    = "SELECT * FROM MASTER_PEGAWAI WHERE STATUS = 'AKTIF'";
    $result_  = $db->query($pegawai);
    $nama_peg = $result_->fetchRow();
    $nama   = $nama_peg['NAMA_PEGAWAI'];
    $jabatan  = $nama_peg['JABATAN'];
    $nipp   = $nama_peg['NIPP'];
    
    $_SESSION["no_nota"] = $no_nota;
    if ($nota_lama == NULL) {
    $_SESSION["nota_lama"] = '';
    } else {
    $_SESSION["nota_lama"] = 'EX '. $nota_lama;
    }
    $_SESSION["jabatan"] = $jabatan;
    $_SESSION["nama_pegawai"] = $nama;
    $_SESSION["nipp"] = $nipp;
    $_SESSION["emkl"] = $emkl;
    $_SESSION["npwp"] = $npwp;
    $_SESSION["alamat"] = $alamat;
    $_SESSION["terbilang"] = $terbilang;
    $_SESSION["total_tagihan"] = $total_tagihan;
    $_SESSION["vessel"] = $vessel."(".$voyage.")";
      $_SESSION["date"] = $dt;
      $_SESSION["PRINTED_BY"] = $nama_lengkap;
      $_SESSION["KET_NOTA"] = "Nota Berlaku Sebagai Pajak Berdasarkan Peraturan Dirjen Pajak PER-27/PJ./2011 tanggal 19 September 2011";
      
    if($row_nota['JN_REPO'] == "EKS_STUFFING"){
      $_SESSION["nm_nota"] = "NOTA RELOKASI KE TPK EKS STUFFING";
    } else {
      $_SESSION["nm_nota"] = "NOTA RELOKASI KE TPK";
    }


require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');

class MYPDF extends TCPDF {

  //Page header
  public function Header() {
    // Logo
    //$image_file = 'images/bg_kanan.jpg';
    //$this->Image($image_file, 20, 10, 40, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
    // Set font
    /*$this->SetFont('Courier', 'B', 14);
    $this->SetY(6);
    $this->Cell(0, 16, $_SESSION["no_nota"], 0, false, 'R', 0, '', 0, false, 'T', 'M');*/
    /*$this->SetFont('Courier', 'B', 14);
    $this->SetY(11);
    $this->Cell(0, 15, $_SESSION["nota_lama"], 0, false, 'R', 0, '', 0, false, 'T', 'M');
    $this->SetFont('Courier', 'B', 8);
    $this->SetY(25);
    $this->SetX(60);
    //$this->MultiCell(60, 45, $_SESSION["KET_NOTA"], 0, false, 'R', 0, '', 0, false, 'T', 'M');
    $this->MultiCell(110,45,$_SESSION["KET_NOTA"],
    0,
    'R',
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
    $this->SetFont('Courier', 'B', 14);
    $this->SetY(36);
    $this->Cell(0, 15, $_SESSION["nm_nota"], 0, false, 'R', 0, '', 0, false, 'T', 'M');
    $this->SetFont('Courier', 'B', 11);
    $this->SetY(42);
    $this->SetX(35);
    $this->Cell(0, 15, $_SESSION["emkl"], 0, false, '', 0, '', 0, false, 'T', 'M');
    $this->SetFont('Courier', 'B', 11);
    $this->SetY(46);
    $this->SetX(35);
    $this->Cell(0, 15, $_SESSION["npwp"], 0, false, '', 0, '', 0, false, 'T', 'M');
    $this->SetFont('Courier', 'B',11);
    $this->SetY(55);
    $this->SetX(35);
    $this->MultiCell(60,45,$_SESSION["alamat"],
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
    $this->SetY(60);
    $this->SetX(35);
    $this->Cell(0, 15, $_SESSION["vessel"], 0, false, '', 0, '', 0, false, 'T', 'M');
    $this->SetY(63);
    $this->SetX(35);
    $this->SetFont('Courier', 'B', 11);*/
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
    /*$this->SetFont('Courier', 'B', 11);
    $this->SetY(-40);
    $this->Cell(0, 0, $_SESSION["no_nota"] , 0, false, 'R', 0, '', 0, false, 'T', 'M');
    $this->SetFont('Courier', 'B', 11);
    $this->SetY(-28);
    $this->SetX(50);
    $this->Cell(0, 0, $_SESSION["emkl"] , 0, false, '', 0, '', 0, false, 'T', 'M');
    $this->SetFont('Courier', 'B I', 11);
    $this->SetY(-23);
    $this->SetX(50);
    $this->Cell(0, 0, $_SESSION["total_tagihan"] , 0, false, '', 0, '', 0, false, 'T', 'M');
    $this->SetFont('Courier', 'B', 11);
    $this->SetY(-16);
    $this->SetX(50);
    $this->Cell(0, 0, $_SESSION["nm_nota"] , 0, false, '', 0, '', 0, false, 'T', 'M');
    $this->SetFont('Courier', 'B', 11);
    $this->SetY(-12);
    $this->Cell(0, 0, $_SESSION["date"] , 0, false, 'R', 0, '', 0, false, 'T', 'M');
    $this->SetFont('Courier', 'B I', 9);
    $this->SetY(-7);
    $this->SetX(50);
    $this->Cell(0, 0, $_SESSION["terbilang"] , 0, false, '', 0, '', 0, false, 'T', 'M');
    
    $this->SetFont('Courier', 'B', 11);*/
    /*$this->SetY(-60);
    $this->SetX(0);
    $this->Cell(0, 0, 'Printed by '.$_SESSION["PRINTED_BY"], 0, false, 'L', 0, '', 0, false, 'T', 'M');*/
    /*if($_GET["first"] != 1){
      $this->SetY(-55);
      $this->SetX(0);
      $this->Cell(0, 0, 'Reprinted by '.$_SESSION["NAME"], 0, false, 'L', 0, '', 0, false, 'T', 'M');
    }*/
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
$pdf->SetMargins('0', '0', '0','0');
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
//set auto page break
$pdf->SetAutoPageBreak(TRUE, '1px');

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set font
$pdf->SetFont('Courier', 15,9.8);


        // menambahkan halaman (harus digunakan minimal 1 kali)
    $resolution= array(215,305);
    $pdf->AddPage('P', $resolution);
       // $pdf->AddPage($orientation='P',$format='',$keepmargins=false, $tocpage=false);
        
        
//        
        ob_start();
    # code...
   $i=1;

  // for ($j=0; $j < 3 ; $j++) { 
     # code..
   foreach ($row_nota as $rowd) {
          # code...     
      $no_request = $rowd[NO_REQUEST];
      $tgl_nota   = $rowd[TGL_NOTA];
      $via    = $rowd[VIA];
      $no_cont  = $rowd[NO_CONTAINER];
      $voyage   = $rowd[VOYAGE_OUT];
      $kapal    = $rowd[NM_KAPAL];
      $size     = $rowd[SIZE_];
      $type     = $rowd[TYPE_];
      $status   = $rowd[STATUS];
      $plbh_tj  = $rowd[PELABUHAN_TUJUAN];
      $area     = $rowd[AREA_];
      $nm_pbm   = $rowd[EMKL];
      $rec_dari   = $rowd[RECEIVING_DARI];
      $npe    = $rowd[NPE];
      $yard     = $rowd[NAMA_YARD];
      $exkapal  = $rowd[EX_KAPAL];

      if ($rowd["EX_KAPAL"] != NULL) {
          $tblx = 'Ex Kapal : '.$exkapal;
      }

      if ($i % 3 != 0) {
         $tblspace = '<div style="margin-bottom:30px"></div>';
        //$tblspace = ' ';
      }
      else {
          $tblspace = ' ';
      }

        $tbl .= '             
               <div style="height:712px; width:767px;border:0px solid  #FFF">   
        <table border="0" width="767px" height="340" cellpadding="2" cellspacing="2">
          <tr>
            <th width="13%" class="txc"  scope="col"></th>
            <th class="txc" width="5%" scope="col"></th>
            <th class="txc" width="5%" scope="col"></th>
            <th class="txc" width="5%" scope="col"></th>
            <th class="txc" width="5%" scope="col"></th>
            <th class="txc" width="8%" scope="col"></th>
            <th class="txc" width="15%" scope="col"></th> 
            <th class="txc" width="13%" scope="col"></th>
            <th class="txc" colspan="2" align="left" scope="col" style="font-size:31px; font-family:Arial">&nbsp;&nbsp;'.$no_request.'</th>
            <th class="txc" width="1%" scope="col"></th>
          </tr>
          <tr>
            <th class="txc"   scope="row"></th>
            <td  class="txc"></td>
            <td  class="txc"></td>
            <td  class="txc"></td>
            <td  class="txc"></td>
            <td  class="txc"></td>
            <td  class="txc"></td>
            <td  class="txc"></td>
            <td colspan="2"  class="style1" style="font-size:31px; font-family:Arial">&nbsp;&nbsp;'.$tgl_nota.'</td>
            <td  class="txc"></td>
          </tr>
          <tr>
            <th  colspan="8" valign="top" align="left"   scope="row"><b style="font-size:66px; padding-left:15px; line-height:5px">VIA '.$via.'</b></th>
            <td width="10%"></td>
            <td width="8%"></td> 
            <td></td>
          </tr>
          <tr>
            <th  scope="row"></th>
            <td colspan="5" align="center"><b style="font-size:66px; line-height:2px">'.$no_cont.'</b></td>
            <td></td>
            <td colspan="2" align="left" style="font-size:32px"><strong>&nbsp;'.$kapal.' &nbsp; &nbsp; &nbsp; &nbsp; '.$voyage.'</strong></td>
            <td align="left">&nbsp;</td>
            <td></td>
          </tr>
          <tr>
            <th  height="30" scope="row"></th>
            <td align="right"><strong>'.$size.'</strong></td>
            <td colspan="3" align="center"><strong>'.$type.'</strong></td>
            <td align="left"><strong>'.$status.'</strong></td>
            <td></td>
            <td align="center">'.$plbh_tj.'</td>
            <td colspan="2"></td>
            <td></td>
          </tr>
          <tr>
            <th  height="30" scope="row"></th>
            <td></td>
            <td colspan="3" align="center"><strong></strong></td>
            <td></td>
            <td></td>
            <td colspan="3"></td>
            <td></td>
          </tr>
          <tr>
            <th  height="25" scope="row"></th>
            <td colspan="5" align="left" style="font-size:29px">
            '.$tblx.'
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <th  height="5" scope="row"></th>
            <td valign="bottom" align="right"><strong>&nbsp;&nbsp;'.$area.'</strong></td> 
            <td valign="bottom" align="right"><strong></strong></td>
            <td align="center"><strong></strong></td>
            <td align="center"><strong></strong></td>
            <td  align="center"><strong> </strong></td>
            <td></td>
            <td colspan="3"><span style="font-size:30px"><strong>'.$nm_pbm.'</strong></span></td>
            <td></td>
          </tr>
          <tr>
            <th  height="8" scope="row"></th>
            <td colspan="4">REC DARI : '.$rec_dari.'</td>
           <!--  <td></td>
            <td></td>
            <td></td> -->
            <td></td>
            <td></td>
            <td colspan="3" style="font-size:30px">&nbsp;</td>
            <td></td>
          </tr>
          <tr>
            <th  height="16" scope="row"></th>
            <td valign="bottom" colspan="5"><!--NO PEB : '.$npe.'-->
              <strong>'.$yard.' </strong></td> 
            <td></td>
            <td ></td>
            <td></td>
            <td align="left" valign="top" style="font-size:30px"><strong>'.$_SESSION["NAME"].'</strong></td> 
            <td>&nbsp;</td>
          </tr> 
        </table>
        </div>
        '.$tblspace.'
        ';
    $i++;
} 
 //}
        ob_end_clean();

        $pdf->writeHTML($tbl, true, false, false, false, '');
        // $pdf->writeHTML($tbl1, true, false, false, false, '');
        // $pdf->writeHTML($tbl2, true, false, false, false, '');
        // $pdf->writeHTML($tbl3, true, false, false, false, '');
        // $pdf->writeHTML($tbl4, true, false, false, false, '');
        
        // Print text using writeHTMLCell()
        //$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);


        //Menutup dan menampilkan dokumen PDF
        $pdf->Output('nota_stuffing.pdf', 'I');

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
        ?>