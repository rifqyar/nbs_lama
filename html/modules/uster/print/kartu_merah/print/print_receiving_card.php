<?php
$tl =  xliteTemplate('print_pontianak_new.htm');

$db			= getDB("storage");
//$no_nota	= $_GET["no_nota"];
$no_req		= $_GET["no_req"];

$query_nota   = "SELECT LUNAS FROM NOTA_RECEIVING WHERE NO_REQUEST = '$no_req' ORDER BY NO_NOTA DESC";
$result_nota  = $db->query($query_nota);
$row_nota   = $result_nota->fetchRow();

if ($row_nota[LUNAS] !='YES') {
    echo "NOTA BELUM LUNAS";
    die();
}

$query_update_kartu = "UPDATE REQUEST_RECEIVING
					SET CETAK_KARTU = CETAK_KARTU +1
				WHERE NO_REQUEST = '$no_req'
			 ";
$db->query($query_update_kartu);
$query_rec_dari = "SELECT RECEIVING_DARI
				FROM REQUEST_RECEIVING
				WHERE NO_REQUEST = '$no_req'
			 ";

$result_rec_dari = $db->query($query_rec_dari);
$row_rec_dari = $result_rec_dari->fetchRow();
$rec_dari = $row_rec_dari["RECEIVING_DARI"];
//echo $row_rec_dari;die;

