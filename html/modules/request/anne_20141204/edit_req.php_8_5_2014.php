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
	$req=$_GET['no_req'];
	$qr="select OI,KODE_PBM, VESSEL,NO_UKK, VOYAGE, PEB, NPE, TGL_REQUEST, 
	to_char(TGL_STACK,'dd-mm-yyyy') TGL_STACK, TGL_OPEN_STACK, TGL_MUAT, TGL_BONGKAR, 
	STATUS,SHIFT_REEFER, START_SHIFT, END_SHIFT, 
	CLOSSING_TIME, PELABUHAN_ASAL, PELABUHAN_TUJUAN, NO_UKK, 
	COA, ALAMAT, NPWP, CARRIER, BOOKING_NUMB,
	(SELECT LINE_OPERATOR FROM MASTER_CARRIERS B WHERE A.CARRIER=B.CODE and rownum = 1) LO, IPOL, 
	IPOD,FPOD,FIPOD from req_receiving_h A WHERE ID_REQ='$req'";
	//print_r($qr);die;
	$db=getDb();
	$er=$db->query($qr);
	$hr=$er->fetchRow();
?>
<script type='text/javascript'>
//var neg1;
var neg2;

function calculator(){

	$start = $('#start_shift').val();
	$end = $('#end_shift').val();
	$.post("request.anne/refcal",{'mulai': $start, 'nanti':$end},function(data){
		$('#jml_shift').val(data);
	});
}

function update_req()
{
	var url="<?=HOME;?>request.anne.ajax/update_req";
	var id_req=$("#id_req").val();
	var ves=$( "#vessel" ).val();
	var	voy=$( "#voy" ).val();
	var	date_muat=$( "#tgl_muat" ).val();
	var	date_close=$( "#clossing_time" ).val();
	var date_op=$( "#tgl_open_stack" ).val();
	var ukk=$( "#ukk" ).val();
	var iemkl=$( "#idemkl" ).val();
	var pol=$( "#pelabuhan_asal" ).val();
	var ipol=$( "#ipol" ).val();
	var pod=$( "#pelabuhan_tujuan" ).val();
	var ipod=$( "#ipod" ).val();
	var ship=$( "#ship" ).val();
	var npe=$( "#npe" ).val();
	var peb=$( "#peb" ).val();
	var icar=$( "#icar" ).val();
	
	if( ukk=='')
	{
		alert('Entry vessel please,..');
		return false;
	}
	else if( date_muat=='')
	{
		alert('Entry date of load please,..');
		return false;
	}
	else if( date_close=='')
	{
		alert('Set clossing date please,..');
		return false;
	}
	else if( date_op=='')
	{
		alert('Set open stack please,..');
		return false;
	}
	else if( iemkl=='')
	{
		alert('Entry EMKL please,..');
		return false;
	}
	else if( ipol=='')
	{
		alert('Entry Port Of Load please,..');
		return false;
	}
	else if( ipod=='')
	{
		alert('Entry Port Of Discharge please,..');
		return false;
	}
	else if ((ship=='O')&&( npe=='')) 
	{	
		alert('Entry NPE please,..');
		return false;
	
	}
	else if ((ship=='O')&&( peb=='')) 
	{	
		alert('Entry PEB please,..');
		return false;
	}
	else if( icar=='')
	{
		alert('Entry Carrier please,..');
		return false;
	}
	else
	{
		//alert('e');
		$.post(url,{ID_REQ:id_req,UKK: ukk,IDEMKL:iemkl, IPOD:ipod, IPOL:ipol, SHIP:ship, NPE:npe, PEB:peb, CAR:icar},function(data){	
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
				document.getElementById("peb").readOnly = true;
				document.getElementById("npe").readOnly = true;
				document.getElementById("vessel").readOnly = true;
				document.getElementById("emkl").readOnly = true;
				//document.getElementById("tgl_stack").disabled = true;
				document.getElementById("pelabuhan_asal").readOnly = true;
				document.getElementById("pelabuhan_tujuan").readOnly = true;
				//document.getElementById("carrier").readOnly = true;
				window.location="<?=HOME?>request.anne/";
			}	
		});	
		
	}
}

