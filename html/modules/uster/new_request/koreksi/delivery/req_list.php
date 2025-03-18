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
                                                $query_list = "SELECT NVL(NOTA_DELIVERY.LUNAS, 0) LUNAS, REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd Mon yyyy') TGL_REQUEST, TO_DATE(TGL_REQUEST_DELIVERY,'dd Mon yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM as NAMA_EMKL ,request_delivery.VESSEL as NAMA_VESSEL, request_delivery.VOYAGE, yard_area.NAMA_YARD
                        FROM REQUEST_DELIVERY, NOTA_DELIVERY, V_MST_PBM emkl, yard_area
                        WHERE  REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
                        AND REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID 
                        AND NOTA_DELIVERY.NO_REQUEST(+) = REQUEST_DELIVERY.NO_REQUEST
                        AND REQUEST_DELIVERY.NO_REQUEST LIKE '%$no_req%'
                        AND request_delivery.PERALIHAN NOT IN ('RELOKASI','STUFFING','STRIPPING')    
						AND request_delivery.DELIVERY_KE = 'LUAR'
						AND request_delivery.KOREKSI = 'Y'
                        ORDER BY REQUEST_DELIVERY.NO_REQUEST DESC";
						
						

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
						AND request_delivery.KOREKSI = 'Y'
                        ORDER BY REQUEST_DELIVERY.NO_REQUEST DESC";

                                        } else if((isset($_POST["no_req"]))&& (isset($_POST["from"])) && (isset($_POST["to"])))
                                        {
                                                $query_list = "SELECT NVL(NOTA_DELIVERY.LUNAS, 0) LUNAS, REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd Mon yyyy') TGL_REQUEST, TO_DATE(TGL_REQUEST_DELIVERY,'dd Mon yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM as NAMA_EMKL ,request_delivery.VESSEL as NAMA_VESSEL, request_delivery.VOYAGE, yard_area.NAMA_YARD
                        FROM REQUEST_DELIVERY, NOTA_DELIVERY, V_MST_PBM emkl, yard_area
                         WHERE  REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
                        AND REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
                        AND NOTA_DELIVERY.NO_REQUEST(+) = REQUEST_DELIVERY.NO_REQUEST
                        AND request_delivery.NO_REQUEST = '$no_req'
						AND request_delivery.KOREKSI = 'Y'
                        AND request_delivery.TGL_REQUEST_DELIVERY BETWEEN TO_DATE('$from','yyyy/mm/dd') AND TO_DATE('$to','yyyy/mm/dd')
                       AND request_delivery.PERALIHAN NOT IN ('RELOKASI','STUFFING','STRIPPING') 
						AND request_delivery.DELIVERY_KE = 'LUAR'
                        ORDER BY REQUEST_DELIVERY.NO_REQUEST DESC";
                        } 
                } else {
                                        $query_list     = "SELECT NVL(NOTA_DELIVERY.LUNAS, 0) LUNAS, REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd Mon yyyy') TGL_REQUEST, TO_CHAR(TGL_REQUEST_DELIVERY,'dd Mon yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM as NAMA_EMKL ,request_delivery.VESSEL as NAMA_VESSEL, request_delivery.VOYAGE, yard_area.NAMA_YARD
                        FROM REQUEST_DELIVERY, NOTA_DELIVERY, V_MST_PBM emkl, yard_area
                        WHERE  REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
                        AND REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
                        AND NOTA_DELIVERY.NO_REQUEST(+) = REQUEST_DELIVERY.NO_REQUEST
                       AND request_delivery.PERALIHAN NOT IN ('RELOKASI','STUFFING','STRIPPING') 
						AND request_delivery.DELIVERY_KE = 'LUAR'
						AND request_delivery.KOREKSI = 'Y'
						and rownum <= 10
                        ORDER BY REQUEST_DELIVERY.NO_REQUEST DESC";
                } 

	
	$result_list	= $db->query($query_list);
	$row_list       = $result_list->getAll(); 
		
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
        
        ?>