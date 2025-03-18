<style>
.content{
	width:95%;
	margin-left:auto;
	margin-right:auto;
	margin-bottom: 10px;
	margin-top:20px;

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

<script type="text/javascript">
setInterval(function()
		{
			jQuery('#list_cont_js').jqGrid('setSelection', '0');
		},
		2000);

	function cek_text()
	{
		var f;
		if($("#no_cont").val()=="")
		{
			alert("Nomor container harus diisi");
			return false;
		}
		else if($("#no_cont").val().length < 11)
		{
			alert("Harap cek nomor container!!!");
			return false;
		}
		else if(berat=="")
		{
			alert("Berat harus diisi");
			return false;
		}
		else if(vessel=="")
		{
			alert("Vessel ~ Voyage harus diisi");
			return false;
		}
		else
		{
			return warn();
		}
	}
	
	function warn()
	{
		var r=confirm("Data will be saved, are you sure??");
		if(r==true)
		{
			alert("Well Done, Discharge Success");
			return true;
		}
		else
		{
			return false;
		}
	
	}

</script>

<script type='text/javascript'>
var my_cont='';
var my_size='';
var my_tipe='';
var my_status='';
var my_req='';
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



function isNumberKey(evt)
{
	var charCode = (evt.which) ? evt.which : event.keyCode;

	if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		return false;
	}
		
	return true;
}

function find_cont()
{
	if(my_cont=='')	
	{
		alert('container not found,please push sync container');	
		return false;
	}
	else
	{
		
		$( "#no_cont" ).val(my_cont);
		$( "#no_ukk" ).val(my_ukk);
		$( "#size" ).val(my_size);
		$( "#tipe" ).val(my_tipe);
		$( "#status" ).val(my_status);
		$( "#hz" ).val(my_hz);
		$( "#iso_code" ).val(my_iso);
		$( "#height" ).val(my_height); 
		$( "#carrier" ).val(my_carrier);
		$( "#vessel" ).val(my_vessel);
		$( "#voyage" ).val(my_voyage);
		$( "#voyage_out" ).val(my_voyage_out);
		$( "#pel_asal" ).val(my_pel_asal);
		$( "#pel_tujuan" ).val(my_pel_tuj);
		$( "#berat" ).val(my_berat);
		$( "#seal" ).val(my_seal_id);
		return true;
	}
}
			
function print_js()
{
	$("#print_jobslip").printElement();
	$("#add_container").dialog("close");
}


	$(document).ready(function() 
	{
		/*
		$("#finds").contextMenu({
					menu: 'myMenu'
				},
					function(action, el, pos)
					{
						$.blockUI({ message: '<h1><br>Please wait...Syncrhonize Container</h1><br><img src={$HOME}images/loadingbox.gif /><br><br>' });
						var url="<?=HOME?>operational.gate_in.add_container/sync";
						$.post(url,{},function(data) 
						{
							alert('ok');
						});
					var ccnt=$("#NO_CONTAINER").val();	
					$('#add_container').html('<table width="100%" height"100%"><tr><td bgcolor="white"><p align="center"><img src="<?=HOME?>images/loading_gud.gif" width="55%" height="90%"/></p></td></tr></table>').dialog({modal:true, height:200,width:450});
					$('#add_container').load("<?=HOME?>operational.gate_in.add_container/sync").dialog({modal:true, height:200,width:450});
				});
		*/		
		
        $( "#NO_CONTAINER" ).autocomplete({
			minLength: 3,
			source: "operational.discharge.auto/container",
			focus: function( event, ui ) 
			{
				$( "#NO_CONTAINER" ).val( ui.item.NO_CONTAINER);
				return false;
			},
			select: function( event, ui ) 
			{
				/*$( "#no_cont" ).val( ui.item.NO_CONTAINER);
				$( "#size" ).val( ui.item.UKURAN);
				$( "#tipe" ).val( ui.item.TYPE_);
				*/
				
				my_cont=ui.item.NO_CONTAINER;
				my_ukk=ui.item.NO_UKK;
				my_size=ui.item.SIZE_;
				my_tipe=ui.item.TYPE_;
				my_status=ui.item.STATUS;
				my_hz=ui.item.HZ;
				my_iso=ui.item.ISO_CODE;
				my_height=ui.item.HEIGHT;
				my_carrier=ui.item.CARRIER;
				my_vessel=ui.item.NM_KAPAL;
				my_voyage=ui.item.VOYAGE_IN;
				my_voyage_out=ui.item.VOYAGE_OUT;
				my_pel_asal=ui.item.NM_PELABUHAN_ASAL;
				my_pel_tuj=ui.item.NM_PELABUHAN_TUJUAN;
				my_berat=ui.item.BERAT;
				my_seal_id=ui.item.SEAL_ID;
								
				
				return false;
			}
		})
		.data( "autocomplete" )._renderItem = function( ul, item ) 
		{
			return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + item.NO_CONTAINER + " <br> " + item.SIZE_+ " / "+item.TYPE_+" / "+item.STATUS+"<BR>"+item.NM_KAPAL+" / "+item.VOYAGE_IN+"-"+item.VOYAGE_OUT+"</a>")
			.appendTo( ul );
		
		};
	});


</script>
<script src="<?=HOME?>context_menu/jsprint.js" type="text/javascript"></script>


