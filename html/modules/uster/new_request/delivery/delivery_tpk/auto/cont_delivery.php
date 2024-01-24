<?php
$no_cont		= strtoupper($_GET["term"]);
$jn_repo		= strtoupper($_GET["jn_repo"]);
$db 			= getDB("storage");

if($jn_repo == 'EMPTY')
{
    $query = "SELECT 
                    a.NO_CONTAINER, 
                    a.SIZE_ AS SIZE_, 
                    a.TYPE_ AS TYPE_ , 
                    CASE
                        WHEN a.NO_BOOKING = 'VESSEL_NOTHING' THEN 'BS05I100001' ELSE a.NO_BOOKING END AS NO_BOOKING,
                    b.STATUS_CONT STATUS, 
                    TO_DATE('','dd/mm/rrrr') TGL_STACK, 
                    'UST' ASAL
             FROM  
                    MASTER_CONTAINER a INNER JOIN HISTORY_CONTAINER b ON a.NO_CONTAINER= b.NO_CONTAINER 
             WHERE 
                    a.NO_CONTAINER LIKE '$no_cont%' 
                    AND a.LOCATION = 'IN_YARD'
                    AND b.status_cont = 'MTY'        
                    AND b.TGL_UPDATE = (SELECT MAX(TGL_UPDATE)FROM HISTORY_CONTAINER WHERE NO_CONTAINER LIKE '$no_cont%' 
                    AND AKTIF IS NULL)AND  b.kegiatan IN ('REALISASI STRIPPING','GATE IN',
                    'REQUEST DELIVERY','PERP DELIVERY','BATAL STUFFING','BORDER GATE IN')";
}
else if ($jn_repo == 'FULL')
{
    $query = "SELECT 
                    a.NO_CONTAINER, 
                    a.SIZE_ AS SIZE_, 
                    a.TYPE_ AS TYPE_ , 
                    CASE
                        WHEN a.NO_BOOKING = 'VESSEL_NOTHING' THEN 'BS05I100001' ELSE a.NO_BOOKING END AS NO_BOOKING,
                    b.STATUS_CONT STATUS, 
                    TO_DATE('','dd/mm/rrrr') TGL_STACK, 
                    'UST' ASAL
             FROM  
                    MASTER_CONTAINER a INNER JOIN HISTORY_CONTAINER b ON a.NO_CONTAINER= b.NO_CONTAINER 
             WHERE 
                    a.NO_CONTAINER LIKE '$no_cont%' 
                    AND a.LOCATION = 'IN_YARD'
                    AND b.status_cont = 'FCL'        
                    AND b.TGL_UPDATE = (SELECT MAX(TGL_UPDATE)FROM HISTORY_CONTAINER WHERE NO_CONTAINER LIKE '$no_cont%' 
                    AND AKTIF IS NULL)AND  b.kegiatan IN ('REALISASI STRIPPING','GATE IN',
                    'REQUEST DELIVERY','PERP DELIVERY','BATAL STUFFING','BORDER GATE IN')";
}
else{
    $query = "SELECT 
                    DISTINCT m.NO_CONTAINER, 
                    m.SIZE_ AS SIZE_, 
                    m.TYPE_ AS TYPE_ ,
                    M.NO_BOOKING NO_BOOKING,
                    s.TGL_REALISASI REALISASI_STUFFING,
                    h.STATUS_CONT AS STATUS,
                    h.NO_REQUEST
             FROM  
                    MASTER_CONTAINER m INNER JOIN HISTORY_CONTAINER h ON m.NO_CONTAINER = h.NO_CONTAINER and m.NO_BOOKING = h.NO_BOOKING
                    INNER JOIN CONTAINER_STUFFING s ON s.NO_CONTAINER = m.NO_CONTAINER AND s.NO_CONTAINER = h.NO_CONTAINER
                    --INNER JOIN v_booking_stack_tpk vb 
                    --ON m.NO_BOOKING = vb.NO_BOOKING
                    --AND h.NO_BOOKING = vb.NO_BOOKING
             WHERE   
                    h.TGL_UPDATE = (SELECT DISTINCT MAX(TGL_UPDATE)FROM HISTORY_CONTAINER WHERE NO_CONTAINER LIKE '%$no_cont%'
                    AND NO_BOOKING = h.NO_BOOKING AND aktif is null and kegiatan in('REALISASI STUFFING','REQUEST BATALMUAT'))    
                    AND m.LOCATION = 'IN_YARD' AND m.NO_CONTAINER LIKE '%$no_cont%' AND s.AKTIF='T'
                    AND h.aktif is null";    
    
}


$result	= $db->query($query);
$row	= $result->getAll();
//print_r($row);
echo json_encode($row);
?>