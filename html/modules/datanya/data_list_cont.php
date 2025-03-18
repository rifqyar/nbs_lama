<?php
$q = $_GET['q'];
//debug($q);die;
$no_ukk = $_GET['no_ukk'];
//debug($no_ukk);die;



//PRINT_r($q);die;
//debug($no_req);die; 
$id_group = $_SESSION["ID_GROUP"];
if(isset($q)) {
	$page = isset($_POST['page'])?$_POST['page']:1;  // get the requested page
	$limit = isset($_POST['rows'])?$_POST['rows']:10; // get how many rows we want to have into the grid
	$sidx = isset($_POST['sidx'])?$_POST['sidx']:'id_bprp'; // get index row - i.e. user click to sort
	//$sord = $_GET['sord']; // get the direction
	if(!$sidx) $sidx =1;
	$db = getDB();
	if($q=='status') {		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM TR_NOTA_ANNE_ICT_H)";
	}
	else if($q=='status_detail')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM TR_NOTA_ANNE_ICT_D)";
	else if($q=='status_e')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (select *
					FROM TR_NOTA_ANNE_ICT_H a,
						 TR_NOTA_ANNE_ICT_D b
					WHERE 
					REGEXP_REPLACE (a.NO_REQ_ANNE, '[[:space:]]', '') =
					REGEXP_REPLACE (b.NO_REQ_ANNE, '[[:space:]]', '')
					AND a.NO_UKK='$no_ukk')";
	else if($q=='status_detail')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (select *
					FROM TR_NOTA_ANNE_ICT_H a,
						 TR_NOTA_ANNE_ICT_D b
					WHERE 
					REGEXP_REPLACE (a.NO_REQ_ANNE, '[[:space:]]', '') =
					REGEXP_REPLACE (b.NO_REQ_ANNE, '[[:space:]]', '')
					AND a.NO_UKK='$no_ukk')";	
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
	/*if($q=='status') //ambil data header
		$query="SELECT NO_REQ_ANNE, NO_NOTA, NO_FAKTUR, NO_UKK ID_VS, NO_BOOKING, VESSEL, VOYAGE_IN ,VOYAGE_OUT, RTA, RTD, PEL_ASAL, PEL_TUJ, NM_AGEN, STATUS_NOTA FROM TR_NOTA_ANNE_ICT_H"; */
	if($q=='status') //ambil data header
		$query="SELECT NO_REQ_ANNE, NO_NOTA, NO_FAKTUR, NO_UKK ID_VS, NO_BOOKING, VESSEL, VOYAGE_IN ,VOYAGE_OUT, RTA, RTD, PEL_ASAL, PEL_TUJ, NM_AGEN, STATUS_NOTA FROM TR_NOTA_ANNE_ICT_H";	
	else if($q=='status_detail') //ambil data header
		$query="SELECT NO_REQ_ANNE, NO_CONTAINER, SIZE_CONT, TYPE_CONT, STATUS_CONT, HZ, KD_COMODITY, TGL_GATE_JS, VESSEL, VOYAGE_IN, VOYAGE_OUT, PEL_ASAL, PEL_TUJ, IMO_CLASS FROM TR_NOTA_ANNE_ICT_D WHERE NO_REQ_ANNE = '$no_req'";	
	else if($q=='status_e') //ambil data header
		$query="
				SELECT 
				b.NO_CONTAINER,
				a.NO_NOTA,
				a.STATUS_NOTA,
				a.KD_PELANGGAN,
				a.EMKL,
				a.NO_NPE,
				a.NO_PEB,
				a.PEL_ASAL,
				a.PEL_TUJ,
				b.SIZE_CONT,
				b.TYPE_CONT,
				b.STATUS_CONT,
				b.IMO_CLASS,
				--e.KODE_STATUS,

				CASE
				WHEN e.KODE_STATUS IS NOT NULL THEN '56' ELSE
					CASE
					WHEN c.PLACEMENT_DATE IS NOT NULL THEN '51' ELSE 
							CASE 
							WHEN d.TGL_GATEIN IS NOT NULL THEN '50' ELSE
							 '49'
							 END 
					 END
				END 
				STATUS, 
				c.SLOT_YARD,
				c.ROW_YARD,
				c.TIER_YARD,
				c.NAMA_BLOCK 
				FROM TR_NOTA_ANNE_ICT_H a,
						  TR_NOTA_ANNE_ICT_D b,
						  YD_PLACEMENT_YARD c,
						  TB_CONT_JOBSLIP d,
						  RBM_LIST e
				WHERE 
				REGEXP_REPLACE (a.NO_REQ_ANNE, '[[:space:]]', '') =
				REGEXP_REPLACE (b.NO_REQ_ANNE, '[[:space:]]', '')
				AND a.NO_UKK = d.ID_VS(+)
				AND b.NO_CONTAINER = c.NO_CONTAINER(+)
				AND b.NO_CONTAINER = e.NO_CONTAINER(+)
				AND a.NO_UKK ='$no_ukk'
				GROUP BY 
				b.NO_CONTAINER,
				a.NO_NOTA,
				a.STATUS_NOTA,
				a.KD_PELANGGAN,
				a.EMKL,
				a.NO_NPE,
				a.NO_PEB,
				a.PEL_ASAL,
				a.PEL_TUJ,
				b.SIZE_CONT,
				b.TYPE_CONT,
				b.STATUS_CONT,
				b.IMO_CLASS,
				--e.KODE_STATUS,
				CASE
				WHEN e.KODE_STATUS IS NOT NULL THEN '56' ELSE
					CASE
					WHEN c.PLACEMENT_DATE IS NOT NULL THEN '51' ELSE 
							CASE 
							WHEN d.TGL_GATEIN IS NOT NULL THEN '50' ELSE
							 '49'
							 END 
					 END
				END ,
				c.SLOT_YARD,
				c.ROW_YARD,
				c.TIER_YARD,
				c.NAMA_BLOCK ";	
	else if($q=='status_i') //ambil data header
		$query="SELECT 
				b.NO_CONTAINER,
				a.NO_NOTA,
				a.STATUS_NOTA,
				a.KD_PELANGGAN,
				a.EMKL,
				a.NO_NPE,
				a.NO_PEB,
				b.SIZE_CONT,
				b.TYPE_CONT,
				b.STATUS_CONT,
				--e.KODE_STATUS,

				CASE
				WHEN e.KODE_STATUS IS NOT NULL THEN '56' ELSE
					CASE
					WHEN c.PLACEMENT_DATE IS NOT NULL THEN '51' ELSE 
							CASE 
							WHEN d.TGL_GATEIN IS NOT NULL THEN '50' ELSE
							 '49'
							 END 
					 END
				END 
				STATUS, 
				c.SLOT_YARD,
				c.ROW_YARD,
				c.TIER_YARD,
				c.NAMA_BLOCK 
				FROM TR_NOTA_ANNE_ICT_H a,
						  TR_NOTA_ANNE_ICT_D b,
						  YD_PLACEMENT_YARD c,
						  TB_CONT_JOBSLIP d,
						  RBM_LIST e
				WHERE 
				REGEXP_REPLACE (a.NO_REQ_ANNE, '[[:space:]]', '') =
				REGEXP_REPLACE (b.NO_REQ_ANNE, '[[:space:]]', '')
				AND a.NO_UKK = d.ID_VS(+)
				AND b.NO_CONTAINER = c.NO_CONTAINER(+)
				AND b.NO_CONTAINER = e.NO_CONTAINER(+)
				AND a.NO_UKK ='$no_ukk'
				GROUP BY 
				b.NO_CONTAINER,
				a.NO_NOTA,
				a.STATUS_NOTA,
				a.KD_PELANGGAN,
				a.EMKL,
				a.NO_NPE,
				a.NO_PEB,
				b.SIZE_CONT,
				b.TYPE_CONT,
				b.STATUS_CONT,
				--e.KODE_STATUS,
				CASE
				WHEN e.KODE_STATUS IS NOT NULL THEN '56' ELSE
					CASE
					WHEN c.PLACEMENT_DATE IS NOT NULL THEN '51' ELSE 
							CASE 
							WHEN d.TGL_GATEIN IS NOT NULL THEN '50' ELSE
							 '49'
							 END 
					 END
				END ,
				c.SLOT_YARD,
				c.ROW_YARD,
				c.TIER_YARD,
				c.NAMA_BLOCK ";	
	
	$res = $db->query($query);
	//debug($res);die;
	//ociexecute($query);
	
	//debug($res);die;
	
	while ($row = $res->fetchRow()) {
		$aksi = "";
		
		// LIST CONTAINER BY STATUS by_status
		if($q == 'status') 
		{
			$pw=$row[ID_VS];
			
			//$status = 'dama';
			//$aksi = "<div><a href=".HOME."billing.rbm.cetak/cetak_rbm?id_vessel=".$row[ID_VS]." target='blank'><img src='images/print.png' width=20px height=20px border='0' title='cetak RBM'></a>&nbsp;<a href=".HOME."billing.rbm.cetak/cetak_rbm2?id_vessel=".$row[ID_VS]." target='blank'><img src='".HOME."images/print_2.png' width=20px height=20px border='0' title='cetak RBM format baru'></a></div>";
			//$aksi1 = "<a href=".HOME."monitoring.cont_move.excel.download/download_excel?id_vessel=".$row[ID_VS]." target='blank'><img src='images/download1.png' width=30px height=30px title='download excel'></a>";
			//$aksi1 = "<a href=".HOME."monitoring.cont_move.excel.download/export_excel?id_vessel=".$row[ID_VS]." target='blank'><img src='images/download1.png' width=30px height=30px title='download excel'></a>";
			$list = "<div><a href=".HOME."monitoring.list_container.list_detail/list_detail?no_req_anne=".$row[NO_REQ_ANNE]." target='blank'>DETAIL</a></div>";
			//$sc		= "<button onclick='sync_rbm(\"$pw\")' title='update RBM'><img src='images/Refresh2.png' width=15px height=15px border='0'></button>&nbsp;<button onclick='get_data(\"$pw\")' title='get data ict'><img src='images/Refresh3.png' width=15px height=15px border='0'></button>";
			$voy	= $row[VOYAGE_IN].' - '.$row[VOYAGE_OUT];
			$responce->rows[$i]['id']=$row[NO_REQ_ANNE];
			$responce->rows[$i]['cell']=array($list,$row[NO_REQ_ANNE],$row[NO_NOTA],$row[NO_FAKTUR],$row[ID_VS],$row[NO_BOOKING],$row[VESSEL],$voy,$row[RTA],$row[RTD],$row[PEL_ASAL],$row[PEL_TUJ],$row[NM_AGEN]);
		
		}
		
		//SELECT NO_REQ_ANNE, NO_CONTAINER, SIZE_CONT, TYPE_CONT, STATUS_CONT, HZ, KD_COMODITY, TGL_GATE_JS, VESSEL, VOYAGE_IN, VOYAGE_OUT, PEL_ASAL, PEL_ASAL, PEL_TUJ, IMO_CLASS FROM TR_NOTA_ANNE_ICT_D 
		else if($q == 'status_detail') 
		{
			
			$list = "<div><a href=".HOME."monitoring.list_container.list_detail/list_detail?no_req_anne=".$row[NO_REQ_ANNE]." target='blank'><i>DETAIL</i></a></div>";
			$responce->rows[$i]['id']=$row[NO_REQ_ANNE];
			$responce->rows[$i]['cell']=array($row[NO_REQ_ANNE],$row[NO_CONTAINER],$row[SIZE_CONT], $row[TYPE_CONT],$row[STATUS_CONT],$row[HZ],$row[KD_COMODITY],$row[TGL_GATE_JS],$row[VESSEL],$row[VOYAGE_IN],$row[VOYAGE_OUT],$row[PEL_ASAL],$row[PEL_TUJ],$row[IMO_CLASS]);
		}
		else if($q == 'status_e') 
		{
			/*
			$list = "<div><a href=".HOME."monitoring.list_container.list_detail/list_detail?no_req_anne=".$row[NO_REQ_ANNE]." target='blank'><i>DETAIL</i></a></div>";
			<font color='red'><b><i>Counter almost full</i></b></font>
			*/
			$status = "<div><font color='#3076A3'><b>".$row[STATUS]."</b></font></div>";
			$status_nota = "<div><font color='#E62E00'><b>".$row[STATUS_NOTA]."</b></font></div>";
			//debug($responce);die;
			$responce->rows[$i]['id']=$row[NO_CONTAINER];
			$responce->rows[$i]['cell']=array($row[NO_CONTAINER],$row[NO_NOTA],$status_nota,$row[EMKL],$row[SIZE_CONT],$row[TYPE_CONT],$row[STATUS_CONT],$status,$row[SLOT_YARD],$row[ROW_YARD],$row[TIER_YARD],$row[NAMA_BLOCK],$row[PEL_ASAL],$row[PEL_TUJ],$row[IMO_CLASS],$row[NO_NPE],$row[NO_PEB]);
		}
		else if($q == 'status_i') 
		{
			
			$list = "<div><a href=".HOME."monitoring.list_container.list_detail/list_detail?no_req_anne=".$row[NO_REQ_ANNE]." target='blank'><i>DETAIL</i></a></div>";
			$responce->rows[$i]['id']=$row[NO_REQ_ANNE];
			$responce->rows[$i]['cell']=array($row[NO_REQ_ANNE],$row[NO_CONTAINER],$row[SIZE_CONT], $row[TYPE_CONT],$row[STATUS_CONT],$row[HZ],$row[KD_COMODITY],$row[TGL_GATE_JS],$row[VESSEL],$row[VOYAGE_IN],$row[VOYAGE_OUT],$row[PEL_ASAL],$row[PEL_TUJ],$row[IMO_CLASS]);
		}
		$i++;
	}
	echo json_encode($responce);
}
?>