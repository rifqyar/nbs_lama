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
		//$this->Cell(0, 10, 'hal '.$this->getAliasNumPage().' dari '.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
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
//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetAutoPageBreak(TRUE, 10);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

$db = getDB();
$p1 = $_GET['pl'];
$query="SELECT A.*,B.*,SYSDATE AS TGL_PROSES,to_char(A.INVOICE_DATE,'DD-MM-YYYY') TGL_NOTA2, to_char('0','999,999,999,999,990.00') ADM_NOTAN,to_char(A.PPN,'999,999,999,999,999.00') PPNN,
        to_char(A.JUMLAH,'999,999,999,999,999.00') TAGIHANN,to_char(A.TOTAL,'999,999,999,999,999.00') TOTALN,UPPER(B.TIPE_REQ) TIPE_REQUEST, UPPER(C.NAME) AS NAMA, B.ID_REQUEST AS NOREQ
        FROM NOTA_HICOSCAN_H A join REQ_HICOSCAN B on A.ID_NOTA='$p1' AND trim(A.ID_REQUEST)=trim(B.ID_REQUEST) left join tb_user c on  C.ID=A.USER_ID_INVOICE";
$data = $db->query($query)->fetchRow();
 $date=date('d M Y H:i:s');

 $idreq=$data['NOREQ'];
 //$query3="SELECT NO_CONTAINER from BH_DETAIL_REQUEST A, BH_NOTA B WHERE B.ID_NOTA='$p1'  AND A.ID_REQUEST=B.ID_REQUEST";
 $query3="SELECT NO_CONTAINER from REQ_HICOSCAN_D WHERE ID_REQUEST='$idreq' group by NO_CONTAINER";
 $res3 = $db->query($query3);
 $row3=$res3->getAll();
 $notanya = $data[ID_NOTA];
 	foreach($row3 as $rows2) {
		if($detail2<>'')	$detail2.=',';
		$detail2 .= $rows2[NO_CONTAINER];
	}
 
 $row3=$res3->getAll();
//print_r("SELECT * from TB_NOTA_DELIVERY_D A, TB_NOTA_DELIVERY_H B, MASTER_BARANG C WHERE A.ID_NOTA='$p1'  AND A.ID_NOTA=B.ID_NOTA AND C.KODE_BARANG=A.ID_CONT");die;
$query_dtl="SELECT A.*,C.*, to_char(A.TARIF,'999,999,999,999,999.00') AS TARIFN, to_char(A.SUB_TOTAL,'999,999,999,999,999.00') AS SUB_TOTALN 
			from NOTA_HICOSCAN_D A, NOTA_HICOSCAN_H B, MASTER_BARANG C WHERE A.ID_NOTA='$p1'  AND A.ID_NOTA=B.ID_NOTA AND C.KODE_BARANG=A.ID_BARANG";
	$res = $db->query($query_dtl);
	//print_R($res);die;
	$i=0;
	//unset($detail);
	 $row2=$res->getAll();
	foreach($row2 as $rows) {
					
		$detail .= '
		<tr style="font-weight:bold">    <td colspan="3" width="45"><font size="6">'.$rows[KETERANGAN].'</font></td>                        
                        <td width="15" align="center"><font size="6">'.$rows[JUMLAH_CONT].'</font></td>                                
                        <td width="48" align="center"><font size="6">'.$rows[UKURAN]." ".$rows[TYPE]." ".$rows[STATUS].'</font></td>                                
                        <td width="15" align="center"><font size="6">'.$rows[HZ].'</font></td>
                        <td width="19" align="center"><font size="6">'.$rows[JUMLAH_HARI].'</font></td>
                        <td width="55" align="right"><font size="6">'.$rows[TARIFN].'</font></td>                        
                        <td width="55" align="right"><font size="6">'.$rows[SUB_TOTALN].'</font></td>        
                        </tr>                        
						
		';
		$i++;
	}


