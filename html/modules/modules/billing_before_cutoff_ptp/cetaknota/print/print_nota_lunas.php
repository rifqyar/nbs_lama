<?php
//require "login_check.php";

require_once(SITE_LIB.'tcpdf/config/lang/eng.php');
require_once(SITE_LIB.'tcpdf/tcpdf.php');

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

	// Page footer
	public function Footer() {
		// Position at 10 mm from bottom
		$this->SetY(-20);
		// Set font
		$this->SetFont('helvetica', 'I', 6);
		// Page number
		$this->Cell(0, 15, 'hal '.$this->getAliasNumPage().' dari '.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
	}
}

// create new PDF document
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf = new MYPDF('P', 'mm', 'A7', true, 'UTF-8', false);


// set header and footer fonts
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(1, 4, 0);
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
$query="SELECT ID_NOTA, ID_REQ FROM nota_delivery_h WHERE TRIM(ID_REQ) = TRIM('$no_req')";
$hasil=$db->query($query);
$hasil_=$hasil->fetchRow();
$notanya=$hasil_['ID_NOTA'];

$query="UPDATE NOTA_DELIVERY_H SET TGL_CETAK_NOTA=SYSDATE WHERE ID_REQ='$no_req'";
$db->query($query);


$query="SELECT a.ID_REQ, 
			   NVL(a.ID_NOTA,'-') ID_NOTA, 
			   NVL(a.NO_FAKTUR,'-') NO_FAKTUR, 
			   a.ID_USER, 
			   TO_CHAR(a.ADM_NOTA,'999,999,999,999') ADM_NOTA, 
			   a.EMKL, 
			   a.ALAMAT, 
			   a.NPWP,
			   TO_CHAR(a.TAGIHAN,'999,999,999,999') TAGIHAN, 
			   TO_CHAR(a.PPN,'999,999,999,999') PPN, 
			   TO_CHAR(a.TOTAL,'999,999,999,999') TOTAL, 
			   a.STATUS, 
			   TO_CHAR(a.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST,
			   (SELECT NAME FROM TB_USER WHERE ID = a.ID_USER) ID_USER,
			   b.VESSEL,b.VOYAGE_OUT AS VOYAGE 
        FROM nota_delivery_h a, req_delivery_h b
		WHERE a.ID_REQ = '$no_req' AND a.ID_REQ = b.ID_REQ";
$data = $db->query($query)->fetchRow();
$date=date('d M Y H:i:s');

//print_r("SELECT * from TB_NOTA_DELIVERY_D A, TB_NOTA_DELIVERY_H B, MASTER_BARANG C WHERE A.ID_NOTA='$p1'  AND A.ID_NOTA=B.ID_NOTA AND C.KODE_BARANG=A.ID_CONT");die;

$query_dtl="SELECT TO_CHAR(a.TARIF, '999,999,999,999') AS TARIF, 
					  TO_CHAR(a.SUB_TOTAL, '999,999,999,999') AS SUB_TOTAL, 
					  a.KETERANGAN, a.HZ, a.JUMLAH_CONT, TO_DATE(a.TGL_START_STACK,'dd/mm/yyyy') START_STACK, 
					  TO_DATE(a.TGL_END_STACK,'dd/mm/yyyy') END_STACK, b.UKURAN SIZE_, b.TYPE, 
					  b.STATUS, a.JUMLAH_HARI
					  FROM nota_delivery_d_tmp a left join  master_barang b on (a.ID_CONT = b.KODE_BARANG)
					  where a.ID_REQ = '$no_req' and KETERANGAN <> 'ADM'";
	//print_R($query_dtl);die;
	$res = $db->query($query_dtl);
	//print_R($res);die;
	$i=0;
	//unset($detail);
	 $row2=$res->getAll();
	foreach($row2 as $rows) {
					
		
                        if($rows[START_STACK]<>''){
                        $detail .='
                    
		<tr><td colspan="3" width="100"><font size="6">'.$rows[KETERANGAN].'</font></td>
                        
                            
                        <td width="10" align="left"><font size="6">'.$rows[JUMLAH_CONT].'</font></td>                              
                        <td width="50" align="left"><font size="6">'.$rows[SIZE_]." ".$rows[TYPE]." ".$rows[STATUS].'</font></td>                                
                            
                        <td width="7" align="left"><font size="6">'.$rows[HZ].'</font></td>                        
                        <td width="45" align="right"><font size="6">'.$rows[TARIF].'</font></td>                        
                        <td width="45" align="right"><font size="6">'.$rows[SUB_TOTAL].'</font></td>        
                        </tr>   
                        <tr>
                        <td colspan="8"><font size="6"><i>('.$rows[START_STACK].' s/d '.$rows[END_STACK].')</i><b> '.$rows[JUMLAH_HARI].' hari</b></font></td></td>
                         </tr>
		';
                        }else
                            $detail .= '
                    
		<tr><td colspan="3" width="100"><font size="6">'.$rows[KETERANGAN].'</font></td>
                        
                            
                        <td width="10" align="left"><font size="6">'.$rows[JUMLAH_CONT].'</font></td>                              
                        <td width="50" align="left"><font size="6">'.$rows[SIZE_]." ".$rows[TYPE]." ".$rows[STATUS].'</font></td>                                
                            
                        <td width="7" align="left"><font size="6">'.$rows[HZ].'</font></td>
                        
                        <td width="45" align="right"><font size="6">'.$rows[TARIF].'</font></td>
                        
                        <td width="45" align="right"><font size="6">'.$rows[SUB_TOTAL].'</font></td>        
                        </tr>   ';
		$i++;
	}


// jumlah detail barangnya
$query_jum="SELECT COUNT(1) JUM_DETAIL FROM NOTA_DELIVERY_D A WHERE A.ID_REQ='$no_req'";
$data_jum = $db->query($query_jum)->fetchRow();
$jum_data_page = 18;	//jumlah data dibatasi per page 18 data
$jum_page = ceil($data_jum['JUM_DETAIL']/$jum_data_page);	//hasil bagi pembulatan ke atas
if(($data_jum[JUM_DETAIL]%$jum_data_page)>10 || ($data_jum[JUM_DETAIL]%$jum_data_page)==0)	$jum_page++;	//jika pada page terakhir jumlah data melebihi 12, tambah 1 page lagi
for($pg=1; $pg<=$jum_page; $pg++) {
	// add a page
	$pdf->AddPage();
	// set font
	$pdf->SetFont('courier', '', 6);	
                
        $tbl = <<<EOD
          
			<table border='0'>                
		<tr>                    
                 <td COLSPAN="1" align="left">$data[ID_NOTA]  </td>
                 <td><font size="4">$data[ID_USER] $date</font></td>
                </tr> 
                <tr>                    
                    <td COLSPAN="3" align="left"><font size="8"><b>$data[ID_REQ]</b></font>  </td>
                </tr>                  
                
                <tr>
                    <td></td>					
                </tr>                
                
                <tr>
                    <td COLSPAN="6"><b>DELIVERY</b></td>                                        
                </tr>                
                              
                <tr>                    
                    <td COLSPAN="4" align="left">$data[EMKL]</td>					
                </tr>
                <tr>                    
                    <td COLSPAN="4" align="left">$data[NPWP]</td>                    
                </tr>
                <tr>                    
                    <td COLSPAN="6" align="left">$data[ALAMAT]</td>					
                </tr>
                <tr>                    
                    <td COLSPAN="4" align="left">$data[VESSEL] / $data[VOYAGE]</td>
                    <td colspan="7"></td>
                </tr>               
                
                <tr>
                <td></td>
                </tr>                                                    
                                
                <tr>                              
                    <th colspan="3" width="100"><font size="6"><b>KETERANGAN</b></font></th>                    
                    <th width="10" align="left"><font size="6"><b>BX</b></font></th>
                
                    <th width="50" align="left"><font size="6"><b>CONTENT</b></font></th>
                    <th width="7" align="left"><font size="6"><b>HZ</b></font></th>
                    
                    <th width="45" align="left"><font size="6"><b>TARIF</b></font></th>                    
                    <th width="45" align="left"><font size="6" ><b>JUMLAH</b></font></th>
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
                        <td colspan="6" align="right">Discount :</td>
                        <td width="50" colspan="2" align="right">0.00</td>
                    </tr>
                    <tr>
                        
                        <td colspan="6" align="right">Administrasi :</td>
                        <td colspan="2" align="right">$data[ADM_NOTA]</td>
                    </tr>
                    <tr>
                      
                        <td colspan="6" align="right">Dasar Peng. Pajak :</td>
                        <td colspan="2" align="right">$data[TAGIHAN]</td>
                    </tr>
                    <tr>
                        
                        <td colspan="6" align="right">Jumlah PPN :</td>
                        <td colspan="2" align="right">$data[PPN]</td>
                    </tr>
                    <tr>
                        
                        <td colspan="6" align="right">Jumlah PPN Subsidi :</td>
                        <td colspan="2" align="right">0.00</td>
                    </tr>
                    <tr>
                     
                        <td colspan="6" align="right">Jumlah Dibayar :</td>
                        <td colspan="2" align="right">$data[TOTAL]</td>
                    </tr>               
        
                    </table>                                      
          
                
EOD;

$style = array(
    'position' => '',
    'align' => 'C',
    'stretch' => false,
    'fitwidth' => true,
    'cellfitalign' => '',
    //'border' => true,
    'hpadding' => 'auto',
    'vpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255),
    'text' => true,
    'font' => 'helvetica',
    'fontsize' => 4,    
    'stretchtext' => 4
);               
        
                //Menampilkan Barcode dari nomor nota
                //$pdf->write1DBarcode("$notanya", 'C128', 0, 0, '', 18, 0.4, $style, 'N');
                //Logo IPC
                //$pdf->Image('images/ipc2.jpg', 50, 7, 20, 10, '', '', '', true, 72);
                $pdf->write1DBarcode("$notanya", 'C128', 0, 0, '', 18, 0.4, $style, 'N');
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


$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();
$pdf->SetFont('helvetica', 'B', 9);
//Close and output PDF document
$pdf->Output('sample.pdf', 'I');
?>