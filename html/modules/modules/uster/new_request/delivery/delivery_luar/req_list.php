<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl =  xliteTemplate('req_list.htm');

        $cari	= $_POST["cari"];
	$no_req	= $_POST["no_req"]; 
	$from   = $_POST["from"];
	$to     = $_POST["to"];
        $id_yard    = 	$_SESSION["IDYARD_STORAGE"];
	
        $db = getDB("storage");
        
            	if(isset($_POST["cari"]) ) 
            	{   
                                        if((isset($_POST["no_req"])) && ($from == NULL) && ($to == NULL))
                                        {
                                                $query_list = "select * from ( SELECT  a.NO_REQUEST, a.TGL_REQUEST, a.TGL_REQUEST_DELIVERY,a.NOTA, a.KOREKSI,
                                      b.NM_PBM AS NAMA_EMKL,
                                      COUNT(c.NO_CONTAINER) JUMLAH
                                FROM REQUEST_DELIVERY a,
                                     V_MST_PBM b,
                                     CONTAINER_DELIVERY c
                                WHERE a.DELIVERY_KE = 'LUAR'
                                AND a.KD_EMKL = b.KD_PBM 
                                AND a.NO_REQUEST = c.NO_REQUEST
								 AND a.NO_REQUEST LIKE '%$no_req%'
								 and A.perp_dari is null
							   AND a.PERALIHAN NOT IN ('RELOKASI','STUFFING','STRIPPING') 
                                GROUP BY a.NO_REQUEST, a.TGL_REQUEST, a.TGL_REQUEST_DELIVERY,a.NOTA, a.KOREKSI,
                                      b.NM_PBM
                                ORDER BY a.TGL_REQUEST DESC) where rownum <= 20";

                                        }
                                        else if(($no_req == NULL) && (isset($_POST["from"])) && (isset($_POST["to"])))
                                        {
                                                $query_list = "select * from ( SELECT  a.NO_REQUEST, a.TGL_REQUEST, a.TGL_REQUEST_DELIVERY,a.NOTA, a.KOREKSI,
                                      b.NM_PBM AS NAMA_EMKL,
                                      COUNT(c.NO_CONTAINER) JUMLAH
                                FROM REQUEST_DELIVERY a,
                                     V_MST_PBM b,
                                     CONTAINER_DELIVERY c
                                WHERE a.DELIVERY_KE = 'LUAR'
                                AND a.KD_EMKL = b.KD_PBM 
                                AND a.NO_REQUEST = c.NO_REQUEST
								and A.perp_dari is null
								AND a.TGL_REQUEST_DELIVERY BETWEEN TO_DATE('$from','yyyy/mm/dd') AND TO_DATE('$to','yyyy/mm/dd')
							   AND a.PERALIHAN NOT IN ('RELOKASI','STUFFING','STRIPPING') 
                                GROUP BY a.NO_REQUEST, a.TGL_REQUEST, a.TGL_REQUEST_DELIVERY,a.NOTA, a.KOREKSI,
                                      b.NM_PBM
                                ORDER BY a.TGL_REQUEST DESC) where rownum <= 20";

                                        } else if((isset($_POST["no_req"]))&& (isset($_POST["from"])) && (isset($_POST["to"])))
                                        {
                                                $query_list = "select * from ( SELECT  a.NO_REQUEST, a.TGL_REQUEST, a.TGL_REQUEST_DELIVERY,a.NOTA, a.KOREKSI,
                                      b.NM_PBM AS NAMA_EMKL,
                                      COUNT(c.NO_CONTAINER) JUMLAH
                                FROM REQUEST_DELIVERY a,
                                      KAPAL_CABANG.MST_PBM b,
                                     CONTAINER_DELIVERY c
                                WHERE a.DELIVERY_KE = 'LUAR'
                                AND a.KD_EMKL = b.KD_PBM 
								AND b.KD_CABANG = '05' 								
                                AND a.NO_REQUEST = c.NO_REQUEST
								 AND a.NO_REQUEST = '$no_req'
								 and A.perp_dari is null
								AND a.TGL_REQUEST_DELIVERY BETWEEN TO_DATE('$from','yyyy/mm/dd') AND TO_DATE('$to','yyyy/mm/dd')
							   AND a.PERALIHAN NOT IN ('RELOKASI','STUFFING','STRIPPING') 
                                GROUP BY a.NO_REQUEST, a.TGL_REQUEST, a.TGL_REQUEST_DELIVERY,a.NOTA, a.KOREKSI,
                                      b.NM_PBM
                                ORDER BY a.TGL_REQUEST DESC) where rownum <= 20";
                        } 
                } else {
                                        $query_list     = "select * from ( SELECT  a.NO_REQUEST, a.TGL_REQUEST,a.NOTA, a.KOREKSI,
                                      b.NM_PBM AS NAMA_EMKL, count(c.no_container) jumlah                                
                                FROM REQUEST_DELIVERY a,
                                        container_delivery c,
                                     KAPAL_CABANG.MST_PBM b
                                WHERE a.DELIVERY_KE = 'LUAR'
                                and a.no_request = c.no_request
                                AND a.KD_EMKL = b.KD_PBM                                 
                                AND b.KD_CABANG = '05'  
                                and A.perp_dari is null
                                 AND a.PERALIHAN NOT IN ('RELOKASI','STUFFING','STRIPPING') 
                                GROUP BY a.NO_REQUEST, a.TGL_REQUEST,a.NOTA, a.KOREKSI,
                                      b.NM_PBM
                                ORDER BY a.TGL_REQUEST DESC)
								WHERE ROWNUM <= 100";
                } 

	
	/* $result_list	= $db->query($query_list);
	$row_list       = $result_list->getAll();  */
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
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
	
	function cek_nota($no_req)
	{
		$db 		= getDB("storage");
		$query_cek	= "SELECT NOTA, KOREKSI FROM REQUEST_DELIVERY WHERE NO_REQUEST = '$no_req'";	
		$result_cek	= $db->query($query_cek);
		$row_cek 	= $result_cek->getAll();
		
		if(count($row_cek) > 0)
		{
			$nota		= $row_cek[0]["NOTA"];
			$koreksi	= $row_cek[0]["KOREKSI"];
			
			//'print/print_pdf?no_req='.$no_req.'&no_nota='.$no_nota.
			// <a href="{$HOME}{$APPID}/view?no_req={$rows.NO_REQUEST}"> LIHAT </a>
			if(($row_cek[0]["NOTA"] <> 'Y') AND ($row_cek[0]["KOREKSI"] <> 'Y'))
			{
				echo '<a href="'.HOME.APPID.'/edit?no_req='.$no_req.'" target="_blank"> EDIT </a> ';
				//echo '<a href="'.HOME.APPID.'/cetak_nota?no_nota='.$no_nota.'&n='.$cetak.'" target="_blank"> CETAK ULANG </a> ';		
			}
			else if(($row_cek[0]["NOTA"] == 'Y') AND ($row_cek[0]["KOREKSI"] <> 'Y'))
			{
				echo '<a href="'.HOME.APPID.'/view?no_req='.$no_req.'" target="_blank" > Nota sudah cetak </a> ';
			}
			else if(($row_cek[0]["NOTA"] == 'Y') AND ($row_cek[0]["KOREKSI"] == 'Y'))
			{
				echo '<a href="'.HOME.APPID.'/view?no_req='.$no_req.'" target="_blank" > Nota sudah cetak </a> ';
				//echo '<a href="'.HOME.APPID.'/edit?no_req='.$no_req.'" target="_blank"> EDIT </a> ';						
			}
			else if(($row_cek[0]["NOTA"] <> 'Y') AND ($row_cek[0]["KOREKSI"] == 'Y'))
			{
				echo '<a href="'.HOME.APPID.'/edit?no_req='.$no_req.'" target="_blank"> EDIT </a> ';		
			}

	}
	}
        
        ?>