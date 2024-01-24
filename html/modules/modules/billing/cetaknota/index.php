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
.ganjil {
  background-color: #FFF; /* Warna untuk baris ganjil */
}
.genap {
  background-color: #bbe3fe; /* Warna untuk baris genap */
}   

</style>

<script type="text/javascript">
function pay()
{
	
	var nonota_ = $('#invoice_numb').val();
	$('#pay').load("<?=HOME?>billing.cetaknota.ajax/payment?idn="+nonota_).dialog({modal:true, height:250,width:350, title : "Payment cash"});
}


$(document).ready(function() 
{	
	$( "#invoice_numb" ).autocomplete({
		minLength: 3,
		source: "billing.cetaknota.auto/invoicenumber",
		focus: function( event, ui ) 
		{
			$( "#invoice_numb" ).val( ui.item.NO_NOTA);
			return false;
		},
		select: function( event, ui ) 
		{
			if (ui.item.PAYMENT == 'Y'){
				alert('Nota Sudah Payment');
			}  else {
				$( "#invoice_numb" ).val( ui.item.NO_NOTA);
				$( "#vessel" ).val( ui.item.VESSEL);
				$( "#voyin" ).val( ui.item.VOYIN);
				$( "#voyout" ).val( ui.item.VOYOUT);
				$( "#id_req" ).val( ui.item.ID_REQ);
				$( "#cust_name" ).val( ui.item.CUST_NAME);
				$( "#total" ).val( ui.item.KREDIT);
				$( "#jenis" ).val( ui.item.JENIS);
			}
			
			
			
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a align='center'>" + item.NO_NOTA + " " +item.JENIS+" <br> "+ item.CUST_NAME+" <br>"+ item.KREDIT +"</a>")
		.appendTo( ul );
	
	};
	//======================================= autocomplete vessel==========================================//
	
});


</script>

<div class="content">
	<div class="main_side">
	  <fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px;align=center"> 
		 <table>
			<tr height="10">
				<td colspan="3"</td>
			</tr>
			<tr>
				<td width="120"></td>
				<td align="center" colspan="2">
					<font size="6" > <img src="<?=HOME?>images/crdc.png" style="vertical-align:middle"> Invoice Number </font></b><br>
				</td>
			</tr>
			<tr>
				<td width="120"></td>
				<td align="center" colspan="2">
					<font size="1"><input style="font-size:40px; font-weight:bold;" id="invoice_numb" name="invoice_numb" size="30" title="entry" class="suggestuwriter" type="text" maxlength="20" />
				  </font></b><br>
				</td>
			</tr>
			<tr>
				<td width="120"></td>
				<td align="right"><b>Customer Name</b></td>
				<td align="left">
					<font size="1"><input style="font-size:20px; font-weight:bold;" id="cust_name" name="cust_name" size="40" title="entry" class="suggestuwriter" type="text"/>
				  </font></b><br>
				</td>
			</tr>
			<tr>
				<td width="120"></td>
				<td align="right"><b>Jenis Nota</b></td>
				<td align="left">
					<font size="1"><input style="font-size:20px; font-weight:bold;" id="jenis" name="jenis" size="20" title="entry" class="suggestuwriter" type="text" maxlength="20" />
				  </font></b><br>
				</td>
			</tr>
			<tr>
				<td width="120"></td>
				<td align="right"><b>No Request</b></td>
				<td align="left">
					<font size="1"><input style="font-size:20px; font-weight:bold;" id="id_req" name="id_req" size="25" title="entry" class="suggestuwriter" type="text" />
				  </font></b><br>
				</td>
			</tr>
			<tr>
				<td width="120"></td>
				<td align="right"><b>Vessel / Voyage</b></td>
				<td align="left">
					<font size="1"><input style="font-size:20px; font-weight:bold;" id="vessel" name="vessel" size="30" title="entry" class="suggestuwriter" type="text" maxlength="20" /> <input style="font-size:20px; font-weight:bold;" id="voyin" name="voyin" size="5" title="entry" class="suggestuwriter" type="text" maxlength="5" /><input style="font-size:20px; font-weight:bold;" id="voyout" name="voyout" size="5" title="entry" class="suggestuwriter" type="text" maxlength="5" />
				  </font></b><br>
				</td>
			</tr>
			<tr>
				<td width="120"></td>
				<td align="right"><b>Total</b></td>
				<td align="left">
					<font size="1"><input style="font-size:20px; font-weight:bold;" id="total" name="total" size="20" title="entry" class="suggestuwriter" type="text" maxlength="20" />
				  </font></b><br>
				</td>
			</tr>
			<tr height="10">
				<td colspan="3"></td>
			</tr>
			<tr>
				<td width="120"></td>
				<td align="center" colspan="2">
					<input type="button" value=" P A Y M E N T   C A S H " onclick="pay()"> </input></font></b><br>
				</td>
			</tr>
			<tr height="10">
				<td colspan="3"></td>
			</tr>
		</table>		
		</fieldset>
		<br>
	</div>
	<form id="mainform">
	<div id='pay'></div>
	
	</form>
</div>