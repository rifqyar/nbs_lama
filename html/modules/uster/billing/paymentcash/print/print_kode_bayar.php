<?php

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');

$no_req = $_GET['no_req'];
$db = getDB('storage');
$query = $db->query("
            SELECT
            *
            FROM
            (
            SELECT
                NO_REQUEST,
                NO_NOTA,
                TGL_NOTA_1,
                PAYMENT_CODE,
                'RECEIVING' KEGIATAN,
                TANGGAL_LUNAS,
                LUNAS,
                EMKL,
                TGL_NOTA,
                TOTAL_TAGIHAN
            FROM
                nota_receiving
            WHERE
                STATUS <> 'BATAL'
            UNION
            SELECT
                NO_REQUEST,
                NO_NOTA,
                TGL_NOTA_1,
                PAYMENT_CODE,
                CASE
                    WHEN STATUS = 'PERP' THEN 'PERP_PNK'
                    ELSE 'STUFFING'
                END KEGIATAN,
                TANGGAL_LUNAS,
                LUNAS,
                EMKL,
                TGL_NOTA,
                TOTAL_TAGIHAN
            FROM
                nota_stuffing
            WHERE
                STATUS <> 'BATAL'
            UNION
            SELECT
                NO_REQUEST,
                NO_NOTA,
                TGL_NOTA_1,
                PAYMENT_CODE,
                CASE
                    WHEN SUBSTR(NO_NOTA, 0, 2) = '03' THEN 'STRIPPING'
                    ELSE 'PERP_STRIP'
                END KEGIATAN,
                TANGGAL_LUNAS,
                LUNAS,
                EMKL,
                TGL_NOTA,
                TOTAL_TAGIHAN
            FROM
                nota_stripping
            WHERE
                STATUS <> 'BATAL'
            UNION
            SELECT
                NO_REQUEST,
                NO_NOTA,
                TGL_NOTA_1,
                PAYMENT_CODE,
                CASE
                    WHEN STATUS = 'PERP' THEN 'PERP_DEV'
                    ELSE 'DELIVERY'
                END KEGIATAN,
                TANGGAL_LUNAS,
                LUNAS,
                EMKL,
                TGL_NOTA,
                TOTAL_TAGIHAN
            FROM
                nota_delivery
            WHERE
                STATUS <> 'BATAL'
            UNION
            SELECT
                NO_REQUEST,
                NO_NOTA,
                TGL_NOTA_1,
                PAYMENT_CODE,
                'RELOKASI' KEGIATAN,
                TANGGAL_LUNAS,
                LUNAS,
                EMKL,
                TGL_NOTA,
                TOTAL_TAGIHAN
            FROM
                nota_relokasi
            WHERE
                STATUS <> 'BATAL'
            UNION
            SELECT
                NO_REQUEST,
                NO_NOTA,
                TGL_NOTA,
                PAYMENT_CODE,
                'BATAL_MUAT' KEGIATAN,
                TGL_LUNAS,
                LUNAS,
                EMKL,
                TGL_NOTA,
                TOTAL_TAGIHAN
            FROM
                nota_batal_muat
            WHERE
                STATUS <> 'BATAL'
            UNION
            SELECT
                NO_REQUEST,
                NO_NOTA,
                TGL_NOTA_1,
                PAYMENT_CODE,
                'RELOK_MTY' KEGIATAN,
                TANGGAL_LUNAS,
                LUNAS,
                EMKL,
                TGL_NOTA,
                TOTAL_TAGIHAN
            FROM
                nota_relokasi_mty
            WHERE
                STATUS <> 'BATAL'
            UNION
            SELECT
                NO_REQUEST,
                NO_NOTA,
                TGL_NOTA_1,
                PAYMENT_CODE,
                'DEL_PNK' KEGIATAN,
                TANGGAL_LUNAS,
                LUNAS,
                EMKL,
                TGL_NOTA,
                TOTAL_TAGIHAN
            FROM
                nota_pnkn_del
            WHERE
                STATUS <> 'BATAL'
            UNION
            SELECT
                NO_REQUEST,
                NO_NOTA,
                TGL_NOTA_1,
                PAYMENT_CODE,
                'STUF_PNK' KEGIATAN,
                TANGGAL_LUNAS,
                LUNAS,
                EMKL,
                TGL_NOTA,
                TOTAL_TAGIHAN
            FROM
                nota_pnkn_stuf
            WHERE
                STATUS <> 'BATAL')
            WHERE
            NO_NOTA IS NOT NULL
            AND PAYMENT_CODE IS NOT NULL
            AND TANGGAL_LUNAS IS NOT NULL
            AND LUNAS = 'YES'
            AND NO_REQUEST = '$no_req'
            ORDER BY
            DBMS_RANDOM.VALUE
                    FETCH NEXT 1 ROWS ONLY");
$result = $query->fetchRow();

if ($result) {


    $pdf = new TCPDF('P', 'mm', array(80, 90), true, 'UTF-8', false);

    // remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    // add a page
    $pdf->AddPage();

    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(0, 10, 'Payment Code', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    $pdf->Ln(4);

    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, $result['PAYMENT_CODE'], 0, false, 'C', 0, '', 0, false, 'M', 'M');
    $pdf->Ln(3);

   
    // define barcode style
    $style = array(
        'position' => '',
        'align' => 'C',
        'stretch' => false,
        'fitwidth' => true,
        'cellfitalign' => '',
        'border' => true,
        'hpadding' => 'auto',
        'vpadding' => 'auto',
        'fgcolor' => array(0, 0, 0),
        'bgcolor' => false, //array(255,255,255),
        'text' => true,
        'font' => 'helvetica',
        'fontsize' => 8,
        'stretchtext' => 4
    );


    // Center position
    $style['position'] = 'C';
    $pdf->write1DBarcode($result['PAYMENT_CODE'], 'C128A', '', '', '', 15, 0.4, $style, 'N');

    $pdf->Ln(2);

    $pdf->SetFont('helvetica', '', 7);
    $pdf->SetX(40);
    $pdf->SetY(33);
    $pdf->Cell(0, 7, 'Biller Name : ', 0, 0, 'L');

    $pdf->SetFont('helvetica', '', 7);
    $pdf->SetX(60);
    $pdf->SetY(33);
    $pdf->Cell(0, 7, 'Multi Terminal Indonesia', 0, 1, 'R');


    $pdf->SetFont('helvetica', '', 7);
    $pdf->SetX(40);
    $pdf->SetY(37);
    $pdf->Cell(0, 7, 'Customer Name : ', 0, 0, 'L');

    $emkl = $result['EMKL'];
    if (strlen($emkl) > 27) {
        $emkl = substr($emkl, 0, 27).".."; // Memotong string menjadi maksimal 15 karakter
    }

    $pdf->SetFont('helvetica', '', 6);
    $pdf->SetX(60);
    $pdf->SetY(37);
    $pdf->Cell(0, 7,  $emkl, 0, 1, 'R');

    $pdf->SetFont('helvetica', '', 7);
    $pdf->SetX(40);
    $pdf->SetY(41);
    $pdf->Cell(0, 7, 'Date : ', 0, 0, 'L');
    

    // Membuat objek DateTime dari string tanggal
    $date = DateTime::createFromFormat('d-M-y h.i.s.u A', $result['TGL_NOTA']);

    // Memformat tanggal sesuai dengan format yang diinginkan
    $new_date_string = $date->format("d-M-y h:i:s");

    $pdf->SetFont('helvetica', '', 7);
    $pdf->SetX(60);
    $pdf->SetY(41);
    $pdf->Cell(0, 7, $new_date_string, 0, 1, 'R');


    $pdf->SetFont('helvetica', 'B', 7);
    $pdf->SetX(40);
    $pdf->SetY(47);
    $pdf->Cell(0, 7, 'PROFORMA', 0, 0, 'L');

    $pdf->SetFont('helvetica', 'B', 7);
    $pdf->SetX(60);
    $pdf->SetY(47);
    $pdf->Cell(0, 7, 'AMOUNT', 0, 1, 'R');

    $pdf->SetFont('helvetica', '', 7);
    $pdf->SetX(40);
    $pdf->SetY(50);
    $pdf->Cell(0, 7, $result['NO_NOTA'], 0, 0, 'L');

    $rupiah = 'Rp ' . number_format($result['TOTAL_TAGIHAN'], 0, ',', '.');

    $pdf->SetFont('helvetica', '', 7);
    $pdf->SetX(60);
    $pdf->SetY(50);
    $pdf->Cell(0, 7, $rupiah, 0, 1, 'R');


    $pdf->SetFont('helvetica', 'B', 7);
    $pdf->SetX(40);
    $pdf->SetY(62);
    $pdf->Cell(0, 7, "TOTAL", 0, 0, 'L');

    $rupiah = 'Rp ' . number_format($result['TOTAL_TAGIHAN'], 0, ',', '.');

    $pdf->SetFont('helvetica', 'B', 7);
    $pdf->SetX(60);
    $pdf->SetY(62);
    $pdf->Cell(0, 7, $rupiah, 0, 1, 'R');

    // ---------------------------------------------------------

    //Close and output PDF document
    $pdf->Output('example_027.pdf', 'I');

    //============================================================+
    // END OF FILE
    //============================================================+

}
