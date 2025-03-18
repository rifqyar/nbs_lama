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
											CASE WHEN nota_stuffing.status = 'BATAL' THEN
                                                CONCAT('BATAL - ',nota_stuffing.no_faktur)
                                             ELSE NVL (nota_stuffing.no_faktur, '-') END  no_nota,
									         --NVL (nota_stuffing.no_nota, '-') no_nota,
											 nota_stuffing.TGL_NOTA,
									         request_stuffing.no_request,
									         TO_CHAR (request_stuffing.tgl_request, 'dd/mm/yyyy') tgl_request,
									         emkl.nm_pbm AS nama_emkl, request_stuffing.voyage,
									         request_stuffing.nm_kapal nama_vessel, yard_area.nama_yard,
									         COUNT (container_stuffing.no_container) jml_cont
									    FROM request_stuffing,
									         nota_stuffing,
									         V_MST_PBM emkl,
									         yard_area,
									         container_stuffing
									   WHERE request_stuffing.kd_consignee = emkl.kd_pbm
										 AND emkl.KD_CABANG = '05'
									     AND request_stuffing.no_request = container_stuffing.no_request
									     AND request_stuffing.perp_dari is null
										 AND request_stuffing.id_yard = yard_area.ID
									     AND nota_stuffing.no_request(+) = request_stuffing.no_request
									GROUP BY NVL (nota_stuffing.lunas, 0),
											CASE WHEN nota_stuffing.status = 'BATAL' THEN
                                                CONCAT('BATAL - ',nota_stuffing.no_faktur)
                                             ELSE NVL (nota_stuffing.no_faktur, '-') END,
									         --NVL (nota_stuffing.no_nota, '-'),
									         request_stuffing.no_request,
									         TO_CHAR (request_stuffing.tgl_request, 'dd/mm/yyyy'),
											 nota_stuffing.TGL_NOTA,
									         emkl.nm_pbm,
									         request_stuffing.voyage,
									         request_stuffing.nm_kapal,
									         yard_area.nama_yard
									ORDER BY request_stuffing.no_request DESC) q
									WHERE q.NO_REQUEST LIKE '%$no_req%' ORDER BY q.TGL_NOTA DESC";
					}
					else if((isset($_POST["from"]))&& (isset($_POST["to"])) && ($no_req == NULL))
					{
						$query_list     = "SELECT * FROM (SELECT   NVL (nota_stuffing.lunas, 0) lunas,
									         NVL (nota_stuffing.no_faktur, '-') no_nota,
									         request_stuffing.no_request,
									         TO_DATE (request_stuffing.tgl_request, 'yy-mm-dd') tgl_request,
									         emkl.nm_pbm AS nama_emkl, request_stuffing.voyage,
									         request_stuffing.nm_kapal nama_vessel, yard_area.nama_yard,
									         COUNT (container_stuffing.no_container) jml_cont
									    FROM request_stuffing,
									         nota_stuffing,
									         V_MST_PBM emkl,
									         yard_area,
									         container_stuffing
									   WHERE request_stuffing.kd_consignee = emkl.kd_pbm
									   AND emkl.KD_CABANG = '05'
									     AND request_stuffing.no_request = container_stuffing.no_request
										 AND request_stuffing.perp_dari is null
									     AND request_stuffing.id_yard = yard_area.ID
									     AND nota_stuffing.no_request(+) = request_stuffing.no_request
										 AND request_stuffing.tgl_request BETWEEN TO_DATE ( '$from', 'yy-mm-dd')
                                    AND TO_DATE ( '$to', 'yy-mm-dd')
									GROUP BY NVL (nota_stuffing.lunas, 0),
									         NVL (nota_stuffing.no_faktur, '-'),
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
									         NVL (nota_stuffing.no_faktur, '-') no_nota,
									         request_stuffing.no_request,
									         TO_CHAR (request_stuffing.tgl_request, 'dd/mm/yyyy') tgl_request,
									         emkl.nm_pbm AS nama_emkl, request_stuffing.voyage,
									         request_stuffing.nm_kapal nama_vessel, yard_area.nama_yard,
									         COUNT (container_stuffing.no_container) jml_cont
									    FROM request_stuffing,
									         nota_stuffing,
									         V_MST_PBM emkl,
									         yard_area,
									         container_stuffing
									   WHERE request_stuffing.kd_consignee = emkl.kd_pbm
									   AND emkl.KD_CABANG = '05'
									     AND request_stuffing.no_request = container_stuffing.no_request
										 AND request_stuffing.perp_dari is null
									     AND request_stuffing.id_yard = yard_area.ID
									     AND nota_stuffing.no_request(+) = request_stuffing.no_request
									GROUP BY NVL (nota_stuffing.lunas, 0),
									         NVL (nota_stuffing.no_faktur, '-'),
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
									         NVL (nota_stuffing.no_faktur, '-') no_nota,
									         request_stuffing.no_request,
									         TO_CHAR (request_stuffing.tgl_request, 'dd/mm/yyyy') tgl_request,
									         emkl.nm_pbm AS nama_emkl, request_stuffing.voyage,
									         request_stuffing.nm_kapal nama_vessel, yard_area.nama_yard,
									         COUNT (container_stuffing.no_container) jml_cont
									    FROM request_stuffing,
									         nota_stuffing,
									         V_MST_PBM emkl,
									         yard_area,
									         container_stuffing
									   WHERE request_stuffing.kd_consignee = emkl.kd_pbm
									   AND emkl.KD_CABANG = '05'
									     AND request_stuffing.no_request = container_stuffing.no_request
										 AND request_stuffing.perp_dari is null
									     AND request_stuffing.id_yard = yard_area.ID
									     AND nota_stuffing.no_request(+) = request_stuffing.no_request
									GROUP BY NVL (nota_stuffing.lunas, 0),
									         NVL (nota_stuffing.no_faktur, '-'),
									         request_stuffing.no_request,
									         TO_CHAR (request_stuffing.tgl_request, 'dd/mm/yyyy'),
									         emkl.nm_pbm,
									         request_stuffing.voyage,
									         request_stuffing.nm_kapal,
									         yard_area.nama_yard
									ORDER BY request_stuffing.no_request DESC)";
					}
                }  else {
                    $query_list     = "SELECT *
					    FROM (  SELECT NVL (nota_stuffing.lunas, 0) lunas,
					                   CASE
					                      WHEN nota_stuffing.status = 'BATAL'
					                      THEN
					                         CONCAT ('BATAL - ', nota_stuffing.no_faktur)
					                      ELSE
					                         NVL (nota_stuffing.no_faktur, '-')
					                   END
					                      no_nota,
					                   request_stuffing.no_request,
					                   TO_CHAR (request_stuffing.tgl_request, 'dd/mm/yyyy')
					                      tgl_request,
					                   request_stuffing.tgl_request tglr,
					                   emkl.nm_pbm AS nama_emkl,
					                   request_stuffing.voyage,
					                   request_stuffing.nm_kapal nama_vessel,
					                   COUNT (container_stuffing.no_container) jml_cont
					              FROM request_stuffing,
					                   nota_stuffing,
					                   nota_pnkn_stuf,
					                   V_MST_PBM emkl,
					                   container_stuffing
					             WHERE request_stuffing.kd_consignee = emkl.kd_pbm
					                   AND emkl.KD_CABANG = '05'
					                   AND request_stuffing.no_request =
					                          container_stuffing.no_request
					                   AND request_stuffing.perp_dari IS NULL
					                   AND nota_stuffing.no_request(+) = request_stuffing.no_request
					                   AND nota_pnkn_stuf.no_request(+) = request_stuffing.no_request
					                   AND request_stuffing.STUFFING_DARI  <> 'AUTO'
					          GROUP BY NVL (nota_stuffing.lunas, 0),
					                   CASE
					                      WHEN nota_stuffing.status = 'BATAL'
					                      THEN
					                         CONCAT ('BATAL - ', nota_stuffing.no_faktur)
					                      ELSE
					                         NVL (nota_stuffing.no_faktur, '-')
					                   END,
					                   request_stuffing.no_request,
					                   TO_CHAR (request_stuffing.tgl_request, 'dd/mm/yyyy'),
					                   request_stuffing.tgl_request,
					                   emkl.nm_pbm,
					                   request_stuffing.voyage,
					                   request_stuffing.nm_kapal
					                   ORDER BY request_stuffing.tgl_request DESC
					                   ) c
					                   WHERE ROWNUM <=80";
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
		$query_cek	= "SELECT NOTA, KOREKSI, NOTA_PNKN, KOREKSI_PNKN, 
		CASE WHEN TO_DATE(TGL_REQUEST,'dd/mm/yy') <= TO_DATE('16/04/2013','dd/mm/yy') THEN 'NO' ELSE 'YES' END AS CEK_TGL
		FROM REQUEST_STUFFING WHERE NO_REQUEST = '$no_req'";
        //echo $query_cek;
		$result_cek	= $db->query($query_cek);
		$row_cek 	= $result_cek->fetchRow();
		
		if(count($row_cek) > 0)
		{
			$cetak		= $row_cek["NOTA"];
			$cetak1		= $row_cek["NOTA_PNKN"];
			$lunas		= $row_cek["KOREKSI"];
			$lunas1		= $row_cek["KOREKSI_PNKN"];
			$ok			= $row_cek["CEK_TGL"]; 
			if($ok == 'NO'){
				if($lunas == 'Y' && $cetak <> 'Y'){
					$ok = 'YES';
				}
				else if ($lunas == 'Y' && $cetak == 'Y'){
					$ok = 'NO'; 
				}
			}
            $req = $no_req;
            $notas = "";
			//echo $lunas;
			/*if (($cetak == NULL) && ($lunas == 'NO'))
			{
				echo '<a href="'.HOME.APPID.'/preview_nota?no_req='.$no_req.'&n=999" target="_self"> Preview Proforma </a> ';
			}
			else  if (($row_cek["NO_NOTA"] <> NULL) && ($row_cek["LUNAS"] == 'YES'))
			{
				echo '<a href="'.HOME.APPID.'.print/print_nota_lunas?no_nota='.$no_nota.'&no_req='.$no_req.'" target="_blank""><b><i> Cetak </i></b> </a> <br>';
                	//	echo '<font color="red"><i>SDH LUNAS</i></font>';
			}
			else if (($cetak <> NULL) && ($lunas == 'NO'))
			{
				echo '<a href="'.HOME.APPID.'.print/print_nota_lunas?no_nota='.$no_nota.'&no_req='.$no_req.'" target="_blank""><b><i> Cetak</i></b>  </a> <br>';
              //  echo '<a href="'.HOME.APPID.'/set_lunas?no_nota='.$no_nota.'" target="_self""><style:"font-color=red"> Set LUNAS</style> </a> ';
			}
			else {
				echo '<a href="'.HOME.APPID.'/preview_nota?no_req='.$no_req.'&n=999" target="_self"> <b><i>Preview Proforma </i></b> </a> ';
			}*/
			if($ok == 'YES'){
				if(($row_cek["NOTA"] <> 'Y') AND ($row_cek["KOREKSI"] <> 'Y') AND ($row_cek["NOTA_PNKN"] <> 'Y') AND ($row_cek["KOREKSI_PNKN"] <> 'Y'))
				{
					echo '<a href="'.HOME.APPID.'/print_nota_simple?no_req='.$no_req.'&n=999&koreksi=N" target="_blank"> <b><i> Preview Proforma Stuffing</i></b></a> <br/>';
					echo '<a href="'.HOME.APPID.'/print_nota_pnkn?no_req='.$no_req.'&n=999&koreksi=N" target="_blank"> <b><i> Preview Proforma Penumpukan</i></b></a> ';
					//echo '<a href="'.HOME.APPID.'/cetak_nota?no_nota='.$no_nota.'&n='.$cetak.'" target="_blank"> Cetak </a> ';		
				}
				else if(($row_cek["NOTA"] == 'Y') AND ($row_cek["KOREKSI"] <> 'Y') AND ($row_cek["NOTA_PNKN"] <> 'Y') AND ($row_cek["KOREKSI_PNKN"] <> 'Y'))
				{
					echo '<a href="'.HOME.APPID.'.print/print_proforma?no_req='.$no_req.'" target="_blank"><b><i> Cetak Proforma Stuffing </i></b></a>';
                    echo " | <a onclick='recalc(\"$req\",\"$notas\")' title='recalculate stuffing'><img src='images/money2.png' ></a> <br>";
					echo '<a href="'.HOME.APPID.'./print_nota_pnkn?no_req='.$no_req.'&n=999&koreksi=N" target="_blank"> <b><i> Preview Proforma Penumpukan</i></b></a> ';
				}
				else if(($row_cek["NOTA"] <> 'Y') AND ($row_cek["KOREKSI"] <> 'Y') AND ($row_cek["NOTA_PNKN"] == 'Y') AND ($row_cek["KOREKSI_PNKN"] <> 'Y'))
				{
					echo '<a href="'.HOME.APPID.'/print_nota_simple?no_req='.$no_req.'&n=999&koreksi=N" target="_blank"> <b><i> Preview Proforma Stuffing</i></b></a> <br/>';
					echo '<a href="'.HOME.APPID.'.print/print_proforma_pnkn?no_req='.$no_req.'" target="_blank"><b><i> Cetak Proforma Penumpukan </i></b></a> '; 
                    echo " | <a onclick='recalc_pnkn(\"$req\",\"$notas\")' title='recalculate penumpukan'><img src='images/money2.png' ></a>";
				}
				else if(($row_cek["NOTA"] == 'Y') AND ($row_cek["KOREKSI"] <> 'Y') AND ($row_cek["NOTA_PNKN"] == 'Y') AND ($row_cek["KOREKSI_PNKN"] <> 'Y')  )
				{
					echo '<a href="'.HOME.APPID.'.print/print_proforma?no_req='.$no_req.'" target="_blank"><b><i> Cetak Proforma Stuffing </i></b></a>';
                    echo " | <a onclick='recalc(\"$req\",\"$notas\")' title='recalculate stuffing'><img src='images/money2.png' ></a> <br>";
					echo '<a href="'.HOME.APPID.'.print/print_proforma_pnkn?no_req='.$no_req.'" target="_blank"><b><i> Cetak Proforma Penumpukan </i></b></a> '; 
                    echo " | <a onclick='recalc_pnkn(\"$req\",\"$notas\")' title='recalculate penumpukan'><img src='images/money2.png' ></a>";
				}
				else if(($row_cek["NOTA"] <> 'Y') AND ($row_cek["KOREKSI"] == 'Y') AND ($row_cek["NOTA_PNKN"] <> 'Y') AND ($row_cek["KOREKSI_PNKN"] == 'Y')  )
				{
					echo '<a href="'.HOME.APPID.'/print_nota_simple?no_req='.$no_req.'&n=999&koreksi=Y" target="_blank"> <b><i> Preview Proforma Stuffing</i></b></a> <br/>';
					echo '<a href="'.HOME.APPID.'/print_nota_pnkn?no_req='.$no_req.'&n=999&koreksi=Y" target="_blank"> <b><i> Preview Proforma Penumpukan</i></b></a>';
				}
				else if(($row_cek["NOTA"] <> 'Y') AND ($row_cek["KOREKSI"] == 'Y') AND ($row_cek["NOTA_PNKN"] <> 'Y') AND ($row_cek["KOREKSI_PNKN"] <> 'Y')  )
				{
					echo '<a href="'.HOME.APPID.'/print_nota_simple?no_req='.$no_req.'&n=999&koreksi=Y" target="_blank"> <b><i> Preview Proforma Stuffing</i></b></a> <br/>';
					echo '<a href="'.HOME.APPID.'/print_nota_pnkn?no_req='.$no_req.'&n=999&koreksi=Y" target="_blank"> <b><i> Preview Proforma Penumpukan</i></b></a>';
				}
				else if(($row_cek["NOTA"] == 'Y') AND ($row_cek["KOREKSI"] == 'Y') AND ($row_cek["NOTA_PNKN"] <> 'Y') AND ($row_cek["KOREKSI_PNKN"] == 'Y')  )
				{
					echo '<a href="'.HOME.APPID.'.print/print_proforma?no_req='.$no_req.'" target="_blank"><b><i> Cetak Proforma Stuffing </i></b></a> <br/>';
                    echo " | <a onclick='recalc(\"$req\",\"$notas\")' title='recalculate stuffing'><img src='images/money2.png' ></a> <br>";
					echo '<a href="'.HOME.APPID.'/print_nota_pnkn?no_req='.$no_req.'&n=999&koreksi=Y" target="_blank"> <b><i> Preview Proforma Penumpukan</i></b></a>';
				}
				else if(($row_cek["NOTA"] <> 'Y') AND ($row_cek["KOREKSI"] == 'Y') AND ($row_cek["NOTA_PNKN"] == 'Y') AND ($row_cek["KOREKSI_PNKN"] == 'Y')  )
				{
					echo '<a href="'.HOME.APPID.'/print_nota_simple?no_req='.$no_req.'&n=999&koreksi=Y" target="_blank"> <b><i> Preview Proforma Stuffing</i></b></a> <br/>';
					echo '<a href="'.HOME.APPID.'.print/print_proforma_pnkn?no_req='.$no_req.'" target="_blank"><b><i> Cetak Proforma Penumpukan </i></b></a> '; 
                    echo " | <a onclick='recalc_pnkn(\"$req\",\"$notas\")' title='recalculate penumpukan'><img src='images/money2.png' ></a> <br>";
				}
				else if(($row_cek["NOTA"] == 'Y') AND ($row_cek["KOREKSI"] == 'Y') AND ($row_cek["NOTA_PNKN"] == 'Y') AND ($row_cek["KOREKSI_PNKN"] == 'Y')  )
				{
					echo '<a href="'.HOME.APPID.'.print/print_proforma?no_req='.$no_req.'" target="_blank"><b><i> Cetak Proforma Stuffing </i></b></a>';
                    echo " | <a onclick='recalc(\"$req\",\"$notas\")' title='recalculate stuffing'><img src='images/money2.png' ></a> <br>";
					echo '<a href="'.HOME.APPID.'.print/print_proforma_pnkn?no_req='.$no_req.'" target="_blank"><b><i> Cetak Proforma Penumpukan </i></b></a> '; 
                    echo " | <a onclick='recalc_pnkn(\"$req\",\"$notas\")' title='recalculate penumpukan'><img src='images/money2.png' ></a> ";
				}
				else if(($row_cek["NOTA"] == 'Y') AND ($row_cek["KOREKSI"] == 'Y') AND ($row_cek["NOTA_PNKN"] == 'Y') AND ($row_cek["KOREKSI_PNKN"] == 'Y')  )
				{
					echo '<a href="'.HOME.APPID.'.print/print_proforma?no_req='.$no_req.'" target="_blank"><b><i> Cetak Proforma Stuffing </i></b></a>';
                    echo " | <a onclick='recalc(\"$req\",\"$notas\")' title='recalculate stuffing'><img src='images/money2.png' ></a> <br>";
					echo '<a href="'.HOME.APPID.'.print/print_proforma_pnkn?no_req='.$no_req.'" target="_blank"><b><i> Cetak Proforma Penumpukan </i></b></a> '; 
                    echo " | <a onclick='recalc_pnkn(\"$req\",\"$notas\")' title='recalculate penumpukan'><img src='images/money2.png' ></a> ";
				}
				else if(($row_cek["NOTA"] == 'N') AND ($row_cek["KOREKSI"] == 'Y') AND ($row_cek["NOTA_PNKN"] == 'Y') AND ($row_cek["KOREKSI_PNKN"] <> 'Y')  )
				{
					echo '<a href="'.HOME.APPID.'/print_nota_simple?no_req='.$no_req.'&n=999&koreksi=Y" target="_blank"> <b><i> Preview Proforma Stuffing</i></b></a> <br/>';
					echo '<a href="'.HOME.APPID.'.print/print_proforma_pnkn?no_req='.$no_req.'" target="_blank"><b><i> Cetak Proforma Penumpukan </i></b></a> '; 
                    echo " | <a onclick='recalc_pnkn(\"$req\",\"$notas\")' title='recalculate penumpukan'><img src='images/money2.png' ></a>";
				}
				else if(($row_cek["NOTA"] == 'Y') AND ($row_cek["KOREKSI"] == 'Y') AND ($row_cek["NOTA_PNKN"] == 'Y') AND ($row_cek["KOREKSI_PNKN"] <> 'Y')  )
				{
					echo '<a href="'.HOME.APPID.'/print_nota_simple?no_req='.$no_req.'&n=999&koreksi=Y" target="_blank"> <b><i> Preview Proforma Stuffing</i></b></a> <br/>';
					echo '<a href="'.HOME.APPID.'.print/print_proforma_pnkn?no_req='.$no_req.'" target="_blank"><b><i> Cetak Proforma Penumpukan </i></b></a> '; 
                    echo " | <a onclick='recalc_pnkn(\"$req\",\"$notas\")' title='recalculate penumpukan'><img src='images/money2.png' ></a>";
				}
			}
			else if($ok == 'NO'){
				if(($row_cek["NOTA"] <> 'Y') AND ($row_cek["KOREKSI"] <> 'Y'))
				{
					echo '<a href="'.HOME.APPID.'/print_nota_simple?no_req='.$no_req.'&n=999&koreksi=N" target="_blank"> <b><i> Preview Proforma</i></b></a> ';
					//echo '<a href="'.HOME.APPID.'/cetak_nota?no_nota='.$no_nota.'&n='.$cetak.'" target="_blank"> Cetak </a> ';		
				}
				else if(($row_cek["NOTA"] == 'Y') AND ($row_cek["KOREKSI"] <> 'Y'))
				{
					echo '<a href="'.HOME.APPID.'.print/print_proforma?no_req='.$no_req.'" target="_blank"><b><i> Cetak </i></b></a> <br>';
					/* echo '<a href="'.HOME.APPID.'.print/print_nota_lunas?no_req='.$no_req.'" target="_blank"><b><i> CETAK DETAIL </i></b></a> '; */
				}
				else if(($row_cek["NOTA"] == 'Y') AND ($row_cek["KOREKSI"] == 'Y'))
				{
					/*echo '<a href="'.HOME.APPID.'.print/print_proforma?no_req='.$no_req.'" target="_blank"><b><i> Cetak </i></b></a> <br>';	*/
					/* echo '<a href="'.HOME.APPID.'.print/print_nota_lunas?no_req='.$no_req.'" target="_blank"><b><i> CETAK DETAIL</i></b></a> <br>';	 */
					echo '<a href="'.HOME.APPID.'.print/print_proforma?no_req='.$no_req.'" target="_blank"><b><i> Cetak Proforma Stuffing </i></b></a>';
					echo '<a href="'.HOME.APPID.'.print/print_proforma_pnkn?no_req='.$no_req.'" target="_blank"><b><i> Cetak Proforma Penumpukan </i></b></a> '; 
				}
				else if(($row_cek["NOTA"] <> 'Y') AND ($row_cek["KOREKSI"] == 'Y'))
				{
					echo '<a href="'.HOME.APPID.'/print_nota_simple?no_req='.$no_req.'&n=999&koreksi=Y" target="_blank"> <b><i> Preview Proforma </i></b></a> ';
				}
			}
		
	}
	}
?>
