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

function sync_pelanggan(){
	var url="<?=HOME;?>request.delivery.sp2.ajax/sync_pelanggan";
	var tees='tes';
	$.post(url,{tes:tees},function(data){	
		alert('sukses');
	});
}

function onfoucusshiftref(){
	var etd_v = $("#tgl_muat").val();
	$("#end_shift").val(etd_v);
}

function create_req()
{
	var url="<?=HOME;?>request.anne.ajax/save_req";
	var ves=$( "#vessel" ).val();        
	var	voy=$( "#voy" ).val();
	var	voyo=$( "#voyo" ).val();
	var	date_muat=$( "#tgl_muat" ).val();
	var	date_close=$( "#clossing_time" ).val();
	var date_op=$( "#tgl_open_stack" ).val();
	var ukk=$( "#ukk" ).val();
	var iemkl=$( "#idemkl" ).val();
	var iemkl2=$( "#idemkl2").val();
	var pol=$( "#pelabuhan_asal" ).val();
	var ipol=$( "#ipol" ).val();
	var pod=$( "#pelabuhan_tujuan" ).val();
	var ipod=$( "#ipod" ).val();
	var ship=$( "#ship" ).val();
	var npe=$( "#npe" ).val();
	var peb=$( "#peb" ).val();
	var icar=$( "#icar" ).val();
	var start_shift=$( "#start_shift" ).val();
	var end_shift=$( "#end_shift" ).val();
	var jml_shift=$( "#jml_shift" ).val();
	var book_ship_h=$( "#book_ship_h" ).val();
	var fpod=$("#fpod").val();
	var fipod=$("#fipod").val();       

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
	
	else if( iemkl2=='')
	{
		alert('Entry EMKL2 please,..');
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
	else if ((ship=='I')&&( npe=='')) 
	{	
		alert('Entry NPE please,..');
		return false;
	
	}
	else if ((ship=='I')&&( peb=='')) 
	{	
		alert('Entry PEB please,..');
		return false;
	}
	else if( icar=='')
	{
		alert('Entry Carrier please,..');
		return false;
	}
	else if( book_ship_h=='')
	{
		alert('Entry Booking Ship please,..');
		return false;
	}
	else
	{
		//alert('e');
		$.post(url,{UKK: ukk,IDEMKL:iemkl,IDEMKL2:iemkl2, IPOD:ipod, IPOL:ipol, SHIP:ship, NPE:npe, PEB:peb, DS:book_ship_h, CAR:icar, START_SHIFT: start_shift, END_SHIFT:end_shift, JML_SHIFT:jml_shift, FIPOD:fipod},function(data){	
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
				document.getElementById("book_ship_h").readOnly = true;
				document.getElementById("npe").readOnly = true;
				document.getElementById("vessel").readOnly = true;
				document.getElementById("emkl").readOnly = true;
				document.getElementById("emkl2").readOnly = true;
				document.getElementById("pelabuhan_asal").readOnly = true;
				document.getElementById("pelabuhan_tujuan").readOnly = true;
				// document.getElementById("carrier").readOnly = true;
				document.getElementById('but_create').style.display = "none";
				$('#detail_req').load("<?=HOME?>request.anne.ajax/detail_req?id="+v_req+"&ship="+ship+"&car="+icar+"&book_ship_h="+encodeURIComponent(book_ship_h)+"&ukk="+ukk+"&vessel="+encodeURIComponent(ves)+"&voyin="+voy+"&voyout="+voyo);
				$('#detail_container').load("<?=HOME?>request.anne.ajax/list_container?id="+v_req);
			}	
		});	
		
	}
}

