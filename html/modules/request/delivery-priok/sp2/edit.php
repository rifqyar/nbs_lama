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
<?php
	$req=$_GET['no_req'];
	
	$db=getDb();
	$param_b_var= array(	
		"v_noreq"=>"$req"
	);
	
	$query = "UPDATE REQ_DELIVERY_H SET IS_EDIT=1 WHERE ID_REQ = :v_noreq";
	$db->query($query,$param_b_var);
	
	$qr="SELECT a.EMKL,a.ALAMAT,a.NPWP,a.TGL_REQUEST,a.NO_SPPB,to_char(a.TGL_SPPB,'dd-mm-yyyy') TGL_SPPB,a.NO_DO,to_char(a.TGL_SP2,'dd-mm-yyyy') TGL_SP2,a.SHIP,a.DEV_VIA,a.KETERANGAN,CALL_SIGN,TO_CHAR(a.TGL_DO,'dd-mm-yyyy') TGL_DO,
		a.BL_NUMB,a.SP_CUSTOM,a.TGL_SP_CUSTOM,
		A.COA as IDEMKL, TO_CHAR(a.disch_date,'dd-mm-yyyy') DISCH_DATE, a.no_ukk, regexp_replace(VESSEL, '[[:space:]]*','') xvessel, a.VESSEL, a.voyage_in, a.voyage_out, a.CUST_DSTN,a.ICUST_DSTN FROM REQ_DELIVERY_H a WHERE a.ID_REQ = TRIM('$req')";
	//print_r($qr);die;
	$db=getDb();
	$er=$db->query($qr);
	$hr=$er->fetchRow();
	
	$ship = $hr['SHIP'];
	
?>
<script type='text/javascript'>
//var neg1;
var neg2;
function all_done()
{
	var url="<?=HOME?>request.delivery.sp2.ajax/update_qtycont";
	$.post(url,{ID:'<?=$req;?>'}, function (data){
			if(data='OK')
			{
				window.location="<?=HOME?>request.delivery.sp2/";
			}
			else
			{
				alert("save failed, call programmer");
			}
		}
	);
	
}
function update_req()
{
	var url="<?=HOME;?>request.delivery.sp2.ajax/update_req";
	var id_req=$( "#id_req" ).val();
	var iemkl=$( "#idemkl" ).val();
	var almt=$( "#alamat" ).val();
	var npwp=$( "#npwp" ).val();
	var tglreq = $("#tgl_req").val();
	var nsppb=$( "#sppb" ).val();
	var tglsppb=$( "#tgl_sp2b" ).val();
	var spcust=$( "#spcust" ).val();
	var tgl_spcust=$( "#tgl_spcust" ).val();
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
	else if ((ship=='O')&& (( nsppb=='')&&( spcust==''))) 
	{	
		alert('Entry SPPB/SP Custom please,..');
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
		
		var url_save_flag="<?=HOME;?>request.delivery.sp2.ajax/save_flag_req";
		$.post(url_save_flag,{noreq:id_req},function(data){	
			//alert(data);
		});
		
		$.post(url,{IDREQ:id_req,IDEMKL:iemkl,SPPB:nsppb, TGLSPPB:tglsppb, TGLSPCUST:tgl_spcust, NDO:ndo, TGLDO:tgldo, TGLDEL:tgldel, SHIP:ship, VIA:via, KET:ket},function(data){	
			if (data!='OK')
			{
				alert('Request gagal ');
				return false;
			}
			else
			{
				all_done();
			}	
		});	
		
	}
}

