<?php
$no_cont		= strtoupper($_GET["term"]);

$db 	= getDB();	
$query	= "select hd.no_ukk,
			 trim(vs.nm_kapal) as vessel,
			 vs.voyage_in||' - '||vs.voyage_out as voyage,
			 hd.bp_id,
			 dt.cont_no_bp as no_container,
			 dt.kd_size as size_,
			 dt.kd_type as type_,
			 dt.kd_status_cont as status_,
			 case when dt.class is not null then 'Y' else 'T'
			 end hz,
			 dt.cont_type as iso_code,
			 dt.status_cont,
			 dt.gross,
			 dt.carrier,
			 dt.height,
			 to_number(dt.bay_bp) as bay_no,
			 to_number(dt.row_bp) as bay_row,
			 to_number(dt.tier_bp) as bay_tier,
			 to_char(conf.confirm_date,'dd-mm-yyyy HH24:MI:SS') as discharge_date,
			 dt.disc_port as pod,
			 dt.load_port as pol,
			 dt.status_segel_merah
		from ttd_bp_cont@prodlinkx dt, ttm_bp_cont@prodlinkx hd, v_pkk_cont@prodlinkx vs, ttd_bp_confirm@prodlinkx conf
		where trim(hd.bp_id) = trim(dt.bp_id)
		 and trim(hd.no_ukk) = trim(vs.no_ukk)
		 and trim(hd.kd_cabang) = trim(vs.kd_cabang)
		 and trim(hd.bp_id) = trim(conf.bp_id)
		 and trim(hd.no_ukk) = trim(conf.no_ukk)
		 and trim(dt.bp_id) = trim(conf.bp_id)
		 and trim(dt.cont_no_bp) = trim(conf.cont_no_bp)
		 and trim(vs.kd_cabang) = '01'
		 and trim(dt.status_cont) in ('03','04','10') 
		 and trim(dt.cont_no_bp) like '$no_cont%'";					
										
//print_r($query);die;
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($query);die;

echo json_encode($row);


?>