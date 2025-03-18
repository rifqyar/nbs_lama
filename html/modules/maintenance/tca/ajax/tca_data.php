<?php
	$db 		= getDB();
	$vessel  	= $_GET['vessel'];
	$voyage_in 	= $_GET['voyage_in'];
	$voyage_out	= $_GET['voyage_out'];
	$terminal	= $_GET['terminal'];

	$query = "SELECT b.no_container,
		         c.f_tcatn truck_id,
		         c.f_tcapn pin_number,
		         CASE
		            WHEN f_tcast = 'R' THEN 'Ready to Gate In'
		            WHEN f_tcast = 'P' THEN 'Processing'
		            WHEN f_tcast = 'F' THEN 'Finished'
		            WHEN f_tcast = 'C' THEN 'Canceled'
		            ELSE 'Not associated yet'
		         END
		            AS status,
		         d.police_number,
		         e.activity,
                 e.cont_location
		    FROM req_delivery_h a
		         JOIN req_delivery_d b
		            ON a.id_req = TRIM (b.id_req)
		         JOIN m_cyc_container@dbint_link e
                    ON a.vessel = e.vessel
                         AND a.voyage_in = e.voyage_in
                         AND a.voyage_out = e.voyage_out
                         AND b.no_container = e.no_container
		         LEFT JOIN opus_repo.tb_association c
		            ON b.no_container = c.f_tcacn AND b.pin_number = c.f_tcapn
		         LEFT JOIN opus_repo.m_cdy_truck d
		            ON c.f_tcatn = d.truck_number
		   WHERE     a.vessel = '$vessel'
		         AND a.voyage_in = '$voyage_in'
		         AND a.voyage_out = '$voyage_out'
		         AND a.dev_via <> 'USTER'
		         AND a.status = 'P'
		         AND e.cont_location <> 'Out'
		ORDER BY tgl_request DESC
		";
	
	$query_ust = "SELECT b.no_container,
                 c.f_tcatn truck_id,
                 c.f_tcapn pin_number,
                 CASE
                    WHEN f_tcast = 'R' THEN 'Ready to Gate In'
                    WHEN f_tcast = 'P' THEN 'Processing'
                    WHEN f_tcast = 'F' THEN 'Finished'
                    WHEN f_tcast = 'C' THEN 'Canceled'
                    ELSE 'Not associated yet'
                 END
                    AS status,
                 d.police_number,
                 e.activity,
                 e.cont_location
            FROM req_delivery_h a
                 JOIN req_delivery_d b
                    ON a.id_req = TRIM (b.id_req)
                 JOIN m_cyc_container@dbint_link e
                    ON a.vessel = e.vessel
                         AND a.voyage_in = e.voyage_in
                         AND a.voyage_out = e.voyage_out
                         AND b.no_container = e.no_container
                 LEFT JOIN opus_repo.tb_association c
                    ON b.no_container = c.f_tcacn AND b.pin_number = c.f_tcapn
                 LEFT JOIN opus_repo.m_cdy_truck d
                    ON c.f_tcatn = d.truck_number
           WHERE     a.vessel = '$vessel'
		         AND a.voyage_in = '$voyage_in'
		         AND a.voyage_out = '$voyage_out'
                 AND a.dev_via = 'USTER'
                 AND e.billing_request_id is not null
                 AND e.cont_location <> 'Out'
        ORDER BY tgl_request DESC";

     if($terminal == 'out') {
     	$data 		= $db->query($query);
	 	$rowdata	= $data->getAll();
	 	$rowmerge = array_merge($rowdata);
	 }
	 else if($terminal == 'uster') {
	     $data_ust = $db->query($query_ust);
	     $rowdata_ust = $data_ust->getAll();
	     $rowmerge = array_merge($rowdata_ust);
	 }
	 else {
	 	$data 		= $db->query($query);
	 	$rowdata	= $data->getAll();
	 	
	 	$data_ust = $db->query($query_ust);
	    $rowdata_ust = $data_ust->getAll();
	 	
	 	$rowmerge = array_merge($rowdata,$rowdata_ust);
	 }

     

     $i=0;
	foreach ($rowmerge as $rowm) {
			$response->rows[$i]['id']=$rowm[NO_CONTAINER];
			$response->rows[$i]['cell']=array($rowm[NO_CONTAINER],$rowm[TRUCK_ID],$rowm[POLICE_NUMBER],$rowm[PIN_NUMBER],$rowm[STATUS],$rowm[ACTIVITY]);
			$i++;
	}
      
    

	echo json_encode($response);
?>