function add_cont1(i,j,k,book_s,ukk,vessel,voyin,voyout)
{
	var valid=$('#rsct').val();
	if((valid=='0') || (valid==''))
	{
		alert('Container Invalid');
	}
	else 
	{
	var nc=$('#nc').val();
	var sc=$('#sc').val();
	var tc=$('#tc').val();
	var stc=$('#stc').val();
	var hc=$('#hc').val();
	var comm=$('#icomm').val();
	var car_d=$('#icar_d').val();
	var imo=$('#imo').val();
	var iso=$('#iso').val();
	var nor=$('#nor').val();
	var book=$('#book').val();
	var hgc=$('#hgc').val();
	var tmp=$('#temp').val();
	var ow=$('#ow').val();
	var oh=$('#oh').val();
	var ol=$('#ol').val();
	var unnumber=$('#unnumber').val();
	var weight_npe=$('#weight_npe').val();
	
	var url="<?=HOME;?>request.anne.ajax/add_detail_req?ukk="+ukk+"&vessel="+vessel+"&voyin="+voyin+"&voyout="+voyout;
	if(nc.length<10)
	{
		alert('Entry valid container number please');
		return false;
	}
	else if(book_s=='')
	{
		alert('Booking number please..');
		return false;
	}
	else if(car_d=='')
	{
		alert('Carrier please..');
		return false;
	}
	if(weight_npe=='')
	{
		alert('Entry Weight NPE please!');
		$("#weight_npe").focus();
		return false;
	}
	if(isNaN(weight_npe) == true){
		alert('Weight NPE must be a number!');
		$("#weight_npe").focus();
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
	else if((hgc=='OOG')&&((oh=='')&&(ow=='')&&(ol=='')))
	{
		alert('Entry Over dimension OW / OH / OL please');
		return false;
	}
	else if((stc=='FCL')&&(comm==''))
	{
		alert('Entry commodity please...');
		return false;
	}       

        
	else if((tc=='RFR')&&(tmp=='') && (stc=='FCL') && (nor=='T'))
	{
		//alert(stc);
		alert('Container Full Reefer, Entry temperature please...');
		return false;
	}
	else 
	{
		$.post(url,{NC: nc,SC:sc, TC:tc, STC:stc, HC:hc, COMM:comm, IMO:imo, ISO:iso, BOOK:book_s, REQ:i, HGC:hgc, SHIP:j, CAR:car_d, TEMP:tmp, OH: oh, OW: ow, OL:ol, UNNUMBER:unnumber, NOR:nor,WEIGHT_NPE:weight_npe},function(data){	
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
		$('#nor').val('T');
		$('#bypass_valid').prop('checked', false);
	}
	}
	//alert(nc);
}

$(document).ready(function() 
{	
        var ukk=$( "#ukk" ).val();
	src = 'request.anne.auto/pelabuhan_pod';
			$( "#pelabuhan_asal" ).val('PONTIANAK, INDOESIA');
			$( "#ipol" ).val('IDPNK');
			$( "#nega" ).val('ID');
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
			$( "#voy" ).val( ui.item.VOYAGE_IN);
			$( "#voyo" ).val( ui.item.VOYAGE_OUT);
			//di comment dl 26/09/2014 yg muncul jadi etd lama soalnya
			/*if(ui.item.FIRST_ETD == null || ui.item.FIRST_ETD == ''){
				$( "#tgl_muat" ).val( ui.item.TGL_JAM_BERANGKAT);
			} else {
				$( "#tgl_muat" ).val( ui.item.FIRST_ETD);
			}*/
			$( "#tgl_muat" ).val( ui.item.TGL_JAM_BERANGKAT);
			$( "#clossing_time" ).val( ui.item.CLOSING_TIME_DOC);
			$( "#tgl_bongkar" ).val( ui.item.TGL_JAM_TIBA);
			$( "#tgl_open_stack" ).val( ui.item.OPEN_STACK);
			$( "#ukk" ).val( ui.item.ID_VSB_VOYAGE);
			$( "#callsign" ).val( ui.item.CALL_SIGN);
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
            $( "#emkl2" ).val( ui.item.NAMA_PERUSAHAAN);
			$( "#idemkl2" ).val( ui.item.KD_PELANGGAN);
			
			
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
	
	
	//======================================= autocomplete EMKL2==========================================//
	$( "#emkl2" ).autocomplete({
		minLength: 3,
		source: "request.anne.auto/emkl",
		focus: function( event, ui ) 
		{
			$( "#emkl2" ).val( ui.item.NAMA_PERUSAHAAN);
			return false;
		},
		select: function( event, ui ) 
		{
			$( "#emkl2" ).val( ui.item.NAMA_PERUSAHAAN);
			$( "#idemkl2" ).val( ui.item.KD_PELANGGAN);
			
			
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a align='center'>" + item.NAMA_PERUSAHAAN + "<br>" +item.ALAMAT_PERUSAHAAN+"</a>")
		.appendTo( ul );	
	};
	//======================================= autocomplete EMKL2==========================================//
	
	
	
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
                    vessel : encodeURIComponent($("#vessel").val()),
					voy : encodeURIComponent($("#voy").val()),
					voyo : encodeURIComponent($("#voyo").val())
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
	$( "#fpod" ).autocomplete({
		minLength: 3,
		source: function(request, response) {
            $.ajax({
                url: "request.anne.auto/pelabuhan_fpod",
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
			$( "#fpod" ).val( ui.item.PELABUHAN);
			return false;
		},
		select: function( event, ui ) 
		{
			$( "#fpod" ).val( ui.item.PELABUHAN);
			$( "#fipod" ).val( ui.item.ID_PEL);
			$( "#negft" ).val( ui.item.NAMA_NEG);
			neg2=ui.item.NAMA_NEG;	
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a>" + item.PELABUHAN + " <br> " + item.ID_PEL + " - "+item.NAMA_NEG+"</a>")
		.appendTo( ul );
	
	};
	//======================================= autocomplete carrier==========================================//
	
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
    if(hgc=='OOG'){
		$("#ow").removeAttr('disabled');
		$("#ol").removeAttr('disabled');
		$("#oh").removeAttr('disabled');
	} else {
		$("#ow").attr('disabled','disabled');
		$("#ol").attr('disabled','disabled');
		$("#oh").attr('disabled','disabled');
	}
	var url="<?=HOME;?>request.anne.ajax/cek_cont_req";
	$.post(url,{SC:sc, TC:tc,HGC:hgc},function(data){	
		//alert(data);
		$('#iso').val(data);
	});
}

function update_req()
{
	var url="<?=HOME;?>request.anne.ajax/save_flag_req";
	var id_req=$('#id_req').val();
	$.post(url,{noreq:id_req},function(data){	
		//alert(data);
	});
	window.location="<?=HOME?>request.anne/";
}

function del(a,b,c,d,e)
{
	var url="<?=HOME;?>request.anne.ajax/del_cont";
	$.post(url,{NO_CONT:b, NO_REQ:a, VESSEL:c, VOYAGE:d, OPERATORID:e},function(data){	
		alert(data);
		$('#detail_container').load("<?=HOME?>request.anne.ajax/list_container?id="+a);
	});
}

function calculator(){
	$start = $('#start_shift').val();
	$end = $('#end_shift').val();
	$.post("request.anne/refcal",{'mulai': $start, 'nanti':$end},function(data){
		$('#jml_shift').val(data);
	});
}

</script>

<div class="content">
	<p>
	<img src="<?=HOME?>images/delivery.png" height="5%" width="5%" style="vertical-align:middle"> <b> <font color='#69b3e2' size='4px'>Request</font> </b>
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
		<td><input type="text" size="20" name="id_req" id="id_req" readonly="readonly" style="background-color:#f9151f; color:white;font-weight:bold;text-align:center"/>
		</td>
		<td></td>
		<td class="form-field-caption" align="right">Date Request</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="20" id="tgl_req" name="tgl_req" value="<?=date('d-m-Y H:i');?>" readonly="readonly"/>
		</td>
	</tr>
	<tr>
		<td class="form-field-caption" align="right">Vessel</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="25" name="vessel" id="vessel"  style="background-color:#FFFFCC;" /><input type="text" size="5" name="callsign" id="callsign"  style="background-color:#FFFFCC;" />
		</td>
		<td></td>
		<td class="form-field-caption" align="right">Clossing time Doc.</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="20" name="clossing_time" id="clossing_time" readonly="readonly"/>
		</td>
	</tr>	
	
	<tr>
		<td class="form-field-caption" align="right">Voyage</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="10" name="voy" id="voy" readonly="readonly"/> - <input type="text" size="10" name="voyo" id="voyo" readonly="readonly"/> <input size="10" name="voyopus" id="voyopus" type="hidden"/>
		</td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">Open stack date</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="20" name="tgl_open_stack" id="tgl_open_stack" readonly="readonly	" />
		</td>
	</tr>     
        
	<tr>
		
		<td class="form-field-caption" align="right">ETA</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="20" name="tgl_bongkar" id="tgl_bongkar" readonly="readonly"/></td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">ETD</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="20" id="tgl_muat" name="tgl_muat" value="" readonly="readonly"/>
		</td>
	</tr>
	 <tr>
		
		
     </tr>
	 <tr>
		
		<td class="form-field-caption" align="right">ID VVD</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="18" name="ukk" id="ukk" readonly="readonly"/>
		</td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">POL</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="25" name="pelabuhan_asal" id="pelabuhan_asal"/> <input type="text" size="8" id="ipol" readonly="readonly"/>
			<input type="hidden" size="8" id="nega"/>
		</td>
     </tr>
	  <tr>
		<td class="form-field-caption" align="right">No P.E.B</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="25" name="peb" id="peb" value="" style="background-color:#FFFFFF;"/>
		</td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">POD</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="25" name="pelabuhan_tujuan" id="pelabuhan_tujuan"  /> <input type="text" size="8" id="ipod" readonly="readonly"/>
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
		<td><input type="text" size="25" name="fpod" id="fpod"  /> <input type="text" size="8" id="fipod" readonly="readonly"/>
			<input type="hidden" size="8" id="negft" />
		</td>
			
     </tr>
	 <tr>
		<td class="form-field-caption" align="right">No N.P.E</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="25" name="npe" id="npe" style="background-color:#FFFFFF;"/>
		</td>
		<td>&nbsp;</td>		
				
		<td class="form-field-caption" align="right">Booking Ship </td>
		<td class="form-field-caption" align="right">:</td>
		<td colspan="13">
			<input type="text" size="15" id="book_ship_h" name="book_ship_h" style="text-transform:uppercase"/>
		</td>
	</tr>
		
     </tr>
	 <tr>
		<td class="form-field-caption" align="right">Cust Lolo</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="25" name="emkl" id="emkl" style="background-color:#FFFFCC;" />
		<input type="hidden" size="40" name="idemkl" id="idemkl" style="background-color:#FFFFCC;" />
		<button onclick="sync_pelanggan()" title="sync data pelanggan"><img src="<?=HOME;?>images/sync.png" width="20" height="20"/></button>
		</td>
		<td></td>
		<td class="form-field-caption" align="right">Calculator Shift Reefer</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input onfocus="onfoucusshiftref()" type="text" size="20" id="start_shift" name="start_shift" value="" style="background-color:#FFFFFF;"/> s/d <input type="text" size="20" id="end_shift" name="end_shift" style="background-color:#FFFFFF;"/><img src="<?=HOME?>images/calculator.png" width="20" onclick="calculator()" /></td>
	</tr>
	
	
	<tr>
		<td class="form-field-caption" align="right">Cust Penumpukan</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="25" name="emkl2" id="emkl2" style="background-color:#FFFFCC;" readonly />
		<input type="hidden" size="40" name="idemkl2" id="idemkl2" style="background-color:#FFFFCC;" />
		<button onclick="sync_pelanggan()" title="sync data pelanggan">
		<img src="<?=HOME;?>images/sync.png" width="20" height="20"/>
		</button>
		</td>		
	</tr>	
	
	</tr>
	 <tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td class="form-field-caption" align="right">Bayar Reefer</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="10" name="jml_shift" id="jml_shift" value="" style="background-color:#FFFFCC;" readonly="readonly"/>*Shift
		</td>
     </tr>
	 <tr>
		<td class="form-field-caption" align="right">Shipping</td>
		<td class="form-field-caption" align="right">:</td>              
                <td><select id="ship" name="ship">                    
                    <option value="D">Intersuler</option>
                    <option value="I">Ocean Going</option>
                </select>
            </td>
            
                
                </td>
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
	<div>
		<div id="detail_req"></div>
		<br>
		<div id="detail_container"></div>
	</div>
	<br/>
	<br/>
	
</div>

