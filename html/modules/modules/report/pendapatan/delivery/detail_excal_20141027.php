<?php
    $vessel = $_GET['vessel'];
    $voyage = $_GET['voyage'];
    $voyage_out = $_GET['voyage_out'];
    $vessel_code = $_GET['vessel_code'];
    $voyage_in = $_GET['voyage_in'];


header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=report_tdr.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<html>
    <title>TDR</title>
    <head>              
    </head>

    <?php  
    $db = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=192.168.29.85)(Port=1521)))(CONNECT_DATA=(SERVER=dedicated)(SERVICE_NAME=TOSDB)))";
    $conn = ocilogon("OPUS_REPO", "opus_repo", "$db") or die("can't connect to server");

    $query_berth = "SELECT
                            VESSEL, 
                            POD,VOYAGE_OUT, 
                            OPERATOR_ID, 
                            to_char(to_date(BERTHING_TIME, 'yyyymmddhh24miss'), 'dd-mm-yyyy') as TGL_BERTHING, 
                            to_char(to_date(BERTHING_TIME, 'yyyymmddhh24miss'), 'hh:mi') as JAM_BERTHING, 
                            to_char(to_date(START_WORK, 'yyyymmddhh24miss'), 'dd-mm-yyyy') as TGL_START_WORK, 
                            to_char(to_date(BERTHING_TIME, 'yyyymmddhh24miss'), 'hh:mi') as JAM_START_WORK, 
                            to_char(to_date(ATD, 'yyyymmddhh24miss'), 'dd-mm-yyyy') as TGL_ATD, 
                            to_char(to_date(BERTHING_TIME, 'yyyymmddhh24miss'), 'hh:mi') as JAM_ATD, 
                            to_char(to_date(END_WORK, 'yyyymmddhh24miss'), 'dd-mm-yyyy') as TGL_END_WORK, 
                            to_char(to_date(BERTHING_TIME, 'yyyymmddhh24miss'), 'hh:mi') as JAM_END_WORK, 
                            BERTH   from m_vsb_voyage 
                    WHERE 
                            vessel = '$vessel'
                            AND voyage = '$voyage'";

