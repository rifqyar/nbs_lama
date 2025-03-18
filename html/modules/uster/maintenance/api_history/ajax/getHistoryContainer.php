<?php
$db = getDb('storage');
$NO_CONTAINER = $_POST["no_cont"];

$query = "SELECT * FROM (SELECT Q.NO_CONTAINER, Q.SIZE_, Q.TYPE_, Q.LOCATION, Q.NO_BOOKING, Q.COUNTER, Q.NM_KAPAL, Q.VOYAGE_IN
   FROM (SELECT DISTINCT MC.NO_CONTAINER, MC.SIZE_, MC.TYPE_, MC.LOCATION, P.NO_BOOKING, MAX(P.TGL_UPDATE) TIME_, P.COUNTER , PK.NM_KAPAL, PK.VOYAGE_IN
                       FROM MASTER_CONTAINER MC
                       JOIN HISTORY_CONTAINER P ON MC.NO_CONTAINER = P.NO_CONTAINER
                       LEFT JOIN V_PKK_CONT PK ON REPLACE(P.NO_BOOKING, 'VESSEL_NOTHING', 'BSK100000023') = PK.NO_BOOKING                    
                       WHERE MC.NO_CONTAINER = '$NO_CONTAINER' 
                       GROUP BY MC.NO_CONTAINER, MC.SIZE_, MC.TYPE_, MC.LOCATION, P.NO_BOOKING, P.COUNTER, PK.NM_KAPAL, PK.VOYAGE_IN
                       ORDER BY TIME_ DESC) Q,  
   (SELECT NO_REQUEST,COUNTER, NO_BOOKING,KEGIATAN,NO_CONTAINER FROM HISTORY_CONTAINER) W
   WHERE Q.NO_BOOKING = W.NO_BOOKING(+)
   AND Q.NO_BOOKING IS NOT NULL
   AND Q.COUNTER = W.COUNTER(+)
   AND Q.NO_CONTAINER = W.NO_CONTAINER(+)
   AND W.NO_BOOKING(+) = 'VESSEL_NOTHING'
   AND W.KEGIATAN(+) = 'REQUEST RECEIVING'
   ORDER BY TIME_ DESC ) WHERE ROWNUM <= 6";

$container = $db->query($query);
$container = $container->getAll();

