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
var neg2;
function create_req()
{
	var url="<?=HOME;?>request.reexport.ajax/save_req";
	var tipe_oi=$( "#tipe_oi" ).val();
	var oves=$( "#ovessel" ).val();
	var ovoy=$( "#ovoy" ).val();
	var oukk=$( "#oukk" ).val();
	var nves=$( "#nvessel" ).val();
	var nvoy=$( "#nvoy" ).val();
	var nukk=$( "#nukk" ).val();
	var emkl=$( "#emkl" ).val();
	var idemkl=$( "#idemkl" ).val();
	var inst=$( "#inst" ).val();
	var bc12=$( "#bc12" ).val();
	var ket=$( "#keterangan" ).val();
	
	if( tipe_oi=='')
	{
		alert('Pilih Jenis Perdagangan!!!');
		return false;
	}
	else if( emkl=='' || idemkl=='')
	{
		alert('Harap Entry Customer / Data Customer tidak valid');
		return false;
	}
	else if( inst=='')
	{
		alert('Harap Entry Nomor Instruksi');
		return false;
	}
	else if( bc12=='')
	{
		alert('Harap Entry BC 1.2');
		return false;
	}
	else if( oves=='' || oukk=='' )
	{
		alert('Harap Entry Ex-Vessel / Data tidak valid');
		return false;
	}
	else if( nves=='' || nukk=='')
	{
		alert('Harap Entry Dest Vessel / Data tidak valid');
		return false;
	}
	else
	{
		//alert('e');
		$.post(url,{TIPE_OI: tipe_oi,EMKL: emkl,IDEMKL:idemkl, INST:inst, BC12:bc12, OVES:oves, OVOY:ovoy, OUKK:oukk, NVES:nves, NVOY:nvoy, NUKK:nukk, KET: ket},function(data){	
			//alert(data);
			var row_data = data;
			var explode = row_data.split(',');
			var v_msg = explode[0];
			var v_req = explode[1];
			if (v_msg!='OK')
			{
				alert('Request gagal : '+v_msg);
				return false;
			}
			else
			{
				$( "#id_req" ).val(v_req);
				document.getElementById("id_req").readOnly = true;
				document.getElementById("tipe_oi").disabled = true;
				document.getElementById("emkl").readOnly = true;
				document.getElementById("inst").readOnly = true;
				document.getElementById("bc12").readOnly = true;
				document.getElementById("ovessel").readOnly = true;
				document.getElementById("nvessel").readOnly = true;
				document.getElementById("keterangan").readOnly = true;
				document.getElementById("but_create").disabled = true;
				$('#detail_req').load("<?=HOME?>request.reexport.ajax/detail_req?id="+v_req+"&oukk="+oukk+"&nukk="+nukk+"&oi="+tipe_oi);
				//$('#detail_container').load("<?=HOME?>request.reexport.ajax/list_container?id="+v_req);
			}	
		});
	}
}

