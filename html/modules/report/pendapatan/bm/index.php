
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
#zebra tr:nth-child(odd) td {
  background-color: #FFF; /* Warna untuk baris ganjil */
}
#zebra tr:nth-child(even) td {
  background-color: #bbe3fe; /* Warna untuk baris genap */
}
</style>
</header>
<body>
<!--<script src="<?=HOME?>js_chart/js/highcharts.js"></script>
<script src="<?=HOME?>js_chart/js/modules/exporting.js"></script>-->


		
		
<div class="content">
<div class="main_side"><h2>&nbsp;<img class="icon" border="0" width="32" height="32" src="images/excel-icon.png" />&nbsp;REPORT YARD</h2><p><br/></p><div align="center">
<fieldset class="form-fieldset">
<table border="0">
<tr height='15'>
		<td colspan="3" class="form-field-caption" valign="top" align="right">  </td>
	</tr>
	<tr>
		<td class="form-field-caption" valign="top" align="right">Pilih Kategori</td>
		<td class="form-field-caption" valign="top" align="right">:</td>
		<td class="form-field-caption" valign="top" align="left">
			<select name="kategori" id="kategori" class="form-field-caption" valign="top" align="right">
                  <option value=""> -- Pilih -- </option>
				  <option value="0"> Yard </option>
				  <option value="1"> Vessel </option>
            </select>
		</td>
	</tr>
	<tr>
		<td class="form-field-caption" valign="top" align="right">Nama Vessel / Voyage</td>
		<td class="form-field-caption" valign="top" align="right">:</td>
		<td class="form-field-caption" valign="top" align="left">
			<input type="text" id="vessel" name="vessel" size="25" />
			<input type="text" id="voyage" name="voyage" size="5" />
			<input type="hidden" id="id_vessel" name="id_vessel" size="5" />
		</td>
	</tr>
	<tr   height='15'>
		<td colspan="3" class="form-field-caption" valign="top" align="right">  </td>
	</tr>
	<tr  height='25'>
		<td colspan="3" class="form-field-caption" valign="top" align="center"> &nbsp;&nbsp; <a class="link-button" style="height:25" onclick="generate()"><img src='images/cari.png' border='0' />Generate Report</a>
		&nbsp &nbsp <a class="link-button" style="height:25" onclick="toexcel()"><img src='images/cari.png' border='0' />Generate Excel</a> </td>
	</tr>
	<tr   height='15'>
		<td colspan="3" class="form-field-caption" valign="top" align="right">  </td>
	</tr>
</table>
</div>
</fieldset>
<p><br/>
</p>
<div id="detail_yard" align="center"></div>
<p><br></p>

<table>
<tr>
<td>
<!--<div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>-->
<p><br></p>
</td>
</tr>
</table>
</div></div>
</body>
</html>