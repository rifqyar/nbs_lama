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
	background: #80dffb;
	box-shadow: 0 2px 4px rgba(0,0,0,0.5), inset 0 1px rgba(255,255,255,0.3), inset 0 10px rgba(255,255,255,0.2), inset 0 10px 20px rgba(255,255,255,0.25), inset 0 -15px 30px rgba(0,0,0,0.3);

	-o-box-shadow: 0 2px 4px rgba(0,0,0,0.5), inset 0 1px rgba(255,255,255,0.3), inset 0 10px rgba(255,255,255,0.2), inset 0 10px 20px rgba(255,255,255,0.25), inset 0 -15px 30px rgba(0,0,0,0.3);

	-webkit-box-shadow: 0 2px 4px rgba(0,0,0,0.5), inset 0 1px rgba(255,255,255,0.3), inset 0 10px rgba(255,255,255,0.2), inset 0 10px 20px rgba(255,255,255,0.25), inset 0 -15px 30px rgba(0,0,0,0.3);
	-moz-box-shadow: 0 2px 4px rgba(0,0,0,0.5), inset 0 1px rgba(255,255,255,0.3), inset 0 10px rgba(255,255,255,0.2), inset 0 10px 20px rgba(255,255,255,0.25), inset 0 -15px 30px rgba(0,0,0,0.3);

	
}

</style>
<script type='text/javascript'>
//var neg1;
function save_req()
{
	var url2="<?=HOME;?>billing.payment_admin.ajax/save_request";
	var nc = $( "#no_container" ).val();
	var io = $( "#io" ).val();
	var payment = $( "#payment" ).val();
	var date_doc = $( "#date_doc" ).val();
	var paid_thru = $( "#paid_thru" ).val();
	var remark = $( "#remark" ).val();
	$.post(url2,{NC: nc, IO: io, PAYMENT: payment, PAID_THRU: paid_thru, REMARK : remark, DATE_DOC:date_doc},function(data){	
			if(data){
				alert(data);
			}
	});
}

$(document).ready(function() 
{	
	//======================================= autocomplete vessel==========================================//
	$( "#no_container" ).autocomplete({
		minLength: 3,
		source: "billing.payment_admin.auto/container",
		focus: function( event, ui ) 
		{
			$( "#no_container" ).val( ui.item.ETG_PRE_CONTNO);
			return false;
		},
		select: function( event, ui ) 
		{
			$( "#payment" ).val( ui.item.PAY_FLG);
			$( "#paid_thru" ).val( ui.item.PAID_THRU);
			$( "#io" ).val( ui.item.ETG_PRE_IOMODE);
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a align='center'>" + item.ETG_PRE_CONTNO + " " +item.ETG_PRE_VESSEL+" - "+ item.ETG_PRE_VOYAGE+"</a>")
		.appendTo( ul );
	
	};
	//======================================= autocomplete vessel==========================================//
	
	$("#paid_thru").datepicker({
			dateFormat: 'dd-mm-yy'
            });
			
	$("#date_doc").datepicker({
			dateFormat: 'dd-mm-yy'
            });		
			
			
	
});

</script>

<div class="content">
	<p>
	<img src="<?=HOME?>images/delivery.png" height="5%" width="5%" style="vertical-align:middle"> <b> <font color='#69b3e2' size='4px'>Admin</font> </b>
	 <font color='#888b8d' size='4px'>
	 Payment
	 </font>
	
	<p><br/>
	  </p>
	
	<hr width="870" color="#e1e0de"></hr><p><br/></p>
	
	<table>
	<tr>
		<td class="form-field-caption" align="right">No Container</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="25" name="no_container" id="no_container"  style="background-color:#FFFFCC;" />
		<input type="hidden" size="25" name="io" id="io"  style="background-color:#FFFFCC;" />
		</td>
		<td></td>
		<td class="form-field-caption" align="right">Paid Thru</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="20" id="paid_thru" name="paid_thru" value=""/>
		</td>

		<td class="form-field-caption" align="right">Date Document</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="20" id="date_doc" name="date_doc" value=""/>
		</td>

		
	</tr>	
	
	<tr>
		<td class="form-field-caption" align="right">Payment</td>
		<td class="form-field-caption" align="right">:</td>    
		<td>
		<select id="payment" name="payment">
		<option value="Y">Yes</option>
		<option value="N">No</option>
		</select>
		</td>
		<td> </td>
		<td class="form-field-caption" align="right">Keterangan</td>
		<td class="form-field-caption" align="right">:</td>
		<td><textarea id="remark" name="remark" cols="30" style="background-color:#FFFFFF;" rows="2" ></textarea>
		</td>
	</tr>     
        
	 <tr>
		<td colspan="7"><button onclick="save_req()" id="but_create">Save</button></td>
     </tr>
	</table>
	</form>
	<br>
	<hr width="870" color="#e1e0de"></hr>
	<br>
	<div>
		<div id="detail_req"></div>
		<br>
		<div id="detail_container"></div>
	</div>
	<br/>
	<br/>
	
</div>

