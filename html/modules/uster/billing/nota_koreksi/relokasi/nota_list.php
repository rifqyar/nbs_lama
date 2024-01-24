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
        
      	IF (isset($_POST['CARI'])){
			if((isset($_POST["NO_REQ"])) && ($from == NULL) && ($to == NULL))
		{
			$query_list		= "SELECT a.NO_REQUEST,
								         TO_CHAR(a.TGL_REQUEST,'dd Mon yyyy') TGL_REQUEST,
								         b.NM_PBM CONSIGNEE,
								         COUNT(c.NO_CONTAINER) JUMLAH
								    FROM request_receiving a, v_mst_pbm b, container_receiving c
								   WHERE     a.KD_CONSIGNEE = b.KD_PBM
								         AND a.RECEIVING_DARI = 'LUAR'
								         AND a.NO_REQUEST = c.NO_REQUEST
										 AND a.NO_REQUEST = '$no_req'
									GROUP BY a.NO_REQUEST,
								         a.TGL_REQUEST,
								         b.NM_PBM
									ORDER BY a.TGL_REQUEST DESC";
		}
		else if((isset($_POST["FROM"]))&& (isset($_POST["TO"])) && ($no_req == NULL))
		{
			$query_list		= "SELECT a.NO_REQUEST,
								         TO_CHAR(a.TGL_REQUEST,'dd Mon yyyy') TGL_REQUEST,
								         b.NM_PBM CONSIGNEE,
								         COUNT(c.NO_CONTAINER) JUMLAH
								    FROM request_receiving a, v_mst_pbm b, container_receiving c
								   WHERE     a.KD_CONSIGNEE = b.KD_PBM
								         AND a.RECEIVING_DARI = 'LUAR'
								         AND a.NO_REQUEST = c.NO_REQUEST
										 AND a.TGL_REQUEST BETWEEN TO_DATE ( '$from', 'YYYY-MM-DD ')
                                                 AND TO_DATE (  CONCAT('$to', '23:59:59'), 'YYYY-MM-DD HH24:MI:SS')
									GROUP BY a.NO_REQUEST,
								         a.TGL_REQUEST,
								         b.NM_PBM
									ORDER BY a.TGL_REQUEST DESC";
		} else if((isset($_POST["FROM"]))&& (isset($_POST["TO"])) && (isset($_POST["NO_REQ"])))
		{
			$query_list		= "SELECT a.NO_REQUEST,
								TO_CHAR(a.TGL_REQUEST,'dd Mon yyyy') TGL_REQUEST,
								         b.NM_PBM CONSIGNEE,
								         COUNT(c.NO_CONTAINER) JUMLAH
								    FROM request_receiving a, v_mst_pbm b, container_receiving c
								   WHERE     a.KD_CONSIGNEE = b.KD_PBM
								         AND a.RECEIVING_DARI = 'LUAR'
								         AND a.NO_REQUEST = c.NO_REQUEST
										 AND a.NO_REQUEST = '$no_req'
										 AND a.TGL_REQUEST BETWEEN TO_DATE ( '$from', 'YYYY-MM-DD ')
                                                 AND TO_DATE (  CONCAT('$to', '23:59:59'), 'YYYY-MM-DD HH24:MI:SS')
									GROUP BY a.NO_REQUEST,
								         a.TGL_REQUEST,
								         b.NM_PBM
									ORDER BY a.TGL_REQUEST DESC";
		}
		
		} else {
				$query_list = "SELECT a.NO_REQUEST,
							 TO_CHAR(a.TGL_REQUEST,'dd Mon yyyy') TGL_REQUEST,
							 b.NM_PBM CONSIGNEE,
							 COUNT(c.NO_CONTAINER) JUMLAH
							FROM REQUEST_RELOKASI a, v_mst_pbm b, CONTAINER_RELOKASI c
							WHERE     A.KD_EMKL = b.KD_PBM
							 AND a.NO_REQUEST = c.NO_REQUEST
							GROUP BY a.NO_REQUEST,
							 a.TGL_REQUEST,
							 b.NM_PBM
							ORDER BY a.TGL_REQUEST DESC"; 
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
		$query_cek	= "SELECT * FROM NOTA_RECEIVING WHERE NO_REQUEST = '$no_req'";
                //echo $query_cek;
		$result_cek	= $db->query($query_cek);
		$row_cek 	= $result_cek->getAll();
		
		if(count($row_cek) > 0)
		{
			$cetak		= $row_cek[0]["CETAK_NOTA"];
			$no_nota	= $row_cek[0]["NO_NOTA"];
			
			if (($row_cek[0]["CETAK_NOTA"] > 0) && ($row_cek[0]["LUNAS"] == 'NO'))
			{
				echo '<a href="'.HOME.APPID.'.print/print_nota_lunas?no_nota='.$no_nota.'&no_req='.$no_req.'" target="_blank""><b><i> CETAK ULANG </i></b></a><br> ';	
                              //  echo '<a href="'.HOME.APPID.'/set_lunas?no_nota='.$no_nota.'"><style:"font-color=red"> Set LUNAS</style> </a> ';	
			}
			else if (($row_cek[0]["CETAK_NOTA"] > 0) && ($row_cek[0]["LUNAS"] == 'YES'))
			{
				echo '<a href="'.HOME.APPID.'.print/print_nota_lunas?no_nota='.$no_nota.'&no_req='.$no_req.'"  target="_blank"><b><i> CETAK ULANG </i></b> </a> <br>';
                               //  echo '<font color="red"><i>SDH LUNAS</i></font>';
			}
                        else if (($row_cek[0]["CETAK_NOTA"] > 0) && ($row_cek[0]["LUNAS"] == 'PIUTANG'))
			{
				echo '<a href="'.HOME.APPID.'.print/print_nota_lunas?no_nota='.$no_nota.'&no_req='.$no_req.'" target="_blank"><b><i> CETAK ULANG </i></b></a> <br>';
                              //   echo '<font color="red"><i>PIUTANG</i></font>';
			}
		}
		else
		{
			echo '<a href="'.HOME.APPID.'/print_nota?no_req='.$no_req.'&n=999" target="_blank"> <b><i> Preview Nota </i></b></a> ';
		}
	}
?>