function add_cont1(i,j,k, book_s)
{
	var nc=$('#nc').val();
	var sc=$('#sc').val();
	var tc=$('#tc').val();
	var stc=$('#stc').val();
	var hc=$('#hc').val();
	var comm=$('#icomm').val();
	var imo=$('#imo').val();
	var unnumber=$('#unnumber').val();
	var iso=$('#iso').val();
	var book=$('#book').val();
	var hgc=$('#hgc').val();
	var tmp=$('#temp').val();
	var car_d=$('#icar_d').val();
	
	var url="<?=HOME;?>request.anne.ajax/add_detail_req";
	var valid=$('#rsct').val();
	if((valid=='0') || (valid==''))
	{
		alert('Container Invalid');
		return false;
	}
	else 
	{
	if(nc=='')
	{
		alert('Entry container number please');
		return false;
	}
	if(car_d=='')
	{
		alert('Entry Carrier please');
		return false;
	}
	else if((hc=='Y')&&(imo==''))
	{
		alert('Entry imo class please');
		return false;
	}
	else if((hc=='Y')&&(unnumber==''))
	{
		alert('Entry un number please');
		return false;
	}
	else if((stc=='FCL')&&(comm==''))
	{
		alert('Entry commodity please...');
		return false;
	}
	else if((tc=='RFR')&&(tmp==''))
	{
		alert('Entry temperature please...');
		return false;
	}
	else 
	{
		$.post(url,{NC: nc,SC:sc, TC:tc, STC:stc, HC:hc, COMM:comm, IMO:imo, ISO:iso, BOOK:book_s, REQ:i, HGC:hgc, SHIP:j, CAR:car_d, TEMP:tmp},function(data){	
			alert(data);
			$('#detail_container').load("<?=HOME?>request.anne.ajax/list_container?id="+i);
		});
		$('#nc').val('');
		$('#nc').focus()
		$('#imo').val('');
		$('#unnumber').val('');
		$('#temp').val('');
		$('#ow').val('');
		$('#oh').val('');
		$('#ol').val('');
		$('#bypass_valid').prop('checked', false);
	}
	}
	//alert(nc);
}