<div class="content" id="finds">
	<p>
	<h2> <img src="<?=HOME?>images/discharge2.png" height="5%" width="5%" style="vertical-align:middle"> Discharge Confirm </h2></p>
	
	<p><br/>
	  </p>
	<form method="post" action="<?=HOME?>operational.discharge.auto/save" onSubmit="return cek_text()" > 
	<table>
	
		<tr>
				<td class="form-field-caption" valign="middle" align="right" width="125">NO CONTAINER</td>
				<td valign="middle" class="form-field-input">: </TD>
				<td valign="middle" class="form-field-input">
				<input style="font-size:26px; font-weight:bold;" id="NO_CONTAINER" name="NO_CONTAINER" size="15" title="entry" class="suggestuwriter" type="text" maxlength="16" value="" /> &nbsp; <input type="button" name="find_c" id="find_c" value="FIND" onclick="find_cont()" style="height:35px; width:50px;" class="context-menu-one	">&nbsp;&nbsp;</TD>
			<!--	<td valign="middle" ALIGN="left" class="form-field-input"><button onclick="sync()" style="height:35px; width:50px;"><img src='images/Refresh2.png' width=20px height=20px border='0'></button></td>
				<td valign="middle" ALIGN="right" class="form-field-input" width="350">
					<button onclick="help()" style="height:35px; width:50px;"><img src='images/help2.png' width=30px height=30px border='0'></button>
				</td> 
		</tr>
		<TR>
			<TD colspan="3" align="right"><b><i><font color="red">click syncrhonize</font> - if container unavailable to discharge</i></b></TD>
		</TR> -->
	</table>
	<hr width="870" color="#e1e0de"></hr><p><br/></p>
	<!--<form method="post" action="<?=HOME?>operational.discharge/save" onSubmit='return cek_text()'>   -->
	<table>
	<tr>
		<td class="form-field-caption" align="right">No UKK</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="15" maxlength="11" name="no_ukk" id="no_ukk" style="background-color:#FFFFFF;" readonly="readonly" />
		
		</td>
		<td></td>
		<td class="form-field-caption" align="right">Height</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="15" maxlength="15" id="height" name="height" style="background-color:#FFFFFF;" readonly="readonly" />
		</td>
	</tr>
	 <tr>
		<td class="form-field-caption" align="right">No Container</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="15" maxlength="11" name="no_cont" id="no_cont" style="background-color:#FFFFFF;" readonly="readonly" />
		</td>
		<td></td>
		<td class="form-field-caption" align="right">CARRIER</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="15" maxlength="15" id="carrier" name="carrier" style="background-color:#FFFFFF;" readonly="readonly" />
		</td>
	</tr>
	 <tr>
		<td class="form-field-caption" align="right">Size / Type / Status / HZ</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="5" name="size" id="size" style="background-color:#FFFFFF;" readonly="readonly" />
			<input type="text" size="5" name="tipe" id="tipe" style="background-color:#FFFFFF;" readonly="readonly" />
			<input type="text" size="5" name="status" id="status" style="background-color:#FFFFFF;" readonly="readonly" />&nbsp;
			<input type="text" size="5" name="hz" id="hz" style="background-color:#FFFFFF;" readonly="readonly" />
		</td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">Berat</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="5" name="berat" id="berat" readonly="readonly"/> Kgs
		</td>
     </tr>
	 
	 <TR>
		<td class="form-field-caption" align="right">ISO CODE</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="5" name="iso_code" id="iso_code" />
		</td>
		<td></td>
		<td class="form-field-caption" align="right">No Seal</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="30" name="seal" id="seal" />
		</td>
	 </TR>
	 <tr>
		<td class="form-field-caption" align="right">Vessel / Voyage</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="25" name="vessel" id="vessel" /> / <input type="text" size="5" name="voyage" id="voyage" readonly="readonly" />
			<input type="hidden" name="id_vessel" id="id_vessel" />
			<input type="hidden" name="id_vs" id="id_vs" />
		</td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">Tgl Discharge</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" readonly='readonly' size="20" name="tgl_gate_in" id="tgl_discharge" value="<?=date('d-m-Y H:i:s')?>" />
		</td>
     </tr>
	 <tr>
		<td class="form-field-caption" align="right">Pelabuhan Asal ~ Tujuan</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="15" name="pel_asal" id="pel_asal" readonly="readonly" />
			<input type="hidden" name="id_pel_asal" id="id_pel_asal" /> ~
			<input type="text" size="15" name="pel_tujuan" id="pel_tujuan" readonly="readonly" />
			<input type="hidden" name="pel_tujuan" id="pel_tujuan" />
		</td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">Remark </td>
		<td class="form-field-caption" align="right">:</td>
		<td><textarea type="text" readonly='readonly' size="20" name="remark" id="remark" value="" /></textarea>
		</td>
     </tr>
	 <tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	 <td>
		<input type="submit" value="Discharge Confirm"/>
		<!--<input type="button" value= "Discharge Confirm" onclick="js_created()"/>  -->
	 </td>
	 </tr>
	 
	</table>
	
	</form>
	<div id="add_container" ></div>
	<!--<div id="print_jobslip" ></div> -->
	<div id="help_d" ></div>
	<br>
	<hr width="870" color="#e1e0de"></hr>
	
	<br/>
	<!--<table id='list_cont_js' width="100%"></table> <div id='pg_l_cont'></div> -->
	<br/>
		
	
</div>
