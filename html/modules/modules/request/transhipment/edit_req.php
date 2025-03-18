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
<?php
	$db=getDb();
	$req=$_GET['no_req'];
	$qr="SELECT SHIPPING_LINE, ALAMAT, NPWP, VESSEL, VOYAGE, E_I, TGL_REQUEST, NO_UKK, USER_REQ, STATUS, NO_BC_12, NO_SURAT_TRS, VESSEL_DT, VOYAGE_DT, NO_UKK_DT, KD_PELANGGAN, OI, DECODE(OI,'O','Ocean Going','I','Intersuler') AS KET_OI, KETERANGAN from REQ_TRANSHIPMENT_H A WHERE ID_REQ='$req'";
	//print_r($qr);die;
	$er=$db->query($qr);
	$hr=$er->fetchRow();
?>
<script type='text/javascript'>
$(document).ready(function() 
{	
	//open at first
	var v_req="<?=$req?>";
	var oukk="<?=$hr['NO_UKK']?>";
	var nukk="<?=$hr['NO_UKK_DT']?>";
	var oi="<?=$hr['OI']?>";
	$('#detail_req').load("<?=HOME?>request.transhipment.ajax/detail_req?id="+v_req+"&oukk="+oukk+"&nukk="+nukk+"&oi="+oi);
	//$('#detail_container').load("<?=HOME?>request.transhipment.ajax/list_container?id="+v_req);
	//======================================= autocomplete vessel==========================================//
	$( "#ovessel" ).autocomplete({
		minLength: 3,
		source: "request.transhipment.auto/vessel",
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
		source: "request.transhipment.auto/vessel",
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
	//======================================= autocomplete EMKL==========================================//
	$( "#emkl" ).autocomplete({
		minLength: 3,
		source: "request.transhipment.auto/emkl",
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

function final_add(i,j,k)
{	
	var url="<?=HOME;?>request.transhipment.ajax/update_req";
	var url2="<?=HOME;?>request.transhipment.ajax/add_detail_req";
	var emkl=$( "#emkl" ).val();
	var idemkl=$( "#idemkl" ).val();
	var inst=$( "#inst" ).val();
	var bc12=$( "#bc12" ).val();
	var ket=$( "#keterangan" ).val();
	var arr = [];
	$("#box2View > option").each(function(){arr.push(this.value);});
	var list = arr.sort().toString().replace(/,/g,"");	//sort array, convert to string then replace all commas : 1,2,3,4, => 1234
	
	if( emkl=='' || idemkl=='')
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
	else if(list=='')
	{
		alert('Pilih Container terlebih dahulu!');
		return false;
	}
	else
	{
		question = confirm("data akan disimpan, cek apakah data sudah benar?")
		if (question != "0") {		
			$.post(url,{ID_REQ: i, EMKL: emkl, IDEMKL:idemkl, INST:inst, BC12:bc12, KET: ket},function(data){	
				//alert(data);
				if (data=='OK')
				{
					$.post(url2,{LIST_CONT: arr, REQ:i, OUKK:j, NUKK:k},function(data2){	
						alert(data2);
						if(data2=='OK') {
							window.onbeforeunload = "null";
							window.location="<?=HOME?>request.transhipment/";
							//$('#detail_container').load("<?=HOME?>request.transhipment.ajax/list_container?id="+i);
						}
					});
				}
			});
		}
	}
}

// function del(a,b)
// {
	// var url="<?=HOME;?>request.transhipment.ajax/del_cont";
	// $.post(url,{NO_CONT:b, NO_REQ:a},function(data){	
		// alert(data);
		// $('#detail_container').load("<?=HOME?>request.transhipment.ajax/list_container?id="+a);
	// });
// }
</script>

<div class="content">
	<p>
	<img src="<?=HOME?>images/delivery.png" height="5%" width="5%" style="vertical-align:middle"> <b> <font color='#69b3e2' size='4px'>Edit Request</font> </b>
	 <font color='#888b8d' size='4px'>
	 Transhipment
	 </font>
	
	<p><br/>
	  </p>
	
	<hr width="870" color="#e1e0de"></hr><p><br/></p>
	
	<table>
	 <tr>
		<td class="form-field-caption" align="right">No Request</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="20" name="id_req" id="id_req" value="<?=$req?>" readonly="readonly" style="background-color:#f9151f; color:white;font-weight:bold;text-align:center"/>
		</td>
		<td></td>
		<td class="form-field-caption" align="right">Date Request</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="20" id="tgl_req" name="tgl_req" value="<?=$hr['TGL_REQUEST'];?>" readonly="readonly"/>
		</td>
	</tr>
	<tr>
		<td class="form-field-caption" align="right">Jenis Perdagangan</td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<input type="text" size="20" id="tipe_oi" name="tipe_oi" value="<?=$hr['KET_OI'];?>" readonly="readonly"/>		
		</td>
	</tr>
	<tr>
		<td class="form-field-caption" align="right">Customer</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="25" name="emkl" id="emkl" value="<?=$hr['SHIPPING_LINE'];?>" style="background-color:#FFFFCC;" />
		<input type="hidden" size="40" name="idemkl" id="idemkl" value="<?=$hr['KD_PELANGGAN'];?>" style="background-color:#FFFFCC;" />
		</td>
	</tr>
	<tr>
		<td class="form-field-caption" align="right">No. Instruksi </td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="18" name="inst" id="inst" value="<?=$hr['NO_SURAT_TRS'];?>" />
		</td>
		<td>&nbsp;</td>
	</tr>	
	<tr>
		<td class="form-field-caption" align="right">BC 1.2</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="18" name="bc12" id="bc12" value="<?=$hr['NO_BC_12'];?>" />
		</td>
		<td>&nbsp;</td>
     </tr>
	 <tr>
		<td class="form-field-caption" align="right">Old Vessel</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="25" name="ovessel" id="ovessel" value="<?=$hr['VESSEL'];?>" readonly="readonly" style="background-color:#FFFFCC;" />
		<input type="text" size="10" name="ovoy" id="ovoy" value="<?=$hr['VOYAGE'];?>" readonly="readonly"/> <input type="hidden" name="oukk" id="oukk" value="<?=$hr['NO_UKK'];?>" /> 
		</td>
	</tr>
	 <tr>
		<td class="form-field-caption" align="right">New Vessel</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="25" name="nvessel" id="nvessel" value="<?=$hr['VESSEL_DT'];?>" readonly="readonly" style="background-color:#FFFFCC;" />
		<input type="text" size="10" name="nvoy" id="nvoy" value="<?=$hr['VOYAGE_DT'];?>" readonly="readonly"/> <input type="hidden" name="nukk" id="nukk" value="<?=$hr['NO_UKK_DT'];?>" /> 
		</td>
	</tr>
	<tr>
		<td class="form-field-caption" align="right">Keterangan</td>
		<td class="form-field-caption" align="right">:</td>
		<td><textarea id="keterangan" name="keterangan" cols="30" style="background-color:#FFFFFF;" rows="2" ><?=$hr['KETERANGAN'];?></textarea>
		</td>
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

