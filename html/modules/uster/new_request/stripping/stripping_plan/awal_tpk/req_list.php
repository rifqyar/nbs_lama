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
    $no_req     = substr($no_req, 1);
	
	if(isset($_POST["CARI"]))
	{
		if($_POST["NO_REQ"] != NULL && $_POST["FROM"] == NULL && $_POST["TO"] == NULL)
		{
			$query_list		= "SELECT * FROM (SELECT PLAN_REQUEST_STRIPPING.TGL_REQUEST, PLAN_REQUEST_STRIPPING.APPROVE,PLAN_REQUEST_STRIPPING.NO_REQUEST, PLAN_REQUEST_STRIPPING.TYPE_STRIPPING,PLAN_REQUEST_STRIPPING.NO_DO, PLAN_REQUEST_STRIPPING.NO_BL,
                                     NVL (REQUEST_STRIPPING.NO_REQUEST, 'blm di approve') NO_REQUEST_APP,
                                     REQUEST_STRIPPING.NOTA, REQUEST_STRIPPING.KOREKSI,
                                     emkl.NM_PBM AS NAMA_PEMILIK
                                FROM PLAN_REQUEST_STRIPPING LEFT JOIN REQUEST_STRIPPING ON REQUEST_STRIPPING.NO_REQUEST =  REPLACE (PLAN_REQUEST_STRIPPING.NO_REQUEST,'P', 'S')
                                AND request_stripping.PERP_DARI IS NULL
                                LEFT JOIN PLAN_CONTAINER_STRIPPING ON PLAN_REQUEST_STRIPPING.NO_REQUEST = PLAN_CONTAINER_STRIPPING.NO_REQUEST
                                LEFT JOIN V_MST_PBM emkl ON PLAN_REQUEST_STRIPPING.KD_CONSIGNEE = emkl.KD_PBM AND emkl.KD_CABANG = '05'                                                               
                             GROUP BY PLAN_REQUEST_STRIPPING.APPROVE,PLAN_REQUEST_STRIPPING.TYPE_STRIPPING, PLAN_REQUEST_STRIPPING.NO_REQUEST,PLAN_REQUEST_STRIPPING.NO_DO, PLAN_REQUEST_STRIPPING.NO_BL,
                                     NVL (REQUEST_STRIPPING.NO_REQUEST, 'blm di approve'),
                                     REQUEST_STRIPPING.NOTA, REQUEST_STRIPPING.KOREKSI,
                                     emkl.NM_PBM, PLAN_REQUEST_STRIPPING.TGL_REQUEST,PLAN_REQUEST_STRIPPING.KD_CONSIGNEE
                                     ORDER BY NO_REQUEST DESC
                            )
                            WHERE NO_REQUEST LIKE '%$no_req%' ";
							
										/* $query_list = " select b.TGL_REQUEST, b.APPROVE,b.NO_REQUEST, b.TYPE_STRIPPING,b.NO_DO, b.NO_BL,
                                     NVL (a.NO_REQUEST, 'blm di approve') NO_REQUEST_APP,
                                     a.NOTA, a.KOREKSI,  d.NM_PBM AS NAMA_PEMILIK
                                     from request_stripping a, plan_request_stripping b, plan_container_stripping c, V_MST_PBM d
                                     where a.NO_REQUEST(+) =  REPLACE (b.NO_REQUEST,'P', 'S')
                                    and  b.NO_REQUEST = c.NO_REQUEST
                                    and  b.KD_CONSIGNEE = d.KD_PBM
                                    and   a.PERP_DARI IS NULL
									AND b.NO_REQUEST LIKE '%$no_req%'
                             GROUP BY b.APPROVE,b.TYPE_STRIPPING, b.NO_REQUEST,b.NO_DO, b.NO_BL,
                                     NVL (a.NO_REQUEST, 'blm di approve'),
                                     a.NOTA, a.KOREKSI,
                                    d.NM_PBM, b.TGL_REQUEST,b.KD_CONSIGNEE,a.tgl_request
                           order by a.tgl_request DESC"; */
		}
		else if ($_POST["NO_REQ"] == NULL && $_POST["FROM"] != NULL && $_POST["TO"] != NULL)
		{
			$query_list		= "SELECT * FROM (SELECT PLAN_REQUEST_STRIPPING.TGL_REQUEST, PLAN_REQUEST_STRIPPING.APPROVE,PLAN_REQUEST_STRIPPING.NO_REQUEST, PLAN_REQUEST_STRIPPING.TYPE_STRIPPING,PLAN_REQUEST_STRIPPING.NO_DO, PLAN_REQUEST_STRIPPING.NO_BL,
                                     NVL (REQUEST_STRIPPING.NO_REQUEST, 'blm di approve') NO_REQUEST_APP,
                                     REQUEST_STRIPPING.NOTA, REQUEST_STRIPPING.KOREKSI,
                                     emkl.NM_PBM AS NAMA_PEMILIK
                                FROM PLAN_REQUEST_STRIPPING LEFT JOIN REQUEST_STRIPPING ON REQUEST_STRIPPING.NO_REQUEST =  REPLACE (PLAN_REQUEST_STRIPPING.NO_REQUEST,'P', 'S')
                                AND request_stripping.PERP_DARI IS NULL
                                LEFT JOIN PLAN_CONTAINER_STRIPPING ON PLAN_REQUEST_STRIPPING.NO_REQUEST = PLAN_CONTAINER_STRIPPING.NO_REQUEST
                                LEFT JOIN V_MST_PBM emkl ON PLAN_REQUEST_STRIPPING.KD_CONSIGNEE = emkl.KD_PBM AND emkl.KD_CABANG = '05'                                                             
                             GROUP BY PLAN_REQUEST_STRIPPING.APPROVE,PLAN_REQUEST_STRIPPING.TYPE_STRIPPING, PLAN_REQUEST_STRIPPING.NO_REQUEST,PLAN_REQUEST_STRIPPING.NO_DO, PLAN_REQUEST_STRIPPING.NO_BL,
                                     NVL (REQUEST_STRIPPING.NO_REQUEST, 'blm di approve'),
                                     REQUEST_STRIPPING.NOTA, REQUEST_STRIPPING.KOREKSI,
                                     emkl.NM_PBM, PLAN_REQUEST_STRIPPING.TGL_REQUEST,PLAN_REQUEST_STRIPPING.KD_CONSIGNEE
                                     ORDER BY NO_REQUEST DESC
                            ) WHERE TGL_REQUEST BETWEEN TO_DATE ( '$from', 'YYYY-MM-DD ') AND TO_DATE ( '$to', 'YYYY-MM-DD ')";
							
						/* $query_list = " select b.TGL_REQUEST, b.APPROVE,b.NO_REQUEST, b.TYPE_STRIPPING,b.NO_DO, b.NO_BL,
                                     NVL (a.NO_REQUEST, 'blm di approve') NO_REQUEST_APP,
                                     a.NOTA, a.KOREKSI,  d.NM_PBM AS NAMA_PEMILIK
                                     from request_stripping a, plan_request_stripping b, plan_container_stripping c, V_MST_PBM d
                                     where a.NO_REQUEST(+) =  REPLACE (b.NO_REQUEST,'P', 'S')
                                    and  b.NO_REQUEST = c.NO_REQUEST
                                    and  b.KD_CONSIGNEE = d.KD_PBM
                                    and   a.PERP_DARI IS NULL
								AND b.TGL_REQUEST BETWEEN TO_DATE ( '$from', 'YYYY-MM-DD ') AND TO_DATE ( '$to', 'YYYY-MM-DD ') 
                             GROUP BY b.APPROVE,b.TYPE_STRIPPING, b.NO_REQUEST,b.NO_DO, b.NO_BL,
                                     NVL (a.NO_REQUEST, 'blm di approve'),
                                     a.NOTA, a.KOREKSI,
                                    d.NM_PBM, b.TGL_REQUEST,b.KD_CONSIGNEE,a.tgl_request
                           order by a.tgl_request DESC"; */
							
		} else if ($_POST["NO_REQ"] != NULL && $_POST["FROM"] != NULL && $_POST["TO"] != NULL)
		{
			$query_list		= "  SELECT * FROM (SELECT PLAN_REQUEST_STRIPPING.TGL_REQUEST, PLAN_REQUEST_STRIPPING.APPROVE,PLAN_REQUEST_STRIPPING.NO_REQUEST, PLAN_REQUEST_STRIPPING.TYPE_STRIPPING,PLAN_REQUEST_STRIPPING.NO_DO, PLAN_REQUEST_STRIPPING.NO_BL,
                                     NVL (REQUEST_STRIPPING.NO_REQUEST, 'blm di approve') NO_REQUEST_APP,
                                     REQUEST_STRIPPING.NOTA, REQUEST_STRIPPING.KOREKSI,
                                     emkl.NM_PBM AS NAMA_PEMILIK
                                FROM PLAN_REQUEST_STRIPPING LEFT JOIN REQUEST_STRIPPING ON REQUEST_STRIPPING.NO_REQUEST =  REPLACE (PLAN_REQUEST_STRIPPING.NO_REQUEST,'P', 'S')
                                AND request_stripping.PERP_DARI IS NULL
                                LEFT JOIN PLAN_CONTAINER_STRIPPING ON PLAN_REQUEST_STRIPPING.NO_REQUEST = PLAN_CONTAINER_STRIPPING.NO_REQUEST
                                LEFT JOIN V_MST_PBM emkl ON PLAN_REQUEST_STRIPPING.KD_CONSIGNEE = emkl.KD_PBM AND emkl.KD_CABANG = '05'                                                              
                             GROUP BY PLAN_REQUEST_STRIPPING.APPROVE,PLAN_REQUEST_STRIPPING.TYPE_STRIPPING, PLAN_REQUEST_STRIPPING.NO_REQUEST,PLAN_REQUEST_STRIPPING.NO_DO, PLAN_REQUEST_STRIPPING.NO_BL,
                                     NVL (REQUEST_STRIPPING.NO_REQUEST, 'blm di approve'),
                                     REQUEST_STRIPPING.NOTA, REQUEST_STRIPPING.KOREKSI,
                                     emkl.NM_PBM, PLAN_REQUEST_STRIPPING.TGL_REQUEST,PLAN_REQUEST_STRIPPING.KD_CONSIGNEE
                                     ORDER BY NO_REQUEST DESC
                            ) WHERE NO_REQUEST LIKE '%$no_req%' AND TGL_REQUEST BETWEEN TO_DATE ( '$from', 'YYYY-MM-DD ') AND TO_DATE ( '$to', 'YYYY-MM-DD ')";
			/* $query_list = " select b.TGL_REQUEST, b.APPROVE,b.NO_REQUEST, b.TYPE_STRIPPING,b.NO_DO, b.NO_BL,
                                     NVL (a.NO_REQUEST, 'blm di approve') NO_REQUEST_APP,
                                     a.NOTA, a.KOREKSI,  d.NM_PBM AS NAMA_PEMILIK
                                     from request_stripping a, plan_request_stripping b, plan_container_stripping c, V_MST_PBM d
                                     where a.NO_REQUEST(+) =  REPLACE (b.NO_REQUEST,'P', 'S')
                                    and  b.NO_REQUEST = c.NO_REQUEST
                                    and  b.KD_CONSIGNEE = d.KD_PBM
                                    and   a.PERP_DARI IS NULL
									AND b.NO_REQUEST LIKE '%$no_req%'
								AND b.TGL_REQUEST BETWEEN TO_DATE ( '$from', 'YYYY-MM-DD ') AND TO_DATE ( '$to', 'YYYY-MM-DD ') 
                             GROUP BY b.APPROVE,b.TYPE_STRIPPING, b.NO_REQUEST,b.NO_DO, b.NO_BL,
                                     NVL (a.NO_REQUEST, 'blm di approve'),
                                     a.NOTA, a.KOREKSI,
                                    d.NM_PBM, b.TGL_REQUEST,b.KD_CONSIGNEE,a.tgl_request
                           order by a.tgl_request DESC"; */
		}
		else{
            $query_list		= "SELECT * FROM (SELECT PLAN_REQUEST_STRIPPING.TGL_REQUEST, PLAN_REQUEST_STRIPPING.APPROVE,PLAN_REQUEST_STRIPPING.NO_REQUEST, PLAN_REQUEST_STRIPPING.TYPE_STRIPPING,PLAN_REQUEST_STRIPPING.NO_DO, PLAN_REQUEST_STRIPPING.NO_BL,
                                     NVL (REQUEST_STRIPPING.NO_REQUEST, 'blm di approve'), NVL(REQUEST_STRIPPING.CLOSING, 'Blm di Approve') NO_REQUEST_APP
                                     REQUEST_STRIPPING.NOTA, REQUEST_STRIPPING.KOREKSI,
                                     emkl.NM_PBM AS NAMA_PEMILIK
                                FROM PLAN_REQUEST_STRIPPING LEFT JOIN REQUEST_STRIPPING ON REQUEST_STRIPPING.NO_REQUEST =  REPLACE (PLAN_REQUEST_STRIPPING.NO_REQUEST,'P', 'S')
								AND request_stripping.PERP_DARI IS NULL
								LEFT JOIN PLAN_CONTAINER_STRIPPING ON PLAN_REQUEST_STRIPPING.NO_REQUEST = PLAN_CONTAINER_STRIPPING.NO_REQUEST
								LEFT JOIN V_MST_PBM emkl ON PLAN_REQUEST_STRIPPING.KD_CONSIGNEE = emkl.KD_PBM AND emkl.KD_CABANG = '05'															
                             GROUP BY PLAN_REQUEST_STRIPPING.APPROVE,PLAN_REQUEST_STRIPPING.TYPE_STRIPPING, PLAN_REQUEST_STRIPPING.NO_REQUEST,PLAN_REQUEST_STRIPPING.NO_DO, PLAN_REQUEST_STRIPPING.NO_BL,
                                     NVL (REQUEST_STRIPPING.NO_REQUEST, 'blm di approve'),
                                     REQUEST_STRIPPING.NOTA, REQUEST_STRIPPING.KOREKSI,
                                     emkl.NM_PBM, PLAN_REQUEST_STRIPPING.TGL_REQUEST,PLAN_REQUEST_STRIPPING.KD_CONSIGNEE
									 ORDER BY NO_REQUEST DESC
                            )
							WHERE ROWNUM <= 20
							";
				/* $query_list = " select b.TGL_REQUEST, b.APPROVE,b.NO_REQUEST, b.TYPE_STRIPPING,b.NO_DO, b.NO_BL,
                                     NVL (a.NO_REQUEST, 'blm di approve') NO_REQUEST_APP,
                                     a.NOTA, a.KOREKSI,  d.NM_PBM AS NAMA_PEMILIK
                                     from request_stripping a, plan_request_stripping b, plan_container_stripping c, V_MST_PBM d
                                     where a.NO_REQUEST(+) =  REPLACE (b.NO_REQUEST,'P', 'S')
                                    and  b.NO_REQUEST = c.NO_REQUEST
                                    and  b.KD_CONSIGNEE = d.KD_PBM
                                    and   a.PERP_DARI IS NULL
									AND b.NO_REQUEST LIKE '%$no_req%'
								AND b.TGL_REQUEST BETWEEN TO_DATE ( '$from', 'YYYY-MM-DD ') AND TO_DATE ( '$to', 'YYYY-MM-DD ') 
                             GROUP BY b.APPROVE,b.TYPE_STRIPPING, b.NO_REQUEST,b.NO_DO, b.NO_BL,
                                     NVL (a.NO_REQUEST, 'blm di approve'),
                                     a.NOTA, a.KOREKSI,
                                    d.NM_PBM, b.TGL_REQUEST,b.KD_CONSIGNEE,a.tgl_request
                           order by a.tgl_request DESC"; */
		}
	
	}
	else
	{
		/*$query_list		= "  SELECT * FROM (SELECT distinct PLAN_REQUEST_STRIPPING.TGL_REQUEST, PLAN_REQUEST_STRIPPING.APPROVE,PLAN_REQUEST_STRIPPING.NO_REQUEST, PLAN_REQUEST_STRIPPING.TYPE_STRIPPING,PLAN_REQUEST_STRIPPING.NO_DO, PLAN_REQUEST_STRIPPING.NO_BL,
                                     NVL (REQUEST_STRIPPING.NO_REQUEST, 'blm di approve') NO_REQUEST_APP, NVL(REQUEST_STRIPPING.CLOSING, 'Blm di Approve') STATUS_REQ,
                                     REQUEST_STRIPPING.NOTA, REQUEST_STRIPPING.KOREKSI,
                                     emkl.NM_PBM AS NAMA_PEMILIK
                                FROM PLAN_REQUEST_STRIPPING LEFT JOIN REQUEST_STRIPPING ON REQUEST_STRIPPING.NO_REQUEST =  REPLACE (PLAN_REQUEST_STRIPPING.NO_REQUEST,'P', 'S')
								AND request_stripping.PERP_DARI IS NULL
								LEFT JOIN PLAN_CONTAINER_STRIPPING ON PLAN_REQUEST_STRIPPING.NO_REQUEST = PLAN_CONTAINER_STRIPPING.NO_REQUEST
								LEFT JOIN V_MST_PBM emkl ON PLAN_REQUEST_STRIPPING.KD_CONSIGNEE = emkl.KD_PBM	AND emkl.KD_CABANG = '05'											
                             GROUP BY PLAN_REQUEST_STRIPPING.APPROVE,PLAN_REQUEST_STRIPPING.TYPE_STRIPPING, PLAN_REQUEST_STRIPPING.NO_REQUEST,PLAN_REQUEST_STRIPPING.NO_DO, PLAN_REQUEST_STRIPPING.NO_BL,
                                     NVL (REQUEST_STRIPPING.NO_REQUEST, 'blm di approve'),
									 NVL(REQUEST_STRIPPING.CLOSING, 'Blm di Approve'),
                                     REQUEST_STRIPPING.NOTA, REQUEST_STRIPPING.KOREKSI,									 
                                     emkl.NM_PBM, PLAN_REQUEST_STRIPPING.TGL_REQUEST,PLAN_REQUEST_STRIPPING.KD_CONSIGNEE
									 ORDER BY  TGL_REQUEST DESC
                            ) a
							WHERE a.TGL_REQUEST BETWEEN TRUNC(ADD_MONTHS(SYSDATE,-2),'MM') AND LAST_DAY(SYSDATE)
							";*/

        /*after tuning here*/ 

        $query_list = "SELECT DISTINCT
                             PLAN_REQUEST_STRIPPING.TGL_REQUEST,
                             PLAN_REQUEST_STRIPPING.APPROVE,
                             PLAN_REQUEST_STRIPPING.NO_REQUEST,
                             PLAN_REQUEST_STRIPPING.TYPE_STRIPPING,
                             PLAN_REQUEST_STRIPPING.NO_DO,
                             PLAN_REQUEST_STRIPPING.NO_BL,
                             NVL (REQUEST_STRIPPING.NO_REQUEST, 'blm di approve') NO_REQUEST_APP,
                             NVL (REQUEST_STRIPPING.CLOSING, 'Blm di Approve') STATUS_REQ,
                             REQUEST_STRIPPING.NOTA,
                             REQUEST_STRIPPING.KOREKSI,
                             emkl.NM_PBM AS NAMA_PEMILIK
                        FROM PLAN_REQUEST_STRIPPING
                             INNER JOIN V_MST_PBM emkl
                                ON PLAN_REQUEST_STRIPPING.KD_CONSIGNEE = emkl.KD_PBM
                                   AND emkl.KD_CABANG = '05'
                             LEFT JOIN REQUEST_STRIPPING
                                ON REQUEST_STRIPPING.NO_REQUEST =
                                      PLAN_REQUEST_STRIPPING.NO_REQUEST_APP_STRIPPING
                                   AND request_stripping.PERP_DARI IS NULL
                          WHERE PLAN_REQUEST_STRIPPING.TGL_REQUEST BETWEEN SYSDATE- INTERVAL '15' DAY AND LAST_DAY (SYSDATE)
                    ORDER BY PLAN_REQUEST_STRIPPING.TGL_REQUEST DESC";

		//echo $query_list;
		/* $query_list = "  SELECT * FROM (select b.TGL_REQUEST, b.APPROVE,b.NO_REQUEST, b.TYPE_STRIPPING,b.NO_DO, b.NO_BL,
                                     NVL (a.NO_REQUEST, 'blm di approve') NO_REQUEST_APP,
                                     a.NOTA, a.KOREKSI,  d.NM_PBM AS NAMA_PEMILIK
                                     from request_stripping a, plan_request_stripping b, plan_container_stripping c, V_MST_PBM d
                                     where a.NO_REQUEST(+) =  REPLACE (b.NO_REQUEST,'P', 'S')
                                    and  b.NO_REQUEST = c.NO_REQUEST
                                    and  b.KD_CONSIGNEE = d.KD_PBM
                                    and   a.PERP_DARI IS NULL
                             GROUP BY b.APPROVE,b.TYPE_STRIPPING, b.NO_REQUEST,b.NO_DO, b.NO_BL,
                                     NVL (a.NO_REQUEST, 'blm di approve'),
                                     a.NOTA, a.KOREKSI,
                                    d.NM_PBM, b.TGL_REQUEST,b.KD_CONSIGNEE,a.tgl_request
									order by b.tgl_request desc)
                           	WHERE ROWNUM <= 20
							ORDER BY TGL_REQUEST DESC
                            "; */
	}

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
	
	$jumlah_req = "SELECT COUNT(*) JUMLAH FROM (SELECT PLAN_REQUEST_STRIPPING.NO_REQUEST, COUNT(PLAN_CONTAINER_STRIPPING.NO_CONTAINER) BOX FROM PLAN_REQUEST_STRIPPING JOIN REQUEST_STRIPPING 
                ON REPLACE(PLAN_REQUEST_STRIPPING.NO_REQUEST,'P','S') = REQUEST_STRIPPING.NO_REQUEST
                LEFT JOIN PLAN_CONTAINER_STRIPPING ON PLAN_REQUEST_STRIPPING.NO_REQUEST = PLAN_CONTAINER_STRIPPING.NO_REQUEST
                WHERE REQUEST_STRIPPING.CLOSING IS NULL AND REQUEST_STRIPPING.TGL_REQUEST BETWEEN TRUNC(ADD_MONTHS(SYSDATE,-1),'MM') AND LAST_DAY(SYSDATE)
                GROUP BY PLAN_REQUEST_STRIPPING.NO_REQUEST) Q
                WHERE Q.BOX <> 0  ";
	$result_list2	= $db->query($jumlah_req);
	$jumlah_			= $result_list2->fetchRow(); 
	
	$jumlah       = $jumlah_['JUMLAH']; 
	
	$total_req = "SELECT COUNT(NO_REQUEST) TOTAL FROM REQUEST_STRIPPING WHERE TO_DATE(TGL_REQUEST,'dd/mm/yy') = TO_DATE(SYSDATE,'dd/mm/yy') AND CLOSING = 'CLOSED'";
	$result_list3	= $db->query($total_req);
	$total_			= $result_list3->fetchRow(); 
	
	$total       = $total_['TOTAL'];
	
	$total_cont = "SELECT COUNT(NO_CONTAINER) TOTAL FROM REQUEST_STRIPPING, CONTAINER_STRIPPING
					WHERE REQUEST_STRIPPING.NO_REQUEST = CONTAINER_STRIPPING.NO_REQUEST AND
					TO_DATE(TGL_REQUEST,'dd/mm/yy') = TO_DATE(SYSDATE,'dd/mm/yy') AND CLOSING = 'CLOSED' AND CONTAINER_STRIPPING.TGL_APPROVE IS NOT NULL";
	$result_cont	= $db->query($total_cont);
	$total_c			= $result_cont->fetchRow(); 
	
	$total_co       = $total_c['TOTAL'];
	
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
	
	/* $result_list	= $db->query($query_list);
	$row_list		= $result_list->getAll();  */
	
	$tl->assign("prevvisible",$prevvisible);	
	$tl->assign("navpage",$navpage);	
	$tl->assign("navlist",$navlist);	
	$tl->assign("nextvisible",$nextvisible);	
	$tl->assign("multipage",$multipage);	
	$tl->assign("row_list",$row_list);
	$tl->assign("jumlah",$jumlah);
	$tl->assign("total",$total);
	$tl->assign("total_co",$total_co);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
	
	/* function cek_list($no_request){
		$db 		= getDB("storage");
		$query_cek	= "SELECT NOTA, KOREKSI FROM REQUEST_STRIPPING WHERE NO_REQUEST = REPLACE ('$no_request','P', 'S')";	
		$result_cek	= $db->query($query_cek);
		$row_cek 	= $result_cek->fetchRow();
		$nota		= $row_cek["NOTA"];
		$koreksi	= $row_cek["KOREKSI"];
		$no_req_c = $no_request;
		
		$r_cek_ap_ = $db->query("SELECT COUNT(TGL_APPROVE) jumlah_app FROM PLAN_CONTAINER_STRIPPING WHERE NO_REQUEST = '$no_req_c'");
		$r_cek_ap = $r_cek_ap_->fetchRow();
		$jum_ap = $r_cek_ap["jumlah_app"];
		$r_cek_count_ = $db->query("SELECT COUNT(NO_CONTAINER) jumlah_cont FROM PLAN_CONTAINER_STRIPPING WHERE NO_REQUEST = '$no_req_c'");
		$r_cek_count = $r_cek_count_->fetchRow();
		$jum_cont = $r_cek_count["jumlah_cont"];
		
		if(($jum_ap == $jum_cont) AND ($row_cek["NOTA"] != 'Y') AND ($row_cek["KOREKSI"] != 'Y')){
			echo '<td class="grid-cell" style="font-size:14px;"> <a href="'.HOME.APPID.'/view?no_req='.$no_request.'" target="_blank">Request Approved</a> </td>';
		}
		else if(($jum_ap < $jum_cont) AND ($row_cek["NOTA"] == NULL) AND ($row_cek["KOREKSI"] == NULL)){
			echo '<td class="grid-cell" style="font-size:14px;"> <a href="'.HOME.APPID.'/view?no_req='.$no_request.'" target="_blank">EDIT</a> </td>';
		}
		else if(($jum_ap == $jum_cont) AND ($row_cek["NOTA"] == 'Y') AND ($row_cek["KOREKSI"] != 'Y')){
			echo '<td class="grid-cell" style="font-size:14px;"> <a href="'.HOME.APPID.'/overview?no_req='.$no_request.'" target="_blank">Nota Sudah Cetak</a> </td>';
		}
		else if(($jum_ap == $jum_cont) AND ($row_cek["NOTA"] <> 'Y') AND ($row_cek["KOREKSI"] == 'Y')){
			echo '<td class="grid-cell" style="font-size:14px;"> <a href="'.HOME.APPID.'/view?no_req='.$no_request.'" target="_blank">EDIT</a> </td>';
		}
	} */
	
?>
