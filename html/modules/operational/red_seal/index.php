<style>
.content{
	width:95%;
	margin-left:auto;
	margin-right:auto;
	margin-bottom: 10px;
	margin-top:20px;

}
.butsave {
    background: none repeat scroll 0 0 #CCCCCC;
    border-color: #CCCCCC black black #CCCCCC;
    border-style: solid;
    border-width: 2px;
	margin-bottom: 10px;
    font-family: Arial,Helvetica,sans-serif;
    font-size: 20px;
    padding: 4px 10px 3px 7px;
    width: 100%;
}

</style>

<script type='text/javascript'>
var my_cont='';
var my_size='';
var my_tipe='';
var my_status='';
var my_no_req='';
var my_hz='';
var my_vessel='';
var my_voyage='';
var my_pel_asal='';
var my_pel_tuj='';
var my_idpel_asal='';
var my_idpel_tuj='';
var v_blok='';
var v_slot='';
var v_row='';
var v_tier='';
var v_err='';
var my_h='';
var my_ukk;

function assign_seal()
{
	var ukk = $("#no_ukk").val();
	var bp_id = $("#bp_id").val();
	var nocont = $("#no_cont").val();
	var isocode = $("#iso_code").val();
    //alert(nocont);
	var url	= "<?=HOME?>operational.red_seal.ajax/update_seal";
	
	var r=confirm("Are you sure?");
	if (r==true)
	{
		$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?=HOME;?>images/103.gif width="30" height="30" /><br><br>' });
		$.post(url,{NO_UKK : ukk, BP_ID : bp_id, NO_CONT : nocont, ISO_CODE : isocode},function(data){
			console.log(data);
			if(data == "OK")
				{
					$.unblockUI({
					onUnblock: function(){ }
					});
					alert("Success...");
					window.location = "<?=HOME?>operational.red_seal/";
				}
			else
				{
					$.unblockUI({
					onUnblock: function(){ }
					});
					alert("Failed...");
					window.location = "<?=HOME?>operational.red_seal/";
				}
		});
	}
	else
	{
		return false;
	}	
}

function find_cont()
{
	if(my_cont=='')	
	{
		alert('container not found\nplease add container');	
		return false;
	}
	else
	{
		$( "#no_ukk" ).val(my_ukk);
		$( "#bp_id" ).val(my_bp_id);
		$( "#no_cont" ).val(my_cont);
		$( "#size" ).val(my_size);
		$( "#tipe" ).val(my_tipe);
		$( "#status" ).val(my_status);
		$( "#hz" ).val(my_hz);
		$( "#iso_code" ).val(my_iso);
		$( "#gross" ).val(my_gross);
		$( "#height_c" ).val(my_height);
		$( "#status_cont" ).val(my_status_cont);
		$( "#vessel" ).val(my_vessel);
		$( "#voyage" ).val(my_voyage);
		$( "#pol" ).val(my_pol);
		$( "#pod" ).val(my_pod);
		$( "#discharge_date" ).val(my_disch_date);
		$( "#red_seal" ).val(my_red_seal);
		$( "#carrier" ).val(my_carrier);
		$( "#bay" ).val(my_bay_no);
		$( "#row" ).val(my_bay_row);
		$( "#tier" ).val(my_bay_tier);
		return true;
	}
}

	$(document).ready(function() 
	{		
        $( "#NO_CONTAINER" ).autocomplete({
			minLength: 6,
			source: "operational.red_seal.auto/container",
			focus: function( event, ui ) 
			{
				$( "#NO_CONTAINER" ).val( ui.item.NO_CONTAINER);
				return false;
			},
			select: function( event, ui ) 
			{
				my_ukk=ui.item.NO_UKK;
				my_cont=ui.item.NO_CONTAINER;
				my_size=ui.item.SIZE_;
				my_tipe=ui.item.TYPE_;
				my_status=ui.item.STATUS_;
				my_hz=ui.item.HZ;
				my_height=ui.item.HEIGHT;
				my_status_cont=ui.item.STATUS_CONT;
				my_iso=ui.item.ISO_CODE;
				my_gross=ui.item.GROSS;
				my_height_c=ui.item.HEIGHT;
				my_vessel=ui.item.VESSEL;
				my_voyage=ui.item.VOYAGE;
				my_bp_id=ui.item.BP_ID;
				my_red_seal=ui.item.STATUS_SEGEL_MERAH;
				my_pol=ui.item.POL;
				my_pod=ui.item.POD;
				my_bay_no=ui.item.BAY_NO;
				my_bay_row=ui.item.BAY_ROW;
				my_bay_tier=ui.item.BAY_TIER;
				my_disch_date=ui.item.DISCHARGE_DATE;
				my_carrier=ui.item.CARRIER;
				my_red_seal=ui.item.STATUS_SEGEL_MERAH;
				return false;
			}
		})
		.data( "autocomplete" )._renderItem = function( ul, item ) 
		{
			return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a> " + item.NO_CONTAINER + " <br> " + item.SIZE_ + " / "+item.TYPE_+" / "+item.STATUS_+"<br>"+item.VESSEL+" / "+item.VOYAGE+"</a>")
			.appendTo( ul );
		
		};
	});

