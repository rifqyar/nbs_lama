<?php
 $tl = xliteTemplate('home.htm');
 $db=  getDB();
 
 $query			="   SELECT a.ID_REQ,
                         b.NAMA EMKL,
                         a.VESSEL,
                         a.VOYAGE,
                         NVL((SELECT COUNT (c.NO_CONTAINER)JUMLAH_CONT FROM tb_req_receiving_d c WHERE c.ID_REQ = a.ID_REQ),0) JUMLAH_CONT
                    FROM tb_req_receiving_h a, master_pbm b
                   WHERE a.KODE_PBM = b.KODE_PBM
                GROUP BY a.ID_REQ,
                         b.NAMA,
                         a.VESSEL,
                         a.VOYAGE
                         ";
 $result_q		=$db->query($query);
 $row			=$result_q->getAll();
 
 
 $tl->assign("table",$row);
 $tl->renderToScreen();
?>