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
								         TO_CHAR(a.TGL_REQUEST,'dd Mon yyyy') TGL_REQUEST,
								        a.NM_CONSIGNEE CONSIGNEE,
								         COUNT(c.NO_CONTAINER) JUMLAH
								    FROM request_receiving a, container_receiving c
								   WHERE     a.RECEIVING_DARI = 'LUAR'
								         AND a.NO_REQUEST = c.NO_REQUEST
										 AND a.NO_REQUEST = '$no_req'
									GROUP BY a.NO_REQUEST,
								         a.TGL_REQUEST,
								         a.NM_CONSIGNEE
									ORDER BY a.TGL_REQUEST DESC";
		}
		else if((isset($_POST["FROM"]))&& (isset($_POST["TO"])) && ($no_req == NULL))
		{
			$query_list		= "SELECT a.NO_REQUEST,
								         TO_CHAR(a.TGL_REQUEST,'dd Mon yyyy') TGL_REQUEST,
								         a.NM_CONSIGNEE CONSIGNEE,
								         COUNT(c.NO_CONTAINER) JUMLAH
								    FROM request_receiving a, container_receiving c
								   WHERE     a.RECEIVING_DARI = 'LUAR'
								         AND a.NO_REQUEST = c.NO_REQUEST
										 AND a.TGL_REQUEST BETWEEN TO_DATE ( '$from', 'YYYY-MM-DD ')
                                                 AND TO_DATE (  CONCAT('$to', '23:59:59'), 'YYYY-MM-DD HH24:MI:SS')
									GROUP BY a.NO_REQUEST,
								         a.TGL_REQUEST,
								         a.NM_CONSIGNEE
									ORDER BY a.TGL_REQUEST DESC";
		} else if((isset($_POST["FROM"]))&& (isset($_POST["TO"])) && (isset($_POST["NO_REQ"])))
		{
			$query_list		= "SELECT a.NO_REQUEST,
								         TO_CHAR(a.TGL_REQUEST,'dd Mon yyyy') TGL_REQUEST,
								         a.NM_CONSIGNEE CONSIGNEE,
								         COUNT(c.NO_CONTAINER) JUMLAH
								    FROM request_receiving a, container_receiving c
								   WHERE     a.RECEIVING_DARI = 'LUAR'
								         AND a.NO_REQUEST = c.NO_REQUEST
										 AND a.NO_REQUEST = '$no_req'
										 AND a.TGL_REQUEST BETWEEN TO_DATE ( '$from', 'YYYY-MM-DD ')
                                                 AND TO_DATE (  CONCAT('$to', '23:59:59'), 'YYYY-MM-DD HH24:MI:SS')
									GROUP BY a.NO_REQUEST,
								         a.TGL_REQUEST,
								         a.NM_CONSIGNEE
									ORDER BY a.TGL_REQUEST DESC";
		}
		
		} else {
		$query_list = "SELECT * FROM (SELECT a.NO_REQUEST,
									 TO_CHAR(a.TGL_REQUEST,'dd Mon yyyy') TGL_REQUEST,
									 a.NM_CONSIGNEE CONSIGNEE,
									 COUNT(c.NO_CONTAINER) JUMLAH
									FROM request_receiving a, container_receiving c
									WHERE     a.NO_REQUEST = c.NO_REQUEST
									 AND a.RECEIVING_DARI = 'LUAR'
									GROUP BY a.NO_REQUEST,
									 a.TGL_REQUEST,
									 a.NM_CONSIGNEE
									ORDER BY a.TGL_REQUEST DESC) WHERE ROWNUM <= 100"; }
	
	/* $result_list	= $db->query($query_list);
	$row_list	= $result_list->getAll();  */
		
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
		$query_cek	= "SELECT NOTA, KOREKSI,LUNAS,a.NO_REQUEST,b.NO_NOTA FROM REQUEST_RECEIVING a, nota_receiving b WHERE a.no_request = b.no_request(+) and a.NO_REQUEST = '$no_req'";	
                //echo $query_cek;
		$result_cek	= $db->query($query_cek);
		$row_cek 	= $result_cek->getAll();
		
		if(count($row_cek) > 0)
		{
			$nota		= $row_cek[0]["NOTA"];
			$koreksi	= $row_cek[0]["KOREKSI"];
            $req        = $row_cek[0]["NO_REQUEST"];
            $notas        = $row_cek[0]["NO_NOTA"];
			
			//'print/print_pdf?no_req='.$no_req.'&no_nota='.$no_nota.
			// <a href="{$HOME}{$APPID}/view?no_req={$rows.NO_REQUEST}"> LIHAT </a>
			if(($row_cek[0]["NOTA"] <> 'Y') AND ($row_cek[0]["KOREKSI"] <> 'Y'))
			{
				echo '<a href="'.HOME.APPID.'/print_nota?no_req='.$no_req.'&n=999&koreksi=N" target="_blank"> <b><i> Preview Nota </i></b></a> ';
				//echo '<a href="'.HOME.APPID.'/cetak_nota?no_nota='.$no_nota.'&n='.$cetak.'" target="_blank"> CETAK ULANG </a> ';		
			}
			else if(($row_cek[0]["NOTA"] == 'Y') AND ($row_cek[0]["KOREKSI"] <> 'Y'))
			{
				echo '<a href="'.HOME.APPID.'.print/print_proforma?no_req='.$no_req.'" target="_blank"><b><i> CETAK ULANG </i></b></a> <br>';
                echo "<a onclick='recalc(\"$req\",\"$notas\")' title='recalculate'><img src='images/money2.png' ></a>";
			}
			else if(($row_cek[0]["NOTA"] == 'Y') AND ($row_cek[0]["KOREKSI"] == 'Y'))
			{
				echo '<a href="'.HOME.APPID.'.print/print_proforma?no_req='.$no_req.'" target="_blank"><b><i> CETAK ULANG </i></b></a> <br>';	
                echo "<a onclick='recalc(\"$req\",\"$notas\")' title='recalculate'><img src='images/money2.png' ></a>";
			}
			else if(($row_cek[0]["NOTA"] <> 'Y') AND ($row_cek[0]["KOREKSI"] == 'Y'))
			{
				echo '<a href="'.HOME.APPID.'/print_nota?no_req='.$no_req.'&n=999&koreksi=Y" target="_blank"> <b><i> Preview Nota </i></b></a> ';
			}

		}
		
		
			
	}
?>
