<?php if (!defined("XLITE_INCLUSION")) die(); ?><span class="graybrown"><img src='images/document-open.png' border='0' class="icon"/><font color="#DE7E21"> Request Delivery MTY / REPO MUAT- SP2 </font><font color="#0066CC"> ke TPK</font></span><br/><!--<span class="graybrown"><img src='images/dokumenbig.png' border='0' class="icon"/> <?php echo($formtitle); ?></span><br/><br/>--><form id="dataForm" name="dataForm" action="<?=HOME?><?=APPID?>/add_do_tpk" method="post" class="form-input" enctype="multipart/form-data"> <input type="hidden" name="__pbkey" value="" /><!--<fieldset class="form-fieldset">--><table cellspacing='2' cellpadding='2' border='0' width="100%" style="margin-top:10px;"><?php	if ($error): ?><tr><td colspan="2">Invalid Form Entry</td></tr><?php	endif; ?><!--<tr><td colspan="2"><b><?php echo($wizstep); ?></b></td></tr>--><tr><td width="49%" ><table><!--
                <tr>
					<td class="form-field-caption" valign="top" align="right">Permintaan Dari </td>
					<td valign="top" class="form-field-input">: 
					 <select name="REQUEST_BY" id="REQUEST_BY"><option value="">--Pilih -- </option> <option value="PELINDO">PT. PELINDO</option> <option value="CONSIGNEE">PEMILIK BARANG</option> </select>
					</td>
				</tr>
				--><tr><td class="form-field-caption" valign="top" align="right">Jenis Repo</td><td valign="top" class="form-field-input">: <select id="JN_REPO" name="JN_REPO"><?php $this->renderSelectOptions(array (
  0 => 
  array (
    'value' => '',
    'label' => ' PILIH ',
  ),
  1 => 
  array (
    'value' => 'EKS_STUFFING',
    'label' => 'EKS STUFFING',
  ),
  2 => 
  array (
    'value' => 'EMPTY',
    'label' => 'EMPTY',
  ),
  3 => 
  array (
    'value' => 'FULL',
    'label' => 'FULL',
  ),
),$false,$false,'0','1'); ?></select></tr><tr><td class="form-field-caption" valign="top" align="right">International/Domestik</td><td  valign="top" class="form-field-input"> : <select id="DI" name="DI"><?php $this->renderSelectOptions(array (
  0 => 
  array (
    'value' => 'D',
    'label' => 'Domestik',
  ),
  1 => 
  array (
    'value' => 'I',
    'label' => 'International',
  ),
),$false,$false,'0','1'); ?></select></td></tr><input id="REQUEST_BY" name="REQUEST_BY" type="hidden" value="PELINDO" /></td><!-- <tr>
					<td class="form-field-caption" valign="top" align="right">No Request Stuffing</td>
					
					<td valign="top" class="form-field-input">:
					<input id="NO_REQ_STUFF" name="NO_REQ_STUFF" type="text" value="<?php echo($row["NO_REQ_STUFF"]); ?>" size="20"   />
					</td>
				</tr> --><tr><td class="form-field-caption" valign="top" align="right">No P.E.B </td><td valign="top" class="form-field-input">: <input id="NO_PEB" name="NO_PEB" type="text" value="<?php echo($row["NO_PEB"]); ?>" size="15" style="background-color:#FFFFCC;" /><input id="id_KD_CABANG" name="KD_CABANG" type="hidden" value="<?php echo($KD_CABANG); ?>" /></td></tr><tr><td class="form-field-caption" valign="top" align="right">No. N.P.E</td><td valign="top" class="form-field-input">: <input id="NO_NPE" name="NO_NPE" type="text" value="<?php echo($row["NO_NPE"]); ?>" size="15" style="background-color:#FFFFCC;" /></td></tr><tr><td class="form-field-caption" valign="middle" align="right" width="177">No RO</td><td width="450" valign="top" class="form-field-input">: <input id="NO_RO" name="NO_RO" type="text" value="" size="16" /><!--<img src="<?php echo($HOME); ?>images/ico_find.png" class="find" onmouseover="_hand(this)" onclick="popup_NO_BOOKING(this.value);" ><div id="ref_NO_BOOKING"></div><div class="suggestionsBox" id="popsuggestions_NO_BOOKING" style="display: none;"><div class="suggestionList" id="popSuggestionsList"> &nbsp;</div></div>
					<block visible="error.NO_BOOKING"><span class="form-field-error"><?php echo($error["NO_BOOKING"]); ?></span></block></td>--></tr><tr><td class="form-field-caption" valign="top" align="right">E.M.K.L</td><td valign="top" class="form-field-input">: <input id="KD_PELANGGAN" name="KD_PELANGGAN" type="hidden" value="<?php echo($row["KD_PELANGGAN"]); ?>" readonly="1" size="3" /><input id="NPWP" name="NPWP" type="hidden" value="<?php echo($row["NO_NPWP_PBM"]); ?>" readonly="1" size="3" /><input id="NO_ACCOUNT_PBM" name="NO_ACCOUNT_PBM" type="hidden" value="<?php echo($row["NO_ACCOUNT_PBM"]); ?>" readonly="1" size="3" /><input id="ALAMAT" name="ALAMAT" type="hidden" value="<?php echo($row["ALAMAT"]); ?>" readonly="1" size="100" /><input id="NM_PELANGGAN" size="40" name="NM_PELANGGAN" type="text" value="<?php echo($row["NM_PELANGGAN"]); ?>" class="kdemkl" title="Autocomplete" style="background-color:#FFFFCC;" /><!--<img src="<?php echo($HOME); ?>images/ico_find.png" class="find" onmouseover="_hand(this)" onclick="popup_PELANGGAN(this.value);" ><div id="ref_PELANGGAN"></div><div class="suggestionsBox" id="popsuggestions_PELANGGAN" style="display: none;"><div class="suggestionList" id="popSuggestionsList"> &nbsp;</div></div>
					<block visible="error.PEMILIK"><span class="form-field-error"><?php echo($error["PEMILIK"]); ?></span></block>--></td></tr><tr style="display:none"><td class="form-field-caption" valign="top" align="right">Penumpukan Empty Oleh</td><td valign="top" class="form-field-input">: <input id="KD_PELANGGAN2" name="KD_PELANGGAN2" type="hidden" value="<?php echo($row["KD_PELANGGAN2"]); ?>" readonly="1" size="3" /><input id="NM_PELANGGAN2" size="40" name="NM_PELANGGAN2" type="text" value="<?php echo($row["NM_PELANGGAN2"]); ?>" title="Autocomplete" class="kdemkl2" style="background-color:#FFFFCC;" /><input id="NPWP2" name="NPWP2" type="hidden" value="<?php echo($row["NO_NPWP_PBM"]); ?>" readonly="1" size="3" /><input id="ALAMAT2" name="ALAMAT2" type="hidden" value="<?php echo($row["ALAMAT"]); ?>" readonly="1" size="100" /></td></tr><tr><td class="form-field-caption" valign="top" align="right">Nama Kapal </td><td valign="top" class="form-field-input">: <input id="KD_KAPAL" name="KD_KAPAL" type="hidden" value="<?php echo($row["KD_KAPAL"]); ?>" size="40" /><input id="CALL_SIGN" name="CALL_SIGN" type="hidden" value="<?php echo($row["CALL_SIGN"]); ?>" size="40" /><input id="NM_KAPAL" name="NM_KAPAL" type="text" value="<?php echo($row["NM_KAPAL"]); ?>" size="40" /><input id="TGL_BERANGKAT" name="TGL_BERANGKAT" type="hidden" value="<?php echo($row["TGL_BERANGKAT"]); ?>" size="40" /><input id="TGL_STACKING" name="TGL_STACKING" type="hidden" value="<?php echo($row["TGL_STACKING"]); ?>" size="40" /><input id="TGL_MUAT" name="TGL_MUAT" type="hidden" value="<?php echo($row["TGL_MUAT"]); ?>" size="40" /><input id="POD" name="POD" type="hidden" value="<?php echo($row["POD"]); ?>" /><input id="POL" name="POL" type="hidden" value="<?php echo($row["POL"]); ?>" /><input id="CLOSING_TIME" name="CLOSING_TIME" type="hidden" value="<?php echo($row["CLOSING_TIME"]); ?>" /><input id="CLOSING_TIME_DOC" name="CLOSING_TIME_DOC" type="hidden" value="<?php echo($row["CLOSING_TIME_DOC"]); ?>" /><?php	if ($error["NO_UKK"]): ?><span class="form-field-error"><?php echo($error["NM_KAPAL"]); ?></span><?php	endif; ?></td></tr><tr><td class="form-field-caption" valign="top" align="right">Voyage</td><td valign="top" class="form-field-input">: <input id="VOYAGE_IN" name="VOYAGE_IN" type="text" value="<?php echo($row["VOYAGE_IN"]); ?>" size="5" readonly="1" /> - <input id="VOYAGE_OUT" name="VOYAGE_OUT" type="text" value="<?php echo($row["VOYAGE_OUT"]); ?>" size="5" readonly="1" /> &nbsp; <a class="form-field-caption">ETA</a> : <input id="ETA" name="ETA" type="text" size="8" readonly="1" /><a class="form-field-caption">ETD</a> : <input id="ETD" name="ETD" type="text" size="8" readonly="1" /></td><input id="OPEN_STACK" name="OPEN_STACK" type="hidden" value="<?php echo($row["OPEN_STACK"]); ?>" /><input id="CONT_LIMIT" name="CONT_LIMIT" type="hidden" value="<?php echo($row["CONT_LIMIT"]); ?>" /><input id="VOYAGE" name="VOYAGE" type="hidden" value="<?php echo($row["VOYAGE"]); ?>" /></tr><tr><td class="form-field-caption" valign="top" align="right">Nama Agen</td><td valign="top" class="form-field-input">: <input id="KD_AGEN" name="KD_AGEN" type="hidden" value="<?php echo($row["KD_AGEN"]); ?>" /><input id="NM_AGEN" name="NM_AGEN" type="text" value="<?php echo($row["NM_AGEN"]); ?>" size="40" readonly="1" /><?php	if ($error["NO_UKK"]): ?><span class="form-field-error"><?php echo($error["NM_AGEN"]); ?></span><?php	endif; ?></td></tr></table></td><td width="51%"><table  border="0"><tr><td class="form-field-caption" valign="top" align="right" >No PKK</td><td valign="top" class="form-field-input">: <input id="NO_UKK" name="NO_UKK" type="text" value="<?php echo($row["NO_UKK"]); ?>" readonly="1" size="16" maxlength="16" title="Autocomplete" class="pkkkapal" /><!--<img src="<?php echo($HOME); ?>images/ico_find.png" class="find" onmouseover="_hand(this)" onclick="popup_NO_PKK(this.value);" ><div id="ref_NO_PKK"></div><div class="suggestionsBox" id="popsuggestions_NO_PKK" style="display: none;"><div class="suggestionList" id="popSuggestionsList"> &nbsp;</div></div>
					<block visible="error.NO_UKK"><span class="form-field-error"><?php echo($error["NO_UKK"]); ?></span></block>--></td></tr><tr><td class="form-field-caption" valign="middle" align="right" width="177">No Booking</td><td width="450" valign="top" class="form-field-input">: <input id="NO_BOOKING" name="NO_BOOKING" type="text" value="<?php echo($row["NO_BOOKING"]); ?>" size="16" maxlength="16" readonly="1" /><!--<img src="<?php echo($HOME); ?>images/ico_find.png" class="find" onmouseover="_hand(this)" onclick="popup_NO_BOOKING(this.value);" ><div id="ref_NO_BOOKING"></div><div class="suggestionsBox" id="popsuggestions_NO_BOOKING" style="display: none;"><div class="suggestionList" id="popSuggestionsList"> &nbsp;</div></div>
					<block visible="error.NO_BOOKING"><span class="form-field-error"><?php echo($error["NO_BOOKING"]); ?></span></block></td>--></tr><tr><td class="form-field-caption" valign="top" align="right" width="177">Pelabuhan Asal</td><td valign="middle" class="form-field-input">: <input id="KD_PELABUHAN_ASAL" name="KD_PELABUHAN_ASAL" type="hidden" value="IDPNK" size="3" class="pod" readonly="1" /><input id="NM_PELABUHAN_ASAL" name="NM_PELABUHAN_ASAL" type="text" value="<?php echo($row["NM_PELABUHAN_ASAL"]); ?>" size="40" maxlength="100" style="background-color:#FFFFCC;" title="Autocomplete" class="pod" readonly="1" /><!--<img src="<?php echo($HOME); ?>images/ico_find.png" class="find" onclick="popup_PEB_DEST(this.value);" onmouseover="_hand(this)" ><div id="ref_PEB_DEST"></div><div class="suggestionsBox" id="popsuggestions_PEB_DEST" style="display: none;"><div class="suggestionList" id="popSuggestionsList"> &nbsp;</div></div>
					<block visible="error.PEB_DEST"><span class="form-field-error"><?php echo($error["PEB_DEST"]); ?></span></block>--></td><input id="id_TGL_STACK" name="TGL_STACK" type="hidden" value="<?php echo($tglskr); ?>" size="19" maxlength="19" /></tr><tr><td class="form-field-caption" valign="top" align="right" width="177">Port of Discharge</td><td valign="top" class="form-field-input">: <input id="KD_PELABUHAN_TUJUAN" name="KD_PELABUHAN_TUJUAN" type="hidden" value="<?php echo($row["KD_PELABUHAN_TUJUAN"]); ?>" size="3" class="pod2" readonly="1" /><input id="NM_PELABUHAN_TUJUAN" name="NM_PELABUHAN_TUJUAN" type="text" value="<?php echo($row["NM_PELABUHAN_TUJUAN"]); ?>" size="40" maxlength="100" style="background-color:#FFFFCC;" class="pod2" title="Autocomplete" /></td></tr><!--<tr><td class="form-field-caption" valign="top" align="right">NO UKK</td><td class="form-field-input" valign="top">: <input id="id_NO_UKK" name="NO_UKK" type="text" value="<?php echo($row["NO_UKK"]); ?>" size="14" maxlength="14" /><block visible="error.NO_UKK"><span class="form-field-error"><?php echo($error["NO_UKK"]); ?></span></block></td></tr>--><!--<tr><td class="form-field-caption" valign="top" align="right">Tgl Rencana Penumpukan</td><td class="form-field-input" valign="top">: <input id="id_TGL_STACK" name="TGL_STACK" type="date" value="<?php echo($row["TGL_STACK"]); ?>" size="19" maxlength="19" /> s / d <input id="id_TGL_MUAT" name="TGL_MUAT" type="date" value="<?php echo($tglmuat); ?>" size="19" maxlength="19" /><block visible="error.TGL_MUAT"><span class="form-field-error"><?php echo($error["TGL_MUAT"]); ?></span></block></td></tr>--><tr><td class="form-field-caption" valign="middle" align="right">Keterangan </td><td class="form-field-input" valign="middle">:&nbsp;<textarea id="KETERANGAN" name="KETERANGAN" cols="40" style="background-color:#FFFFCC;" rows="1" ></textarea><?php	if ($error["PEB_KETERANGAN"]): ?><span class="form-field-error"><?php echo($error["PEB_KETERANGAN"]); ?></span><?php	endif; ?></td></tr><tr><td class="form-field-caption" valign="middle" align="right">Bayar Reefer</td><td>:&nbsp;<input disabled="1" id="SHIFT_RFR" name="SHIFT_RFR" style="background-color:#FFFFCC;" type="text" value="2" size="2" /> &nbsp;<b>* Shift</b><!--<img src="images/calculator.png" width="20" onclick="calculator()" />--></td></tr><tr><td class="form-field-caption" valign="middle" align="right">Calculator Shift Reefer </td><td>: <input disabled="1" type="text" maxlength="19" size="12" value="" id="ID_TGL_MULAI" name="TGL_MULAI" readonly="1" />&nbsp; s/d <input disabled="1" type="text" maxlength="19" size="12" value="" id="ID_TGL_NANTI" name="TGL_NANTI" readonly="1" />&nbsp; <img src="images/calculator.png" width="20" onclick="calculator()" /></td></tr></table></td></tr><tr><tr><td colspan="2" class="form-footer"><a class="link-button" onClick="cekearly()"><img src='images/cont_addnew.gif' border="0"> Simpan </a>&nbsp; </td></tr></table><!--</fieldset>--></form><script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script><script>



