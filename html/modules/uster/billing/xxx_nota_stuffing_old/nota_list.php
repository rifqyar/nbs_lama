<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl =  xliteTemplate('nota_list.htm');

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
	$id_yard = $_SESSION["IDYARD_STORAGE"];
        
        $cari	= $_POST["cari"];
		$no_req	= $_POST["no_req"]; 
		$from   = $_POST["from"];
		$to     = $_POST["to"];
        $no_nota  = $_POST["no_nota"];
        $id_yard  = $_SESSION["IDYARD_STORAGE"];
	
        
	$db = getDB("storage");

	 //if (($_SESSION["ID_ROLE"] == '1') OR ($_SESSION["ID_ROLE"] == '2')){
            	if(isset($_POST["cari"]) ) 
            	{   
                      if((isset($_POST["no_req"])) && ($from == NULL) && ($to == NULL))
					{
						$query_list     = "SELECT * FROM (SELECT   NVL (nota_stuffing.lunas, 0) lunas,
									         NVL (nota_stuffing.no_nota, '-') no_nota,
									         request_stuffing.no_request,
									         TO_CHAR (request_stuffing.tgl_request, 'dd/mm/yyyy') tgl_request,
									         emkl.nm_pbm AS nama_emkl, request_stuffing.voyage,
									         request_stuffing.nm_kapal nama_vessel, yard_area.nama_yard,
									         COUNT (container_stuffing.no_container) jml_cont
									    FROM request_stuffing,
									         nota_stuffing,
									         v_mst_pbm emkl,
									         yard_area,
									         container_stuffing
									   WHERE request_stuffing.kd_consignee = emkl.kd_pbm
									     AND request_stuffing.no_request = container_stuffing.no_request
									     AND request_stuffing.id_yard = yard_area.ID
									     AND nota_stuffing.no_request(+) = request_stuffing.no_request
									GROUP BY NVL (nota_stuffing.lunas, 0),
									         NVL (nota_stuffing.no_nota, '-'),
									         request_stuffing.no_request,
									         TO_CHAR (request_stuffing.tgl_request, 'dd/mm/yyyy'),
									         emkl.nm_pbm,
									         request_stuffing.voyage,
									         request_stuffing.nm_kapal,
									         yard_area.nama_yard
									ORDER BY request_stuffing.no_request DESC) q
									WHERE q.NO_REQUEST LIKE '%$no_req%'";
					}
					else if((isset($_POST["from"]))&& (isset($_POST["to"])) && ($no_req == NULL))
					{
						$query_list     = "SELECT * FROM (SELECT   NVL (nota_stuffing.lunas, 0) lunas,
									         NVL (nota_stuffing.no_nota, '-') no_nota,
									         request_stuffing.no_request,
									         TO_DATE (request_stuffing.tgl_request, 'yy-mm-dd') tgl_request,
									         emkl.nm_pbm AS nama_emkl, request_stuffing.voyage,
									         request_stuffing.nm_kapal nama_vessel, yard_area.nama_yard,
									         COUNT (container_stuffing.no_container) jml_cont
									    FROM request_stuffing,
									         nota_stuffing,
									         v_mst_pbm emkl,
									         yard_area,
									         container_stuffing
									   WHERE request_stuffing.kd_consignee = emkl.kd_pbm
									     AND request_stuffing.no_request = container_stuffing.no_request
									     AND request_stuffing.id_yard = yard_area.ID
									     AND nota_stuffing.no_request(+) = request_stuffing.no_request
										 AND request_stuffing.tgl_request BETWEEN TO_DATE ( '$from', 'yy-mm-dd')
                                    AND TO_DATE ( '$to', 'yy-mm-dd')
									GROUP BY NVL (nota_stuffing.lunas, 0),
									         NVL (nota_stuffing.no_nota, '-'),
									         request_stuffing.no_request,
									         TO_DATE (request_stuffing.tgl_request, 'yy-mm-dd'),
									         emkl.nm_pbm,
									         request_stuffing.voyage,
									         request_stuffing.nm_kapal,
									         yard_area.nama_yard
									ORDER BY request_stuffing.no_request DESC) q";						
					} 
					else if((isset($_POST["from"]))&& (isset($_POST["to"])) && (isset($_POST["no_req"])))
					{
						$query_list     = "SELECT * FROM (SELECT   NVL (nota_stuffing.lunas, 0) lunas,
									         NVL (nota_stuffing.no_nota, '-') no_nota,
									         request_stuffing.no_request,
									         TO_CHAR (request_stuffing.tgl_request, 'dd/mm/yyyy') tgl_request,
									         emkl.nm_pbm AS nama_emkl, request_stuffing.voyage,
									         request_stuffing.nm_kapal nama_vessel, yard_area.nama_yard,
									         COUNT (container_stuffing.no_container) jml_cont
									    FROM request_stuffing,
									         nota_stuffing,
									         v_mst_pbm emkl,
									         yard_area,
									         container_stuffing
									   WHERE request_stuffing.kd_consignee = emkl.kd_pbm
									     AND request_stuffing.no_request = container_stuffing.no_request
									     AND request_stuffing.id_yard = yard_area.ID
									     AND nota_stuffing.no_request(+) = request_stuffing.no_request
									GROUP BY NVL (nota_stuffing.lunas, 0),
									         NVL (nota_stuffing.no_nota, '-'),
									         request_stuffing.no_request,
									         TO_CHAR (request_stuffing.tgl_request, 'dd/mm/yyyy'),
									         emkl.nm_pbm,
									         request_stuffing.voyage,
									         request_stuffing.nm_kapal,
									         yard_area.nama_yard
									ORDER BY request_stuffing.no_request DESC) q
									WHERE q.NO_REQUEST LIKE '%$no_req%' AND q.tgl_request BETWEEN TO_DATE ( '$from', 'YYYY-MM-DD ')
                                    AND TO_DATE (  CONCAT('$to', '23:59:59'), 'YYYY-MM-DD HH24:MI:SS')";
					}
					else{
						$query_list     = "SELECT * FROM (SELECT   NVL (nota_stuffing.lunas, 0) lunas,
									         NVL (nota_stuffing.no_nota, '-') no_nota,
									         request_stuffing.no_request,
									         TO_CHAR (request_stuffing.tgl_request, 'dd/mm/yyyy') tgl_request,
									         emkl.nm_pbm AS nama_emkl, request_stuffing.voyage,
									         request_stuffing.nm_kapal nama_vessel, yard_area.nama_yard,
									         COUNT (container_stuffing.no_container) jml_cont
									    FROM request_stuffing,
									         nota_stuffing,
									         v_mst_pbm emkl,
									         yard_area,
									         container_stuffing
									   WHERE request_stuffing.kd_consignee = emkl.kd_pbm
									     AND request_stuffing.no_request = container_stuffing.no_request
									     AND request_stuffing.id_yard = yard_area.ID
									     AND nota_stuffing.no_request(+) = request_stuffing.no_request
									GROUP BY NVL (nota_stuffing.lunas, 0),
									         NVL (nota_stuffing.no_nota, '-'),
									         request_stuffing.no_request,
									         TO_CHAR (request_stuffing.tgl_request, 'dd/mm/yyyy'),
									         emkl.nm_pbm,
									         request_stuffing.voyage,
									         request_stuffing.nm_kapal,
									         yard_area.nama_yard
									ORDER BY request_stuffing.no_request DESC)";
					}
                }  else {
                    $query_list     = "SELECT * FROM (SELECT   NVL (nota_stuffing.lunas, 0) lunas,
											 NVL (nota_stuffing.no_nota, '-') no_nota,
											 request_stuffing.no_request,
											 TO_CHAR (request_stuffing.tgl_request, 'dd/mm/yyyy') tgl_request,
											 request_stuffing.tgl_request tglr,
											 emkl.nm_pbm AS nama_emkl, request_stuffing.voyage,
											 request_stuffing.nm_kapal nama_vessel, yard_area.nama_yard,
											 COUNT (container_stuffing.no_container) jml_cont
										FROM request_stuffing,
											 nota_stuffing,
											 v_mst_pbm emkl,
											 yard_area,
											 container_stuffing
									   WHERE request_stuffing.kd_consignee = emkl.kd_pbm
										 AND request_stuffing.no_request = container_stuffing.no_request
										 AND request_stuffing.id_yard = yard_area.ID
										 AND nota_stuffing.no_request(+) = request_stuffing.no_request
									GROUP BY NVL (nota_stuffing.lunas, 0),
											 NVL (nota_stuffing.no_nota, '-'),
											 request_stuffing.no_request,
											 TO_CHAR (request_stuffing.tgl_request, 'dd/mm/yyyy'),
											 request_stuffing.tgl_request,
											 emkl.nm_pbm,
											 request_stuffing.voyage,
											 request_stuffing.nm_kapal,
											 yard_area.nama_yard) c 
									ORDER BY c.tglr desc";
                }
	/* $result_list	= $db->query($query_list);
	$row_list	= $result_list->getAll(); */ 
		
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
		$query_cek	= "SELECT NOTA, KOREKSI FROM REQUEST_STUFFING WHERE NO_REQUEST = '$no_req'";
        //echo $query_cek;
		$result_cek	= $db->query($query_cek);
		$row_cek 	= $result_cek->fetchRow();
		
		if(count($row_cek) > 0)
		{
			$cetak		= $row_cek["NOTA"];
			$lunas		= $row_cek["KOREKSI"];
			//echo $lunas;
			/*if (($cetak == NULL) && ($lunas == 'NO'))
			{
				echo '<a href="'.HOME.APPID.'/preview_nota?no_req='.$no_req.'&n=999" target="_self"> Preview Nota </a> ';
			}
			else  if (($row_cek["NO_NOTA"] <> NULL) && ($row_cek["LUNAS"] == 'YES'))
			{
				echo '<a href="'.HOME.APPID.'.print/print_nota_lunas?no_nota='.$no_nota.'&no_req='.$no_req.'" target="_blank""><b><i> CETAK ULANG </i></b> </a> <br>';
                	//	echo '<font color="red"><i>SDH LUNAS</i></font>';
			}
			else if (($cetak <> NULL) && ($lunas == 'NO'))
			{
				echo '<a href="'.HOME.APPID.'.print/print_nota_lunas?no_nota='.$no_nota.'&no_req='.$no_req.'" target="_blank""><b><i> CETAK ULANG</i></b>  </a> <br>';
              //  echo '<a href="'.HOME.APPID.'/set_lunas?no_nota='.$no_nota.'" target="_self""><style:"font-color=red"> Set LUNAS</style> </a> ';
			}
			else {
				echo '<a href="'.HOME.APPID.'/preview_nota?no_req='.$no_req.'&n=999" target="_self"> <b><i>Preview Nota </i></b> </a> ';
			}*/
			
			if(($row_cek["NOTA"] <> 'Y') AND ($row_cek["KOREKSI"] <> 'Y'))
			{
				echo '<a href="'.HOME.APPID.'/print_nota?no_req='.$no_req.'&n=999&koreksi=N" target="_blank"> <b><i> Preview Nota</i></b></a> ';
				//echo '<a href="'.HOME.APPID.'/cetak_nota?no_nota='.$no_nota.'&n='.$cetak.'" target="_blank"> CETAK ULANG </a> ';		
			}
			else if(($row_cek["NOTA"] == 'Y') AND ($row_cek["KOREKSI"] <> 'Y'))
			{
				echo '<a href="'.HOME.APPID.'.print/print_nota_lunas?no_req='.$no_req.'" target="_blank"><b><i> CETAK ULANG </i></b></a> <br>';
			}
			else if(($row_cek["NOTA"] == 'Y') AND ($row_cek["KOREKSI"] == 'Y'))
			{
				echo '<a href="'.HOME.APPID.'.print/print_nota_lunas?no_req='.$no_req.'" target="_blank"><b><i> CETAK ULANG </i></b></a> <br>';	
			}
			else if(($row_cek["NOTA"] <> 'Y') AND ($row_cek["KOREKSI"] == 'Y'))
			{
				echo '<a href="'.HOME.APPID.'/print_nota?no_req='.$no_req.'&n=999&koreksi=Y" target="_blank"> <b><i> Preview Nota </i></b></a> ';
			}
		
	}
	}
?>
