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
		$this->SetFont('helvetica', 'I', 5);
		// Page number
		//$this->Cell(0, 15, 'hal '.$this->getAliasNumPage().' dari '.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
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
$pdf->SetMargins(1, 3, 0);
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->setPrintHeader(false);

//set auto page breaks
//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetAutoPageBreak(TRUE, 5);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

$db = getDB();
$p01 = $_GET['no_req'];
$id_user=$_SESSION['PENGGUNA_ID'];
$query="SELECT ID_NOTA FROM NOTA_STACKEXT_H WHERE TRIM(ID_REQUEST) = TRIM('$p01')";
$hasil=$db->query($query);
$hasil_=$hasil->fetchRow();
$notanya=$hasil_['ID_NOTA'];
//if($p01{3}=='E')
		$kode='X';
/*	else
		$kode='Y';*/
$query="INSERT INTO LOG_NOTA (ID_NOTA,NO_REQUEST,KODE_MODUL,KETERANGAN,ID_USER, TANGGAL) VALUES ('$notanya','$p01','$kode','CETAK','$id_user',SYSDATE)";
$db->query($query);
$query="UPDATE NOTA_REEXPORT_H SET TGL_CETAK=SYSDATE WHERE ID_NOTA='$notanya'";
$db->query($query);

$p1=$notanya;
//print_r($p1);die;
$query="SELECT A.*,B.*, SYSDATE AS TGL_PROSES,UPPER(B.USER_REQ) AS NAMA,to_char(A.INVOICE_DATE,'DD-MM-YYYY') TGL_NOTA2, to_char(A.ADM_NOTA,'999,999,999,999,990.00') ADM_NOTAN,to_char(A.PPN,'999,999,999,999,990.00') PPNN, to_char(A.JUMLAH,'999,999,999,999,999.00') TAGIHANN,to_char(A.TOTAL,'999,999,999,999,999.00') TOTALN
		FROM NOTA_STACKEXT_H A, REQ_STACKEXT_H B WHERE A.ID_NOTA='$p1' AND A.ID_REQUEST=B.ID_REQ";
$data = $db->query($query)->fetchRow();
 $date=date('d M Y H:i:s');
 $query3="SELECT DISTINCT TGL_START_STACK, TGL_END_STACK FROM NOTA_STACKEXT_D WHERE ID_NOTA='$p1' AND ID_CONT IS NOT NULL";
 $res3 = $db->query($query3);
 $row3=$res3->getAll();
 	foreach($row3 as $rows2) {
		$detail2 = $rows2[TGL_START_STACK]." s/d ".$rows2[TGL_END_STACK];
	}
 
$query_dtl="SELECT A.*,C.*, to_char(A.TARIF,'999,999,999,999,999.00') AS TARIFN, to_char(A.SUB_TOTAL,'999,999,999,999,999.00') AS SUB_TOTALN 
			FROM NOTA_STACKEXT_D A, NOTA_STACKEXT_H B, MASTER_BARANG C WHERE A.ID_NOTA='$p1'  AND A.ID_NOTA=B.ID_NOTA AND C.KODE_BARANG=A.ID_CONT";
	$res = $db->query($query_dtl);
	
	// query adm
	$query_adm="SELECT to_char(TARIF,'999,999,999,999,999.00') AS ADM
			FROM NOTA_STACKEXT_D WHERE KETERANGAN = 'ADM' AND  ID_REQ = '$p01'";
	$ad = $db->query($query_adm);
	$adm = $ad->fetchRow();
	$biayaadm = $adm['ADM'];
	//print_R($res);die;
	$i=0;
	//unset($detail);
	 $row2=$res->getAll();
	foreach($row2 as $rows) {
					
		if($rows[TGL_START_STACK]<>''){
                        $detail .='
                    
		<tr><td colspan="3" width="100"><font size="6">'.'<b>'.$rows[KETERANGAN].'</b>'.'</font></td>                                                    
                        <td width="10" align="left"><font size="6">'.'<b>'.$rows[JUMLAH_CONT].'</b>'.'</font></td>                              
                        <td width="50" align="left"><font size="6">'.'<b>'.$rows[SIZE_]." ".$rows[TYPE]." ".$rows[STATUS].'</b>'.'</font></td>                                                            
                        <td width="10" align="left"><font size="6">'.'<b>'.$rows[HZ].'</b>'.'</font></td>                        
                        <td width="40" align="left"><font size="6">'.'<b>'.$rows[TARIF].'</b>'.'</font></td>                        
                        <td width="43" align="right"><font size="6">'.'<b>'.$rows[SUB_TOTAL].'</b>'.'</font></td>        
                        </tr>   
                        <tr>
		<td colspan="8"><font size="6"><i><b>('.$rows[TGL_START_STACK].' s/d '.$rows[TGL_END_STACK].')'.$rows[JUMLAH_HARI].' hari</b></i></font></td>
                         </tr>
		';
                        }else
                            $detail .= '
                    
		<tr><td colspan="3" width="100"><font size="6">'.$rows[KETERANGAN].'</font></td>
                        
                            
                        <td width="10" align="left"><font size="6">'.$rows[JUMLAH_CONT].'</font></td>                              
                        <td width="50" align="left"><font size="6">'.$rows[SIZE_]." ".$rows[TYPE]." ".$rows[STATUS].'</font></td>                                                            
                        <td width="10" align="left"><font size="6">'.$rows[HZ].'</font></td>                        
                        <td width="40" align="left"><font size="6">'.$rows[TARIF].'</font></td>                        
                        <td width="43" align="right"><font size="6">'.$rows[SUB_TOTAL].'</font></td>        
                        </tr>   ';                                             
            
		$i++;
	}


