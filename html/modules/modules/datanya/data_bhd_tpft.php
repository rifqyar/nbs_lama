<?php
$db = getDB();
$q  = $_GET['q'];

if(isset($q)) {
	$page = isset($_POST['page'])?$_POST['page']:1;  // get the requested page
	$limit = isset($_POST['rows'])?$_POST['rows']:10; // get how many rows we want to have into the grid
	$sidx = isset($_POST['sidx'])?$_POST['sidx']:'id_bprp'; // get index row - i.e. user click to sort
	//$sord = $_GET['sord']; // get the direction
	if(!$sidx) $sidx =1;
	
	if($q=='list_pranota_tpft') {
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM REQ_BHD_H";
	}
	else
	if($q=='list_nota_tpft') {
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM REQ_BHD_H";
	}
		
	$res = $db->query($query)->fetchRow();
	$count = $res[NUMBER_OF_ROWS];
	
	if( $count >0 ) {
		$total_pages = ceil($count/$limit);
	}
	else { 
		$total_pages = 0;
	}
	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit; // do not put $limit*($page - 1)	

	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	if($q=='list_pranota_tpft') {   //ambil data
	
		$query="SELECT A.ID_BHD,
					   A.NO_PRANOTA,
					   TO_CHAR (A.TGL_PRANOTA, 'DD/MM/RRRR') TGL_PRANOTA,
					   A.CUST_NAME,
					   A.PERIODE,
					   TO_CHAR (A.TOTAL_TAGIHAN, '999,999,999,999') TOTAL_TAGIHAN,
					   A.STATUS_NOTA,
					   (SELECT COUNT (1)
						  FROM BIL_BHD_CONT_H
						 WHERE ID_PRANOTA = a.ID_BHD)
						  JML_CONT
				  FROM REQ_BHD_H A
				 ORDER BY A.TGL_PRANOTA DESC";
	}
	else
	if($q=='list_nota_tpft') {   //ambil data
	
		$query="SELECT A.ID_BHD,
					   B.NO_NOTA,
					   A.NO_PRANOTA,
					   TO_CHAR (A.TGL_PRANOTA, 'DD/MM/RRRR') TGL_PRANOTA,
					   A.CUST_NAME,
					   A.PERIODE,
					   TO_CHAR (A.TOTAL_TAGIHAN, '999,999,999,999') TOTAL_TAGIHAN,
					   A.STATUS_NOTA,
					   B.LUNAS,
					   (SELECT COUNT (1)
						  FROM BIL_BHD_CONT_H
						 WHERE ID_PRANOTA = a.ID_BHD)
						  JML_CONT
				  FROM REQ_BHD_H A,BIL_NOTA_BHD_H B
				 WHERE A.NO_PRANOTA = B.NO_PRANOTA(+)
				 ORDER BY A.TGL_PRANOTA DESC";
	}
	
	$res = $db->query($query);
	while ($row = $res->fetchRow()) {
	    
		$aksi = "";
		if($q == 'list_pranota_tpft') 
		{
			$id_pranota = $row[ID_BHD];
			$cetak_pranota  = "<button title='Cetak Pranota' onclick='cetak_pranota(\"$id_pranota\")'><img src='images/printer.png' height='15px' width='15px'></button>";
			$cetak_detail_pranota  = "<button title='Cetak Detail Pranota' onclick='cetak_detail_pranota(\"$id_pranota\")'><img src='images/printer.png' height='15px' width='15px'></button>";
			$responce->rows[$i]['id']=$i;
			$responce->rows[$i]['cell']=array($cetak_pranota.$cetak_detail_pranota,$row[NO_PRANOTA],$row[TGL_PRANOTA],$row[PERIODE],$row[CUST_NAME],$row[JML_CONT],$row[TOTAL_TAGIHAN],$row[STATUS_NOTA]);
		}
		else
		if($q == 'list_nota_tpft') 
		{
			$id_pranota = $row[ID_BHD];
			$status = $row[STATUS_NOTA];
			if($status=='N'){
			   $cetak = "<button title='Preview Nota' onclick='preview_nota(\"$id_pranota\")'><img src='images/preview.png' height='15px' width='15px'></button>";
			   $no_nota = "<font color='red'>Belum Nota</font>";
			}
			else{
			   $cetak = "<button title='Cetak Nota' onclick='cetak_nota(\"$id_pranota\")'><img src='images/printer.png' height='15px' width='15px'></button>";
			   $no_nota = $row[NO_NOTA];;
			}
						
			$responce->rows[$i]['id']=$i;
			$responce->rows[$i]['cell']=array($cetak,$no_nota,$row[NO_PRANOTA],$row[TGL_PRANOTA],$row[PERIODE],$row[CUST_NAME],$row[JML_CONT],$row[TOTAL_TAGIHAN],$row[LUNAS]);
		}
						
		$i++;
	}
	echo json_encode($responce);
}
?>