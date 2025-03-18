<script type="text/javascript">

function print(idplp)
{
	var option = $("#cetak").val();
	
	if(option=='listcont')
	{
		window.open('<?=HOME?>request.plplini1.print/print_req/?id_plp='+idplp,'_blank');
	}
	else
	{
		window.open('<?=HOME?>request.plplini1.print/sp_yor/?id_plp='+idplp,'_blank');
	}
}

</script>

	<br />
	<?
		$id_plp = $_GET['plpid'];
	?>
	<table>
		<tr>
			<td class="form-field-caption" align="right">Option</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<select name="cetak" id="cetak">
					<option value="listcont"> List Container </option>
					<option value="spyor"> SP YOR </option>
				</select>
				&nbsp;&nbsp;
				<input type="button" name="cetak" value="&nbsp;Print&nbsp;" onclick="print('<?=$id_plp?>')"/>
			</td>
		</tr>
	</table>		