// jumlah detail barangnya
$query_jum="SELECT COUNT(1) JUM_DETAIL FROM NOTA_STACKEXT_D A WHERE A.ID_NOTA='$p1'";
$data_jum = $db->query($query_jum)->fetchRow();
$jum_data_page = 18;	//jumlah data dibatasi per page 18 data
$jum_page = ceil($data_jum['JUM_DETAIL']/$jum_data_page);	//hasil bagi pembulatan ke atas
if(($data_jum[JUM_DETAIL]%$jum_data_page)>10 || ($data_jum[JUM_DETAIL]%$jum_data_page)==0)	$jum_page++;	//jika pada page terakhir jumlah data melebihi 12, tambah 1 page lagi
$jum_page=1;
for($pg=1; $pg<=$jum_page; $pg++) {
	// add a page
	$pdf->AddPage();
	// set font
	$pdf->SetFont('helvetica', '', 6);
	
	$tbl = <<<EOD
			<table>
                
                <tr>                    
                    <td COLSPAN="2" align="left">$data[ID_NOTA]  </td>
                 <td><font size="4">$data[ID_USER] <b>|</b> $date</font></td>
                </tr> 
                <tr>                    
                    <td COLSPAN="3" align="left"><font size="8"><b>$data[ID_REQUEST]</b></font>  </td>
                </tr>                                                              
                
                <tr>
                    <td COLSPAN="6"><b>PRANOTA PERPANJANGAN EXPORT</b></td>                                        
                </tr>                
                
                
                <tr>
                <td></td>
                </tr>
                
                
                <tr>                    
                    <td COLSPAN="4" align="left"><font size="6"><b>$data[CUSTOMER]</b></font></td>					
                </tr>
                <tr>                    
                    <td COLSPAN="4" align="left"><font size="6"><b>$data[NPWP]</b></font></td>                    
                </tr>
                <tr>                    
                    <td COLSPAN="6" align="left"><font size="6"><b>$data[ALAMAT]</b></font></td>					
                </tr>
                <tr>                    
                    <td COLSPAN="4" align="left"><font size="6"><b>$data[VESSEL] / $data[VOYAGE] - $data[VESSEL_DT]/$data[VOYAGE_DT]</b></font></td>                    
                </tr>                                              
                
                
                <tr>
                    <td width="80" align="left"><font size="6"><b>MASA PENUMPUKAN:</b></font> </td>                    
                    <td colspan="5"><font size="6"><b>$detail2</b></font></td>                    
                </tr>                                    
                
                <tr>
                <td></td>
                </tr> 
                
                
                <tr>                              
                    <th colspan="3" width="100"><font size="6"><b>KETERANGAN</b></font></th>                    
                    <th width="10" align="left"><font size="6"><b>BX</b></font></th>
                
                    <th width="50" align="left"><font size="6"><b>CONTENT</b></font></th>
                    <th width="10" align="left"><font size="6"><b>HZ</b></font></th>
                    
                    <th width="43" align="left"><font size="6"><b>TARIF</b></font></th>                    
                    <th width="43" align="left"><font size="6" ><b>JUMLAH</b></font></th>
                </tr>                
                
                
                <tr>
                    <td colspoan="14">
                        <hr style="border: 2px dashed #C0C0C0" color="#FFFFFF" size="6" width="300">
                    </td>                        
                
                </tr>
				$detail
				<tr>                
                
                    <td>
                <td></td><td></td>
                
                        <hr style=" border: 2px dashed #C0C0C0" color="#FFFFFF" size="6" width="300">
                    </td>
                </tr>
				
                </table>
                
EOD;
$tbl .=<<<EOD
<table>                    
                    <tr>
                        <td colspan="5" align="right"><b>Discount :</b></td>
                        <td width="50" colspan="2" align="right"><b>0.00</b></td>
                    </tr>
                    <tr>
                        
                        <td colspan="5" align="right"><b>Administrasi :</b></td>
                        <td colspan="2" align="right"><b>$data[ADM_NOTA]</b></td>
                    </tr>
                    <tr>
                      
                        <td colspan="5" align="right"><b>Dasar Peng. Pajak :</b></td>
                        <td colspan="2" align="right"><b>$data[JUMLAH]</b></td>
                    </tr>
                    <tr>
                        
                        <td colspan="5" align="right"><b>Jumlah PPN :</b></td>
                        <td colspan="2" align="right"><b>$data[PPN]</b></td>
                    </tr>
                    <tr>
                        
                        <td colspan="5" align="right"><b>Jumlah PPN Subsidi :</b></td>
                        <td colspan="2" align="right"><b>0.00</b></td>
                    </tr>
                    <tr>
                     
                        <td colspan="5" align="right"><b>Jumlah Dibayar :</b></td>
                        <td colspan="2" align="right"><b>$data[TOTAL]</b></td>
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

/*$pdf->ln();
$pdf->SetFont('courier', '', 8);
$pdf->Write(0, 'Nota sebagai faktur pajak berdasarkan Peraturan Dirjen Pajak', '', 0, 'L', true, 0, false, false, 0);
$pdf->Write(0, 'Nomor 10/PJ/2010 tanggal 9 Maret 2010', '', 0, 'L', true, 0, false, false, 0);$pdf->ln();
$pdf->ln();
$pdf->SetFont('courier', '', 9);
$pdf->Write(0, $data[TGL_NOTA], '', 0, 'R', true, 0, false, false, 0);*/

$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();
$pdf->SetFont('helvetica', 'B', 6);
//Close and output PDF document
$pdf->Output('sample.pdf', 'I');
?>