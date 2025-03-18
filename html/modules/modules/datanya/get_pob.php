<?php
$q = $_GET['q'];
if(isset($q)) {
	$page = isset($_POST['page'])?$_POST['page']:1;  // get the requested page
	$limit = isset($_POST['rows'])?$_POST['rows']:10; // get how many rows we want to have into the grid
	$sidx = isset($_POST['sidx'])?$_POST['sidx']:'id_bprp'; // get index row - i.e. user click to sort
	//$sord = $_GET['sord']; // get the direction
	if(!$sidx) $sidx =1;
	$db = getDB();
	if($q=='req') {		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM POB_REQUEST)";
	}
	else if($q=='inc'){
		$query="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM POB_PENDAPATAN)";
	}
	else if($q=='nota'){
		$query="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM POB_PENDAPATAN)";
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
	if($q=='req') //ambil data header
		$query="SELECT * FROM POB_REQUEST ORDER BY NO_STOK DESC";
	else if($q=='inc')
		$query="SELECT * FROM POB_PENDAPATAN ORDER BY NO_NOTA DESC";
	else if($q=='nota')
		$query="SELECT * FROM POB_PENDAPATAN ORDER BY NO_NOTA DESC";
	
  $res = $db->query($query);
	
	while ($row = $res->fetchRow()) {
		$aksi = "";
		if($q == 'req') 
		{
			if($row[FLAG]=="N")
				$aksi = "<a href='request.pob_req/edit/$row[NO_STOK]'><img border='0' src='images/edit.png' title='Edit'></a>&nbsp;&nbsp;";
			$aksi .= "<a href='print/print_pob_req?p1=$row[NO_STOK]' target='_blank'><img border='0' src='images/printer.png' width='14' height='14' title='Cetak Request'></a>";	
			$seri_x= $row[NO_SERIX];
			$seri_y= $row[NO_SERIY];
			$no_seri= $seri_x .' sd '. $seri_y;
      $responce->rows[$i]['id']=$row[NO_STOK];
			$responce->rows[$i]['cell']=array($aksi,
                                $row[NO_STOK],
                                $row[TGL_STOK],
                                $row[PERIODE],
                                $no_seri,
                                $row[JUMLAH_LEMBAR],
                                $row[KETERANGAN],
                                $row[FLAG]);
		}
		else if($q == 'inc') {
		  if($row[FLAG]=="C")
		    $aksi = "<a href='request.pob_inc/update/$row[NO_NOTA]'><img border='0' width='20' height='20' src='images/transfer2.png' title='Invoice Pranota'></a>&nbsp;&nbsp;";
			$aksi .= "<a href='print/print_pranota_pasob?p1=$row[NO_NOTA]' target='_blank'><img border='0' src='images/printer.png' width='14' height='14' title='Cetak Pranota'></a>";
			$responce->rows[$i]['id']=$row[NO_NOTA];
			$responce->rows[$i]['cell']=array($aksi,
                                $row[NO_NOTA],
                                $row[TGL_NOTA],
                                $row[NO_STOK],
                                $row[JML_LEMBAR],
                                $row[PENDAPATAN],
                                $row[KETERANGAN],
                                $row[FLAG]);
		}
		else if($q == 'nota') {
		  if($row[JKM_NO]=="")
		    $aksi = "<a href='nota.pob/update/$row[NO_NOTA]'><img border='0' src='images/edit.png' title='Update JKM'></a>&nbsp;&nbsp;";
			$aksi .= "<a href='print/print_nota_pasob?p1=$row[NO_NOTA]' target='_blank'><img border='0' src='images/printer.png' width='14' height='14' title='Cetak Nota'></a>";
			$responce->rows[$i]['id']=$row[NO_NOTA];
			$responce->rows[$i]['cell']=array($aksi,
                                $row[NO_NOTA],
                                $row[RECEIPT_NO],
                                $row[JKM_NO],
                                $row[JKM_DATE],
                                $row[JML_LEMBAR],
                                $row[PENDAPATAN],
                                $row[FLAG]);
		}
		
		$i++;
	}
	echo json_encode($responce);
}
?>
