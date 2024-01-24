<?php
	
	$tl =  xliteTemplate('nota_list.htm');
	$id_yard = $_SESSION["IDYARD_STORAGE"];
        
        $cari	= $_POST["cari"];
		$no_req	= $_POST["no_req"]; 
		$from   = $_POST["from"];
		$to     = $_POST["to"];
        $no_nota  = $_POST["no_nota"];
        $id_yard  = $_SESSION["IDYARD_STORAGE"];
	
        
	$db = getDB("storage");
	if($no_req != NULL && $from == NULL && $to == NULL){
		$query_list     = "SELECT   NVL (nota_stuffing.lunas, 0) lunas,
								 NVL (nota_stuffing.no_nota, '-') no_nota,
								 request_stuffing.no_request,
								 TO_CHAR (request_stuffing.tgl_request, 'dd/mm/yyyy') tgl_request,
								 emkl.nm_pbm AS nama_emkl, request_stuffing.voyage,
								 request_stuffing.nm_kapal nama_vessel, yard_area.nama_yard,
								 COUNT (container_stuffing.no_container) jml_cont
							FROM request_stuffing,
								 nota_stuffing,
								 v_mst_pbm emkl,
								 yard_area,
								 container_stuffing
						   WHERE request_stuffing.kd_consignee = emkl.kd_pbm
							 AND request_stuffing.no_request = container_stuffing.no_request
							 AND request_stuffing.id_yard = yard_area.ID
							 AND nota_stuffing.no_request(+) = request_stuffing.no_request
							 AND request_stuffing.no_request = '$no_req'
						GROUP BY NVL (nota_stuffing.lunas, 0),
								 NVL (nota_stuffing.no_nota, '-'),
								 request_stuffing.no_request,
								 TO_CHAR (request_stuffing.tgl_request, 'dd/mm/yyyy'),
								 emkl.nm_pbm,
								 request_stuffing.voyage,
								 request_stuffing.nm_kapal,
								 yard_area.nama_yard
						ORDER BY request_stuffing.no_request DESC";
	}
	else if($no_req == NULL && $from != NULL && $to != NULL){
		$query_list     = "SELECT   NVL (nota_stuffing.lunas, 0) lunas,
								 NVL (nota_stuffing.no_nota, '-') no_nota,
								 request_stuffing.no_request,
								 TO_DATE (request_stuffing.tgl_request, 'dd/mm/yyyy') tgl_request,
								 emkl.nm_pbm AS nama_emkl, request_stuffing.voyage,
								 request_stuffing.nm_kapal nama_vessel, yard_area.nama_yard,
								 COUNT (container_stuffing.no_container) jml_cont
							FROM request_stuffing,
								 nota_stuffing,
								 v_mst_pbm emkl,
								 yard_area,
								 container_stuffing
						   WHERE request_stuffing.kd_consignee = emkl.kd_pbm
							 AND request_stuffing.no_request = container_stuffing.no_request
							 AND request_stuffing.id_yard = yard_area.ID
							 AND nota_stuffing.no_request(+) = request_stuffing.no_request
							 AND tgl_request between TO_DATE ('$from', 'dd/mm/yyyy') AND TO_DATE ('$to', 'dd/mm/yyyy')							
						GROUP BY NVL (nota_stuffing.lunas, 0),
								 NVL (nota_stuffing.no_nota, '-'),
								 request_stuffing.no_request,
								 TO_DATE (request_stuffing.tgl_request, 'dd/mm/yyyy'),
								 emkl.nm_pbm,
								 request_stuffing.voyage,
								 request_stuffing.nm_kapal,
								 yard_area.nama_yard
						ORDER BY request_stuffing.no_request DESC";
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
		$query_cek	= "SELECT * FROM NOTA_STUFFING WHERE NO_REQUEST = '$no_req'";
                //echo $query_cek;
		$result_cek	= $db->query($query_cek);
		$row_cek 	= $result_cek->getAll();
		
		if(count($row_cek) > 0)
		{
			$cetak		= $row_cek[0]["CETAK_NOTA"];
			$no_nota	= $row_cek[0]["NO_NOTA"];
			
			if (($row_cek[0]["CETAK_NOTA"] > 0) && ($row_cek[0]["LUNAS"] == 'NO'))
			{
				echo '<a href="'.HOME.APPID.'.print/print_nota_lunas?no_nota='.$no_nota.'&no_req='.$no_req.'" target="_blank""><b><i>  CETAK ULANG</i></b>  </a><br> ';	
                               // echo '<a href="'.HOME.APPID.'/set_lunas?no_nota='.$no_nota.'"><style:"font-color=red"> Set LUNAS</style> </a> ';	
			}
			else if (($row_cek[0]["CETAK_NOTA"] > 0) && ($row_cek[0]["LUNAS"] == 'YES'))
			{
				echo '<a href="'.HOME.APPID.'.print/print_nota_lunas?no_nota='.$no_nota.'&no_req='.$no_req.'" target="_blank"><b><i>  CETAK ULANG </i></b> </a> <br>';
                               //  echo '<font color="red"><i>SDH LUNAS</i></font>';
			}
                        else if (($row_cek[0]["CETAK_NOTA"] > 0) && ($row_cek[0]["LUNAS"] == 'PIUTANG'))
			{
				echo '<a href="'.HOME.APPID.'.print/print_nota_lunas?no_nota='.$no_nota.'&no_req='.$no_req.'" target="_blank"><b><i> CETAK ULANG</i></b> </a> <br>';
                             //    echo '<font color="red"><i>PIUTANG</i></font>';
			}
		}
		else
		{
			echo '<a href="'.HOME.APPID.'/print_nota?no_req='.$no_req.'&n=999" target="_blank"> Preview Nota </a> ';
		}
	}
?>
