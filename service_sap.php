<?php

include "framework/conf.php";
include "framework/_debug.php";
include "framework/_viewstate.php";
include "framework/init.php";


//echo R_PATH;die();
ini_set("display_errors", "0");

include('lib/xml2array.php');
require_once('lib/nusoap/nusoap.php');

$server = new soap_server();
$server->configureWSDL('ApiNbs', 'urn:ApiNbs');


$server->register(
    'getPaymentCode',
    array(),
    array('return' => 'xsd:string'),
    'urn:getPaymentCode',
    'urn:getPaymentCode#getPaymentCode',
    'rpc',
    'encoded',
    'Get Payment Code'
);

$server->register(
    'GetStatusPayment',
    array(),
    array('return' => 'xsd:string'),
    'urn:GetStatusPayment',
    'urn:GetStatusPayment#GetStatusPayment',
    'rpc',
    'encoded',
    'Get Status Payment'
);


function getPaymentCode()
{
    $db = getDB('storage');
    $service = 'getPaymentCode';
    $query = $db->query("
            SELECT
                *
        FROM
                (
            SELECT
                    NO_NOTA,
                    TGL_NOTA_1,
                    PAYMENT_CODE,
                    'RECEIVING' KEGIATAN
            FROM
                    nota_receiving
            WHERE
                    STATUS <> 'BATAL'
        UNION
            SELECT
                    NO_NOTA,
                    TGL_NOTA_1,
                    PAYMENT_CODE,
                    CASE
                        WHEN STATUS = 'PERP' THEN 'PERP_PNK'
                        ELSE 'STUFFING'
			        END KEGIATAN
            FROM
                    nota_stuffing
            WHERE
                    STATUS <> 'BATAL'
        UNION
            SELECT
                    NO_NOTA,
                    TGL_NOTA_1,
                    PAYMENT_CODE,
                    CASE
                        WHEN SUBSTR(NO_NOTA, 0, 2) = '03' THEN 'STRIPPING'
                        ELSE 'PERP_STRIP'
			        END KEGIATAN
            FROM
                    nota_stripping
            WHERE
                    STATUS <> 'BATAL'
        UNION
            SELECT
                    NO_NOTA,
                    TGL_NOTA_1,
                    PAYMENT_CODE,
                    CASE
                        WHEN STATUS = 'PERP' THEN 'PERP_DEV'
                        ELSE 'DELIVERY'
			        END KEGIATAN
            FROM
                    nota_delivery
            WHERE
                    STATUS <> 'BATAL'
        UNION
            SELECT
                    NO_NOTA,
                    TGL_NOTA_1,
                    PAYMENT_CODE,
                    'RELOKASI' KEGIATAN
            FROM
                    nota_relokasi
            WHERE
                    STATUS <> 'BATAL'
        UNION
            SELECT
                    NO_NOTA,
                    TGL_NOTA,
                    PAYMENT_CODE,
                    'BATAL_MUAT' KEGIATAN
            FROM
                    nota_batal_muat
            WHERE
                    STATUS <> 'BATAL'
        UNION
            SELECT
                    NO_NOTA,
                    TGL_NOTA_1,
                    PAYMENT_CODE,
                    'RELOK_MTY' KEGIATAN
            FROM
                    nota_relokasi_mty
            WHERE
                    STATUS <> 'BATAL'
        UNION
            SELECT
                    NO_NOTA,
                    TGL_NOTA_1,
                    PAYMENT_CODE,
                    'DEL_PNK' KEGIATAN
            FROM
                    nota_pnkn_del
            WHERE
                    STATUS <> 'BATAL'
        UNION
            SELECT
                    NO_NOTA,
                    TGL_NOTA_1,
                    PAYMENT_CODE,
                    'STUF_PNK' KEGIATAN
            FROM
                    nota_pnkn_stuf
            WHERE
                    STATUS <> 'BATAL')
        WHERE
            NO_NOTA IS NOT NULL
            AND PAYMENT_CODE IS NULL
            AND TGL_NOTA_1 > TO_DATE('2024-01-03', 'YYYY-MM-DD')
        ORDER BY
            DBMS_RANDOM.VALUE
        FETCH NEXT 1 ROWS ONLY");
    $result = $query->fetchRow();
    //Check Apakah Masih Ada PaymentCode Belum ada
    if ($result) {
        $noNota = $result['NO_NOTA'];
        $query = $db->query("select * from mti_customer_ss.sap_uper_cash_nbs_v@SAP_SERVICE WHERE NO_NOTA = '$noNota' ");
        $resultSAP = $query->fetchRow();

        //Check Apakah Payment Code Sudah Ada Di SAP
        if ($resultSAP) {
            $paymentCode = $resultSAP['PAYMENT_CODE'];
            if ($result['KEGIATAN'] == 'RECEIVING') {
                $tb_name = "NOTA_RECEIVING";
            } else if ($result['KEGIATAN'] == 'STUFFING' || $result['KEGIATAN'] == 'PERP' || $result['KEGIATAN'] == 'PERP_PNK') {
                $tb_name = "NOTA_STUFFING";
            } else if ($result['KEGIATAN'] == 'STRIPPING' || $result['KEGIATAN'] == 'PERP_STRIP') {
                $tb_name = "NOTA_STRIPPING";
            } else if ($result['KEGIATAN'] == 'DELIVERY' || $result['KEGIATAN'] == 'PERP_DEV' || $result['KEGIATAN'] == 'PERP') {
                $tb_name = "NOTA_DELIVERY";
            } else if ($result['KEGIATAN'] == 'RELOKASI') {
                $tb_name = "NOTA_RELOKASI";
            } else if ($result['KEGIATAN'] == 'BATAL_MUAT') {
                $tb_name = "NOTA_BATAL_MUAT";
            } else if ($result['KEGIATAN'] == 'RELOK_MTY') {
                $tb_name = "NOTA_RELOKASI_MTY";
            } else if ($result['KEGIATAN'] == 'DEL_PNK') {
                $tb_name = "NOTA_PNKN_DEL";
            } else if ($result['KEGIATAN'] == 'STUF_PNK') {
                $tb_name = "NOTA_PNKN_STUF";
            }
            $query = $db->query("update $tb_name set PAYMENT_CODE='$paymentCode' WHERE NO_NOTA = '$noNota' ");
            if ($query) {
                $msg = array(
                    'code' => "1",
                    'msg' => "Inserted Payment Code $noNota"
                );
                $msg_json = json_encode($msg);
                $query = $db->query("INSERT INTO USTER.SAP_SERVICE_LOG
                (URL, PAYLOAD, RESPONSE, NOTES)
                VALUES('$service',NULL, '$msg_json', 'Inserted Payment Code $noNota')");

                return "Inserted Payment Code $noNota";
            } else {
                $msg = array(
                    'code' => "0",
                    'msg' => "Failed Inserted Payment Code $noNota"
                );
                $msg_json = json_encode($msg);
                $query = $db->query("INSERT INTO USTER.SAP_SERVICE_LOG
                (URL, PAYLOAD, RESPONSE, NOTES)
                VALUES('$service',NULL, '$msg_json', 'Failed Inserted Payment Code $noNota')");
                return "Failed Inserted Payment Code $noNota";
            }
        } else {
            $msg = array(
                'code' => "0",
                'msg' => "Payment Code Not Found $noNota"
            );
            $msg_json = json_encode($msg);
            $query = $db->query("INSERT INTO USTER.SAP_SERVICE_LOG
            (URL, PAYLOAD, RESPONSE, NOTES)
            VALUES('$service',NULL, '$msg_json', 'Payment Code Not Found $noNota')");
            return "Payment Code Not Found $noNota";
        }
    } else {
        return "All Payment Codes Are Available";
    }
}

function GetStatusPayment()
{
    $db = getDB('storage');
    $service = 'GetStatusPayment';
    $query = $db->query("
            SELECT
            *
        FROM
            (
            SELECT
                NO_NOTA,
                TGL_NOTA_1,
                PAYMENT_CODE,
                'RECEIVING' KEGIATAN,
                TANGGAL_LUNAS,
                LUNAS
            FROM
                nota_receiving
            WHERE
                STATUS <> 'BATAL'
        UNION
            SELECT
                NO_NOTA,
                TGL_NOTA_1,
                PAYMENT_CODE,
                CASE
                    WHEN STATUS = 'PERP' THEN 'PERP_PNK'
                    ELSE 'STUFFING'
                END KEGIATAN,
                TANGGAL_LUNAS,
                LUNAS
            FROM
                nota_stuffing
            WHERE
                STATUS <> 'BATAL'
        UNION
            SELECT
                NO_NOTA,
                TGL_NOTA_1,
                PAYMENT_CODE,
                CASE
                    WHEN SUBSTR(NO_NOTA, 0, 2) = '03' THEN 'STRIPPING'
                    ELSE 'PERP_STRIP'
                END KEGIATAN,
                TANGGAL_LUNAS,
                LUNAS
            FROM
                nota_stripping
            WHERE
                STATUS <> 'BATAL'
        UNION
            SELECT
                NO_NOTA,
                TGL_NOTA_1,
                PAYMENT_CODE,
                CASE
                    WHEN STATUS = 'PERP' THEN 'PERP_DEV'
                    ELSE 'DELIVERY'
                END KEGIATAN,
                TANGGAL_LUNAS,
                LUNAS
            FROM
                nota_delivery
            WHERE
                STATUS <> 'BATAL'
        UNION
            SELECT
                NO_NOTA,
                TGL_NOTA_1,
                PAYMENT_CODE,
                'RELOKASI' KEGIATAN,
                TANGGAL_LUNAS,
                LUNAS
            FROM
                nota_relokasi
            WHERE
                STATUS <> 'BATAL'
        UNION
            SELECT
                NO_NOTA,
                TGL_NOTA,
                PAYMENT_CODE,
                'BATAL_MUAT' KEGIATAN,
                TGL_LUNAS,
                LUNAS
            FROM
                nota_batal_muat
            WHERE
                STATUS <> 'BATAL'
        UNION
            SELECT
                NO_NOTA,
                TGL_NOTA_1,
                PAYMENT_CODE,
                'RELOK_MTY' KEGIATAN,
                TANGGAL_LUNAS,
                LUNAS
            FROM
                nota_relokasi_mty
            WHERE
                STATUS <> 'BATAL'
        UNION
            SELECT
                NO_NOTA,
                TGL_NOTA_1,
                PAYMENT_CODE,
                'DEL_PNK' KEGIATAN,
                TANGGAL_LUNAS,
                LUNAS
            FROM
                nota_pnkn_del
            WHERE
                STATUS <> 'BATAL'
        UNION
            SELECT
                NO_NOTA,
                TGL_NOTA_1,
                PAYMENT_CODE,
                'STUF_PNK' KEGIATAN,
                TANGGAL_LUNAS,
                LUNAS
            FROM
                nota_pnkn_stuf
            WHERE
                STATUS <> 'BATAL')
        WHERE
            NO_NOTA IS NOT NULL
            AND PAYMENT_CODE IS NOT NULL
            AND TANGGAL_LUNAS IS NULL
            AND LUNAS = 'NO'
        ORDER BY
            DBMS_RANDOM.VALUE
                FETCH NEXT 1 ROWS ONLY");
    $result = $query->fetchRow();
    //Check Apakah Masih Ada PaymentCode Belum ada
    if ($result) {
        $noNota = $result['NO_NOTA'];
        $query = $db->query("
        SELECT
            *
        FROM
            mti_customer_ss.sap_uper_cash_nbs_v@SAP_SERVICE
        WHERE
            PAYMENT_CODE IS NOT NULL
            AND SAP_TGL_PELUNASAN IS NOT NULL
            AND SAP_BANK IS NOT NULL
            AND NO_NOTA = '$noNota'");
        $resultSAP = $query->fetchRow();

        //Check Apakah Payment Code Sudah Ada Di SAP
        if ($resultSAP) {
            $paymentCode = $resultSAP['PAYMENT_CODE'];
            if ($result['KEGIATAN'] == 'RECEIVING') {
                $tb_name = "NOTA_RECEIVING";
            } else if ($result['KEGIATAN'] == 'STUFFING' || $result['KEGIATAN'] == 'PERP' || $result['KEGIATAN'] == 'PERP_PNK') {
                $tb_name = "NOTA_STUFFING";
            } else if ($result['KEGIATAN'] == 'STRIPPING' || $result['KEGIATAN'] == 'PERP_STRIP') {
                $tb_name = "NOTA_STRIPPING";
            } else if ($result['KEGIATAN'] == 'DELIVERY' || $result['KEGIATAN'] == 'PERP_DEV' || $result['KEGIATAN'] == 'PERP') {
                $tb_name = "NOTA_DELIVERY";
            } else if ($result['KEGIATAN'] == 'RELOKASI') {
                $tb_name = "NOTA_RELOKASI";
            } else if ($result['KEGIATAN'] == 'BATAL_MUAT') {
                $tb_name = "NOTA_BATAL_MUAT";
            } else if ($result['KEGIATAN'] == 'RELOK_MTY') {
                $tb_name = "NOTA_RELOKASI_MTY";
            } else if ($result['KEGIATAN'] == 'DEL_PNK') {
                $tb_name = "NOTA_PNKN_DEL";
            } else if ($result['KEGIATAN'] == 'STUF_PNK') {
                $tb_name = "NOTA_PNKN_STUF";
            }

            $query = $db->query("update $tb_name set LUNAS='YES',TANGGAL_LUNAS=SYSDATE WHERE NO_NOTA = '$noNota' ");

            $query = true;
            if ($query) {
                $msg = array(
                    'code' => "1",
                    'msg' => "Status Payment Changed to Paid $noNota"
                );
                $msg_json = json_encode($msg);
                $query = $db->query("INSERT INTO USTER.SAP_SERVICE_LOG
                (URL, PAYLOAD, RESPONSE, NOTES)
                VALUES('$service',NULL, '$msg_json', 'Status Payment Changed to Paid $noNota')");

                return "Status Payment Changed to Paid $noNota";
            } else {
                $msg = array(
                    'code' => "0",
                    'msg' => "Failed Status Payment Changed to Paid $noNota"
                );
                $msg_json = json_encode($msg);
                $query = $db->query("INSERT INTO USTER.SAP_SERVICE_LOG
                (URL, PAYLOAD, RESPONSE, NOTES)
                VALUES('$service',NULL, '$msg_json', 'Failed Status Payment Changed to Paid $noNota')");
                return "Failed Status Payment Changed to Paid $noNota";
            }
        } else {
            $msg = array(
                'code' => "0",
                'msg' => "No Nota Unpaid $noNota"
            );
            $msg_json = json_encode($msg);
            $query = $db->query("INSERT INTO USTER.SAP_SERVICE_LOG
            (URL, PAYLOAD, RESPONSE, NOTES)
            VALUES('$service',NULL, '$msg_json', 'No Nota Unpaid $noNota')");
            return "No Nota Unpaid $noNota";
        }
    } else {
        return "No New Payments Yet";
    }
}

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