</script>

<div class="content" id="finds">
	<p>
	<h2> <img src="<?=HOME?>images/red_seal.png" height="5%" width="5%" style="vertical-align:middle">&nbsp;<font color="#0378C6">Red Seal</font> Import</h2>
	</p>	
	<p><br/>
	  </p>
	<table>	
		<tr>
				<td class="form-field-caption" valign="middle" align="right" width="125"><blink><font size="3" color="red">NO CONTAINER</font></blink></td>
				<td valign="middle" class="form-field-input">: </TD>
				<td valign="middle" class="form-field-input">
				<input maxlength="11" style="font-size:26px; font-weight:bold;" id="NO_CONTAINER" name="NO_CONTAINER" size="15" title="entry" class="suggestuwriter" type="text" maxlength="16" value="" /> &nbsp; <input type="button" name="find_c" id="find_c" value="FIND" onclick="find_cont()" style="height:35px; width:50px;" class="context-menu-one	">&nbsp;&nbsp;&nbsp;<b><i>(*Min. 6 character)</i></b></td>				
		</tr>
	</table>
	<br/>
	<hr width="875" color="#e1e0de"></hr><p><br/></p>
	
	<table>
	<tr>
		<td class="form-field-caption" align="right">No Container</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="15" maxlength="11" name="no_cont" id="no_cont" style="background-color:#FFFFFF;" readonly="readonly" />
		</td>
		<td></td>
		<td class="form-field-caption" align="right">Ship Position</td>
		<td class="form-field-caption" align="right">:</td>
		<td>Bay <input type="text" size="3" id="bay" name="bay" readonly="readonly"/>&nbsp;
			Row <input type="text" size="3" id="row" name="row" readonly="readonly"/>&nbsp;
			Tier <input type="text" size="3" id="tier" name="tier" readonly="readonly"/>
		</td>
	</tr>
	 <tr>
		<td class="form-field-caption" align="right">Size / Type / Status / HZ</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="5" name="size" id="size" style="background-color:#FFFFFF;" readonly="readonly" />
			<input type="text" size="5" name="tipe" id="tipe" style="background-color:#FFFFFF;" readonly="readonly" />
			<input type="text" size="5" name="status" id="status" style="background-color:#FFFFFF;" readonly="readonly" />&nbsp;
			<input type="text" size="3" name="hz" id="hz" style="background-color:#FFFFFF;" readonly="readonly" />
		</td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">Gross</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="5" name="gross" id="gross" readonly="readonly"/> Kgs
		</td>
     </tr>
	 <tr>
		<td class="form-field-caption" align="right">Height</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="5" name="height_c" id="height_c" readonly="readonly"/>
		</td>
		<td></td>
		<td class="form-field-caption" align="right">Discharge Date</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" readonly='readonly' size="20" name="discharge_date" id="discharge_date"  />
		</td>
	 </tr>
	 <tr>
		<td class="form-field-caption" align="right">ISO CODE</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="5" name="iso_code" id="iso_code" readonly="readonly"/>
		</td>
		<td></td>
		<td class="form-field-caption" align="right">Red Seal</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" readonly='readonly' size="2" name="red_seal" id="red_seal" readonly="readonly"/>
		</td>
	 </tr>
	 <tr>
		<td class="form-field-caption" align="right">Vessel / Voyage</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="20" name="vessel" id="vessel" readonly="readonly"/> / <input type="text" size="10" name="voyage" id="voyage" readonly="readonly"/>
			<input type="hidden" name="no_ukk" id="no_ukk" />
			<input type="hidden" name="bp_id" id="bp_id" />
		</td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">Status Cont</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" readonly='readonly' size="2" name="status_cont" id="status_cont" readonly="readonly"/>
		</td>
     </tr>
	 <tr>
		<td class="form-field-caption" align="right">Disch Port ~ Load Port</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="8" name="pod" id="pod" readonly="readonly" /> ~
			<input type="text" size="8" name="pol" id="pol" readonly="readonly" />
		</td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">Line Operator</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" readonly='readonly' size="5" name="carrier" id="carrier" readonly="readonly"/>
		</td>
     </tr>
	</table>
	<br/>
	<hr width="875" color="#e1e0de"></hr>
	<br/>
	<input id="id_SAVEBUT" class="butsave" type="button" onClick="assign_seal()" value="UPDATE RED SEAL">
</div>
