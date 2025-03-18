<?php
$tgl = date('d F Y H:i');
$tanggal = date("dmY");
$no_ukk = $_GET['no_ukk'];
$kg = $_GET['keg'];

$db = getDB('dbint');
$query_p = "SELECT VESSEL NM_KAPAL,VOYAGE_IN, VOYAGE_OUT,OPERATOR_NAME NM_PEMILIK FROM m_vsb_voyage WHERE id_vsb_voyage='$no_ukk'";
$result_p = $db->query($query_p);
$rowe = $result_p->fetchRow();

$ves = $rowe[NM_KAPAL];
$vin = $rowe[VOYAGE_IN];
$vot = $rowe[VOYAGE_OUT];

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=List_" . $no_ukk . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<table>
            <tr>
                <th colspan="16">
                    LIST CONTAINER BY STATUS <?=$rowe['NM_KAPAL'] ?> <?=$rowe['VOYAGE_IN'] ?> - <?=$rowe['VOYAGE_OUT'] ?>
                </th>
            </tr>
            <tr>
                <th colspan="16"> 
                    <?=$rowe['NM_PEMILIK'] ?>
                </th>
            </tr>
        </table>
        <br/>

            <table border="1">
                <tr>
                    <th>No.</th>
                    <th>Container Numb</th>
                    <th colspan="4">Sz - Typ - Sts - Hz</th>
                    <th>Kode Status</th>
                    <th>Gate In - Out</th>
                    <th>Yard Allocation</th>
                    <th>Date Placement</th>
                    <th colspan="4">Yard Placement</th>
                    <th>Bay Plan</th>
                    <th>Date Loading - Disch. Confirm</th>
                    <th>Customer Name</th>
                    <th>NPE / SPPB</th>
                    <th>PEB</th>
                    <th>Weight</th>
                    <th>Weight NPE</th>
                </tr>
                
<?php

