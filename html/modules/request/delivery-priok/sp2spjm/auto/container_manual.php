<?php
//utk menon-aktifkan template default
outputRaw();
$nocont = strtoupper($_GET["term"]);
$ukk = TRIM($_GET['no_ukk']);
$db = getDB('ora');

$query = "select c.no_ukk,
				 c.nm_kapal vessel,
				 c.voyage_in||'-'||c.voyage_out voyage,
				 a.cont_no_bp no_container,
				 trim(a.kd_size)||'-'||trim(a.kd_type)||'-'||trim(a.kd_status_cont) jenis,
				 trim(a.cont_type) isocode,
				 trim(a.height) height,
				 trim(a.carrier) carrier,
                 to_char(d.confirm_date,'dd-mm-rrrr') tgl_disch
        from ttd_bp_cont a, ttm_bp_cont b, v_pkk_cont c, ttd_bp_confirm d
        where trim(a.status_cont) = '03'
            and trim(c.no_ukk) = '$ukk'     
            and trim(a.bp_id) = trim(b.bp_id)
            and trim(b.no_ukk) = trim(c.no_ukk)
            and trim(a.bp_id) = trim(d.bp_id)
            and trim(a.cont_no_bp) = trim(d.cont_no_bp)
            and trim(b.kd_cabang) = '01'
			and trim(a.cont_no_bp) like '%$nocont%'";
			  
//print_r($query);die;
$result	= $db->query($query);
$row = $result->getAll();	

echo json_encode($row);
?>