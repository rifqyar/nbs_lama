<?php

$container			= strtoupper($_GET["term"]);


$db 			= getDB('dbint');	



$query 			= "select etg_pre_contno no_container, if_lst_bil_rqst_id no_request, to_date(pay_thru_dt,'yyyymmddhh24miss') pay_thru, b.cont_location,b.activity  from etg_preadvice a
left join m_cyc_container b on a.etg_pre_contno = b.no_container and a.etg_pre_vessel = b.vessel_code and a.etg_pre_voyage = b.voyage
                   where etg_pre_iomode = 'O' and pay_flg = 'Y' and etg_pre_contno like '$container%' and activity = 'PLACEMENT IMPORT'";	

$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>