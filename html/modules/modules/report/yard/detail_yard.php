<?php
 $tl = xliteTemplate('detail_yard.htm');
 $db = getDB(); 
 
 $kategori = $_POST['kategori'];
 $id_vessel = $_POST['id_vessel'];
  $id_blok = $_POST['id_blok'];
  $id_book = $_POST['id_book'];

	if ($kategori == 0){
		if ($id_blok == NULL){
		// kategori pake yard
		//echo "SELECT CONCAT('BLOK ',a.NAME) NAME, 0 KAPASITAS, 0 ALOKASI, 0 NOT_ALOCATE FROM YD_BLOCKING_AREA a, YD_YARD_AREA b WHERE a.ID_YARD_AREA = b.ID AND b.STATUS = 'AKTIF'";die;
		 $query = "SELECT ID_BLOK, NAMA_BLOK, TOTAL_KAPASITAS, NVL(SUM(JML_TERALOKASI),0) TOTAL_ALOKASI, (TOTAL_KAPASITAS-SUM(JML_TERALOKASI)) BLM_TERALOKASI, SUM(JML_PLACEMENT) TOTAL_PLACEMENT, SUM(JML_CETAK_JOBSLIP) TOTAL_CETAK_JOBSLIP FROM REPORT_YARD
					WHERE TGL_UPDATE = (SELECT(MAX(TGL_UPDATE)) FROM REPORT_YARD) GROUP BY ID_BLOK, NAMA_BLOK, TOTAL_KAPASITAS";
		 $result = $db->query($query);
		 $row = $result->getAll();
		 $query2 = "SELECT SUM(TOTAL_KAPASITAS) GRAND_KAPASITAS, NVL(SUM(JML_TERALOKASI),0) GRAND_TOTAL_ALOKASI, (SUM(TOTAL_KAPASITAS)-SUM(JML_TERALOKASI)) GRAND_BLM_TERALOKASI, SUM(JML_PLACEMENT) GRAND_PLACEMENT, SUM(JML_CETAK_JOBSLIP) GRAND_TOTAL_CETAK_JOBSLIP FROM REPORT_YARD
                    WHERE TGL_UPDATE = (SELECT(MAX(TGL_UPDATE)) FROM REPORT_YARD)";
		 $result2 = $db->query($query2);
		 $row2 = $result2->getAll();
		 
		 } else {
			$query = "SELECT ID_BLOK, NAMA_BLOK, TOTAL_KAPASITAS, NVL(SUM(JML_TERALOKASI),0) TOTAL_ALOKASI, (TOTAL_KAPASITAS-SUM(JML_TERALOKASI)) BLM_TERALOKASI, SUM(JML_PLACEMENT) TOTAL_PLACEMENT, SUM(JML_CETAK_JOBSLIP) TOTAL_CETAK_JOBSLIP FROM REPORT_YARD
					WHERE TGL_UPDATE = (SELECT(MAX(TGL_UPDATE)) FROM REPORT_YARD) AND ID_BLOK = '$id_blok' GROUP BY ID_BLOK, NAMA_BLOK, TOTAL_KAPASITAS";
		 $result = $db->query($query);
		 $row = $result->getAll();
		 $query2 = "SELECT SUM(TOTAL_KAPASITAS) GRAND_KAPASITAS, NVL(SUM(JML_TERALOKASI),0) GRAND_TOTAL_ALOKASI, (SUM(TOTAL_KAPASITAS)-SUM(JML_TERALOKASI)) GRAND_BLM_TERALOKASI, SUM(JML_PLACEMENT) GRAND_PLACEMENT, SUM(JML_CETAK_JOBSLIP) GRAND_TOTAL_CETAK_JOBSLIP FROM REPORT_YARD
                    WHERE TGL_UPDATE = (SELECT(MAX(TGL_UPDATE)) FROM REPORT_YARD)";
		 $result2 = $db->query($query2);
		 $row2 = $result2->getAll();
		 }
	} else {
		// kategori pake vessel
		//echo "SELECT CONCAT('BLOK ',a.NAME) NAME, 0 KAPASITAS, 0 ALOKASI, 0 NOT_ALOCATE FROM YD_BLOCKING_AREA a, YD_YARD_AREA b WHERE a.ID_YARD_AREA = b.ID AND b.STATUS = '$id_vessel'";die;
		 
		 /*$query3 = "SELECT CONCAT(CONCAT(CONCAT('REPORT YARD UNTUK KAPAL ',a.NAMA_VESSEL),' VOY '),b.VOYAGE) TITLE FROM VESSEL_SCHEDULE b, MASTER_VESSEL a WHERE
          b.ID_VES = a.KODE_KAPAL AND b.ID_VS = '$id_vessel'";
		 $result3 = $db->query($query3);
		 $row3 = $result3->getAll();
		 $data = $row3['TITLE'];*/
		 
		 $query = "SELECT ID_BOOK, SIZE_, TIPE, STATUS, KATEGORI FROM REPORT_YARD_VESSEL WHERE ID_VS = '$id_vessel' AND
		 TGL_UPDATE = (SELECT(MAX(TGL_UPDATE)) FROM REPORT_YARD)
		 GROUP BY ID_BOOK, SIZE_, TIPE, STATUS, KATEGORI
		 ";
		 $result = $db->query($query);
		 $row = $result->getAll();
	}
	$tl->assign('tes',$tes);
 $tl->assign('kategori',$kategori);
 $tl->assign('header',$row);
 $tl->assign('id_book',$id_book);
  $tl->assign('id_blok',$id_blok);
  $tl->assign('grand',$row2);
   $tl->assign('vessel',$id_vessel);
 $tl->renderToScreen();
?>