function cekearly()
{
	    var CONSIGNEE = $('#NM_PELANGGAN').val();
		Swal.fire({
			title: 'Mengecheck Pengkinian NPWP',
			allowOutsideClick: false,
			didOpen: function () {
				Swal.showLoading();
			}
		});
		$.ajax({
			url: '/uster.maintenance.api_history.ajax/UpdateNPWPByEMKL',
			type: 'POST',
			data: {
				EMKL: CONSIGNEE,
			},
			dataType: 'json',
			success: function (response) {
				if (response.message && response.status == '1' && response.activity == 'update') {
					Swal.fire({
						title: 'Success!',
						text: response.message,
						icon: 'success',
						confirmButtonText: 'OK'
					});
				} else if (response.message && response.status == '1' && response.activity == 'pass') {
					Swal.close();
					//alert ("Undifined Result");
					$NO_BOOKING = $("#NO_BOOKING").val();
					$KD_PBM     = $("#KD_PELANGGAN").val();
					$KD_PELABUHAN_ASAL     = $("#KD_PELABUHAN_ASAL").val();
					$KD_PELABUHAN_TUJUAN     = $("#KD_PELABUHAN_TUJUAN").val();
					//$KD_PBM2    = $("#KD_PELANGGAN2").val(); 
					$NO_UKK		= $("#NO_UKK").val();
					$JN_REPO	= $("#JN_REPO").val();

					if($NO_UKK == '' || $KD_PBM == '')
					{
						$.blockUI({ message: '<div align=left style=font-size:15pt;font-weight:bold;color:red ><img src=\'images/cont_invalid.png\' align=absmiddle border=0>Kapal dan EMKL Harus Diisi</div>' ,css: { 'left': '450px','margin-top': '100px', 'width': '350px', 'height': '40px', 'padding': '20px','align':'center' } });
								$('.blockOverlay').attr('title','Klik untuk menutup').click($.unblockUI); 
								setTimeout($.unblockUI, 2000); 
								return false;
					}
					else if($NO_BOOKING == '' )
					{
						$.blockUI({ message: '<div align=left style=font-size:15pt;font-weight:bold;color:red ><img src=\'images/cont_invalid.png\' align=absmiddle border=0>Belum Booking Stack</div>' ,css: { 'left': '450px','margin-top': '100px', 'width': '350px', 'height': '40px', 'padding': '20px','align':'center' } });
							$('.blockOverlay').attr('title','Klik untuk menutup').click($.unblockUI); 
							setTimeout($.unblockUI, 2000); 
							return false;
					}
					else if($JN_REPO == '' )
					{
						$.blockUI({ message: '<div align=left style=font-size:15pt;font-weight:bold;color:red ><img src=\'images/cont_invalid.png\' align=absmiddle border=0>Jenis Repo Harus Diisi</div>' ,css: { 'left': '450px','margin-top': '100px', 'width': '350px', 'height': '40px', 'padding': '20px','align':'center' } });
							$('.blockOverlay').attr('title','Klik untuk menutup').click($.unblockUI); 
							setTimeout($.unblockUI, 2000); 
							return false;
					}
					else if($KD_PELABUHAN_ASAL == '' || $KD_PELABUHAN_TUJUAN == '' )
					{
						$.blockUI({ message: '<div align=left style=font-size:15pt;font-weight:bold;color:red ><img src=\'images/cont_invalid.png\' align=absmiddle border=0>POD / Final Discharge Harus Diisi</div>' ,css: { 'left': '450px','margin-top': '100px', 'width': '350px', 'height': '40px', 'padding': '20px','align':'center' } });
							$('.blockOverlay').attr('title','Klik untuk menutup').click($.unblockUI); 
							setTimeout($.unblockUI, 2000); 
							return false;
					}
					else {
						$('#dataForm').submit();
					}
				} else {
					Swal.fire({
						title: 'Error!',
						text: response.message,
						icon: 'error',
						confirmButtonText: 'OK'
					});
				}
			},
			error: function (xhr, status, error) {
				Swal.fire({
					title: 'Error!',
					text: 'Error: ' + error,
					icon: 'error',
					confirmButtonText: 'OK'
				});
			}
		});
	
}
		