$list = array();
foreach ($container as $container) {
  
    $no_cont = $container['NO_CONTAINER'];
    $no_booking = $container['NO_BOOKING'];
    unset($container['NO_BOOKING']);
    $counter = $container['COUNTER'];
    
    if($no_booking==null||$no_booking==''){
        $que = "select no_booking from request_delivery, container_delivery 
        where request_delivery.no_request = container_delivery.no_request and delivery_ke = 'TPK' 
        and to_date(tgl_request,'dd/mm/yy') between to_date('09/04/2013','dd/mm/yy') and  to_date('11/04/2013','dd/mm/yy') and jn_repo = 'EMPTY'
        and no_container = '$no_container'";
        $rque = $db->query($que);
        $rwq = $rque->fetchRow();
    
        $book =  $rwq["NO_BOOKING"];
    
        $q_detail = "SELECT A.NM_KAPAL, A.VOYAGE_IN , A.TGL_JAM_TIBA AS TGL_TIBA FROM v_pkk_cont A
        WHERE A.NO_BOOKING = '$book'";
        $res_detail = $db->query($q_detail);
        $data['vessel'] = $res_detail->fetchRow();
    }else{
        $q_detail = "SELECT A.NM_KAPAL, A.VOYAGE_IN , A.TGL_JAM_TIBA AS TGL_TIBA FROM v_pkk_cont A
        WHERE A.NO_BOOKING = '$no_booking'";
        $res_detail = $db->query($q_detail);
        $data['vessel'] = $res_detail->fetchRow();
    }
    
    
    
    $data['container'] = $container;
    
    if ($no_booking == "VESSEL_NOTHING") {
        $q_detail = "SELECT MC.NO_CONTAINER, HC.STATUS_CONT, HC.ID_YARD ,YAR.NAMA_YARD, HC.KEGIATAN, 
                            CASE WHEN HC.KEGIATAN = 'REALISASI STRIPPING'
            THEN (SELECT to_char(MAX(TGL_REALISASI),'DD-MM-YYYY hh24:mi')  FROM CONTAINER_STRIPPING WHERE NO_CONTAINER = MC.NO_CONTAINER AND NO_REQUEST = RST.NO_REQUEST) 
            WHEN HC.KEGIATAN = 'REALISASI STUFFING'
            THEN (SELECT to_char(MAX(TGL_REALISASI),'DD-MM-YYYY hh24:mi') FROM CONTAINER_STUFFING WHERE NO_CONTAINER = MC.NO_CONTAINER AND NO_REQUEST = RSF.NO_REQUEST)
            WHEN HC.KEGIATAN = 'BORDER GATE IN'
            THEN (SELECT to_char(MAX(TGL_IN),'DD-MM-YYYY hh24:mi') FROM BORDER_GATE_IN WHERE NO_CONTAINER = MC.NO_CONTAINER AND (NO_REQUEST = RST.NO_REQUEST OR NO_REQUEST = RSF.NO_REQUEST OR NO_REQUEST = RR.NO_REQUEST))
             WHEN HC.KEGIATAN = 'BORDER GATE OUT'
            THEN (SELECT to_char(MAX(TGL_IN),'DD-MM-YYYY hh24:mi') FROM BORDER_GATE_OUT WHERE NO_CONTAINER = MC.NO_CONTAINER AND (NO_REQUEST = RR.NO_REQUEST OR NO_REQUEST = RD.NO_REQUEST))
            WHEN HC.KEGIATAN = 'GATE OUT'
            THEN (SELECT to_char(MAX(TGL_IN),'DD-MM-YYYY hh24:mi') FROM GATE_OUT WHERE NO_CONTAINER = MC.NO_CONTAINER AND (NO_REQUEST = RST.NO_REQUEST OR NO_REQUEST = RSF.NO_REQUEST OR NO_REQUEST = RR.NO_REQUEST OR NO_REQUEST = RD.NO_REQUEST))
            WHEN HC.KEGIATAN = 'GATE IN'
            THEN (SELECT to_char(MAX(TGL_IN),'DD-MM-YYYY hh24:mi') FROM GATE_IN WHERE NO_CONTAINER = MC.NO_CONTAINER AND NO_REQUEST = RR.NO_REQUEST)
            WHEN HC.KEGIATAN = 'REQUEST DELIVERY'
            THEN (SELECT to_char(TGL_REQUEST,'DD-MM-YYYY hh24:mi') FROM REQUEST_DELIVERY WHERE NO_REQUEST = RD.NO_REQUEST)
            WHEN HC.KEGIATAN = 'REQUEST RECEIVING'
            THEN (SELECT to_char(TGL_REQUEST,'DD-MM-YYYY hh24:mi') FROM REQUEST_RECEIVING WHERE NO_REQUEST = RR.NO_REQUEST)
            WHEN HC.KEGIATAN = 'REQUEST STRIPPING'
            THEN (SELECT to_char(TGL_REQUEST,'DD-MM-YYYY hh24:mi') FROM REQUEST_STRIPPING WHERE NO_REQUEST = RST.NO_REQUEST)
            WHEN HC.KEGIATAN = 'REQUEST STUFFING'
            THEN (SELECT to_char(TGL_REQUEST,'DD-MM-YYYY hh24:mi') FROM REQUEST_STUFFING WHERE NO_REQUEST = RSF.NO_REQUEST)
            WHEN HC.KEGIATAN = 'REQUEST BATALMUAT'
            THEN (SELECT to_char(TGL_REQUEST,'DD-MM-YYYY hh24:mi') FROM REQUEST_BATAL_MUAT WHERE NO_REQUEST = RBM.NO_REQUEST)
            ELSE to_char(HC.TGL_UPDATE,'DD-MM-YYYY hh24:mi')
            END TGL_UPDATE, HC.NO_BOOKING NO_BOOKING, HC.ID_USER, MU.NAME NAMA_LENGKAP, RR.NO_REQUEST NO_REQ_REC, 
                            RST.NO_REQUEST NO_REQ_STR, RD.NO_REQUEST NO_REQ_DEL, RSF.NO_REQUEST NO_REQ_STF, REL.NO_REQUEST NO_REQ_REL, RBM.NO_REQUEST NO_REQ_RBM
                            FROM MASTER_CONTAINER MC INNER JOIN HISTORY_CONTAINER HC
                            ON MC.NO_CONTAINER = HC.NO_CONTAINER 
                            LEFT JOIN REQUEST_RECEIVING RR
                            ON RR.NO_REQUEST = HC.NO_REQUEST
                            LEFT JOIN REQUEST_STRIPPING RST
                            ON RST.NO_REQUEST = HC.NO_REQUEST
                            LEFT JOIN REQUEST_DELIVERY RD
                            ON RD.NO_REQUEST = HC.NO_REQUEST
                            LEFT JOIN REQUEST_STUFFING RSF
                            ON RSF.NO_REQUEST = HC.NO_REQUEST
                            LEFT JOIN REQUEST_RELOKASI REL
                            ON REL.NO_REQUEST = HC.NO_REQUEST
            LEFT JOIN REQUEST_BATAL_MUAT RBM
            ON RBM.NO_REQUEST = HC.NO_REQUEST
                            LEFT JOIN YARD_AREA YAR ON HC.ID_YARD = YAR.ID
                            LEFT JOIN BILLING_NBS.TB_USER MU ON to_char(MU.ID) = HC.ID_USER
                            WHERE MC.NO_CONTAINER = '$no_cont'
                            AND HC.NO_BOOKING = '$no_booking'
                            AND HC.COUNTER = '$counter'
                            ORDER BY HC.TGL_UPDATE DESC";
    } else {
        $q_detail = "SELECT MC.NO_CONTAINER, HC.STATUS_CONT, HC.ID_YARD ,YAR.NAMA_YARD, HC.KEGIATAN, 
                            CASE WHEN HC.KEGIATAN = 'REALISASI STRIPPING'
            THEN (SELECT to_char(MAX(TGL_REALISASI),'DD-MM-YYYY hh24:mi')  FROM CONTAINER_STRIPPING WHERE NO_CONTAINER = MC.NO_CONTAINER AND NO_REQUEST = RST.NO_REQUEST) 
            WHEN HC.KEGIATAN = 'REALISASI STUFFING'
            THEN (SELECT to_char(MAX(TGL_REALISASI),'DD-MM-YYYY hh24:mi') FROM CONTAINER_STUFFING WHERE NO_CONTAINER = MC.NO_CONTAINER AND NO_REQUEST = RSF.NO_REQUEST)
            WHEN HC.KEGIATAN = 'BORDER GATE IN'
            THEN (SELECT to_char(MAX(TGL_IN),'DD-MM-YYYY hh24:mi') FROM BORDER_GATE_IN WHERE NO_CONTAINER = MC.NO_CONTAINER AND (NO_REQUEST = RST.NO_REQUEST OR NO_REQUEST = RSF.NO_REQUEST OR NO_REQUEST = RR.NO_REQUEST))
             WHEN HC.KEGIATAN = 'BORDER GATE OUT'
            THEN (SELECT to_char(MAX(TGL_IN),'DD-MM-YYYY hh24:mi') FROM BORDER_GATE_OUT WHERE NO_CONTAINER = MC.NO_CONTAINER AND (NO_REQUEST = RR.NO_REQUEST OR NO_REQUEST = RD.NO_REQUEST))
            WHEN HC.KEGIATAN = 'GATE OUT'
            THEN (SELECT to_char(MAX(TGL_IN),'DD-MM-YYYY hh24:mi') FROM GATE_OUT WHERE NO_CONTAINER = MC.NO_CONTAINER AND (NO_REQUEST = RST.NO_REQUEST OR NO_REQUEST = RSF.NO_REQUEST OR NO_REQUEST = RR.NO_REQUEST OR NO_REQUEST = RD.NO_REQUEST))
            WHEN HC.KEGIATAN = 'GATE IN'
            THEN (SELECT to_char(MAX(TGL_IN),'DD-MM-YYYY hh24:mi') FROM GATE_IN WHERE NO_CONTAINER = MC.NO_CONTAINER AND NO_REQUEST = RR.NO_REQUEST)
            WHEN HC.KEGIATAN = 'REQUEST DELIVERY'
            THEN (SELECT to_char(TGL_REQUEST,'DD-MM-YYYY hh24:mi') FROM REQUEST_DELIVERY WHERE NO_REQUEST = RD.NO_REQUEST)
            WHEN HC.KEGIATAN = 'REQUEST RECEIVING'
            THEN (SELECT to_char(TGL_REQUEST,'DD-MM-YYYY hh24:mi') FROM REQUEST_RECEIVING WHERE NO_REQUEST = RR.NO_REQUEST)
            WHEN HC.KEGIATAN = 'REQUEST STRIPPING'
            THEN (SELECT to_char(TGL_REQUEST,'DD-MM-YYYY hh24:mi') FROM REQUEST_STRIPPING WHERE NO_REQUEST = RST.NO_REQUEST)
            WHEN HC.KEGIATAN = 'REQUEST STUFFING'
            THEN (SELECT to_char(TGL_REQUEST,'DD-MM-YYYY hh24:mi') FROM REQUEST_STUFFING WHERE NO_REQUEST = RSF.NO_REQUEST)
            WHEN HC.KEGIATAN = 'REQUEST BATALMUAT'
            THEN (SELECT to_char(TGL_REQUEST,'DD-MM-YYYY hh24:mi') FROM REQUEST_BATAL_MUAT WHERE NO_REQUEST = RBM.NO_REQUEST)
            ELSE to_char(HC.TGL_UPDATE,'DD-MM-YYYY hh24:mi')
            END TGL_UPDATE, HC.NO_BOOKING NO_BOOKING, HC.ID_USER, MU.NAME NAMA_LENGKAP, RR.NO_REQUEST NO_REQ_REC, 
                            RST.NO_REQUEST NO_REQ_STR, RD.NO_REQUEST NO_REQ_DEL, RSF.NO_REQUEST NO_REQ_STF, REL.NO_REQUEST NO_REQ_REL, RBM.NO_REQUEST NO_REQ_RBM
                            FROM MASTER_CONTAINER MC INNER JOIN HISTORY_CONTAINER HC
                            ON MC.NO_CONTAINER = HC.NO_CONTAINER 
                            LEFT JOIN REQUEST_RECEIVING RR
                            ON RR.NO_REQUEST = HC.NO_REQUEST
                            LEFT JOIN REQUEST_STRIPPING RST
                            ON RST.NO_REQUEST = HC.NO_REQUEST
                            LEFT JOIN REQUEST_DELIVERY RD
                            ON RD.NO_REQUEST = HC.NO_REQUEST
                            LEFT JOIN REQUEST_STUFFING RSF
                            ON RSF.NO_REQUEST = HC.NO_REQUEST
                            LEFT JOIN REQUEST_RELOKASI REL
                            ON REL.NO_REQUEST = HC.NO_REQUEST
            LEFT JOIN REQUEST_BATAL_MUAT RBM
            ON RBM.NO_REQUEST = HC.NO_REQUEST
                            LEFT JOIN YARD_AREA YAR ON HC.ID_YARD = YAR.ID
                            LEFT JOIN BILLING_NBS.TB_USER MU ON TO_CHAR(MU.ID) = HC.ID_USER
                            WHERE MC.NO_CONTAINER = '$no_cont'
                            AND HC.NO_BOOKING = '$no_booking'
                            AND HC.COUNTER = '$counter'
                            ORDER BY HC.TGL_UPDATE DESC";
    }
    $res_detail = $db->query($q_detail);
    $r_det = $res_detail->getAll();
    
    $data['handling'] = $r_det;
    
    
    
   
    
    $list[] = $data;
}




echo json_encode($list);