$(document).ready(function() 
{	
	//open at first
	document.getElementById('but_create').style.display = "none";
	src = 'request.anne.auto/pelabuhan_pod';
	var v_req="<?=$req?>";
	var ship="<?=$hr['OI']?>";
	var icar=encodeURIComponent("<?=$hr['CARRIER']?>");
	var book_ship_h="<?=urlencode($hr['BOOKING_NUMB']);?>";
	
	//$('#detail_req').load("<?=HOME?>request.anne.ajax/detail_req?id="+v_req+"&ship="+ship+"&car="+icar+"&book_ship_h="+book_ship_h);
	$('#detail_req').load("<?=HOME?>request.anne.ajax/detail_req?id="+v_req+"&ship="+ship+"&car=null&book_ship_h="+book_ship_h);
	$('#detail_container').load("<?=HOME?>request.anne.ajax/list_container?id="+v_req);
	//======================================= autocomplete vessel==========================================//
	$( "#vessel" ).autocomplete({
		minLength: 3,
		source: "request.anne.auto/vessel",
		focus: function( event, ui ) 
		{
			$( "#vessel" ).val( ui.item.VESSEL);
			return false;
		},
		select: function( event, ui ) 
		{
			$( "#vessel" ).val( ui.item.VESSEL);
			$( "#voy" ).val( ui.item.VOYAGE_OUT);
			if(ui.item.FIRST_ETD == null || ui.item.FIRST_ETD == ''){
				$( "#tgl_muat" ).val( ui.item.TGL_JAM_BERANGKAT);
			} else {
				$( "#tgl_muat" ).val( ui.item.FIRST_ETD);
			}
			$( "#clossing_time" ).val( ui.item.CLOSING_TIME_DOC);
			$( "#tgl_bongkar" ).val( ui.item.TGL_JAM_TIBA);
			$( "#tgl_open_stack" ).val( ui.item.OPEN_STACK);
			$( "#ukk" ).val( ui.item.ID_VSB_VOYAGE);
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a align='center'>" + item.VESSEL + " " +item.VOYAGE_IN+" - "+ item.VOYAGE_OUT+" <br>"+ item.ID_VSB_VOYAGE +"</a>")
		.appendTo( ul );
	
	};
	//======================================= autocomplete vessel==========================================//
	
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
	
	//======================================= autocomplete pol ==========================================//
	$( "#pelabuhan_asal" ).autocomplete({
		minLength: 3,
		source: "request.anne.auto/pelabuhan",
		focus: function( event, ui ) 
		{
			$( "#pelabuhan_asal" ).val( ui.item.PELABUHAN);
			return false;
		},
		select: function( event, ui ) 
		{
			$( "#pelabuhan_asal" ).val( ui.item.PELABUHAN);
			$( "#ipol" ).val( ui.item.ID_PEL);
			$( "#nega" ).val( ui.item.NAMA_NEG);
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a>" + item.PELABUHAN + " <br> " + item.ID_PEL + " - "+item.NAMA_NEG+"</a>")
		.appendTo( ul );
	
	};
	
	//======================================= autocomplete pod==========================================//
	$( "#pelabuhan_tujuan" ).autocomplete({
		minLength: 3,
		source: function(request, response) {
            $.ajax({
                url: src,
                dataType: "json",
                data: {
                    term : request.term,
                    vessel : encodeURIComponent($("#vessel").val())
                },
                success: function(data) {
                    response(data);
                }
            });
        },
		focus: function( event, ui ) 
		{
			$( "#pelabuhan_tujuan" ).val( ui.item.PELABUHAN);
			return false;
		},
		select: function( event, ui ) 
		{
			$( "#pelabuhan_tujuan" ).val( ui.item.PELABUHAN);
			$( "#ipod" ).val( ui.item.ID_PEL);
			$( "#negt" ).val( ui.item.NAMA_NEG);
			neg2=ui.item.NAMA_NEG;
			if (neg2!='INDONESIA')
			{
				$( "#ship" ).val('O');
			}
			else
			{
				$( "#ship" ).val('I');
			}
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a>" + item.PELABUHAN + " <br> " + item.ID_PEL + " - "+item.NAMA_NEG+"</a>")
		.appendTo( ul );
	
	};
	
	$("#tgl_stack").datepicker({
	dateFormat: 'dd-mm-yy'
	});
	
	$("#start_shift").datetimepicker({
			dateFormat: 'dd-mm-yy'
            });
			
	$("#end_shift").datetimepicker({
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
	if(tc=='HQ'){
		$('#hgc').val('9.6');
	}
	var hgc=$('#hgc').val();
	var url="<?=HOME;?>request.anne.ajax/cek_cont_req";
	$.post(url,{SC:sc, TC:tc,HGC:hgc},function(data){	
		//alert(data);
		$('#iso').val(data);
	});
}

function del(a,b,c,d,e)
{
	var url="<?=HOME;?>request.anne.ajax/del_cont";
	$.post(url,{NO_CONT:b, NO_REQ:a, VESSEL:c, VOYAGE:d, OPERATORID:e},function(data){	
		alert(data);
		$('#detail_container').load("<?=HOME?>request.anne.ajax/list_container?id="+a);
	});
}
</script>

<div class="content">
	<p>
	<img src="<?=HOME?>images/delivery.png" height="5%" width="5%" style="vertical-align:middle"> <b> <font color='#69b3e2' size='4px'>Edit Request</font> </b>
	 <font color='#888b8d' size='4px'>
	 Receiving
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
		<td class="form-field-caption" align="right">Vessel</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="25" name="vessel" id="vessel"  style="background-color:#FFFFCC;" readonly="readonly" value="<?=$hr['VESSEL'];?>" />
		</td>
		<td></td>
		<td class="form-field-caption" align="right">Clossing time Doc.</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="20" name="clossing_time" id="clossing_time" readonly="readonly" value="<?=$hr['CLOSSING_TIME'];?>" />
		</td>
	</tr>
	<tr>
		<td class="form-field-caption" align="right">Voyage</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="10" name="voy" id="voy" readonly="readonly" value="<?=$hr['VOYAGE'];?>" readonly="readonly"  /><input size="10" name="voyopus" id="voyopus" type="hidden"/>
		</td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">Tgl open stack</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="20" name="tgl_open_stack" id="tgl_open_stack" readonly="readonly" value="<?=$hr['TGL_OPEN_STACK'];?>" />
		</td>
	</tr>
	
	 <tr>
		<td class="form-field-caption" align="right">No. UKK</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="18" name="ukk" id="ukk" readonly="readonly" value="<?=$hr['NO_UKK'];?>" />
		</td>
		
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">Tgl bongkar</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="20" name="tgl_bongkar" id="tgl_bongkar" readonly="readonly" value="<?=$hr['TGL_BONGKAR'];?>" />
		</td>
     </tr>
	 <tr>
		<td class="form-field-caption" align="right">Tgl muat</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="20" id="tgl_muat" name="tgl_muat" readonly="readonly" value="<?=$hr['TGL_MUAT'];?>" />
		</td>
		
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">Pelabuhan Asal</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="25" name="pelabuhan_asal" id="pelabuhan_asal" value="<?=$hr['PELABUHAN_ASAL'];?>" /> <input type="text" size="8" id="ipol" value="<?=$hr['IPOL'];?>" readonly="readonly"/>
			<input type="hidden" size="8" id="nega" />
		</td>
     </tr>
	  <tr>
		<td class="form-field-caption" align="right">No P.E.B</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="25" name="peb" id="peb" value="<?=$hr['PEB'];?>" style="background-color:#FFFFFF;"/>
		</td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">Pelabuhan Tujuan</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="25" name="pelabuhan_tujuan" id="pelabuhan_tujuan" value="<?=$hr['PELABUHAN_TUJUAN'];?>"/> <input type="text" size="8" id="ipod" readonly="readonly" value="<?=$hr['IPOD'];?>"/>
			<input type="hidden" size="8" id="negt" />
		</td>
			
     </tr>
	 <tr>
		<td class="form-field-caption" align="right"></td>
		<td class="form-field-caption" align="right"></td>
		<td>
		</td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">FPOD</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="25" name="fpod" id="fpod"  value="<?=$hr['FPOD'];?>" /> <input type="text" size="8" id="fipod" value="<?=$hr['FIPOD'];?>" readonly="readonly"/>
			<input type="hidden" size="8" id="negft" />
		</td>
			
     </tr>
	 <tr>
		<td class="form-field-caption" align="right">No N.P.E</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="25" name="npe" id="npe" style="background-color:#FFFFFF;" value="<?=$hr['NPE'];?>"/>
		</td>
		<td>&nbsp;</td>	
		<td class="form-field-caption" align="right">Booking Ship </td>
		<td class="form-field-caption" align="right">:</td>
		<td colspan="13">
			<input type="text" size="15" id="book_ship_h" name="book_ship_h" style="background-color:#FFFFFF;" value="<?=$hr['BOOKING_NUMB'];?>" />
		</td>		
     </tr>
	 <tr>
		<td class="form-field-caption" align="right">Customer</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="25" name="emkl" id="emkl" style="background-color:#FFFFCC;" value="<?=$hr['KODE_PBM'];?>"/>
		<input type="hidden" size="40" name="idemkl" id="idemkl" style="background-color:#FFFFCC;" value="<?=$hr['COA'];?>"/>
		
		</td>
		<td></td>
		<td class="form-field-caption" align="right">Calculator Shift Reefer</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="20" id="start_shift" name="start_shift" value="" style="background-color:#FFFFFF;"/> s/d <input type="text" size="20" id="end_shift" name="end_shift" style="background-color:#FFFFFF;"/><img src="<?=HOME?>images/calculator.png" width="20" onclick="calculator()" /></td>
	</tr>
	</tr>
	 <tr>
		<td></td>
		<td></td>
		<td></td>
		</td>
		<td></td>
		<td class="form-field-caption" align="right">Bayar Reefer</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="10" name="jml_shift" id="jml_shift" value="" style="background-color:#FFFFCC;" readonly="readonly"/>*Shift
		</td>
     </tr>
	 <tr>
		<td class="form-field-caption" align="right">Shipping</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="5" id="ship" name="ship" readonly="readonly" style="background-color:#FFFFCC; font-weight:bold;text-align:center" value="<?=$hr['OI'];?>"/><br>
		<b><i>O : Ocean Going - I : Intersuler</i></b></td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">Keterangan</td>
		<td class="form-field-caption" align="right">:</td>
		<td><textarea id="keterangan" name="keterangan" cols="30" style="background-color:#FFFFFF;" rows="2" ></textarea>
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
	<div id="content_detail">
		<div id="detail_req"></div>
		<br>
		<div id="detail_container"></div>
	</div>
	<br/>
	<br/>
	
</div>

