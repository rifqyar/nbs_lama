<?php

 require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');

class MYPDF extends TCPDF {

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
$pdf = new MYPDF("L", PDF_UNIT, "A4", true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 002');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(true);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set font
$pdf->SetFont('courier', 7);


        // menambahkan halaman (harus digunakan minimal 1 kali)
        $pdf->AddPage($orientation='L',$format='',$keepmargins=false, $tocpage=false); 
        
        $id_area = $_GET["lokasi"];
		$lapangan = '';
		$mlo = $_GET["mlo"];
		$status = $_GET["status"];
	    $db = getDB("storage");
	if($mlo == 'T'){
		$qplacement = "SELECT PLACEMENT.NO_CONTAINER NO_CONTAINER, MASTER_CONTAINER.SIZE_ , MASTER_CONTAINER.TYPE_, 
			  PLACEMENT.STATUS, PLACEMENT.NO_REQUEST_RECEIVING,  TRUNC(PLACEMENT.TGL_PLACEMENT) TGL_PLACEMENT, SYSDATE TGL_CETAK, 
			  BLOCKING_AREA.NAME BLOK_, PLACEMENT.SLOT_, PLACEMENT.ROW_, PLACEMENT.TIER_, TO_DATE(TO_CHAR(SYSDATE,'YYYYMMDD HH:MI:SS AM'),'YYYYMMDD HH:MI:SS AM') -TO_DATE (TO_CHAR (PLACEMENT.TGL_PLACEMENT, 'YYYYMMDD HH:MI:SS AM'),'YYYYMMDD HH:MI:SS AM') DWELLING, YARD_AREA.NAMA_YARD LAPANGAN
			  FROM PLACEMENT INNER JOIN MASTER_CONTAINER
			  ON PLACEMENT.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
		      INNER JOIN BLOCKING_AREA ON PLACEMENT.ID_BLOCKING_AREA = BLOCKING_AREA.ID
			  INNER JOIN YARD_AREA ON BLOCKING_AREA.ID_YARD_AREA = YARD_AREA.ID
			  WHERE YARD_AREA.ID = '$id_area' AND MASTER_CONTAINER.NO_CONTAINER NOT IN(SELECT NO_CONTAINER FROM MASTER_CONTAINER WHERE MLO = 'MLO') AND PLACEMENT.STATUS='$status'
			  ORDER BY DWELLING DESC";
	} else if($mlo == 'Y'){
		$qplacement = "SELECT PLACEMENT.NO_CONTAINER NO_CONTAINER, MASTER_CONTAINER.SIZE_ , MASTER_CONTAINER.TYPE_, 
			  PLACEMENT.STATUS, PLACEMENT.NO_REQUEST_RECEIVING,  TRUNC(PLACEMENT.TGL_PLACEMENT) TGL_PLACEMENT, SYSDATE TGL_CETAK, 
			  BLOCKING_AREA.NAME BLOK_, PLACEMENT.SLOT_, PLACEMENT.ROW_, PLACEMENT.TIER_, TO_DATE(TO_CHAR(SYSDATE,'YYYYMMDD HH:MI:SS AM'),'YYYYMMDD HH:MI:SS AM') -TO_DATE (TO_CHAR (PLACEMENT.TGL_PLACEMENT, 'YYYYMMDD HH:MI:SS AM'),'YYYYMMDD HH:MI:SS AM') DWELLING, YARD_AREA.NAMA_YARD LAPANGAN
			  FROM PLACEMENT INNER JOIN MASTER_CONTAINER
			  ON PLACEMENT.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
		      INNER JOIN BLOCKING_AREA ON PLACEMENT.ID_BLOCKING_AREA = BLOCKING_AREA.ID
			  INNER JOIN YARD_AREA ON BLOCKING_AREA.ID_YARD_AREA = YARD_AREA.ID
			  WHERE YARD_AREA.ID = '$id_area' AND MASTER_CONTAINER.MLO = 'MLO' AND PLACEMENT.STATUS='$status'
			  ORDER BY DWELLING DESC";
	} else {
		$qplacement = "SELECT PLACEMENT.NO_CONTAINER NO_CONTAINER, MASTER_CONTAINER.SIZE_ , MASTER_CONTAINER.TYPE_, 
			  PLACEMENT.STATUS, PLACEMENT.NO_REQUEST_RECEIVING,  TRUNC(PLACEMENT.TGL_PLACEMENT) TGL_PLACEMENT, SYSDATE TGL_CETAK, 
			  BLOCKING_AREA.NAME BLOK_, PLACEMENT.SLOT_, PLACEMENT.ROW_, PLACEMENT.TIER_, TO_DATE(TO_CHAR(SYSDATE,'YYYYMMDD HH:MI:SS AM'),'YYYYMMDD HH:MI:SS AM') -TO_DATE (TO_CHAR (PLACEMENT.TGL_PLACEMENT, 'YYYYMMDD HH:MI:SS AM'),'YYYYMMDD HH:MI:SS AM') DWELLING, YARD_AREA.NAMA_YARD LAPANGAN
			  FROM PLACEMENT INNER JOIN MASTER_CONTAINER
			  ON PLACEMENT.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
		      INNER JOIN BLOCKING_AREA ON PLACEMENT.ID_BLOCKING_AREA = BLOCKING_AREA.ID
			  INNER JOIN YARD_AREA ON BLOCKING_AREA.ID_YARD_AREA = YARD_AREA.ID
			  WHERE YARD_AREA.ID = '$id_area' AND PLACEMENT.STATUS='$status'
			  ORDER BY DWELLING DESC";
	}
		
	$rplace = $db->query($qplacement);
	$rowplace = $rplace->getAll();
        
//        
       ob_start();
		$i=0;
        foreach($rowplace as $rowp){
		$dwell = $rowp["DWELLING"];
						$time = strstr($dwell, '.');
						$dec = "0".$time;									
						$days = floor($dwell);
						$hours = $dec*24;
						$clock = gmdate('H:i:s', floor($hours * 3600));						
						$dwelling_time = $days." Hari - ".$clock;
						$i++; 
        $tbl1 .= '<tr>
				  <td align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt">'.$i.'</td>
				  <td align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt">'.$rowp["NO_CONTAINER"].'</td>
				  <td align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt">'.$rowp["SIZE_"].'</td>
				  <td align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt">'.$rowp["TYPE_"].'</td>
				  <td align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt">'.$rowp["STATUS"].'</td>
				  <td align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt">'.$rowp["NO_REQUEST_RECEIVING"].' </td>
				  <td align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt">'.$rowp["TGL_PLACEMENT"].' </td>
				  <td align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt">'.$rowp["TGL_CETAK"].'</td>
				  <td align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt">'.$rowp["LAPANGAN"].'</td>
				  <td align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt">'.$rowp["BLOK_"].'-'.$rowp["SLOT_"].'-'.$rowp["ROW_"].'-'.$rowp["TIER_"].'</td>				  
				  <td align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt">
				  	'.$dwelling_time.'		  
				  </td>
				  
				 
			  </tr>';
			$lapangan = $rowp["LAPANGAN"];
        }
       
                    
        $tbl ='
		<center><h2> Laporan Dwelling Time di '.$lapangan.' </h2></center>
		<table class="grid-table" border="1" cellpadding="1" cellspacing="1"  width="100%" >
			  <tr style=" font-size:10pt">
				  <th align="center" width="2%" valign="top" class="grid-header"  style="font-size:8pt;font-weight:bold;">No </th>
				  <th align="center" valign="top" class="grid-header"  style="font-size:8pt;font-weight:bold;">NO. CONTAINER </th>
				  <th valign="top" width="5%" align="center" class="grid-header"  style="font-size:8pt;font-weight:bold;">SIZE</th>
				  <th valign="top" width="5%" align="center" class="grid-header"  style="font-size:8pt;font-weight:bold;">TYPE</th> 
				  <th valign="top" width="7%" align="center" class="grid-header"  style="font-size:8pt;font-weight:bold;">STATUS</th> 
				  <th valign="top" width="12%" align="center" class="grid-header"  style="font-size:8pt;font-weight:bold;">NO REQUEST</th>
				  <th valign="top" align="center" class="grid-header"  style="font-size:8pt;font-weight:bold;">TGL PLACEMENT</th>  
				  <th valign="top" align="center" class="grid-header"  style="font-size:8pt;font-weight:bold;">TGL CETAK</th>  
				  <th Valign="top" align="center" class="grid-header"  style="font-size:8pt;font-weight:bold;">LAPANGAN</th>  
				  <th Valign="top" width="16%" align="center" class="grid-header"  style="font-size:8pt;font-weight:bold;">BLOK SLOT ROW TIER</th>  
				  <th Valign="top" width="17%" align="center" class="grid-header"  style="font-size:8pt;font-weight:bold;">DWELLING TIME</th>  
			  </tr>
			  '.$tbl1.'
			  </table>'; 
     
        ob_end_clean();
		
        $pdf->writeHTML($tbl, true, false, false, false, '');
        
       // $pdf->writeHTML($tbl4, true, false, false, false, '');
        
        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);


        //Menutup dan menampilkan dokumen PDF
        $pdf->Output('print.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+
        
        
        ?>