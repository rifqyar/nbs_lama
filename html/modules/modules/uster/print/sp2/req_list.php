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
	$id_yard = $_SESSION["IDYARD_STORAGE"];
        
        $cari	= $_POST["cari"];
		$no_req	= $_POST["no_req"]; 
		$from   = $_POST["from"];
		$to     = $_POST["to"];
        $id_yard  = $_SESSION["IDYARD_STORAGE"];
	
        
	$db = getDB("storage");

	 //if (($_SESSION["ID_ROLE"] == '1') OR ($_SESSION["ID_ROLE"] == '2')){
            	if(isset($_POST["cari"]) ) 
            	{   
                                                 if((isset($_POST["no_req"])) && ($from == NULL) && ($to == NULL))
                                        {
                                                $query_list = "SELECT REQUEST_DELIVERY.DELIVERY_KE,
                                               REQUEST_DELIVERY.PERALIHAN,
                                               NVL (NOTA_DELIVERY.LUNAS, 0) LUNAS,
                                               NVL (NOTA_DELIVERY.NO_FAKTUR, '-') NO_NOTA,
                                               REQUEST_DELIVERY.NO_REQUEST,
                                               TO_CHAR (REQUEST_DELIVERY.TGL_REQUEST, 'dd/mm/yyyy')
                                                  TGL_REQUEST,
                                               TO_DATE (TGL_REQUEST_DELIVERY, 'dd/mm/yyyy')
                                                  TGL_REQUEST_DELIVERY,
                                               emkl.NM_PBM AS NAMA_EMKL,
                                               request_delivery.VOYAGE,
                                               request_delivery.VESSEL NAMA_VESSEL,
                                               COUNT (container_delivery.NO_CONTAINER) JML_CONT
                                          FROM REQUEST_DELIVERY
                                               LEFT JOIN NOTA_DELIVERY
                                                  ON NOTA_DELIVERY.NO_REQUEST = REQUEST_DELIVERY.NO_REQUEST
                                               INNER JOIN KAPAL_CABANG.MST_PBM emkl
                                                  ON REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
                                                     AND emkl.KD_CABANG = '05'
                                               INNER JOIN container_delivery
                                                  ON REQUEST_DELIVERY.NO_REQUEST =
                                                        container_delivery.NO_REQUEST
                                         WHERE REQUEST_DELIVERY.delivery_ke = 'LUAR' AND REQUEST_DELIVERY.NO_REQUEST = '$no_req'
                                      GROUP BY REQUEST_DELIVERY.DELIVERY_KE,
                                               REQUEST_DELIVERY.PERALIHAN,
                                               NVL (NOTA_DELIVERY.LUNAS, 0),
                                               NVL (NOTA_DELIVERY.NO_FAKTUR, '-'),
                                               REQUEST_DELIVERY.NO_REQUEST,
                                               TO_CHAR (REQUEST_DELIVERY.TGL_REQUEST, 'dd/mm/yyyy'),
                                               TO_DATE (TGL_REQUEST_DELIVERY, 'dd/mm/yyyy'),
                                               emkl.NM_PBM,
                                               request_delivery.VOYAGE,
                                               request_delivery.VESSEL";

                                        }
                                        else if(($no_req == NULL) && (isset($_POST["from"])) && (isset($_POST["to"])))
                                        {
                                                				 
					 $query_list = "SELECT DISTINCT * FROM (
						SELECT REQUEST_DELIVERY.DELIVERY_KE, REQUEST_DELIVERY.PERALIHAN, NVL(NOTA_DELIVERY.LUNAS, 0) LUNAS, NVL(NOTA_DELIVERY.NO_NOTA, '-') NO_NOTA, REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM AS NAMA_EMKL, request_delivery.VOYAGE, request_delivery.VESSEL NAMA_VESSEL, yard_area.NAMA_YARD, COUNT(container_delivery.NO_CONTAINER) JML_CONT
                        FROM REQUEST_DELIVERY left join NOTA_DELIVERY on NOTA_DELIVERY.NO_REQUEST = REQUEST_DELIVERY.NO_REQUEST
                        left join  v_mst_pbm emkl on REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM AND emkl.KD_CABANG = '05'
                        left join yard_area on REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
                        inner join container_delivery on REQUEST_DELIVERY.NO_REQUEST = container_delivery.NO_REQUEST
                        where
                        nota_delivery.TGL_NOTA = (SELECT MAX(e.TGL_NOTA) FROM NOTA_DELIVERY e WHERE e.NO_REQUEST = REQUEST_DELIVERY.NO_REQUEST)
                        and 
                        request_delivery.PERALIHAN NOT IN ('RELOKASI', 'STRIPPING', 'STUFFING')
                     GROUP BY REQUEST_DELIVERY.DELIVERY_KE, REQUEST_DELIVERY.PERALIHAN, NVL(NOTA_DELIVERY.LUNAS, 0), NVL(NOTA_DELIVERY.NO_NOTA, '-'),REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy'), TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy'), emkl.NM_PBM, request_delivery.VOYAGE, request_delivery.VESSEL, yard_area.NAMA_YARD
                     union SELECT REQUEST_DELIVERY.DELIVERY_KE, REQUEST_DELIVERY.PERALIHAN, NVL(NOTA_DELIVERY.LUNAS, 0) LUNAS, NVL(NOTA_DELIVERY.NO_NOTA, '-') NO_NOTA, REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM AS NAMA_EMKL, request_delivery.VOYAGE, request_delivery.VESSEL NAMA_VESSEL, yard_area.NAMA_YARD, COUNT(container_delivery.NO_CONTAINER) JML_CONT
                        FROM REQUEST_DELIVERY left join NOTA_DELIVERY on NOTA_DELIVERY.NO_REQUEST = REQUEST_DELIVERY.NO_REQUEST
                        left join  v_mst_pbm emkl on REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM AND emkl.KD_CABANG = '05'
                        left join yard_area on REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
                        inner join container_delivery on REQUEST_DELIVERY.NO_REQUEST = container_delivery.NO_REQUEST
                        where
                        request_delivery.PERALIHAN NOT IN ('RELOKASI', 'STRIPPING', 'STUFFING')
                     GROUP BY REQUEST_DELIVERY.DELIVERY_KE, REQUEST_DELIVERY.PERALIHAN, NVL(NOTA_DELIVERY.LUNAS, 0), NVL(NOTA_DELIVERY.NO_NOTA, '-'),REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy'), TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy'), emkl.NM_PBM, request_delivery.VOYAGE, request_delivery.VESSEL, yard_area.NAMA_YARD ) agr
					WHERE request_delivery.TGL_REQUEST_DELIVERY BETWEEN TO_DATE('$from','yyyy/mm/dd') AND TO_DATE('$to','yyyy/mm/dd')
                ORDER BY agr.NO_REQUEST DESC";

                                        }
                                         else if(($no_req <> NULL) && ($_POST["from"] <> NULL) && ($_POST["to"] <> NULL))
                                        {
                                                					 
					  $query_list = "SELECT DISTINCT * FROM (
						SELECT REQUEST_DELIVERY.DELIVERY_KE, REQUEST_DELIVERY.PERALIHAN, NVL(NOTA_DELIVERY.LUNAS, 0) LUNAS, NVL(NOTA_DELIVERY.NO_NOTA, '-') NO_NOTA, REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM AS NAMA_EMKL, request_delivery.VOYAGE, request_delivery.VESSEL NAMA_VESSEL, yard_area.NAMA_YARD, COUNT(container_delivery.NO_CONTAINER) JML_CONT
                        FROM REQUEST_DELIVERY left join NOTA_DELIVERY on NOTA_DELIVERY.NO_REQUEST = REQUEST_DELIVERY.NO_REQUEST
                        left join  v_mst_pbm emkl on REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM AND emkl.KD_CABANG = '05'
                        left join yard_area on REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
                        inner join container_delivery on REQUEST_DELIVERY.NO_REQUEST = container_delivery.NO_REQUEST
                        where
                        nota_delivery.TGL_NOTA = (SELECT MAX(e.TGL_NOTA) FROM NOTA_DELIVERY e WHERE e.NO_REQUEST = REQUEST_DELIVERY.NO_REQUEST)
                        and 
                        request_delivery.PERALIHAN NOT IN ('RELOKASI', 'STRIPPING', 'STUFFING')
                     GROUP BY REQUEST_DELIVERY.DELIVERY_KE, REQUEST_DELIVERY.PERALIHAN, NVL(NOTA_DELIVERY.LUNAS, 0), NVL(NOTA_DELIVERY.NO_NOTA, '-'),REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy'), TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy'), emkl.NM_PBM, request_delivery.VOYAGE, request_delivery.VESSEL, yard_area.NAMA_YARD
                     union SELECT REQUEST_DELIVERY.DELIVERY_KE, REQUEST_DELIVERY.PERALIHAN, NVL(NOTA_DELIVERY.LUNAS, 0) LUNAS, NVL(NOTA_DELIVERY.NO_NOTA, '-') NO_NOTA, REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM AS NAMA_EMKL, request_delivery.VOYAGE, request_delivery.VESSEL NAMA_VESSEL, yard_area.NAMA_YARD, COUNT(container_delivery.NO_CONTAINER) JML_CONT
                        FROM REQUEST_DELIVERY left join NOTA_DELIVERY on NOTA_DELIVERY.NO_REQUEST = REQUEST_DELIVERY.NO_REQUEST
                        left join  v_mst_pbm emkl on REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM AND emkl.KD_CABANG = '05'
                        left join yard_area on REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
                        inner join container_delivery on REQUEST_DELIVERY.NO_REQUEST = container_delivery.NO_REQUEST
                        where
                        request_delivery.PERALIHAN NOT IN ('RELOKASI', 'STRIPPING', 'STUFFING')
                     GROUP BY REQUEST_DELIVERY.DELIVERY_KE, REQUEST_DELIVERY.PERALIHAN, NVL(NOTA_DELIVERY.LUNAS, 0), NVL(NOTA_DELIVERY.NO_NOTA, '-'),REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy'), TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy'), emkl.NM_PBM, request_delivery.VOYAGE, request_delivery.VESSEL, yard_area.NAMA_YARD ) agr
					WHERE agr.NO_REQUEST = '$no_req' AND request_delivery.TGL_REQUEST_DELIVERY BETWEEN TO_DATE('$from','yyyy/mm/dd') AND TO_DATE('$to','yyyy/mm/dd')
                ORDER BY agr.NO_REQUEST DESC ";
                                                
						
                                }
								else{
								$query_list = "SELECT DISTINCT * FROM (
						SELECT REQUEST_DELIVERY.DELIVERY_KE, REQUEST_DELIVERY.PERALIHAN, NVL(NOTA_DELIVERY.LUNAS, 0) LUNAS, NVL(NOTA_DELIVERY.NO_NOTA, '-') NO_NOTA, REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM AS NAMA_EMKL, request_delivery.VOYAGE, request_delivery.VESSEL NAMA_VESSEL, yard_area.NAMA_YARD, COUNT(container_delivery.NO_CONTAINER) JML_CONT
                        FROM REQUEST_DELIVERY left join NOTA_DELIVERY on NOTA_DELIVERY.NO_REQUEST = REQUEST_DELIVERY.NO_REQUEST
                        left join  v_mst_pbm emkl on REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM AND emkl.KD_CABANG = '05'
                        left join yard_area on REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
                        inner join container_delivery on REQUEST_DELIVERY.NO_REQUEST = container_delivery.NO_REQUEST
                        where
                        nota_delivery.TGL_NOTA = (SELECT MAX(e.TGL_NOTA) FROM NOTA_DELIVERY e WHERE e.NO_REQUEST = REQUEST_DELIVERY.NO_REQUEST)
                        and 
                        request_delivery.PERALIHAN NOT IN ('RELOKASI', 'STRIPPING', 'STUFFING')
                     GROUP BY REQUEST_DELIVERY.DELIVERY_KE, REQUEST_DELIVERY.PERALIHAN, NVL(NOTA_DELIVERY.LUNAS, 0), NVL(NOTA_DELIVERY.NO_NOTA, '-'),REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy'), TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy'), emkl.NM_PBM, request_delivery.VOYAGE, request_delivery.VESSEL, yard_area.NAMA_YARD
                     union SELECT REQUEST_DELIVERY.DELIVERY_KE, REQUEST_DELIVERY.PERALIHAN, NVL(NOTA_DELIVERY.LUNAS, 0) LUNAS, NVL(NOTA_DELIVERY.NO_NOTA, '-') NO_NOTA, REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM AS NAMA_EMKL, request_delivery.VOYAGE, request_delivery.VESSEL NAMA_VESSEL, yard_area.NAMA_YARD, COUNT(container_delivery.NO_CONTAINER) JML_CONT
                        FROM REQUEST_DELIVERY left join NOTA_DELIVERY on NOTA_DELIVERY.NO_REQUEST = REQUEST_DELIVERY.NO_REQUEST
                        left join  v_mst_pbm emkl on REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM AND emkl.KD_CABANG = '05'
                        left join yard_area on REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
                        inner join container_delivery on REQUEST_DELIVERY.NO_REQUEST = container_delivery.NO_REQUEST
                        where
                        request_delivery.PERALIHAN NOT IN ('RELOKASI', 'STRIPPING', 'STUFFING')
                     GROUP BY REQUEST_DELIVERY.DELIVERY_KE, REQUEST_DELIVERY.PERALIHAN, NVL(NOTA_DELIVERY.LUNAS, 0), NVL(NOTA_DELIVERY.NO_NOTA, '-'),REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy'), TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy'), emkl.NM_PBM, request_delivery.VOYAGE, request_delivery.VESSEL, yard_area.NAMA_YARD ) agr
					ORDER BY agr.NO_REQUEST DESC";
								}
				} else {
											/*
                                        $query_list     = "   SELECT NVL(NOTA_DELIVERY.LUNAS, 0) LUNAS, NVL(NOTA_DELIVERY.NO_NOTA, '-') NO_NOTA, REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM AS NAMA_EMKL, request_delivery.VOYAGE, request_delivery.VESSEL NAMA_VESSEL, yard_area.NAMA_YARD, COUNT(container_delivery.NO_CONTAINER) JML_CONT
                        FROM REQUEST_DELIVERY, NOTA_DELIVERY, v_mst_pbm emkl, yard_area, container_delivery
                        WHERE  REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
                        AND REQUEST_DELIVERY.NO_REQUEST = container_delivery.NO_REQUEST
                        AND REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
                        AND NOTA_DELIVERY.NO_REQUEST(+) = REQUEST_DELIVERY.NO_REQUEST
                        and request_delivery.PERALIHAN <> 'RELOKASI'
                        and request_delivery.STATUS = 'NEW'
                       and request_delivery.NO_REQUEST NOT IN (SELECT request_delivery.NO_REQUEST FROM request_delivery where DELIVERY_KE = 'TPK' AND PERALIHAN = 'T')
                     GROUP BY  NVL(NOTA_DELIVERY.LUNAS, 0), NVL(NOTA_DELIVERY.NO_NOTA, '-'),REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy'), TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy'), emkl.NM_PBM, request_delivery.VOYAGE, request_delivery.VESSEL, yard_area.NAMA_YARD
                     ORDER BY request_delivery.NO_REQUEST DESC";
					 */
					 /* $query_list     = "SELECT REQUEST_DELIVERY.DELIVERY_KE, REQUEST_DELIVERY.PERALIHAN, NVL(NOTA_DELIVERY.LUNAS, 0) LUNAS, NVL(NOTA_DELIVERY.NO_NOTA, '-') NO_NOTA, REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM AS NAMA_EMKL, request_delivery.VOYAGE, request_delivery.VESSEL NAMA_VESSEL, yard_area.NAMA_YARD, COUNT(container_delivery.NO_CONTAINER) JML_CONT
                        FROM REQUEST_DELIVERY, NOTA_DELIVERY, v_mst_pbm emkl, yard_area, container_delivery
                        WHERE  REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM AND emkl.KD_CABANG = '05'
                        AND REQUEST_DELIVERY.NO_REQUEST = container_delivery.NO_REQUEST
                        AND REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
                        AND NOTA_DELIVERY.NO_REQUEST = REQUEST_DELIVERY.NO_REQUEST
						--and nota_delivery.TGL_NOTA = (SELECT MAX(e.TGL_NOTA) FROM NOTA_DELIVERY e WHERE e.NO_REQUEST = request_delivery.NO_REQUEST)
                        --and 
						request_delivery.PERALIHAN NOT IN ('RELOKASI', 'STRIPPING', 'STUFFING')
                     GROUP BY REQUEST_DELIVERY.DELIVERY_KE, REQUEST_DELIVERY.PERALIHAN, NVL(NOTA_DELIVERY.LUNAS, 0), NVL(NOTA_DELIVERY.NO_NOTA, '-'),REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy'), TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy'), emkl.NM_PBM, request_delivery.VOYAGE, request_delivery.VESSEL, yard_area.NAMA_YARD
                     ORDER BY request_delivery.NO_REQUEST DESC"; */
					
                    //Last update 8/4/2014
                     /*$query_list = "SELECT DISTINCT * FROM (
						SELECT REQUEST_DELIVERY.DELIVERY_KE, REQUEST_DELIVERY.PERALIHAN, NVL(NOTA_DELIVERY.LUNAS, 0) LUNAS, NVL(NOTA_DELIVERY.NO_NOTA, '-') NO_NOTA, REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM AS NAMA_EMKL, request_delivery.VOYAGE, request_delivery.VESSEL NAMA_VESSEL, yard_area.NAMA_YARD, COUNT(container_delivery.NO_CONTAINER) JML_CONT
                        FROM REQUEST_DELIVERY left join NOTA_DELIVERY on NOTA_DELIVERY.NO_REQUEST = REQUEST_DELIVERY.NO_REQUEST
                        left join  v_mst_pbm emkl on REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM AND emkl.KD_CABANG = '05'
                        left join yard_area on REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
                        inner join container_delivery on REQUEST_DELIVERY.NO_REQUEST = container_delivery.NO_REQUEST
                        where
                        nota_delivery.TGL_NOTA = (SELECT MAX(e.TGL_NOTA) FROM NOTA_DELIVERY e WHERE e.NO_REQUEST = REQUEST_DELIVERY.NO_REQUEST)
                        and 
                        request_delivery.PERALIHAN NOT IN ('RELOKASI', 'STRIPPING', 'STUFFING') and REQUEST_DELIVERY.delivery_ke = 'LUAR'
                     GROUP BY REQUEST_DELIVERY.DELIVERY_KE, REQUEST_DELIVERY.PERALIHAN, NVL(NOTA_DELIVERY.LUNAS, 0), NVL(NOTA_DELIVERY.NO_NOTA, '-'),REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy'), TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy'), emkl.NM_PBM, request_delivery.VOYAGE, request_delivery.VESSEL, yard_area.NAMA_YARD
                     union SELECT REQUEST_DELIVERY.DELIVERY_KE, REQUEST_DELIVERY.PERALIHAN, NVL(NOTA_DELIVERY.LUNAS, 0) LUNAS, NVL(NOTA_DELIVERY.NO_NOTA, '-') NO_NOTA, REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM AS NAMA_EMKL, request_delivery.VOYAGE, request_delivery.VESSEL NAMA_VESSEL, yard_area.NAMA_YARD, COUNT(container_delivery.NO_CONTAINER) JML_CONT
                        FROM REQUEST_DELIVERY left join NOTA_DELIVERY on NOTA_DELIVERY.NO_REQUEST = REQUEST_DELIVERY.NO_REQUEST
                        left join  v_mst_pbm emkl on REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM AND emkl.KD_CABANG = '05'
                        left join yard_area on REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
                        inner join container_delivery on REQUEST_DELIVERY.NO_REQUEST = container_delivery.NO_REQUEST
                        where
                        request_delivery.PERALIHAN NOT IN ('RELOKASI', 'STRIPPING', 'STUFFING') and REQUEST_DELIVERY.delivery_ke = 'LUAR'
                     GROUP BY REQUEST_DELIVERY.DELIVERY_KE, REQUEST_DELIVERY.PERALIHAN, NVL(NOTA_DELIVERY.LUNAS, 0), NVL(NOTA_DELIVERY.NO_NOTA, '-'),REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy'), TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy'), emkl.NM_PBM, request_delivery.VOYAGE, request_delivery.VESSEL, yard_area.NAMA_YARD ) agr
					ORDER BY agr.NO_REQUEST DESC";*/
                    $query_list = " SELECT * FROM (  SELECT REQUEST_DELIVERY.DELIVERY_KE,
                                               REQUEST_DELIVERY.PERALIHAN,
                                               NVL (NOTA_DELIVERY.LUNAS, 0) LUNAS,
                                               NVL (NOTA_DELIVERY.NO_FAKTUR, '-') NO_NOTA,
                                               REQUEST_DELIVERY.NO_REQUEST,
                                               TO_CHAR (REQUEST_DELIVERY.TGL_REQUEST, 'dd/mm/yyyy')
                                                  TGL_REQUEST,
                                               TO_DATE (TGL_REQUEST_DELIVERY, 'dd/mm/yyyy')
                                                  TGL_REQUEST_DELIVERY,
                                               emkl.NM_PBM AS NAMA_EMKL,
                                               request_delivery.VOYAGE,
                                               request_delivery.VESSEL NAMA_VESSEL,
                                               COUNT (container_delivery.NO_CONTAINER) JML_CONT
                                          FROM REQUEST_DELIVERY
                                               LEFT JOIN NOTA_DELIVERY
                                                  ON NOTA_DELIVERY.NO_REQUEST = REQUEST_DELIVERY.NO_REQUEST
                                               INNER JOIN KAPAL_CABANG.MST_PBM emkl
                                                  ON REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
                                                     AND emkl.KD_CABANG = '05'
                                               INNER JOIN container_delivery
                                                  ON REQUEST_DELIVERY.NO_REQUEST =
                                                        container_delivery.NO_REQUEST
                                         WHERE REQUEST_DELIVERY.delivery_ke = 'LUAR'
                                      GROUP BY REQUEST_DELIVERY.DELIVERY_KE,
                                               REQUEST_DELIVERY.PERALIHAN,
                                               NVL (NOTA_DELIVERY.LUNAS, 0),
                                               NVL (NOTA_DELIVERY.NO_FAKTUR, '-'),
                                               REQUEST_DELIVERY.NO_REQUEST,
                                               TO_CHAR (REQUEST_DELIVERY.TGL_REQUEST, 'dd/mm/yyyy'),
                                               TO_DATE (TGL_REQUEST_DELIVERY, 'dd/mm/yyyy'),
                                               emkl.NM_PBM,
                                               request_delivery.VOYAGE,
                                               request_delivery.VESSEL
                                      ORDER BY TGL_REQUEST DESC
                            ) WHERE ROWNUM <= 100 ";
                } 
                             
       //    } else {
      /*              if(isset($_POST["cari"]) ) 
            	{   
                                                if((isset($_POST["no_req"])) && ($from == NULL) && ($to == NULL) && ($no_nota == NULL))
                                        {
                                                $query_list = "SELECT request_delivery.*, NVL(nota_delivery.NO_NOTA, '') NO_NOTA, emkl.NAMA AS NAMA_EMKL, vessel.NAMA_VESSEL, voyage.VOYAGE 
                                FROM request_delivery, master_pbm emkl, nota_delivery, vessel, voyage
                                where request_delivery.ID_EMKL = emkl.ID
                                and request_delivery.ID_VOYAGE = voyage.NO_BOOKING
                                and voyage.KODE_VESSEL = vessel.KODE_VESSEL
                                and nota_delivery.NO_REQUEST(+) = request_delivery.NO_REQUEST
                                and request_delivery.PERALIHAN <> 'RELOKASI'
                                and request_delivery.STATUS = 'NEW'
                                and request_delivery.ID_YARD = '$id_yard'
                                and request_delivery.NO_REQUEST = '$no_req'
                                and request_delivery.NO_REQUEST NOT IN (SELECT request_delivery.NO_REQUEST FROM request_delivery where DELIVERY_KE = 'TPK' AND PERALIHAN = 'T')
                                ORDER BY request_delivery.NO_REQUEST DESC";

                                        }
                                        else if(($no_req == NULL) && (isset($_POST["from"])) && (isset($_POST["to"])) && ($no_nota == NULL))
                                        {
                                                $query_list = "SELECT request_delivery.*, NVL(nota_delivery.NO_NOTA, '') NO_NOTA, emkl.NAMA AS NAMA_EMKL, vessel.NAMA_VESSEL, voyage.VOYAGE 
                                FROM request_delivery, master_pbm emkl, nota_delivery, vessel, voyage
                                where request_delivery.ID_EMKL = emkl.ID
                                and request_delivery.ID_VOYAGE = voyage.NO_BOOKING
                                and voyage.KODE_VESSEL = vessel.KODE_VESSEL
                                and nota_delivery.NO_REQUEST(+) = request_delivery.NO_REQUEST
                                and request_delivery.PERALIHAN <> 'RELOKASI'
                                and request_delivery.STATUS = 'NEW'
                                and request_delivery.ID_YARD = '$id_yard'
                                and request_delivery.NO_REQUEST = '$no_req'
                                AND request_delivery.TGL_REQUEST_DELIVERY BETWEEN TO_DATE('$from','yyyy/mm/dd') AND TO_DATE('$to','yyyy/mm/dd')
                                and request_delivery.NO_REQUEST NOT IN (SELECT request_delivery.NO_REQUEST FROM request_delivery where DELIVERY_KE = 'TPK' AND PERALIHAN = 'T')
                                ORDER BY request_delivery.NO_REQUEST DESC";

                                        }
                                          else if(($no_req == NULL) && ($_POST["from"] == NULL) && ($_POST["to"] == NULL) && (isset($_POST["no_nota"])))
                                        {
                                                $query_list = "SELECT request_delivery.*, NVL(nota_delivery.NO_NOTA, '') NO_NOTA, emkl.NAMA AS NAMA_EMKL, vessel.NAMA_VESSEL, voyage.VOYAGE 
                                FROM request_delivery, master_pbm emkl, nota_delivery, vessel, voyage
                                where request_delivery.ID_EMKL = emkl.ID
                                and request_delivery.ID_VOYAGE = voyage.NO_BOOKING
                                and voyage.KODE_VESSEL = vessel.KODE_VESSEL
                                and nota_delivery.NO_REQUEST(+) = request_delivery.NO_REQUEST
                                and request_delivery.PERALIHAN <> 'RELOKASI'
                                and request_delivery.STATUS = 'NEW'
                                and request_delivery.ID_YARD = '$id_yard'
                                and nota_delivery.NO_NOTA = '$no_nota'
                                and request_delivery.NO_REQUEST NOT IN (SELECT request_delivery.NO_REQUEST FROM request_delivery where DELIVERY_KE = 'TPK' AND PERALIHAN = 'T')
                                ORDER BY request_delivery.NO_REQUEST DESC";
                                                
                                }
                                    else if(($_POST["no_req"]== NULL)&& (isset($_POST["from"])) && (isset($_POST["to"]))  && ($_POST["no_nota"] == NULL))
                                        {
                                                $query_list = "SELECT NVL(NOTA_DELIVERY.LUNAS, 0) LUNAS, REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.NAMA AS NAMA_EMKL, VOYAGE.VOYAGE, VESSEL.NAMA_VESSEL, yard_area.NAMA_YARD
                        FROM REQUEST_DELIVERY, NOTA_DELIVERY, MASTER_PBM emkl, VESSEL, VOYAGE, yard_area
                        WHERE  REQUEST_DELIVERY.ID_EMKL = emkl.ID
                        AND REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
                        AND REQUEST_DELIVERY.ID_VOYAGE = VOYAGE.NO_BOOKING 
                        AND VOYAGE.KODE_VESSEL = VESSEL.KODE_VESSEL
                        AND NOTA_DELIVERY.NO_REQUEST(+) = REQUEST_DELIVERY.NO_REQUEST
                         AND request_delivery.TGL_REQUEST_DELIVERY BETWEEN TO_DATE('$from','yyyy/mm/dd') AND TO_DATE('$to','yyyy/mm/dd')
                        AND request_delivery.PERALIHAN <> 'RELOKASI' 
                        ORDER BY REQUEST_DELIVERY.NO_REQUEST DESC";
                                        }    else if(($no_req == NULL) && (isset($_POST["from"])) && (isset($_POST["to"])) && (isset($_POST["no_nota"])))
                                        {
                                                $query_list = "SELECT request_delivery.*, NVL(nota_delivery.NO_NOTA, '') NO_NOTA, emkl.NAMA AS NAMA_EMKL, vessel.NAMA_VESSEL, voyage.VOYAGE 
                                FROM request_delivery, master_pbm emkl, nota_delivery, vessel, voyage
                                where request_delivery.ID_EMKL = emkl.ID
                                and request_delivery.ID_VOYAGE = voyage.NO_BOOKING
                                and voyage.KODE_VESSEL = vessel.KODE_VESSEL
                                and nota_delivery.NO_REQUEST(+) = request_delivery.NO_REQUEST
                                and request_delivery.PERALIHAN <> 'RELOKASI'
                                and request_delivery.STATUS = 'NEW'
                                and request_delivery.ID_YARD = '$id_yard'
                                and nota_delivery.NO_NOTA = '$no_nota'
                                AND request_delivery.TGL_REQUEST_DELIVERY BETWEEN TO_DATE('$from','yyyy/mm/dd') AND TO_DATE('$to','yyyy/mm/dd')
                                and request_delivery.NO_REQUEST NOT IN (SELECT request_delivery.NO_REQUEST FROM request_delivery where DELIVERY_KE = 'TPK' AND PERALIHAN = 'T')
                                ORDER BY request_delivery.NO_REQUEST DESC";
                                                
                                } else if((isset($_POST["no_req"]))&& ($_POST["from"] == NULL) && ( $_POST["to"] == NULL)  && (isset($_POST["no_nota"])))
                                        {
                                                $query_list = "SELECT NVL(NOTA_DELIVERY.LUNAS, 0) LUNAS, REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.NAMA AS NAMA_EMKL, VOYAGE.VOYAGE, VESSEL.NAMA_VESSEL, yard_area.NAMA_YARD
                        FROM REQUEST_DELIVERY, NOTA_DELIVERY, MASTER_PBM emkl, VESSEL, VOYAGE, yard_area
                        WHERE  REQUEST_DELIVERY.ID_EMKL = emkl.ID
                        AND REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
                        AND REQUEST_DELIVERY.ID_VOYAGE = VOYAGE.NO_BOOKING 
                        AND VOYAGE.KODE_VESSEL = VESSEL.KODE_VESSEL
                        AND NOTA_DELIVERY.NO_REQUEST(+) = REQUEST_DELIVERY.NO_REQUEST
                        AND request_delivery.NO_REQUEST = '$no_req'
                        AND nota_delivery.NO_NOTA = '$no_nota'
                        AND request_delivery.PERALIHAN <> 'RELOKASI'
                        AND request_delivery.ID_YARD = '$id_yard'  
                        ORDER BY REQUEST_DELIVERY.NO_REQUEST DESC";
                                        } else if((isset($_POST["no_req"]))&& (isset($_POST["from"])) && (isset($_POST["to"]))  && (isset($_POST["no_nota"])))
                                        {
                                                $query_list = "SELECT NVL(NOTA_DELIVERY.LUNAS, 0) LUNAS, REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.NAMA AS NAMA_EMKL, VOYAGE.VOYAGE, VESSEL.NAMA_VESSEL, yard_area.NAMA_YARD
                        FROM REQUEST_DELIVERY, NOTA_DELIVERY, MASTER_PBM emkl, VESSEL, VOYAGE, yard_area
                        WHERE  REQUEST_DELIVERY.ID_EMKL = emkl.ID
                        AND REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
                        AND REQUEST_DELIVERY.ID_VOYAGE = VOYAGE.NO_BOOKING 
                        AND VOYAGE.KODE_VESSEL = VESSEL.KODE_VESSEL
                        AND NOTA_DELIVERY.NO_REQUEST(+) = REQUEST_DELIVERY.NO_REQUEST
                        AND request_delivery.NO_REQUEST = '$no_req'
                        AND nota_delivery.NO_NOTA = '$no_nota'
                        AND request_delivery.TGL_REQUEST_DELIVERY BETWEEN TO_DATE('$from','yyyy/mm/dd') AND TO_DATE('$to','yyyy/mm/dd')
                        AND request_delivery.PERALIHAN <> 'RELOKASI'
                        AND request_delivery.ID_YARD = '$id_yard'  
                        ORDER BY REQUEST_DELIVERY.NO_REQUEST DESC";
                                        } 
        } else {
                $query_list     = "SELECT request_delivery.*, NVL(nota_delivery.NO_NOTA, '') NO_NOTA, emkl.NAMA AS NAMA_EMKL, vessel.NAMA_VESSEL, voyage.VOYAGE 
                                FROM request_delivery, master_pbm emkl, nota_delivery, vessel, voyage
                                where request_delivery.ID_EMKL = emkl.ID
                                and request_delivery.ID_VOYAGE = voyage.NO_BOOKING
                                and voyage.KODE_VESSEL = vessel.KODE_VESSEL
                                and nota_delivery.NO_REQUEST(+) = request_delivery.NO_REQUEST
                                and request_delivery.PERALIHAN <> 'RELOKASI'
                                and request_delivery.STATUS = 'NEW'
                                and request_delivery.ID_YARD = '$id_yard'
                                and request_delivery.NO_REQUEST NOT IN (SELECT request_delivery.NO_REQUEST FROM request_delivery where DELIVERY_KE = 'TPK' AND PERALIHAN = 'T')
                                ORDER BY request_delivery.NO_REQUEST DESC";
        }} 
        
        */
        
        
      
	
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

?>
