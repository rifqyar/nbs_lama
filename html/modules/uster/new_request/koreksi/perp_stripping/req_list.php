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
                                            
                                             $query_list = "SELECT REQUEST_STRIPPING.NO_REQUEST, 
                        TO_CHAR( REQUEST_STRIPPING.TGL_APPROVE,'dd/mm/yyyy') TGL_APPROVE, 
                        emkl.NM_PBM AS NAMA_CONSIGNEE,
                        pnmt.NM_PBM AS NAMA_PENUMPUK, 
                        NVL(REQUEST_STRIPPING.PERP_DARI,'-') AS EX_REQ, 
                        REQUEST_STRIPPING.PERP_KE,
                        REQUEST_STRIPPING.NO_DO,
                        REQUEST_STRIPPING.NO_BL,
                        REQUEST_STRIPPING.NOTA,
                        NVL(nota_stripping.LUNAS, 0) LUNAS,
                        REQUEST_STRIPPING.TGL_REQUEST,
						COUNT(container_stripping.NO_CONTAINER) JUMLAH
                FROM REQUEST_STRIPPING,V_MST_PBM emkl, V_MST_PBM pnmt,CONTAINER_STRIPPING, NOTA_STRIPPING
                WHERE REQUEST_STRIPPING.KD_CONSIGNEE = emkl.KD_PBM 
                AND REQUEST_STRIPPING.KD_PENUMPUKAN_OLEH = pnmt.KD_PBM
                AND REQUEST_STRIPPING.NO_REQUEST = CONTAINER_STRIPPING.NO_REQUEST 
                AND REQUEST_STRIPPING.NO_REQUEST = NOTA_STRIPPING.NO_REQUEST(+)
				AND  REQUEST_STRIPPING.NO_REQUEST  = '$no_req'
				AND request_stripping.KOREKSI = 'Y'
                GROUP BY REQUEST_STRIPPING.NO_REQUEST,REQUEST_STRIPPING.NO_DO,REQUEST_STRIPPING.TGL_REQUEST, NVL(nota_stripping.LUNAS, 0),
                        REQUEST_STRIPPING.NOTA,REQUEST_STRIPPING.NO_BL, REQUEST_STRIPPING.TGL_APPROVE, TO_CHAR( REQUEST_STRIPPING.TGL_AWAL,'dd/mm/yyyy'), emkl.NM_PBM, pnmt.NM_PBM,  TO_CHAR( REQUEST_STRIPPING.TGL_AKHIR,'dd/mm/yyyy'), REQUEST_STRIPPING.PERP_DARI, REQUEST_STRIPPING.PERP_KE
                ORDER BY REQUEST_STRIPPING.TGL_REQUEST DESC";		

                                        }
                                        else if ($_POST["no_req"] == NULL && $_POST["from"] != NULL && $_POST["to"] != NULL)
                                        {
$query_list = "SELECT REQUEST_STRIPPING.NO_REQUEST, 
                        TO_CHAR( REQUEST_STRIPPING.TGL_APPROVE,'dd/mm/yyyy') TGL_APPROVE, 
                        emkl.NM_PBM AS NAMA_CONSIGNEE,
                        pnmt.NM_PBM AS NAMA_PENUMPUK, 
                        NVL(REQUEST_STRIPPING.PERP_DARI,'-') AS EX_REQ, 
                        REQUEST_STRIPPING.PERP_KE,
                        REQUEST_STRIPPING.NO_DO,
                        REQUEST_STRIPPING.NO_BL,
                        REQUEST_STRIPPING.NOTA,
                        NVL(nota_stripping.LUNAS, 0) LUNAS,
                        REQUEST_STRIPPING.TGL_REQUEST,
				COUNT(container_stripping.NO_CONTAINER) JUMLAH
                FROM REQUEST_STRIPPING,V_MST_PBM emkl, V_MST_PBM pnmt,CONTAINER_STRIPPING, NOTA_STRIPPING
                WHERE REQUEST_STRIPPING.KD_CONSIGNEE = emkl.KD_PBM 
                AND REQUEST_STRIPPING.KD_PENUMPUKAN_OLEH = pnmt.KD_PBM
                AND REQUEST_STRIPPING.NO_REQUEST = CONTAINER_STRIPPING.NO_REQUEST 
                AND REQUEST_STRIPPING.NO_REQUEST = NOTA_STRIPPING.NO_REQUEST(+)
				AND request_stripping.KOREKSI = 'Y'
				 AND REQUEST_STRIPPING.TGL_REQUEST BETWEEN TO_DATE ( '$from', 'dd-mm-rrrr') AND TO_DATE ( '$to', 'dd-mm-rrrr') 
                GROUP BY REQUEST_STRIPPING.NO_REQUEST,REQUEST_STRIPPING.NO_DO,REQUEST_STRIPPING.TGL_REQUEST, NVL(nota_stripping.LUNAS, 0),
                        REQUEST_STRIPPING.NOTA,REQUEST_STRIPPING.NO_BL, REQUEST_STRIPPING.TGL_APPROVE, TO_CHAR( REQUEST_STRIPPING.TGL_AWAL,'dd/mm/yyyy'), emkl.NM_PBM, pnmt.NM_PBM,  TO_CHAR( REQUEST_STRIPPING.TGL_AKHIR,'dd/mm/yyyy'), REQUEST_STRIPPING.PERP_DARI, REQUEST_STRIPPING.PERP_KE
                ORDER BY REQUEST_STRIPPING.TGL_REQUEST DESC";			

                                        } else if((isset($_POST["no_req"]))&& (isset($_POST["from"])) && (isset($_POST["to"])))
                                        {
                                             $query_list = "SELECT REQUEST_STRIPPING.NO_REQUEST, 
                        TO_CHAR( REQUEST_STRIPPING.TGL_APPROVE,'dd/mm/yyyy') TGL_APPROVE, 
                        emkl.NM_PBM AS NAMA_CONSIGNEE,
                        pnmt.NM_PBM AS NAMA_PENUMPUK, 
                        NVL(REQUEST_STRIPPING.PERP_DARI,'-') AS EX_REQ, 
                        REQUEST_STRIPPING.PERP_KE,
                        REQUEST_STRIPPING.NO_DO,
                        REQUEST_STRIPPING.NO_BL,
                        REQUEST_STRIPPING.NOTA,
                        NVL(nota_stripping.LUNAS, 0) LUNAS,
                        REQUEST_STRIPPING.TGL_REQUEST,
						COUNT(container_stripping.NO_CONTAINER) JUMLAH
                FROM REQUEST_STRIPPING,V_MST_PBM emkl, V_MST_PBM pnmt,CONTAINER_STRIPPING, NOTA_STRIPPING
                WHERE REQUEST_STRIPPING.KD_CONSIGNEE = emkl.KD_PBM 
                AND REQUEST_STRIPPING.KD_PENUMPUKAN_OLEH = pnmt.KD_PBM
                AND REQUEST_STRIPPING.NO_REQUEST = CONTAINER_STRIPPING.NO_REQUEST 
                AND REQUEST_STRIPPING.NO_REQUEST = NOTA_STRIPPING.NO_REQUEST(+)
				AND  REQUEST_STRIPPING.NO_REQUEST  = '$no_req'
				AND request_stripping.KOREKSI = 'Y'
				 AND REQUEST_STRIPPING.TGL_REQUEST BETWEEN TO_DATE ( '$from', 'dd-mm-rrrr') AND TO_DATE ( '$to', 'dd-mm-rrrr')
                GROUP BY REQUEST_STRIPPING.NO_REQUEST,REQUEST_STRIPPING.NO_DO,REQUEST_STRIPPING.TGL_REQUEST, NVL(nota_stripping.LUNAS, 0),
                        REQUEST_STRIPPING.NOTA,REQUEST_STRIPPING.NO_BL, REQUEST_STRIPPING.TGL_APPROVE, TO_CHAR( REQUEST_STRIPPING.TGL_AWAL,'dd/mm/yyyy'), emkl.NM_PBM, pnmt.NM_PBM,  TO_CHAR( REQUEST_STRIPPING.TGL_AKHIR,'dd/mm/yyyy'), REQUEST_STRIPPING.PERP_DARI, REQUEST_STRIPPING.PERP_KE
                ORDER BY REQUEST_STRIPPING.TGL_REQUEST DESC";		
                        } 	}
	else
	{
	/*	$query_list = "SELECT REQUEST_STRIPPING.NO_REQUEST, TO_CHAR( REQUEST_STRIPPING.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_CHAR( REQUEST_STRIPPING.TGL_REQUEST + 3,'dd/mm/yyyy') AS TGL_REQUEST_END, emkl.NAMA AS NAMA_EMKL, pnmt.NAMA AS NAMA_PNMT, COUNT(CONTAINER_STRIPPING.NO_CONTAINER) TOTAL, NVL(REQUEST_STRIPPING.PERP_DARI,'---') AS EX_REQ, REQUEST_STRIPPING.PERP_KE
				FROM REQUEST_STRIPPING INNER JOIN MASTER_PBM emkl ON REQUEST_STRIPPING.ID_EMKL = emkl.ID 
				JOIN MASTER_PBM pnmt ON REQUEST_STRIPPING.ID_PEMILIK = pnmt.ID 
				JOIN CONTAINER_STRIPPING ON REQUEST_STRIPPING.NO_REQUEST = CONTAINER_STRIPPING.NO_REQUEST AND 'Y' = CONTAINER_STRIPPING.AKTIF
				JOIN NOTA_STRIPPING ON REQUEST_STRIPPING.NO_REQUEST = NOTA_STRIPPING.NO_REQUEST AND 'YES' = LUNAS
				WHERE REQUEST_STRIPPING.TGL_REQUEST 
				BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) 
				GROUP BY REQUEST_STRIPPING.NO_REQUEST, TO_CHAR( REQUEST_STRIPPING.TGL_REQUEST,'dd/mm/yyyy'), emkl.NAMA, pnmt.NAMA,  TO_CHAR( REQUEST_STRIPPING.TGL_REQUEST + 3,'dd/mm/yyyy'), REQUEST_STRIPPING.PERP_DARI, REQUEST_STRIPPING.PERP_KE
				ORDER BY REQUEST_STRIPPING.NO_REQUEST DESC";
	*/
	$query_list = "SELECT REQUEST_STRIPPING.NO_REQUEST, 
                        TO_CHAR( REQUEST_STRIPPING.TGL_APPROVE,'dd/mm/yyyy') TGL_APPROVE, 
                        emkl.NM_PBM AS NAMA_CONSIGNEE,
                        pnmt.NM_PBM AS NAMA_PENUMPUK, 
                        NVL(REQUEST_STRIPPING.PERP_DARI,'-') AS EX_REQ, 
                        REQUEST_STRIPPING.PERP_KE,
                        REQUEST_STRIPPING.NO_DO,
                        REQUEST_STRIPPING.NO_BL,
                        REQUEST_STRIPPING.NOTA,
                        NVL(nota_stripping.LUNAS, 0) LUNAS,
                        REQUEST_STRIPPING.TGL_REQUEST,
						COUNT(container_stripping.NO_CONTAINER) JUMLAH
                FROM REQUEST_STRIPPING,V_MST_PBM emkl, V_MST_PBM pnmt,CONTAINER_STRIPPING, NOTA_STRIPPING
                WHERE REQUEST_STRIPPING.KD_CONSIGNEE = emkl.KD_PBM 
                AND REQUEST_STRIPPING.KD_PENUMPUKAN_OLEH = pnmt.KD_PBM
                AND REQUEST_STRIPPING.NO_REQUEST = CONTAINER_STRIPPING.NO_REQUEST 
                AND REQUEST_STRIPPING.NO_REQUEST = NOTA_STRIPPING.NO_REQUEST(+)
				AND request_stripping.KOREKSI = 'Y'
                GROUP BY REQUEST_STRIPPING.NO_REQUEST,REQUEST_STRIPPING.NO_DO,REQUEST_STRIPPING.TGL_REQUEST, NVL(nota_stripping.LUNAS, 0),
                        REQUEST_STRIPPING.NOTA,REQUEST_STRIPPING.NO_BL, REQUEST_STRIPPING.TGL_APPROVE, TO_CHAR( REQUEST_STRIPPING.TGL_AWAL,'dd/mm/yyyy'), emkl.NM_PBM, pnmt.NM_PBM,  TO_CHAR( REQUEST_STRIPPING.TGL_AKHIR,'dd/mm/yyyy'), REQUEST_STRIPPING.PERP_DARI, REQUEST_STRIPPING.PERP_KE
                ORDER BY REQUEST_STRIPPING.TGL_REQUEST DESC";
		
	}

	$result_list	= $db->query($query_list);
	$row_list       = $result_list->getAll(); 
	$i=0;	
	foreach ($row_list as $row){
		$no_request[$i] = $row['NO_REQUEST'];
		
		$cek_cont		= "SELECT COUNT(NO_CONTAINER) TOTAL FROM CONTAINER_STRIPPING WHERE NO_REQUEST = '".$no_request[$i]."' AND AKTIF = 'Y'";
		$cont_list		= $db->query($cek_cont);
		$row_list_      = $cont_list->fetchRow();
		$total[$i]		= $row_list_['TOTAL'];
		
		$tgl_approve[$i]	= $row['TGL_APPROVE'];
		$nama_cons[$i]	= $row['NAMA_CONSIGNEE'];
		$nama_penum[$i]	= $row['NAMA_PENUMPUK'];
		$ex_req[$i]		= $row['EX_REQ'];
		$perp_ke[$i]	= $row['PERP_KE'];
		$no_do[$i]		= $row['NO_DO'];
		$no_bl[$i]		= $row['NO_BL'];
		$nota[$i]		= $row['NOTA'];
		$lunas[$i]		= $row['LUNAS'];
		$tgl_req[$i]	= $row['TGL_REQUEST'];
		
		$jumlah[$i]	= $row['JUMLAH'];
	
	$i++;
	}
	
	$tl->assign("no_request",$no_request);
	$tl->assign("total",$total);
	$tl->assign("jumlah",$jumlah);
	$tl->assign("tgl_approve",$tgl_approve);
	$tl->assign("consignee",$nama_cons);
	$tl->assign("nama_penumpuk",$nama_penum);
	$tl->assign("ex_req",$ex_req);
	$tl->assign("nota",$nota);
	$tl->assign("perp_ke",$perp_ke);
	$tl->assign("no_do",$no_do);
	$tl->assign("no_bl",$no_bl);
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
