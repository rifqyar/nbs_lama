<?php
	$tl = xliteTemplate('not.htm');			
	$db = getDB();

	$stat = $_GET['remark']; //req,realisasi,view
	$id_reqs = $_GET['id_req'];
    $status_time = "not";
	
	   if($stat=="req")
	   {
			$row = $db->query("SELECT ID_ALAT,
								      SHIFT,
									  ID_DETAILS
								   FROM GLC_DETAIL_REQUEST_SHIFT
								   WHERE ID_REQ = '$id_reqs'
								   AND STAT_TIME = 'NOT'
								   AND EST_REAL = 'ESTIMASI'")->getAll();
			
			$tl->assign('hasil_detail',$row);
		}
		else if($stat=="realisasi")
		{
			$row = $db->query("SELECT ID_ALAT,
			                          ID_DETAILS,
									  TO_CHAR(MULAI,'DD-MM-YYYY HH24:MI:SS') AS MULAI,
									  TO_CHAR(SELESAI,'DD-MM-YYYY HH24:MI:SS') AS SELESAI
								   FROM GLC_DETAIL_REQUEST_SHIFT
								   WHERE ID_REQ = '$id_reqs'
								   AND STAT_TIME = 'NOT'
								   AND EST_REAL = 'REALISASI'")->getAll();
			
			$tl->assign('hasil_detail',$row);
		}
		else if($stat=="view")
		{
			$query3 = "SELECT DISTINCT ID_ALAT FROM GLC_DETAIL_REQUEST_SHIFT WHERE ID_REQ = '$id_reqs' AND STAT_TIME = 'NOT' AND EST_REAL = 'REALISASI'";
			$eksekusi3 = $db->query($query3);
			$col_preview3 = $eksekusi3->getAll();
			
			$n=0;
			foreach($col_preview3 as $b)
			{
				$id_alat[$n] = $b['ID_ALAT'];		
				$hasil3[$n] = $db->query("SELECT TO_CHAR(MIN(MULAI),'DD-MM-YYYY HH24:MI:SS') AS START_WORK,
										   TO_CHAR(MAX(SELESAI),'DD-MM-YYYY HH24:MI:SS') AS END_WORK,
										   ROUND((SUM(SHIFT)*(24))/8) AS JML_SHIFT
										   FROM GLC_DETAIL_REQUEST_SHIFT 
										   WHERE ID_REQ = '$id_reqs' 
										   AND ID_ALAT = '$id_alat[$n]'
										   AND STAT_TIME = 'NOT'
										   AND EST_REAL = 'REALISASI'")->fetchRow();
				
				$start_work[$n] = $hasil3[$n]['START_WORK'];
				$end_work[$n] = $hasil3[$n]['END_WORK'];
				$jumlah_shift[$n] = $hasil3[$n]['JML_SHIFT'];
				$n++;
			}
			
			$tl->assign('glc',$col_preview3);
			$tl->assign('strt',$start_work);
			$tl->assign('end',$end_work);
			$tl->assign('shift',$jumlah_shift);			
		}
	
	$tl->assign('status_form',$stat);
	$tl->assign('stat_time',$status_time);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);		
	$tl->renderToScreen();
	
?>