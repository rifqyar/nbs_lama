<?php
//header('Location: '.HOME .'static/error.htm');		
$tl =  xliteTemplate('nota_list.htm');


//------------------------	
$id_yard = $_SESSION["IDYARD_STORAGE"];

$cari        = $_POST["CARI"];
$no_req = trim($_POST["NO_REQ"]);
$from   = $_POST["FROM"];
$to     = $_POST["TO"];
$id_yard  = $_SESSION["IDYARD_STORAGE"];


$db = getDB("storage");

//if (($_SESSION["ID_ROLE"] == '1') OR ($_SESSION["ID_ROLE"] == '2')){
if (isset($cari) && !empty($cari)) { // Periksa jika "CARI" aktif dan tidak kosong
        if (!empty($no_req) && empty($from) && empty($to)) {
                // Jika NO_REQ ada, tetapi FROM dan TO kosong
                $query_list                = "SELECT NVL(nota_delivery.LUNAS, 0) LUNAS, NVL(nota_delivery.NO_FAKTUR, '-') NO_NOTA, request_delivery.NO_REQUEST, TO_CHAR( request_delivery.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_DATE(request_delivery.TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM AS NAMA_EMKL,   COUNT(container_delivery.NO_CONTAINER) JML_CONT, request_delivery.PERP_DARI
                        FROM request_delivery, nota_delivery, V_MST_PBM emkl,  container_delivery
                        WHERE  request_delivery.KD_EMKL = emkl.KD_PBM and emkl.KD_CABANG = '05'
                        AND request_delivery.NO_REQUEST = container_delivery.NO_REQUEST
                        
                        AND nota_delivery.NO_REQUEST(+) = request_delivery.NO_REQUEST
                        and request_delivery.STATUS = 'PERP'
						AND request_delivery.NO_REQUEST LIKE '%$no_req%'
                        GROUP BY  NVL(nota_delivery.LUNAS, 0), NVL(nota_delivery.NO_FAKTUR, '-'),request_delivery.NO_REQUEST, TO_CHAR( request_delivery.TGL_REQUEST,'dd/mm/yyyy'), TO_DATE(request_delivery.TGL_REQUEST_DELIVERY,'dd/mm/yyyy'), emkl.NM_PBM,request_delivery.PERP_DARI,request_delivery.TGL_REQUEST
                        ORDER BY request_delivery.TGL_REQUEST DESC";
        } elseif (!empty($from) && !empty($to) && empty($no_req)) {
                // Jika FROM dan TO ada, tetapi NO_REQ kosong
                $query_list                = " SELECT NVL(nota_delivery.LUNAS, 0) LUNAS, NVL(nota_delivery.NO_FAKTUR, '-') NO_NOTA, request_delivery.NO_REQUEST, TO_CHAR( request_delivery.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_DATE(request_delivery.TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM AS NAMA_EMKL,   COUNT(container_delivery.NO_CONTAINER) JML_CONT, request_delivery.PERP_DARI
                                FROM request_delivery, nota_delivery, V_MST_PBM emkl,  container_delivery
                                WHERE  request_delivery.KD_EMKL = emkl.KD_PBM and emkl.KD_CABANG = '05'
                                AND request_delivery.NO_REQUEST = container_delivery.NO_REQUEST
                                
                                AND nota_delivery.NO_REQUEST(+) = request_delivery.NO_REQUEST
                                and request_delivery.STATUS = 'PERP'
                                                        AND request_delivery.TGL_REQUEST BETWEEN TO_DATE ( '$from', 'YYYY-MM-DD ')
                                AND TO_DATE (  CONCAT('$to', '23:59:59'), 'YYYY-MM-DD HH24:MI:SS')
                        GROUP BY  NVL(nota_delivery.LUNAS, 0), NVL(nota_delivery.NO_FAKTUR, '-'),request_delivery.NO_REQUEST, TO_CHAR( request_delivery.TGL_REQUEST,'dd/mm/yyyy'), TO_DATE(request_delivery.TGL_REQUEST_DELIVERY,'dd/mm/yyyy'), emkl.NM_PBM,request_delivery.PERP_DARI,request_delivery.TGL_REQUEST
                        ORDER BY request_delivery.TGL_REQUEST DESC";
        } elseif (!empty($from) && !empty($to) && !empty($no_req)) {
                // Jika FROM, TO, dan NO_REQ semua ada
                $query_list                = " SELECT NVL(nota_delivery.LUNAS, 0) LUNAS, NVL(nota_delivery.NO_FAKTUR, '-') NO_NOTA, request_delivery.NO_REQUEST, TO_CHAR( request_delivery.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_DATE(request_delivery.TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM AS NAMA_EMKL,   COUNT(container_delivery.NO_CONTAINER) JML_CONT, request_delivery.PERP_DARI
                                FROM request_delivery, nota_delivery, V_MST_PBM emkl,  container_delivery
                                WHERE  request_delivery.KD_EMKL = emkl.KD_PBM and emkl.KD_CABANG = '05'
                                AND request_delivery.NO_REQUEST = container_delivery.NO_REQUEST
                                
                                AND nota_delivery.NO_REQUEST(+) = request_delivery.NO_REQUEST
                                and request_delivery.STATUS = 'PERP'
                                                        AND request_delivery.NO_REQUEST = '$no_req'
                                                        AND request_delivery.TGL_REQUEST BETWEEN TO_DATE ( '$from', 'YYYY-MM-DD ')
                                AND TO_DATE (  CONCAT('$to', '23:59:59'), 'YYYY-MM-DD HH24:MI:SS')
                        GROUP BY  NVL(nota_delivery.LUNAS, 0), NVL(nota_delivery.NO_FAKTUR, '-'),request_delivery.NO_REQUEST, TO_CHAR( request_delivery.TGL_REQUEST,'dd/mm/yyyy'), TO_DATE(request_delivery.TGL_REQUEST_DELIVERY,'dd/mm/yyyy'), emkl.NM_PBM,request_delivery.PERP_DARI,request_delivery.TGL_REQUEST
                        ORDER BY request_delivery.TGL_REQUEST DESC";
        } else {
                $query_list     = "SELECT * FROM (SELECT NVL (nota_delivery.LUNAS, 0) LUNAS,
                                                NVL (nota_delivery.NO_FAKTUR, '-') NO_NOTA,
                                                request_delivery.NO_REQUEST,
                                                TO_CHAR (request_delivery.TGL_REQUEST, 'dd/mm/yyyy') TGL_REQUEST,
                                                TO_DATE (request_delivery.TGL_REQUEST_DELIVERY, 'dd/mm/yyyy')
                                                TGL_REQUEST_DELIVERY,
                                                emkl.NM_PBM AS NAMA_EMKL,
                                                COUNT (container_delivery.NO_CONTAINER) JML_CONT,
                                                request_delivery.PERP_DARI
                                        FROM request_delivery,
                                                nota_delivery,
                                                V_MST_PBM emkl,
                                                container_delivery
                                        WHERE     request_delivery.KD_EMKL = emkl.KD_PBM
                                                AND emkl.KD_CABANG = '05'
                                                AND request_delivery.NO_REQUEST = container_delivery.NO_REQUEST
                                                AND nota_delivery.NO_REQUEST(+) = request_delivery.NO_REQUEST
                                                AND request_delivery.STATUS = 'PERP'
                                        GROUP BY NVL (nota_delivery.LUNAS, 0),
                                                NVL (nota_delivery.NO_FAKTUR, '-'),
                                                request_delivery.NO_REQUEST,
                                                TO_CHAR (request_delivery.TGL_REQUEST, 'dd/mm/yyyy'),
                                                TO_DATE (request_delivery.TGL_REQUEST_DELIVERY, 'dd/mm/yyyy'),
                                                emkl.NM_PBM,
                                                request_delivery.PERP_DARI,
                                                request_delivery.TGL_REQUEST
                                        ORDER BY request_delivery.TGL_REQUEST DESC)
                                        WHERE ROWNUM <=100";
        }
} else {
        $query_list     = "SELECT * FROM (SELECT NVL (nota_delivery.LUNAS, 0) LUNAS,
                                         NVL (nota_delivery.NO_FAKTUR, '-') NO_NOTA,
                                         request_delivery.NO_REQUEST,
                                         TO_CHAR (request_delivery.TGL_REQUEST, 'dd/mm/yyyy') TGL_REQUEST,
                                         TO_DATE (request_delivery.TGL_REQUEST_DELIVERY, 'dd/mm/yyyy')
                                            TGL_REQUEST_DELIVERY,
                                         emkl.NM_PBM AS NAMA_EMKL,
                                         COUNT (container_delivery.NO_CONTAINER) JML_CONT,
                                         request_delivery.PERP_DARI
                                    FROM request_delivery,
                                         nota_delivery,
                                         V_MST_PBM emkl,
                                         container_delivery
                                   WHERE     request_delivery.KD_EMKL = emkl.KD_PBM
                                         AND emkl.KD_CABANG = '05'
                                         AND request_delivery.NO_REQUEST = container_delivery.NO_REQUEST
                                         AND nota_delivery.NO_REQUEST(+) = request_delivery.NO_REQUEST
                                         AND request_delivery.STATUS = 'PERP'
                                GROUP BY NVL (nota_delivery.LUNAS, 0),
                                         NVL (nota_delivery.NO_FAKTUR, '-'),
                                         request_delivery.NO_REQUEST,
                                         TO_CHAR (request_delivery.TGL_REQUEST, 'dd/mm/yyyy'),
                                         TO_DATE (request_delivery.TGL_REQUEST_DELIVERY, 'dd/mm/yyyy'),
                                         emkl.NM_PBM,
                                         request_delivery.PERP_DARI,
                                         request_delivery.TGL_REQUEST
                                ORDER BY request_delivery.TGL_REQUEST DESC)
                                WHERE ROWNUM <=100";
}


