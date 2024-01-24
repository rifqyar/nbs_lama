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
        
        $cari	= $_POST["CARI"];
		$no_req	= $_POST["NO_REQ"]; 
		$from   = $_POST["FROM"];
		$to     = $_POST["TO"];
        $id_yard  = $_SESSION["IDYARD_STORAGE"];
	
        
	$db = getDB("storage");
        
      	IF (isset($_POST['CARI'])){
			if((isset($_POST["NO_REQ"])) && ($from == NULL) && ($to == NULL))
		{
			$query_list		= "SELECT a.NO_REQUEST,
							 TO_DATE(a.TGL_REQUEST,'yyyy-mm-dd') TGL_REQUEST_C,
							 TO_CHAR(a.TGL_REQUEST,'dd Mon yyyy') TGL_REQUEST,
							 b.NM_PBM CONSIGNEE,
							 COUNT(c.NO_CONTAINER) JUMLAH
							FROM REQUEST_RELOKASI a, v_mst_pbm b, CONTAINER_RELOKASI c
							WHERE     A.KD_EMKL = b.KD_PBM
							 AND a.NO_REQUEST = c.NO_REQUEST AND a.NO_REQUEST LIKE '%$no_req%'
							GROUP BY a.NO_REQUEST,
							 a.TGL_REQUEST,
							 b.NM_PBM
							ORDER BY a.TGL_REQUEST DESC";
		}
		else if((isset($_POST["FROM"]))&& (isset($_POST["TO"])) && ($no_req == NULL))
		{
			$query_list		= "SELECT a.NO_REQUEST,
							TO_DATE(a.TGL_REQUEST,'yyyy-mm-dd') TGL_REQUEST_C,
							 TO_CHAR(a.TGL_REQUEST,'dd Mon yyyy') TGL_REQUEST,
							 b.NM_PBM CONSIGNEE,
							 COUNT(c.NO_CONTAINER) JUMLAH
							FROM REQUEST_RELOKASI a, v_mst_pbm b, CONTAINER_RELOKASI c
							WHERE     A.KD_EMKL = b.KD_PBM
							 AND a.NO_REQUEST = c.NO_REQUEST AND TGL_REQUEST_C BETWEEN TO_DATE('$from','yyyy-mm-dd') AND TO_DATE('$to','yyyy-mm-dd')
							GROUP BY a.NO_REQUEST,
							 a.TGL_REQUEST,
							 b.NM_PBM
							ORDER BY a.TGL_REQUEST DESC";
		} else if((isset($_POST["FROM"]))&& (isset($_POST["TO"])) && (isset($_POST["NO_REQ"])))
		{
			$query_list		= "SELECT a.NO_REQUEST,
							TO_DATE(a.TGL_REQUEST,'yyyy-mm-dd') TGL_REQUEST_C,
							 TO_CHAR(a.TGL_REQUEST,'dd Mon yyyy') TGL_REQUEST,
							 b.NM_PBM CONSIGNEE,
							 COUNT(c.NO_CONTAINER) JUMLAH
							FROM REQUEST_RELOKASI a, v_mst_pbm b, CONTAINER_RELOKASI c
							WHERE     A.KD_EMKL = b.KD_PBM
							 AND a.NO_REQUEST = c.NO_REQUEST AND TGL_REQUEST_C BETWEEN TO_DATE('$from','yyyy-mm-dd') AND TO_DATE('$to','yyyy-mm-dd')
							 AND a.NO_REQUEST LIKE '%$no_req%'
							GROUP BY a.NO_REQUEST,
							 a.TGL_REQUEST,
							 b.NM_PBM
							ORDER BY a.TGL_REQUEST DESC";
		}
		
		} else {
				$query_list = "SELECT a.NO_REQUEST,
							 TO_CHAR(a.TGL_REQUEST,'dd Mon yyyy') TGL_REQUEST,
							 b.NM_PBM CONSIGNEE,
							 COUNT(c.NO_CONTAINER) JUMLAH
							FROM REQUEST_RELOKASI a, v_mst_pbm b, CONTAINER_RELOKASI c
							WHERE     A.KD_EMKL = b.KD_PBM
							 AND a.NO_REQUEST = c.NO_REQUEST
							GROUP BY a.NO_REQUEST,
							 a.TGL_REQUEST,
							 b.NM_PBM
							ORDER BY a.TGL_REQUEST DESC"; 
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
		$query_cek	= "SELECT * FROM NOTA_RELOKASI WHERE NO_REQUEST = '$no_req'";
                //echo $query_cek;
		$result_cek	= $db->query($query_cek);
		$row_cek 	= $result_cek->getAll();
		
		if(count($row_cek) > 0)
		{
			$cetak		= $row_cek[0]["CETAK_NOTA"];
			$no_nota	= $row_cek[0]["NO_NOTA"];
			
			if (($row_cek[0]["CETAK_NOTA"] > 0) && ($row_cek[0]["LUNAS"] == 'NO'))
			{
				echo '<a href="'.HOME.APPID.'.print/print_nota_lunas?no_nota='.$no_nota.'&no_req='.$no_req.'" target="_blank""><b><i> CETAK ULANG </i></b></a><br> ';	
                              //  echo '<a href="'.HOME.APPID.'/set_lunas?no_nota='.$no_nota.'"><style:"font-color=red"> Set LUNAS</style> </a> ';	
			}
			else if (($row_cek[0]["CETAK_NOTA"] > 0) && ($row_cek[0]["LUNAS"] == 'YES'))
			{
				echo '<a href="'.HOME.APPID.'.print/print_nota_lunas?no_nota='.$no_nota.'&no_req='.$no_req.'"  target="_blank"><b><i> CETAK ULANG </i></b> </a> <br>';
                               //  echo '<font color="red"><i>SDH LUNAS</i></font>';
			}
            else if (($row_cek[0]["CETAK_NOTA"] > 0) && ($row_cek[0]["LUNAS"] == 'PIUTANG'))
			{
				echo '<a href="'.HOME.APPID.'.print/print_nota_lunas?no_nota='.$no_nota.'&no_req='.$no_req.'" target="_blank"><b><i> CETAK ULANG </i></b></a> <br>';
                              //   echo '<font color="red"><i>PIUTANG</i></font>';
			}
		}
		else
		{
			echo '<a href="'.HOME.APPID.'/print_nota?no_req='.$no_req.'&n=999" target="_blank"> <b><i> Preview Nota </i></b></a> ';
		}
	}
?>
