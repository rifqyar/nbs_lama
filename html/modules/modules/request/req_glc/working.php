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
			$row = $db->query("SELECT ID_ALAT,
									  TO_CHAR(TGL_SHIFT,'DD-MM-YYYY') AS TGL_SHIFT,
									  NO_SHIFT,
									  TO_CHAR(MULAI,'HH24:MI:SS') AS MULAI, 
				                      TO_CHAR(SELESAI,'HH24:MI:SS') AS SELESAI,
									  STAT_TIME,
									  KETERANGAN,
									  HRS_USED,
									  MIN_USED,
									  ID_DETAILS 
								   FROM GLC_DETAIL_REAL_SHIFT
								   WHERE ID_REQ = '$id_reqs'
								   AND ID_ALAT = '$glc'
								   ORDER BY ID_DETAILS")->getAll();
							 
				$a=0;
				foreach($row as $r)
				{	
                    $id_produksi[$a] = $r['ID_DETAILS'];				
					$box_ton[$a] = $db->query("SELECT SUM(JUMLAH_CONT) AS JML_BOX_TON
											   FROM GLC_PRODUKSI 
											   WHERE ID_REQ = '$id_reqs'
											   AND ID_DETAILS = '$id_produksi[$a]'")->fetchRow();
											   
					$jumlah_prod[$a] = $box_ton[$a]['JML_BOX_TON'];
					
					if($jumlah_prod[$a]!=NULL)
					{
						$jml_produksi[$a]=$jumlah_prod[$a];
					}
					else
					{
						$jml_produksi[$a]="0";
					}
				  $a++;
				}
			
			if($row!=NULL)
			{
				$trafik_box = $db->query("SELECT SUM(JUMLAH_CONT) AS JML_BOX
												   FROM GLC_PRODUKSI A, GLC_DETAIL_REAL_SHIFT B
												   WHERE A.ID_REQ = '$id_reqs'
												   AND A.ID_REQ = B.ID_REQ
												   AND A.ID_DETAILS = B.ID_DETAILS
												   AND B.ID_ALAT = '$glc'")->fetchRow();
				if($trafik_box['JML_BOX']==NULL)
				{
					$trafik_bm = "0";
				}
				else
				{
					$trafik_bm = $trafik_box['JML_BOX'];
				}
				
				$mch = $db->query("SELECT ROUND(($trafik_bm)/((SUM(SELISIH))*24)) AS MCH
												   FROM GLC_DETAIL_REAL_SHIFT 
												   WHERE ID_REQ = '$id_reqs'
												   AND ID_ALAT = '$glc'
												   AND STAT_TIME = 'WORKING'")->fetchRow();
		        $jml_mch = $mch['MCH'];
				
				$lama_pakai_glc = $db->query("SELECT MOD(ROUND((SUM(SELISIH))*24),24) AS JAM_PAKAI,
													 MOD(ROUND((SUM(SELISIH))*1440),60) AS MENIT_PAKAI
												   FROM GLC_DETAIL_REAL_SHIFT 
												   WHERE ID_REQ = '$id_reqs'
												   AND ID_ALAT = '$glc'")->fetchRow();
				$lama_glc_jam = $lama_pakai_glc['JAM_PAKAI'];
				$lama_glc_menit = $lama_pakai_glc['MENIT_PAKAI'];
				if(($lama_glc_jam==0)&&($lama_glc_menit==0))
				{
					$lama = "-";
				}
				else if($lama_glc_jam==0)
				{
					$lama = $lama_glc_menit." menit";
				}
				else if($lama_glc_menit==0)
				{
					$lama = $lama_glc_jam." jam";
				}
				else
				{
					$lama = $lama_glc_jam." jam ".$lama_glc_menit." menit";
				}
				
				$lama_real = $db->query("SELECT MOD(ROUND((SUM(SELISIH))*24),24) AS JAM_REAL,
											    MOD(ROUND((SUM(SELISIH))*1440),60) AS MENIT_REAL
												   FROM GLC_DETAIL_REAL_SHIFT 
												   WHERE ID_REQ = '$id_reqs'
												   AND ID_ALAT = '$glc'
												   AND STAT_TIME = 'WORKING'")->fetchRow();
				$lama_real_jam = $lama_real['JAM_REAL'];
				$lama_real_menit = $lama_real['MENIT_REAL'];
				if(($lama_real_jam==0)&&($lama_real_menit==0))
				{
					$real = "-";
				}
				else if($lama_real_jam==0)
				{
					$real = $lama_real_menit." menit";
				}
				else if($lama_real_menit==0)
				{
					$real = $lama_real_jam." jam";
				} 
				else
				{
					$real = $lama_real_jam." jam ".$lama_real_menit." menit";
				}				
				
				$lama_idle = $db->query("SELECT MOD(ROUND((SUM(SELISIH))*24),24) AS JAM_IDLE,
											    MOD(ROUND((SUM(SELISIH))*1440),60) AS MENIT_IDLE
												   FROM GLC_DETAIL_REAL_SHIFT 
												   WHERE ID_REQ = '$id_reqs'
												   AND ID_ALAT = '$glc'
												   AND STAT_TIME = 'IDLE'")->fetchRow();
				$lama_idle_jam = $lama_idle['JAM_IDLE'];
				$lama_idle_menit = $lama_idle['MENIT_IDLE'];
				 if(($lama_idle_jam==0)&&($lama_idle_menit==0))
				{
					$idle = "-";
				}
				else if($lama_idle_jam==0)
				{
					$idle = $lama_idle_menit." menit";
				}
				else if($lama_idle_menit==0)
				{
					$idle = $lama_idle_jam." jam";
				}
				else
				{
					$idle = $lama_idle_jam." jam ".$lama_idle_menit." menit";
				}
				
				$lama_not = $db->query("SELECT MOD(ROUND((SUM(SELISIH))*24),24) AS JAM_NOT,
											    MOD(ROUND((SUM(SELISIH))*1440),60) AS MENIT_NOT
												   FROM GLC_DETAIL_REAL_SHIFT 
												   WHERE ID_REQ = '$id_reqs'
												   AND ID_ALAT = '$glc'
												   AND STAT_TIME = 'NOT OPERATION'")->fetchRow();
				$lama_not_jam = $lama_not['JAM_NOT'];
				$lama_not_menit = $lama_not['MENIT_NOT'];
				if(($lama_not_jam==0)&&($lama_not_menit==0))
				{
					$not = "-";
				}
				else if($lama_not_jam==0)
				{
					$not = $lama_not_menit." menit";
				}
				else if($lama_not_menit==0)
				{
					$not = $lama_not_jam." jam";
				}
				else 
				{
					$not = $lama_not_jam." jam ".$lama_not_menit." menit";
				}
			
				$tl->assign('trfk',$trafik_bm);
				$tl->assign('lm',$lama);
				$tl->assign('rl',$real);
				$tl->assign('idl',$idle);
				$tl->assign('mch',$jml_mch);
				$tl->assign('nt',$not);
				
			}
			else
			{
				$trafik_bm = "";
				$lama = "";
				$real = "";
				$idle = "";
				$jml_mch = "";
				$not = "";
				
				$tl->assign('trfk',$trafik_bm);
				$tl->assign('lm',$lama);
				$tl->assign('rl',$real);
				$tl->assign('idl',$idle);
				$tl->assign('mch',$jml_mch);
				$tl->assign('nt',$not);
			}
			
			//print_r($jumlah_header);die;
			//print_r($jml_produksi);die;
			
			$tl->assign('jml_prod',$jml_produksi);
			$tl->assign('hasil_detail',$row);
			$tl->assign('id_req',$id_reqs);			
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