// jumlah detail barangnya
$query_jum="SELECT COUNT(1) JUM_DETAIL FROM BH_DETAIL_NOTA A WHERE A.ID_NOTA='$p1'";
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
               
		<td COLSPAN="2" align="left"><font size="8"><b>$data[ID_NOTA]</b></font></td>
                 <td><font size="8"><b>$data[ID_USER] | $date</b></font></td>
                </tr> 
                <tr>                    
                    <td COLSPAN="3" align="left"><font size="6"><b>$data[ID_REQUEST]</b></font>  </td>
                </tr>                  
                
                <tr>
                    <td></td>					
                </tr> 
                
                <tr>                    
                    <td align="left" width="200px"><font size="8"><b>HI CO SCAN $data[TIPE_REQUEST]</b></font></td>
                    
                </tr>
                
                
                <tr>                    
                    <td COLSPAN="4" align="left"><b>$data[EMKL]</b></td>					
                </tr>
                <tr>                    
                    <td COLSPAN="4" align="left"><b>$data[NPWP]</b></td>                    
                </tr>
                <tr>                    
                    <td COLSPAN="6" align="left"><b>$data[ALAMAT_EMKL]</b></td>					
                </tr>
                <tr>                    
                    <td COLSPAN="4" align="left"><b>$data[VESSEL] / $data[VOYAGE]</b></td>
                    <td colspan="7"></td>
                </tr>                                                             
                                
                <tr>
                <td></td>
                </tr>
                
                <tr>                    
                    <td width="70px"><b>NO. CONTAINER :</b> </td>                    
                    <td><b>$detail2</b></td>                    
                </tr>                               
                
                <tr>
                    <th colspan="3" width="45"><font size="6"><b>KETERANGAN</b></font></th>                    
                    <th width="15" align="center"><font size="6"><b>BX</b></font></th>                
                    <th width="48" align="center"><font size="6"><b>CONTENT</b></font></th>                                   
                    <th width="15" align="center"><font size="6"><b>HZ</b></font></th>
                    <th width="19" align="center"><font size="6"><b>HARI</b></font></th>
                    <th width="55" align="center"><font size="6"><b>TARIF</b></font></th>                    
                    <th width="55" align="center"><font size="6" ><b>JUMLAH</b></font></th>
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
        <tr style="font-weight:bold">
                        <td colspan="6" align="right">Discount :</td>
                        <td width="55" colspan="2" align="right">0.00</td>
                    </tr>
                    <tr style="font-weight:bold">                        
                        <td colspan="6" align="right">Administrasi :</td>
                        <td colspan="2" align="right">$data[ADM_NOTAN]</td>
                    </tr>
                    <tr style="font-weight:bold">
                      
                        <td colspan="6" align="right">Dasar Peng. Pajak :</td>
                        <td colspan="2" align="right">$data[TAGIHANN]</td>
                    </tr>
                    <tr style="font-weight:bold">
                        
                        <td colspan="6" align="right">Jumlah PPN :</td>
                        <td colspan="2" align="right">$data[PPNN]</td>
                    </tr>
                    <tr style="font-weight:bold">                        
                        <td colspan="6" align="right">Jumlah PPN Subsidi :</td>
                        <td colspan="2" align="right">0.00</td>
                    </tr>
                    <tr style="font-weight:bold">                     
                        <td colspan="6" align="right"><font size="8">Jumlah Dibayar :</font></td>
                        <td colspan="2" align="right"><font size="8">$data[TOTALN]</font></td>
                    </tr>                            
                    </table>                
                <br>
                    <br>
                    <br>
                    <br>
EOD;
$tbl .=<<<EOD
<table> 
                    <tr>
                    <td colspan="8">
                        <hr style="border: dashed 2px #C0C0C0" color="#FFFFFF" size="6" width="700">
                    </td>
                    </tr>
                    <tr>
                    <td colspan="8">
                        <i>form untuk Bank</i>
                    </td>
                    </tr>                   
                    <tr>
                    <td colspan="8">
                        &nbsp;
                    </td>
                    </tr>                   
                    <tr>
                    <td colspan="8">
                        &nbsp;
                    </td>
                    </tr>                   
                    <tr>
                        <td colspan="3" align="right"><font size="8"><b>Nomor Invoice :</b></font></td>
                        <td colspan="4" align="left"><font size="8"> <b>$data[ID_NOTA]</b></font></td>
                    </tr>               
                    <tr>
                        <td colspan="3" align="right"><font size="8"><b>Customer :</b></font></td>
                        <td colspan="4" align="left"><font size="8"> <b>$data[EMKL]</b></font></td>
                    </tr>               
                    <tr>
                     
                        <td colspan="3" align="right"><font size="8"><b>Jumlah Dibayar :</b></font></td>
                        <td colspan="4" align="left"><font size="8"> Rp. <b>$data[TOTALN]</b></font></td>
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
        $pdf->write1DBarcode("$notanya", 'C128', 0, 0, '', 18, 0.4, $style, 'N');
        //Logo IPC
        //$pdf->Image('images/ipc2.jpg', 50, 7, 20, 10, '', '', '', true, 72);	
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