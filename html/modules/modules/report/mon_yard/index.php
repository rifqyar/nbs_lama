<?php
 $tl = xliteTemplate('grid.htm');

 $db             = getDB();
                    $query_get_yard = "SELECT a.ID, a.NAME FROM YD_YARD_AREA b, YD_BLOCKING_AREA a WHERE a.ID_YARD_AREA = b.ID AND b.STATUS = 'AKTIF' AND a.NAME <> 'NULL'";
                    $result_yard    = $db->query($query_get_yard);
                    $row_yard       = $result_yard->getAll();
                    foreach ($row_yard as $row) {
					
		$dua .= "<option value='".$row['ID']."'>".$row['NAME']."</option>";
		//$i++;
	}
	
	
	$tiga  			= "</select>";
 
	$satu			= "<select name='blok' id='blok'>
		                <option value=''>--- PILIH ---</option>".$dua."</select>";
						
$tl->assign("blok",$satu);
 $tl->renderToScreen();
?>