if($rec_dari=="LUAR")
{
	/* $query_nota	= "SELECT a.NO_NOTA AS NO_NOTA,
       TO_CHAR(a.TGL_NOTA, 'DD-MM-YYYY') AS TGL_NOTA,
       c.NM_PBM AS EMKL,
       a.NO_REQUEST AS NO_REQUEST,
       d.NO_CONTAINER AS NO_CONTAINER,
       e.SIZE_ AS SIZE_,
       e.TYPE_ AS TYPE_,
       d.STATUS,
       b.CETAK_KARTU AS CETAK_KARTU,
       f.NAMA_YARD AS NAMA_YARD,
       V_PKK_CONT.NM_KAPAL,
       V_PKK_CONT.VOYAGE_IN,
       V_PKK_CONT.VOYAGE_OUT
  FROM                NOTA_RECEIVING a
                   INNER JOIN
                      REQUEST_RECEIVING b
                   ON a.NO_REQUEST = b.NO_REQUEST
                JOIN
                   V_MST_PBM c
                ON b.KD_CONSIGNEE = c.KD_PBM
             JOIN
                CONTAINER_RECEIVING d
             ON b.NO_REQUEST = d.NO_REQUEST
          JOIN
             MASTER_CONTAINER e
          ON d.NO_CONTAINER = e.NO_CONTAINER
       JOIN
          YARD_AREA f
       ON d.DEPO_TUJUAN = f.ID,
       PETIKEMAS_CABANG.TTM_BP_CONT TTM_BP_CONT,
       PETIKEMAS_CABANG.TTD_BP_CONT TTD_BP_CONT,
       PETIKEMAS_CABANG.V_PKK_CONT V_PKK_CONT,
       PETIKEMAS_CABANG.TTD_BP_CONFIRM TTD_BP_CONFIRM
 WHERE     TTM_BP_CONT.BP_ID = TTD_BP_CONT.BP_ID
       AND TTM_BP_CONT.NO_UKK = V_PKK_CONT.NO_UKK
       AND TTD_BP_CONT.CONT_NO_BP = TTD_BP_CONFIRM.CONT_NO_BP
       AND TTM_BP_CONT.NO_UKK = TTD_BP_CONFIRM.NO_UKK
       AND TTD_BP_CONT.BP_ID = TTD_BP_CONFIRM.BP_ID
       AND TTM_BP_CONT.KD_CABANG = '05'
       AND TTD_BP_CONT.CONT_NO_BP = D.NO_CONTAINER
       AND a.NO_REQUEST = '$no_req'
						
				
					   "; */
					   
	/* $query_nota  = " SELECT a.NO_NOTA AS NO_NOTA,
       TO_CHAR(a.TGL_NOTA, 'DD-MM-YYYY') AS TGL_NOTA,
       c.NM_PBM AS EMKL,
       a.NO_REQUEST AS NO_REQUEST,
       d.NO_CONTAINER AS NO_CONTAINER,
       e.SIZE_ AS SIZE_,
       e.TYPE_ AS TYPE_,
       d.STATUS,
       b.CETAK_KARTU AS CETAK_KARTU,
	   b.RECEIVING_DARI,
       f.NAMA_YARD AS NAMA_YARD,
       V_PKK_CONT.NM_KAPAL,
       V_PKK_CONT.VOYAGE_IN,
       V_PKK_CONT.VOYAGE_OUT
  FROM                NOTA_RECEIVING a
                   INNER JOIN
                      REQUEST_RECEIVING b
                   ON a.NO_REQUEST = b.NO_REQUEST
                JOIN
                   V_MST_PBM c
                ON b.KD_CONSIGNEE = c.KD_PBM
             JOIN
                CONTAINER_RECEIVING d
             ON b.NO_REQUEST = d.NO_REQUEST
          JOIN
             MASTER_CONTAINER e
          ON d.NO_CONTAINER = e.NO_CONTAINER
       JOIN
          YARD_AREA f
       ON d.DEPO_TUJUAN = f.ID,
       PETIKEMAS_CABANG.TTM_BP_CONT TTM_BP_CONT,
       PETIKEMAS_CABANG.TTD_BP_CONT TTD_BP_CONT,
       PETIKEMAS_CABANG.V_PKK_CONT V_PKK_CONT,
       PETIKEMAS_CABANG.TTD_BP_CONFIRM TTD_BP_CONFIRM
 WHERE     TTM_BP_CONT.BP_ID = TTD_BP_CONT.BP_ID
       AND TTM_BP_CONT.NO_UKK = V_PKK_CONT.NO_UKK
       AND TTD_BP_CONT.CONT_NO_BP = TTD_BP_CONFIRM.CONT_NO_BP
       AND TTM_BP_CONT.NO_UKK = TTD_BP_CONFIRM.NO_UKK
       AND TTD_BP_CONT.BP_ID = TTD_BP_CONFIRM.BP_ID
       AND TTM_BP_CONT.KD_CABANG = '05'
	   AND TTD_BP_CONT.STATUS_CONT = '04U'
       AND TTD_BP_CONT.CONT_NO_BP = D.NO_CONTAINER
	   and a.TGL_NOTA = (SELECT MAX(e.TGL_NOTA) FROM NOTA_RECEIVING e WHERE e.NO_REQUEST = b.NO_REQUEST)
       AND a.NO_REQUEST = '$no_req'"; */
	   
	 $query_nota =  "SELECT NOTA_RECEIVING.NO_NOTA,
                   TO_CHAR (NOTA_RECEIVING.TGL_NOTA, 'DD-MM-YYYY') TGL_NOTA,
                   V_MST_PBM.NM_PBM EMKL,
                   REQ.NO_REQUEST,
                   CONTAINER_RECEIVING.NO_CONTAINER,
                   MASTER_CONTAINER.SIZE_,
                   MASTER_CONTAINER.TYPE_,
                   CONTAINER_RECEIVING.STATUS STATUS,
                   REQ.CETAK_KARTU,
                   REQ.RECEIVING_DARI,
                   YARD_AREA.NAMA_YARD,
                   MASTER_CONTAINER.NO_BOOKING NM_KAPAL,
                   CASE
                      WHEN CONTAINER_RECEIVING.VIA ='darat' THEN 'DARAT'
                      ELSE 'TONGKANG'
                   END
                      VIA,
                   CASE WHEN MASTER_CONTAINER.MLO = 'MLO' THEN '03' ELSE '02' END AREA_,
                   CONTAINER_RECEIVING.EX_KAPAL
              FROM NOTA_RECEIVING
                   INNER JOIN REQUEST_RECEIVING REQ
                      ON NOTA_RECEIVING.NO_REQUEST = REQ.NO_REQUEST
                   INNER JOIN CONTAINER_RECEIVING
                      ON REQ.NO_REQUEST = CONTAINER_RECEIVING.NO_REQUEST
                   INNER JOIN MASTER_CONTAINER
                      ON CONTAINER_RECEIVING.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
                   LEFT JOIN  V_MST_PBM V_MST_PBM
                   ON REQ.KD_CONSIGNEE = V_MST_PBM.KD_PBM
                         AND V_MST_PBM.kd_cabang = '05'
                   LEFT JOIN YARD_AREA
                      ON CONTAINER_RECEIVING.DEPO_TUJUAN = YARD_AREA.ID
             WHERE NOTA_RECEIVING.TGL_NOTA = (SELECT MAX (NOTA.TGL_NOTA)
                                                FROM NOTA_RECEIVING NOTA
                                               WHERE NOTA.NO_REQUEST = REQ.NO_REQUEST)
                   AND REQ.NO_REQUEST = '$no_req'";

	$result_nota	= $db->query($query_nota);
	$row			= $result_nota->getAll();
}

