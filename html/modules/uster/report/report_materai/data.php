<?php



	
	
	$db = getDB("storage");

		$query ="";

    if(isset($_GET['id_req']) && $_GET['id_req'] != NULL){
        $idreq = $_GET['id_req'];
        $query .= "SELECT a.no_peraturan AS NO_PERATURAN,a.NOMINAL AS NOMINAL,a.tgl_peraturan AS TGL_PERATURAN ,a.TERPAKAI,a.SALDO from master_materai a where NO_PERATURAN IS NOT NULL AND NO_PERATURAN = '$idreq'";
    }    
	
	else if(isset($_GET['id_time']) && isset($_GET['id_time']) && $_GET['id_time2'] == NULL && $_GET['id_time'] != NULL ){
        $idtime = $_GET['id_time'];
        $query .= "SELECT a.no_peraturan AS NO_PERATURAN,a.NOMINAL AS NOMINAL,a.tgl_peraturan AS TGL_PERATURAN ,a.TERPAKAI,a.SALDO from master_materai a where NO_PERATURAN IS NOT NULL AND TO_CHAR(TGL_PERATURAN,'YYYY-MM-DD') = '$idtime'";
    }else if(isset($_GET['id_time2']) && isset($_GET['id_time']) && $_GET['id_time'] != NULL && $_GET['id_time2'] != NULL){
        $idtime = $_GET['id_time'];
        $idtime2 = $_GET['id_time2'];
        $query .= "SELECT a.no_peraturan AS NO_PERATURAN,a.NOMINAL AS NOMINAL,a.tgl_peraturan AS TGL_PERATURAN ,a.TERPAKAI,a.SALDO from master_materai a where NO_PERATURAN IS NOT NULL AND A.TGL_PERATURAN BETWEEN TO_DATE('$idtime','YYYY-MM-DD') AND TO_DATE('$idtime2','YYYY-MM-DD') ";
        //print_r($query);die();
    }else{
    	$query ="SELECT a.no_peraturan AS NO_PERATURAN,a.NOMINAL AS NOMINAL,a.tgl_peraturan AS TGL_PERATURAN ,a.TERPAKAI,a.SALDO from master_materai a order by id desc";
    	
    }


	
	$res = $db->query($query)->fetchRow();
	$count = $res[NUMBER_OF_ROWS];

	
	

	if( $count >0 ) 
	{
		$total_pages = ceil($count/$limit);
	}
	else 
	{ 
		$total_pages = 0;
	}
	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit; // do not put $limit*($page - 1)	

	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	
		// $query="SELECT * FROM (
  //   SELECT A.NO_REQUEST, A.KREDIT, A.NO_NOTA_MTI  || '<br>' || A.TGL_SIMPAN AS NO_NOTA_MTI, A.NO_FAKTUR_MTI || '<br>' || A.TGL_SIMPAN AS NO_FAKTUR_MTI, A.TGL_SIMPAN, A.SP_MTI,B.NM_PBM || '<br>' || B.NO_NPWP_PBM AS NM_PBM, RANK() OVER (ORDER BY TGL_SIMPAN DESC , ROWNUM DESC )  r
  //   FROM ITPK_NOTA_HEADER A INNER JOIN  MST_PELANGGAN B ON A.CUSTOMER_NUMBER = B.NO_ACCOUNT_PBM WHERE A.STATUS <> '5' ORDER BY A.TGL_SIMPAN DESC
  //   )  WHERE r < 500";
					
	// if(isset($_GET['id_req']) && $_GET['id_req'] != NULL){
 //        $idreq = $_GET['id_req'];
 //        $query .= " WHERE NO_PERATURAN = '$idreq'";
 //    }    
	
	// if(isset($_GET['id_time']) && $_GET['id_time'] != NULL){
 //        $idtime = $_GET['id_time'];
 //        $query .= " WHERE TO_CHAR(TGL_PERATURAN,'DD-MM-YYYY') = '$idtime'";
 //    }
	
	$res = $db->query($query);
	
	while ($row = $res->fetchRow()) 
	{
			
			$deposit = "Rp. ".number_format($row[NOMINAL]);
			$terpakai = "Rp. ".number_format($row[TERPAKAI]);
			$saldo = "Rp. ".number_format($row[SALDO]);
			
			
			$responce->rows[$i]['id']=$row[NO_NOTA];
			$responce->rows[$i]['cell']=array($row[NO_PERATURAN],$deposit,$row[TGL_PERATURAN],$terpakai,$saldo);
		
			
		$i++;
	}
	echo json_encode($responce);
	die();

?>