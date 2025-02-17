<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
$query 			= "select * from (select d.no_container, d.size_cont, trim(d.type_cont) type_cont, d.vessel||'|'||d.voyage_in||' '||d.voyage_out as vessel, d.no_ukk
                    from BILLING_NBS.req_delivery_h h, BILLING_NBS.req_delivery_d d where trim(h.id_req) = trim(d.id_req) 
                    and no_container like '%$no_cont%'
                    order by tgl_request desc) where rownum <=1";
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>