else if($rec_dari=="TPK"){
	$query_nota	= "SELECT b.NM_PBM AS EMKL,
                              a.NO_REQUEST AS NO_REQUEST,
							  a.RECEIVING_DARI,
                              c.NO_CONTAINER AS NO_CONTAINER,
                              d.SIZE_ AS SIZE_,
                              d.TYPE_ AS TYPE_,
                              c.STATUS AS STATUS,
                              c.BLOK_TPK AS BLOK_TPK,
                              c.SLOT_TPK AS SLOT_TPK,
                              c.ROW_TPK AS ROW_TPK,
                              c.TIER_TPK AS TIER_TPK,
                              a.CETAK_KARTU AS CETAK_KARTU,
                              f.NAMA_YARD AS NAMA_YARD,
                              V_PKK_CONT.NM_KAPAL,
                              V_PKK_CONT.VOYAGE_IN,
                              V_PKK_CONT.VOYAGE_OUT
                       FROM REQUEST_RECEIVING a 
                                INNER JOIN 
                                V_MST_PBM b ON a.KD_CONSIGNEE = b.KD_PBM 
                                JOIN
                                CONTAINER_RECEIVING c ON  a.NO_REQUEST = c.NO_REQUEST
                                 JOIN
                                MASTER_CONTAINER d ON c.NO_CONTAINER = d.NO_CONTAINER
                                JOIN
                                YARD_AREA f ON c.DEPO_TUJUAN = f.ID ,
                                PETIKEMAS_CABANG.TTM_BP_CONT TTM_BP_CONT,PETIKEMAS_CABANG.TTD_BP_CONT TTD_BP_CONT,   
                                PETIKEMAS_CABANG.V_PKK_CONT V_PKK_CONT,PETIKEMAS_CABANG.TTD_BP_CONFIRM TTD_BP_CONFIRM
                                WHERE TTM_BP_CONT.BP_ID = TTD_BP_CONT.BP_ID 
                                AND TTM_BP_CONT.NO_UKK = V_PKK_CONT.NO_UKK 
                                AND TTD_BP_CONT.CONT_NO_BP = TTD_BP_CONFIRM.CONT_NO_BP 
                                AND TTM_BP_CONT.NO_UKK = TTD_BP_CONFIRM.NO_UKK 
                                AND TTD_BP_CONT.BP_ID = TTD_BP_CONFIRM.BP_ID 
                                AND TTM_BP_CONT.KD_CABANG ='05' 
								AND TTD_BP_CONT.STATUS_CONT = '04'
                                AND TTD_BP_CONT.CONT_NO_BP = C.NO_CONTAINER
                                AND a.NO_REQUEST = '$no_req'
							   ";

	$result_nota	= $db->query($query_nota);
	$row			= $result_nota->getAll();
}

	$result_nota	= $db->query($query_nota);
	$row			= $result_nota->getAll();
$name 		= $_SESSION["NAME"]; 

$tl->assign('userid',$name);
$tl->assign('row',$row);
$tl->renderToScreen();
?>