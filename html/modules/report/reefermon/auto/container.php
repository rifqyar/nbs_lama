<?php

$no_cont		= strtoupper($_GET["term"]);
$db 			= getDB('dbint');

	$query 			= "select b.NO_CONTAINER,b.SIZE_CONT||' / '||b.TYPE_CONT||' / '||b.STATUS as CONTSPEK,
b.VESSEL||' '|| b.VOYAGE_IN||' / '||B.VOYAGE_OUT as CONTVVD,
    to_char(to_date(a.CYR_PLUG_PINDATE||a.CYR_PLUG_PINTIME,'yyyymmddhh24miss'),'dd Mon yyyy hh24:mi') as contplugin,
    to_char(to_date(a.CYR_PLUG_POUTDATE||a.CYR_PLUG_POUTTIME,'yyyymmddhh24miss'),'dd Mon yyyy hh24:mi') as contplugout,
    a.CYR_PLUG_PINTEMP||'/'||a.CYR_PLUG_POUTTEMP AS TEMPS,
    b.E_I,
    b.POINT,
   round( (to_date(a.CYR_PLUG_POUTDATE||a.CYR_PLUG_POUTTIME,'yyyymmddhh24miss')-to_date(a.CYR_PLUG_PINDATE||a.CYR_PLUG_PINTIME,'yyyymmddhh24miss'))*24,3 )
    ||' / '||ceil((to_date(a.CYR_PLUG_POUTDATE||a.CYR_PLUG_POUTTIME,'yyyymmddhh24miss')-to_date(a.CYR_PLUG_PINDATE||a.CYR_PLUG_PINTIME,'yyyymmddhh24miss'))*3) as SHIFT,
        b.VESSEL, b.VOYAGE_IN,B.VOYAGE_OUT,
    ceil((to_date(a.CYR_PLUG_POUTDATE||a.CYR_PLUG_POUTTIME,'yyyymmddhh24miss')-to_date(a.CYR_PLUG_PINDATE||a.CYR_PLUG_PINTIME,'yyyymmddhh24miss'))*3) as SHIFT_REAL,
    c.customer_name
 from pnoadm.cyr_pluginout a join opus_repo.m_cyc_container b on A.CYR_PLUG_CONTNO=B.NO_CONTAINER and A.CYR_PLUG_POINT=B.POINT  
 left join m_billing c on b.no_container =c.no_container and b.billing_request_id = c.no_request
 where a.cyr_plug_contno='$no_cont'
 order by vessel_confirm desc";
					//Chassis
$result			= $db->query($query);
$row			= $result->getAll();	

//echo $query;
//print_r($row);

echo json_encode($row);


?>