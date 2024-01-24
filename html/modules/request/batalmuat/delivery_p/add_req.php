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
//var neg1;
var neg2;
function create_req()
{
	var url="<?=HOME;?>request.delivery.sp2.ajax/save_req";
	var iemkl=$( "#idemkl" ).val();
	var almt=$( "#alamat" ).val();
	var npwp=$( "#npwp" ).val();
	var tglreq = $("#tgl_req").val();
	var nsppb=$( "#sppb" ).val();
	var tglsppb=$( "#tgl_sp2b" ).val();
	var ndo=$( "#do" ).val();
	var tgldo=$( "#tgl_do" ).val();
	var tgldel=$( "#tgl_delivery" ).val();
	var ship=$( "#ship" ).val();
	var via=$( "#dev_via" ).val();
	var ket=$( "#keterangan" ).val();
	
		
	if( iemkl=='')
	{
		alert('Entry EMKL please,..');
		return false;
	}
	else if ((ship=='O')&&( nsppb=='')) 
	{	
		alert('Entry SPPB please,..');
		return false;
	
	}
	else if ((ship=='O')&&( ndo=='')) 
	{	
		alert('Entry DO please,..');
		return false;
	}
	else if( tgldel=='')
	{
		alert('Entry Date Delivery please,..');
		return false;
	}
	else
	{
		//alert('e');
		$.post(url,{IDEMKL:iemkl,SPPB:nsppb, TGLSPPB:tglsppb, NDO:ndo, TGLDO:tgldo, TGLDEL:tgldel, SHIP:ship, VIA:via, KET:ket},function(data){	
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
				document.getElementById("ship").readOnly = true;
				document.getElementById("sppb").readOnly = true;
				document.getElementById("tgl_sp2b").readOnly = true;
				document.getElementById("do").readOnly = true;
				document.getElementById("tgl_do").disabled = true;
				document.getElementById("emkl").readOnly = true;
				document.getElementById("dev_via").readOnly = true;
				document.getElementById("keterangan").readOnly = true;
				document.getElementById("tgl_delivery").readOnly = true;
				document.getElementById('but_create').style.display = "none";
				$('#detail_req').load("<?=HOME?>request.delivery.sp2.ajax/detail_req?id="+v_req+"&ship="+ship);
				$('#detail_container').load("<?=HOME?>request.delivery.sp2.ajax/list_container?id="+v_req);
			}	
		});	
		
	}
}

function add_cont1(i,j)
{
	var nc=$('#nc').val();
	var sc=$('#sc').val();
	var tc=$('#tc').val();
	var stc=$('#stc').val();
	var hc=$('#hc').val();
	var comm=$('#icomm').val();
	var imo=$('#imo').val();
	var iso=$('#iso').val();
	var car=$('#car').val();
	var hgc=$('#hgc').val();
	var tmp=$('#temp').val();
	var ukk=$('#ukk').val();
	
	
	var url="<?=HOME;?>request.delivery.sp2.ajax/add_detail_req";
	if(nc=='')
	{
		alert('Entry container number please');
		return false;
	}
	else if((hc=='Y')&&(imo==''))
	{
		alert('Entry imo class please');
		return false;
	}
	else if((stc=='FCL')&&(comm==''))
	{
		alert('Entry commodity please...');
		return false;
	}
	else 
	{
		$.post(url,{NC: nc,SC:sc, TC:tc, STC:stc, HC:hc, COMM:comm, IMO:imo, ISO:iso, REQ:i, HGC:hgc, SHIP:j, CAR:car, TEMP:tmp,UKK:ukk},function(data){	
			alert(data);
			$('#detail_container').load("<?=HOME?>request.delivery.sp2.ajax/list_container?id="+i);
		});
	}
	//alert(nc);
}

