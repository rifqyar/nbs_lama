<?php
	$voyin	= $_GET['voyin'];
	$voyout	= $_GET['voyout'];
	$vessel	= $_GET['ukk'];
	$ct		= $_GET['id_ct'];
	$ei		= $_GET['ei'];
	$db		= getDb();
 
	if ($ei == 'I'){
		$sq="/* Formatted on 23-Apr-14 4:39:49 PM (QP5 v5.163.1008.3004) */
			  /* Formatted on 22/05/2015 02:14:51 PM (QP5 v5.163.1008.3004) */
  SELECT z.NO_CONTAINER,
         z.ID_NOTA,
         z.NO_FAKTUR,
         z.ID_REQ,
         z.EMKL,
         z.STATUS,
         z.ALAMAT,
         z.VESSEL,
         z.VOYAGE_IN,
         z.VOYAGE_OUT,
         z.TGL_SIMPAN,
         z.TGL_PAYMENT,
         z.PAYMENT_VIA,
         z.TOTAL,
         z.COA,
         z.KD_MODUL,
         z.KET,
         y.TANGGAL_RECEIPT,
         z.TGL_BERLAKU,
         z.USER_ID,
         tu.NAME
    FROM (SELECT NO_CONTAINER,
                 nota_delivery_h.ID_NOTA,
                 NO_FAKTUR,
                 req_delivery_h.ID_REQ,
                 nota_delivery_h.EMKL,
                 nota_delivery_h.STATUS,
                 nota_delivery_h.ALAMAT,
                 nota_delivery_h.VESSEL,
                 nota_delivery_h.VOYAGE_IN,
                 nota_delivery_h.VOYAGE_OUT,
                 req_delivery_h.TGL_REQUEST AS TGL_SIMPAN,
                 TGL_PAYMENT,
                 PAYMENT_VIA,
                 TOTAL,
                 nota_delivery_h.COA,
                 KD_MODUL,
                 'SP2' KET,
                 TO_CHAR (nota_delivery_h.TGL_SP2, 'DD-MON-YYYY')
                    AS TGL_BERLAKU,
                 req_delivery_h.ID_USER AS USER_ID
            FROM req_delivery_d
                 LEFT JOIN nota_delivery_h
                    ON (TRIM (req_delivery_d.ID_REQ) =
                           TRIM (nota_delivery_h.ID_REQ))
                 LEFT JOIN req_delivery_h
                    ON (TRIM (req_delivery_d.ID_REQ) =
                           TRIM (req_delivery_h.ID_REQ))
          UNION
          SELECT NO_CONTAINER,
                 BH_NOTA.ID_NOTA,
                 NO_FAKTUR,
                 BH_NOTA.ID_REQUEST AS ID_REQ,
                 EMKL,
                 STATUS,
                 ALAMAT_EMKL AS ALAMAT,
                 VESSEL,
                 ' ' AS VOYAGE_IN,
                 VOYAGE AS VOYAGE_OUT,
                 TGL_CETAK AS TGL_SIMPAN,
                 TGL_PAYMENT,
                 PAYMENT_VIA,
                 TOTAL,
                 COA,
                 '' AS KD_MODUL,
                 'BH' KET,
                 '' AS TGL_BERLAKU,
                 USER_ID_PAYMENT AS USER_ID
            FROM    BH_NOTA
                 INNER JOIN
                    BH_DETAIL_NOTA
                 ON (TRIM (BH_NOTA.ID_NOTA) = TRIM (BH_DETAIL_NOTA.ID_NOTA))
          UNION
          SELECT NO_CONTAINER,
                 EXMO_NOTA.ID_NOTA,
                 NO_FAKTUR,
                 ID_REQ AS ID_REQ,
                 EMKL,
                 STATUS,
                 ALAMAT,
                 '' AS VESSEL,
                 ' ' AS VOYAGE_IN,
                 ' ' AS VOYAGE_OUT,
                 TGL_CETAK_NOTA AS TGL_SIMPAN,
                 TGL_PAYMENT,
                 PAYMENT_VIA,
                 TOTAL,
                 COA,
                 '' AS KD_MODUL,
                 'EXMO' KET,
                 '' AS TGL_BERLAKU,
                 USER_ID_PAYMENT AS USER_ID
            FROM    EXMO_NOTA
                 INNER JOIN
                    EXMO_DETAIL_NOTA
                 ON (TRIM (EXMO_NOTA.ID_NOTA) = TRIM (EXMO_DETAIL_NOTA.ID_NOTA))
          UNION
          SELECT NO_CONTAINER,
                 B.ID_NOTA,
                 B.NO_FAKTUR,
                 B.ID_REQ AS ID_REQ,
                 B.CUSTOMER AS EMKL,
                 B.STATUS,
                 B.ALAMAT,
                 C.VESSEL_NEW AS VESSEL,
                 ' ' AS VOYAGE_IN,
                 C.VOYAGE_OUT_NEW AS VOYAGE_OUT,
                 B.TGL_CETAK AS TGL_SIMPAN,
                 B.TGL_PAYMENT,
                 B.PAYMENT_VIA,
                 B.TOTAL,
                 B.COA,
                 '' AS KD_MODUL,
                 'TRANS' KET,
                 '' AS TGL_BERLAKU,
                 USER_ID_PAYMENT AS USER_ID
            FROM REQ_TRANSHIPMENT_D A
                 LEFT JOIN NOTA_TRANSHIPMENT_H B
                    ON A.ID_REQ = B.ID_REQ
                 LEFT JOIN REQ_TRANSHIPMENT_H C
                    ON (TRIM (A.ID_REQ) = TRIM (C.ID_REQ))
          UNION
          SELECT NO_CONTAINER,
                 NOTA_HICOSCAN_H.ID_NOTA,
                 NOTA_HICOSCAN_H.ID_NOTA AS NO_FAKTUR,
                 REQ_HICOSCAN_D.ID_REQUEST AS ID_REQ,
                 NOTA_HICOSCAN_H.EMKL AS NM_PEMILIK,
                 NOTA_HICOSCAN_H.STATUS AS STATUS,
                 NOTA_HICOSCAN_H.ALAMAT_EMKL AS ALAMAT,
                 NOTA_HICOSCAN_H.VESSEL,
                 NOTA_HICOSCAN_H.VOYAGE AS VOYAGE_IN,
                 NULL AS VOYAGE_OUT,
                 TGL_CETAK AS TGL_SIMPAN,
                 TGL_PAYMENT,
                 PAYMENT_VIA,
                 TOTAL,
                 COA,
                 '' AS KD_MODUL,
                 'HICO' KET,
                 '' AS TGL_BERLAKU,
                 USER_ID_PAYMENT AS USER_ID
            FROM REQ_HICOSCAN_D
                 LEFT JOIN NOTA_HICOSCAN_H
                    ON (TRIM (NOTA_HICOSCAN_H.ID_REQUEST) =
                           TRIM (REQ_HICOSCAN_D.ID_REQUEST))
                 LEFT JOIN REQ_HICOSCAN
                    ON (TRIM (REQ_HICOSCAN.ID_REQUEST) =
                           TRIM (REQ_HICOSCAN_D.ID_REQUEST))) z
         LEFT JOIN tth_nota_all y
            ON z.ID_NOTA = y.KD_UPER
         LEFT JOIN TB_USER tu
            ON z.USER_ID = TU.ID
   WHERE z.NO_CONTAINER = '$ct'
ORDER BY z.TGL_SIMPAN DESC
			";
	} else {
				$sq="/* Formatted on 23-Apr-14 4:39:49 PM (QP5 v5.163.1008.3004) */
			  /* Formatted on 22/05/2015 11:16:00 AM (QP5 v5.163.1008.3004) */
  SELECT z.NO_CONTAINER,
         z.ID_NOTA,
         z.NO_FAKTUR,
         z.ID_REQ,
         z.EMKL,
         z.STATUS,
         z.ALAMAT,
         z.VESSEL,
         z.VOYAGE_IN,
         z.VOYAGE_OUT,
         z.TGL_SIMPAN,
         z.TGL_PAYMENT,
         z.PAYMENT_VIA,
         z.TOTAL,
         z.COA,
         z.KD_MODUL,
         z.KET,
         y.TANGGAL_RECEIPT,
         z.TGL_BERLAKU,
         z.PEB,
         z.NPE,
         z.USER_ID,
         tu.NAME
    FROM (SELECT NO_CONTAINER,
                 NOTA_receiving_h.ID_NOTA,
                 NO_FAKTUR,
                 req_receiving_h.ID_REQ,
                 EMKL,
                 nota_receiving_h.STATUS,
                 nota_receiving_h.ALAMAT,
                 nota_receiving_h.VESSEL,
                 req_receiving_h.VOYAGE_in AS VOYAGE_IN,
                 nota_receiving_h.VOYAGE_OUT,
                 req_receiving_h.TGL_REQUEST AS TGL_SIMPAN,
                 TGL_PAYMENT,
                 PAYMENT_VIA,
                 TOTAL,
                 nota_receiving_h.COA,
                 KD_MODUL,
                 'ANNE' KET,
                 '' AS TGL_BERLAKU,
                 req_receiving_h.PEB,
                 req_receiving_h.NPE,
                 req_receiving_h.ID_USER AS USER_ID
            FROM req_receiving_d
                 LEFT JOIN nota_receiving_h
                    ON (TRIM (nota_receiving_h.ID_REQ) =
                           TRIM (req_receiving_d.ID_REQ))
                 LEFT JOIN req_receiving_h
                    ON (TRIM (req_receiving_h.ID_REQ) =
                           TRIM (req_receiving_d.ID_REQ))
           WHERE req_receiving_h.NO_UKK = '$vessel'
          UNION
          SELECT NO_CONTAINER,
                 nota_batalmuat_h.ID_NOTA AS ID_NOTA,
                 NO_FAKTUR,
                 nota_batalmuat_h.ID_REQ AS ID_REQ,
                 nota_batalmuat_h.EMKL,
                 nota_batalmuat_h.STATUS,
                 nota_batalmuat_h.ALAMAT,
                 req_batalmuat_h.VESSEL,
                 ' ' AS VOYAGE_IN,
                 req_batalmuat_h.VOYAGE_OUT AS VOYAGE_OUT,
                 TGL_NOTA AS TGL_SIMPAN,
                 TGL_PAYMENT,
                 PAYMENT_VIA,
                 nota_batalmuat_h.TOTAL,
                 COA,
                 nota_batalmuat_h.JENIS AS KD_MODUL,
                 'BM' KET,
                 '' AS TGL_BERLAKU,
                 '' PEB,
                 '' NPE,
                 USER_ID_PAYMENT AS USER_ID
            FROM nota_batalmuat_h
                 LEFT JOIN req_batalmuat_d
                    ON (TRIM (nota_batalmuat_h.ID_REQ) =
                           TRIM (req_batalmuat_d.ID_REQ))
                 LEFT JOIN req_batalmuat_h
                    ON (TRIM (req_batalmuat_h.ID_REQ) =
                           TRIM (req_batalmuat_d.ID_REQ))
          UNION
          SELECT NO_CONTAINER,
                 BH_NOTA.ID_NOTA,
                 NO_FAKTUR,
                 BH_NOTA.ID_REQUEST AS ID_REQ,
                 EMKL,
                 STATUS,
                 ALAMAT_EMKL AS ALAMAT,
                 VESSEL,
                 ' ' AS VOYAGE_IN,
                 VOYAGE AS VOYAGE_OUT,
                 TGL_CETAK AS TGL_SIMPAN,
                 TGL_PAYMENT,
                 PAYMENT_VIA,
                 TOTAL,
                 COA,
                 '' AS KD_MODUL,
                 'BH' KET,
                 '' AS TGL_BERLAKU,
                 '' PEB,
                 '' NPE,
                 USER_ID_PAYMENT AS USER_ID
            FROM    BH_NOTA
                 INNER JOIN
                    BH_DETAIL_NOTA
                 ON (TRIM (BH_NOTA.ID_NOTA) = TRIM (BH_DETAIL_NOTA.ID_NOTA))
          UNION
          SELECT NO_CONTAINER,
                 EXMO_NOTA.ID_NOTA,
                 NO_FAKTUR,
                 ID_REQUEST AS ID_REQ,
                 EMKL,
                 STATUS,
                 ALAMAT,
                 '' AS VESSEL,
                 ' ' AS VOYAGE_IN,
                 ' ' AS VOYAGE_OUT,
                 TGL_CETAK_NOTA AS TGL_SIMPAN,
                 TGL_PAYMENT,
                 PAYMENT_VIA,
                 TOTAL,
                 COA,
                 '' AS KD_MODUL,
                 'EXMO' KET,
                 '' AS TGL_BERLAKU,
                 '' PEB,
                 '' NPE,
                 USER_ID_PAYMENT AS USER_ID
            FROM    EXMO_NOTA
                 INNER JOIN
                    EXMO_DETAIL_NOTA
                 ON (TRIM (EXMO_NOTA.ID_NOTA) = TRIM (EXMO_DETAIL_NOTA.ID_NOTA))
          UNION
          SELECT NO_CONTAINER,
                 A.ID_NOTA,
                 A.NO_FAKTUR,
                 A.ID_REQ AS ID_REQ,
                 A.CUSTOMER AS EMKL,
                 A.STATUS,
                 A.ALAMAT,
                 B.VESSEL_NEW AS VESSEL,
                 ' ' AS VOYAGE_IN,
                 B.VOYAGE_OUT_NEW AS VOYAGE_OUT,
                 A.TGL_CETAK AS TGL_SIMPAN,
                 A.TGL_PAYMENT,
                 A.PAYMENT_VIA,
                 A.TOTAL,
                 A.COA,
                 '' AS KD_MODUL,
                 'TRANS' KET,
                 '' AS TGL_BERLAKU,
                 '' PEB,
                 '' NPE,
                 USER_ID_PAYMENT AS USER_ID
            FROM NOTA_TRANSHIPMENT_H A
                 LEFT JOIN REQ_TRANSHIPMENT_H B
                    ON B.ID_REQ = A.ID_REQ
                 INNER JOIN REQ_TRANSHIPMENT_D C
                    ON (TRIM (A.ID_REQ) = TRIM (C.ID_REQ))
          UNION
          SELECT NO_CONTAINER,
                 NOTA_REEKSPOR_H.ID_NOTA,
                 NO_FAKTUR,
                 NOTA_REEKSPOR_H.ID_REQUEST AS ID_REQ,
                 NM_PEMILIK,
                 STATUS,
                 ALAMAT,
                 NM_KAPAL AS VESSEL,
                 VOYAGE_IN,
                 VOYAGE_OUT,
                 TGL_SIMPAN,
                 TGL_PAYMENT,
                 PAYMENT_VIA,
                 TOTAL,
                 COA,
                 '' AS KD_MODUL,
                 'REEX' KET,
                 '' AS TGL_BERLAKU,
                 '' PEB,
                 '' NPE,
                 USER_ID_PAYMENT AS USER_ID
            FROM    NOTA_REEKSPOR_H
                 INNER JOIN
                    REQ_REEKSPOR_D
                 ON (TRIM (NOTA_REEKSPOR_H.ID_REQUEST) =
                        TRIM (REQ_REEKSPOR_D.ID_REQUEST))
          UNION
          SELECT NO_CONTAINER,
                 A.ID_NOTA,
                 A.NO_FAKTUR,
                 A.ID_REQ AS ID_REQ,
                 A.CUSTOMER AS EMKL,
                 A.STATUS,
                 A.ALAMAT,
                 B.VESSEL_NEW AS VESSEL,
                 ' ' AS VOYAGE_IN,
                 B.VOYAGE_OUT_NEW AS VOYAGE_OUT,
                 A.TGL_CETAK AS TGL_SIMPAN,
                 A.TGL_PAYMENT,
                 A.PAYMENT_VIA,
                 A.TOTAL,
                 A.COA,
                 '' AS KD_MODUL,
                 'TRANS' KET,
                 '' AS TGL_BERLAKU,
                 '' PEB,
                 '' NPE,
                 USER_ID_PAYMENT AS USER_ID
            FROM NOTA_REEXPORT_H A
                 LEFT JOIN REQ_REEXPORT_H B
                    ON B.ID_REQ = A.ID_REQ
                 INNER JOIN REQ_REEXPORT_D C
                    ON (TRIM (A.ID_REQ) = TRIM (C.ID_REQ))
          UNION
          SELECT NO_CONTAINER,
                 NOTA_HICOSCAN_H.ID_NOTA,
                 NOTA_HICOSCAN_H.ID_NOTA AS NO_FAKTUR,
                 NOTA_HICOSCAN_H.ID_REQUEST AS ID_REQ,
                 NOTA_HICOSCAN_H.EMKL AS NM_PEMILIK,
                 NOTA_HICOSCAN_H.STATUS,
                 NOTA_HICOSCAN_H.ALAMAT_EMKL AS ALAMAT,
                 NOTA_HICOSCAN_H.VESSEL,
                 NOTA_HICOSCAN_H.VOYAGE AS VOYAGE_IN,
                 NULL AS VOYAGE_OUT,
                 TGL_CETAK AS TGL_SIMPAN,
                 TGL_PAYMENT,
                 PAYMENT_VIA,
                 TOTAL,
                 COA,
                 '' AS KD_MODUL,
                 'HICO' KET,
                 '' AS TGL_BERLAKU,
                 '' PEB,
                 '' NPE,
                 USER_ID_PAYMENT AS USER_ID
            FROM    NOTA_HICOSCAN_H
                 INNER JOIN
                    NOTA_HICOSCAN_D
                 ON (TRIM (NOTA_HICOSCAN_H.ID_NOTA) =
                        TRIM (NOTA_HICOSCAN_D.ID_NOTA))) z
         LEFT JOIN tth_nota_all y
            ON z.ID_NOTA = y.KD_UPER
         LEFT JOIN TB_USER tu
            ON z.USER_ID = TU.ID
   WHERE z.NO_CONTAINER = '$ct'
ORDER BY z.TGL_SIMPAN DESC
			";
	}
	
		
	$eq=$db->query($sq);
	$rq=$eq->getAll();
