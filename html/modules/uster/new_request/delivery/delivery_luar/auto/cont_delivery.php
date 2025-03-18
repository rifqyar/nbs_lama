<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB("storage");

$query 			= "SELECT DISTINCT a.NO_CONTAINER,
                                   a.SIZE_ AS SIZE_,
                                   a.TYPE_ AS TYPE_ ,
                                   b.STATUS_CONT STATUS,
                                   '' BP_ID,
                                   TO_DATE('','dd/mm/rrrr') TGL_BONGKAR,
                                   TO_DATE('','dd/mm/rrrr') TGL_END,
								   'UST' ASAL
                    FROM MASTER_CONTAINER a
                        LEFT JOIN HISTORY_CONTAINER b
                            ON A.NO_CONTAINER = B.NO_CONTAINER
                            --AND A.NO_BOOKING = B.NO_BOOKING
                            --AND A.COUNTER = B.COUNTER
                        LEFT JOIN PLACEMENT
                            ON a.NO_CONTAINER = PLACEMENT.NO_CONTAINER
                    WHERE a.NO_CONTAINER LIKE '$no_cont%' AND a.LOCATION = 'IN_YARD'
                    AND B.TGL_UPDATE = (SELECT MAX(TGL_UPDATE) FROM HISTORY_CONTAINER WHERE NO_CONTAINER LIKE '$no_cont%')
                   -- AND B.STATUS_CONT = 'MTY' 
                    and b.kegiatan IN ('REALISASI STRIPPING','GATE IN','BATAL STUFFING','BATAL RECEIVING','REQUEST BATALMUAT','REQUEST DELIVERY', 'BORDER GATE IN', 'BATAL STRIPPING', 'PERPANJANGAN STRIPPING')
					--UNION
                 -- select NO_CONTAINER, KD_SIZE SIZE_,KD_TYPE TYPE_, STATUS_CONT STATUS, BP_ID,
				--	TO_DATE(TGL_STACK,'dd/mm/rrrr') TGL_BONGKAR, TO_DATE(TGL_STACK,'dd/mm/rrrr')+4 TGL_END, 'TPK' ASAL
                --  from petikemas_cabang.V_MTY_AREA_TPK
				--	where NO_CONTAINER LIKE '$no_cont%' and TO_DATE(TGL_STACK,'dd/mm/rrrr') > TO_DATE('01/04/2013','dd/mm/rrrr') ";
$result			= $db->query($query);
$row			= $result->getAll();

//print_r($row);

echo json_encode($row);


?>
