<?php
?>

<style>
.content {
    margin: 20px auto 10px;
    width: 95%;
}
</style>

<script type="text/javascript">
$(document).ready(function() 
{		
	$("#tanggal1").datepicker({
		dateFormat: 'dd-mm-yy',
		maxDate: 0
	});
	document.getElementById("month2").style.display="none";
	document.getElementById("year2").style.display="none";
});

function chPeriodtype()
{
	var id=$('#vperiod').val();
	if(id==0)
	{
		document.getElementById("month2").style.display="none";
		document.getElementById("year2").style.display="none";
		document.getElementById("tanggal1").style.display="";
	}
	else{
		document.getElementById("tanggal1").style.display="none";
		document.getElementById("month2").style.display="";
		document.getElementById("year2").style.display="";
	}
}

function genReport()
{
	var id=$('#vperiod').val();
	if(id==0)
	{
		var paramperiod=$('#tanggal1').val();
	}
	else
	{
		var paramperiod=$('#month2').val()+''+$('#year2').val();
	}
	var url="<?=HOME?>report.rp_rfrrevenue.ajax/generate";

	$.post(url,{period:paramperiod,periodtype:id},function(data){
		//alert(data);
		var url2="<?=HOME;?>report.rp_rfrrevenue.ajax/toexcel?id="+data;
		window.location.replace(url2);
	});
}

</script>
<div class="content">
	
<p>
	<img src="<?=HOME?>images/20.png" height="7%" width="7%" style="vertical-align:middle">
	<b> <font color='#69b3e2' size='4px'>Report</font> </b>
	 <font color='#888b8d' size='4px'>
	 Reefer Revenue
	 </font>
	</p>
	<br>
<table>
	<tr>
		<td>Period Type</td>
		<td><select id="vperiod" onclick="chPeriodtype()" onchange="chPeriodtype()">
			<option value="0">Daily</option>
			<option value="1">Monthly</option>
		</select>
		</td>
	</tr>
	<tr>
		<td>Period</td>
		<td><input id="tanggal1" name="tanggal1" type="readonly" size="20" placeholder="date month year"/>
			<select id="month2" name="month2"/>
				<option value='01'>01</option>
				<option value='02'>02</option>
				<option value='03'>03</option>
				<option value='04'>04</option>
				<option value='05'>05</option>
				<option value='06'>06</option>
				<option value='07'>07</option>
				<option value='08'>08</option>
				<option value='09'>09</option>
				<option value='10'>10</option>
				<option value='11'>21</option>
				<option value='12'>12</option>
			</select>
			<select id="year2" name="year2"/>
				<option value='2019'>2019</option>
				<option value='2018'>2018</option>
				<option value='2017'>2017</option>
				<option value='2016'>2016</option>
				<option value='2015'>2015</option>
				<option value='2014'>2014</option>
				<option value='2013'>2013</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Period</td>
		<td><button onclick="genReport()">Generate to Excel</button>
		</td>
	</tr>
</table>
<div>
<br>