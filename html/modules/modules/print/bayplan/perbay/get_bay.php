
<?php
	$id_vs = $_GET['id'];
				
  ?>

	<html>
	<body>
	<div id="print_html_perbay">
	<br>
	<form method="post" enctype="multipart/form-data" action="<?=HOME?>print.bayplan.perbay/simpan?id_vs=<?=$id_vs?>" target="_blank">	
	<table>
		<tr height='20'>
			<td class="form-field-caption" align="right"></td>
			<td class="form-field-caption" align="right"></td>
			<td></td>			
		</tr>
		<?
		$db = getDB();
		$query = "SELECT ID_VS, BAY FROM STW_BAY_AREA WHERE ID_VS = '$id_vs' ORDER BY BAY ASC";
		$result    = $db->query($query);
		$blok      = $result->getAll();
		$id_vs	   = $blok['ID_VS'];
		?>
		<tr>
			<td class="form-field-caption" align="center">BAY</td>
			<td class="form-field-caption" align="center"> : </td>
			<td class="form-field-caption" align="left" ><input type="hidden" name="id_vs" id="id_vs" value="$id_vs"/>	
															<select id="bay" name="bay" >
															<?php 	
																foreach ($blok as $row)
																{
																	echo "<option value=".$row['BAY']."> ".$row['BAY'] ."</option>";
																}
															?>				
															</select>
			</td>
		</tr>
		<tr>	
			<td colspan="3" align="center" height='10'></TD>
		</tr>		
		<tr>
			<td class="form-field-caption" align="center">Posisi</td>
			<td class="form-field-caption" align="center"> : </td>
			<td class="form-field-caption" align="left">	<select id="posisi" name="posisi">
															<option value='above'>Above</option>
															<option value='below'>Below</option>
															</select>
			</td>				
		</tr>
		<tr>	
			<td colspan="3" align="center" height='10'></TD>
		</tr>		
		<tr>
			<td colspan="5" align="center"> 
			<input type="submit" value="OK" name="submit"/>
			</td>
		</tr>
		
	</table>
	</form>
	</div>
	
</body>
</html>
