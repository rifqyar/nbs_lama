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
        
        $cari	= $_POST["CARI"];
		$no_req	= $_POST["NO_REQ"]; 
		$from   = $_POST["FROM"];
		$to     = $_POST["TO"];
        $id_yard  = $_SESSION["IDYARD_STORAGE"];
	
        
	$db = getDB("storage");

	 //if (($_SESSION["ID_ROLE"] == '1') OR ($_SESSION["ID_ROLE"] == '2')){
            	if(isset($_POST["CARI"]) ) 
            	{   
                                              	if((isset($_POST["NO_REQ"])) && ($from == NULL) && ($to == NULL))
		{
			$query_list		= "SELECT NVL(nota_delivery.LUNAS, 0) LUNAS, NVL(nota_delivery.NO_NOTA, '-') NO_NOTA, request_delivery.NO_REQUEST, TO_CHAR( request_delivery.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_DATE(request_delivery.TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM AS NAMA_EMKL,  yard_area.NAMA_YARD, COUNT(container_delivery.NO_CONTAINER) JML_CONT, request_delivery.PERP_DARI
                        FROM request_delivery, nota_delivery, v_mst_pbm emkl, yard_area, container_delivery
                        WHERE  request_delivery.KD_EMKL = emkl.KD_PBM
                        AND request_delivery.NO_REQUEST = container_delivery.NO_REQUEST
                        AND request_delivery.ID_YARD = YARD_AREA.ID
                        AND nota_delivery.NO_REQUEST(+) = request_delivery.NO_REQUEST
                        and request_delivery.STATUS = 'PERP'
						AND request_delivery.NO_REQUEST = '$no_req'
                     GROUP BY  NVL(nota_delivery.LUNAS, 0), NVL(nota_delivery.NO_NOTA, '-'),request_delivery.NO_REQUEST, TO_CHAR( request_delivery.TGL_REQUEST,'dd/mm/yyyy'), TO_DATE(request_delivery.TGL_REQUEST_DELIVERY,'dd/mm/yyyy'), emkl.NM_PBM,yard_area.NAMA_YARD,request_delivery.PERP_DARI
                     ORDER BY request_delivery.NO_REQUEST DESC";
		}
		else if((isset($_POST["FROM"]))&& (isset($_POST["TO"])) && ($no_req == NULL))
		{
			$query_list		= " SELECT NVL(nota_delivery.LUNAS, 0) LUNAS, NVL(nota_delivery.NO_NOTA, '-') NO_NOTA, request_delivery.NO_REQUEST, TO_CHAR( request_delivery.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_DATE(request_delivery.TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM AS NAMA_EMKL,  yard_area.NAMA_YARD, COUNT(container_delivery.NO_CONTAINER) JML_CONT, request_delivery.PERP_DARI
                        FROM request_delivery, nota_delivery, v_mst_pbm emkl, yard_area, container_delivery
                        WHERE  request_delivery.KD_EMKL = emkl.KD_PBM
                        AND request_delivery.NO_REQUEST = container_delivery.NO_REQUEST
                        AND request_delivery.ID_YARD = YARD_AREA.ID
                        AND nota_delivery.NO_REQUEST(+) = request_delivery.NO_REQUEST
                        and request_delivery.STATUS = 'PERP'
						AND request_delivery.TGL_REQUEST BETWEEN TO_DATE ( '$from', 'YYYY-MM-DD ')
                        AND TO_DATE (  CONCAT('$to', '23:59:59'), 'YYYY-MM-DD HH24:MI:SS')
                     GROUP BY  NVL(nota_delivery.LUNAS, 0), NVL(nota_delivery.NO_NOTA, '-'),request_delivery.NO_REQUEST, TO_CHAR( request_delivery.TGL_REQUEST,'dd/mm/yyyy'), TO_DATE(request_delivery.TGL_REQUEST_DELIVERY,'dd/mm/yyyy'), emkl.NM_PBM,yard_area.NAMA_YARD,request_delivery.PERP_DARI
                     ORDER BY request_delivery.NO_REQUEST DESC";
					 
		} else if((isset($_POST["FROM"]))&& (isset($_POST["TO"])) && (isset($_POST["NO_REQ"])))
		{
			$query_list		= " SELECT NVL(nota_delivery.LUNAS, 0) LUNAS, NVL(nota_delivery.NO_NOTA, '-') NO_NOTA, request_delivery.NO_REQUEST, TO_CHAR( request_delivery.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_DATE(request_delivery.TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM AS NAMA_EMKL,  yard_area.NAMA_YARD, COUNT(container_delivery.NO_CONTAINER) JML_CONT, request_delivery.PERP_DARI
                        FROM request_delivery, nota_delivery, v_mst_pbm emkl, yard_area, container_delivery
                        WHERE  request_delivery.KD_EMKL = emkl.KD_PBM
                        AND request_delivery.NO_REQUEST = container_delivery.NO_REQUEST
                        AND request_delivery.ID_YARD = YARD_AREA.ID
                        AND nota_delivery.NO_REQUEST(+) = request_delivery.NO_REQUEST
                        and request_delivery.STATUS = 'PERP'
						AND request_delivery.NO_REQUEST = '$no_req'
						AND request_delivery.TGL_REQUEST BETWEEN TO_DATE ( '$from', 'YYYY-MM-DD ')
                        AND TO_DATE (  CONCAT('$to', '23:59:59'), 'YYYY-MM-DD HH24:MI:SS')
                     GROUP BY  NVL(nota_delivery.LUNAS, 0), NVL(nota_delivery.NO_NOTA, '-'),request_delivery.NO_REQUEST, TO_CHAR( request_delivery.TGL_REQUEST,'dd/mm/yyyy'), TO_DATE(request_delivery.TGL_REQUEST_DELIVERY,'dd/mm/yyyy'), emkl.NM_PBM,yard_area.NAMA_YARD,request_delivery.PERP_DARI
                     ORDER BY request_delivery.NO_REQUEST DESC";
		}
		
                                        } else {
                                        $query_list     = "   SELECT NVL(nota_delivery.LUNAS, 0) LUNAS, NVL(nota_delivery.NO_NOTA, '-') NO_NOTA, request_delivery.NO_REQUEST, TO_CHAR( request_delivery.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_DATE(request_delivery.TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM AS NAMA_EMKL,  yard_area.NAMA_YARD, COUNT(container_delivery.NO_CONTAINER) JML_CONT, request_delivery.PERP_DARI
                        FROM request_delivery, nota_delivery, v_mst_pbm emkl, yard_area, container_delivery
                        WHERE  request_delivery.KD_EMKL = emkl.KD_PBM
                        AND request_delivery.NO_REQUEST = container_delivery.NO_REQUEST
                        AND request_delivery.ID_YARD = YARD_AREA.ID
                        AND nota_delivery.NO_REQUEST(+) = request_delivery.NO_REQUEST
                        and request_delivery.STATUS = 'PERP'
                     GROUP BY  NVL(nota_delivery.LUNAS, 0), NVL(nota_delivery.NO_NOTA, '-'),request_delivery.NO_REQUEST, TO_CHAR( request_delivery.TGL_REQUEST,'dd/mm/yyyy'), TO_DATE(request_delivery.TGL_REQUEST_DELIVERY,'dd/mm/yyyy'), emkl.NM_PBM,yard_area.NAMA_YARD,request_delivery.PERP_DARI
                     ORDER BY request_delivery.NO_REQUEST DESC";
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
        
        
      
	
	$result_list	= $db->query($query_list);
	$row_list	= $result_list->getAll(); 
		
	
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
	
	function cek_nota($no_req)
	{
		$db 		= getDB("storage");
		$query_cek	= "SELECT * FROM NOTA_DELIVERY WHERE NO_REQUEST = '$no_req'";
                //echo $query_cek;
		$result_cek	= $db->query($query_cek);
		$row_cek 	= $result_cek->getAll();
		
		if(count($row_cek) > 0)
		{
			$cetak		= $row_cek[0]["CETAK_NOTA"];
			$no_nota	= $row_cek[0]["NO_NOTA"];
			
			if (($row_cek[0]["CETAK_NOTA"] > 0) && ($row_cek[0]["LUNAS"] == 'NO'))
			{
				echo '<a href="'.HOME.APPID.'.print/print_nota_lunas?no_nota='.$no_nota.'&no_req='.$no_req.'" target="_blank""><b><i> CETAK ULANG</i></b> </a><br> ';	
                            //    echo '<a href="'.HOME.APPID.'/set_lunas?no_nota='.$no_nota.'"><style:"font-color=red"> Set LUNAS</style> </a> ';	
			}
			else if (($row_cek[0]["CETAK_NOTA"] > 0) && ($row_cek[0]["LUNAS"] == 'YES'))
			{
				echo '<a href="'.HOME.APPID.'.print/print_nota_lunas?no_nota='.$no_nota.'&no_req='.$no_req.'" target="_blank"><b><i> CETAK ULANG </i></b></a> <br>';
                             //    echo '<font color="red"><i>SDH LUNAS</i></font>';
			}
                        else if (($row_cek[0]["CETAK_NOTA"] > 0) && ($row_cek[0]["LUNAS"] == 'PIUTANG'))
			{
				echo '<a href="'.HOME.APPID.'.print/print_nota_lunas?no_nota='.$no_nota.'&no_req='.$no_req.'" target="_blank"><b><i> CETAK ULANG </i></b> </a> <br>';
                             //    echo '<font color="red"><i>PIUTANG</i></font>';
			}
		}
		else
		{
			echo '<a href="'.HOME.APPID.'/print_nota?no_req='.$no_req.'&n=999" target="_blank"><b><i> Preview Nota </i></b></a> ';
		}
	}
?>
