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

	$from  = $_POST['from'];
	$to    = $_POST['to'];
	
	$no_req   = $_POST['no_req'];
	
	if(isset($_POST["cari"]))
	{
  if($_POST["no_req"] != NULL && $_POST["from"] == NULL && $_POST["to"] == NULL)
                                        {
                                            
        $query_list = "SELECT * FROM (
							SELECT rb.NO_REQUEST,
                                     emkl.NM_PBM AS NAMA_CONSIGNEE,
                                    --pnmt.NM_PBM AS NAMA_PENUMPUK, 
                                    NVL(rb.PERP_DARI,'-') AS EX_REQ, 
                                    rb.PERP_KE,
                                    rb.NOTA CETAK_NOTA_BARU,
                                    rl.NOTA CETAK_NOTA_LAMA,
                                    rb.NM_KAPAL,
                                    rb.VOYAGE,
                                    NVL(nl.LUNAS, 0) LUNAS_NOTA_LAMA,
                                    NVL(nb.LUNAS, 0) LUNAS_NOTA_BARU,
                                    rb.TGL_REQUEST,
                                    COUNT(container_stuffing.NO_CONTAINER) JUMLAH,
									rb.no_booking,
									rb.stuffing_dari
                            FROM request_stuffing rb, --request baru  
                                      request_stuffing rl, --request lama 
                                      V_MST_PBM emkl, 
                                      --V_MST_PBM pnmt,
                                      container_stuffing, 
                                      nota_stuffing nb,
                                      nota_stuffing nl 
                            WHERE rb.KD_CONSIGNEE = emkl.KD_PBM AND emkl.KD_CABANG = '05'
                                --AND rb.KD_PENUMPUKAN_OLEH = pnmt.KD_PBM
                                AND rb.NO_REQUEST = container_stuffing.NO_REQUEST 
                                AND rb.NO_REQUEST = nb.NO_REQUEST(+)
                                AND rb.NO_REQUEST = '$no_req'
                                AND rl.NO_REQUEST = nl.NO_REQUEST(+)
                                AND container_stuffing.NO_REQUEST IS NOT NULL
                                AND container_stuffing.AKTIF = 'Y'
                                AND rb.PERP_DARI = rl.NO_REQUEST(+)
                            GROUP BY  rb.NO_REQUEST,rb.TGL_REQUEST, 
                                            NVL(nl.LUNAS, 0),
                                            NVL(nb.LUNAS, 0),
                                            rb.NOTA,
                                            rl.NOTA,
                                            rb.NM_KAPAL,
                                            rb.VOYAGE,
                                            emkl.NM_PBM, 
                                            --pnmt.NM_PBM, 
                                            rb.PERP_DARI, 
                                            rb.PERP_KE,
											rb.no_booking,
											rb.stuffing_dari
                            ORDER BY rb.TGL_REQUEST DESC
                            )
                ORDER BY TGL_REQUEST DESC";		
		
		echo $query_list;
                                        }
                                        else if ($_POST["no_req"] == NULL && $_POST["from"] != NULL && $_POST["to"] != NULL)
                                        {
$query_list = "SELECT request_stuffing.NO_REQUEST, 
                        TO_CHAR( request_stuffing.TGL_APPROVE,'dd/mm/yyyy') TGL_APPROVE, 
                        emkl.NM_PBM AS NAMA_CONSIGNEE,
                        pnmt.NM_PBM AS NAMA_PENUMPUK, 
                        NVL(request_stuffing.PERP_DARI,'-') AS EX_REQ, 
                        request_stuffing.PERP_KE,
                        request_stuffing.NO_DO,
                        request_stuffing.NO_BL,
                        request_stuffing.NOTA,
                        NVL(nota_stuffing.LUNAS, 0) LUNAS,
                        request_stuffing.TGL_REQUEST,
				COUNT(container_stuffing.NO_CONTAINER) JUMLAH
                FROM request_stuffing,V_MST_PBM emkl, V_MST_PBM pnmt,container_stuffing, nota_stuffing
                WHERE request_stuffing.KD_CONSIGNEE = emkl.KD_PBM 
                AND request_stuffing.KD_PENUMPUKAN_OLEH = pnmt.KD_PBM
                AND request_stuffing.NO_REQUEST = container_stuffing.NO_REQUEST 
                AND request_stuffing.NO_REQUEST = nota_stuffing.NO_REQUEST(+)
				 AND request_stuffing.TGL_REQUEST BETWEEN TO_DATE ( '$from', 'dd-mm-rrrr') AND TO_DATE ( '$to', 'dd-mm-rrrr') 
                GROUP BY request_stuffing.NO_REQUEST,request_stuffing.NO_DO,request_stuffing.TGL_REQUEST, NVL(nota_stuffing.LUNAS, 0),
                        request_stuffing.NOTA,request_stuffing.NO_BL, request_stuffing.TGL_APPROVE, TO_CHAR( request_stuffing.TGL_AWAL,'dd/mm/yyyy'), emkl.NM_PBM, pnmt.NM_PBM,  TO_CHAR( request_stuffing.TGL_AKHIR,'dd/mm/yyyy'), request_stuffing.PERP_DARI, request_stuffing.PERP_KE
                ORDER BY request_stuffing.TGL_REQUEST DESC";			

                                        } else if((isset($_POST["no_req"]))&& (isset($_POST["from"])) && (isset($_POST["to"])))
                                        {
                                             $query_list = "SELECT request_stuffing.NO_REQUEST, 
                        TO_CHAR( request_stuffing.TGL_APPROVE,'dd/mm/yyyy') TGL_APPROVE, 
                        emkl.NM_PBM AS NAMA_CONSIGNEE,
                        pnmt.NM_PBM AS NAMA_PENUMPUK, 
                        NVL(request_stuffing.PERP_DARI,'-') AS EX_REQ, 
                        request_stuffing.PERP_KE,
                        request_stuffing.NO_DO,
                        request_stuffing.NO_BL,
                        request_stuffing.NOTA,
                        NVL(nota_stuffing.LUNAS, 0) LUNAS,
                        request_stuffing.TGL_REQUEST,
						COUNT(container_stuffing.NO_CONTAINER) JUMLAH
                FROM request_stuffing,V_MST_PBM emkl, V_MST_PBM pnmt,container_stuffing, nota_stuffing
                WHERE request_stuffing.KD_CONSIGNEE = emkl.KD_PBM AND emkl.KD_CABANG = '05'
                AND request_stuffing.KD_PENUMPUKAN_OLEH = pnmt.KD_PBM AND pnmt.KD_CABANG = '05'
                AND request_stuffing.NO_REQUEST = container_stuffing.NO_REQUEST 
                AND request_stuffing.NO_REQUEST = nota_stuffing.NO_REQUEST(+)
				AND  request_stuffing.NO_REQUEST  = '$no_req'
				 AND request_stuffing.TGL_REQUEST BETWEEN TO_DATE ( '$from', 'dd-mm-rrrr') AND TO_DATE ( '$to', 'dd-mm-rrrr')
                GROUP BY request_stuffing.NO_REQUEST,request_stuffing.NO_DO,request_stuffing.TGL_REQUEST, NVL(nota_stuffing.LUNAS, 0),
                        request_stuffing.NOTA,request_stuffing.NO_BL, request_stuffing.TGL_APPROVE, TO_CHAR( request_stuffing.TGL_AWAL,'dd/mm/yyyy'), emkl.NM_PBM, pnmt.NM_PBM,  TO_CHAR( request_stuffing.TGL_AKHIR,'dd/mm/yyyy'), request_stuffing.PERP_DARI, request_stuffing.PERP_KE
                ORDER BY request_stuffing.TGL_REQUEST DESC";		
                        } 	}
	else
	{
	/*	$query_list = "SELECT request_stuffing.NO_REQUEST, TO_CHAR( request_stuffing.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_CHAR( request_stuffing.TGL_REQUEST + 3,'dd/mm/yyyy') AS TGL_REQUEST_END, emkl.NAMA AS NAMA_EMKL, pnmt.NAMA AS NAMA_PNMT, COUNT(container_stuffing.NO_CONTAINER) TOTAL, NVL(request_stuffing.PERP_DARI,'---') AS EX_REQ, request_stuffing.PERP_KE
				FROM request_stuffing INNER JOIN MASTER_PBM emkl ON request_stuffing.ID_EMKL = emkl.ID 
				JOIN MASTER_PBM pnmt ON request_stuffing.ID_PEMILIK = pnmt.ID 
				JOIN container_stuffing ON request_stuffing.NO_REQUEST = container_stuffing.NO_REQUEST AND 'Y' = container_stuffing.AKTIF
				JOIN nota_stuffing ON request_stuffing.NO_REQUEST = nota_stuffing.NO_REQUEST AND 'YES' = LUNAS
				WHERE request_stuffing.TGL_REQUEST 
				BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) 
				GROUP BY request_stuffing.NO_REQUEST, TO_CHAR( request_stuffing.TGL_REQUEST,'dd/mm/yyyy'), emkl.NAMA, pnmt.NAMA,  TO_CHAR( request_stuffing.TGL_REQUEST + 3,'dd/mm/yyyy'), request_stuffing.PERP_DARI, request_stuffing.PERP_KE
				ORDER BY request_stuffing.NO_REQUEST DESC";
	*/
	/*
	$query_list = "SELECT * FROM (SELECT request_stuffing.NO_REQUEST,
                        emkl.NM_PBM AS NAMA_CONSIGNEE,
                        pnmt.NM_PBM AS NAMA_PENUMPUK, 
                        NVL(request_stuffing.PERP_DARI,'-') AS EX_REQ, 
                        request_stuffing.PERP_KE,
                        request_stuffing.NOTA,
						request_stuffing.NM_KAPAL,
                        request_stuffing.VOYAGE,
                        NVL(nota_stuffing.LUNAS, 0) LUNAS,
                        request_stuffing.TGL_REQUEST,
                        COUNT(container_stuffing.NO_CONTAINER) JUMLAH
                FROM request_stuffing,V_MST_PBM emkl, V_MST_PBM pnmt,container_stuffing, nota_stuffing
                WHERE request_stuffing.KD_CONSIGNEE = emkl.KD_PBM 
                AND request_stuffing.KD_PENUMPUKAN_OLEH = pnmt.KD_PBM
                AND request_stuffing.NO_REQUEST = container_stuffing.NO_REQUEST 
                AND request_stuffing.NO_REQUEST = nota_stuffing.NO_REQUEST(+)
				AND container_stuffing.NO_REQUEST IS NOT NULL
				AND container_stuffing.AKTIF = 'Y'
                GROUP BY request_stuffing.NO_REQUEST,request_stuffing.TGL_REQUEST, NVL(nota_stuffing.LUNAS, 0),
                        request_stuffing.NOTA,request_stuffing.NM_KAPAL,
                        request_stuffing.VOYAGE,
                        emkl.NM_PBM, pnmt.NM_PBM, request_stuffing.PERP_DARI, request_stuffing.PERP_KE
                ORDER BY request_stuffing.TGL_REQUEST DESC)
                ORDER BY TGL_REQUEST DESC";
	*/
	
	$query_list = "SELECT * FROM (
							SELECT rb.NO_REQUEST,
                                     emkl.NM_PBM AS NAMA_CONSIGNEE,
                                    --pnmt.NM_PBM AS NAMA_PENUMPUK, 
                                    NVL(rb.PERP_DARI,'-') AS EX_REQ, 
                                    rb.PERP_KE,
                                    rb.NOTA CETAK_NOTA_BARU,
                                    rl.NOTA CETAK_NOTA_LAMA,
                                    rb.NM_KAPAL,
                                    rb.VOYAGE,
                                    NVL(nl.LUNAS, 0) LUNAS_NOTA_LAMA,
                                    NVL(nb.LUNAS, 0) LUNAS_NOTA_BARU,
                                    rb.TGL_REQUEST,
                                    COUNT(container_stuffing.NO_CONTAINER) JUMLAH,
									rb.no_booking,
									rb.stuffing_dari
                            FROM request_stuffing rb, --request baru  
                                      request_stuffing rl, --request lama 
                                      KAPAL_CABANG.MST_PBM emkl, 
                                      --V_MST_PBM pnmt,
                                      container_stuffing, 
                                      nota_stuffing nb,
                                      nota_stuffing nl 
                            WHERE rb.KD_CONSIGNEE = emkl.KD_PBM AND emkl.KD_CABANG = '05'
                                --AND rb.KD_PENUMPUKAN_OLEH = pnmt.KD_PBM
                                AND rb.NO_REQUEST = container_stuffing.NO_REQUEST 
                                AND rb.NO_REQUEST = nb.NO_REQUEST(+)
                                AND rl.NO_REQUEST = nl.NO_REQUEST(+)
                                AND container_stuffing.NO_REQUEST IS NOT NULL
                                AND container_stuffing.AKTIF = 'Y'
                                AND rb.PERP_DARI = rl.NO_REQUEST(+)
                            GROUP BY  rb.NO_REQUEST,rb.TGL_REQUEST, 
                                            NVL(nl.LUNAS, 0),
                                            NVL(nb.LUNAS, 0),
                                            rb.NOTA,
                                            rl.NOTA,
                                            rb.NM_KAPAL,
                                            rb.VOYAGE,
                                            emkl.NM_PBM, 
                                            --pnmt.NM_PBM, 
                                            rb.PERP_DARI, 
                                            rb.PERP_KE,
											rb.no_booking,
											rb.stuffing_dari
                            ORDER BY rb.TGL_REQUEST DESC
                            )
                ORDER BY TGL_REQUEST DESC";
		
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
	//debug($row_list);
	$i=0;	
	foreach ($row_list as $row){
		$no[$i] = $row['__no'];
		$no_request[$i] = $row['NO_REQUEST'];
		
		$cek_cont		= "SELECT COUNT(NO_CONTAINER) TOTAL FROM container_stuffing WHERE NO_REQUEST = '".$no_request[$i]."' AND AKTIF = 'Y'";
		$cont_list		= $db->query($cek_cont);
		$row_list_      = $cont_list->fetchRow();
		$total[$i]		= $row_list_['TOTAL'];
		
		// rb.NO_REQUEST,
		 // emkl.NM_PBM AS NAMA_CONSIGNEE,
		// pnmt.NM_PBM AS NAMA_PENUMPUK, 
		// NVL(rb.PERP_DARI,'-') AS EX_REQ, 
		// rb.PERP_KE,
		// rb.NOTA CETAK_NOTA_BARU,
		// rl.NOTA CETAK_NOTA_LAMA,
		// rb.NM_KAPAL,
		// rb.VOYAGE,
		// NVL(nl.LUNAS, 0) LUNAS_NOTA_LAMA,
		// NVL(nb.LUNAS, 0) LUNAS_NOTA_BARU,
		// rb.TGL_REQUEST,
		// COUNT(container_stuffing.NO_CONTAINER) JUMLAH 
		
		$nama_cons[$i]			= $row['NAMA_CONSIGNEE'];
		$nama_penum[$i]			= $row['NAMA_PENUMPUK'];
		$ex_req[$i]				= $row['EX_REQ'];
		$perp_ke[$i]			= $row['PERP_KE'];
		$cetak_nota_lama[$i]	= $row['CETAK_NOTA_LAMA'];
		$cetak_nota_baru[$i]	= $row['CETAK_NOTA_BARU'];
		$lunas_nota_lama[$i]	= $row['LUNAS_NOTA_LAMA'];
		$lunas_nota_baru[$i]	= $row['LUNAS_NOTA_BARU'];
		$nm_kapal[$i]			= $row['NM_KAPAL'];
		$voyage[$i]				= $row['VOYAGE'];
		$tgl_req[$i]			= $row['TGL_REQUEST'];
		$no_booking[$i]			= $row['NO_BOOKING'];
		$stuffing_dari[$i]			= $row['STUFFING_DARI'];
		
		/* $querys			= "select COUNT(*) cek
						from v_booking_stack_tpk a
						where a.NO_BOOKING = '".$no_booking[$i]."'
							and sysdate > to_timestamp (a.DOC_CLOSING_DATE_DRY-1)";
							
		$results		= $db->query($querys);
		$rows		= $results->fetchRow();
		
		if($rows['CEK'] > 0){
			//$tl->assign('close','sudah melewati closing time');
			$closing_time[$i] = 'sudah melewati closing time';
			//echo "sudah melewati closing time";
		}
		else {
			$html = "<a href=".$HOME.$APPID."/view_perp?no_req=".$no_request[$i]."><img src='images/ico_approval.gif' border='0' />&nbsp Perpanjangan</a>";
			$closing_time[$i] = $html;
			//echo $html;
		} */
		
		$jumlah[$i]	    = $row['JUMLAH'];
	
	$i++;
	}
	
	//print_r($no_booking);die;
	
	
	$tl->assign("no",$no);
	$tl->assign("no_request",$no_request);
	$tl->assign("no_booking",$no_booking);
	//$tl->assign("closing_time",$closing_time);
	$tl->assign("total",$total);
	$tl->assign("jumlah",$jumlah);
	$tl->assign("nm_kapal",$nm_kapal);
	$tl->assign("voyage",$voyage);
	$tl->assign("consignee",$nama_cons);
	$tl->assign("nama_penumpuk",$nama_penum);
	$tl->assign("lunas_nota_lama",$lunas_nota_lama);
	$tl->assign("lunas_nota_baru",$lunas_nota_baru);
	$tl->assign("stuffing_dari",$stuffing_dari);
	$tl->assign("ex_req",$ex_req);
	$tl->assign("nota",$nota);
	$tl->assign("perp_ke",$perp_ke);
	$tl->assign("lunas",$lunas);
	$tl->assign("tgl_req",$tgl_req);
	if ($i >=19){
	$i= 19;
	}else {
	$i =$i-1;
	}
	$tl->assign("counter",$i);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	
	$tl->renderToScreen();
?>
