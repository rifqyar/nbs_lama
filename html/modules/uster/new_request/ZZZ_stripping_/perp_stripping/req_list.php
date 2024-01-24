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
                        $query_list = " SELECT a.NO_REQUEST, a.PERP_DARI, b.NM_PBM, COUNT(c.NO_CONTAINER) JUMLAH, a.TGL_REQUEST, a.NOTA, c.TGL_APPROVE FROM request_stripping a, v_mst_pbm b, container_stripping c
                                    WHERE a.NO_REQUEST = c.NO_REQUEST 
                                    AND a.KD_CONSIGNEE = b.KD_PBM
                                    AND c.TGL_REALISASI IS NULL 
                                    AND a.NOTA IS NOT NULL
									AND a.NO_REQUEST = '$no_req'
                                    GROUP BY a.NO_REQUEST, a.PERP_DARI, b.NM_PBM,  a.TGL_REQUEST, a.NOTA, c.TGL_APPROVE
                                    ORDER BY a.TGL_REQUEST DESC";

					}
					else if(($no_req == NULL) && (isset($_POST["from"])) && (isset($_POST["to"])))
					{
                                                $query_list = " SELECT a.NO_REQUEST, a.PERP_DARI, b.NM_PBM, COUNT(c.NO_CONTAINER) JUMLAH, a.TGL_REQUEST, a.NOTA, c.TGL_APPROVE FROM request_stripping a, v_mst_pbm b, container_stripping c
                                    WHERE a.NO_REQUEST = c.NO_REQUEST 
                                    AND a.KD_CONSIGNEE = b.KD_PBM
                                    AND c.TGL_REALISASI IS NULL 
                                    AND a.NOTA IS NOT NULL
									AND a.TGL_REQUEST BETWEEN TO_DATE('$from','yyyy/mm/dd') AND TO_DATE('$to','yyyy/mm/dd')
                                    GROUP BY a.NO_REQUEST, a.PERP_DARI, b.NM_PBM,  a.TGL_REQUEST, a.NOTA, c.TGL_APPROVE
                                    ORDER BY a.TGL_REQUEST DESC";

                  } else if((isset($_POST["no_req"]))&& (isset($_POST["from"])) && (isset($_POST["to"])))
                  {
                                                $query_list = " SELECT a.NO_REQUEST, a.PERP_DARI, b.NM_PBM, COUNT(c.NO_CONTAINER) JUMLAH, a.TGL_REQUEST, a.NOTA, c.TGL_APPROVE FROM request_stripping a, v_mst_pbm b, container_stripping c
                                    WHERE a.NO_REQUEST = c.NO_REQUEST 
                                    AND a.KD_CONSIGNEE = b.KD_PBM
                                    AND c.TGL_REALISASI IS NULL 
                                    AND a.NOTA IS NOT NULL
									AND a.NO_REQUEST = '$no_req'
									AND a.TGL_REQUEST BETWEEN TO_DATE('$from','yyyy/mm/dd') AND TO_DATE('$to','yyyy/mm/dd')
                                    GROUP BY a.NO_REQUEST, a.PERP_DARI, b.NM_PBM,  a.TGL_REQUEST, a.NOTA, c.TGL_APPROVE
                                    ORDER BY a.TGL_REQUEST DESC";
				}
               } else {
     /*      $query_list     = "  SELECT REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd Mon yyyy') TGL_REQUEST, TO_CHAR(TGL_REQUEST_DELIVERY,'dd Mon yyyy') TGL_REQUEST_DELIVERY, request_delivery.STATUS, 
                    emkl.NM_PBM AS NAMA_EMKL, request_delivery.VOYAGE, request_delivery.VESSEL, yard_area.NAMA_YARD, NVL(request_delivery.PERP_DARI,'') PERP_DARI, request_delivery.PERP_KE, NVL(nota_delivery.LUNAS,0) LUNAS, NVL(nota_delivery.CETAK_NOTA,0) CETAK_NOTA,COUNT(container_delivery.NO_CONTAINER) JUMLAH
                        FROM REQUEST_DELIVERY, NOTA_DELIVERY, v_mst_pbm emkl, VESSEL, VOYAGE, yard_area, container_delivery
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
           
					$query_list = " SELECT a.NO_REQUEST, a.PERP_DARI, b.NM_PBM, COUNT(c.NO_CONTAINER) JUMLAH, a.TGL_REQUEST, a.NOTA, c.TGL_APPROVE FROM request_stripping a, v_mst_pbm b, container_stripping c
                                    WHERE a.NO_REQUEST = c.NO_REQUEST 
                                    AND a.KD_CONSIGNEE = b.KD_PBM
                                    AND c.TGL_REALISASI IS NULL 
                                    AND a.NOTA IS NOT NULL
                                    GROUP BY a.NO_REQUEST, a.PERP_DARI, b.NM_PBM,  a.TGL_REQUEST, a.NOTA, c.TGL_APPROVE
                                    ORDER BY a.TGL_REQUEST DESC"; 
								  
								  
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
	
	$result_list	= $db->query($query_list);
	$row_list       = $result_list->getAll(); 
		
	
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
