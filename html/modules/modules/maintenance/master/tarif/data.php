<?php
//require_lib ('mzphplib.php');
outputRaw();
//$branch_id = getLoginBranch();
$cari=strtolower($_GET['sSearch']);
$sWhere = " WHERE ID_CONT NOT NULL ";
$aColumns = array( '','ID_CONT', 'JENIS_BIAYA', 'TARIF', 'VAL', 'OI','START_PERIOD','END_PERIOD');

	 if ( $_GET['sSearch'] != "" ){
		 $sWhere .= " AND (";
		 $sWhere .= " lower(ID_CONT) LIKE '%".( $cari )."%' OR lower(JENIS_BIAYA) LIKE '%" .( $cari )."%' OR lower(VAL) LIKE '%" .( $cari )."%'";
		 $sWhere .= ')';
	 }
	 
	 
	 if ( isset( $_GET['iSortCol_0'] ) ){
		$sOrder = "ORDER BY  ";
		for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
		{
			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
			{
				$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
				 	". $_GET['sSortDir_'.$i] .", ";
			}
		}
		
		$sOrder = substr_replace( $sOrder, "", -2 );
		if ( $sOrder == "ORDER BY" ){
			$sOrder = "";
		}
	}

		

		$db 	= getDB();						
		$sql= " SELECT * FROM MASTER_TARIF_CONT
		$sWhere  $sOrder " ;
		
		print_r($sql);die();
		
		$count=$db->query($sql)->RecordCount();
		$rs =$db->selectLimit($sql,(int)$_GET['iDisplayStart'] ,(int)$_GET['iDisplayLength'] );
		
		if($count<=0){
			$dataTabels="{"."\"aaData\":[";
			$dataTabels .="]}";
			echo $dataTabels;
			exit;
		}
		$row=$rs->GetAll();
		$dataTabels="{"."\"sEcho\":".$_GET['sEcho'].",\"iTotalRecords\":$count".",\"iTotalDisplayRecords\":$count".",\"aaData\":[";
		
		if(!isset($_GET['iDisplayStart'])){
			$i=0;
		}else{
			$i=$_GET['iDisplayStart'];
		}
		foreach($row as $data){
		$i++;
		$edit= _link( array('sub'=>'edit', 'ORDER_ID'=>$data['ORDER_ID'] ) );
	
			$dataTabels .="[";
			$dataTabels .="\"".$i."\",";
			$dataTabels .="\"".$data['ID_CONT']."\",";
			$dataTabels .="\"".$data['JENIS_BIAYA'] ."\",";
			$dataTabels .="\"<b>".$data['TARIF']."</b>\",";
			$dataTabels .="\"".$data['VAL']."\",";
			$dataTabels .="\"".$data['OI']."\",";
			$dataTabels .="\"<b>".$data['START_PERIOD']."\",";
			$dataTabels .="\"<b>".$data['END_PERIOD']."\",";
			$dataTabels .="\""."<a href="<?=HOME?>maintenance.master.tarif/edit?id={$tarifs.ID_CONT}&jenis={$tarifs.JENIS_BIAYA}&val={$tarifs.VAL}&oi={$tarifs.OI}"><img src="<?=HOME?>images/edit.png" /></a> "."\"";
			
			$dataTabels .="],";
		}
						
		
		$dataTabels .="|||";
		$dataTabels=str_replace(",|||","",$dataTabels);
		$dataTabels .="]}";
		echo $dataTabels;

?>

