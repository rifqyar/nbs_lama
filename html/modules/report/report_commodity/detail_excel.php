<?php 
	$periode_awal = $_GET['periode_awal'];
	$periode_akhir = $_GET['periode_akhir'];
	$status = $_GET['status'];
	$vessel = $_GET['vessel'];
	$voyin = $_GET['voyin'];
	$voyout = $_GET['voyout'];


	$db = getDB();

	$AdditionalCondition = "";

	if($vessel != '' && $voyin != '' && $voyout != '')
	{
		$AdditionalCondition = "
			AND 
			    TRIM(A.VESSEL) = TRIM('$vessel')		
			AND 
			    TRIM(A.VOYAGE_IN) = TRIM('$voyin')		
			AND 
			    TRIM(A.VOYAGE_OUT) = TRIM('$voyout')
		";
	}

	$AdditionalConditionPeriode = "";

	if($periode_awal != '' && $periode_akhir != '')
	{
		$AdditionalConditionPeriode = "
			AND TO_DATE(C.ACTIVITY_DATE, 'YYYYMMDDHH24MISS') >= TO_DATE('$periode_awal', 'YYYYMMDD') 
			AND TO_DATE(C.ACTIVITY_DATE, 'YYYYMMDDHH24MISS') < TO_DATE('$periode_akhir', 'YYYYMMDD') + 1 
		";
	}

	$queryReceiving = "
		SELECT D.KD_COMMODITY, D.NM_COMMODITY,'LOADING' D_L,
			CASE WHEN A.SIZE_CONT = '20' AND C.ACTIVITY = 'GATE OUT DELIVERY' THEN COUNT(*) ELSE 0 END BOX_20_GATE_OUT_DELIVERY, 
			CASE WHEN A.SIZE_CONT = '20' AND C.ACTIVITY = 'GATE OUT DELIVERY' THEN SUM(C.WEIGHT) / 1000 ELSE 0 END TON_20_GATE_OUT_DELIVERY, 
			CASE WHEN A.SIZE_CONT = '40' AND C.ACTIVITY = 'GATE OUT DELIVERY' THEN COUNT(*) ELSE 0 END BOX_40_GATE_OUT_DELIVERY, 
			CASE WHEN A.SIZE_CONT = '40' AND C.ACTIVITY = 'GATE OUT DELIVERY' THEN SUM(C.WEIGHT) / 1000 ELSE 0 END TON_40_GATE_OUT_DELIVERY, 
			CASE WHEN A.SIZE_CONT = '45' AND C.ACTIVITY = 'GATE OUT DELIVERY' THEN COUNT(*) ELSE 0 END BOX_45_GATE_OUT_DELIVERY, 
			CASE WHEN A.SIZE_CONT = '45' AND C.ACTIVITY = 'GATE OUT DELIVERY' THEN SUM(C.WEIGHT) / 1000 ELSE 0 END TON_45_GATE_OUT_DELIVERY,
			CASE WHEN A.SIZE_CONT = '20' AND C.ACTIVITY = 'LOADING' THEN COUNT(*) ELSE 0 END BOX_20_LOADING, 
			CASE WHEN A.SIZE_CONT = '20' AND C.ACTIVITY = 'LOADING' THEN SUM(C.WEIGHT) / 1000 ELSE 0 END TON_20_LOADING, 
			CASE WHEN A.SIZE_CONT = '40' AND C.ACTIVITY = 'LOADING' THEN COUNT(*) ELSE 0 END BOX_40_LOADING, 
			CASE WHEN A.SIZE_CONT = '40' AND C.ACTIVITY = 'LOADING' THEN SUM(C.WEIGHT) / 1000 ELSE 0 END TON_40_LOADING, 
			CASE WHEN A.SIZE_CONT = '45' AND C.ACTIVITY = 'LOADING' THEN COUNT(*) ELSE 0 END BOX_45_LOADING, 
			CASE WHEN A.SIZE_CONT = '45' AND C.ACTIVITY = 'LOADING' THEN SUM(C.WEIGHT) / 1000 ELSE 0 END TON_45_LOADING 
		FROM
			REQ_RECEIVING_D A 
				LEFT JOIN
					OPUS_REPO.M_CYC_CONTAINER C 
					ON TRIM(A.NO_CONTAINER) = TRIM(C.NO_CONTAINER) 
					AND TRIM(A.VESSEL) = TRIM(C.VESSEL) 
					AND TRIM(A.VOYAGE_IN) = TRIM(C.VOYAGE_IN) 
					AND TRIM(A.VOYAGE_OUT) = TRIM(C.VOYAGE_OUT) 
				LEFT JOIN
					MASTER_COMMODITY D 
					ON A.KD_COMODITY = D.KD_COMMODITY 
		WHERE
			C.ACTIVITY = 'LOADING' 
			AND KD_COMMODITY IS NOT NULL 
			AND NM_COMMODITY IS NOT NULL 
			$AdditionalConditionPeriode
			$AdditionalCondition
		GROUP BY
			C.ACTIVITY, D.KD_COMMODITY, D.NM_COMMODITY, A.SIZE_CONT, A.ID_REQ
   	";

	$queryDelivery = "
		SELECT D.KD_COMMODITY, D.NM_COMMODITY,'LOADING' D_L,
			CASE WHEN A.SIZE_CONT = '20' AND C.ACTIVITY = 'GATE OUT DELIVERY' THEN COUNT(*) ELSE 0 END BOX_20_GATE_OUT_DELIVERY, 
			CASE WHEN A.SIZE_CONT = '20'  AND C.ACTIVITY = 'GATE OUT DELIVERY' THEN SUM(C.WEIGHT) / 1000 ELSE 0 END TON_20_GATE_OUT_DELIVERY, 
			CASE WHEN A.SIZE_CONT = '40'  AND C.ACTIVITY = 'GATE OUT DELIVERY' THEN COUNT(*) ELSE 0 END BOX_40_GATE_OUT_DELIVERY, 
			CASE WHEN A.SIZE_CONT = '40'  AND C.ACTIVITY = 'GATE OUT DELIVERY' THEN SUM(C.WEIGHT) / 1000 ELSE 0 END TON_40_GATE_OUT_DELIVERY, 
			CASE WHEN A.SIZE_CONT = '45'  AND C.ACTIVITY = 'GATE OUT DELIVERY' THEN COUNT(*) ELSE 0 END BOX_45_GATE_OUT_DELIVERY, 
			CASE WHEN A.SIZE_CONT = '45'  AND C.ACTIVITY = 'GATE OUT DELIVERY' THEN SUM(C.WEIGHT) / 1000 ELSE 0 END TON_45_GATE_OUT_DELIVERY,
			CASE WHEN A.SIZE_CONT = '20' AND C.ACTIVITY = 'LOADING' THEN COUNT(*) ELSE 0 END BOX_20_LOADING, 
			CASE WHEN A.SIZE_CONT = '20'  AND C.ACTIVITY = 'LOADING' THEN SUM(C.WEIGHT) / 1000 ELSE 0 END TON_20_LOADING, 
			CASE WHEN A.SIZE_CONT = '40'  AND C.ACTIVITY = 'LOADING' THEN COUNT(*) ELSE 0 END BOX_40_LOADING, 
			CASE WHEN A.SIZE_CONT = '40'  AND C.ACTIVITY = 'LOADING' THEN SUM(C.WEIGHT) / 1000 ELSE 0 END TON_40_LOADING, 
			CASE WHEN A.SIZE_CONT = '45'  AND C.ACTIVITY = 'LOADING' THEN COUNT(*) ELSE 0 END BOX_45_LOADING, 
			CASE WHEN A.SIZE_CONT = '45'  AND C.ACTIVITY = 'LOADING' THEN SUM(C.WEIGHT) / 1000 ELSE 0 END TON_45_LOADING 
		FROM
			REQ_DELIVERY_D A 
				LEFT JOIN
					OPUS_REPO.M_CYC_CONTAINER C 
					ON TRIM(A.NO_CONTAINER) = TRIM(C.NO_CONTAINER) 
					AND TRIM(A.VESSEL) = TRIM(C.VESSEL) 
					AND TRIM(A.VOYAGE_IN) = TRIM(C.VOYAGE_IN) 
					AND TRIM(A.VOYAGE_OUT) = TRIM(C.VOYAGE_OUT) 
				LEFT JOIN
					MASTER_COMMODITY D 
					ON 
					CASE
					   WHEN
					      TRIM(A.KD_COMODITY) = 'G' 
					   THEN
					      'C000000492' 
					   WHEN
					      TRIM(A.KD_COMODITY) = 'M' 
					   THEN
					      'C000001383' 
					   ELSE
					      TRIM(A.KD_COMODITY) 
					END = TRIM(D.KD_COMMODITY) 
		WHERE
			C.ACTIVITY = 'GATE OUT DELIVERY' 
			AND KD_COMMODITY IS NOT NULL 
			AND NM_COMMODITY IS NOT NULL 
			$AdditionalConditionPeriode
			$AdditionalCondition
		GROUP BY
			C.ACTIVITY, D.KD_COMMODITY, D.NM_COMMODITY, A.SIZE_CONT, A.ID_REQ
   	";

	$TOTALQUERY = "
		SELECT
			Z.KD_COMMODITY,
			Z.NM_COMMODITY,
			SUM(Z.BOX_20_GATE_OUT_DELIVERY) BOX_20_GATE_OUT_DELIVERY,
			SUM(Z.TON_20_GATE_OUT_DELIVERY) TON_20_GATE_OUT_DELIVERY,
			SUM(Z.BOX_40_GATE_OUT_DELIVERY) BOX_40_GATE_OUT_DELIVERY,
			SUM(Z.TON_40_GATE_OUT_DELIVERY) TON_40_GATE_OUT_DELIVERY,
			SUM(Z.BOX_45_GATE_OUT_DELIVERY) BOX_45_GATE_OUT_DELIVERY,
			SUM(Z.TON_45_GATE_OUT_DELIVERY) TON_45_GATE_OUT_DELIVERY,
			SUM(Z.BOX_20_GATE_OUT_DELIVERY) + SUM(Z.BOX_40_GATE_OUT_DELIVERY) + SUM(Z.BOX_45_GATE_OUT_DELIVERY) AS TOTAL_BOX_GATE_OUT_DELIVERY,
			SUM(Z.TON_20_GATE_OUT_DELIVERY) + SUM(Z.TON_40_GATE_OUT_DELIVERY) + SUM(Z.TON_45_GATE_OUT_DELIVERY) AS TOTAL_TON_GATE_OUT_DELIVERY,
			SUM(Z.BOX_20_LOADING) BOX_20_LOADING,
			SUM(Z.TON_20_LOADING) TON_20_LOADING,
			SUM(Z.BOX_40_LOADING) BOX_40_LOADING,
			SUM(Z.TON_40_LOADING) TON_40_LOADING,
			SUM(Z.BOX_45_LOADING) BOX_45_LOADING,
			SUM(Z.TON_45_LOADING) TON_45_LOADING,
			SUM(Z.BOX_20_LOADING) + SUM(Z.BOX_40_LOADING) + SUM(Z.BOX_45_LOADING) AS TOTAL_BOX_LOADING,
			SUM(Z.TON_20_LOADING) + SUM(Z.TON_40_LOADING) + SUM(Z.TON_45_LOADING) AS TOTAL_TON_LOADING 
			FROM
			(
			 	";
				if($status == "ALL")
				{
					$TOTALQUERY .= $queryReceiving.' UNION ALL '.$queryDelivery;
				}
				elseif($status == "LOADING")
				{
					$TOTALQUERY .= $queryReceiving;
				}
				elseif($status == "DISCHARGE")
				{
					$TOTALQUERY .= $queryDelivery;
				}

			 	$TOTALQUERY .= "  
			)
			Z 
			GROUP BY
			Z.KD_COMMODITY, Z.NM_COMMODITY 
			ORDER BY Z.NM_COMMODITY ASC
   	";



