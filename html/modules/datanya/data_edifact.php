<?php
$q = $_GET['q'];
$list_det_ukk = $_GET['list_det_ukk'];
$no_ukks = $_GET['no_ukks'];
//$no_ukk = $_GET['no_ukk'];
//$no_cont = $_GET['no_cont'];
$no_bundle = $_GET['no_bundle'];
//PRINT_r($q);die;
//debug($no_bundle);die;
$id_group = $_SESSION["ID_GROUP"];
if(isset($q)) {
	$page = isset($_POST['page'])?$_POST['page']:1;  // get the requested page
	$limit = isset($_POST['rows'])?$_POST['rows']:10; // get how many rows we want to have into the grid
	$sidx = isset($_POST['sidx'])?$_POST['sidx']:'id_bprp'; // get index row - i.e. user click to sort
	//$sord = $_GET['sord']; // get the direction
	if(!$sidx) $sidx =1;
	$db = getDB();
	if($q=='pranota_bprp') {		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM OG_NOTA_BPRPH WHERE STATUS!='X')";
		//$query=OCIparse($conn, "SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM $tb_ntbprph WHERE STATUS!='X')");
	}
	else if($q=='edifact_file')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM RBM_H)";	
	//print_r($query);die;
	$res = $db->query($query)->fetchRow();
	$count = $res[NUMBER_OF_ROWS];
	
	/*oci_define_by_name($query, 'NUMBER_OF_ROWS', $count);
	oci_execute($query);
	oci_fetch($query);*/

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
	if($q=='pranota_bprp') //ambil data header
		$query="SELECT A.*, B.*, OG_GETNAMA.GET_PERDAGANGAN(B.JN_DAGANG) PERDAGANGAN FROM OG_NOTA_BPRPH A, OG_BPRPH B WHERE A.STATUS!='X' AND A.ID_BPRP=B.ID_BPRP ORDER BY ID_NOTA DESC";
	else if($q=='edifact_file') //ambil data header
		$query="SELECT NO_UKK, 
						TRIM(NM_KAPAL) AS NM_KAPAL,
						VOYAGE_IN ,
						VOYAGE_OUT,
                        NM_PELABUHAN_ASAL AS POD,
						NM_PELABUHAN_TUJUAN AS POL,
						TO_CHAR(TGL_JAM_TIBA,'DD-MM-YYYY HH24:MI') AS TGL_JAM_TIBA,
						TO_CHAR(TGL_JAM_BERANGKAT,'DD-MM-YYYY HH24:MI') AS TGL_JAM_BERANGKAT,
						TO_CHAR(TGL_JAM_TIBA,'YYYYMMDDHH24MI') AS SEQ 
			 FROM RBM_H
			 ORDER BY SEQ DESC";
	$res = $db->query($query);
	//debug($res);die;
	//ociexecute($query);
	
	while ($row = $res->fetchRow()) {
		$aksi = "";
		if($q == 'pranota_bprp') 
		{
			if($row[STATUS]=="I")
				$aksi = "<a href='nota.penumpukan_ogdk/edit/$row[ID_NOTA]'><img border='0' src='images/edit.png' title='update jkm'></a>&nbsp;&nbsp;<a href='print/print_nota_bprp?p1=$row[ID_NOTA]' target='_blank'><img border='0' src='images/printer.png' width='14' height='14' title='cetak nota'></a>&nbsp;&nbsp;";
			$aksi .= "<a href='print/print_pranota_bprp?p1=$row[ID_NOTA]' target='_blank'><img border='0' src='images/view.png' width='14' height='14' title='cetak pranota BPRP'></a>";	
			$responce->rows[$i]['id']=$row[ID_NOTA];
			$responce->rows[$i]['cell']=array($aksi,$row[ID_BPRP],$row[ID_NOTA],$row[TGL_INVOICE],$row[TERMINAL],$row[NM_CUST],$row[VESSEL],$row[VOYAGE],$row[PERDAGANGAN],$row[NO_JKM],$row[TOTAL],$row[STATUS]);
		}
		//CONT MOVEMENT
		else if($q == 'edifact_file') 
		{		
			$coco = "<a onclick='codeco(\"$row[NO_UKK]\")'><img src='images/edi_file.png' width=24px height=24px title='codeco'></a>&nbsp;&nbsp;<a onclick='coarri(\"$row[NO_UKK]\")'><img src='images/edi_file.png' width=24px height=24px title='coarri'></a>";
			
			$bp = "<a onclick='baplie(\"$row[NO_UKK]\")'><img src='images/baplie.png' width=24px height=24px title='baplie loading'></a>";
			$sch = "<button onclick='active_sch(\"$no_ukks\")' title='active scheduler'><img src='images/green_tombol.png' width=15px height=15px></button>&nbsp;&nbsp;<button onclick='inactive_sch(\"$no_ukks\")' title='inactive scheduler'><img src='images/red_tombol.png' width=15px height=15px></button>";		
			$voy = $row[VOYAGE_IN].'/'.$row[VOYAGE_OUT];
			$responce->rows[$i]['id']=$row[NO_UKK];
			$responce->rows[$i]['cell']=array($coco,$bp,$sch,$row[NO_UKK],$row[NM_KAPAL],$voy,$row[TGL_JAM_TIBA],$row[TGL_JAM_BERANGKAT],$row[POD],$row[POL]);
		}
		
		$i++;
	}
	echo json_encode($responce);
}
?>