if (isset($_GET['pp'])) {
        $pp = $_GET['pp'];
} else {
        $pp = 1;
}

$item_per_page = 20;

$totalNum = $db->query($query_list)->RecordCount();
$maxPage   = ceil($totalNum / $item_per_page) - 1;
if ($maxPage < 0) $maxPage = 0;

$page   = ($pp <= $maxPage + 1 && $pp > 0) ? $pp - 1 : 0;
$__offset = $page * $item_per_page;

$rs         = $db->selectLimit($query_list, $__offset, $item_per_page);
$rows         = array();
if ($rs && $rs->RecordCount() > 0) {

        for ($__rowindex = 1 + $__offset; $row = $rs->FetchRow(); $__rowindex++) {
                $row["__no"] = $__rowindex;
                $rows[] = $row;
        }
        $rs->close();
}
$row_list = &$rows;
## navigator
#
//echo $maxPage;die;
if ($maxPage > 0) {
        $multipage = true;

        ## begin create nav
        $pages = array();
        for ($i = 0; $i <= $maxPage; $i++)
                $pages[] = array($i + 1, $i + 1);
        $nav['pages'] = $pages;

        if ($page > 0) {
                $nav['prev'] = array('label' => 'Prev', 'p' => $page - 1);
        } else {
                $nav['prev'] = false;
        }

        if ($page < $maxPage) {
                $nav['next'] = array('label' => 'Next', 'p' => $page + 1);
        } else {
                $nav['next'] = false;
        }
        ## end create nav

        $navlist = $nav['pages'];
        $navpage = $page + 1;

        if ($pp <= $maxPage) {
                $nextvisible         = true;
                $navnext                = $nav['next'];
        }
        if ($pp > 1) {
                $prevvisible        = true;
                $navprev                = $nav['prev'];
        }
}


