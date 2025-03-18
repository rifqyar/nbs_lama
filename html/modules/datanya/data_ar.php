<?php
$q = $_GET['q'];
//$id_group = $_SESSION["ID_GROUP"];
if(isset($q)) {
	$page = isset($_POST['page'])?$_POST['page']:1;  // get the requested page
	$limit = isset($_POST['rows'])?$_POST['rows']:10; // get how many rows we want to have into the grid
	$sidx = isset($_POST['sidx'])?$_POST['sidx']:1; // get index row - i.e. user click to sort
	//$sord = $_GET['sord']; // get the direction
	if(!$sidx) $sidx =1;
	$db = getDB('pyma');
	if($q=='ar')
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM AR_SIMKEU)";
	else if($q=='p_rec')
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM REQ_RECEIVING_H)";
	else if ($q=='dllist')
		$query='SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM RBM_H)';
	else if ($q=='vs_fix')
		$query='SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM RBM_H)';
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
	if($q=='ar') //ambil data header
		$query="SELECT * FROM AR_SIMKEU ORDER BY TRX_DATE DESC";
	else if($q=='p_rec') //ambil data header
		$query="SELECT a.ID_REQ,
                         a.KODE_PBM AS EMKL,
                         a.VESSEL,
                         a.VOYAGE,
						 NVL((SELECT COUNT (1) JUMLAH_CONT FROM req_receiving_d c WHERE c.NO_REQ_ANNE = a.ID_REQ),0) JUMLAH_CONT,
						 a.STATUS, a.PEB,a.NPE,a.TGL_STACK, a.TGL_MUAT,a.NO_UKK
                    FROM req_receiving_h a order by a.id_req desc";
	else if($q=='dllist') //ambil data header
		$query="SELECT NO_UKK ID_VS,(SELECT KETERANGAN FROM MASTER_JENIS_KAPAL WHERE ID_JK=JENIS_KAPAL) AS JENIS_KAPAL2,NM_KAPAL NAMA_VESSEL , VOYAGE_IN ,VOYAGE_OUT, to_char(TGL_JAM_TIBA,'DD MON YYYY HH24:Mi') ETA, to_char(TGL_JAM_BERANGKAT,'DD MON YYYY HH24:Mi') ETD, NM_PELABUHAN_ASAL, NM_PELABUHAN_TUJUAN,to_char(RTA,'DD MON YYYY HH24:Mi') RTA,to_char(RTD,'DD MON YYYY HH24:Mi') RTD, STATUS, FLAG_SYNC_CARD FROM RBM_H ORDER BY DATE_INSERT DESC";
	else if($q=='vs_fix') //ambil data header
		$query="SELECT NO_UKK,NM_KAPAL,VOYAGE_IN,VOYAGE_OUT, (SELECT KETERANGAN FROM MASTER_JENIS_KAPAL WHERE ID_JK=JENIS_KAPAL) AS TIPE_KPL, to_char(TGL_JAM_TIBA,'DD MON YYYY HH24:Mi') ETA, to_char(TGL_JAM_BERANGKAT,'DD MON YYYY HH24:Mi') ETD, NM_PELABUHAN_ASAL, NM_PELABUHAN_TUJUAN,to_char(RTA,'DD MON YYYY HH24:Mi') RTA,to_char(RTD,'DD MON YYYY HH24:Mi') RTD, STATUS, FLAG_SYNC_CARD FROM RBM_H ORDER BY DATE_INSERT DESC";
	$res = $db->query($query);
	//debug($res);die;	
	while ($row = $res->fetchRow()) {
		$aksi = "";
		if($q == 'ar') 
		{
			$responce->rows[$i]['id']=$row[TRX_NUMBER];
			$responce->rows[$i]['cell']=array($row[TRX_NUMBER],$row[TRX_DATE],$row[GL_DATE],$row[TYPE],$row[ACCOUNT_NUMBER],
												$row[CUSTOMER_NAME],$row[TIPE_JASA],$row[DESCRIPTION],$row[NO_ORDER],$row[AMOUNT_LINE],$row[AMOUNT_PPN],
												$row[FAKTUR_KENA],$row[FAKTUR_BEBAS],$row[TOTAL],$row[CURRENCY],$row[YEAR_UP],$row[MONTH_UP]);
		}
		else if ($q=='p_rec')
		{
			if(($row[STATUS]=="P")||($row[STATUS]=="T")) //means Saved Invoice
			{
				$aksi = "<a href=".HOME."print.receiving_card.print_rec/print?no_req=".$row[ID_REQ]."><img src='images/printer.png' title='edit request'></a>";	
				
			}
			else
			{
				$aksi = "<blink><font color='red'><b><i>Request <br>belum dibayar</i></b></font></blink>";	
			}
			$vesv=$row[VESSEL].' - '.$row[VOYAGE];
			$responce->rows[$i]['id']=$row[ID_REQ];
			$responce->rows[$i]['cell']=array($aksi,$row[STATUS],$row[ID_REQ],$row[PEB],$row[NPE],$row[NO_UKK], $row[TGL_STACK], $row[TGL_MUAT],$row[EMKL],$vesv,$row[JUMLAH_CONT]);
		}
		else if ($q=='dllist')
		{
			if(($row[STATUS]=="P")||($row[STATUS]=="T")) //means Saved Invoice
			{
				$aksi = "<a href=".HOME."print.receiving_card.print_rec/print?no_req=".$row[ID_REQ]."><img src='images/printer.png' title='edit request'></a>";	
				
			}
			else
			{
				$aksi = "<blink><font color='red'><b><i>Request <br>belum dibayar</i></b></font></blink>";	
			}
			$vesv=$row[VESSEL].' - '.$row[VOYAGE];
			$responce->rows[$i]['id']=$row[ID_REQ];
			$responce->rows[$i]['cell']=array($aksi,$row[ID_REQ],$row[PEB],$row[NPE],$row[NO_UKK], $row[TGL_STACK], $row[TGL_MUAT],$row[EMKL],$vesv,$row[JUMLAH_CONT]);
		}
		else if ($q=='vs_fix')
		{
			if(($row[FLAG_SYNC_CARD]==1)) //means Saved Invoice
			{
				$aksi = "<a href=".HOME."print.disch_load_list.cetak/disch_list?p1=".$row[NO_UKK]."><img src='images/print2.png' title='Cetak Discharging List'></a>&nbsp;&nbsp;<a href=".HOME."print.disch_load_list.cetak/load_list?p1=".$row[NO_UKK]."><img src='images/print3.png' title='Cetak Loading List'></a>";	
				
			}
			else
			{
				$aksi = "<blink><font color='red'><b><i>Request <br>belum dibayar</i></b></font></blink>";	
			}
			$vesv=$row[VESSEL].' - '.$row[VOYAGE];
			$responce->rows[$i]['id']=$row[NO_UKK];
			$responce->rows[$i]['cell']=array($aksi,$row[NO_UKK],$row[NM_KAPAL],$row[VOYAGE_IN]."-".$row[VOYAGE_OUT],$row[TIPE_KPL], $row[ETA], $row[ETD],$row[RTA],$row[RTD],$row[NM_PELABUHAN_ASAL], $row[NM_PELABUHAN_TUJUAN]);
		}
		$i++;
	}
	echo json_encode($responce);
}
?>