//echo $query_berth;
    $query_berth = OCIparse($conn, $query_berth);
    ociexecute($query_berth);
    $row_berth = oci_fetch_array($query_berth, OCI_ASSOC);
    ?>

    <div id="container" style="margin:50 0 50 200; width: 1000px">
        <table border="1" class="table_alumni">
            <h1>Terminal Departure Report</h1>

            <tr>
                <th width="250">Port</th>
                <td class="td-tengah" rowspan="2">Terminal Departure Report</td>
                <th class="td-tengah">Terminal</th>        
            </tr>

            <tr>
                <td class="td-tengah">Jakarta</td>        
                <td class="td-tengah">TO#3 Tanjung Priok</td> 
                
            </tr>
        </table>
        <p>&nbsp;</p>


        <table border="1" class="table_alumni">
            <tr>
                <td width="50" height="24">Vessel:</td>
                <td width="194"><?php echo $row_berth['VESSEL']; ?></td>
                <td width="93">Service:</td>
                <td width="190">CISX</td>
            </tr>
            <tr>
                <td>Voyage:</td>
                <td><?php echo $row_berth['VOYAGE_OUT']; ?></td>
                <td>Berth:</td>
                <td><?php echo $row_berth['BERTH']; ?></td>
            </tr>
        </table>

        <p>&nbsp;</p>
        <table class="table_alumni" border="1"><colgroup><col width="74" /> <col width="134" /> <col width="151" /> <col width="87" /> <col span="3" width="86" /> <col width="74" /> <col width="69" /> </colgroup>
            <tbody>
                <tr>
                    <td width="82" height="17" class="xl66">Options</td>
                    <td colspan="2" class="xl71">Arrival</td>
                    <td width="106" class="xl67">dd-mm-yyyy</td>
                    <td width="60" class="xl67">hh : mm</td>
                    <td colspan="2" class="xl72">Departure</td>
                    <td width="71" class="xl67">dd-mm-yyyy</td>
                    <td width="208" class="xl67">mm : hh</td>
                </tr>
                <tr>
                    <td width="82" height="74" rowspan="4" class="xl73">Vessel Operation Time</td>
                    <td colspan="2" class="xl74">Arrival Pilot Station</td>
                    <td class="xl68">&nbsp;</td>
                    <td class="xl68">&nbsp;</td>
                    <td colspan="2" class="xl76">&nbsp;</td>
                    <td class="xl68">&nbsp;</td>
                    <td class="xl68">&nbsp;</td>
                </tr>
                <tr>
                    <td height="17" colspan="2" class="xl77">Pilot On Board</td>
                    <td class="xl68">&nbsp;</td>
                    <td class="xl68">&nbsp;</td>
                    <td colspan="2" class="xl79">Pilot On Board</td>
                    <td class="xl68">&nbsp;</td>
                    <td class="xl68">&nbsp;</td>
                </tr>
                <tr>
                    <td height="20" colspan="2" class="xl77">Berthed</td>
                    <td width="106" class="xl69"><?php echo $row_berth['TGL_BERTHING']; ?></td>
                    <td width="60" class="xl69"><?php echo $row_berth['JAM_BERTHING']; ?></td>
                    <td colspan="2" class="xl79">Sailed</td>
                    <td width="71" class="xl69"><?php echo $row_berth['TGL_ATD']; ?></td>
                    <td width="208" class="xl69"><?php echo $row_berth['JAM_ATD']; ?></td>
                </tr>
                <tr>
                    <td height="20" colspan="2" class="xl78">Commenced Work</td>
                    <td width="106" class="xl69"><?php echo $row_berth['TGL_START_WORK']; ?></td>
                    <td width="60" class="xl69"><?php echo $row_berth['JAM_START_WORK']; ?></td>
                    <td colspan="2" class="xl79">Completed Work</td>
                    <td width="71" class="xl69"><?php echo $row_berth['TGL_END_WORK']; ?></td>
                    <td width="208" class="xl69"><?php echo $row_berth['JAM_END_WORK']; ?></td>
                </tr>
                <tr>
                    <td height="34" rowspan="2" class="xl81">Draft</td>
                    <td colspan="2" class="xl75">Foreward</td>
                    <td class="xl68">&nbsp;</td>
                    <td class="xl68">&nbsp;</td>
                    <td colspan="2" class="xl75">Foreward</td>
                    <td class="xl68">&nbsp;</td>
                    <td class="xl68">&nbsp;</td>
                </tr>
                <tr>
                    <td height="17" colspan="2" class="xl76">Afterward</td>
                    <td class="xl68">&nbsp;</td>
                    <td class="xl68">&nbsp;</td>
                    <td colspan="2" class="xl76">Afterward</td>
                    <td class="xl68">&nbsp;</td>
                    <td class="xl68">&nbsp;</td>
                </tr>
                <tr>
                    <td height="34" rowspan="2" class="xl81">Bunker Detail</td>
                    <td colspan="2" class="xl75">Heavy (FO)</td>
                    <td class="xl68">&nbsp;</td>
                    <td class="xl68">&nbsp;</td>
                    <td colspan="2" class="xl75">Heavy (FO)</td>
                    <td class="xl68">&nbsp;</td>
                    <td class="xl68">&nbsp;</td>
                </tr>
                <tr>
                    <td height="17" colspan="2" class="xl75">Marine (DO)</td>
                    <td class="xl68">&nbsp;</td>
                    <td class="xl68">&nbsp;</td>
                    <td colspan="2" class="xl75">Marine (DO)</td>
                    <td class="xl68">&nbsp;</td>
                    <td class="xl68">&nbsp;</td>
                </tr>
                <tr>
                    <td height="34" rowspan="2" class="xl81">Others</td>
                    <td colspan="2" class="xl75">Tug Used</td>
                    <td class="xl68">&nbsp;</td>
                    <td class="xl68">&nbsp;</td>
                    <td colspan="2" class="xl75">Tug Used</td>
                    <td class="xl68">&nbsp;</td>
                    <td class="xl68">&nbsp;</td>
                </tr>
                <tr>
                    <td height="17" colspan="4" class="xl74">Next ETA</td>
                    <td colspan="2" class="xl83"><?php echo $row_berth['POD']; ?></td>
                    <td class="xl68">&nbsp;</td>
                    <td class="xl68">&nbsp;</td>
                </tr>
            </tbody>
        </table>
        <p>&nbsp;</p>

        <?php
        $query_alat = " SELECT 
                                VSP_SHP_ACTU_GCNO as TERMINAL_CRANE,
                                TO_DATE(MIN (VSP_SHP_COMPDATE || VSP_SHP_COMPTIME), 'yyyymmddhh24miss') AS START_WORK,
                                to_char(TO_DATE(MIN(VSP_SHP_COMPDATE||VSP_SHP_COMPTIME), 'yyyymmddhh24miss'), 'dd-mm-yyyy  hh:mi:ss') as START_WORK1, 
                                TO_DATE(MAX (VSP_SHP_COMPDATE || VSP_SHP_COMPTIME), 'yyyymmddhh24miss') AS END_WORK,
                                to_char(TO_DATE(MAX(VSP_SHP_COMPDATE||VSP_SHP_COMPTIME), 'yyyymmddhh24miss'), 'dd-mm-yyyy  hh:mi:ss') as END_WORK1,
                                (TO_DATE(MAX (VSP_SHP_COMPDATE || VSP_SHP_COMPTIME), 'yyyymmddhh24miss')  - TO_DATE(MIN (VSP_SHP_COMPDATE || VSP_SHP_COMPTIME), 'yyyymmddhh24miss'))*24 AS CRANE_HOUR                         
                        FROM 
                                PNOADM.VSP_SHIP                                                
                        WHERE     
                                vsp_shp_vessel = '$vessel_code'                                                
                                AND VSP_SHP_VOYAGE = '$voyage'
                                AND VSP_SHP_ACTU_GCNO IS NOT NULL
                                GROUP BY VSP_SHP_ACTU_GCNO
                                ORDER BY VSP_SHP_ACTU_GCNO ASC";

        $query_alat = OCIparse($conn, $query_alat);
        ociexecute($query_alat);

        $query_alat2 = "SELECT 
                                MCH_STOP_MACHNO, 
                                SUM(SELISIH) AS IDLE_TIME, 
                                MIN(START_TIME1) AS START_TIME_QC, 
                                MAX(END_TIME1) AS END_TIME_QC  
                        FROM  
                               (SELECT 
                                        MCH_STOP_MACHNO,
                                        MCH_STOP_STARTDATE || MCH_STOP_STARTTIME AS START_TIME,
                                        to_date(MCH_STOP_STARTDATE || MCH_STOP_STARTTIME, 'yyyymmddhh24miss') AS START_TIME1,       
                                        MCH_STOP_ENDDATE || MCH_STOP_ENDTIME AS END_TIME,
                                        to_date(MCH_STOP_ENDDATE || MCH_STOP_ENDTIME, 'yyyymmddhh24miss') AS END_TIME1, 
                                        (to_date(MCH_STOP_ENDDATE || MCH_STOP_ENDTIME, 'yyyymmddhh24miss')  - to_date(MCH_STOP_STARTDATE || MCH_STOP_STARTTIME, 'yyyymmddhh24miss'))*24 AS SELISIH  
                                FROM 
                                        PNOADM.MCH_WORKSTOP
                        WHERE 
                                MCH_STOP_VESSEL = '$vessel_code'
                                AND MCH_STOP_VOYAGE= '$voyage' )                                   
                        GROUP BY 
                                MCH_STOP_MACHNO
                                ORDER BY MCH_STOP_MACHNO ASC";

        $query_alat2 = OCIparse($conn, $query_alat2);
        ociexecute($query_alat2);


        $query_alat3 = "SELECT 
                                CRANE_ID, 
                                COUNT(NO_CONTAINER) AS TTL_BOXES 
                        FROM 
                                M_CYC_CONTAINER WHERE VESSEL_CODE = '$vessel_code' 
                                AND VOYAGE = '$voyage'
                                AND CRANE_ID IS NOT NULL 
                        GROUP BY 
                                CRANE_ID
                        ORDER BY 
                                CRANE_ID ASC";
        $query_alat3 = OCIparse($conn, $query_alat3);
        ociexecute($query_alat3);

        $query_alat4 = "SELECT 
                                DISTINCT(CRANE_ID), 
                                (SELECT 
                                        sum(jumlah) 
                                 FROM 
                                        m_hatch_move b 
                                 WHERE 
                                        a.vessel = b.vessel 
                                        and b.voyage_in=a.voyage_in 
                                        and b.voyage_out=a.voyage_out 
                                        and a.crane_id=b.alat) 
                                 AS HATCH_COVER 
                        FROM 
                                 M_CYC_CONTAINER a  
                        WHERE  
                                 vessel = '$vessel'      
                                 AND voyage_out = '$voyage_out'
                                 AND voyage_in = '$voyage_in'
                                 AND crane_id is not null 
                        ORDER BY 
                                crane_id ASC";
        //echo $query_alat4;
        $query_alat4 = OCIparse($conn, $query_alat4);
        ociexecute($query_alat4);


        $query_alat5 = "SELECT 
                                COUNT(1) as TTL_MOVE, 
                                alat_shift 
                        FROM 
                                m_shifting where vessel = '$vessel' 
                                and voyage_in='$voyage_in' 
                        group by 
                                alat_shift
                        ORDER BY 
                                ALAT_SHIFT ASC";
        $query_alat5 = OCIparse($conn, $query_alat5);
        ociexecute($query_alat5);
        
        
        $query_gcr = "SELECT ALAT, SUM (QTY) AS QTY
    FROM (  SELECT alat, COUNT (*) QTY
              FROM m_stevedoring
             WHERE vessel = '$vessel' AND voyage_in = '$voyage_in'
          GROUP BY alat
          UNION ALL
          SELECT DISTINCT
                 (crane_id) ALAT,
                 (SELECT COUNT (*)
                    FROM m_shifting b
                   WHERE     a.vessel = b.vessel
                         AND b.voyage_in = a.voyage_in
                         AND b.voyage_out = a.voyage_out
                         AND a.crane_id = b.alat_shift)
                    QTY
            FROM m_cyc_container a
           WHERE     vessel = '$vessel'
                 AND voyage_out = '$voyage_out'
                 AND voyage_in = '$voyage_in'
                 AND crane_id IS NOT NULL
          UNION ALL
          SELECT DISTINCT
                 (crane_id) ALAT,
                 (SELECT SUM (jumlah)
                    FROM m_hatch_move b
                   WHERE     a.vessel = b.vessel
                         AND a.voyage_in = b.voyage_in
                         AND a.voyage_out = b.voyage_out
                         AND a.crane_id = b.alat)
                    QTY
            FROM m_cyc_container a
           WHERE     vessel = '$vessel'
                 AND voyage_out = '$voyage_out'
                 AND voyage_in = '$voyage_in'
                 AND crane_id IS NOT NULL)
