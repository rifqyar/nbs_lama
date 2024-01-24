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
	//$db2 = getDB("ora");
	
	$cari	= $_POST["CARI"];
	$from	= $_POST["FROM"]; 
	$to		= $_POST["TO"];
	$no_req	= $_POST["NO_REQ"];
	
	if(isset($_POST["CARI"]) ) 
	{
		if($_POST["NO_REQ"] != NULL && $_POST["FROM"] == NULL && $_POST["TO"] == NULL)
		{
			$query_list		= "  SELECT  a.*, 
									  a.NM_CONSIGNEE AS NAMA_EMKL
								FROM REQUEST_RECEIVING a
								WHERE a.RECEIVING_DARI = 'LUAR'
								AND a.NO_REQUEST LIKE '%$no_req%'
							    ORDER BY a.TGL_REQUEST DESC";
		}
		 else if ($_POST["NO_REQ"] == NULL && $_POST["FROM"] == NULL && $_POST["TO"] == NULL)
		{
			$query_list		= "  SELECT  a.*, 
									  a.NM_CONSIGNEE AS NAMA_EMKL
								FROM REQUEST_RECEIVING a
								WHERE  a.RECEIVING_DARI = 'LUAR'
								AND a.NO_REQUEST LIKE '%$no_req%'
							    ORDER BY a.TGL_REQUEST DESC";
		}
		else if ($_POST["NO_REQ"] == NULL && $_POST["FROM"] != NULL && $_POST["TO"] != NULL)
		{
			$query_list		= "  SELECT  a.*, 
								  a.NM_CONSIGNEE AS NAMA_EMKL
								FROM REQUEST_RECEIVING a
								WHERE a.RECEIVING_DARI = 'LUAR'
								AND a.TGL_REQUEST BETWEEN TO_DATE ( '$from', 'YYYY-MM-DD ')
                                AND TO_DATE (  CONCAT('$to', '23:59:59'), 'YYYY-MM-DD HH24:MI:SS')
							    ORDER BY a.TGL_REQUEST DESC";
		}
		else{
			$query_list		= "  SELECT  a.*, 
								  a.NM_CONSIGNEE AS NAMA_EMKL
								FROM REQUEST_RECEIVING a
								WHERE a.RECEIVING_DARI = 'LUAR'							 
								AND a.NO_REQUEST LIKE '%$no_req%'
								AND a.TGL_REQUEST BETWEEN TO_DATE ( '$from', 'YYYY-MM-DD ')
                                AND TO_DATE (  CONCAT('$to', '23:59:59'), 'YYYY-MM-DD HH24:MI:SS')
							    ORDER BY a.TGL_REQUEST DESC";
		}
		
	}
	else
	{
		
		$query_list		= "SELECT *
						  FROM (  SELECT a.*, a.NM_CONSIGNEE AS NAMA_EMKL
						            FROM REQUEST_RECEIVING a
						           WHERE     a.RECEIVING_DARI = 'LUAR'
						                 AND a.TGL_REQUEST BETWEEN SYSDATE - INTERVAL '14' DAY
						                                       AND SYSDATE
						        ORDER BY a.TGL_REQUEST DESC)";
								
								/*
								SELECT  a.*, 
									  b.NM_PBM AS NAMA_EMKL
								FROM REQUEST_RECEIVING a,
									 V_MST_PBM b
								WHERE a.TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) 
							    AND a.KD_CONSIGNEE = b.KD_PBM 
							    ORDER BY a.TGL_REQUEST DESC
								*/
		
	}
	
	echo $query_list;
	
	/* $result_list	= $db->query($query_list);
	$row_list		= $result_list->getAll();  */
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
	
	//debug($row_list);
	//debug($row_trf);
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
		$query_cek	= "SELECT NOTA, KOREKSI,LUNAS FROM REQUEST_RECEIVING a, nota_receiving b WHERE a.no_request = b.no_request(+) and a.NO_REQUEST = '$no_req'";	
		$result_cek	= $db->query($query_cek);
		$row_cek 	= $result_cek->getAll();
		
		if(count($row_cek) > 0)
		{
			$nota		= $row_cek[0]["NOTA"];
			$koreksi	= $row_cek[0]["KOREKSI"];
			$lunas	= $row_cek[0]["LUNAS"];
			
			//'print/print_pdf?no_req='.$no_req.'&no_nota='.$no_nota.
			// <a href="{$HOME}{$APPID}/view?no_req={$rows.NO_REQUEST}"> LIHAT </a>
            if($lunas == 'NO'){
                echo '<a href="'.HOME.APPID.'/view?no_req='.$no_req.'" target="_blank"> EDIT </a> ';
            }
            else {
                if(($row_cek[0]["NOTA"] <> 'Y') AND ($row_cek[0]["KOREKSI"] <> 'Y'))
                {
                    echo '<a href="'.HOME.APPID.'/view?no_req='.$no_req.'" target="_blank"> EDIT </a> ';
                    //echo '<a href="'.HOME.APPID.'/cetak_nota?no_nota='.$no_nota.'&n='.$cetak.'" target="_blank"> CETAK ULANG </a> ';		
                }
                else if(($row_cek[0]["NOTA"] == 'Y') AND ($row_cek[0]["KOREKSI"] <> 'Y'))
                {

                    $cek_nota = "SELECT STATUS FROM ITPK_NOTA_HEADER WHERE NO_REQUEST = '$no_req' AND STATUS_NOTA = 0";
                    $rcek = $db->query($cek_nota)->fetchRow();
                    if($rcek['STATUS'] = 2){
                        echo '<a href="'.HOME.APPID.'/overview?no_req='.$no_req.'" target="_blank" > Nota sudah cetak </a> ';
                    }
                    else {
                        echo '<a href="'.HOME.APPID.'/view?no_req='.$no_req.'" target="_blank"> Proforma sudah cetak <br/> EDIT </a> ';
                    }
                    //echo '<a href="'.HOME.APPID.'/overview?no_req='.$no_req.'" target="_blank" > Nota sudah cetak </a> ';
                }
                else if(($row_cek[0]["NOTA"] == 'Y') AND ($row_cek[0]["KOREKSI"] == 'Y'))
                {

                    echo '<a href="'.HOME.APPID.'/view?no_req='.$no_req.'" target="_blank"> Proforma sudah cetak <br/> EDIT </a> ';

                }
                else if(($row_cek[0]["NOTA"] == 'F') AND ($row_cek[0]["KOREKSI"] <> 'Y'))
                {
                    echo '<a href="'.HOME.APPID.'/overview?no_req='.$no_req.'" target="_blank" > Nota sudah cetak </a> ';	
                }
                else if(($row_cek[0]["NOTA"] <> 'Y') AND ($row_cek[0]["KOREKSI"] == 'Y'))
                {
                    echo '<a href="'.HOME.APPID.'/view?no_req='.$no_req.'" target="_blank"> EDIT </a> ';		
                }
            }

	}
	}
?>
