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
         INNER JOIN v_mst_pbm pbm ON REQUEST_STRIPPING.KD_CONSIGNEE = pbm.KD_PBM
         INNER JOIN container_stripping ON REQUEST_STRIPPING.NO_REQUEST = container_stripping.NO_REQUEST
        WHERE request_stripping.NO_REQUEST = '$no_req'
		AND request_stripping.PERP_DARI IS NULL
        GROUP BY REQUEST_STRIPPING.TGL_REQUEST, request_stripping.NO_REQUEST,  emkl.NM_PBM, pbm.NM_PBM, request_stripping.TGL_APPROVE,request_stripping.NO_DO, request_stripping.NO_BL
        ORDER BY REQUEST_STRIPPING.NO_REQUEST DESC";
		}
		else if((isset($_POST["FROM"]))&& (isset($_POST["TO"])) && ($no_req == NULL))
		{
			$query_list		= "SELECT REQUEST_STRIPPING.TGL_REQUEST, REQUEST_STRIPPING.TGL_APPROVE, request_stripping.NO_DO, request_stripping.NO_BL,request_stripping.NO_REQUEST, emkl.NM_PBM AS PENUMPUKAN_OLEH, pbm.NM_PBM AS CONSIGNEE, COUNT(container_stripping.NO_CONTAINER) AS JML
        FROM REQUEST_STRIPPING INNER JOIN v_mst_pbm emkl ON REQUEST_STRIPPING.KD_PENUMPUKAN_OLEH = emkl.KD_PBM
         INNER JOIN v_mst_pbm pbm ON REQUEST_STRIPPING.KD_CONSIGNEE = pbm.KD_PBM
         INNER JOIN container_stripping ON REQUEST_STRIPPING.NO_REQUEST = container_stripping.NO_REQUEST
        WHERE request_stripping.PERP_DARI IS NULL
		AND REQUEST_STRIPPING.TGL_REQUEST BETWEEN TO_DATE ( '$from', 'YYYY-MM-DD ')
        AND TO_DATE (  CONCAT('$to', '23:59:59'), 'YYYY-MM-DD HH24:MI:SS')
        GROUP BY REQUEST_STRIPPING.TGL_REQUEST, request_stripping.NO_REQUEST,  emkl.NM_PBM, pbm.NM_PBM, request_stripping.TGL_APPROVE,request_stripping.NO_DO, request_stripping.NO_BL
        ORDER BY REQUEST_STRIPPING.NO_REQUEST DESC";
		} else if((isset($_POST["FROM"]))&& (isset($_POST["TO"])) && (isset($_POST["NO_REQ"])))
		{
			$query_list		= "SELECT REQUEST_STRIPPING.TGL_REQUEST, REQUEST_STRIPPING.TGL_APPROVE, request_stripping.NO_DO, request_stripping.NO_BL,request_stripping.NO_REQUEST, emkl.NM_PBM AS PENUMPUKAN_OLEH, pbm.NM_PBM AS CONSIGNEE, COUNT(container_stripping.NO_CONTAINER) AS JML
        FROM REQUEST_STRIPPING INNER JOIN v_mst_pbm emkl ON REQUEST_STRIPPING.KD_PENUMPUKAN_OLEH = emkl.KD_PBM
         INNER JOIN v_mst_pbm pbm ON REQUEST_STRIPPING.KD_CONSIGNEE = pbm.KD_PBM
         INNER JOIN container_stripping ON REQUEST_STRIPPING.NO_REQUEST = container_stripping.NO_REQUEST
        WHERE request_stripping.NO_REQUEST = '$no_req'
		AND request_stripping.PERP_DARI IS NULL
		AND REQUEST_STRIPPING.TGL_REQUEST BETWEEN TO_DATE ( '$from', 'YYYY-MM-DD ')
        AND TO_DATE (  CONCAT('$to', '23:59:59'), 'YYYY-MM-DD HH24:MI:SS')
        GROUP BY REQUEST_STRIPPING.TGL_REQUEST, request_stripping.NO_REQUEST,  emkl.NM_PBM, pbm.NM_PBM, request_stripping.TGL_APPROVE,request_stripping.NO_DO, request_stripping.NO_BL
        ORDER BY REQUEST_STRIPPING.NO_REQUEST DESC";
		}
		
	}
	else
	{
		$query_list  = "   SELECT  request_stripping.PERP_DARI , REQUEST_STRIPPING.TGL_REQUEST, REQUEST_STRIPPING.TGL_APPROVE, request_stripping.NO_DO, request_stripping.NO_BL,request_stripping.NO_REQUEST, emkl.NM_PBM AS PENUMPUKAN_OLEH,  COUNT(container_stripping.NO_CONTAINER) AS JML
        FROM REQUEST_STRIPPING INNER JOIN v_mst_pbm emkl ON REQUEST_STRIPPING.KD_PENUMPUKAN_OLEH = emkl.KD_PBM
         INNER JOIN container_stripping ON REQUEST_STRIPPING.NO_REQUEST = container_stripping.NO_REQUEST
        WHERE request_stripping.PERP_DARI IS NULL
        GROUP BY  request_stripping.PERP_DARI ,REQUEST_STRIPPING.TGL_REQUEST, request_stripping.NO_REQUEST,  emkl.NM_PBM, request_stripping.TGL_APPROVE,request_stripping.NO_DO, request_stripping.NO_BL
        ORDER BY REQUEST_STRIPPING.NO_REQUEST DESC";
	}
	
	$result_list	= $db->query($query_list);
	$row_list	= $result_list->getAll(); 
		
	
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
	
	function cek_nota($no_req)
	{
		$db 		= getDB("storage");
		$query_cek	= "SELECT b.NO_NOTA, NVL(b.LUNAS,0) LUNAS, NVL(b.NO_NOTA,0) NO_NOTA FROM request_stripping a, nota_stripping b WHERE a.NO_REQUEST = b.NO_REQUEST(+) AND a.NO_REQUEST = '$no_req'";
        //echo $query_cek;
		$result_cek	= $db->query($query_cek);
		$row_cek 	= $result_cek->fetchRow();
		
		if(count($row_cek) > 0)
		{
			$cetak		= $row_cek["NO_NOTA"];
			$lunas		= $row_cek["LUNAS"];
            $no_nota    = $row_cek["NO_NOTA"];
			//echo $lunas;
			if (($cetak == NULL) && ($lunas == 'NO'))
			{
				echo '<a href="'.HOME.APPID.'/preview_nota?no_req='.$no_req.'&n=999" target="_self"> Preview Nota </a> ';
			}
			else  if (($row_cek[0]["NO_NOTA"] <> NULL) && ($row_cek[0]["LUNAS"] == 'YES'))
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
			}
		
	}
	}
?>