function add_cont1(i,j)
{
	var OBXchecked = "False";
	if ($('#bypass_valid_obx').is(":checked"))
	{
		OBXchecked = "True";
	}
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
	var booksl=$('#booksl').val();
	var unnumber=$('#unnumber').val();
	var ow=$('#ow').val();
	var oh=$('#oh').val();
	var ol=$('#ol').val();
	var ukk=$('#ukk').val();
	var pol=$('#pol').val();
	var pod=$('#pod').val();
	var pli=$('#pli').val();
	var plo=$('#plo').val();
	
	var call_sign=$( "#call_sign" ).val();
	var vin=$( "#voyin" ).val();
	var vesx = $( "#ves" ).val();
	var v_req=$( "#id_req" ).val();
	
	var url2="<?=HOME;?>request.delivery.sp2.auto/status_container";
	$.post(url2,{NC: nc, CC: call_sign, VC: vin},function(data){	
			if(data){
				alert(data);
			}
		});
		
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
	else if((tc=='RFR')&&(pli=='')&&(stc!='EMPTY'))
	{
		alert('Entry plug in at OPUS please...');
		return false;
	}
	else if((tc=='RFR')&&(plo=='')&&(stc!='EMPTY'))
	{
		alert('Entry plug out please...');
		return false;
	}
	else 
	{
$.post(url,{VIN :vin,VESX :vesx, NC: nc,SC:sc, TC:tc, STC:stc, HC:hc, COMM:comm, IMO:imo, ISO:iso, REQ:i, HGC:hgc, SHIP:j, CAR:car, TEMP:tmp,UKK:ukk, BOOKSL: booksl, OW:ow, OH:oh, OL:ol, UNNUMBER:unnumber, POD:pod, POL:pol, PLI:pli, PLO:plo, OBXVAL:OBXchecked},function(data){	
			if (data.substring(0,5) =='EXIST'){
			 alert(data);
			} else {
				alert(data);
				$('#detail_container').load("<?=HOME?>request.delivery.sp2.ajax/list_container?id="+i);                                                                
				$('#nc').val('');
                                $('#nc').focus()
				$('#sc').val('');
				$('#tc').val('');
				$('#stc').val('');
				$('#hgc').val('');
				$('#ow').val('');
				$('#oh').val('');
				$('#ol').val('');
				$('#temp').val('');
				$('#hc').val('');
				$('#imo').val('');
				$('#unnumber').val('');
				$('#comm').val('');
				$('#iso').val('');
				$('#car').val('');
				$('#pli').val('');
				$('#plo').val('');
			}
		});
	//alert(nc);
	}
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
			$( "#cargow" ).val( ui.item.NAMA_PERUSAHAAN);
			$( "#icargow" ).val( ui.item.KD_PELANGGAN);
			
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
	//======================================= autocomplete vessel==========================================//
	$( "#ves" ).autocomplete({
		minLength: 3,
		source: "request.delivery.sp2.auto/vessel",
		focus: function( event, ui ) 
		{
			$( "#ves" ).val( ui.item.VESSEL);
			return false;
		},
		select: function( event, ui ) 
		{
			$( "#ves" ).val( ui.item.VESSEL);
			$( "#id_ves_scd" ).val( ui.item.ID_VES_SCD);
			$( "#voyin" ).val( ui.item.VOYAGE_IN);
			$( "#voyout" ).val( ui.item.VOYAGE_OUT);
			$( "#date_disch" ).val( ui.item.START_WORK);
			
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a align='center'>" + item.VESSEL + "<br>" +item.VOYAGE_IN+" - " +item.VOYAGE_OUT+"</a>")
		.appendTo( ul );
	
	};
	//======================================= autocomplete vessel==========================================//
	
	
	//$('#detail_req').load("<?=HOME?>request.delivery.sp2.ajax/detail_req?id=<?=$req?>&ship=<?=$ship?>");
	$('#detail_req').load("<?=HOME?>request.delivery.sp2.ajax/detail_req?id=<?=$req?>&ship=<?=$ship?>&call_sign=<?=$hr['CALL_SIGN'];?>&vin=<?=$hr['VOYAGE_IN'];?>");
	$('#detail_container').load("<?=HOME?>request.delivery.sp2.ajax/list_container2?id=<?=$req?>");
	
	$("#tgl_sp2b").datepicker({
			dateFormat: 'dd-mm-yy'
            });
	$("#tgl_spcust").datepicker({
			dateFormat: 'dd-mm-yy'
            });
	$("#tgl_do").datepicker({
			dateFormat: 'dd-mm-yy'
            });
	$("#tgl_do_awal").datepicker({
			dateFormat: 'dd-mm-yy'
            });
	
	/*$("#tgl_delivery").datepicker({
			dateFormat: 'dd-mm-yy'
            });*/
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

function del(j,i,k,l,m)
{
	var url="<?=HOME;?>request.delivery.sp2.ajax/del_cont";
	$.post(url,{REQ:i, CONT:j, VESSEL:k, VOYAGE:l, OPERATORID:m},function(data){	
		alert(data);
		//$('#detail_container').load("<?=HOME?>request.delivery.sp2.ajax/list_container2?id="+i);
		jQuery("#l_vessel").jqGrid('setGridParam',{datatype: 'json'}).trigger('reloadGrid');
	});
}

function calculator_do(){
	var tgl_do_awal = $('#tgl_do_awal').val().split("-"); 
	var date_do_awal = new Date(tgl_do_awal[2],(tgl_do_awal[1]-1),tgl_do_awal[0]);
	date_do_awal.setDate(date_do_awal.getDate()+($('#tgl_do_add').val()-1));
	var dateStr = padStr(date_do_awal.getDate()) + "-" +
				  padStr(1 + date_do_awal.getMonth()) + "-" +
                  padStr(date_do_awal.getFullYear());
	$('#tgl_do').val(dateStr);
}

function padStr(i) {
    return (i < 10) ? "0" + i : "" + i;
}

</script>

<div class="content">
	<p>
	<img src="<?=HOME?>images/delivery.png" height="5%" width="5%" style="vertical-align:middle"> <b> <font color='#69b3e2' size='4px'>Edit Request SP2</font> </b>
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
		<td><input type="text" size="25" name="ves" id="ves" value="<?=$hr['VESSEL'];?>" readonly="readonly" style="background-color:#FFFFFF;"/>
			<input type="text" size="10" name="call_sign" id="call_sign" value="<?=$hr['CALL_SIGN'];?>" style="background-color:#FFFFFF;"/>
			<input type="hidden" size="10" name="id_ves_scd" id="id_ves_scd" value="<?=$hr['NO_UKK'];?>" style="background-color:#FFFFFF;"/>
		</td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">Voyage</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="8" id="voyin" name="voyin" value="<?=$hr['VOYAGE_IN'];?>"/> - <input type="text" size="8" id="voyout" name="voyout" value="<?=$hr['VOYAGE_OUT'];?>"/>
		</td>
	</tr>
	
	<tr>
		<td class="form-field-caption" align="right">Shipping</td>
		<td class="form-field-caption" align="right">:</td>
		<td><select id='ship' name="ship">
			<option value="O">Ocean Going</option>
			<option value="I">Interinsuler</option>
			</select><br>
		<b><i>O : Ocean Going - I : Interinsuler</i></b></td>
		<td></td>
		<td class="form-field-caption" align="right">Date Disch</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="20" id="date_disch" name="date_disch" value="<?=$hr['DISCH_DATE'];?>" readonly="readonly"/>
		</td>
	</tr>

	<br>

	<tr>
		<td class="form-field-caption" align="right">No S.P.P.B</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="25" name="sppb" id="sppb" value="<?=$hr['NO_SPPB'];?>" style="background-color:#FFFFFF;"/>
		<b><i>*Untuk Ocean Going Harus Diisi</i></b>
		</td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">SP Custom</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="25" name="spcust" id="spcust" value="<?=$hr['SP_CUSTOM'];?>" style="background-color:#FFFFFF;"/>
		
		</td>
     </tr>
	 <tr>
		<td class="form-field-caption" align="right">Date SPPB</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="20" id="tgl_sp2b" name="tgl_sp2b" value="<?=$hr['TGL_SPPB'];?>"/>
		</td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">Date SP Custom</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="20" id="tgl_spcust" name="tgl_spcust" value="<?=$hr['TGL_SP_CUSTOM'];?>"/>
		</td>	
     </tr>
	 <tr>
		<td class="form-field-caption" align="right">No D.O</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="25" name="do" id="do" value="<?=$hr['NO_DO'];?>" style="background-color:#FFFFFF;"/>
		<b><i>*Untuk Ocean Going Harus Diisi</i></b>
		</td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">Calculator Valid DO</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="10" id="tgl_do_awal" name="tgl_do_awal" value="" value=""/> selama 
		<input type="text" size="4" id="tgl_do_add" name="tgl_do_add" value="1"/> hari
		<img src="<?=HOME?>images/calculator.png" width="20" onclick="calculator_do()" />
		</td>
	</tr>
	 <tr>
	 		<td class="form-field-caption" align="right">BL Number</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="20" id="blnumb" name="blnumb" value="<?=$hr['BL_NUMB'];?>"/>
		</td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">Date Valid DO</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="20" id="tgl_do" name="tgl_do" value="<?=$hr['TGL_DO'];?>" value=""/>
		</td>
	 </tr>
	 <tr>
		<td class="form-field-caption" align="right">Consignee</td>
		<td class="form-field-caption" align="right">:</td>
		<td> <input type="text" size="25" name="emkl" id="emkl" value="<?=$hr['EMKL'];?>" style="background-color:#FFFFCC;" />
		<input type="hidden" size="40" name="idemkl" id="idemkl" value="<?=$hr['IDEMKL'];?>" style="background-color:#FFFFCC;" />
		</td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">Tujuan</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="25" id="cargow" name="cargow" value="<?=$hr['CUST_DSTN'];?>"/>
		<input type="hidden" size="20" id="icargow" name="icargow" value="<?=$hr['ICUST_DSTN'];?>"/>
		</td>
	 </tr>
	 <tr>
		<td class="form-field-caption" align="right">Address</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="55" id="alamat" name="alamat" value="<?=$hr['ALAMAT'];?>" readonly="readonly"/>
		</td>
	</tr>
	<tr>
		<td class="form-field-caption" align="right">N.P.W.P</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="20" name="npwp" id="npwp" value="<?=$hr['NPWP'];?>" readonly="readonly"/>
		</td>
    </tr>

	 
	 <tr>
		<td class="form-field-caption" align="right">Delivery Via</td>
		<td class="form-field-caption" align="right">:</td>
		<td>
		<?php if ($hr['DEV_VIA']=='TONGKANG'){?>
		<select name="dev_via" id="dev_via" value="<?=$hr['DEV_VIA'];?>">
			<option value="TONGKANG">Tongkang</option>
			<option value="TRUCK">Truck</option>
		</select><?php } else {?>
		<select name="dev_via" id="dev_via" value="<?=$hr['DEV_VIA'];?>">
			<option value="TRUCK">Truck</option>
			<option value="TONGKANG">Tongkang</option>
		</select> <?php } ?> </td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">Remarks</td>
		<td class="form-field-caption" align="right">:</td>
		<td><textarea id="keterangan" name="keterangan" value="" cols="30" style="background-color:#FFFFFF;" rows="2" ></textarea>
		</td>
     </tr>
	 <tr>
		<td class="form-field-caption" align="right">Date Delivery</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="20" name="tgl_delivery" id="tgl_delivery" readonly="readonly" value="<?=$hr['TGL_SP2'];?>"/>
		</td>
    </tr>
	 <tr>
		<td class="form-field-caption" align="right"></td>
		<td class="form-field-caption" align="right"></td>
		<td></td>
		<td>&nbsp;</td>
		
     </tr>
	 <tr>
		<td colspan="7"><button onclick="update_req()" id="but_create"></button></td>
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

