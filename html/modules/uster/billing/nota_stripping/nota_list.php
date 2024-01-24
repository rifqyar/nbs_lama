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
	
	$db = getDB("storage");
	
	$cari	= $_POST["CARI"];
	$from	= $_POST["FROM"]; 
	$to		= $_POST["TO"];
	$no_req	= $_POST["NO_REQ"];
	
	if(isset($_POST["CARI"]) ) 
	{
		if((isset($_POST["NO_REQ"])) && ($from == NULL) && ($to == NULL))
		{
			$query_list		= "SELECT REQUEST_STRIPPING.TGL_REQUEST, REQUEST_STRIPPING.TGL_APPROVE, request_stripping.NO_DO, request_stripping.NO_BL, request_stripping.NO_REQUEST, emkl.NM_PBM AS PENUMPUKAN_OLEH, pbm.NM_PBM AS CONSIGNEE, COUNT(container_stripping.NO_CONTAINER) AS JML
        FROM REQUEST_STRIPPING INNER JOIN v_mst_pbm emkl ON REQUEST_STRIPPING.KD_PENUMPUKAN_OLEH = emkl.KD_PBM
         INNER JOIN v_mst_pbm pbm ON REQUEST_STRIPPING.KD_CONSIGNEE = pbm.KD_PBM AND pbm.KD_CABANG = '05'
         INNER JOIN container_stripping ON REQUEST_STRIPPING.NO_REQUEST = container_stripping.NO_REQUEST
        WHERE request_stripping.NO_REQUEST LIKE '%$no_req%'
		AND request_stripping.PERP_DARI IS NULL 
		AND request_stripping.CLOSING = 'CLOSED'
        GROUP BY REQUEST_STRIPPING.TGL_REQUEST, request_stripping.NO_REQUEST,  emkl.NM_PBM, pbm.NM_PBM, request_stripping.TGL_APPROVE,request_stripping.NO_DO, request_stripping.NO_BL
        ORDER BY REQUEST_STRIPPING.NO_REQUEST DESC";
		}
		else if((isset($_POST["FROM"]))&& (isset($_POST["TO"])) && ($no_req == NULL))
		{
			$query_list		= "SELECT REQUEST_STRIPPING.TGL_REQUEST, REQUEST_STRIPPING.TGL_APPROVE, request_stripping.NO_DO, request_stripping.NO_BL,request_stripping.NO_REQUEST, emkl.NM_PBM AS PENUMPUKAN_OLEH, pbm.NM_PBM AS CONSIGNEE, COUNT(container_stripping.NO_CONTAINER) AS JML
        FROM REQUEST_STRIPPING INNER JOIN v_mst_pbm emkl ON REQUEST_STRIPPING.KD_PENUMPUKAN_OLEH = emkl.KD_PBM
         INNER JOIN v_mst_pbm pbm ON REQUEST_STRIPPING.KD_CONSIGNEE = pbm.KD_PBM AND pbm.KD_CABANG = '05'
         INNER JOIN container_stripping ON REQUEST_STRIPPING.NO_REQUEST = container_stripping.NO_REQUEST
        WHERE request_stripping.PERP_DARI IS NULL
		AND request_stripping.CLOSING = 'CLOSED'
		AND REQUEST_STRIPPING.TGL_REQUEST BETWEEN TO_DATE ( '$from', 'YYYY-MM-DD ')
        AND TO_DATE (  CONCAT('$to', '23:59:59'), 'YYYY-MM-DD HH24:MI:SS')
        GROUP BY REQUEST_STRIPPING.TGL_REQUEST, request_stripping.NO_REQUEST,  emkl.NM_PBM, pbm.NM_PBM, request_stripping.TGL_APPROVE,request_stripping.NO_DO, request_stripping.NO_BL
        ORDER BY REQUEST_STRIPPING.NO_REQUEST DESC";
		} else if((isset($_POST["FROM"]))&& (isset($_POST["TO"])) && (isset($_POST["NO_REQ"])))
		{
			$query_list		= "SELECT REQUEST_STRIPPING.TGL_REQUEST, REQUEST_STRIPPING.TGL_APPROVE, request_stripping.NO_DO, request_stripping.NO_BL,request_stripping.NO_REQUEST, emkl.NM_PBM AS PENUMPUKAN_OLEH, pbm.NM_PBM AS CONSIGNEE, COUNT(container_stripping.NO_CONTAINER) AS JML
        FROM REQUEST_STRIPPING INNER JOIN v_mst_pbm emkl ON REQUEST_STRIPPING.KD_PENUMPUKAN_OLEH = emkl.KD_PBM
         INNER JOIN v_mst_pbm pbm ON REQUEST_STRIPPING.KD_CONSIGNEE = pbm.KD_PBM AND pbm.KD_CABANG = '05'
         INNER JOIN container_stripping ON REQUEST_STRIPPING.NO_REQUEST = container_stripping.NO_REQUEST
        WHERE request_stripping.NO_REQUEST = '$no_req'
		AND request_stripping.PERP_DARI IS NULL
		AND request_stripping.CLOSING = 'CLOSED'
		AND REQUEST_STRIPPING.TGL_REQUEST BETWEEN TO_DATE ( '$from', 'YYYY-MM-DD ')
        AND TO_DATE (  CONCAT('$to', '23:59:59'), 'YYYY-MM-DD HH24:MI:SS')
        GROUP BY REQUEST_STRIPPING.TGL_REQUEST, request_stripping.NO_REQUEST,  emkl.NM_PBM, pbm.NM_PBM, request_stripping.TGL_APPROVE,request_stripping.NO_DO, request_stripping.NO_BL
        ORDER BY REQUEST_STRIPPING.NO_REQUEST DESC";
		}
		
	}
	else
	{
		$query_list  = "SELECT  request_stripping.PERP_DARI , REQUEST_STRIPPING.TGL_REQUEST, REQUEST_STRIPPING.TGL_APPROVE, request_stripping.NO_DO, request_stripping.NO_BL,request_stripping.NO_REQUEST, emkl.NM_PBM AS PENUMPUKAN_OLEH,  COUNT(container_stripping.NO_CONTAINER) AS JML
        FROM REQUEST_STRIPPING  left JOIN v_mst_pbm emkl ON REQUEST_STRIPPING.KD_PENUMPUKAN_OLEH = emkl.KD_PBM
         INNER JOIN container_stripping ON REQUEST_STRIPPING.NO_REQUEST = container_stripping.NO_REQUEST
        WHERE request_stripping.PERP_DARI IS NULL
		AND request_stripping.CLOSING = 'CLOSED'
		AND emkl.KD_CABANG = '05'
		--AND REQUEST_STRIPPING.TGL_REQUEST BETWEEN TRUNC (ADD_MONTHS (SYSDATE, -10), 'MM') AND LAST_DAY (SYSDATE)
        GROUP BY  request_stripping.PERP_DARI ,REQUEST_STRIPPING.TGL_REQUEST, request_stripping.NO_REQUEST,  emkl.NM_PBM, request_stripping.TGL_APPROVE,request_stripping.NO_DO, request_stripping.NO_BL
        ORDER BY REQUEST_STRIPPING.TGL_REQUEST DESC";
	}
	
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
		$query_cek	= "SELECT NOTA, KOREKSI, NOTA_PNKN, KOREKSI_PNKN, CASE WHEN TRUNC(TGL_REQUEST) < TO_DATE('01/01/2015','DD/MM/RRRR') THEN 'NO'
		ELSE 'YES' END STATUS_CUTOFF, NO_NOTA FROM REQUEST_STRIPPING LEFT JOIN NOTA_STRIPPING ON REQUEST_STRIPPING.NO_REQUEST = NOTA_STRIPPING.NO_REQUEST WHERE REQUEST_STRIPPING.NO_REQUEST = '$no_req'";
        //echo $query_cek;
		$result_cek	= $db->query($query_cek);
		$row_cek 	= $result_cek->fetchRow();
		
		if(count($row_cek) > 0)
		{
			$cetak		= $row_cek["NOTA"];
			$cetak1		= $row_cek["NOTA_PNKN"];
			$lunas		= $row_cek["KOREKSI"];
			$lunas1		= $row_cek["KOREKSI_PNKN"];
            $req        = $no_req;
            $notas      = $row_cek["NO_NOTA"];

			if($row_cek['STATUS_CUTOFF'] == 'YES'){
			
				if(($row_cek["NOTA"] <> 'Y') AND ($row_cek["KOREKSI"] <> 'Y') AND ($row_cek["NOTA_PNKN"] <> 'Y') AND ($row_cek["KOREKSI_PNKN"] <> 'Y'))
				{
					echo '<a href="'.HOME.APPID.'/preview_nota_simple?no_req='.$no_req.'&n=999&koreksi=N" target="_blank"> <b><i> Preview Proforma Stripping</i></b></a> <br/>';
					echo '<a href="'.HOME.APPID.'/preview_nota_relokmty?no_req='.$no_req.'&n=999&koreksi=N" target="_blank"> <b><i> Preview Proforma Relokasi MTY</i></b></a> ';
					//echo '<a href="'.HOME.APPID.'/cetak_nota?no_nota='.$no_nota.'&n='.$cetak.'" target="_blank"> CETAK ULANG </a> ';		
				}
				else if(($row_cek["NOTA"] == 'Y') AND ($row_cek["KOREKSI"] <> 'Y') AND ($row_cek["NOTA_PNKN"] <> 'Y') AND ($row_cek["KOREKSI_PNKN"] <> 'Y'))
				{
					echo '<a href="'.HOME.APPID.'.print/print_proforma_strip?no_req='.$no_req.'" target="_blank"><b><i> Cetak Proforma Stripping </i></b></a> ';
                    echo " | <a onclick='recalc(\"$req\",\"$notas\")' title='recalculate stripping'><img src='images/money2.png' ></a> <br>";
					echo '<a href="'.HOME.APPID.'./preview_nota_relokmty?no_req='.$no_req.'&n=999&koreksi=N" target="_blank"> <b><i> Preview Proforma Relokasi MTY</i></b></a> ';
				}
				else if(($row_cek["NOTA"] <> 'Y') AND ($row_cek["KOREKSI"] <> 'Y') AND ($row_cek["NOTA_PNKN"] == 'Y') AND ($row_cek["KOREKSI_PNKN"] <> 'Y'))
				{
					echo '<a href="'.HOME.APPID.'/preview_nota_simple?no_req='.$no_req.'&n=999&koreksi=N" target="_blank"> <b><i> Preview Proforma Stripping</i></b></a> <br/>';
					echo '<a href="'.HOME.APPID.'.print/print_proforma_relok?no_req='.$no_req.'" target="_blank"><b><i> Cetak Proforma Relokasi </i></b></a> ';
                    echo " | <a onclick='recalc_relok(\"$req\",\"$notas\")' title='recalculate relokasi'><img src='images/money2.png' ></a>";
				}
				else if(($row_cek["NOTA"] == 'Y') AND ($row_cek["KOREKSI"] <> 'Y') AND ($row_cek["NOTA_PNKN"] == 'Y') AND ($row_cek["KOREKSI_PNKN"] <> 'Y')  )
				{
					echo '<a href="'.HOME.APPID.'.print/print_proforma_strip?no_req='.$no_req.'" target="_blank"><b><i> Cetak Proforma Stripping </i></b></a>';
                    echo " | <a onclick='recalc(\"$req\",\"$notas\")' title='recalculate stripping'><img src='images/money2.png' ></a> <br>";
					echo '<a href="'.HOME.APPID.'.print/print_proforma_relok?no_req='.$no_req.'" target="_blank"><b><i> Cetak Proforma Relokasi </i></b></a> '; 
                    echo " | <a onclick='recalc_relok(\"$req\",\"$notas\")' title='recalculate relokasi'><img src='images/money2.png' ></a> <br>";
				}
				else if(($row_cek["NOTA"] <> 'Y') AND ($row_cek["KOREKSI"] == 'Y') AND ($row_cek["NOTA_PNKN"] <> 'Y') AND ($row_cek["KOREKSI_PNKN"] == 'Y')  )
				{
					echo '<a href="'.HOME.APPID.'/preview_nota_simple?no_req='.$no_req.'&n=999&koreksi=Y" target="_blank"> <b><i> Preview Proforma Stripping</i></b></a> <br/>';
					echo '<a href="'.HOME.APPID.'/preview_nota_relokmty?no_req='.$no_req.'&n=999&koreksi=Y" target="_blank"> <b><i> Preview Proforma Relokasi MTY</i></b></a>';
				}
				else if(($row_cek["NOTA"] <> 'Y') AND ($row_cek["KOREKSI"] == 'Y') AND ($row_cek["NOTA_PNKN"] <> 'Y') AND ($row_cek["KOREKSI_PNKN"] <> 'Y')  )
				{
					echo '<a href="'.HOME.APPID.'/preview_nota_simple?no_req='.$no_req.'&n=999&koreksi=Y" target="_blank"> <b><i> Preview Proforma Stripping</i></b></a> <br/>';
					echo '<a href="'.HOME.APPID.'/preview_nota_relokmty?no_req='.$no_req.'&n=999&koreksi=Y" target="_blank"> <b><i> Preview Proforma Relokasi MTY</i></b></a>';
				}
				else if(($row_cek["NOTA"] == 'Y') AND ($row_cek["KOREKSI"] == 'Y') AND ($row_cek["NOTA_PNKN"] <> 'Y') AND ($row_cek["KOREKSI_PNKN"] == 'Y')  )
				{
					echo '<a href="'.HOME.APPID.'.print/print_proforma_strip?no_req='.$no_req.'" target="_blank"><b><i> Cetak Proforma Stripping </i></b></a> ';
                    echo " | <a onclick='recalc(\"$req\",\"$notas\")' title='recalculate stripping'><img src='images/money2.png' ></a> <br>";
					echo '<a href="'.HOME.APPID.'/preview_nota_relokmty?no_req='.$no_req.'&n=999&koreksi=Y" target="_blank"> <b><i> Preview Proforma Relokasi MTY</i></b></a>';
				}
				else if(($row_cek["NOTA"] <> 'Y') AND ($row_cek["KOREKSI"] == 'Y') AND ($row_cek["NOTA_PNKN"] == 'Y') AND ($row_cek["KOREKSI_PNKN"] == 'Y')  )
				{
					echo '<a href="'.HOME.APPID.'/preview_nota_simple?no_req='.$no_req.'&n=999&koreksi=Y" target="_blank"> <b><i> Preview Proforma Stripping</i></b></a> <br/>';
					echo '<a href="'.HOME.APPID.'.print/print_proforma_relok?no_req='.$no_req.'" target="_blank"><b><i> Cetak Proforma Relokasi </i></b></a> '; 
                    echo " | <a onclick='recalc_relok(\"$req\",\"$notas\")' title='recalculate relokasi'><img src='images/money2.png' ></a>";
				}
				else if(($row_cek["NOTA"] == 'Y') AND ($row_cek["KOREKSI"] == 'Y') AND ($row_cek["NOTA_PNKN"] == 'Y') AND ($row_cek["KOREKSI_PNKN"] == 'Y')  )
				{
					echo '<a href="'.HOME.APPID.'.print/print_proforma_strip?no_req='.$no_req.'" target="_blank"><b><i> Cetak Proforma Stripping </i></b></a>';
                    echo " | <a onclick='recalc(\"$req\",\"$notas\")' title='recalculate stripping'><img src='images/money2.png' ></a> <br>";
					echo '<a href="'.HOME.APPID.'.print/print_proforma_relok?no_req='.$no_req.'" target="_blank"><b><i> Cetak Proforma Relokasi </i></b></a> '; 
                    echo " | <a onclick='recalc_relok(\"$req\",\"$notas\")' title='recalculate relokasi'><img src='images/money2.png' ></a>";
				}
				else if(($row_cek["NOTA"] == 'Y') AND ($row_cek["KOREKSI"] == 'Y') AND ($row_cek["NOTA_PNKN"] == 'Y') AND ($row_cek["KOREKSI_PNKN"] == 'Y')  )
				{
					echo '<a href="'.HOME.APPID.'.print/print_proforma_strip?no_req='.$no_req.'" target="_blank"><b><i> Cetak Proforma Stripping </i></b></a>';
                    echo " | <a onclick='recalc(\"$req\",\"$notas\")' title='recalculate stripping'><img src='images/money2.png' ></a> <br>";
					echo '<a href="'.HOME.APPID.'.print/print_proforma_relok?no_req='.$no_req.'" target="_blank"><b><i> Cetak Proforma Relokasi </i></b></a> '; 
                    echo " | <a onclick='recalc_relok(\"$req\",\"$notas\")' title='recalculate relokasi'><img src='images/money2.png' ></a>";
				}
				else if(($row_cek["NOTA"] == 'N') AND ($row_cek["KOREKSI"] == 'Y') AND ($row_cek["NOTA_PNKN"] == 'Y') AND ($row_cek["KOREKSI_PNKN"] <> 'Y')  )
				{
					echo '<a href="'.HOME.APPID.'/preview_nota_simple?no_req='.$no_req.'&n=999&koreksi=Y" target="_blank"> <b><i> Preview Proforma Stripping</i></b></a> <br/>';
					echo '<a href="'.HOME.APPID.'.print/print_proforma_relok?no_req='.$no_req.'" target="_blank"><b><i> Cetak Proforma Relokasi </i></b></a> '; 
                    echo " | <a onclick='recalc_relok(\"$req\",\"$notas\")' title='recalculate relokasi'><img src='images/money2.png' ></a>";
				}
				else if(($row_cek["NOTA"] == 'Y') AND ($row_cek["KOREKSI"] == 'Y') AND ($row_cek["NOTA_PNKN"] == 'Y') AND ($row_cek["KOREKSI_PNKN"] <> 'Y')  )
				{
					echo '<a href="'.HOME.APPID.'/preview_nota_simple?no_req='.$no_req.'&n=999&koreksi=Y" target="_blank"> <b><i> Preview Proforma Stripping</i></b></a> <br/>';
					echo '<a href="'.HOME.APPID.'.print/print_proforma_relok?no_req='.$no_req.'" target="_blank"><b><i> Cetak Proforma Relokasi </i></b></a> '; 
                    echo " | <a onclick='recalc_relok(\"$req\",\"$notas\")' title='recalculate relokasi'><img src='images/money2.png' ></a>";
				}
			}
			else {
				echo '<a href="'.HOME.APPID.'.print/print_proforma_strip?no_req='.$no_req.'" target="_blank"><b><i> Cetak Proforma Stripping </i></b></a> <br/>';
				echo '<a href="'.HOME.APPID.'.print/print_proforma_relok?no_req='.$no_req.'" target="_blank"><b><i> Cetak Proforma Relokasi </i></b></a> '; 
			}		
	}
	}
?>
