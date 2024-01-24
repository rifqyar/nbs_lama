<?php
 $tl = xliteTemplate('home_time_sheet.htm');
 $db = getDB();
 $row = $db->query("SELECT A.ID_REQ, 
                           A.NO_UPER_BM,
                           C.NAMA,
                           B.NAMA_VESSEL,
                           A.VOYAGE,
                           A.STATUS FROM GLC_REQUEST A, MASTER_VESSEL B, MASTER_PBM C
                           WHERE A.KODE_KAPAL = B.KODE_KAPAL
                           AND A.KODE_PBM = C.KODE_PBM")->getAll();
						   
 $n=0;
 foreach($row as $b)
 {
	 $id_req[$n] = $b['ID_REQ'];	 
	 $row2[$n] = $db->query("SELECT DISTINCT ID_ALAT FROM GLC_DETAIL_EST_SHIFT WHERE ID_REQ = '$id_req[$n]'")->getAll();
     //print_r($row2);die;
	
	if($row2[$n] != NULL)
	{
		 $i=0;
		 foreach($row2[$n] as $r)
		 {
			 $a[$i]=$r['ID_ALAT'];
			 $i++;
		 }
	
    	$glcs[$n] = implode(", ",$a);
	}
	else
	{
		$glcs[$n] = "-";	
	}
	
    //print_r($glcs);die;	
    $n++;
 } 
 
 //print_r($row2);die;
 //print_r($glcs);die;
 $tl->assign('req',$row);
 $tl->assign('glc',$glcs);
 $tl->renderToScreen();
?>

