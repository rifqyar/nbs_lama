<?php
	$q = $_GET['q'];
	$no_ukk = $_GET['no_ukk'];

	$id_group = $_SESSION["ID_GROUP"];
	$page = isset($_POST['page'])?$_POST['page']:1;  // get the requested page
	$limit = isset($_POST['rows'])?$_POST['rows']:10; // get how many rows we want to have into the grid
	$sidx = isset($_POST['sidx'])?$_POST['sidx']:'id_bprp'; // get index row - i.e. user click to sort
		
	if(!$sidx) $sidx =1;
	
	$db = getDB();
	
	$count = 0;
	
	$query ="SELECT COUNT (*)
		FROM CONFIRM_DISC A
       INNER JOIN MASTER_ALAT B
          ON A.ALAT = B.ID_ALAT
       INNER JOIN ISWS_LIST_CONTAINER C
          ON A.NO_CONTAINER = C.NO_CONTAINER AND A.NO_UKK = C.NO_UKK
       INNER JOIN RBM_H D
          ON A.NO_UKK = D.NO_UKK
             AND A.ID_DISC IN (  SELECT MAX (ID_DISC)
                                   FROM CONFIRM_DISC A
                               GROUP BY ALAT)";	
	$res = $db->query($query)->fetchRow();
	$count = $res[NUMBER_OF_ROWS];
	
	$query ="SELECT COUNT (*)
  FROM CONFIRM_LOAD A
       INNER JOIN MASTER_ALAT B
          ON A.ALAT = B.ID_ALAT
       INNER JOIN ISWS_LIST_CONTAINER C
          ON A.NO_CONTAINER = C.NO_CONTAINER AND A.NO_UKK = C.NO_UKK
       INNER JOIN RBM_H D
          ON A.NO_UKK = D.NO_UKK
             AND A.ID_LOAD IN (  SELECT MAX (ID_LOAD)
                                   FROM CONFIRM_LOAD A
                               GROUP BY ALAT)";	
	$res = $db->query($query)->fetchRow();
	$count = $count + $res[NUMBER_OF_ROWS];
	
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

		$query="SELECT B.NAMA_ALAT,
       'DISCHARGE' AS STATUS,
       to_char(A.DATE_CONFIRM,'DD-MM-YYYY HH24:MI') DATE_CONFIRM,
       to_char(A.DATE_CONFIRM,'YYYYMMDD') as DATESTRING,
       C.BERAT,
       D.NM_KAPAL,
       ('[' || D.VOYAGE_IN || '-' || D.VOYAGE_OUT || ']') AS VOYAGE,
       A.NO_CONTAINER
  FROM CONFIRM_DISC A
       INNER JOIN MASTER_ALAT B
          ON A.ALAT = B.ID_ALAT
       INNER JOIN ISWS_LIST_CONTAINER C
          ON A.NO_CONTAINER = C.NO_CONTAINER AND A.NO_UKK = C.NO_UKK
       INNER JOIN RBM_H D
          ON A.NO_UKK = D.NO_UKK
             AND A.ID_DISC IN (  SELECT MAX (ID_DISC)
                                   FROM CONFIRM_DISC A
                               GROUP BY ALAT)
UNION
SELECT B.NAMA_ALAT,
       'LOAD' AS STATUS,
       to_char(A.DATE_CONFIRM,'DD-MM-YYYY HH24:MI') DATE_CONFIRM,
       to_char(A.DATE_CONFIRM,'YYYYMMDD') as DATESTRING,
       C.BERAT,
       D.NM_KAPAL,
       ('[' || D.VOYAGE_IN || '-' || D.VOYAGE_OUT || ']') AS VOYAGE,
       A.NO_CONTAINER
  FROM CONFIRM_LOAD A
       INNER JOIN MASTER_ALAT B
          ON A.ALAT = B.ID_ALAT
       INNER JOIN ISWS_LIST_CONTAINER C
          ON A.NO_CONTAINER = C.NO_CONTAINER AND A.NO_UKK = C.NO_UKK
       INNER JOIN RBM_H D
          ON A.NO_UKK = D.NO_UKK
             AND A.ID_LOAD IN (  SELECT MAX (ID_LOAD)
                                   FROM CONFIRM_LOAD A
                               GROUP BY ALAT)";	
	
	$res = $db->query($query);
	
	$arrIDCrane;
	$arrDateCrane;
	while ($row = $res->fetchRow()) {
			if (in_array($row[NAMA_ALAT], $arrIDCrane)) {
				$key = array_search($row[NAMA_ALAT], $arrIDCrane);
				if($arrDateCrane[$key] < $row[DATESTRING]){
					$arrDateCrane[$key]= $row[DATESTRING];
					$responce->rows[$key]['id']=$row[NAMA_ALAT];
					$responce->rows[$key]['cell']=array($row[NAMA_ALAT],$row[STATUS],$row[DATE_CONFIRM], $row[BERAT],$row[NM_KAPAL],$row[VOYAGE],$row[NO_CONTAINER]);
				}
			} else {
				$arrIDCrane[$i]=$row[NAMA_ALAT];
				$arrDateCrane[$i]=$row[DATESTRING];
				$responce->rows[$i]['id']=$row[NAMA_ALAT];
				$responce->rows[$i]['cell']=array($row[NAMA_ALAT],$row[STATUS],$row[DATE_CONFIRM], $row[BERAT],$row[NM_KAPAL],$row[VOYAGE],$row[NO_CONTAINER]);
				$i++;
			}
	}
	echo json_encode($responce);
?>