<?php if (!defined("XLITE_INCLUSION")) die(); ?><span class="graybrown"><img src='images/document-open.png' border='0' class="icon"/><font color="#DE7E21"> Request Delivery - SP2 </font><font color="#0066CC"> ke TPK</font></span><br/><!--<span class="graybrown"><img src='images/dokumenbig.png' border='0' class="icon"/> <?php echo($formtitle); ?></span><br/><br/>--><form id="dataForm" name="dataForm" action="<?=HOME?><?=APPID?>/edit_do" method="post" class="form-input" enctype="multipart/form-data"> <input type="hidden" name="__pbkey" value="" /><!--<fieldset class="form-fieldset">--><table cellspacing='2' cellpadding='2' border='0' width="100%" style="margin-top:10px;"><?php	if ($error): ?><tr><td colspan="2">Invalid Form Entry</td></tr><?php	endif; ?><!--<tr><td colspan="2"><b><?php echo($wizstep); ?></b></td></tr>--><tr><td width="49%" ><table><!-- <tr>
					<td class="form-field-caption" valign="top" align="right">Permintaan Dari </td>
					<td valign="top" class="form-field-input">: 
					<input id="REQUEST_BY"  name="REQUEST_BY" type="text" value="<?php echo($row["REQUEST_BY"]); ?>" />
				</tr> --><tr><td class="form-field-caption" valign="top" align="right">Jenis Repo</td><td valign="top" class="form-field-input">: <input id="JN_REPO" name="JN_REPO" type="text" value="<?php echo($row["JN_REPO"]); ?>" size="40" readonly="1" /><?php	if ($error["NO_UKK"]): ?><span class="form-field-error"><?php echo($error["JN_REPO"]); ?></span><?php	endif; ?></td></tr><!-- <tr>
					<td class="form-field-caption" valign="top" align="right">No Request Stuffing</td>
					
					<td valign="top" class="form-field-input">:
					<input id="NO_REQ_STUFFING" name="NO_REQ_STUFFING" type="text" value="<?php echo($row["NO_REQ_STUFFING"]); ?>" size="20"  readonly />
					</td>
				</tr> --><tr><td class="form-field-caption" valign="top" align="right">No Request Delivery </td><td valign="top" class="form-field-input">: <input id="NO_REQUEST" name="NO_REQUEST" type="text" value="<?php echo($row["NO_REQUEST"]); ?>" /><input id="NO_REQUEST2" name="NO_REQUEST2" type="text" value="<?php echo($no_req2); ?>" /></td></tr><tr style="display:none"><td class="form-field-caption" valign="top" align="right">Penumpukan Empty Oleh</td><td valign="top" class="form-field-input">: <input id="KD_PELANGGAN" name="KD_PELANGGAN" type="hidden" value="<?php echo($row["KD_PBM"]); ?>" readonly="1" size="3" /><input id="NPWP" name="NPWP" type="hidden" value="<?php echo($row["NO_NPWP_PBM"]); ?>" readonly="1" size="3" /><input id="ALAMAT" name="ALAMAT" type="hidden" value="<?php echo($row["ALAMAT"]); ?>" readonly="1" size="100" /><input id="NM_PELANGGAN" size="40" name="NM_PELANGGAN" type="text" value="<?php echo($row["NAMA_PBM"]); ?>" class="kdemkl" title="Autocomplete" style="background-color:#FFFFCC;" /><!--<img src="<?php echo($HOME); ?>images/ico_find.png" class="find" onmouseover="_hand(this)" onclick="popup_PELANGGAN(this.value);" ><div id="ref_PELANGGAN"></div><div class="suggestionsBox" id="popsuggestions_PELANGGAN" style="display: none;"><div class="suggestionList" id="popSuggestionsList"> &nbsp;</div></div>
					<block visible="error.PEMILIK"><span class="form-field-error"><?php echo($error["PEMILIK"]); ?></span></block>--></td></tr><tr><td class="form-field-caption" valign="top" align="right">E.M.K.L</td><td valign="top" class="form-field-input">: <input id="KD_PELANGGAN2" name="KD_PELANGGAN2" type="hidden" value="<?php echo($row["KD_PBM2"]); ?>" readonly="1" size="3" /><input id="NM_PELANGGAN2" size="40" name="NM_PELANGGAN2" type="text" value="<?php echo($row["NAMA_PBM2"]); ?>" title="Autocomplete" class="kdemkl2" style="background-color:#FFFFCC;" /><input id="NPWP2" name="NPWP2" type="hidden" value="<?php echo($row["NO_NPWP_PBM"]); ?>" readonly="1" size="3" /><input id="ALAMAT2" name="ALAMAT2" type="hidden" value="<?php echo($row["ALAMAT"]); ?>" readonly="1" size="100" /></td></tr><tr><td class="form-field-caption" valign="top" align="right">No P.E.B </td><td valign="top" class="form-field-input">: <input id="id_NO_PEB" name="NO_PEB" type="text" value="<?php echo($row["PEB"]); ?>" size="15" style="background-color:#FFFFCC;" /><input id="id_KD_CABANG" name="KD_CABANG" type="hidden" value="<?php echo($kd_cbg); ?>" /></td></tr><tr><td class="form-field-caption" valign="top" align="right">No. N.P.E</td><td valign="top" class="form-field-input">: <input id="id_NO_NPE" name="NO_NPE" type="text" value="<?php echo($row["NPE"]); ?>" size="15" style="background-color:#FFFFCC;" /></td></tr><tr><td class="form-field-caption" valign="middle" align="right" width="177">No RO</td><td width="450" valign="top" class="form-field-input">: <input id="NO_RO" name="NO_RO" type="text" value="<?php echo($row["NO_RO"]); ?>" size="16" /><!--<img src="<?php echo($HOME); ?>images/ico_find.png" class="find" onmouseover="_hand(this)" onclick="popup_NO_BOOKING(this.value);" ><div id="ref_NO_BOOKING"></div><div class="suggestionsBox" id="popsuggestions_NO_BOOKING" style="display: none;"><div class="suggestionList" id="popSuggestionsList"> &nbsp;</div></div>
					<block visible="error.NO_BOOKING"><span class="form-field-error"><?php echo($error["NO_BOOKING"]); ?></span></block></td>--></tr><tr><td class="form-field-caption" valign="top" align="right">Nama Kapal </td><td valign="top" class="form-field-input">: <input id="KD_KAPAL" name="KD_KAPAL" type="hidden" value="<?php echo($row["KD_KAPAL"]); ?>" size="40" /><input id="NM_KAPAL" name="NM_KAPAL" type="text" value="<?php echo($row["NM_KAPAL"]); ?>" size="40" /><?php	if ($error["NO_UKK"]): ?><span class="form-field-error"><?php echo($error["NM_KAPAL"]); ?></span><?php	endif; ?><input id="CALL_SIGN" name="CALL_SIGN" type="hidden" value="<?php echo($row["CALL_SIGN"]); ?>" size="40" /></td></tr><tr><td class="form-field-caption" valign="top" align="right">Voyage</td><td valign="top" class="form-field-input">: <input id="VOYAGE_IN" name="VOYAGE_IN" type="text" value="<?php echo($row["VOYAGE_IN"]); ?>" size="5" readonly="1" /><input id="VOYAGE" name="VOYAGE" type="text" value="<?php echo($row["VOYAGE"]); ?>" size="5" readonly="1" /><input id="VOYAGE_OUT" name="VOYAGE_OUT" type="hidden" value="<?php echo($row["VOYAGE_OUT"]); ?>" size="3" readonly="1" /> &nbsp; <a class="form-field-caption">ETD</a> : <input value="<?php echo($row["TGL_BERANGKAT"]); ?>" id="ETD" name="ETD" type="text" size="8" readonly="1" /><a class="form-field-caption">ETA</a> : <input value="<?php echo($row["TGL_TIBA"]); ?>" id="ETA" name="ETA" type="text" size="8" readonly="1" /></td></tr><tr><td class="form-field-caption" valign="top" align="right">Nama Agen</td><td valign="top" class="form-field-input">: <input id="KD_AGEN" name="KD_AGEN" type="hidden" value="<?php echo($row["KD_AGEN"]); ?>" size="3" readonly="1" /><input id="TGL_BERANGKAT" name="TGL_BERANGKAT" type="hidden" value="<?php echo($row["TGL_BERANGKAT"]); ?>" size="3" readonly="1" /><input id="NM_AGEN" name="NM_AGEN" type="text" value="<?php echo($row["NM_AGEN"]); ?>" size="40" readonly="1" /><?php	if ($error["NO_UKK"]): ?><span class="form-field-error"><?php echo($error["NM_AGEN"]); ?></span><?php	endif; ?></td></tr></table></td><td width="51%"><table  border="0"><tr><td class="form-field-caption" valign="top" align="right" >No PKK</td><td valign="top" class="form-field-input">: <input id="NO_UKK" name="NO_UKK" type="text" value="<?php echo($row["NO_UKK"]); ?>" readonly="1" size="16" maxlength="16" title="Autocomplete" class="pkkkapal" /><!--<img src="<?php echo($HOME); ?>images/ico_find.png" class="find" onmouseover="_hand(this)" onclick="popup_NO_PKK(this.value);" ><div id="ref_NO_PKK"></div><div class="suggestionsBox" id="popsuggestions_NO_PKK" style="display: none;"><div class="suggestionList" id="popSuggestionsList"> &nbsp;</div></div>
					<block visible="error.NO_UKK"><span class="form-field-error"><?php echo($error["NO_UKK"]); ?></span></block>--></td></tr><tr><td class="form-field-caption" valign="middle" align="right" width="177">No Booking</td><td width="450" valign="top" class="form-field-input">: <input id="NO_BOOKING" name="NO_BOOKING" type="text" value="<?php echo($row["NO_BOOKING"]); ?>" size="16" maxlength="16" readonly="1" /><!--<img src="<?php echo($HOME); ?>images/ico_find.png" class="find" onmouseover="_hand(this)" onclick="popup_NO_BOOKING(this.value);" ><div id="ref_NO_BOOKING"></div><div class="suggestionsBox" id="popsuggestions_NO_BOOKING" style="display: none;"><div class="suggestionList" id="popSuggestionsList"> &nbsp;</div></div>
					<block visible="error.NO_BOOKING"><span class="form-field-error"><?php echo($error["NO_BOOKING"]); ?></span></block></td>--></tr><tr><td class="form-field-caption" valign="top" align="right" width="177">Port Of Destination</td><td valign="middle" class="form-field-input">: <input id="POL" name="POL" type="hidden" value="<?php echo($row["KD_PELABUHAN_TUJUAN"]); ?>" size="3" readonly="1" /><input id="KD_PELABUHAN_ASAL" name="KD_PELABUHAN_ASAL" type="hidden" value="IDPNK" size="3" class="pod" readonly="1" /><input id="NM_PELABUHAN_ASAL" name="NM_PELABUHAN_ASAL" type="text" value="PONTIANAK, INDONESIA" size="40" maxlength="100" style="background-color:#FFFFCC;" title="Autocomplete" class="pod" /></tr><tr><td class="form-field-caption" valign="top" align="right" width="177">Final Discharge</td><td valign="top" class="form-field-input">: <input id="KD_PELABUHAN_TUJUAN" name="KD_PELABUHAN_TUJUAN" type="hidden" value="<?php echo($row["KD_PELABUHAN_TUJUAN"]); ?>" size="3" class="pod2" readonly="1" /><input id="POD" name="POD" type="hidden" value="<?php echo($row["KD_PELABUHAN_ASAL"]); ?>" size="3" readonly="1" /><input id="NM_PELABUHAN_TUJUAN" name="NM_PELABUHAN_TUJUAN" type="text" value="<?php echo($row["NM_PELABUHAN_TUJUAN"]); ?>" size="40" maxlength="100" style="background-color:#FFFFCC;" class="pod2" title="Autocomplete" /></td></tr><!--<tr><td class="form-field-caption" valign="top" align="right">NO UKK</td><td class="form-field-input" valign="top">: <input id="id_NO_UKK" name="NO_UKK" type="text" value="<?php echo($row["NO_UKK"]); ?>" size="14" maxlength="14" /><block visible="error.NO_UKK"><span class="form-field-error"><?php echo($error["NO_UKK"]); ?></span></block></td></tr>--><tr><td class="form-field-caption" valign="middle" align="right">Keterangan </td><td class="form-field-input" valign="middle">:&nbsp;<textarea id="KETERANGAN" name="KETERANGAN" cols="40" style="background-color:#FFFFCC;" rows="1" ><?php echo($row["KETERANGAN"]); ?></textarea><?php	if ($error["PEB_KETERANGAN"]): ?><span class="form-field-error"><?php echo($error["PEB_KETERANGAN"]); ?></span><?php	endif; ?></td></tr><tr><td class="form-field-caption" valign="middle" align="right">Bayar Reefer</td><td>:&nbsp;<input id="id_SHIFT_RFR" name="SHIFT_RFR" style="background-color:#FFFFCC;" type="text" value="2" size="2" /> &nbsp;<b>* Shift</b><!--<img src="images/calculator.png" width="20" onclick="calculator()" />--></td></tr><tr><td class="form-field-caption" valign="middle" align="right">Calculator Shift Reefer </td><td>: <input id="TGL_STACKING" name="TGL_STACKING" type="hidden" value="<?php echo($row["TGL_STACKING"]); ?>" size="40" /><input id="TGL_MUAT" name="TGL_MUAT" type="hidden" value="<?php echo($row["TGL_MUAT"]); ?>" size="40" /><input type="text" maxlength="19" size="12" value="" id="ID_TGL_MULAI" name="TGL_MULAI" readonly="1" />&nbsp; s/d <input type="text" maxlength="19" size="12" value="" id="ID_TGL_NANTI" name="TGL_NANTI" readonly="1" />&nbsp; <img src="images/calculator.png" width="20" onclick="calculator()" /></td></tr></table></td></tr><tr><tr><td colspan="2" class="form-footer"><a class="link-button" onClick="cekearly()"><img src='images/cont_addnew.gif' border="0"> Simpan </a>&nbsp;</td></tr></table><!--</fieldset>--></form></fieldset><fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; "><center><table class="form-input" style="margin: 30px 30px 30px 30px;"  ><tr><td  class="form-field-caption"> Nomor Container </td><td> : </td><td><input type="text" name="NO_CONT" ID="NO_CONT" /></td><td style="width:50px;">&nbsp;</td><td  class="form-field-caption"> Ukuran </td><td> : </td><td><input type="text" name="SIZE" id="SIZE" readonly="readonly" size="5" /><input type="hidden" name="TGL_STACK" id="TGL_STACK" readonly="readonly" size="5" /><input type="hidden" name="ASAL" id="ASAL" readonly="readonly" size="5" /><input id="NO_REQUEST" name="NO_REQUEST" type="hidden" value="<?php echo($row["NO_REQUEST"]); ?>" /></td><td style="width:50px;">&nbsp;</td><td  class="form-field-caption"> Height </td><td> : </td><td><select id="hg" name="hg"><?php $this->renderSelectOptions(array (
  0 => 
  array (
    'value' => '8.6',
    'label' => '8.6',
  ),
  1 => 
  array (
    'value' => '9.6',
    'label' => '9.6',
  ),
  2 => 
  array (
    'value' => 'OOG',
    'label' => 'OOG',
  ),
),$false,$false,'0','1'); ?></select></td></tr><tr><td  class="form-field-caption"> Status </td><td> : </td><td><input type="text" name="STATUS" id="STATUS" readonly="readonly" size="5" /></td><!-- <td> <select name="STATUS" id="STATUS" class="kdemkl" onchange="set_status()">
			<option value="MTY">MTY</option> 			
			<option value="FCL">FCL</option> 
			<option value="LCL">LCL</option> 
			</select></td> --><td style="width:50px;">&nbsp;</td><td  class="form-field-caption"> TIPE </td><td> : </td><td><input type="text" name="TYPE" id="TYPE" readonly="readonly" size="5" /></td><td style="width:50px;">&nbsp;</td><td  class="form-field-caption"> Temp </td><td> : </td><td><input type="text" name="temp" id="temp" size="5" /></td></tr><tr><td  class="form-field-caption"> HZ/IMO/UN NUMBER </td><td> : </td><td><select name="HZ" id="HZ" onchange="check_hz()"><?php $this->renderSelectOptions(array (
  0 => 
  array (
    'value' => 'N',
    'label' => 'T',
  ),
  1 => 
  array (
    'value' => 'Y',
    'label' => 'Y',
  ),
),$false,$false,'0','1'); ?></select> &nbsp;<input type="text" id="imo" name="imo" size="3" /> &nbsp;<input type="text" id="unnumber" name="unnumber" size="3" /></td><td style="width:50px;">&nbsp;</td><td  class="form-field-caption"> Komoditi </td><td> : </td><td><input type="text" name="KOMODITI" ID="KOMODITI" /><input type="hidden" name="ID_KOMODITI" ID="ID_KOMODITI" /><input type="hidden" name="EX_PMB" ID="EX_PMB" /></td><td style="width:50px;">&nbsp;</td><td  class="form-field-caption"> Carrier </td><td> : </td><td><input type="text" name="carrier" id="carrier" size="5" /></td></tr><tr><td  class="form-field-caption"> VIA</td><td> : </td><td><select name="via" id="via"><?php $this->renderSelectOptions(array (
  0 => 
  array (
    'value' => 'darat',
    'label' => 'DARAT',
  ),
  1 => 
  array (
    'value' => 'tongkang',
    'label' => 'TONGKANG',
  ),
),$false,$false,'0','1'); ?></select></td><td style="width:50px;">&nbsp;</td><td  class="form-field-caption"> Berat </td><td> : </td><td><input type="text" name="berat" ID="berat" /></td><td style="width:50px;">&nbsp;</td><td  class="form-field-caption"> OH-OW-OL </td><td> : </td><td><input type="text" name="oh" id="oh" size="3" />-<input type="text" name="ow" id="ow" size="3" /> -<input type="text" name="ol" id="ol" size="3" /></td></tr><tr><td  class="form-field-caption"> No Seal</td><td> : </td><td><input type="text" name="no_seal" ID="no_seal" /></td><td style="width:50px;">&nbsp;</td><td  class="form-field-caption"> Keterangan</td><td> : </td><td><input type="text" name="keterangan" ID="keterangan" /></td></tr><tr><td  class="form-field-caption"> Tgl Stack</td><td> : </td><td><input type="text" readonly="readonly" name="TGL_STACK2" ID="TGL_STACK2" /></td><td style="width:50px;">&nbsp;</td><td  class="form-field-caption"> End Stack</td><td> : </td><td><input type="text" readonly="readonly" name="END_STACK" ID="END_STACK" /></td></tr><tr align="center"><td colspan="10"><a class="link-button" id='addcontbt' onClick="addNewRow()"><img src='images/cont_addnew.gif' border="0"> Tambahkan Container </a>&nbsp;</td></tr></table><div id="cont_list" style="margin: 10px 10px 10px 10px;"></div></center></fieldset><script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script><script>
function cekearly()
{
	
					//	alert ("Undifined Result");
						$NO_BOOKING = $("#NO_BOOKING").val();
						$KD_PBM     = $("#KD_PELANGGAN").val();
						$KD_PBM2    = $("#KD_PELANGGAN2").val(); 
						$NO_UKK		= $("#NO_UKK").val();
					if($NO_UKK != '' && $KD_PBM != '' && $KD_PBM2 != '' && $NO_BOOKING != '')
					{
						$.post("<?php echo($HOME); ?><?=APPID?>/cekearly/",{"NO_BOOKING":$NO_BOOKING,"KD_PBM":$KD_PBM, "NO_UKK":$NO_UKK}, function(data){ 
						//alert (data);
						if( data == 'T' ){
							$.blockUI({ message: '<div align=left style=font-size:15pt;font-weight:bold;color:red ><img src=\'images/cont_invalid.png\' align=absmiddle border=0> Closing Time Sudah Berakhir</div>' ,css: { 'left': '450px','margin-top': '100px', 'width': '350px', 'height': '40px', 'padding': '20px','align':'center' } });
							$('.blockOverlay').attr('title','Klik untuk menutup').click($.unblockUI); 
							setTimeout($.unblockUI, 2000); 
							return false;
								}else if( data == 'N' ){
									$.blockUI({ message: '<div align=left style=font-size:15pt;font-weight:bold;color:red ><img src=\'images/cont_invalid.png\' align=absmiddle border=0> EMKL Tidak di izin kan Early Stacking!</div>' ,css: { 'left': '450px','margin-top': '100px', 'width': '350px', 'height': '40px', 'padding': '20px','align':'center' } });
							$('.blockOverlay').attr('title','Klik untuk menutup').click($.unblockUI); 
							setTimeout($.unblockUI, 2000); 
							return false;				
								}else if( data == 'Y' ){
									$('#dataForm').submit();
								}else {alert ("Undifined Result");}
						});
						}else if($NO_UKK == '' || $KD_PBM == '' || $KD_PBM2 == '')
				{
					$.blockUI({ message: '<div align=left style=font-size:15pt;font-weight:bold;color:red ><img src=\'images/cont_invalid.png\' align=absmiddle border=0>Kapal dan EMKL Harus Diisi</div>' ,css: { 'left': '450px','margin-top': '100px', 'width': '350px', 'height': '40px', 'padding': '20px','align':'center' } });
							$('.blockOverlay').attr('title','Klik untuk menutup').click($.unblockUI); 
							setTimeout($.unblockUI, 2000); 
							return false;
				}else if($NO_BOOKING == '')
					{
					$.blockUI({ message: '<div align=left style=font-size:15pt;font-weight:bold;color:red ><img src=\'images/cont_invalid.png\' align=absmiddle border=0>Belum Booking Stack</div>' ,css: { 'left': '450px','margin-top': '100px', 'width': '350px', 'height': '40px', 'padding': '20px','align':'center' } });
							$('.blockOverlay').attr('title','Klik untuk menutup').click($.unblockUI); 
							setTimeout($.unblockUI, 2000); 
							return false;
					}
				

}