function save(){
	$("#dataForm").submit();
}


$(function() {
	
	$("#NO_REQ_STUFF").prop('disabled', true);
	$("#NO_REQ_STUFF").css("background-color","#F0F0F0");

	$("#JN_REPO").change(function(){
	
		if($("#JN_REPO").val()=="EKS_STUFFING")
		{
			//alert("tes");
			$("#NO_REQ_STUFF").prop('disabled', false);
			$("#NO_REQ_STUFF").css("background-color","#FFFFCC");
			//hari = $("#HARI_STUFF").val();
			//alert(hari);
			
			
			
		}else 
		{
			//alert("tes2");
			$("#NO_REQ_STUFF").css("background-color","#F0F0F0");
			$("#NO_REQ_STUFF").prop('disabled', true);
			//hari = $("#NO_REQ_STUFF").val();
			//alert(hari);
			
						
		}
	
	});





	
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
			$( "#NO_ACCOUNT_PBM" ).val( ui.item.NO_ACCOUNT_PBM );


			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + item.NM_PBM + "<br />" + item.ALMT_PBM + "</a>")
			.appendTo( ul );
	};    

	/*$( "#NM_PELANGGAN2" ).autocomplete({
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
	};   */

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
	
	
	//NO_REQ_STUFF
	/*
	$( "#NO_REQ_STUFF" ).autocomplete({
		minLength: 3,
		source: "<?php echo($HOME); ?><?php echo($APPID); ?>.auto/list_req_stuff",
		focus: function(event, ui) {
			$( "#NO_REQ_STUFF" ).val( ui.item.NO_REQUEST);
			return false;
		},
		select: function(event, ui) {
			$( "#NO_REQ_STUFF").val( ui.item.NO_REQUEST);
			$( "#NM_AGEN").val( ui.item.NM_AGEN);
			$( "#KD_AGEN").val( ui.item.KD_AGEN);
			$( "#NM_KAPAL").val( ui.item.NM_KAPAL);
			$( "#VOYAGE_IN").val( ui.item.VOYAGE);
			$( "#NO_UKK").val( ui.item.NO_UKK);
			$( "#NO_BOOKING").val( ui.item.NO_BOOKING);
			$( "#NO_NPE").val( ui.item.NO_NPE);
			$( "#NO_PEB").val( ui.item.NO_PEB);
			$( "#KD_PELANGGAN").val( ui.item.KD_PBM);
			$( "#NM_PELANGGAN").val( ui.item.NM_PBM);
			$( "#KD_PELABUHAN_ASAL").val( ui.item.KD_PELABUHAN_ASAL);
			$( "#KD_PELABUHAN_TUJUAN").val( ui.item.KD_PELABUHAN_TUJUAN);
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + item.NO_REQUEST+  "</a>")
			.appendTo( ul );
	};
	*/
	
	
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
     
        
	$("#cont_list").load('<?=HOME?><?=APPID?>/edit_cont_list?no_req=<?php echo($row_request["NO_REQUEST"]); ?> #list', function(data) {        	  
	}); 	
});

