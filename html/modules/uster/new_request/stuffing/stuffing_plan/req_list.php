<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl =  xliteTemplate('req_list.htm');

//-----------------paging
/*
	if(isset($_GET["page"]))
	{
		$page = $_GET["page"];	
	}
	else
	{
		$page = 1;	
	}
*/
//------------------------	
	
	$db = getDB("storage");

	$from		= $_POST["FROM"]; 
	$to			= $_POST["TO"];
	$no_req		= $_POST["NO_REQ"];
	$id_yard	= $_SESSION["IDYARD_STORAGE"]; 	
	
	if(isset($_POST["CARI"]))
	{
		if((isset($_POST["NO_REQ"])) && ($from == NULL) && ($to == NULL))
		{
			$query_list = "SELECT * FROM (SELECT PLAN_REQUEST_STUFFING.TGL_REQUEST, PLAN_REQUEST_STUFFING.APPROVE,PLAN_REQUEST_STUFFING.NO_REQUEST,
							PLAN_REQUEST_STUFFING.NO_PEB, PLAN_REQUEST_STUFFING.NO_NPE,PLAN_REQUEST_STUFFING.NM_KAPAL, PLAN_REQUEST_STUFFING.VOYAGE,
                                     NVL (REQUEST_STUFFING.NO_REQUEST, 'blm di approve') NO_REQUEST_APP,
                                     REQUEST_STUFFING.NOTA, REQUEST_STUFFING.KOREKSI, emkl.NM_PBM AS NAMA_PEMILIK
                                FROM PLAN_REQUEST_STUFFING LEFT JOIN REQUEST_STUFFING ON  PLAN_REQUEST_STUFFING.NO_REQUEST =  REPLACE (REQUEST_STUFFING.NO_REQUEST,'S', 'P')
                                LEFT JOIN  V_MST_PBM emkl ON PLAN_REQUEST_STUFFING.KD_CONSIGNEE = emkl.KD_PBM AND emkl.KD_CABANG = '05'
                                LEFT JOIN PLAN_CONTAINER_STUFFING ON PLAN_REQUEST_STUFFING.NO_REQUEST = PLAN_CONTAINER_STUFFING.NO_REQUEST
							 WHERE PLAN_REQUEST_STUFFING.EARLY_STUFFING IS NULL	AND PLAN_REQUEST_STUFFING.NO_REQUEST = '$no_req'
                             GROUP BY PLAN_REQUEST_STUFFING.APPROVE, PLAN_REQUEST_STUFFING.NO_REQUEST,PLAN_REQUEST_STUFFING.NO_PEB, PLAN_REQUEST_STUFFING.NO_NPE,PLAN_REQUEST_STUFFING.NM_KAPAL, PLAN_REQUEST_STUFFING.VOYAGE,
                                     NVL (REQUEST_STUFFING.NO_REQUEST, 'blm di approve'), REQUEST_STUFFING.NOTA, REQUEST_STUFFING.KOREKSI, emkl.NM_PBM, PLAN_REQUEST_STUFFING.TGL_REQUEST,
                                     PLAN_REQUEST_STUFFING.KD_CONSIGNEE
                            ORDER BY PLAN_REQUEST_STUFFING.TGL_REQUEST DESC)";
		}
		else if((isset($_POST["FROM"]))&& (isset($_POST["TO"])) && ($no_req == NULL))
		{
			$query_list = "SELECT * FROM (SELECT PLAN_REQUEST_STUFFING.TGL_REQUEST, PLAN_REQUEST_STUFFING.APPROVE,PLAN_REQUEST_STUFFING.NO_REQUEST,
							PLAN_REQUEST_STUFFING.NO_PEB, PLAN_REQUEST_STUFFING.NO_NPE,PLAN_REQUEST_STUFFING.NM_KAPAL, PLAN_REQUEST_STUFFING.VOYAGE,
                                     NVL (REQUEST_STUFFING.NO_REQUEST, 'blm di approve') NO_REQUEST_APP,
                                     REQUEST_STUFFING.NOTA, REQUEST_STUFFING.KOREKSI, emkl.NM_PBM AS NAMA_PEMILIK
                                FROM PLAN_REQUEST_STUFFING LEFT JOIN REQUEST_STUFFING ON  PLAN_REQUEST_STUFFING.NO_REQUEST =  REPLACE (REQUEST_STUFFING.NO_REQUEST,'S', 'P')
                                LEFT JOIN  V_MST_PBM emkl ON PLAN_REQUEST_STUFFING.KD_CONSIGNEE = emkl.KD_PBM
                                LEFT JOIN PLAN_CONTAINER_STUFFING ON PLAN_REQUEST_STUFFING.NO_REQUEST = PLAN_CONTAINER_STUFFING.NO_REQUEST
							WHERE PLAN_REQUEST_STUFFING.TGL_REQUEST BETWEEN TO_DATE ( '$from', 'YYYY-MM-DD ')
                                 AND TO_DATE (  CONCAT('$to', '23:59:59'), 'YYYY-MM-DD HH24:MI:SS')
                             GROUP BY PLAN_REQUEST_STUFFING.APPROVE, PLAN_REQUEST_STUFFING.NO_REQUEST,PLAN_REQUEST_STUFFING.NO_PEB, PLAN_REQUEST_STUFFING.NO_NPE,PLAN_REQUEST_STUFFING.NM_KAPAL, PLAN_REQUEST_STUFFING.VOYAGE,
                                     NVL (REQUEST_STUFFING.NO_REQUEST, 'blm di approve'), REQUEST_STUFFING.NOTA, REQUEST_STUFFING.KOREKSI, emkl.NM_PBM, PLAN_REQUEST_STUFFING.TGL_REQUEST,
                                     PLAN_REQUEST_STUFFING.KD_CONSIGNEE
                            ORDER BY PLAN_REQUEST_STUFFING.TGL_REQUEST DESC)
							where rownum <= 20";
								
		} else if((isset($_POST["FROM"]))&& (isset($_POST["TO"])) && (isset($_POST["NO_REQ"])))
		{
			
			$query_list = "SELECT * FROM (SELECT PLAN_REQUEST_STUFFING.TGL_REQUEST, PLAN_REQUEST_STUFFING.APPROVE,PLAN_REQUEST_STUFFING.NO_REQUEST,
							PLAN_REQUEST_STUFFING.NO_PEB, PLAN_REQUEST_STUFFING.NO_NPE,PLAN_REQUEST_STUFFING.NM_KAPAL, PLAN_REQUEST_STUFFING.VOYAGE,
                                     NVL (REQUEST_STUFFING.NO_REQUEST, 'blm di approve') NO_REQUEST_APP,
                                     REQUEST_STUFFING.NOTA, REQUEST_STUFFING.KOREKSI, emkl.NM_PBM AS NAMA_PEMILIK
                                FROM PLAN_REQUEST_STUFFING LEFT JOIN REQUEST_STUFFING ON  PLAN_REQUEST_STUFFING.NO_REQUEST =  REPLACE (REQUEST_STUFFING.NO_REQUEST,'S', 'P')
                                LEFT JOIN  V_MST_PBM emkl ON PLAN_REQUEST_STUFFING.KD_CONSIGNEE = emkl.KD_PBM
                                LEFT JOIN PLAN_CONTAINER_STUFFING ON PLAN_REQUEST_STUFFING.NO_REQUEST = PLAN_CONTAINER_STUFFING.NO_REQUEST
							WHERE PLAN_REQUEST_STUFFING.NO_REQUEST = '$no_req'
								 AND PLAN_REQUEST_STUFFING.TGL_REQUEST BETWEEN TO_DATE ( '$from', 'YYYY-MM-DD ')
                                 AND TO_DATE (  CONCAT('$to', '23:59:59'), 'YYYY-MM-DD HH24:MI:SS')
                             GROUP BY PLAN_REQUEST_STUFFING.APPROVE, PLAN_REQUEST_STUFFING.NO_REQUEST,PLAN_REQUEST_STUFFING.NO_PEB, PLAN_REQUEST_STUFFING.NO_NPE,PLAN_REQUEST_STUFFING.NM_KAPAL, PLAN_REQUEST_STUFFING.VOYAGE,
                                     NVL (REQUEST_STUFFING.NO_REQUEST, 'blm di approve'), REQUEST_STUFFING.NOTA, REQUEST_STUFFING.KOREKSI, emkl.NM_PBM, PLAN_REQUEST_STUFFING.TGL_REQUEST,
                                     PLAN_REQUEST_STUFFING.KD_CONSIGNEE
                            ORDER BY PLAN_REQUEST_STUFFING.TGL_REQUEST DESC)
							WHERE rownum <= 20";
		}
		
	}
	else
	{
		$query_list		= "SELECT *
						  FROM (  SELECT PLAN_REQUEST_STUFFING.TGL_REQUEST,
						                 PLAN_REQUEST_STUFFING.APPROVE,
						                 PLAN_REQUEST_STUFFING.NO_REQUEST,
						                 PLAN_REQUEST_STUFFING.NO_PEB,
						                 PLAN_REQUEST_STUFFING.NO_NPE,
						                 PLAN_REQUEST_STUFFING.NM_KAPAL,
						                 PLAN_REQUEST_STUFFING.VOYAGE,
						                 NVL (REQUEST_STUFFING.NO_REQUEST, 'blm di approve')
						                    NO_REQUEST_APP,
						                 REQUEST_STUFFING.NOTA,
						                 REQUEST_STUFFING.KOREKSI,
						                 emkl.NM_PBM AS NAMA_PEMILIK
						            FROM PLAN_REQUEST_STUFFING
						                 LEFT JOIN REQUEST_STUFFING
						                    ON PLAN_REQUEST_STUFFING.NO_REQUEST_APP =
						                          REQUEST_STUFFING.NO_REQUEST
						                 LEFT JOIN V_MST_PBM emkl
						                    ON PLAN_REQUEST_STUFFING.KD_CONSIGNEE = emkl.KD_PBM
						                       AND emkl.KD_CABANG = '05'
						                 LEFT JOIN PLAN_CONTAINER_STUFFING
						                    ON PLAN_REQUEST_STUFFING.NO_REQUEST =
						                          PLAN_CONTAINER_STUFFING.NO_REQUEST
						           WHERE PLAN_REQUEST_STUFFING.EARLY_STUFFING IS NULL
						        GROUP BY PLAN_REQUEST_STUFFING.APPROVE,
						                 PLAN_REQUEST_STUFFING.NO_REQUEST,
						                 PLAN_REQUEST_STUFFING.NO_PEB,
						                 PLAN_REQUEST_STUFFING.NO_NPE,
						                 PLAN_REQUEST_STUFFING.NM_KAPAL,
						                 PLAN_REQUEST_STUFFING.VOYAGE,
						                 NVL (REQUEST_STUFFING.NO_REQUEST, 'blm di approve'),
						                 REQUEST_STUFFING.NOTA,
						                 REQUEST_STUFFING.KOREKSI,
						                 emkl.NM_PBM,
						                 PLAN_REQUEST_STUFFING.TGL_REQUEST,
						                 PLAN_REQUEST_STUFFING.KD_CONSIGNEE
						        ORDER BY PLAN_REQUEST_STUFFING.TGL_REQUEST DESC)
						        WHERE ROWNUM <=80";
	}
	#paging_function
	if(isset($_GET['pp'])){
		$pp = $_GET['pp'];
	}else{
		$pp = 1;
	}
	
	$item_per_page = 20;
	
	$totalNum = $db->query($query_list)->RecordCount();
	$maxPage   = ceil($totalNum / $item_per_page)-1; 
	if ($maxPage<0) $maxPage = 0;
		
	$page   = ( $pp <= $maxPage+1 && $pp > 0 )?$pp-1:0;
	$__offset = $page * $item_per_page;
	
	$rs 	= $db->selectLimit( $query_list,$__offset,$item_per_page );
	$rows 	= array();
	if ($rs && $rs->RecordCount()>0) {
		
		for ($__rowindex = 1 + $__offset; $row=$rs->FetchRow(); $__rowindex++) {
			$row["__no"] = $__rowindex;
			$rows[] = $row;
			
		}
		$rs->close();
	}
	$row_list = & $rows;
	
	$jumlah_req = "SELECT COUNT(NO_REQUEST) JUMLAH FROM PLAN_REQUEST_STUFFING WHERE REPLACE(NO_REQUEST,'P','S') NOT IN (SELECT NO_REQUEST FROM REQUEST_STUFFING) AND EARLY_STUFFING IS NULL ";
	$result_list2	= $db->query($jumlah_req);
	$jumlah_			= $result_list2->fetchRow(); 
	
	$jumlah       = $jumlah_['JUMLAH'];
	
	$total_req = "SELECT COUNT(DISTINCT REQUEST_STUFFING.NO_REQUEST) TOTAL FROM REQUEST_STUFFING, CONTAINER_STUFFING 
					WHERE REQUEST_STUFFING.NO_REQUEST= CONTAINER_STUFFING.NO_REQUEST AND
					TO_DATE(TGL_REQUEST,'dd/mm/yy') = TO_DATE(SYSDATE,'dd/mm/yy') AND EARLY_STUFFING IS NULL";
	$result_list3	= $db->query($total_req);
	$total_			= $result_list3->fetchRow(); 
	
	$total       = $total_['TOTAL'];
	
	
	$total_cont = "SELECT COUNT(NO_CONTAINER) TOTAL FROM REQUEST_STUFFING, CONTAINER_STUFFING
					WHERE REQUEST_STUFFING.NO_REQUEST= CONTAINER_STUFFING.NO_REQUEST AND CONTAINER_STUFFING.TGL_APPROVE IS NOT NULL AND 
					TO_DATE(TGL_REQUEST,'dd/mm/yy') = TO_DATE(SYSDATE,'dd/mm/yy') AND EARLY_STUFFING IS NULL";
	$result_cont	= $db->query($total_cont);
	$total_c			= $result_cont->fetchRow(); 
	
	$total_co       = $total_c['TOTAL'];
	
	
	/* $result_list	= $db->query($query_list);
	$row_list		= $result_list->getAll();  */
	## navigator
	#
	//echo $maxPage;die;
	if ($maxPage>0) {
		$multipage = true;
		
		## begin create nav
		$pages = array();
		for ($i=0;$i<=$maxPage;$i++)
			$pages[] = array($i+1,$i+1);
		$nav['pages'] = $pages;
				
		if ($page>0) {
			$nav['prev'] = array( 'label'=>'Prev', 'p'=>$page-1 );
		} else {
			$nav['prev'] = false;
		}
		
		if ($page < $maxPage) {
			$nav['next'] = array( 'label'=>'Next', 'p'=>$page+1 );
		} else {
			$nav['next'] = false;
		}
		## end create nav
		
		$navlist = $nav['pages'];
		$navpage = $page+1;

		if ($pp <= $maxPage) {
			$nextvisible 	= true;
			$navnext		= $nav['next'];
		}	
		if ($pp > 1) {
			$prevvisible	= true;
			$navprev		= $nav['prev'];
		}	
	}	
	
	$tl->assign("prevvisible",$prevvisible);	
	$tl->assign("navpage",$navpage);	
	$tl->assign("navlist",$navlist);	
	$tl->assign("nextvisible",$nextvisible);	
	$tl->assign("multipage",$multipage);	
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("jumlah",$jumlah);
	$tl->assign("total",$total);
	$tl->assign("total_co",$total_co);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
	
	
	function cek_nota($no_req)
	{
		$db 		= getDB("storage");
		$query_cek	= "SELECT NOTA, KOREKSI, LUNAS FROM request_stuffing LEFT JOIN nota_stuffing ON request_stuffing.NO_REQUEST = nota_stuffing.NO_REQUEST WHERE request_stuffing.NO_REQUEST = REPLACE ('$no_req','P', 'S')";	
		$result_cek	= $db->query($query_cek);
		$row_cek 	= $result_cek->fetchRow();
		$nota		= $row_cek["NOTA"];
		$koreksi	= $row_cek["KOREKSI"];
		$lunas    	= $row_cek["LUNAS"];
			//echo "SELECT NOTA, KOREKSI FROM request_stuffing WHERE NO_REQUEST = REPLACE ('$no_req','P', 'S')";
			//'print/print_pdf?no_req='.$no_req.'&no_nota='.$no_nota.
			// <a href="{$HOME}{$APPID}/view?no_req={$rows.NO_REQUEST}"> LIHAT </a>
		$r_cek_ap_ = $db->query("SELECT COUNT(TGL_APPROVE) jumlah_app FROM PLAN_CONTAINER_STRIPPING WHERE NO_REQUEST = '$no_req_c'");
		$r_cek_ap = $r_cek_ap_->fetchRow();
		$jum_ap = $r_cek_ap["jumlah_app"];
		$r_cek_count_ = $db->query("SELECT COUNT(NO_CONTAINER) jumlah_cont FROM PLAN_CONTAINER_STRIPPING WHERE NO_REQUEST = '$no_req_c'");
		$r_cek_count = $r_cek_count_->fetchRow();
		$jum_cont = $r_cek_count["jumlah_cont"];
		
        if($lunas == 'NO'){
               echo '<a href="'.HOME.APPID.'/view?no_req='.$no_req.'" target="_blank"><blink style="color:red"><b> EDIT </b></blink></a> ';
        }
        else{
			if(($row_cek["NOTA"] <> 'Y') AND ($row_cek["KOREKSI"] <> 'Y'))
			{
				echo '<a href="'.HOME.APPID.'/view?no_req='.$no_req.'" target="_blank"><blink style="color:red"><b> EDIT </b></blink></a> ';
	
				//echo '<a href="'.HOME.APPID.'/cetak_nota?no_nota='.$no_nota.'&n='.$cetak.'" target="_blank"> CETAK ULANG </a> ';		
			}
			else if(($row_cek["NOTA"] == NULL) AND ($row_cek["KOREKSI"] == NULL))
			{
					echo '<a href="'.HOME.APPID.'/view?no_req='.$no_req.'" target="_blank"> <blink style="color:red"><b> EDIT </b></blink></a> ';
		
			}
			else if(($row_cek["NOTA"] == 'Y') AND ($row_cek["KOREKSI"] <> 'Y'))
			{
				echo '<a href="'.HOME.APPID.'/overview?no_req='.$no_req.'" target="_blank" > Nota sudah cetak </a> ';
			}
			else if(($row_cek["NOTA"] == 'Y') AND ($row_cek["KOREKSI"] == 'Y'))
			{
				echo '<a href="'.HOME.APPID.'/overview?no_req='.$no_req.'" target="_blank" > Nota sudah cetak </a> ';	
			}
			else if(($row_cek["NOTA"] <> 'Y') AND ($row_cek["KOREKSI"] == 'Y'))
			{
				echo '<a href="'.HOME.APPID.'/view?no_req='.$no_req.'" target="_blank"><blink style="color:red"><b> EDIT </b></blink> </a> ';
		
			}
        }
	}
?>