function save(){
 $("#dataForm").submit();
}

function set_status() 
{
	if($("#STATUS").val() == "EMPTY")
	{
              $('#HZ').val("N");
		$('#via').val("darat");
		$('#no_seal').val(" - ");
		$('#komoditi').val(" - ");
		$('#berat').val(" - ");
		
                
	}
	else
	{
		$('#via').val("darat");
		$('#HZ').val("N");
		
	}
}	

$(function() {	
	
	$( "#NM_PELANGGAN" ).autocomplete({
		minLength: 3,
		source: "<?php echo($HOME); ?><?php echo($APPID); ?>.auto/pbm",
		focus: function( event, ui ) {
			$( "#KD_PELANGGAN" ).val( ui.item.KD_PBM);
			return false;
		},
		select: function( event, ui ) {
			$( "#KD_PELANGGAN" ).val( ui.item.KD_PBM );
			$( "#NM_PELANGGAN" ).val( ui.item.NM_PBM );
			$( "#ALAMAT" ).val( ui.item.ALMT_PBM);
			$( "#NPWP" ).val( ui.item.NO_NPWP_PBM );


			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + item.NM_PBM + "<br />" + item.ALMT_PBM + "</a>")
			.appendTo( ul );
	}; 
    
    var v_kapal = $("#KD_KAPAL").val();
    var v_voyage = $("#VOYAGE").val();
    
    // $( "#carrier" ).autocomplete({
	// 	minLength: 2,
	// 	source: "<?php echo($HOME); ?><?php echo($APPID); ?>.auto/carrier?ves=" + v_kapal,
	// 	focus: function( event, ui ) {
	// 		$( "#carrier" ).val( ui.item.CODE);
	// 		return false;
	// 	},
	// 	select: function( event, ui ) {
	// 		$( "#carrier" ).val( ui.item.CODE );
	// 		$( "#cardet" ).val( ui.item.LINE_OPERATOR );


	// 		return false;
	// 	}
	// })
	// .data( "autocomplete" )._renderItem = function( ul, item ) {
	// 	return $( "<li></li>" )
	// 		.data( "item.autocomplete", item )
	// 		.append( "<a>" + item.CODE + "<br />" + item.LINE_OPERATOR + "</a>")
	// 		.appendTo( ul );
	// }; 

	$( "#carrier" ).autocomplete({
		minLength: 2,
		source: "<?php echo($HOME); ?><?php echo($APPID); ?>.auto/carrier_praya?voyage=" + v_voyage,
		focus: function(event, ui) {
			$( "#carrier" ).val( ui.item.operatorCode);
			return false;
		},
		select: function(event, ui) {
			$( "#carrier").val( ui.item.operatorCode);
			$( "#cardet" ).val( ui.item.operatorName );
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + item.operatorCode + "<br />" + item.operatorName + "</a>" )
			.appendTo( ul );
	};           

	$( "#NM_PELANGGAN2" ).autocomplete({
		minLength: 3,
		source: "<?php echo($HOME); ?><?php echo($APPID); ?>.auto/pbm",
		focus: function( event, ui ) {
			$( "#KD_PELANGGAN2" ).val( ui.item.KD_PBM);
			return false;
		},
		select: function( event, ui ) {
			$( "#KD_PELANGGAN2" ).val( ui.item.KD_PBM );
			$( "#NM_PELANGGAN2" ).val( ui.item.NM_PBM );
			$( "#ALAMAT2" ).val( ui.item.ALMT_PBM);
			$( "#NPWP2" ).val( ui.item.NO_NPWP_PBM );


			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + item.NM_PBM + "<br />" + item.ALMT_PBM + "</a>")
			.appendTo( ul );
	};   

	
	// $( "#NM_PELABUHAN_ASAL" ).autocomplete({
	// 	minLength: 3,
	// 	source: "<?php echo($HOME); ?><?php echo($APPID); ?>.auto/master_pelabuhan_praya",
	// 	focus: function( event, ui ) {
	// 		$( "#NM_PELABUHAN_ASAL" ).val( ui.item.NM_PELABUHAN);
	// 		return false;
	// 	},
	// 	select: function( event, ui ) {
	// 		$( "#KD_PELABUHAN_ASAL" ).val( ui.item.KD_PELABUHAN );
	// 		$( "#NM_PELABUHAN_ASAL" ).val( ui.item.NM_PELABUHAN );


	// 		return false;
	// 	}
	// })
	// .data( "autocomplete" )._renderItem = function( ul, item ) {
	// 	return $( "<li></li>" )
	// 		.data( "item.autocomplete", item )
	// 		.append( "<a>" + item.KD_PELABUHAN + " | " + item.NM_PELABUHAN+ "</a>")
	// 		.appendTo( ul );
	// }; 

	$( "#NM_PELABUHAN_ASAL" ).autocomplete({
		minLength: 3,
		source: "<?php echo($HOME); ?><?php echo($APPID); ?>.auto/master_pelabuhan_palapa",
		focus: function(event, ui) {
			$( "#NM_PELABUHAN_ASAL" ).val( ui.item.CDG_PORT_NAME);
			return false;
		},
		select: function(event, ui) {
			$( "#KD_PELABUHAN_ASAL" ).val( ui.item.CDG_PORT_CODE );
			$( "#NM_PELABUHAN_ASAL" ).val( ui.item.CDG_PORT_NAME );
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + item.CDG_PORT_CODE+ " | " + item.CDG_PORT_NAME+ "</a>" )
			.appendTo( ul );
	};    

	$( "#NM_PELABUHAN_TUJUAN" ).autocomplete({
		minLength: 3,
		source: "<?php echo($HOME); ?><?php echo($APPID); ?>.auto/master_pelabuhan_palapa",
		focus: function(event, ui) {
			$( "#NM_PELABUHAN_TUJUAN" ).val( ui.item.CDG_PORT_NAME);
			return false;
		},
		select: function(event, ui) {
			$( "#KD_PELABUHAN_TUJUAN" ).val( ui.item.CDG_PORT_CODE );
			$( "#NM_PELABUHAN_TUJUAN" ).val( ui.item.CDG_PORT_NAME );
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + item.CDG_PORT_CODE+ " | " + item.CDG_PORT_NAME+ "</a>" )
			.appendTo( ul );
	};  
	// $( "#NM_PELABUHAN_TUJUAN" ).autocomplete({
	// 	minLength: 3,
	// 	source: "<?php echo($HOME); ?><?php echo($APPID); ?>.auto/master_pelabuhan_praya",
	// 	focus: function(event, ui) {
	// 		$( "#NM_PELABUHAN_TUJUAN" ).val( ui.item.NM_PELABUHAN);
	// 		return false;
	// 	},
	// 	select: function(event, ui) {
	// 		$( "#KD_PELABUHAN_TUJUAN").val( ui.item.KD_PELABUHAN );
	// 		$( "#NM_PELABUHAN_TUJUAN").val( ui.item.NM_PELABUHAN );

	// 		return false;
	// 	}
	// })
	// .data( "autocomplete" )._renderItem = function( ul, item ) {
	// 	return $( "<li></li>" )
	// 		.data( "item.autocomplete", item )
	// 		.append( "<a>" + item.KD_PELABUHAN + " | " + item.NM_PELABUHAN+ "</a>")
	// 		.appendTo( ul );
	// };    

	$( "#NM_KAPAL" ).autocomplete({
		minLength: 3,
		source: "<?php echo($HOME); ?><?php echo($APPID); ?>.auto/master_vessel_palapa",
		focus: function(event, ui) {
			$( "#NM_KAPAL" ).val( ui.item.VESSEL);
			return false;
		},
		select: function(event, ui) {
			$( "#KD_KAPAL").val( ui.item.VESSEL_CODE);
			$( "#NM_AGEN").val( ui.item.OPERATOR_NAME);
			$( "#KD_AGEN").val( ui.item.OPERATOR_ID);
			$( "#NM_KAPAL").val( ui.item.VESSEL);
			$( "#VOYAGE_IN").val( ui.item.VOYAGE_IN);
			$( "#VOYAGE_OUT").val( ui.item.VOYAGE_OUT);
			$( "#NO_UKK").val( ui.item.ID_VSB_VOYAGE);
			if (ui.item.VESSEL_CODE && ui.item.ID_VSB_VOYAGE) {
                $("#NO_BOOKING").val(`BP${ui.item.VESSEL_CODE}${ui.item.ID_VSB_VOYAGE}`);
            }
            $("#CALL_SIGN").val(ui.item.CALL_SIGN);
			$( "#TGL_BERANGKAT").val(ui.item.ATD)
			$( "#TGL_STACKING").val(ui.item.OPEN_STACK)
			$( "#TGL_MUAT").val(ui.item.CLOSING_TIME_DOC)
			$( "#ETA").val(ui.item.ETA)
			$( "#ETD").val(ui.item.ETD)
			$( "#POL").val(ui.item.ID_POL);
			$( "#POD").val(ui.item.ID_POD);
			$( "#NM_PELABUHAN_ASAL").val(ui.item.POL)
			$( "#KD_PELABUHAN_ASAL").val(ui.item.ID_POL)
			$("#OPEN_STACK").val(ui.item.OPEN_STACK)
			$("#CONT_LIMIT").val(ui.item.CONTAINER_LIMIT)
			$("#CLOSING_TIME").val(ui.item.CLOSING_TIME)
			$("#CLOSING_TIME_DOC").val(ui.item.CLOSING_TIME_DOC)
			$("#VOYAGE").val(ui.item.VOYAGE);
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + item.VESSEL+ " | " + item.VOYAGE_IN+ "/" + item.VOYAGE_OUT + "</a>" )
			.appendTo( ul );
	};   


	
	 $( "#KOMODITI" ).autocomplete({
		minLength: 3,
		source: "<?php echo($HOME); ?><?php echo($APPID); ?>.auto/komoditi",
		focus: function(event, ui) {
			$( "#KOMODITI" ).val( ui.item.NM_COMMODITY);
			return false;
		},
		select: function(event, ui) {
			$( "#KOMODITI").val( ui.item.NM_COMMODITY);
			$( "#ID_KOMODITI").val( ui.item.KD_COMMODITY);
			
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + item.NM_COMMODITY+ "</a>")
			.appendTo( ul );
	};       
     
	var jn_repo = $("#JN_REPO").val(); 
	 
	$( "#NO_CONT" ).autocomplete({
	minLength: 3,
	source: "<?php echo($HOME); ?><?php echo($APPID); ?>.auto/cont_delivery?jn_repo=" + $("#JN_REPO").val(),
	focus: function( event, ui ) {
		$( "#NO_CONT" ).val( ui.item.NO_CONTAINER );
		return false;
	},
	select: function( event, ui ) {
		//alert($("#JN_REPO").val());
		if($("#JN_REPO").val() == 'EKS_STUFFING'){
			
			if(ui.item.NO_BOOKING != $("#NO_BOOKING").val() && ui.item.SOURCE == 'EXSTUF'){
				$.blockUI({ 
					message: '<h1>Kapal tidak sesuai saat req. stuffing, Harap Melakukan batal muat terlebih dahulu!</h1>', 
					timeout: 2000
				});
				$( "#NO_CONT" ).val( '' );
			}
			else {
			
				$( "#NO_CONT" ).val( ui.item.NO_CONTAINER );
				$( "#SIZE" ).val( ui.item.SIZE_);
				$( "#TYPE" ).val( ui.item.TYPE_);
				$( "#STATUS" ).val( ui.item.STATUS);
				$( "#TGL_STACK" ).val( ui.item.REALISASI_STUFFING);
				$( "#TGL_STACK2" ).val( ui.item.REALISASI_STUFFING);
				//alert(ui.item.REALISASI_STUFFING);
				$( "#ASAL" ).val( ui.item.ASAL);
				$( "#EX_PMB" ).val( ui.item.NO_BOOKING);
				if(ui.item.STATUS == null){
					$( "#STATUS" ).val('MTY');
				}
				$("#END_STACK").val($( "#TGL_BERANGKAT").val());
                if(ui.item.TYPE_ == 'HQ'){
                    $("#hg").val('9.6');
                }
			}
		}
		else {
			$( "#NO_CONT" ).val( ui.item.NO_CONTAINER );
				$( "#SIZE" ).val( ui.item.SIZE_);
				$( "#TYPE" ).val( ui.item.TYPE_);
				$( "#STATUS" ).val( ui.item.STATUS);
				$( "#TGL_STACK" ).val( ui.item.TGL_STACK);
				$( "#TGL_STACK2" ).val( ui.item.TGL_STACK);
				$( "#ASAL" ).val( ui.item.ASAL);
				$( "#EX_PMB" ).val( ui.item.NO_BOOKING);
				if(ui.item.STATUS == null){
					$( "#STATUS" ).val('MTY');
				}
                if(ui.item.TYPE_ == 'HQ'){
                    $("#hg").val('9.6');
                    //alert(ui.item.SIZE_);
                }
				if(ui.item.ASAL == 'UST'){
					var jn_repo				= $("#JN_REPO").val();
					$.post("<?php echo($HOME); ?><?php echo($APPID); ?>.auto/get_tgl_stack",{no_cont : ui.item.NO_CONTAINER, JN_REPO: jn_repo}, function(data){
						var datax = $.parseJSON(data);
						$("#TGL_STACK2").val(datax.TGL_BONGKAR);
					});
				}
				$("#END_STACK").val($( "#TGL_BERANGKAT").val());
		}
		return false;
	}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li style='text-align:left'></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + item.NO_CONTAINER + " - "+item.STATUS+" "+item.SIZE_+" "+item.TYPE_+"</a>" )
			.appendTo( ul );
	};
        
        
        
	$("#cont_list").load('<?=HOME?><?=APPID?>/edit_cont_list?no_req=<?php echo($row["NO_REQUEST"]); ?>&no_req2=<?php echo($no_req2); ?> #list', function(data) {        	  
	}); 	
});

function check_hz()
{
	var hz_ = $("#HZ").val();
	//alert(hz_);
	if(hz_=='Y')
	{
		$("#imo").removeAttr('disabled');
		$("#unnumber").removeAttr('disabled');
	}
	else
	{
		$("#imo").attr('disabled','disabled');
		$("#unnumber").attr('disabled','disabled');
	}
}
function addNewRow() 
{
		var komo = $("#ID_KOMODITI").val();
		var gross = $("#berat").val();
        
        if(komo == '' || gross == ''){
			alert('komoditi dan berat harap diisi');
			return false;
		} else {
				var $nocont 	= $('#NO_CONT').val();
				var $NO_UKK 	= $("#NO_UKK").val();
				var $NO_BOOKING = $('#NO_BOOKING').val();
				var $TYPE_NAME  = $('#TYPE').val();
				var $JENIS   	= $('#STATUS').val();				
				var $SIZE   	= $('#SIZE').val();

		if($('#NO_CONT').val().length>=1)
		{
			$.post("<?php echo($HOME); ?><?=APPID?>/ceknocont/",{"NO_UKK" : $NO_UKK, "NO_CONTAINER" : $nocont ,"NO_BOOKING":$NO_BOOKING,"TYPE":$TYPE_NAME,"JENIS":$JENIS,"SIZE":$SIZE}, function(data){
			if(data == 'Y'){
//				function add_cont()
//			{
			var no_cont_        	= $("#NO_CONT").val();
			var hz_                 = $("#HZ").val();
			var no_req_				= $("#NO_REQUEST").val();
			var no_req2_			= $("#NO_REQUEST2").val();
			var status_				= $("#STATUS").val();
			var komoditi_			= $("#KOMODITI").val();
			var kd_komoditi_		= $("#ID_KOMODITI").val();
			var keterangan_			= $("#keterangan").val();
			var no_seal_			= $("#no_seal").val();
			var berat_				= $("#berat").val();
			var via_				= $("#via").val();
			var size_				= $("#SIZE").val();
			var tipe_				= $("#TYPE").val();
			var no_booking_			= $("#NO_BOOKING").val();
			var no_ukk_				= $("#NO_UKK").val();
			var tgl_delivery		= $("#TGL_BERANGKAT").val();
			var tgl_stack			= $("#TGL_STACK2").val();
			var asal				= $("#ASAL").val();
			var jn_repo				= $("#JN_REPO").val();
			var ex_pmb				= $("#EX_PMB").val();
			var url					= "<?php echo($HOME); ?><?php echo($APPID); ?>.ajax/add_cont_dev";
            var imo_                = $("#imo").val();
            var unnumber_           = $("#unnumber").val();
            var temp_               = $("#temp").val();
            var carrier_            = $("#carrier").val();
            var oh_                 = $("#oh").val();
            var ow_                 = $("#ow").val();
            var ol_                 = $("#ol").val();
            var height              = $("#hg").val();
            var cont_limit_			= $("#CONT_LIMIT").val();
			$('#cont_list').html('<p align=center><img src=<?php echo($HOME); ?>images/loadingbox.gif /></p>');
			if(carrier_ == ''){
                alert('CARRIER HARUS DIISI');
                return false;
            }
            else if(tgl_stack == ''){
                alert('Tgl Stack Tidak Boleh Kosong');
                return false;
            }
            else if(size_ == '' || tipe_ == '' || status_== ''){
                alert('Size, Type, dan Status tidak boleh kosong');
                return false;
            }
            else if(hz_=='Y'){
                if(imo_ == '' || unnumber_ == ''){
                    alert('IMO CLASS DAN UN NUMBER WAJIB DIISI');
                    return false;
                }
            }
            else if(tipe_ == 'RFR' && temp_ == ''){
                    alert('TEMPERATURE WAJIB DIISI');
                    return false;
            }
            else {
                $("#addcontbt").attr("style","display:none");
			$.post(url,{KETERANGAN : keterangan_, NO_SEAL : no_seal_, BERAT : berat_, VIA : via_, KOMODITI: komoditi_, KD_KOMODITI: kd_komoditi_, NO_CONT: no_cont_, NO_REQ : no_req_, STATUS : status_, HZ : hz_, SIZE : size_, TIPE : tipe_, NO_BOOKING : no_booking_, NO_UKK : no_ukk_, NO_REQ2 : no_req2_, tgl_delivery : tgl_delivery, tgl_stack : tgl_stack, asal : asal, 
                        JN_REPO : jn_repo, EX_PMB : ex_pmb,IMO:imo_,UNNUMBER:unnumber_, HEIGHT:height, TEMP:temp_, CARRIER:carrier_,
                       OH:oh_,OW:ow_,OL:ol_,CONT_LIMIT:cont_limit_},function(data){
				console.log(data);
				if(data == "NOT_EXIST")
				{
					alert("Container Belum Terdaftar");	
				}
				else if(data == "CLOSING_TIME")
				{
					alert("Masa Closing Time Sudah Habis, Silakan Lakukan Booking Stack Pada Kapal Lain");
				}
				else if(data == "OK1")
				{
					alert("Berhasil Tambah Container");
					$("#cont_list").load('<?=HOME?><?=APPID?>/edit_cont_list?no_req=<?php echo($row["NO_REQUEST"]); ?>&no_req2=<?php echo($no_req2); ?> #list', function(data) {        	  
					}); 
				}
				else if(data == "BLM_PLACEMENT")
				{
						alert("Container Belum Placement");	
				} 
				else if(data == "SDH_REQUEST")
				{
						alert("Container Sudah Mengajukan Request Delivery");	
				}
				else if(data == "EXIST_REC")
				{
						alert("Container Masih Aktif di Req Receiving / Belum Gate In");
				}
				else if(data == "EXIST_DEL_BY_BOOKING")
				{
						alert("Container Sudah Di Request Pada Voyage Tersebut");
				}
				else if(data == "EXIST_MUAT")
				{
						alert("Container Dalam Proses Muat");
				}else if(data == "EXIST_TPK")
				{
						alert("Container Masih Aktif di TPK");
				}
				else if(data == "EXIST_STRIP")
				{
						alert("Container Masih Aktif di Req Stripping / Belum Realisasi");
				}
				else if(data == "EXIST_STUF")
				{
						aalert("Container Masih Aktif di Req Stuffing / Belum Realisasi");
				}
					$("#NO_CONT").val('');
					$("#HZ").val('');
					$("#STATUS").val('');
					//$("#KOMODITI").val('');
					//$("#ID_KOMODITI").val('');
					$("#keterangan").val('');
					$("#no_seal").val('');
					$("#via").val('');
					$("#SIZE").val('');
					$("#TYPE").val('');
					$("#TGL_STACK2").val('');
					$("#END_STACK").val('');
				$("#addcontbt").removeAttr("style");
				$("#cont_list").load('<?=HOME?><?=APPID?>/edit_cont_list?no_req=<?php echo($row["NO_REQUEST"]); ?>&no_req2=<?php echo($no_req2); ?> #list', function(data) {        	  
				}); 
			});	
            }
			//prosedur add comment disini, method $.post, include user id dan content id
		}
		//}
				else if(data=='T'){

						//	$.blockUI({ message: '<h2>Container Dalam Proses Muat!</h2>' ,css: { 'color':'red' , 'height': '60px', 'margin-top': '100px', 'padding-top': '20px' } });

							$.blockUI({ message: '<div align=left style=font-size:15pt;font-weight:bold;color:red ><img src=\'images/cont_invalid.png\' align=absmiddle border=0> Container Dalam Proses Muat!</div>' ,css: { 'left': '350px','margin-top': '100px', 'width': '550px', 'height': '40px', 'padding': '20px','align':'center' } });

							$('.blockOverlay').attr('title','Klik untuk menutup').click($.unblockUI); 

							setTimeout($.unblockUI, 2000); 

					}
					else if(data=='Z'){

						//	$.blockUI({ message: '<h2>Container Dalam Proses Muat!</h2>' ,css: { 'color':'red' , 'height': '60px', 'margin-top': '100px', 'padding-top': '20px' } });

							$.blockUI({ message: '<div align=left style=font-size:15pt;font-weight:bold;color:red ><img src=\'images/cont_invalid.png\' align=absmiddle border=0> Closing Time Dry/Reefer Sudah Habis!</div>' ,css: { 'left': '350px','margin-top': '100px', 'width': '550px', 'height': '40px', 'padding': '20px','align':'center' } });

							$('.blockOverlay').attr('title','Klik untuk menutup').click($.unblockUI); 

							setTimeout($.unblockUI, 3000); 

					}else if(data=='X'){

						//	$.blockUI({ message: '<h2>Container Dalam Proses Muat!</h2>' ,css: { 'color':'red' , 'height': '60px', 'margin-top': '100px', 'padding-top': '20px' } });

							$.blockUI({ message: '<div align=left style=font-size:15pt;font-weight:bold;color:red ><img src=\'images/cont_invalid.png\' align=absmiddle border=0> Jumlah Booking Teus Sudah Habis!</div>' ,css: { 'left': '350px','margin-top': '100px', 'width': '550px', 'height': '40px', 'padding': '20px','align':'center' } });

							$('.blockOverlay').attr('title','Klik untuk menutup').click($.unblockUI); 

							setTimeout($.unblockUI, 2000); 

					}
									});
		}
	}
}
		/*
function add_cont()
{
	var no_cont_        	= $("#NO_CONT").val();
    var hz_                 = $("#HZ").val();
	var no_req_				= $("#NO_REQUEST").val();
	var no_req2_			= $("#NO_REQUEST2").val();
	var status_				= $("#STATUS").val();
    var komoditi_			= $("#KOMODITI").val();
	var kd_komoditi_		= $("#ID_KOMODITI").val();
    var keterangan_			= $("#keterangan").val();
    var no_seal_			= $("#no_seal").val();
    var berat_				= $("#berat").val();
    var via_				= $("#via").val();
	var size_				= $("#SIZE").val();
	var tipe_				= $("#TYPE").val();
	var no_booking_			= $("#NO_BOOKING").val();
	var no_ukk_				= $("#NO_UKK").val();
	var tgl_delivery		= $("#TGL_BERANGKAT").val();
	var url					= "<?php echo($HOME); ?><?php echo($APPID); ?>.ajax/add_cont_dev";
	
	$('#cont_list').html('<p align=center><img src=<?php echo($HOME); ?>images/loadingbox.gif /></p>');
	
	$.post(url,{KETERANGAN : keterangan_, NO_SEAL : no_seal_, BERAT : berat_, VIA : via_, KOMODITI: komoditi_, KD_KOMODITI: kd_komoditi_, NO_CONT: no_cont_, NO_REQ : no_req_, STATUS : status_, HZ : hz_, SIZE : size_, TIPE : tipe_, NO_BOOKING : no_booking_, NO_UKK : no_ukk_, NO_REQ2 : no_req2_, tgl_delivery : tgl_delivery},function(data){
		console.log(data);
		if(data == "NOT_EXIST")
		{
			alert("Container Belum Terdaftar");	
		}
		else if(data == "CLOSING_TIME")
		{
			alert("Masa Closing Time Sudah Habis, Silakan Lakukan Booking Stack Pada Kapal Lain");
		}
		else if(data == "OK")
		{
			alert("Berhasil Tambah Container");
			$("#cont_list").load('<?=HOME?><?=APPID?>/edit_cont_list?no_req=<?php echo($row["NO_REQUEST"]); ?>&no_req2=<?php echo($no_req2); ?> #list', function(data) {        	  
			}); 
		}
		else if(data == "BLM_PLACEMENT")
		{
				alert("Container Belum Placement");	
		} 
		else if(data == "SDH_REQUEST")
		{
				alert("Container Sudah Mengajukan Request Delivery");	
		}
			$("#NO_CONT").val('');
			$("#HZ").val('');
			$("#STATUS").val('');
			$("#KOMODITI").val('');
			$("#ID_KOMODITI").val('');
			$("#keterangan").val('');
			$("#no_seal").val('');
			$("#berat").val('');
			$("#via").val('');
			$("#SIZE").val('');
			$("#TYPE").val('');
		
		$("#cont_list").load('<?=HOME?><?=APPID?>/edit_cont_list?no_req=<?php echo($row["NO_REQUEST"]); ?>&no_req2=<?php echo($no_req2); ?> #list', function(data) {        	  
		}); 
	});	
	//prosedur add comment disini, method $.post, include user id dan content id
}
*/
function del_cont($no_cont,$ex_bp)
{
	var no_req_		= "<?php echo($row["NO_REQUEST"]); ?>";//$("#NO_REQUEST").val(); 
	var url			= "<?php echo($HOME); ?><?php echo($APPID); ?>.ajax/del_cont";
	var no_req2_    = $("#NO_REQUEST2").val();
	$('#cont_list').html('<p align=center><img src=<?php echo($HOME); ?>images/loadingbox.gif /></p>');
	$.post(url,{NO_CONT: $no_cont, NO_REQ : no_req_, EX_BP : $ex_bp, NO_REQ2 : no_req2_},function(data){
		console.log(data);
		if(data == "OK")
		{
			alert("Berhasil menghapus container " + $no_cont);
			$("#cont_list").load('<?=HOME?><?=APPID?>/edit_cont_list?no_req=<?php echo($row["NO_REQUEST"]); ?>&no_req2=<?php echo($no_req2); ?> #list', function(data) {        	  
			}); 
			
		}
		else
		{
			alert("Gagal menghapus container " + $no_cont);
			$("#cont_list").load('<?=HOME?><?=APPID?>/edit_cont_list?no_req=<?php echo($row["NO_REQUEST"]); ?>&no_req2=<?php echo($no_req2); ?> #list', function(data) {        	  
			}); 		
		}
	});	
	
	$("#NO_CONT").val('');
	$("#HZ").val('');
	$("#STATUS").val('');
	$("#KOMODITI").val('');
	$("#ID_KOMODITI").val('');
	$("#keterangan").val('');
	$("#no_seal").val('');
	$("#berat").val('');
	$("#via").val('');
	$("#SIZE").val('');
	$("#TYPE").val('');
}

</script>