function add_cont()
{
		var no_cont_    = $("#NO_CONT").val();
        var hz_         = $("#HZ").val();
		var no_req_		= "<?php echo($row_request["NO_REQUEST"]); ?>";
		var status_		= $("#STATUS").val();
        var komoditi_	= $("#komoditi").val();
        var keterangan_	= $("#keterangan").val();
        var no_seal_	= $("#no_seal").val();
        var berat_		= $("#berat").val();
        var via_		= $("#via").val();
		var no_ukk_		= $("#ukk").val();
		var shift_rfr_	= $("#shift_rfr").val();
		var tgl_stacking_	= $("#tgl_stacking").val();
				
		var url			= "<?php echo($HOME); ?><?php echo($APPID); ?>.ajax/add_cont_dev";
	
	
	$.post(url,{KETERANGAN : keterangan_, NO_SEAL : no_seal_, BERAT : berat_, VIA : via_, KOMODITI: komoditi_, NO_CONT: no_cont_, NO_REQ : no_req_, STATUS : status_, HZ : hz_, NO_UKK : no_ukk_, SHIFT_RFR : shift_rfr_, TGL_STACKING : tgl_stacking_, TGL_MUAT : tgl_muat_ },function(data){
		console.log(data);
		if(data == "NOT_EXIST")
		{
			alert("Container Belum Terdaftar");	
		}
		else if(data == "OK")
		{
			$("#cont_list").load('<?=HOME?><?=APPID?>/edit_cont_list?no_req=<?php echo($row_request["NO_REQUEST"]); ?> #list', function(data) {        	  
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
	});	
	//prosedur add comment disini, method $.post, include user id dan content id
}

function del_cont($no_cont)
{
	var no_req_		= "<?php echo($row_request["NO_REQUEST"]); ?>";
	var url			= "<?php echo($HOME); ?><?php echo($APPID); ?>.ajax/del_cont";
	
	$.post(url,{NO_CONT: $no_cont, NO_REQ : no_req_},function(data){
		console.log(data);
		if(data == "OK")
		{
			$("#cont_list").load('<?=HOME?><?=APPID?>/cont_list?no_req=<?php echo($row_request["NO_REQUEST"]); ?> #list', function(data) {        	  
			}); 	
		}
	});	
}

$(function() {	
	
	$( "#tgl_req_dev" ).datepicker();
	$( "#tgl_req_dev" ).datepicker( "option", "dateFormat", "yy-mm-dd" );

	$( "#ID_TGL_MULAI" ).datepicker();
	$( "#ID_TGL_MULAI" ).datepicker( "option", "dateFormat", "yy-mm-dd" );

	$( "#ID_TGL_NANTI" ).datepicker();
	$( "#ID_TGL_NANTI" ).datepicker( "option", "dateFormat", "yy-mm-dd" );

	$( "#TGL_REQ" ).datepicker();
	$( "#TGL_REQ" ).datepicker( "option", "dateFormat", "yy-mm-dd" );

});

function calculator(){
//	$.blockUI({ message: '<div id=\"divmsg\"><img src=\"images/cont_loading.gif\" /> <h2>Please wait..</h2></div>' ,css: {'color':'blue', 'height': '90px', 'margin-top': '100px', 'padding-top': '0px' }});
	$mulai = $('#ID_TGL_MULAI').val();
	$nanti = $('#ID_TGL_NANTI').val();
	$.post("<?php echo($HOME); ?><?=APPID?>/refcal/",{'mulai': $mulai, 'nanti':$nanti},function(data){
		//$.blockUI({ message: '<div id=\"divmsg\">'+data+'</div>' ,css: {'color':'blue', 'height': '90px', 'margin-top': '100px', 'padding-top': '0px' }});
		$('#SHIFT_RFR').val(data);
	});
}

</script>