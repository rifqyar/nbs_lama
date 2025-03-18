 <?php
 $tl =  xliteTemplate('layout_lapangan.htm');
 
        $db	= getDB();
        
        $kategori       = $_POST['kategori'];
        $id_kategori    = $_POST['id_kategori'];
        $id_kategori2   = $_POST['id_kategori2'];
        
                                        
        $query_yard_area = "SELECT * FROM YD_YARD_AREA WHERE STATUS = 'AKTIF'";
        $result          = $db->query($query_yard_area);
        $yard_area       = $result->fetchRow();
        $yard_id         = $yard_area['ID'];
        $width           = $yard_area['WIDTH'];
        
                                 
        $query_yard_cell = "SELECT a.INDEX_CELL, b.COLOR DAMA, b.NAME, a.SLOT_, a.ROW_ FROM YD_BLOCKING_CELL a, YD_BLOCKING_AREA b WHERE a.ID_BLOCKING_AREA = b.ID AND b.ID_YARD_AREA = '$yard_id' ORDER BY a.INDEX_CELL ASC ";
        $result_         = $db->query($query_yard_cell);
        $index_cell      = $result_->getAll();

        if (isset($kategori)){
        if ($_POST['kategori'] == 'kapal'){
									$query_get_r_t		 = "SELECT ID_CELL FROM YD_PLACEMENT_YARD WHERE ID_VS = '$id_kategori' ORDER BY ID_CELL ASC";
									$res_r_t	         = $db->query($query_get_r_t);
									$row_count_		 = $res_r_t->getAll();
									
									
								} else if ($_POST['kategori'] == 'consignee'){
									$query_get_r_t		 = "SELECT ID_CELL FROM YD_PLACEMENT_YARD WHERE KODE_PBM = '$id_kategori2' ORDER BY ID_CELL ASC";
									$res_r_t	         = $db->query($query_get_r_t);
									$row_count_		 = $res_r_t->getAll();
										
									
								} else if ($_POST['kategori'] == 'size'){
									$kate	= $_POST['kategori'];
									$query_get_r_t		 = "SELECT ID_CELL FROM YD_PLACEMENT_YARD WHERE SIZE_ = '$kategori' ORDER BY ID_CELL ASC";
									$res_r_t	         = $db->query($query_get_r_t);
									$row_count_		 = $res_r_t->getAll();
							}}

        $tl->assign("kategori",$kategori);
        $tl->assign("row_cont",$row_count_);
        $tl->assign("width",$width);
        $tl->assign("index_cell",$index_cell);
	//$tl->assign("yard_area",$yard_area);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
                                                ?>