$(document).ready(function() 
{	
	//======================================= autocomplete EMKL==========================================//
	$( "#emkl" ).autocomplete({
		minLength: 3,
		source: "request.anne.auto/emkl",
		focus: function( event, ui ) 
		{
			$( "#emkl" ).val( ui.item.NAMA_PERUSAHAAN);
			return false;
		},
		select: function( event, ui ) 
		{
			$( "#emkl" ).val( ui.item.NAMA_PERUSAHAAN);
			$( "#idemkl" ).val( ui.item.KD_PELANGGAN);
			$( "#alamat" ).val( ui.item.ALAMAT_PERUSAHAAN);
			$( "#npwp" ).val( ui.item.NO_NPWP);
			
			
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
	
	
	
	$("#tgl_delivery").datepicker({
			dateFormat: 'dd-mm-yy'
            });
	$("#tgl_sp2b").datepicker({
			dateFormat: 'dd-mm-yy'
            });
	$("#tgl_do").datepicker({
			dateFormat: 'dd-mm-yy'
            });
	
	window.onbeforeunload = function () {
	return 'Your content has not been properly saved yet!';
	};
});

function cek_iso()
{
	var sc=$('#sc').val();
	var tc=$('#tc').val();
	var hgc=$('#hgc').val();
	var url="<?=HOME;?>request.delivery.sp2.ajax/cek_cont_req";
	$.post(url,{SC:sc, TC:tc,HGC:hgc},function(data){	
		//alert(data);
		$('#iso').val(data);
	});
}

function del(j,i)
{
	var url="<?=HOME;?>request.delivery.sp2.ajax/del_cont";
	$.post(url,{REQ:i, CONT:j},function(data){	
		alert(data);
		$('#detail_container').load("<?=HOME?>request.delivery.sp2.ajax/list_container?id="+j);
	});
}
</script>

<div class="content">
	<p>
	<img src="<?=HOME?>images/delivery.png" height="5%" width="5%" style="vertical-align:middle"> <b> <font color='#69b3e2' size='4px'>Request SP2</font> </b>
	 <font color='#888b8d' size='4px'>
	 Delivery
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
		<td class="form-field-caption" align="right">Shipping</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="5" id="ship" name="ship" style="background-color:#FFFFCC; font-weight:bold;text-align:center"/><br>
		<b><i>O : Ocean Going - I : Intersuler</i></b></td>
	</tr>

	<br>

	<tr>
		<td class="form-field-caption" align="right">No S.P.P.B</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="25" name="sppb" id="sppb" value="" style="background-color:#FFFFFF;"/>
		<b><i>*Untuk Ocean Going Harus Diisi</i></b>
		</td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">Date SPPB</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="20" id="tgl_sp2b" name="tgl_sp2b" value=""/>
		</td>	
     </tr>
	 
	 <tr>
		<td class="form-field-caption" align="right">No D.O</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="25" name="do" id="do" style="background-color:#FFFFFF;"/>
		<b><i>*Untuk Ocean Going Harus Diisi</i></b>
		</td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">Date DO</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="20" id="tgl_do" name="tgl_do" value=""/>
		</td>
     </tr>
	 <tr>
		<td class="form-field-caption" align="right">EMKL</td>
		<td class="form-field-caption" align="right">:</td>
		<td> <input type="text" size="25" name="emkl" id="emkl" style="background-color:#FFFFCC;" />
		<input type="hidden" size="40" name="idemkl" id="idemkl" style="background-color:#FFFFCC;" />
		</td>
	 </tr>
	 <tr>
		<td class="form-field-caption" align="right">Address</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="55" id="alamat" name="alamat" readonly="readonly"/>
		</td>
	</tr>
	<tr>
		<td class="form-field-caption" align="right">N.P.W.P</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="20" name="npwp" id="npwp" readonly="readonly"/>
		</td>
    </tr>

	 
	 <tr>
		<td class="form-field-caption" align="right">Delivery Via</td>
		<td class="form-field-caption" align="right">:</td>
		<td><select name="dev_via" id="dev_via" value={$tipe}>
			<option value="TONGKANG">Tongkang</option>
			<option value="TRUCK">Truck</option>
		</select></td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">Remarks</td>
		<td class="form-field-caption" align="right">:</td>
		<td><textarea id="keterangan" name="keterangan" cols="30" style="background-color:#FFFFFF;" rows="2" ></textarea>
		</td>
     </tr>
	 <tr>
		<td class="form-field-caption" align="right">Date Delivery</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="20" name="tgl_delivery" id="tgl_delivery" value=""/>
		</td>
    </tr>
	 <tr>
		<td class="form-field-caption" align="right"></td>
		<td class="form-field-caption" align="right"></td>
		<td></td>
		<td>&nbsp;</td>
		
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
		<br>
		<div id="detail_container"></div>
	</div>
	<br/>
	<br/>
	
</div>

