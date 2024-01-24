<?php
 $id_vs = $_GET['id'];
 $bay_area = $_GET['bay_area'];
 $posisi = $_GET['posisi'];
 $no_bay = $_GET['no_bay'];
 $bay = $_GET['bay'];
 $db = getDB();
 
?>

		<table>
			<tr height='20'>
				<td class="form-field-caption" align="right"></td>
				<td class="form-field-caption" align="right"></td>
				<td></td>			
			</tr>
			<tr>
				<td class="form-field-caption" align="right">Container Crane</td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left">
					<select id="alat" name="alat">
						<option value="-"> -- Pilih --</option>
					    <?
							$query_alat = "SELECT TRIM(ID_ALAT) AS ID_ALAT,NAMA_ALAT FROM MASTER_ALAT WHERE PENEMPATAN = 'BAY' ORDER BY ID_ALAT ASC";
							$result3 = $db->query($query_alat);
							$alat = $result3->getAll();	

							foreach($alat as $row3)
							{
						?>
						<option value="<?=$row3['ID_ALAT']?>"><?=$row3['NAMA_ALAT']?></option>
						<?
						  }
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="form-field-caption" align="right">Container Size</td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left">
					<select id="cont_sz" name="cont_sz">
						<option value="">-pilih-</option>
						<option value="20">20"</option>
						<option value="40">40"/45"</option>
						<option value="pn">palka</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="form-field-caption" align="right">Activity</td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left">
					<input type="text" name="activity" id="activity" value="IMPORT" readonly="readonly" size="8"/>
				</td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="3" align="right"> <input type="button" name="alokasi" value="Alokasi" onclick="alokasi_alat('<?=$id_vs?>','<?=$no_bay?>','<?=$bay_area?>','<?=$posisi?>','<?=$bay?>')" /> </td>
			</tr>
		</table>

