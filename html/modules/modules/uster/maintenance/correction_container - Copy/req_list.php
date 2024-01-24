<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl =  xliteTemplate('req_list.htm');

        $cari	= $_POST["cari"];
	$no_req	= $_POST["no_req"]; 
	$from   = $_POST["from"];
	$to     = $_POST["to"];
        $id_yard    = 	$_SESSION["IDYARD_STORAGE"];
	
        $db = getDB("storage");
        
            	if(isset($_POST["cari"]) ) 
            	{   
                                        if((isset($_POST["no_req"])) && ($from == NULL) && ($to == NULL))
                                        {
                                                $query_list     = "SELECT * FROM (
						SELECT REQUEST_DELIVERY.NO_REQUEST, 'DELIVERY' KEGIATAN, 
                                 REQUEST_DELIVERY.TGL_REQUEST,
                                 emkl.NM_PBM AS NAMA_EMKL, COUNT(CONTAINER_DELIVERY.NO_CONTAINER) JUMLAH_CONT
                            FROM REQUEST_DELIVERY LEFT JOIN CONTAINER_DELIVERY ON REQUEST_DELIVERY.NO_REQUEST = CONTAINER_DELIVERY.NO_REQUEST
                            LEFT JOIN V_MST_PBM emkl ON REQUEST_DELIVERY.KD_EMKL= emkl.KD_PBM
                            LEFT JOIN yard_area ON REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
                           WHERE         
                                 request_delivery.PERALIHAN NOT IN ('RELOKASI', 'STUFFING', 'STRIPPING')
                           GROUP BY REQUEST_DELIVERY.NO_REQUEST,
                                 REQUEST_DELIVERY.TGL_REQUEST,
                                 emkl.NM_PBM,
                                 yard_area.NAMA_YARD, REQUEST_DELIVERY.DELIVERY_KE, 'DELIVERY'
						--ORDER BY REQUEST_DELIVERY.NO_REQUEST DESC
						UNION
						SELECT  A.NO_REQUEST, 'RECEIVING' KEGIATAN, A.TGL_REQUEST, b.NM_PBM AS NAMA_EMKL, COUNT(D.NO_CONTAINER) JUMLAH_CONT
						FROM REQUEST_RECEIVING a, CONTAINER_RECEIVING d,
							 V_MST_PBM b, YARD_AREA c
						WHERE a.RECEIVING_DARI IN ('LUAR','TPK')
						AND a.KD_CONSIGNEE = b.KD_PBM
						AND A.ID_YARD = C.ID
						AND A.NO_REQUEST(+) = D.NO_REQUEST						
						GROUP BY A.NO_REQUEST,A.TGL_REQUEST, b.NM_PBM, 'RECEIVING'
						--ORDER BY A.NO_REQUEST DESC
						UNION
						SELECT REQUEST_STRIPPING.NO_REQUEST, 'STRIPPING' KEGIATAN, REQUEST_STRIPPING.TGL_REQUEST, 
						emkl.NM_PBM AS NAMA_PEMILIK, COUNT(CONTAINER_STRIPPING.NO_CONTAINER) JUMLAH_CONT
						FROM V_MST_PBM emkl, REQUEST_STRIPPING, CONTAINER_STRIPPING
						WHERE REQUEST_STRIPPING.NO_REQUEST(+) = CONTAINER_STRIPPING.NO_REQUEST
						AND REQUEST_STRIPPING.KD_CONSIGNEE = EMKL.KD_PBM						
						GROUP BY  REQUEST_STRIPPING.NO_REQUEST, REQUEST_STRIPPING.TGL_REQUEST, emkl.NM_PBM, 'STRIPPING'
						--ORDER BY REQUEST_STRIPPING.NO_REQUEST DESC
						UNION
						SELECT REQUEST_STUFFING.NO_REQUEST, 'STUFFING' KEGIATAN,  REQUEST_STUFFING.TGL_REQUEST, 
						emkl.NM_PBM AS NAMA_PEMILIK, COUNT(CONTAINER_STUFFING.NO_CONTAINER) JUMLAH_CONT
						FROM V_MST_PBM emkl, REQUEST_STUFFING, CONTAINER_STUFFING
						WHERE REQUEST_STUFFING.NO_REQUEST(+) = CONTAINER_STUFFING.NO_REQUEST
						AND REQUEST_STUFFING.KD_CONSIGNEE = EMKL.KD_PBM						
						GROUP BY  REQUEST_STUFFING.NO_REQUEST, REQUEST_STUFFING.TGL_REQUEST, emkl.NM_PBM, 'STUFFING'
						--ORDER BY REQUEST_STUFFING.NO_REQUEST DESC
						UNION
						SELECT REQUEST_RELOKASI.NO_REQUEST, 'RELOKASI' KEGIATAN, REQUEST_RELOKASI.TGL_REQUEST, 
						emkl.NM_PBM AS NAMA_PEMILIK, COUNT(CONTAINER_RELOKASI.NO_CONTAINER) JUMLAH_CONT
						FROM V_MST_PBM emkl, REQUEST_RELOKASI, CONTAINER_RELOKASI
						WHERE REQUEST_RELOKASI.NO_REQUEST(+) = CONTAINER_RELOKASI.NO_REQUEST
						AND REQUEST_RELOKASI.KD_EMKL = EMKL.KD_PBM						
						GROUP BY  REQUEST_RELOKASI.NO_REQUEST, REQUEST_RELOKASI.TGL_REQUEST, emkl.NM_PBM, 'RELOKASI'
						) join_all WHERE join_all.NO_REQUEST = '$no_req' ORDER BY join_all.NO_REQUEST DESC,  join_all.KEGIATAN";
						echo $query_list;

                                        }
                                        else if(($no_req == NULL) && (isset($_POST["from"])) && (isset($_POST["to"])))
                                        {
                                                $query_list = "SELECT NVL(NOTA_DELIVERY.LUNAS, 0) LUNAS, REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd Mon yyyy') TGL_REQUEST, TO_DATE(TGL_REQUEST_DELIVERY,'dd Mon yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM as NAMA_EMKL ,request_delivery.VESSEL as NAMA_VESSEL, request_delivery.VOYAGE, yard_area.NAMA_YARD
                        FROM REQUEST_DELIVERY, NOTA_DELIVERY, V_MST_PBM emkl, yard_area
                         WHERE  REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
                        AND REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
                        AND NOTA_DELIVERY.NO_REQUEST(+) = REQUEST_DELIVERY.NO_REQUEST
                        AND request_delivery.TGL_REQUEST_DELIVERY BETWEEN TO_DATE('$from','yyyy/mm/dd') AND TO_DATE('$to','yyyy/mm/dd')
                       AND request_delivery.PERALIHAN NOT IN ('RELOKASI','STUFFING','STRIPPING')
						AND request_delivery.DELIVERY_KE = 'LUAR'
                        ORDER BY REQUEST_DELIVERY.NO_REQUEST DESC";

                                        } else if((isset($_POST["no_req"]))&& (isset($_POST["from"])) && (isset($_POST["to"])))
                                        {
                                                $query_list = "SELECT NVL(NOTA_DELIVERY.LUNAS, 0) LUNAS, REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd Mon yyyy') TGL_REQUEST, TO_DATE(TGL_REQUEST_DELIVERY,'dd Mon yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM as NAMA_EMKL ,request_delivery.VESSEL as NAMA_VESSEL, request_delivery.VOYAGE, yard_area.NAMA_YARD
                        FROM REQUEST_DELIVERY, NOTA_DELIVERY, V_MST_PBM emkl, yard_area
                         WHERE  REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
                        AND REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
                        AND NOTA_DELIVERY.NO_REQUEST(+) = REQUEST_DELIVERY.NO_REQUEST
                        AND request_delivery.NO_REQUEST = '$no_req'
                        AND request_delivery.TGL_REQUEST_DELIVERY BETWEEN TO_DATE('$from','yyyy/mm/dd') AND TO_DATE('$to','yyyy/mm/dd')
                       AND request_delivery.PERALIHAN NOT IN ('RELOKASI','STUFFING','STRIPPING') 
						AND request_delivery.DELIVERY_KE = 'LUAR'
                        ORDER BY REQUEST_DELIVERY.NO_REQUEST DESC";
                        } 
                } else {
                        $query_list     = "SELECT * FROM (
						SELECT REQUEST_DELIVERY.NO_REQUEST, 'DELIVERY' KEGIATAN, 
                                 REQUEST_DELIVERY.TGL_REQUEST,
                                 emkl.NM_PBM AS NAMA_EMKL, COUNT(CONTAINER_DELIVERY.NO_CONTAINER) JUMLAH_CONT
                            FROM REQUEST_DELIVERY LEFT JOIN CONTAINER_DELIVERY ON REQUEST_DELIVERY.NO_REQUEST = CONTAINER_DELIVERY.NO_REQUEST
                            LEFT JOIN V_MST_PBM emkl ON REQUEST_DELIVERY.KD_EMKL= emkl.KD_PBM
                            LEFT JOIN yard_area ON REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
                           WHERE         
                                 request_delivery.PERALIHAN NOT IN ('RELOKASI', 'STUFFING', 'STRIPPING')
                           GROUP BY REQUEST_DELIVERY.NO_REQUEST,
                                 REQUEST_DELIVERY.TGL_REQUEST,
                                 emkl.NM_PBM,
                                 yard_area.NAMA_YARD, REQUEST_DELIVERY.DELIVERY_KE, 'DELIVERY'
						--ORDER BY REQUEST_DELIVERY.NO_REQUEST DESC
						UNION
						SELECT  A.NO_REQUEST, 'RECEIVING' KEGIATAN, A.TGL_REQUEST, b.NM_PBM AS NAMA_EMKL, COUNT(D.NO_CONTAINER) JUMLAH_CONT
						FROM REQUEST_RECEIVING a, CONTAINER_RECEIVING d,
							 V_MST_PBM b, YARD_AREA c
						WHERE a.RECEIVING_DARI IN ('LUAR','TPK')
						AND a.KD_CONSIGNEE = b.KD_PBM
						AND A.ID_YARD = C.ID
						AND A.NO_REQUEST(+) = D.NO_REQUEST
						AND rownum <= 5
						GROUP BY A.NO_REQUEST,A.TGL_REQUEST, b.NM_PBM, 'RECEIVING'
						--ORDER BY A.NO_REQUEST DESC
						UNION
						SELECT REQUEST_STRIPPING.NO_REQUEST, 'STRIPPING' KEGIATAN, REQUEST_STRIPPING.TGL_REQUEST, 
						emkl.NM_PBM AS NAMA_PEMILIK, COUNT(CONTAINER_STRIPPING.NO_CONTAINER) JUMLAH_CONT
						FROM V_MST_PBM emkl, REQUEST_STRIPPING, CONTAINER_STRIPPING
						WHERE REQUEST_STRIPPING.NO_REQUEST(+) = CONTAINER_STRIPPING.NO_REQUEST
						AND REQUEST_STRIPPING.KD_CONSIGNEE = EMKL.KD_PBM
						AND ROWNUM <= 5
						GROUP BY  REQUEST_STRIPPING.NO_REQUEST, REQUEST_STRIPPING.TGL_REQUEST, emkl.NM_PBM, 'STRIPPING'
						--ORDER BY REQUEST_STRIPPING.NO_REQUEST DESC
						UNION
						SELECT REQUEST_STUFFING.NO_REQUEST, 'STUFFING' KEGIATAN,  REQUEST_STUFFING.TGL_REQUEST, 
						emkl.NM_PBM AS NAMA_PEMILIK, COUNT(CONTAINER_STUFFING.NO_CONTAINER) JUMLAH_CONT
						FROM V_MST_PBM emkl, REQUEST_STUFFING, CONTAINER_STUFFING
						WHERE REQUEST_STUFFING.NO_REQUEST(+) = CONTAINER_STUFFING.NO_REQUEST
						AND REQUEST_STUFFING.KD_CONSIGNEE = EMKL.KD_PBM
						AND ROWNUM <= 5
						GROUP BY  REQUEST_STUFFING.NO_REQUEST, REQUEST_STUFFING.TGL_REQUEST, emkl.NM_PBM, 'STUFFING'
						--ORDER BY REQUEST_STUFFING.NO_REQUEST DESC
						UNION
						SELECT REQUEST_RELOKASI.NO_REQUEST, 'RELOKASI' KEGIATAN, REQUEST_RELOKASI.TGL_REQUEST, 
						emkl.NM_PBM AS NAMA_PEMILIK, COUNT(CONTAINER_RELOKASI.NO_CONTAINER) JUMLAH_CONT
						FROM V_MST_PBM emkl, REQUEST_RELOKASI, CONTAINER_RELOKASI
						WHERE REQUEST_RELOKASI.NO_REQUEST(+) = CONTAINER_RELOKASI.NO_REQUEST
						AND REQUEST_RELOKASI.KD_EMKL = EMKL.KD_PBM
						AND ROWNUM <= 5
						GROUP BY  REQUEST_RELOKASI.NO_REQUEST, REQUEST_RELOKASI.TGL_REQUEST, emkl.NM_PBM, 'RELOKASI'
						) join_all ORDER BY join_all.NO_REQUEST DESC,  join_all.KEGIATAN";
                } 	
	$result_list	= $db->query($query_list);
	$row_list       = $result_list->getAll(); 
		
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
        
?>