$(document).ready(function() 
{	
	//======================================= autocomplete vessel==========================================//
	$( "#ovessel" ).autocomplete({
		minLength: 3,
		source: "request.reexport.auto/vessel",
		focus: function( event, ui ) 
		{
			$( "#ovessel" ).val( ui.item.NM_KAPAL);
			return false;
		},
		select: function( event, ui ) 
		{
			$( "#ovessel" ).val( ui.item.NM_KAPAL);
			$( "#ovoy" ).val( ui.item.VOYAGE);
			$( "#oukk" ).val( ui.item.NO_UKK);
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a align='center'>" + item.NM_KAPAL + " " +item.VOYAGE+" <br>"+ item.NO_UKK +"</a>")
		.appendTo( ul );
	
	};
	
	$( "#nvessel" ).autocomplete({
		minLength: 3,
		source: "request.reexport.auto/vessel",
		focus: function( event, ui ) 
		{
			$( "#nvessel" ).val( ui.item.NM_KAPAL);
			return false;
		},
		select: function( event, ui ) 
		{
			$( "#nvessel" ).val( ui.item.NM_KAPAL);
			$( "#nvoy" ).val( ui.item.VOYAGE);
			$( "#nukk" ).val( ui.item.NO_UKK);
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a align='center'>" + item.NM_KAPAL + " " +item.VOYAGE+" <br>"+ item.NO_UKK +"</a>")
		.appendTo( ul );
	
	};
	//======================================= autocomplete vessel==========================================//
	
	//======================================= autocomplete EMKL==========================================//
	$( "#emkl" ).autocomplete({
		minLength: 3,
		source: "request.reexport.auto/emkl",
		focus: function( event, ui ) 
		{
			$( "#emkl" ).val( ui.item.NAMA_PERUSAHAAN);
			return false;
		},
		select: function( event, ui ) 
		{
			$( "#emkl" ).val( ui.item.NAMA_PERUSAHAAN);
			$( "#idemkl" ).val( ui.item.KD_PELANGGAN);
			
			
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a align='center'>" + item.NAMA_PERUSAHAAN + "<br>" +item.ALAMAT_PERUSAHAAN+"</a>")
		.appendTo( ul );
	
	};
	//======================================= autocomplete EMKL==========================================//
	
	window.onbeforeunload = function () {
	return 'Your content has not been properly saved yet!';
	};
});

function sync_pelanggan(){
	var url="<?=HOME;?>request.delivery.sp2.ajax/sync_pelanggan";
	var tees='tes';
	$.post(url,{tes:tees},function(data){	
		alert('sukses');
	});
}

function final_add(i,j,k)
{	
	var url="<?=HOME;?>request.reexport.ajax/add_detail_req";
	var arr = [];
	$("#box2View > option").each(function(){arr.push(this.value);});
	var list = arr.sort().toString().replace(/,/g,"");	//sort array, convert to string then replace all commas : 1,2,3,4, => 1234
	if(list=='')
	{
		alert('Pilih Container terlebih dahulu!');
		return false;
	}
	else
	{
		question = confirm("data akan disimpan, cek apakah data sudah benar?")
		if (question != "0") {
			$.post(url,{LIST_CONT: arr, REQ:i, OUKK:j, NUKK:k},function(data){	
				alert(data);
				if(data=='OK') {
					window.onbeforeunload = "null";
					window.location="<?=HOME?>request.reexport/";
					//$('#detail_container').load("<?=HOME?>request.reexport.ajax/list_container?id="+i);
				}
			});
		}
	}
}

</script>

<div class="content">
	<p>
	<img src="<?=HOME?>images/delivery.png" height="5%" width="5%" style="vertical-align:middle"> <b> <font color='#69b3e2' size='4px'>Request</font> </b>
	 <font color='#888b8d' size='4px'>
	 Reexport
	 </font>
	
	<p><br/>
	  </p>
	
	<hr width="870" color="#e1e0de"></hr><p><br/></p>
	
	<table>
	 <tr>
		<td class="form-field-caption" align="right">No Request</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="20" name="id_req" id="id_req" readonly="readonly" style="background-color:#f9151f; color:white;font-weight:bold;text-align:center"/>
		</td>
		<td></td>
		<td class="form-field-caption" align="right">Date Request</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="20" id="tgl_req" name="tgl_req" value="<?=date('d-m-Y H:i');?>" readonly="readonly"/>
		</td>
	</tr>
	<tr>
		<td class="form-field-caption" align="right">Jenis Perdagangan</td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<select id="tipe_oi" name="tipe_oi">
				<option value="">-Pilih-</option>
				<option value="O">Ocean Going</option>
				<option value="I">Intersuler</option>
			</select>		
		</td>
	</tr>
	<tr>
		<td class="form-field-caption" align="right">Customer</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="40	" name="emkl" id="emkl" style="background-color:#FFFFCC;" />
		<input type="hidden" size="40" name="idemkl" id="idemkl" style="background-color:#FFFFCC;" />
		<button onclick="sync_pelanggan()" title="sync data pelanggan"><img src="<?=HOME;?>images/sync.png" width="10" height="10"/></button>
		</td>
	</tr>
	<tr>
		<td class="form-field-caption" align="right">No. Instruksi </td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="18" name="inst" id="inst" />
		</td>
		<td>&nbsp;</td>
	</tr>	
	<tr>
		<td class="form-field-caption" align="right">BC 1.2</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="18" name="bc12" id="bc12" />
		</td>
		<td>&nbsp;</td>
     </tr>
	 <tr>
		<td class="form-field-caption" align="right">Old Vessel</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="25" name="ovessel" id="ovessel"  style="background-color:#FFFFCC;" />
		<input type="text" size="10" name="ovoy" id="ovoy" readonly="readonly"/> <input type="hidden" name="oukk" id="oukk" /> 
		</td>
	</tr>
	 <tr>
		<td class="form-field-caption" align="right">New Vessel</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="25" name="nvessel" id="nvessel"  style="background-color:#FFFFCC;" />
		<input type="text" size="10" name="nvoy" id="nvoy" readonly="readonly"/> <input type="hidden" name="nukk" id="nukk" /> 
		</td>
	</tr>
	 <tr>
		<td class="form-field-caption" align="right">Keterangan</td>
		<td class="form-field-caption" align="right">:</td>
		<td><textarea id="keterangan" name="keterangan" cols="30" style="background-color:#FFFFFF;" rows="2" ></textarea>
		</td>
	</tr>
	 <tr>
		<td colspan="7"></td>
     </tr> 
	
	 <tr>
		<td colspan="7"><button onclick="create_req()" id="but_create"><img src="<?=HOME;?>images/create_req.png"/></button></td>
     </tr>
	</table>
	</form>
	<br>
	<hr width="870" color="#e1e0de"></hr>
	<br>
	<div>
		<div id="detail_req"></div>
		<!--<br>
		<div id="detail_container"></div>-->
	</div>
	<br/>
	<br/>
	
</div>