if($kg=='I')
        {
            $query ="SELECT a.NO_CONTAINER, TRIM(a.SIZE_CONT) AS SIZE_, TRIM(a.TYPE_CONT) AS TYPE_, TRIM(a.STATUS) AS STATUS, NVL(TRIM(a.HZ),'N') AS HZ, a.WEIGHT AS BERAT ,TRIM(a.POD) AS POD ,TRIM(a.POL) AS POL,
                    b.NO_REQUEST, b.NO_TRX as NO_NOTA, b.STATUS_PAID as STATUS_PAYMENT,
                    CASE
                        WHEN a.E_I='I' AND ACTIVITY=NULL THEN '01'
                        WHEN a.E_I='I' AND ACTIVITY='DISCHARGE' THEN '02'
                        WHEN a.E_I='I' AND ACTIVITY='PLACEMENT IMPORT' THEN '03'
                        WHEN a.E_I='I' AND ACTIVITY='GATE OUT DELIVERY' THEN '10'
                        WHEN a.E_I='I' AND ACTIVITY='GATE IN DELIVERY (TRUCK IN)' THEN '09'
                    END AS KODE_STATUS, 
                    TO_CHAR(TO_DATE(a.GATE_IN_DATE,'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:MI') TGL_GATE_IN,
                    TO_CHAR(TO_DATE(a.GATE_OUT_DATE,'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:MI') TGL_GATE_OUT,
                    TO_CHAR(TO_DATE(a.YARD_CONFIRM,'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:MI')  TGL_PLACEMENT, 
                    a.YD_BLOCK BLOCK,
                    a.YD_SLOT SLOT , 
                    a.YD_ROW ROW_,
                    a.YD_TIER TIER, 
                    a.BAYPLAN_POSITION AS BAY,
                    a.BAYPLAN_POSITION AS BAY_REAL, 
                    TO_CHAR(TO_DATE(a.vessel_CONFIRM,'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:MI') DATE_CONFIRM,

                    CUSTOMER_NAME, CUSTOM_NUMBER AS SPPB
                     FROM m_cyc_contaa left join m_billing b
                     on a.billing_request_id = b.no_request and a.vessel_code=b.vessel and a.voyage_in=b.voy_in and iner A.voyage_out=b.voy_out and a.no_container=b.no_container
                    WHERE a.vessel='$ves' 
                    and a.voyage_in='$vin' and a.voyage_out= '$vot'
                    and a.E_I='$kg'
                    ORDER BY KODE_STATUS DESC";
        }
        else
        {
            $db=getDb();
            $query="SELECT a.NO_CONTAINER,
                         TRIM (a.SIZE_CONT) AS SIZE_,
                         TRIM (a.TYPE_CONT) AS TYPE_,
                         TRIM (a.STATUS) AS STATUS,
                         NVL (TRIM (a.HZ), 'N') AS HZ,
                         a.WEIGHT AS BERAT,
                         TRIM (a.POD) AS POD,
                         TRIM (a.POL) AS POL,
                         a.BILLING_REQUEST_ID AS NO_REQUEST,
                         CASE
                            WHEN a.E_I = 'E' AND a.ACTIVITY = 'INSPECTION RECEIVING' THEN '50'
                            WHEN a.E_I = 'E' AND a.ACTIVITY = 'PLACEMENT EXPORT' THEN '51'
                            WHEN a.E_I = 'E' AND a.ACTIVITY = 'GATE IN RECEIVING' THEN '50'
                            WHEN a.E_I = 'E' AND a.ACTIVITY = 'GATE OUT RECEIVING' AND CONT_LOCATION='Yard' THEN '51'
                            WHEN a.E_I = 'E' AND a.ACTIVITY = 'GATE OUT RECEIVING' AND CONT_LOCATION='Vessel' THEN '56'
                            WHEN a.E_I = 'E' AND a.ACTIVITY = 'HAULAGE EXPORT' THEN '51'
                            WHEN a.E_I = 'E' AND a.ACTIVITY = 'LOADING' THEN '56'
                            WHEN a.E_I = 'E' AND a.ACTIVITY IS NULL THEN '49'
                         END
                            AS KODE_STATUS,
                         TO_CHAR (TO_DATE (a.GATE_IN_DATE, 'YYYYMMDDHH24MISS'),
                                  'DD-MM-YYYY HH24:MI')
                            TGL_GATE_IN,
                         TO_CHAR (TO_DATE (a.GATE_OUT_DATE, 'YYYYMMDDHH24MISS'),
                                  'DD-MM-YYYY HH24:MI')
                            TGL_GATE_OUT,
                         TO_CHAR (TO_DATE (a.YARD_CONFIRM, 'YYYYMMDDHH24MISS'),
                                  'DD-MM-YYYY HH24:MI')
                            TGL_PLACEMENT,
                         a.YD_BLOCK BLOCK,
                         a.YD_SLOT SLOT,
                         a.YD_ROW ROW_,
                         a.YD_TIER TIER,
                         a.BAYPLAN_POSITION AS BAY,
                         a.STW_POSITION AS BAY_REAL,
                         TO_CHAR (TO_DATE (a.vessel_CONFIRM, 'YYYYMMDDHH24MISS'),
                                  'DD-MM-YYYY HH24:MI')
                            DATE_CONFIRM,
                         b.NPE,
                         b.PEB,
                         b.KODE_PBM,
                         c.WEIGHT_NPE
                    FROM    m_cyc_container@dbint_link a
                         LEFT JOIN
                            req_receiving_h b
                         ON (a.billing_request_id = b.id_req)
                         LEFT JOIN
                            req_receiving_d c
                         ON (a.billing_request_id = c.no_req_anne and a.no_container = c.no_container)
                   WHERE     a.vessel = '$ves'
                         AND a.voyage_in = '$vin'
                         AND a.voyage_out = '$vot'
                         AND E_I = '$kg'
                ORDER BY KODE_STATUS ASC";
        }

$result_h = $db->query($query);

$res = $result_h->getAll();
//echo $query;die;
$i = 1;

?>
<?php foreach ($res as $row) {
    $db=getDb();
    $req=$row['NO_REQUEST'];
    if($kg=='I'){
     
        $qrreq="select EMKL, NO_SPPB from req_delivery_h where id_req='$req'";
        $qrex=$db->query($qrreq);
        $rts=$qrex->fetchRow();

    }    

    ?>
                    <tr>

                        <td><?=$i ?></td>
                        <td><?=$row['NO_CONTAINER'] ?></td>
                        <td><?=$row['SIZE_'] ?></td>
                        <td><?=$row['TYPE_'] ?></td>
                        <td><?=$row['STATUS'] ?></td>
                        <td><?=$row['HZ'] ?></td>
                        <td><?=$row['KODE_STATUS'] ?></td>
                        <td><?=$row['TGL_GATE_IN'] ?></td>
                        <td><?=$row['LOKASI'] ?></td>
                        <td><?=$row['TGL_PLACEMENT'] ?></td>
                        <td><?=$row['BLOCK'] ?></td>
                        <td><?=$row['SLOT'] ?></td>
                        <td><?=$row['ROW_'] ?></td>
                        <td><?=$row['TIER'] ?></td>
                        <td><?=$row['BAY'] ?></td>
                        <td><?=$row['DATE_CONFIRM'] ?></td>
                        <td><?=$rts['EMKL'] ?><?=$row['KODE_PBM'] ?></td>
                        <td><?=$rts['NO_SPPB'] ?><?=$row['NPE'] ?></td>
                        <td><?=$row['PEB'] ?></td>
                         <td><?=$row['BERAT'] ?></td>
                         <td><?=$row['WEIGHT_NPE'] ?></td>
                    </tr>
    <?php
    $i++;
}
?>
            </table>