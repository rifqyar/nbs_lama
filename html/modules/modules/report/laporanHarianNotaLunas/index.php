<style>
.content{
	width:95%;
	margin-left:auto;
	margin-right:auto;
	margin-bottom: 10px;
	margin-top:20px;
}
.main_side{
	width:100%;
	float:left;
	text-align:left;
}
.rightside{ 
	width:25%;
	float:right;
	text-align:center;
}

.main_side{
	width:100%;
	float:left;
	text-align:left;
}
.ui-jqgrid .ui-jqgrid-htable th div {
    height:auto;
    overflow:hidden;
    padding-right:4px;
    padding-top:2px;
    position:relative;
    vertical-align:text-top;
    white-space:normal !important;
}


</style>
<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
$backuri                      = str_replace('&_saveok=1', '', $_SERVER['REQUEST_URI']);
$_SESSION['__uriback'][APPID] = $backuri . (strpos($_SERVER['REQUEST_URI'], '?') === false ? '?' : '');

$db=getDb();
$qry="select DESCRIPTIVE_FLEX_CONTEXT_CODE AS KD_MODUL, DESCRIPTION  from master_modul_simkeu where DESCRIPTIVE_FLEX_CONTEXT_CODE like 'PTKM%' order by DESCRIPTIVE_FLEX_CONTEXT_CODE";
$execqry=$db->query($qry);
$rowd=$execqry->getAll();
//echo $rowd[1][KD_MODUL];die;

?>


<script>
$(document).ready(function() 
{		
	$("#trxdate").datepicker({
		dateFormat: 'dd-mm-yy',
		maxDate: 0
	});
	
});
function export_excel()
{
	var trxdate = $( "#trxdate" ).val();
	var keg = $( "#KEG" ).val();
	
	window.open("<?=$HOME?>report.laporanHarianNotaLunas.excel_pdf/export_excel?trxdate="+trxdate+"&keg="+keg);
	
}
function export_pdf()
{
	var trxdate = $( "#trxdate" ).val();
	var keg = $( "#KEG" ).val();
	
	window.open("<?=$HOME?>report.laporanHarianNotaLunas.excel_pdf/export_pdf?trxdate="+trxdate+"&keg="+keg);
	
}

</script>

<div class="content">
	<div class="main_side">
	<p>
	<img src="<?=HOME?>images/gudreport.png" height="7%" width="7%" style="vertical-align:middle">
		<b> <font color='#69b3e2' size='4px'>Laporan</font> </b>
	 <font color='#888b8d' size='4px'>
	 Harian Nota Lunas (PTKM)
	 </font>
	</p>
	<fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; ">
	<center>
		<table style="margin: 10px 10px 10px 10px;" border="0">
			<tr>
				<td>Tanggal Transaksi</td>
				<td> : </td>
				<td> <input type="text" size="25" align="center" name="trxdate" id="trxdate" size="30"/></td>
			</tr>
			<tr>
				<td>Tipe Layanan</td>
				<td> : </td>
				<td> 
				<select name="KEG" id="KEG">
					<option value="ALL">ALL</option>
				  <?php foreach($rowd as $rows){
				  ?>
				  <option value="<?=$rows['KD_MODUL']?>"><?=$rows['DESCRIPTION']?></option>
				  <?php }?>
				</select>
				</td>
			</tr>
			
			<tr>
				<td>Export to</td>
				<td> : </td>
				<td align="center">
				<a onclick="export_excel()" style="height: 35px; width:80px;" target="_blank" title="export to excel">
				<img src="<?=HOME?>images/mexcel2.png" ></a>&nbsp;</td>
			</tr>
		</table>
	</center>
	</fieldset>
	
	
	<p><br/></p>
	<table id='list_container' width="100%"></table> <div id='pg_l_booking'></div>
	
	<br>
	<br>
	
	<div id="dialog-form">
		<form>
			<div id="table_profil"></div>
		</form>
	</div>
		<br/>
		<br/>
		</div>
	</div>
	<div id="shift_r"></div>
	<div id="hatch_r"></div>