GROUP BY ALAT
ORDER BY ALAT ASC ";
        $query_gcr = OCIparse($conn, $query_gcr);
        ociexecute($query_gcr);  
        
        ?>


        <table class="table_alumni" border="1">
            <colgroup><col width="74" /> <col width="134" /> <col width="151" /> <col width="87" /> <col span="3" width="86" /> <col width="74" /> <col width="69" /> <col width="67" /> </colgroup>
            <tbody>
                <tr>
                    <th>TERMINAL CRANES</th>
                    <th>COMMENCED DISCH</th>
                    <th>COMPLETED LOADING&nbsp;</th>
                    <th>CRANE HOURS</th>
                    <th>IDLE TIME</th>
                    <th>GROSS CRANE HOURS</th>
                    <th>TTL BOXES</th>
                    <th>TTL HATCH COVER</th>
                    <th>TTL MOVES</th>
                    <th>GCR (MPH)</th>
                </tr>

                <?php
                while ($row_alat = oci_fetch_array($query_alat, OCI_ASSOC)
                ) {

                    $row_alat2 = oci_fetch_array($query_alat2, OCI_ASSOC);
                    $row_alat3 = oci_fetch_array($query_alat3, OCI_ASSOC);
                    $row_alat4 = oci_fetch_array($query_alat4, OCI_ASSOC);
                    //print_r($row_alat4);
                    //echo "ivan ganteng";
                    $row_alat5 = oci_fetch_array($query_alat5, OCI_ASSOC);
                    $row_gcr = oci_fetch_array($query_gcr, OCI_ASSOC)
                    ?>
                    <tr>
                        <td class="td-tengah"><?php echo $row_alat['TERMINAL_CRANE']; ?></td>
                        <td class="td-kiri"><?php echo $row_alat['START_WORK1']; ?></td>
                        <td class="td-kiri"><?php echo $row_alat['END_WORK1']; ?></td>
                        <td class="td-kiri"><?php echo round($row_alat['CRANE_HOUR'], 2); ?></td>

                        <td class="td-kiri"><?php echo round($row_alat2['IDLE_TIME'], 2); ?></td>
                        <td class="td-tengah"><?php echo round($row_alat['CRANE_HOUR'] - round($row_alat2['IDLE_TIME'], 2), 2); ?></td>
                        <td class="td-tengah"><?php echo $row_alat3['TTL_BOXES']; ?></td>
                        <?php if ($row_alat4['HATCH_COVER']==""){ ?>
                        <td class="td-tengah"><?php echo 0 ; ?></td>
                        <?php } else{?>
                        
                        <td class="td-tengah"><?php echo $row_alat4['HATCH_COVER']; ?></td>
                        <?php } ?>

                        <?php if ($row_alat5['TTL_MOVE'] == "") { ?>

                        <td class="td-tengah"><?php echo 0 ?>  </td>
                        <?php } else { ?>
                        <td class="td-tengah"><?php echo $row_alat5['TTL_MOVE'] ?></td>
                        <?php } ?>
                        <td class="td-tengah"><?php echo round($row_gcr['QTY']/($row_alat['CRANE_HOUR']-$row_alat2['IDLE_TIME']),2) ?></td>
                    </tr>
                <?php } ?>

            </tbody>
        </table>

        <p>&nbsp;</p>
       

        <h2>Discharging / Loading Summary</h2>

        <?php
        $query_rbm = "SELECT 
                        count(1) as JUMLAH, carrier, activity, size_cont, type_cont FROM M_CYC_CONTAINER 
                    WHERE 
                        VESSEL_CODE = '$vessel_code' 
                        and VOYAGE = '$voyage' 
                        AND VESSEL_CONFIRM IS NOT NULL
                    group by 
                        carrier, activity, size_cont, type_cont";
        $query_rbm = OCIparse($conn, $query_rbm);
        ociexecute($query_rbm);
        $data_rbm = array();
        while ($row_query_rbm = oci_fetch_array($query_rbm, OCI_ASSOC)) {
            $data_rbm[$row_query_rbm[CARRIER]][$row_query_rbm[ACTIVITY]][$row_query_rbm[SIZE_CONT]][$row_query_rbm[TYPE_CONT]] = $row_query_rbm[JUMLAH];
        };

        $query_rbm2 = "select CARRIER, activity from m_cyc_container where vessel_code = '$vessel_code' and voyage = '$voyage' and activity is not null group by carrier, activity order by carrier, activity";
        $query_rbm2 = OCIparse($conn, $query_rbm2);
        ociexecute($query_rbm2);
        ?>

        <table class="table_alumni" border="1">
            <tr>
                <th>CARRIER</th>
                <th>ACTIVITY</th>
                <th>20 DC</th>
                <th>40 DC</th>
                <th>45 DC</th>
                <th>20 RFR</th>
                <th>40 RFR</th>
                <th>45 RFR</th>
                <th>Total</th>
            </tr>
            <?php
            $jumlah1 = "";
            $jumlah2 = "";
            $jumlah3 = "";
            $jumlah4 = "";
            $jumlah5 = "";
            $jumlah6 = "";

            while ($row_query_rbm2 = oci_fetch_array($query_rbm2)) {
                ?>
                <tr>
                    <td class="td-tengah"> <?php echo $row_query_rbm2[CARRIER]; ?></td>
                    <td class="td-kiri"><?php echo$row_query_rbm2[ACTIVITY]; ?></td>                
                    <td class="td-tengah"><?php
                        echo
                        ($data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][20][DRY]) +
                        ($data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][20][HQ]) +
                        ($data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][20][TNK]) +
                        ($data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][20][OT]) +
                        ($data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][20][FLT]);

                        $jumlah1 = $jumlah1 +
                                ($data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][20][DRY]) +
                                ($data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][20][HQ]) +
                                ($data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][20][TNK]) +
                                ($data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][20][OT]) +
                                ($data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][20][FLT])
                        ?>
                    </td>          
                    <td class="td-tengah"><?php
                        echo
                        $data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][40][DRY] +
                        $data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][40][HQ] +
                        $data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][40][TNK] +
                        $data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][40][OT] +
                        $data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][40][FLT];

                        $jumlah2 = $jumlah2 +
                                $data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][40][DRY] +
                                $data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][40][HQ] +
                                $data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][40][TNK] +
                                $data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][40][OT] +
                                $data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][40][FLT]
                        ?>
                    </td>                
                    <td class="td-tengah"><?php
                        echo
                        $data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][45][DRY] +
                        $data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][45][HQ] +
                        $data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][45][TNK] +
                        $data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][45][OT] +
                        $data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][45][FLT];

                        $jumlah3 = $jumlah3 +
                                $data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][45][DRY] +
                                $data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][45][HQ] +
                                $data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][45][TNK] +
                                $data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][45][OT] +
                                $data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][45][FLT]
                        ?>
                    </td>
                    
                    
                    <?php if($data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][20][RFR]==0){?>
                    
                            <td class="td-tengah"><?php echo 0;
                        $jumlah4 = $jumlah4 +
                                $data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][20][RFR]
                        ?>
                    </td>
                        <?php } else{?>
                        
                    <td class="td-tengah"><?php
                        
                        echo $data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][20][RFR];
                        $jumlah4 = $jumlah4 +
                                $data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][20][RFR]
                        ?>
                    </td>
                    
                        <?php }?>
                    <td class="td-tengah"><?php
                        echo $data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][40][RFR];
                        $jumlah5 = $jumlah5 +
                                $data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][40][RFR];
                        ?>
                    </td>
                    <td class="td-tengah"><?php
                        echo $data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][45][RFR];
                        $jumlah6 = $jumlah6 +
                                $data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][45][RFR];
                        ?>
                    </td>
                    <td class="td-tengah"><?php
                        echo
                        (($data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][20][DRY]) +
                        ($data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][20][HQ]) +
                        ($data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][20][TNK]) +
                        ($data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][20][OT]) +
                        ($data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][20][FLT])) +
                        ($data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][40][DRY] +
                        $data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][40][HQ] +
                        $data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][40][TNK] +
                        $data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][40][OT] +
                        $data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][40][FLT]) +
                        ($data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][45][DRY] +
                        $data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][45][HQ] +
                        $data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][45][TNK] +
                        $data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][45][OT] +
                        $data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][45][FLT]) +
                        ($data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][20][RFR]) +
                        ($data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][40][RFR]) +
                        ($data_rbm[$row_query_rbm2[CARRIER]][$row_query_rbm2[ACTIVITY]][45][RFR])
                        ?></td>             

                </tr>
            <?php } ?>
            <tr>
                
                <td colspan="2" class="td-tengah">TOTAL</td>
                <td class="td-tengah"><?= $jumlah1 ?></td>
                <td class="td-tengah"><?= $jumlah2 ?></td>
                <td class="td-tengah"><?= $jumlah3 ?></td>
                <td class="td-tengah"><?= $jumlah4 ?></td>
                <td class="td-tengah"><?= $jumlah5 ?></td>
                <td class="td-tengah"><?= $jumlah6 ?></td>
                <td class="td-tengah"><?= $jumlah7 ?></td>
                
                
            </tr>
        </table>
        <p>&nbsp;</p>
        

        <?php
        $query_load_reefer = "select no_container, size_cont, reefer_temp, stw_position,status from m_cyc_container where type_cont = 'RFR' and voyage = '$voyage' and vessel_code = '$vessel_code' and E_I = 'E'";
        $query_load_reefer = OCIparse($conn, $query_load_reefer);
        ociexecute($query_load_reefer);
        $c = 1;
        ?>

        <h2>Load Reefer List</h2>
        <table class="table_alumni" border="1">
            <tbody>
                <tr>
                    <th>No</th>
                    <th>Container No</th>
                    <th>Size</th>
                    <th>Stowage</th>
                    <th>Temperature</th>
                    <th>Status</th>
                </tr>
                <?php while ($row_load_reefer = oci_fetch_array($query_load_reefer, OCI_ASSOC)) { ?>

                    <tr>
                        <td class="td-tengah"><?php echo $c++; ?></td>
                        <td class="td-kiri"><?php echo $row_load_reefer['NO_CONTAINER']; ?></td>
                        <td class="td-tengah"><?php echo $row_load_reefer['SIZE_CONT']; ?> </td>
                        <td class="td-kiri"><?php echo $row_load_reefer['STW_POSITION']; ?></td>
                        <?php if ($row_load_reefer['REEFER_TEMP'] == 0) { ?>
                            <td class="td-tengah"><?php echo "-"; ?></td>
                        <?php } else { ?>
                            <td class="td-tengah"><?php echo $row_load_reefer['REEFER_TEMP']; ?></td>
                        <?php } ?>


                        <td class="td-kiri"><?php echo $row_load_reefer['STATUS']; ?></td>
                    </tr>
                <?php } ?>

            </tbody>
        </table>


        <p>&nbsp;</p>       

        <?php
        $query_load_hazardous = "select no_container, vessel, size_cont, voyage, stw_position, IMO from m_cyc_container where E_I = 'E' and HZ = 'Y' and voyage = '$voyage' and vessel_code = '$vessel_code'";
        $query_load_hazardous = OCIparse($conn, $query_load_hazardous);
        ociexecute($query_load_hazardous);
        $c = 1;
        ?>

        <h2>Load Hazardous List</h2>
        <table class="table_alumni" border="1">
            <tbody>
                <tr>
                    <th>No</th>
                    <th>Container No</th>
                    <th>Size</th>
                    <th>Stowage</th>
                    <th>IMO Class</th>
                </tr>
                <?php while ($row_load_hazardous = oci_fetch_array($query_load_hazardous, OCI_ASSOC)) { ?>

                    <tr>
                        <td class="td-tengah"><?php echo $c++; ?></td>
                        <td class="td-kiri"><?php echo $row_load_hazardous['NO_CONTAINER']; ?></td>
                        <td class="td-tengah"><?php echo $row_load_hazardous['SIZE_CONT']; ?> </td>
                        <td class="td-kiri"><?php echo $row_load_hazardous['STW_POSITION']; ?></td>
                        <td class="td-kiri"><?php echo $row_load_hazardous['IMO']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>


        <?php
        $query_load_oog = "select NO_CONTAINER, SIZE_CONT, STW_POSITION, OVER_HEIGHT, OVER_LENGTH, OVER_WIDTH from m_cyc_container where (over_height <>'' or over_height<>0 or over_length <>''  or over_length<>0 or over_width <>'' or over_width<>0) and E_I = 'E' and voyage = '$voyage' and vessel_code = '$vessel_code'";
        $query_load_oog = OCIparse($conn, $query_load_oog);
        ociexecute($query_load_oog);
        $c = 1;
        ?>
        
        <p>&nbsp;</p>

        <h2>Load OOG/UC List</h2>
        <table class="table_alumni" border="1">
            <tbody>
                <tr>
                    <th>No</th>
                    <th>Containe No</th>
                    <th>Size</th>
                    <th>Stowage</th>
                    <th>OH</th>
                    <th>OW</th>
                    <th>OL</th>
                </tr>
                <?php while ($row_load_oog = oci_fetch_array($query_load_oog, OCI_ASSOC)) { ?>

                    <tr>
                        <td class="td-tengah"><?php echo $c++; ?></td>
                        <td class="td-kiri"><?php echo $row_load_oog['NO_CONTAINER']; ?></td>
                        <td class="td-tengah"><?php echo $row_load_oog['SIZE_CONT']; ?> </td>

                        <?php if ($row_load_oog['STW_POSITION'] == "") { ?>

                            <td><?php echo '-'; ?></td>
                        <?php } else { ?>
                            <td class="td-kiri"><?php echo $row_load_oog['STW_POSITION']; ?></td>
                        <?php } ?>


                        <?php if ($row_load_oog['OVER_HEIGHT'] == "") { ?>
                            <td class="td-tengah"><?php echo 0; ?></td>
                        <?php } else { ?> 

                            <td class="td-tengah"><?php echo $row_load_oog['OVER_HEIGHT']; ?></td>
                        <?php } ?>

                        <?php if ($row_load_oog['OVER_WIDTH'] == "") { ?>
                            <td class="td-tengah"><?php echo 0; ?></td>
                        <?php } else { ?> 

                            <td class="td-tengah"><?php echo $row_load_oog['OVER_WIDTH']; ?></td>
                        <?php } ?>

                        <?php if ($row_load_oog['OVER_LENGTH'] == "") { ?>
                            <td class="td-tengah"><?php echo 0; ?></td>
                        <?php } else { ?> 

                            <td class="td-tengah"><?php echo $row_load_oog['OVER_LENGTH']; ?></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>



</html>