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
        $no_req = $_POST['no_req'];
        $id_yard    = 	$_SESSION["IDYARD_STORAGE"];
		
            	if(isset($_POST["cari"]) ) 
            	{   
					if((isset($_POST["no_req"])) && ($from == NULL) && ($to == NULL))
					{
                        $query_list = " SELECT * FROM (SELECT a.NO_REQUEST, a.KOREKSI, a.NOTA, NVL(a.PERP_DARI,'-') PERP_DARI, TO_DATE(a.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_DATE(a.TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, b.NM_PBM,count(d.no_container) JUMLAH
                                FROM request_delivery a, KAPAL_CABANG.MST_PBM b, container_delivery d
                                where a.KD_EMKL = b.KD_PBM
								and b.KD_CABANG = '05'
                                and a.no_request = d.no_request
								AND a.NO_REQUEST LIKE '%$no_req'
								AND a.DELIVERY_KE='LUAR'
                                group by a.NO_REQUEST, a.NOTA,a.KOREKSI, NVL(a.PERP_DARI,'-') ,a.tgl_request, TO_DATE(a.TGL_REQUEST,'dd/mm/yyyy'), TO_DATE(a.TGL_REQUEST_DELIVERY,'dd/mm/yyyy'), b.NM_PBM
                                order by a.tgl_request DESC)";

					}
					else if(($no_req == NULL) && (isset($_POST["from"])) && (isset($_POST["to"])))
					{
                    $query_list = " SELECT * FROM (SELECT a.NO_REQUEST, a.KOREKSI, a.NOTA, NVL(a.PERP_DARI,'-') PERP_DARI, TO_DATE(a.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_DATE(a.TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, b.NM_PBM,count(d.no_container) JUMLAH
                                FROM request_delivery a, KAPAL_CABANG.MST_PBM b,  container_delivery d
                                where a.KD_EMKL = b.KD_PBM
								and b.KD_CABANG = '05'
                                and a.no_request = d.no_request
								AND a.DELIVERY_KE='LUAR'
								AND a.TGL_REQUEST_DELIVERY BETWEEN TO_DATE('$from','yyyy/mm/dd') AND TO_DATE('$to','yyyy/mm/dd')
                                group by a.NO_REQUEST, a.KOREKSI,a.NOTA, NVL(a.PERP_DARI,'-') ,a.tgl_request, TO_DATE(a.TGL_REQUEST,'dd/mm/yyyy'), TO_DATE(a.TGL_REQUEST_DELIVERY,'dd/mm/yyyy'), b.NM_PBM
                                order by a.tgl_request DESC)";

                  } else if((isset($_POST["no_req"]))&& (isset($_POST["from"])) && (isset($_POST["to"])))
                  {
                  $query_list = " SELECT * FROM (SELECT a.NO_REQUEST, a.KOREKSI, a.NOTA, NVL(a.PERP_DARI,'-') PERP_DARI, TO_DATE(a.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_DATE(a.TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, b.NM_PBM,count(d.no_container) JUMLAH
                                FROM request_delivery a, KAPAL_CABANG.MST_PBM b,container_delivery d
                                where a.KD_EMKL = b.KD_PBM
								and b.KD_CABANG = '05'
                                and a.no_request = d.no_request
								AND a.NO_REQUEST = '$no_req'
								AND a.DELIVERY_KE='LUAR'
								AND a.TGL_REQUEST_DELIVERY BETWEEN TO_DATE('$from','yyyy/mm/dd') AND TO_DATE('$to','yyyy/mm/dd')
                                group by a.NO_REQUEST, a.KOREKSI,a.NOTA, NVL(a.PERP_DARI,'-') ,a.tgl_request, TO_DATE(a.TGL_REQUEST,'dd/mm/yyyy'), TO_DATE(a.TGL_REQUEST_DELIVERY,'dd/mm/yyyy'), b.NM_PBM
                                order by a.tgl_request DESC)";
				}
               } else {
			   
				$query_list = " SELECT * FROM 
								(SELECT a.NO_REQUEST, a.KOREKSI,a.NOTA, NVL(a.PERP_DARI,'-') PERP_DARI, TO_DATE(a.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_DATE(a.TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, b.NM_PBM,count(d.no_container) JUMLAH
                                FROM request_delivery a, KAPAL_CABANG.MST_PBM b,container_delivery d
                                where a.KD_EMKL = b.KD_PBM
								and b.KD_CABANG = '05'
                                and a.no_request = d.no_request
								AND a.DELIVERY_KE='LUAR'
                                group by a.NO_REQUEST,a.KOREKSI, a.NOTA, NVL(a.PERP_DARI,'-') ,a.tgl_request, TO_DATE(a.TGL_REQUEST,'dd/mm/yyyy'), TO_DATE(a.TGL_REQUEST_DELIVERY,'dd/mm/yyyy'), b.NM_PBM
                                order by a.tgl_request DESC) WHERE ROWNUM <=100";
			   
			   
			   }
			   
     /*      $query_list     = "  SELECT REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd Mon yyyy') TGL_REQUEST, TO_CHAR(TGL_REQUEST_DELIVERY,'dd Mon yyyy') TGL_REQUEST_DELIVERY, request_delivery.STATUS, 
                    emkl.NM_PBM AS NAMA_EMKL, request_delivery.VOYAGE, request_delivery.VESSEL, yard_area.NAMA_YARD, NVL(request_delivery.PERP_DARI,'') PERP_DARI, request_delivery.PERP_KE, NVL(nota_delivery.LUNAS,0) LUNAS, NVL(nota_delivery.CETAK_NOTA,0) CETAK_NOTA,COUNT(container_delivery.NO_CONTAINER) JUMLAH
                        FROM REQUEST_DELIVERY, NOTA_DELIVERY, V_MST_PBM emkl, VESSEL, VOYAGE, yard_area, container_delivery
                        WHERE  REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
                        AND REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
                        AND NOTA_DELIVERY.NO_REQUEST(+) = REQUEST_DELIVERY.NO_REQUEST
                        AND request_delivery.NO_REQUEST = container_delivery.NO_REQUEST
                        AND container_delivery.AKTIF = 'Y'
                       AND request_delivery.PERALIHAN NOT IN ('RELOKASI','STUFFING','STRIPPING')
                       AND request_delivery.DELIVERY_KE = 'LUAR'
                        AND nota_delivery.LUNAS = 'YES'
                        AND REQUEST_DELIVERY.PERP_DARI IS NULL
                       -- AND request_delivery.NO_REQUEST NOT IN (SELECT request_delivery.NO_REQUEST FROM request_delivery WHERE request_delivery.STATUS = 'NEW'  AND request_delivery.NO_REQUEST IN (SELECT request_delivery.PERP_DARI FROM request_delivery))
                          --AND request_delivery.NO_REQUEST NOT IN (SELECT request_delivery.PERP_DARI from request_delivery)
                        AND rownum<=50 
                        GROUP BY request_delivery.PERALIHAN,REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd Mon yyyy'), TO_CHAR(TGL_REQUEST_DELIVERY,'dd Mon yyyy'), request_delivery.STATUS, emkl.NM_PBM, request_delivery.VOYAGE, request_delivery.VESSEL, yard_area.NAMA_YARD, NVL(request_delivery.PERP_DARI,''), request_delivery.PERP_KE, NVL(nota_delivery.LUNAS,0),NVL(nota_delivery.CETAK_NOTA,0),REQUEST_DELIVERY.TGL_REQUEST
                        ORDER BY REQUEST_DELIVERY.TGL_REQUEST DESC";
              } */
           
				/*	$query_list = "       SELECT REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd Mon yyyy') TGL_REQUEST, TO_CHAR(TGL_REQUEST_DELIVERY,'dd Mon yyyy') TGL_REQUEST_DELIVERY, request_delivery.STATUS,COUNT(container_delivery.NO_CONTAINER) JUMLAH, 
										emkl.NM_PBM AS NAMA_EMKL, request_delivery.VOYAGE, request_delivery.VESSEL, yard_area.NAMA_YARD, NVL(request_delivery.PERP_DARI,'') PERP_DARI, request_delivery.PERP_KE, NVL(nota_delivery.LUNAS,0) LUNAS, NVL(nota_delivery.CETAK_NOTA,0) CETAK_NOTA
											FROM REQUEST_DELIVERY, NOTA_DELIVERY, V_MST_PBM emkl, VESSEL, VOYAGE, yard_area, container_delivery
											WHERE  REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
											AND REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
											AND NOTA_DELIVERY.NO_REQUEST(+) = REQUEST_DELIVERY.NO_REQUEST
											AND request_delivery.NO_REQUEST = container_delivery.NO_REQUEST
									 AND container_delivery.AKTIF = 'Y'
										   AND request_delivery.PERALIHAN NOT IN ('RELOKASI','STUFFING','STRIPPING')
										   AND request_delivery.DELIVERY_KE = 'LUAR'
											AND nota_delivery.LUNAS = 'YES'
											GROUP BY REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd Mon yyyy'), TO_CHAR(TGL_REQUEST_DELIVERY,'dd Mon yyyy'), request_delivery.STATUS,
										emkl.NM_PBM, request_delivery.VOYAGE, request_delivery.VESSEL, yard_area.NAMA_YARD, NVL(request_delivery.PERP_DARI,''), request_delivery.PERP_KE, NVL(nota_delivery.LUNAS,0), NVL(nota_delivery.CETAK_NOTA,0)
								  UNION 
										SELECT REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd Mon yyyy') TGL_REQUEST, TO_CHAR(TGL_REQUEST_DELIVERY,'dd Mon yyyy') TGL_REQUEST_DELIVERY, request_delivery.STATUS,COUNT(container_delivery.NO_CONTAINER) JUMLAH, 
										emkl.NM_PBM AS NAMA_EMKL, request_delivery.VOYAGE, request_delivery.VESSEL, yard_area.NAMA_YARD, NVL(request_delivery.PERP_DARI,'') PERP_DARI, request_delivery.PERP_KE, NVL(nota_delivery.LUNAS,0) LUNAS, NVL(nota_delivery.CETAK_NOTA,0) CETAK_NOTA
											FROM REQUEST_DELIVERY, NOTA_DELIVERY, V_MST_PBM emkl, VESSEL, VOYAGE, yard_area, container_delivery
											WHERE  REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
											AND REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
											AND NOTA_DELIVERY.NO_REQUEST(+) = REQUEST_DELIVERY.NO_REQUEST
											AND request_delivery.NO_REQUEST = container_delivery.NO_REQUEST
										   AND request_delivery.DELIVERY_KE = 'LUAR'
											AND request_delivery.STATUS = 'EXT'
											GROUP BY REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd Mon yyyy'), TO_CHAR(TGL_REQUEST_DELIVERY,'dd Mon yyyy'), request_delivery.STATUS,
										emkl.NM_PBM, request_delivery.VOYAGE, request_delivery.VESSEL, yard_area.NAMA_YARD, NVL(request_delivery.PERP_DARI,''), request_delivery.PERP_KE, NVL(nota_delivery.LUNAS,0), NVL(nota_delivery.CETAK_NOTA,0)
								  "; 
								  
								  
				}   
         
        
//        
//	if(isset($_POST["cari"]))
//	{
//		$query_list = "SELECT REQUEST_DELIVERY.NO_REQUEST,TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, emkl.NAMA AS NAMA_EMKL, pnmt.NAMA AS NAMA_PNMT 
//                    FROM REQUEST_DELIVERY 
//                    INNER JOIN MASTER_PBM emkl ON REQUEST_DELIVERY.ID_EMKL = emkl.ID 
//                    JOIN MASTER_PBM pnmt ON REQUEST_DELIVERY.ID_PEMILIK = pnmt.ID 
//                    WHERE REQUEST_DELIVERY.TGL_REQUEST 
//                    BETWEEN TO_DATE('".$from."', 'dd/mm/yyyy') AND 
//                    TO_DATE('".$to."', 'dd/mm/yyyy') 
//                    ORDER BY REQUEST_DELIVERY.NO_REQUEST DESC";
//	}
//	else
//	{
//		$query_list = "SELECT request_delivery.NO_REQUEST,TO_CHAR( request_delivery.TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, TO_CHAR( request_delivery.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, emkl.NAMA AS NAMA_EMKL, COUNT(contdev.NO_CONTAINER) TOTAL, request_delivery.PERP_DARI, request_delivery.PERP_KE, NVL(nota_delivery.LUNAS,0) LUNAS
//FROM request_delivery, container_delivery contdev, master_pbm emkl, nota_delivery
//WHERE request_delivery.ID_EMKL = emkl.ID 
//AND request_delivery.NO_REQUEST = contdev.NO_REQUEST
//AND request_delivery.NO_REQUEST = nota_delivery.NO_REQUEST(+)
//GROUP BY request_delivery.NO_REQUEST,TO_CHAR( request_delivery.TGL_REQUEST_DELIVERY,'dd/mm/yyyy') , TO_CHAR( request_delivery.TGL_REQUEST,'dd/mm/yyyy') , emkl.NAMA, request_delivery.PERP_DARI, request_delivery.PERP_KE, NVL(nota_delivery.LUNAS,0)
//ORDER BY request_delivery.NO_REQUEST DESC";
//	}
*/	
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
	
	$i=0;
	foreach($row_list as $row){
		$no[$i]		 = $row['__no'];
		$no_request[$i]		 = $row['NO_REQUEST'];
		$jumlah[$i]		 = $row['JUMLAH'];
		$query				= "SELECT COUNT(NO_CONTAINER) TOTAL FROM container_delivery WHERE no_request = '".$no_request[$i]."' AND AKTIF = 'Y'";
		$perp				= $db->query($query);
		$perp_       		= $perp->fetchRow(); 
		$total[$i]			= $perp_['TOTAL'];
		$nota[$i] 			 = $row['NOTA'];
		$ex_req[$i] 		 = $row['PERP_DARI'];
		$tgl_request[$i] 	 = $row['TGL_REQUEST'];
		$tgl_request_delivery[$i] = $row['TGL_REQUEST_DELIVERY'];
		$nama_pbm[$i] 		 = $row['NM_PBM'];
		$koreksi[$i] 		 = $row['KOREKSI'];
		$i++;
	}
	$tl->assign("no",$no);
	$tl->assign("jumlah",$jumlah);
	$tl->assign("no_request",$no_request);
	$tl->assign("total",$total);
	$tl->assign("tgl_request",$tgl_request);
	$tl->assign("tgl_req_dev",$tgl_request_delivery);
	$tl->assign("nm_pbm",$nama_pbm);
	$tl->assign("ex_req",$ex_req);
	$tl->assign("nota",$nota);
	$tl->assign("koreksi",$koreksi);
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
