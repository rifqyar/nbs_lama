<?php
 $tl = xliteTemplate('list_req.htm');
 $db = getDB();
 $keyword = $_POST['KEYWORD'];
 $cari = $_POST['CARI'];
 
 if( $cari == "cari" )
 {
	$row = $db->query("SELECT A.ID_REQ, 
                           A.NO_UPER_BM,
                           C.NAMA,
                           B.NAMA_VESSEL,
                           A.VOYAGE,
                           A.STATUS,
						   A.REMARK 
						   FROM GLC_REQUEST A, MASTER_VESSEL B, MASTER_PBM C
                           WHERE A.KODE_KAPAL = B.KODE_KAPAL
                           AND A.KODE_PBM = C.KODE_PBM
						   AND A.ID_REQ LIKE '%$keyword%'
						   AND ROWNUM<=20"
						   )->getAll();
  }
  else
  {
	$row = $db->query("SELECT A.ID_REQ, 
                           A.NO_UPER_BM,
                           C.NAMA,
                           B.NAMA_VESSEL,
                           A.VOYAGE,
                           A.STATUS,
						   A.REMARK 
						   FROM GLC_REQUEST A, MASTER_VESSEL B, MASTER_PBM C
                           WHERE A.KODE_KAPAL = B.KODE_KAPAL
                           AND A.KODE_PBM = C.KODE_PBM
						   AND ROWNUM<=20
						   ORDER BY A.ID_HEADER DESC"
						   )->getAll();
  }
						   
 //print_r($row);die;
 $n=0;
 foreach($row as $b)
 {
	 $id_req[$n] = $b['ID_REQ'];	 
	 $row2[$n] = $db->query("SELECT DISTINCT ID_ALAT FROM GLC_DETAIL_EST_SHIFT WHERE ID_REQ = '$id_req[$n]' ORDER BY ID_ALAT")->getAll();
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
		$glcs[$n] = "no";	
	}
    //print_r($glcs);die;	
    $n++;
 } 
 
 $tl->assign('req',$row);
 $tl->assign('glc',$glcs);
 $tl->assign("HOME",HOME);
 $tl->assign("APPID",APPID);
 $tl->renderToScreen();
?>