$tl->assign("prevvisible", $prevvisible);
$tl->assign("navpage", $navpage);
$tl->assign("navlist", $navlist);
$tl->assign("nextvisible", $nextvisible);
$tl->assign("multipage", $multipage);
$tl->assign("row_list", $row_list);
$tl->assign("HOME", HOME);
$tl->assign("APPID", APPID);

$tl->renderToScreen();

function cek_nota($no_req)
{
        $db                 = getDB("storage");
        $query_cek        = "SELECT NOTA, KOREKSI FROM REQUEST_DELIVERY WHERE NO_REQUEST = '$no_req'";
        $result_cek        = $db->query($query_cek);
        $row_cek         = $result_cek->getAll();

        if (count($row_cek) > 0) {
                $nota                = $row_cek[0]["NOTA"];
                $koreksi        = $row_cek[0]["KOREKSI"];

                //'print/print_pdf?no_req='.$no_req.'&no_nota='.$no_nota.
                // <a href="{$HOME}{$APPID}/view?no_req={$rows.NO_REQUEST}"> LIHAT </a>
                if (($row_cek[0]["NOTA"] <> 'Y') and ($row_cek[0]["KOREKSI"] <> 'Y')) {
                        echo '<a href="' . HOME . APPID . '/print_nota?no_req=' . $no_req . '&koreksi=N" target="_blank"> Preview Proforma </a> ';
                        //echo '<a href="'.HOME.APPID.'/cetak_nota?no_nota='.$no_nota.'&n='.$cetak.'" target="_blank"> Cetak Proforma </a> ';		
                } else if (($row_cek[0]["NOTA"] == 'Y') and ($row_cek[0]["KOREKSI"] <> 'Y')) {
                        echo '<a href="' . HOME . APPID . '.print/print_proforma?no_req=' . $no_req . '" target="_blank" > Cetak Proforma </a> ';
                } else if (($row_cek[0]["NOTA"] == 'Y') and ($row_cek[0]["KOREKSI"] == 'Y')) {
                        echo '<a href="' . HOME . APPID . '.print/print_proforma?no_req=' . $no_req . '" target="_blank" > Cetak Proforma </a> ';
                } else if (($row_cek[0]["NOTA"] <> 'Y') and ($row_cek[0]["KOREKSI"] == 'Y')) {
                        echo '<a href="' . HOME . APPID . '/print_nota?no_req=' . $no_req . '&koreksi=Y"" target="_blank">Preview Proforma</a> ';
                }
        }
}