?>
<style>
.std{
	
	font:italic bold 12px Helvetica;
	color:#6d6b6b;
	text-align:center;
	background-color:#ffffff;
	height:30px;
}
.std2{
	
	font:italic bold 12px Helvetica;
	color:#f91550;
	text-align:center;
	background-color:#ffffff;
	height:30px;
}
.std3{
	
	font:italic bold 12px Helvetica;
	color:#f91550;
	text-align:left;
	background-color:#ffffff;
	height:30px;
}

</style>

<table>
	<tr>
		<th class="grid-header" width="20">No</th>   
		<th class="grid-header" width="200">No. Request</th>
		<th class="grid-header" width="100">Type</th>
		<th class="grid-header" width="100">No. Invoice</th>
		<!--<th class="grid-header" width="200">Tax Number</th>-->
		<th class="grid-header" width="100">Status</th>
		<th class="grid-header" width="100">Date Request</th>
		<th class="grid-header" width="100">Date Payment</th>
		<th class="grid-header" width="100">Paid Thru</th>
         <!--       <th class="grid-header" width="100">O Voy</th>
                <th class="grid-header" width="100">N Voy</th>-->
		<th class="grid-header" width="100">User</th>
		<th class="grid-header" width="100">Customer</th>
		<th class="grid-header" width="100">NPE</th>
		<th class="grid-header" width="100">PEB</th>
    </tr>
	<?php
	$no=1;
	
	foreach ($rq as $row) {
	?>
	<tr>
		<td class="std"><?=$no;?></td>
		<td  class="std"><?=$row['ID_REQ'];?></td>
		<td  class="std"><?=$row['KET'];?></td>
		<td  class="std"><?=$row['ID_NOTA'];?></td>
		<!--<td  class="std"><?=$row['NO_FAKTUR'];?></td>-->
		<td  class="std"><?=$row['STATUS'];?></td>
		<td  class="std"><?=$row['TGL_SIMPAN'];?></td>
		<td  class="std"><?=$row['TGL_PAYMENT'];?></td>
		<td  class="std"><?=$row['TGL_BERLAKU'];?></td>
		<!--        <td></td>
                <td></td>-->
		<td  class="std"><?=$row['NAME'];?></td>
		<td  class="std"><?=$row['EMKL'];?></td>
		<td  class="std"><?=$row['PEB'];?></td>
		<td  class="std"><?=$row['NPE'];?></td>
	</tr>
	<tr>
		<td class="std3">&nbsp;</td>
		<!--<td  class="std3" colspan="6">Customer : <?=$row['EMKL'];?></td>
		
		<td  class="std3" colspan="5">PEB : <?=$row['PEB'];?> NPE: <?=$row['NPE'];?></td>-->
		
	</tr>
	<?php $no++; }?>
</table>