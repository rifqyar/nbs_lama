<?php
$q = $_GET['q'];
if(isset($q)) {
	$page = isset($_POST['page'])?$_POST['page']:1;  // get the requested page
	$limit = isset($_POST['rows'])?$_POST['rows']:10; // get how many rows we want to have into the grid
	$sidx = isset($_POST['sidx'])?$_POST['sidx']:'id_bprp'; // get index row - i.e. user click to sort
	//$sord = $_GET['sord']; // get the direction
	if(!$sidx) $sidx =1;
	$id = $_GET['id'];
	
	$db = getDB();
	if($q=='pranota_bprp') {
		//$query=OCIparse($conn, "SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM $tb_ntbprpd WHERE ID_NOTA='$id')");
		$query="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM OG_NOTA_BPRPD WHERE ID_NOTA='$id')";
	}
	else if($q=='vs_fix'){
		//$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM TR_VESSEL_SCHEDULE_ICT)";
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM m_vsb_voyage)";
		$db = getDB('dbint');
	}
	else if($q=='pranota_bm')
		$query="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM OG_DNOTA WHERE ID_NOTA='$id')";
	else if($q=='pranota_dk')
		$query="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM OG_DNOTA2 WHERE ID_NOTA='$id')";
	else if($q=='pranota_lolo')
		$query="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM OG_NOTA_LOLOD WHERE ID_NOTA='$id')";
	
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
	if($q=='pranota_bprp') {
		/*$query=OCIparse($conn, "SELECT NO, OG_GETNAMA.GET_NAMABRG(JNS_BRG) JNS_BRG, OG_GETNAMA.GET_NAMAKMS(KEMASAN) KEMASAN, JUMLAH, SATUAN, DECODE(BERBAHAYA,1,'YA','TIDAK') BERBAHAYA, 
								TGL_IN, TGL_OUT, TGH_PENUMPUKAN, TGH_KEBERSIHAN, SUBTOTAL FROM $tb_ntbprpd WHERE ID_NOTA='$id' ORDER BY NO");*/
		$query="SELECT NO, OG_GETNAMA.GET_NAMABRG(JNS_BRG) JNS_BRG, OG_GETNAMA.GET_NAMAKMS(KEMASAN) KEMASAN, JUMLAH, SATUAN, DECODE(BERBAHAYA,1,'YA','TIDAK') BERBAHAYA, 
				TGL_IN, TGL_OUT, TGH_PENUMPUKAN, TGH_KEBERSIHAN, SUBTOTAL FROM OG_NOTA_BPRPD WHERE ID_NOTA='$id' ORDER BY NO";
	}
	else if($q=='vs_fix') //ambil data header
		$query="SELECT ID_VSB_VOYAGE ,VESSEL , VOYAGE_IN ,VOYAGE_OUT,
		to_char(TO_DATE(ETA,'YYYYMMDDHH24MISS'),'DD MON YYYY HH24:Mi') ETA, 
		to_char(TO_DATE(ETD,'YYYYMMDDHH24MISS'),'DD MON YYYY HH24:Mi') ETD, 
		to_char(TO_DATE(ATA,'YYYYMMDDHH24MISS'),'DD MON YYYY HH24:Mi') ATA,
		to_char(TO_DATE(ATD,'YYYYMMDDHH24MISS'),'DD MON YYYY HH24:Mi') ATD 
		FROM m_vsb_voyage ORDER BY id_vsb_voyage desc";
	else if($q=='pranota_bm')
		$query="SELECT NO, VIA, OG_GETNAMA.GET_NAMABRG(JNS_BRG) JNS_BRG, OG_GETNAMA.GET_NAMAKMS(KEMASAN) KEMASAN, BONGKAR, MUAT, SATUAN, DECODE(BERBAHAYA,1,'YA','TIDAK') BERBAHAYA, 
								OG_GETNAMA.GET_NAMAKEG(KEGIATAN) KEGIATAN, OG_GETNAMA.GET_NAMASUBKEG(SUBKEG) SUBKEG, TARIF, SUBTOTAL FROM OG_DNOTA WHERE ID_NOTA='$id' ORDER BY NO";
	else if($q=='pranota_dk')
		$query="SELECT NO, VIA, OG_GETNAMA.GET_NAMABRG(JNS_BRG) JNS_BRG, OG_GETNAMA.GET_NAMAKMS(KEMASAN) KEMASAN, BONGKAR, MUAT, SATUAN, DECODE(BERBAHAYA,1,'YA','TIDAK') BERBAHAYA, 
								OG_GETNAMA.GET_NAMAKEG(KEGIATAN) KEGIATAN, OG_GETNAMA.GET_NAMASUBKEG(SUBKEG) SUBKEG, TOT_TARIF TARIF, SUBTOTAL FROM OG_DNOTA2 WHERE ID_NOTA='$id' ORDER BY NO";	
	else if($q=='pranota_lolo')
		$query="SELECT NO, OG_GETNAMA.GET_NAMABRG(JNS_BRG) JNS_BRG, OG_GETNAMA.GET_NAMAKMS(KEMASAN) KEMASAN, JUMLAH, SATUAN, LIFT_ON, LIFT_OFF, SUB_TOTAL
				FROM OG_NOTA_LOLOD WHERE ID_NOTA='$id' ORDER BY NO";
	$res = $db->query($query);								
	while ($row = $res->fetchRow()) {
		$aksi = "";
		if($q=='pranota_bprp') {
			$responce->rows[$i]['id']=$row[NO];
			$responce->rows[$i]['cell']=array($row[JNS_BRG],$row[KEMASAN],$row[JUMLAH],$row[SATUAN],$row[BERBAHAYA],$row[TGL_IN],$row[TGL_OUT],$row[TGH_PENUMPUKAN],$row[TGH_KEBERSIHAN],$row[SUBTOTAL]);
		}
		else if($q=='pranota_bm') {
			$responce->rows[$i]['id']=$row[NO];
			$responce->rows[$i]['cell']=array($row[VIA],$row[JNS_BRG],$row[KEMASAN],$row[BONGKAR],$row[MUAT],$row[SATUAN],$row[BERBAHAYA],$row[KEGIATAN],$row[SUBKEG],$row[TARIF],$row[SUBTOTAL]);
		}
		else if($q=='pranota_dk') {
			$responce->rows[$i]['id']=$row[NO];
			$responce->rows[$i]['cell']=array($row[VIA],$row[JNS_BRG],$row[KEMASAN],$row[BONGKAR],$row[MUAT],$row[SATUAN],$row[BERBAHAYA],$row[KEGIATAN],$row[SUBKEG],$row[TARIF],$row[SUBTOTAL]);
		}
		else if($q=='pranota_lolo') {
			$responce->rows[$i]['id']=$row[NO];
			$responce->rows[$i]['cell']=array($row[JNS_BRG],$row[KEMASAN],$row[JUMLAH],$row[SATUAN],$row[LIFT_ON],$row[LIFT_OFF],$row[SUB_TOTAL]);
		}
		else if($q == 'vs_fix') 
		{
			$pw=$row[ID_VSB_VOYAGE];
			$voy	= $row[VOYAGE_IN].' - '.$row[VOYAGE_OUT];
			$responce->rows[$i]['id']=$row[ID_VSB_VOYAGE];
			$responce->rows[$i]['cell']=array($row[ID_VSB_VOYAGE],$row[VESSEL],$voy, $row[ETA],$row[ETD],$row[ATA],$row[ATD]);
		}
		$i++;
	}
	echo json_encode($responce);
}
?>