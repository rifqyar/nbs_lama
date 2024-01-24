<?php
	$tl = xliteTemplate('working.htm');			
	$db = getDB();

	$stat = $_GET['remark']; //req,realisasi,view
	$id_reqs = $_GET['id_req'];
	$glc = $_GET['alat'];
    $status_time = "wrk";
	
	   if($stat=="req")
	   {
			$row = $db->query("SELECT ID_ALAT,
								      SHIFT,
									  TO_CHAR(MULAI,'DD-MM-YYYY HH24:MI:SS') AS MULAI,
									  TO_CHAR(SELESAI,'DD-MM-YYYY HH24:MI:SS') AS SELESAI,
									  ID_DETAILS
								   FROM GLC_DETAIL_EST_SHIFT
								   WHERE ID_REQ = '$id_reqs'
								   AND ID_ALAT = '$glc'
								   ORDER BY ID_DETAILS,MULAI")->getAll();
			
			$tl->assign('hasil_detail',$row);
		}
		else if($stat=="realisasi")
		{
			$row = $db->query("SELECT ID_REQ,
									  ID_ALAT,
			                          ID_DETAILS,
									  TO_CHAR(MULAI,'DD-MM-YYYY HH24:MI:SS') AS MULAI,
									  TO_CHAR(SELESAI,'DD-MM-YYYY HH24:MI:SS') AS SELESAI,
									  KETERANGAN,
									  STAT_TIME
								   FROM GLC_DETAIL_REAL_SHIFT
								   WHERE ID_REQ = '$id_reqs'
								   AND ID_ALAT = '$glc'
								   ORDER BY ID_DETAILS,MULAI")->getAll();
								   
			 $n=0;
			 foreach($row as $b)
			 {
				 $time_stat[$n] = $b['STAT_TIME'];
				 $id_produksi[$n] = $b['ID_DETAILS'];
				
				if(($time_stat[$n]=="WORKING")||($time_stat[$n]=="NOT OPERATION"))
				{					 				
					$glcs_stat[$n] = "OK";
				}
				else if($time_stat[$n]=="IDLE")
				{
					$glcs_stat[$n] = "-";
				}
				
				$box_ton[$n] = $db->query("SELECT SUM(JUMLAH_CONT) AS JML_BOX_TON
										   FROM GLC_PRODUKSI 
										   WHERE ID_REQ = '$id_reqs'
										   AND ID_DETAILS = '$id_produksi[$n]'")->fetchRow();
										   
				$jumlah_prod[$n] = $box_ton[$n]['JML_BOX_TON'];
				
				if($jumlah_prod[$n]!=NULL)
				{
					$jml_produksi[$n]=$jumlah_prod[$n];
				}
				else
				{
					$jml_produksi[$n]="0";
				}
				
				$n++;
			 }
			
			//print_r($glcs_stat);die;
			//print_r($jml_produksi);die;
			
			$tl->assign('glc_stat',$glcs_stat);
			$tl->assign('jml_prod',$jml_produksi);
			$tl->assign('hasil_detail',$row);
		}
		else if($stat=="view")
		{
		  $hasil3 = $db->query("SELECT (SUM(HRS_USED))/8 AS JAM,
								       ((SUM(MIN_USED))/60)/8 AS MENIT
    							   FROM GLC_DETAIL_REAL_SHIFT 
								   WHERE ID_REQ = '$id_reqs' 
								   AND ID_ALAT = '$glc'
								   AND TARIF = 'Y'")->fetchRow();
          
		  $hasil4 = $db->query("SELECT TO_CHAR(MIN(MULAI),'DD-MM-YYYY HH24:MI:SS') AS START_WORK,
								   TO_CHAR(MAX(SELESAI),'DD-MM-YYYY HH24:MI:SS') AS END_WORK
    							   FROM GLC_DETAIL_REAL_SHIFT 
								   WHERE ID_REQ = '$id_reqs' 
								   AND ID_ALAT = '$glc'")->fetchRow();
				
		  $start_work = $hasil4['START_WORK'];
		  $end_work = $hasil4['END_WORK'];
		  $jumlah_shift = $hasil3['JAM']+$hasil3['MENIT'];
		
		  if($start_work==NULL)
		  {
				$start_work = "-";
				$end_work = "-";
				$jumlah_shift = "-";
				
				$tl->assign('glc',$glc);
				$tl->assign('strt',$start_work);
				$tl->assign('end',$end_work);
				$tl->assign('shift',$jumlah_shift);	
		   }
		   else
		   {			
				$tl->assign('glc',$glc);
				$tl->assign('strt',$start_work);
				$tl->assign('end',$end_work);
				$tl->assign('shift',$jumlah_shift);
		   }
		}
	
	$tl->assign('status_form',$stat);
	$tl->assign('stat_time',$status_time);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);		
	$tl->renderToScreen();
	
?>