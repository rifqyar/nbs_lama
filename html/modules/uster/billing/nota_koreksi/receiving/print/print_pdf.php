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

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 048');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 048', PDF_HEADER_STRING);

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);


// set header and footer fonts
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins($left = 4,
				 $top,
				 $right = 4,
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
//$pdf->SetFont('courier', 'B', 20);

// add a page
$pdf->AddPage();

//$pdf->Write(0, 'Example of HTML tables', '', 0, 'L', true, 0, false, false, 0);

$pdf->SetFont('courier', '', 10);



//koneksi db oracle
/*
$db ="(DESCRIPTION=
    (ADDRESS=
      (PROTOCOL=TCP)
      (HOST=anton-PC)
      (PORT=1521)
    )
    (CONNECT_DATA=
      (SERVER=dedicated)
      (SERVICE_NAME=XE)
    )
  )";*/	
  
 
//
$db = getDB("storage");


// 
//$conn = ocilogon("STORAGE", "STORAGE","$db")or die ("can't connect to server");

$no_nota	= $_GET["no_nota"];
$no_req		= $_GET["no_req"];

$query_nota	= "SELECT a.NO_NOTA AS NO_NOTA,
                          a.NO_FAKTUR AS NO_FAKTUR,
                          TO_CHAR(a.TGL_NOTA,'dd-mm-yyyy HH24:MI:SS') AS TGL_NOTA,
                          c.NM_PBM AS EMKL,
                          a.NO_REQUEST AS NO_REQUEST,
                          a.LUNAS AS LUNAS,
                          c.NO_NPWP_PBM AS NPWP,
                          c.ALMT_PBM AS ALAMAT
                   FROM NOTA_RECEIVING a,
                        REQUEST_RECEIVING b,
                        V_MST_PBM c
                   WHERE a.NO_NOTA = '$no_nota'
                    AND     a.NO_REQUEST = b.NO_REQUEST
                    AND     b.KD_CONSIGNEE = c.KD_PBM
				   ";



				   
//$result_nota = OCIparse($conn, $query_nota);
//ociexecute($result_nota);
//OCIFetchInto ($result_nota, $row_nota, OCI_ASSOC);

$result_nota	= $db->query($query_nota);
$row_nota		= $result_nota->fetchRow(); 
//debug($row_list);
	
    //print $row['NO_NOTA'];die;
	


//SEMENTARA PROCEDURE DI ORACLE BELUM JALAN, PAKAI INI DULU
	//TARIF LOLO
$query_trf_lo ="SELECT a.STATUS AS STATUS,
					   a.HZ AS HZ,
					   b.TYPE_ AS TYPE_,
					   b.SIZE_ AS SIZE_,
					   d.ID_ISO AS ISO,
					   TO_CHAR(d.TARIF, '999,999,999,999')   AS TARIFKU,
					   COUNT(c.ID_ISO) AS JUM,
					   TO_CHAR((COUNT(c.ID_ISO) * d.TARIF) , '999,999,999,999') AS TOTAL,
					   (COUNT(c.ID_ISO) * d.TARIF) AS TOTAL_CAL
				FROM    CONTAINER_RECEIVING a, 
						MASTER_CONTAINER b,
						ISO_CODE c,
						MASTER_TARIF d,
						GROUP_TARIF e
				WHERE a.NO_REQUEST = '$no_req'
				  AND a.NO_CONTAINER = b.NO_CONTAINER
				  AND d.ID_GROUP_TARIF = '2'
				  AND b.SIZE_ = c.SIZE_
				  AND b.TYPE_ = c.TYPE_ 
				  AND a.STATUS = c.STATUS
				  AND c.ID_ISO = d.ID_ISO
				  AND d.ID_GROUP_TARIF = e.ID_GROUP_TARIF
				  GROUP BY   a.HZ ,
							 b.TYPE_ ,
							 b.SIZE_ ,
							 d.ID_ISO,
							 d.TARIF,
							 a.STATUS
				  ORDER BY d.ID_ISO" ;


//$result_trf_lo = OCIparse($conn, $query_trf_lo);
//ociexecute($result_trf_lo);	

$result_trf_lo	= $db->query($query_trf_lo);
$row_trf_lo		= $result_trf_lo->getAll(); 

	
//END OF TARIF LOLO
//TARIF PENUMPUKKAN
/*
$query_trf ="SELECT    a.STATUS AS STATUS,
					   a.HZ AS HZ,
					   b.TYPE_ AS TYPE_,
					   b.SIZE_ AS SIZE_,
					   d.ID_ISO AS ISO,
					   TO_CHAR(d.TARIF, '999,999,999,999')   AS TARIFKU,
					   COUNT(c.ID_ISO) AS JUM,
					   TO_CHAR((COUNT(c.ID_ISO) * d.TARIF) , '999,999,999,999') AS TOTAL,
					   (COUNT(c.ID_ISO) * d.TARIF) AS TOTAL_CAL
			FROM    	storage.CONTAINER_RECEIVING a, 
						storage.MASTER_CONTAINER b,
						storage.ISO_CODE c,
						storage.MASTER_TARIF d,
						storage.GROUP_TARIF e
			WHERE a.NO_REQUEST = '$no_req'
			  AND a.NO_CONTAINER = b.NO_CONTAINER
			  AND d.ID_GROUP_TARIF = '1'
			  AND b.SIZE_ = c.SIZE_
			  AND b.TYPE_ = c.TYPE_ 
			  AND a.STATUS = c.STATUS
			  AND c.ID_ISO = d.ID_ISO
			  AND d.ID_GROUP_TARIF = e.ID_GROUP_TARIF
			  GROUP BY   a.HZ ,
						 b.TYPE_ ,
						 b.SIZE_ ,
						 d.ID_ISO,
						 d.TARIF,
						 a.STATUS
			  ORDER BY d.ID_ISO" ;





//$result_trf = OCIparse($conn, $query_trf);
//ociexecute($result_trf);	

$result_trf	= $db->query($query_trf);
$row_trf		= $result_trf->getAll(); 
*/
//END OF TARIF PENUMPUKKAN
//TARIF KEBERSIHAN
$query_trf_brsh ="SELECT   DISTINCT     b.SIZE_ AS SIZE_,
						   a.HZ AS HZ,
                           TO_CHAR(d.TARIF, '999,999,999,999')   AS TARIFKU,
						   COUNT(c.ID_ISO) AS JUM,
						   TO_CHAR((COUNT(c.ID_ISO) * d.TARIF) , '999,999,999,999') AS TOTAL,
						   (COUNT(c.ID_ISO) * d.TARIF) AS TOTAL_CAL
					FROM    CONTAINER_RECEIVING a, 
							MASTER_CONTAINER b,
							ISO_CODE c,
							MASTER_TARIF d,
							GROUP_TARIF e
					WHERE a.NO_REQUEST = '$no_req'
					  AND a.NO_CONTAINER = b.NO_CONTAINER
					  AND d.ID_GROUP_TARIF = '6'
					  AND b.SIZE_ = c.SIZE_
					  AND c.ID_ISO = d.ID_ISO
					  AND d.ID_GROUP_TARIF = e.ID_GROUP_TARIF
					  GROUP BY  a.HZ ,
								b.SIZE_ ,
							    c.ID_ISO,
							    d.TARIF
					  ORDER BY b.SIZE_" ;





//$result_trf_brsh = OCIparse($conn, $query_trf_brsh);
//ociexecute($result_trf_brsh);

$result_trf_brsh	= $db->query($query_trf_brsh);
$row_trf_brsh		= $result_trf_brsh->getAll(); 
//END OF TARIF KEBERSIHAN
$html = ' 	
<div id="print" style="margin-left:0px;" >
<table  >
	
	<tr>
		<td colspan="32" align="left"><b>PT. PELABUHAN INDONESIA II (PERSERO)</b></td>
	</tr>
	<tr>
		<td colspan="32" align="left"><b>CABANG PONTIANAK</b></td>
	</tr>
	<tr>
		<td colspan="26"></td>
		<td colspan="5" align="right"><b>'.$row_nota['NO_NOTA'].'</b></td>
	</tr>
	<tr><td></td></tr>
	<tr>
		<td colspan="19"></td>
		<td colspan="4" align="left">No. Faktur</td>
		<td colspan="1" align="right">:</td>
		<td colspan="8" align="left"> '.$row_nota['NO_FAKTUR'].'</td>
	</tr>
	<tr>
		<td colspan="19"></td>
		<td colspan="4" align="left">No. Doc</td>
		<td colspan="1" align="right">:</td>
		<td colspan="8" align="left"> '.$row_nota['NO_REQUEST'].'</td>
	</tr>
	
	<tr>
		<td COLSPAN="19"></td>
		<td COLSPAN="4" align="left">Tgl.Proses</td>
		<td colspan="1" align="right">:</td>
		<td COLSPAN="8" align="left"> '.$row_nota['TGL_NOTA'].'</td>
	</tr>
	
	<tr><td></td></tr>
	<tr>
		<td COLSPAN="18"></td>
		<td COLSPAN="14" align="left"><font size="12">PENUMPUKAN / GERAKAN ( RECEIVING )</font></td>
	</tr>
	<tr><td></td></tr>
	<tr>
				<td colspan="6"></td>
				<td colspan="11" align="left"><font size="9">'.$row_nota['EMKL'].'</font></td>
				<td ></td>
	</tr> 
	<tr>
				<td colspan="6"></td>
				<td colspan="11" align="left"><font size="9">'.$row_nota['NPWP'].'</font></td>
				<td ></td>
	</tr>
	<tr>
				<td colspan="6"></td>
				<td colspan="11" align="left"><font size="9">'.$row_nota['ALAMAT'].'</font></td>
				<td ></td>
	</tr>
	<tr><td></td></tr>
	<tr><td></td></tr>
	<tr><td></td></tr>
	<tr>
		<td colspan="9" ><b>KETERANGAN</b></td>
		<td colspan="2" align="center"><b>BOX</b></td>
		<td colspan="2" align="center"><b>SZ</b></td>
		<td colspan="2" align="center"><b>TY</b></td>
		<td colspan="2" align="center"><b>ST</b></td>
		<td colspan="2" align="center"><b>HZ</b></td>
		<td colspan="5" align="right"><b>TARIF</b></td>
		<td colspan="2" align="right"><b>VAL</b></td>
		<td colspan="5" align="right"><b>JUMLAHs</b></td>
	</tr>
	<tr><td colspan="32">--------------------------------------------------------------------------------------------</td></tr>
	';
foreach ($row_trf_brsh as  $row_trf_brsh) {
$jumlah = $row_trf_brsh["TOTAL_CAL"];
$total_brsh += $jumlah;

$html .='<tr>
		<td colspan="9" >KEBERSIHAN</td>
		<td colspan="2" align="center">'.$row_trf_brsh['JUM'].'</td>
		<td colspan="2" align="center">'.$row_trf_brsh['SIZE_'].'</td>
		<td colspan="2" align="center"></td>
		<td colspan="2" align="center"></td>
		<td colspan="2" align="center">'.$row_trf_brsh['HZ'].'</td>
		<td  colspan="5" align="right">'.$row_trf_brsh['TARIFKU'].'</td>
		<td colspan="2" align="right">IDR</td>
		<td  colspan="5" align="right">'.$row_trf_brsh['TOTAL'].'</td>
	</tr>';

 }	
	
foreach ($row_trf_lo as $row_trf_lo) {
$jumlah = $row_trf_lo["TOTAL_CAL"];
$total_lo += $jumlah;

$html .='<tr>
		<td colspan="9" >LIFT OFF</td>
		<td colspan="2" align="center">'.$row_trf_lo['JUM'].'</td>
		<td colspan="2" align="center">'.$row_trf_lo['SIZE_'].'</td>
		<td colspan="2" align="center">'.$row_trf_lo['TYPE_'].'</td>
		<td colspan="2" align="center">'.$row_trf_lo['STATUS'].'</td>
		<td colspan="2" align="center">'.$row_trf_lo['HZ'].'</td>
		<td  colspan="5" align="right">'.$row_trf_lo['TARIFKU'].'</td>
		<td colspan="2" align="right">IDR</td>
		<td  colspan="5" align="right">'.$row_trf_lo['TOTAL'].'</td>
	</tr>';

 }
 
foreach($row_trf as $row_trf) {
$jumlah = $row_trf["TOTAL_CAL"];
		$total_pnk += $jumlah;

$html .='<tr>
		<td colspan="9" >PENUMPUKKAN MASSA I</td>
		<td colspan="2" align="center">'.$row_trf['JUM'].'</td>
		<td colspan="2" align="center">'.$row_trf['SIZE_'].'</td>
		<td colspan="2" align="center">'.$row_trf['TYPE_'].'</td>
		<td colspan="2" align="center">'.$row_trf['STATUS'].'</td>
		<td colspan="2" align="center">'.$row_trf['HZ'].'</td>
		<td  colspan="5" align="right">'.$row_trf['TARIFKU'].'</td>
		<td colspan="2" align="right">IDR</td>
		<td  colspan="5" align="right">'.$row_trf['TOTAL'].'</td>
	</tr>';

 }
 
 //Discount
	$result_trf_brsh	= $db->query($query_trf_brsh);
	$row_trf_brsh		= $result_trf_brsh->fetchRow(); 
	
	$discount =0;
	$query_discount		= "SELECT TO_CHAR($discount , '999,999,999,999') AS DISCOUNT FROM DUAL";
	$result_discount	= $db->query($query_discount);
	$row_discount		= $result_discount->fetchRow();
		//$result_discount = OCIparse($conn, $query_discount);
		//ociexecute($result_discount);	
		//OCIFetchInto ($result_discount, $row_discount, OCI_ASSOC);
//Biaya Administrasi
	$adm =10000;
	$query_adm		= "SELECT TO_CHAR($adm , '999,999,999,999') AS ADM FROM DUAL";
	$result_adm	= $db->query($query_adm);
	$row_adm		= $result_adm->fetchRow();
		//$result_adm = OCIparse($conn, $query_adm);
		//ociexecute($result_adm);	
		//OCIFetchInto ($result_adm, $row_adm, OCI_ASSOC);
//Menghitung Total dasar pengenaan pajak
	$total = $total_pnk + $total_lo + $total_brsh;
	$query_tot		= "SELECT TO_CHAR('$total' , '999,999,999,999') AS TOTAL_ALL FROM DUAL";
	$result_tot	= $db->query($query_tot);
	$row_tot		= $result_tot->fetchRow();
		//$result_tot = OCIparse($conn, $query_tot);
		//ociexecute($result_tot);	
		//OCIFetchInto ($result_tot, $row_tot, OCI_ASSOC);
//Menghitung Jumlah PPN
	$ppn = $total/10;
	$query_ppn		= "SELECT TO_CHAR('$ppn' , '999,999,999,999') AS PPN FROM DUAL";
	$result_ppn	= $db->query($query_ppn);
	$row_ppn		= $result_ppn->fetchRow();
		//$result_ppn = OCIparse($conn, $query_ppn);
		//ociexecute($result_ppn);	
		//OCIFetchInto ($result_ppn, $row_ppn, OCI_ASSOC);
	//Menghitung Bea Materai
	$bea_materai = 0;
	$query_materai		= "SELECT TO_CHAR('$bea_materai' , '999,999,999,999') AS MATERAI FROM DUAL";
	$result_ppn	= $db->query($query_ppn);
	$row_ppn		= $result_ppn->fetchRow();
		//$result_ppn = OCIparse($conn, $query_ppn);
		//ociexecute($result_materai);	
		//OCIFetchInto ($result_materai, $row_materai, OCI_ASSOC);
	//Menghitung Jumlah dibayar
	$total_bayar = $adm + $total + $ppn;
	$query_bayar		= "SELECT TO_CHAR('$total_bayar' , '999,999,999,999') AS TOTAL_BAYAR FROM DUAL";
	$result_bayar	= $db->query($query_bayar);
	$row_bayar		= $result_bayar->fetchRow();
	//$result_bayar = OCIparse($conn, $query_bayar);
	//ociexecute($result_bayar);	
	//OCIFetchInto ($result_bayar, $row_bayar, OCI_ASSOC);
 
//print_r($a);die;
//
$html .='	<tr><td colspan="32">--------------------------------------------------------------------------------------------</td></tr>
			<tr><td></td></tr>
			<tr><td></td></tr>
			<tr>
					<td colspan="17"></td>
					<td colspan="8"  align="right">Discount :</td>
                    <td colspan="6" align="right">'.$row_discount['DISCOUNT'].'</td>
				</tr>
				<tr>
					<td colspan="17"></td>
					<td colspan="8"  align="right">Administrasi :</td>
                    <td colspan="6" align="right">'.$row_adm['ADM'].'</td>
				</tr>
				<tr>
					<td colspan="17"></td>
					<td colspan="8"  align="right">Dasar Pengenaan Pajak :</td>
                    <td colspan="6" align="right">'.$row_tot['TOTAL_ALL'].'</td>
				</tr>
				<tr>
					<td colspan="17"></td>
					<td colspan="8"  align="right">Jumlah PPN :</td>
                    <td colspan="6" align="right">'.$row_ppn['PPN'].'</td>
				</tr>
				<tr>
					<td colspan="17"></td>
					<td colspan="8"  align="right">Bea Materai :</td>
                    <td colspan="6" align="right">'.$row_materai['MATERAI'].'</td>
				</tr>
				<tr>
					<td colspan="17"></td>
					<td colspan="8"  align="right">Jumlah Dibayar :</td>
                    <td colspan="6" align="right">'.$row_bayar['TOTAL_BAYAR'].'</td>
				</tr>
				<tr>
				<tr><td></td></tr>
				<tr>
					<td colspan="2"><font size="9">USER :</font></td>
				</tr>
				<tr><td></td></tr>
				<tr><td></td></tr>
				<tr><td></td></tr>
				<tr><td></td></tr>
				<tr><td></td></tr>
				<tr>
				<td colspan="17"><font size="8">Nota sebagai Faktur Pajak berdasarkan Peraturan Dirjen Pajak
				Nomor 10/PJ/2010 Tanggal 9 Maret 2010</font></td>
				</tr>
				<tr><td></td></tr>
				<tr><td></td></tr>
				<tr><td></td></tr>
				<tr><td></td></tr>
				<tr>
					<td colspan="24"></td>
					<td colspan="7"><font size="8">'.date('Y-m-d H:i:s').'</font></td>
				</tr>
				<tr>
					<td colspan="22"></td>
					<td colspan="7"><font size="8">MANAGER KEUANGAN</font></td>
				</tr>
				<tr><td></td></tr><tr><td></td></tr>
				<tr>
					<td colspan="22"></td>
					<td colspan="7"><font size="8">MUW</font></td>
				</tr>
				<tr>
					<td colspan="22"></td>
					<td colspan="7"><font size="8">NIPP : 277107035</font></td>
				</tr>
				
				<tr>
					<td></td>
				</tr>
			</table>
			</div>';

// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('print_pdf.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+

?>