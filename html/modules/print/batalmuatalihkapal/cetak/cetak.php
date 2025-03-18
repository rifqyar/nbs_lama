<?php

$db			= getDB();
$no_req		= $_GET["no_req"];
$tl = xliteTemplate('print.htm');


	/* $query_nota	= " SELECT a.*, b.*, c.*, to_char(to_date(d.clossing_time,'rrrrmmddhh24miss'),'dd/mm/rrrr hh24:mi:ss') closing_time, D.ID_POD
                  FROM TB_BATALMUAT_H a, TB_BATALMUAT_D b, NOTA_BATALMUAT_H c, M_VSB_VOYAGE@dbint_link D WHERE a.ID_BATALMUAT = b.ID_BATALMUAT 
                  AND trim(A.ID_BATALMUAT)=trim(C.ID_BATALMUAT) AND D.vessel(+) = A.vessel AND D.VOYAGE_IN(+) = a.VOYAGE  AND a.ID_BATALMUAT = '$no_req'"; */
			
	/*perbaikain alfin 24 Maret 2016
	$query_nota	= "	SELECT a.*,
                       b.*,
                       c.*,
                       TO_CHAR (TO_DATE (d.clossing_time, 'rrrrmmddhh24miss'),
                                'dd/mm/rrrr hh24:mi:ss')
                          closing_time,
                       D.ID_POD,
                       E.POD AS PODES,
                       (SELECT g.VESSEL || ' / ' || g.VOYAGE_IN
                          FROM REQ_BATALMUAT_D f, M_VSB_VOYAGE@dbint_link g
                         WHERE F.NO_UKK = g.ID_VSB_VOYAGE AND f.NO_CONTAINER = b.NO_CONTAINER)
                          EX_KAPAL
                  FROM REQ_BATALMUAT_H a,
                       REQ_BATALMUAT_D b,
                       NOTA_BATALMUAT_H c,
                       M_VSB_VOYAGE@dbint_link D,
                       M_CYC_CONTAINER@dbint_link E
                 WHERE     a.ID_REQ = b.ID_REQ
                       AND TRIM (A.ID_REQ) = TRIM (C.ID_REQ)
                       AND D.vessel(+) = A.vessel
                       AND D.VOYAGE_IN(+) = a.VOYAGE_IN
                       AND a.ID_REQ = '$no_req'
                       AND E.NO_CONTAINER = B.NO_CONTAINER
                       AND E.VESSEL = D.vessel
                       AND E.VOYAGE_IN = d.voyage_in
					";
*/

$query_nota	= "	SELECT a.*,
                       b.*,
                       c.*,
                       TO_CHAR (TO_DATE (d.clossing_time, 'rrrrmmddhh24miss'),
                                'dd/mm/rrrr hh24:mi:ss')
                          closing_time,
                       D.ID_POD,
                       E.POD AS PODES,
                       (SELECT g.VESSEL || ' / ' || g.VOYAGE_IN
                          FROM M_VSB_VOYAGE@dbint_link g
                         WHERE b.NO_UKK = g.ID_VSB_VOYAGE)
                          EX_KAPAL
                  FROM REQ_BATALMUAT_H a,
                       REQ_BATALMUAT_D b,
                       NOTA_BATALMUAT_H c,
                       M_VSB_VOYAGE@dbint_link D,
                       M_CYC_CONTAINER@dbint_link E
                 WHERE     a.ID_REQ = b.ID_REQ
                       AND TRIM (A.ID_REQ) = TRIM (C.ID_REQ)
                       AND D.vessel(+) = A.vessel
                       AND D.VOYAGE_IN(+) = a.VOYAGE_IN
                       AND a.ID_REQ = '$no_req'
                       AND E.NO_CONTAINER = B.NO_CONTAINER
                       AND E.VESSEL = D.vessel
                       AND E.VOYAGE_IN = d.voyage_in
					";					

	$result_nota	= $db->query($query_nota);
	$row			= $result_nota->getAll();
			
	$i = 1;


	$tl->assign('data',$row);

$tl->renderToScreen();
	?>