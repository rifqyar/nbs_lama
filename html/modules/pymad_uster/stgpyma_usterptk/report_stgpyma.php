<?php
	require_lib ('mzphplib.php');
		
	// $dstart  = $_GET['DSTART'];
	// $dfinish = $_GET['DFINISH'];
	// $whouse  = $_GET['WHOUSE_ID'];	
	// $vessel =  $_GET['VESSEL'];	
	// $voy =  $_GET['VOY'];
	// $kade =  $_GET['KADE'];
	// $pbm	= $_GET['PBM'];
	// $tr =  $_GET['TERMINAL'];
	
	$row 	= array();
	$db = getDB();
	// $row['DSTART'] 		= ValidDate ($dstart) ;
	// $row['DFINISH'] 	= ValidDate ($dfinish) ;
	// $row['WHOUSE_ID'] 	= addslashes ($whouse) ;	
	// $row['WHOUSE'] 		= getWHName ($whouse) ;
	// $row['TERMINAL'] 	= $tr;	

	// start rule migrasi
	// if ($dstart == '') {
		// header('location: '.HOME.'stgpyma_barangcab');
		// die();
	// }
	// else {
		// $start=$dstart;
		// if ($dfinish == ''){
			// $finish = date(d).'/'.date(m).'/'.date(Y);
		// }else{
			// $finish=$dfinish;
		// }
	// }
	// echo $start.'<br/>'.$finish;
	// die();
	// $arr_start = explode ( "/" , $start);
	// $arr_finish = explode ( "/" , $finish);
	// $int_start = $arr_start[2].$arr_start[1].$arr_start[0];
	// $int_finish = $arr_finish[2].$arr_finish[1].$arr_finish[0];
	// echo $int_start.'<br/>'.$int_finish;
	// die();
	// $query      = "select TO_CHAR(KAPAL_PROD.all_general_pkg.get_cutoff_date('BARANG', '01'),'RRRRMMDD') TGL from dual";
	// $result = $db->query($query);
	// $rowd   = $result->fetchRow();
	// $cutoff = $rowd['TGL'];
	// if ($int_start<$cutoff && $int_finish>=$cutoff){
		// header('location: '.HOME.'laphar/?error_date=1');
		// die();
	// }
	// $query  = "SELECT nota_name, trx_number, trx_date, customer_name, confirmation_status, '' AS amount, '' AS service_type, '' AS line_desc 
					// FROM KAPAL_CABANG.stg_pyma 
					// GROUP BY nota_name, trx_number, trx_date, customer_name, confirmation_status
					// ORDER BY trx_number, trx_date ASC";
	// $result = $db->query($query);
	// $rowd   = $result->fetchRow();
	// $subsidiary = $rowd['NAMA'];
	// end rule migrasi
	
	//untuk nilai tanggal
	// $now = ValidDate(date(j).'-'.date(m).'-'.date(y));
	// IF ($dstart != '') {
		// IF ($dfinish != '') {
			// //ECHO "INI"; EXIT ;
			// $row['TANGGAL'] = ValidDate ($dstart) . ' s/d ' . ValidDate ($dfinish) ; }
		// ELSE {
			// $row['TANGGAL'] = ValidDate ($dstart) . ' s/d ' . $now ;}
	// } ELSEIF ($dfinish != ''){
		// IF ($dstart != '') {
			// $row['TANGGAL'] = ValidDate ($dstart) . ' s/d ' . ValidDate ($dfinish) ; }
		// ELSE {
			// $row['TANGGAL'] = '1-Jan-1900 s/d ' . ValidDate ($dfinish) ;}
	// }
	
	//untuk cabang
	$cabang = GetLoginBranch ();
	
	// MASTER  & DETAIL	 
	$sql = "SELECT nota_name, trx_number, trx_date, customer_name, confirmation_status, sum(amount) AS amount, '' AS service_type, '' AS line_desc from 
			KAPAL_CABANG.stg_pyma 
			GROUP BY nota_name, trx_number, trx_date, customer_name, confirmation_status
			ORDER BY trx_number, trx_date ASC";
	
	// if ($dfinish != '' && $dfinish !='1-Jan-1900'){ 
		// $sql = $sql . " AND DORDER  <= '".ValidDate($dfinish)."' "; } 
	// if ($vessel != '') {  
		// $sql = $sql . " AND VESSEL_ID='$vessel' "; }
	// if ($voy != '') {  
		// $sql = $sql . " AND VOYAGE_NO='$voy' "; }
	// if ($kade != '') {  
		// $sql = $sql . " AND KADE='$kade' "; }
	// if ($pbm != '')
	// { 
			// $sql = $sql . " AND PBM_ID  = '".$pbm."' ";
			// //$sqlsum = $sqlsum . " AND PBM_ID  = '".$pbm."' ";  
	// }
	// $sql = $sql . " ORDER BY DORDER ASC ";
	
	//ECHO " SQL : " . $sql ; EXIT;	
			
	$rs 	= $db->query( $sql );
	$dtls 	= array();
	if ($rs && $rs->RecordCount()>0) {
		
		for ($__rowindex = 1 + $__offset; $dtl=$rs->FetchRow(); $__rowindex++) {
			$dtl["_no"] = $__rowindex;			
			$dtls[] = $dtl;
			
		}
		$rs->close();
	}
	//echo '<pre>'; print_r ( $dtls ); echo '</pre>'; die(); 
	outputRaw();
	$tl=xliteTemplate("report_stgpyma.html");
	$tl->assign('row', $row);
	$tl->assign('dtls', $dtls);
	// start rule migrasi
	// $tl->assign('subsidiary', $subsidiary);
	// end rule migrasi

	$tl->renderToScreen();
	
?>