<style>
.content{
	width:95%;
	margin-left:auto;
	margin-right:auto;
	margin-bottom: 10px;
	margin-top:20px;

}
button{
	border-radius: 4px;
    border: 1px solid #d0d0d0;
	}
button:hover {
	background: #69b3e2;
	box-shadow: 0 2px 4px rgba(0,0,0,0.5), inset 0 1px rgba(255,255,255,0.3), inset 0 10px rgba(255,255,255,0.2), inset 0 10px 20px rgba(255,255,255,0.25), inset 0 -15px 30px rgba(0,0,0,0.3);

	-o-box-shadow: 0 2px 4px rgba(0,0,0,0.5), inset 0 1px rgba(255,255,255,0.3), inset 0 10px rgba(255,255,255,0.2), inset 0 10px 20px rgba(255,255,255,0.25), inset 0 -15px 30px rgba(0,0,0,0.3);

	-webkit-box-shadow: 0 2px 4px rgba(0,0,0,0.5), inset 0 1px rgba(255,255,255,0.3), inset 0 10px rgba(255,255,255,0.2), inset 0 10px 20px rgba(255,255,255,0.25), inset 0 -15px 30px rgba(0,0,0,0.3);
	-moz-box-shadow: 0 2px 4px rgba(0,0,0,0.5), inset 0 1px rgba(255,255,255,0.3), inset 0 10px rgba(255,255,255,0.2), inset 0 10px 20px rgba(255,255,255,0.25), inset 0 -15px 30px rgba(0,0,0,0.3);

	
}

</style>

<script type='text/javascript'>
$(document).ready(function() 
{
	$("#start_date").datepicker({
			dateFormat: 'dd/mm/yy'
            });
	$("#end_date").datepicker({
			dateFormat: 'dd/mm/yy'
            });
	
});  

function validasi()
{
	if(document.getElementById("start_date").value=="") {
		alert("Tanggal Awal Belum Diisi...!!!");
		document.getElementById("start_date").focus();
		return false;
	} else if(document.getElementById("end_date").value=="") {
		alert("Tanggal Akhir Belum Diisi...!!!");
		document.getElementById("end_date").focus();
		return false;
	} else {
		return preview_nota_bhd();
	}
}

function preview_nota_bhd()
{
   var cust_no     = $("#cust_no").val();
   var start_date  = $("#start_date").val();
   var end_date    = $("#end_date").val();
   //alert(cust_no+" "+start_date+" "+end_date);
   window.location = "<?=HOME?>request.behandle_tpft/preview_nota_bhd?cust_no="+cust_no+"&start_date="+start_date+"&end_date="+end_date;   
}
</script>

<div class="content">
	<p>
	<h1> <img src="<?=HOME?>images/behandle2.png" height="5%" width="5%" style="vertical-align:middle">&nbsp;<font color="#0378C6">Request</font> Behandle TPFT</h1></p>
	<p><br/>
	<hr width="870" color="#e1e0de"></hr>
	<br>
	<table width="800" border="0">
	  <tr>
	    <td class="form-field-caption" widht="500" align="right">Nama Pengguna Jasa</td>
		<td class="form-field-caption">:</td>
		<td class="form-field-caption" colspan="5"><input type="text" size="50" name="cust_name" id="cust_name" value="MULTI TERMINAL INDONESIA PT." readonly /><input type="text" size="5" name="cust_no" id="cust_no" value="106659" readonly="readonly"/></td>
	  </tr>
	  <tr>
	    <td class="form-field-caption" align="right">N.P.W.P</td>
		<td class="form-field-caption">:</td>
		<td class="form-field-caption" colspan="5"><input type="text" size="30" name="cust_tax_no" id="cust_tax_no" value="02.106.620.4-093.000" readonly="readonly"/></td>
	  </tr>
	  <tr>
	    <td class="form-field-caption" align="right">Alamat</td>
		<td class="form-field-caption">:</td>
		<td class="form-field-caption" colspan="5"><textarea cols="35" rows="1" name="cust_addr" id="cust_addr" readonly="readonly"/>JL.PULAU PAYUNG 1 PELABUHAN TANJUNG PRIOK,JAKARTA UTARA</textarea></td>
	  </tr>
	  <tr>
	    <td class="form-field-caption" align="right">Periode Kegiatan</td>
		<td class="form-field-caption">:</td>
		<td class="form-field-caption" colspan="5"><input type="text" size="10" name="start_date" id="start_date" /> s.d. <input type="text" size="10" name="end_date" id="end_date" /></td>
	  </tr>
	</table>
	<br>
	<hr width="870" color="#e1e0de"></hr>
	<br>
	<div align="right"><button onclick="validasi()"><img src="<?=HOME;?>images/preview_nota_bhd.png"/></button></div>
</div>