$result1 = $db->query($TOTALQUERY);
$baris = $result1->getAll();
?>

<?php 
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=ReportCommodity.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table>
	<tr>
		<td colspan='14' style='text-align: center'>
			<h1>
				Data Commodity Export
			</h1>
		</td>
	</tr>
	<tr>
		<td colspan='14' style='text-align: center'>	
			<h3>
				<?php 
					if($vessel != '' && $voyin != '' && $voyout != '')
					{
						echo $vessel.' Voy '.$voyin.' / '.$voyin;
					}
				?>
			</h3>
		</td>	
	</tr>	
</table>
<br>



<table>
	<tr>
		<td colspan='14' style='text-align:center'>
			REKAPITULASI KOMODITI EXPORT
		<td>
	</tr>
	<tr>
		<td colspan='14' style='text-align:center'>
			<?php
				
				if($periode_awal != '' && $periode_akhir != '')
				{
					echo date('j F Y', strtotime($periode_awal));  ?> - <?= date('j F Y', strtotime($periode_akhir));  
				}
			?> 
		<td>
	</tr>
</table>
<br/>
<br/>
<table border="1">
    <tr bgcolor="#6699FF">
		<td rowspan="3" style='text-align:center; vertical-align:middle'>NO</td>
		<td rowspan="3" style='text-align:center; vertical-align:middle'>KOMODITI</td>
		<td colspan="6" style='text-align:center'>DISCHARGE</td>
		<td colspan="6" style='text-align:center'>LOADING</td>
    </tr>
	<tr bgcolor="#6699FF">
		<td colspan="2" style='text-align:center'>20"</td>
		<td colspan="2" style='text-align:center'>40"</td>
		<td colspan="2" style='text-align:center'>45"</td>
		<td colspan="2" style='text-align:center'>20"</td>
		<td colspan="2" style='text-align:center'>40"</td>
		<td colspan="2" style='text-align:center'>45"</td>
    </tr>
	<tr bgcolor="#6699FF">
		<td style='text-align:center'>BOX</td>		
		<td style='text-align:center'>TON</td>	

		<td style='text-align:center'>BOX</td>		
		<td style='text-align:center'>TON</td>

		<td style='text-align:center'>BOX</td>		
		<td style='text-align:center'>TON</td>

		<td style='text-align:center'>BOX</td>		
		<td style='text-align:center'>TON</td>	

		<td style='text-align:center'>BOX</td>		
		<td style='text-align:center'>TON</td>

		<td style='text-align:center'>BOX</td>		
		<td style='text-align:center'>TON</td>
	</tr>	
	<?
	$no=0;

	$total_box_20_delivery = 0;
	$total_box_40_delivery = 0;
	$total_box_45_delivery = 0;

	$total_ton_20_delivery = 0;
	$total_ton_40_delivery = 0;
	$total_ton_45_delivery = 0;

	$total_box_20_receiving = 0;
	$total_box_40_receiving = 0;
	$total_box_45_receiving = 0;

	$total_ton_20_receiving = 0;
	$total_ton_40_receiving = 0;
	$total_ton_45_receiving = 0;

	foreach ($baris as $row)
	{
		$no++;
		?>
		<tr>
			<td><?=$no?></td>
			<td><?=$row[NM_COMMODITY]?></td>

			<!-- Delivery -->
				<!-- Delivery 20 -->
					<td>
						<?php 
							echo $row[BOX_20_GATE_OUT_DELIVERY];

							$total_box_20_delivery += $row[BOX_20_GATE_OUT_DELIVERY]; 
						?>
					</td>
					<td>
						<?php 
							echo $row[TON_20_GATE_OUT_DELIVERY];

							$total_ton_20_delivery += $row[TON_20_GATE_OUT_DELIVERY]; 
						?>
					</td>

				<!-- Delivery 40 -->
					<td>
						<?php 
							echo $row[BOX_40_GATE_OUT_DELIVERY];							

							$total_box_40_delivery += $row[BOX_40_GATE_OUT_DELIVERY]; 
						?>
					</td>
					<td>
						<?php 
							echo $row[TON_40_GATE_OUT_DELIVERY];

							$total_ton_40_delivery += $row[TON_40_GATE_OUT_DELIVERY]; 
						?>
					</td>

				<!-- Delivery 45 -->
					<td>
						<?php 
							echo $row[BOX_45_GATE_OUT_DELIVERY];

							$total_box_45_delivery += $row[BOX_45_GATE_OUT_DELIVERY]; 
						?>
					</td>
					<td>
						<?php 
							echo $row[TON_45_GATE_OUT_DELIVERY];

							$total_ton_45_delivery += $row[TON_45_GATE_OUT_DELIVERY]; 
						?>
					</td>


			<!-- Receiving -->
				<!-- Receiving 20 -->
					<td>
						<?php 
							echo $row[BOX_20_LOADING];

							$total_box_20_receiving += $row[BOX_20_LOADING]; 
						?>
					</td>
					<td>
						<?php 
							echo $row[TON_20_LOADING];

							$total_ton_20_receiving += $row[TON_20_LOADING]; 
						?>
					</td>

				<!-- Receiving 40 -->
					<td>
						<?php 
							echo $row[BOX_40_LOADING];

							$total_box_40_receiving += $row[BOX_40_LOADING]; 
						?>
					</td>
					<td>
						<?php 
							echo $row[TON_40_LOADING];

							$total_ton_40_receiving += $row[TON_40_LOADING]; 
						?>
					</td>

				<!-- Receiving 45 -->
					<td>
						<?php 
							echo $row[BOX_45_LOADING];

							$total_box_45_receiving += $row[BOX_45_LOADING]; 
						?>
					</td>
					<td>
						<?php 
							echo $row[TON_45_LOADING];
							
							$total_ton_45_receiving += $row[TON_45_LOADING]; 
						?>
					</td>
			
		</tr>
	<? } ?>
	<tr>
		<td colspan='2' style='text-align:center'>
			TOTAL
		</td>
		<td>
			<?= $total_box_20_delivery; ?>
		</td>
		<td>
			<?= number_format($total_ton_20_delivery,2,",","."); ?>
		</td>

		<td>
			<?= $total_box_40_delivery; ?>
		</td>
		<td>
			<?= number_format($total_ton_40_delivery,2,",","."); ?>
		</td>

		<td>
			<?= $total_box_45_delivery; ?>
		</td>
		<td>
			<?= number_format($total_ton_45_delivery,2,",","."); ?>
		</td>


		<td>
			<?= $total_box_20_receiving; ?>
		</td>
		<td>
			<?= number_format($total_ton_20_receiving,2,",","."); ?>
		</td>

		<td>
			<?= $total_box_40_receiving; ?>
		</td>
		<td>
			<?= number_format($total_ton_40_receiving,2,",","."); ?>
		</td>

		<td>
			<?= $total_box_45_receiving; ?>
		</td>
		<td>
			<?= number_format($total_ton_45_receiving,2,",","."); ?>
		</td>

	</tr>
</table>

<?php die(); ?>