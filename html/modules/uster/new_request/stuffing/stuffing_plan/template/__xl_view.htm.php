<?php if (!defined("XLITE_INCLUSION")) die(); ?><span class="graybrown"><img src='images/document-open.png' border='0' class="icon"/><font color="#DE7E21"> Request Stuffing Petikemas</font></span><fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; "><form action="<?=HOME?><?=APPID?>/add_edit" method="POST"> <fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px; "><legend>&nbsp &nbsp <font color="red"><b>Entry Nama Pengguna Jasa</b></font></legend><table style="margin: 10px 10px 10px 10px;" ><tr><td class="form-field-caption" valign="middle" align="right">No Request</td><td  class="form-field-caption"> : </td><td><input type="text" size="20" id="no_req" name="NO_REQ" value="<?php echo($row_request["NO_REQUEST"]); ?>" readonly="readonly" /><input type="text" size="20" name="NO_REQUEST2" id="NO_REQUEST2" value="<?php echo($no_req2); ?>" /><input type="text" name="NO_REQUEST3" id="NO_REQUEST3" value="<?php echo($no_req3); ?>" /></td></tr><tr><td class="form-field-caption" valign="middle" align="right">Nama EMKL</td><td  class="form-field-caption"> : </td><td><input type="text" size="40" name="EMKL" id="EMKL" placeholder="<?php echo($row_request["NAMA_EMKL"]); ?>" /><input type="hidden" name="ID_EMKL" id="ID_EMKL" value="<?php echo($row_request["ID_EMKL"]); ?>" /></td></tr><tr><td class="form-field-caption" valign="middle" align="right">Penumpukan Oleh</td><td  class="form-field-caption"> : </td><td><input type="text" name="PNKN_BY" id="PNKN_BY" placeholder="<?php echo($row_request["NAMA_PNMT"]); ?>" size="40" maxlength="100" style="background-color:#FFFFCC;" /><input type="hidden" name="ID_PNKN_BY" id="ID_PNKN_BY" value="<?php echo($row_request["ID_PENUMPUKAN"]); ?>" /></td></tr><tr><td class="form-field-caption" valign="middle" align="right">International/Domestik</td><td  class="form-field-caption"> : </td><td><input type="input" value="<?php echo($row_request["DI"]); ?>" name="DI" id="DI" /></td></tr></table></fieldset><fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px; "><legend> &nbsp &nbsp <font color="red"><b>Entry Nama Kapal</b></font></legend><table style="margin: 10px 10px 10px 10px;" ><tr><td class="form-field-caption" valign="top" align="right">Nama Kapal </td><td colspan="5" class="form-field-caption" > : <input id="NM_KAPAL" name="NM_KAPAL" type="text" value="<?php echo($row_request["NM_KAPAL"]); ?>" size="40" style="background-color:#FFFFCC;" title="Autocomplete" /><input id="KD_KAPAL" name="KD_KAPAL" type="hidden" value="<?php echo($row_request["KD_KAPAL"]); ?>" /><input id="TGL_MUAT" name="TGL_MUAT" type="hidden" value="<?php echo($row_request["TGL_MUAT"]); ?>" size="40" /><input id="NO_UKK" name="NO_UKK" type="hidden" value="<?php echo($row_request["NO_UKK"]); ?>" /><input id="TGL_STACKING" name="TGL_STACKING" type="hidden" value="<?php echo($row_request["TGL_STACKING"]); ?>" /><input id="TGL_BERANGKAT" name="TGL_BERANGKAT" type="hidden" value="<?php echo($row_request["TGL_BERANGKAT"]); ?>" /><input id="CALL_SIGN" name="CALL_SIGN" type="hidden" value="<?php echo($row_request["CALL_SIGN"]); ?>" /><input id="VOYAGE" name="VOYAGE" type="hidden" value="<?php echo($row_request["VOYAGE"]); ?>" /><input id="ID_VSB_VOYAGE" name="ID_VSB_VOYAGE" type="hidden" value="<?php echo($row_request["NO_UKK"]); ?>" /><input id="VESSEL_ID" name="VESSEL_ID" type="hidden" value="<?php echo($row_request["VESSEL_ID"]); ?>" /><!-- <block visible="error.NO_UKK"><span class="form-field-error"><?php echo($error["NM_KAPAL"]); ?></span></block> --> Voyage : <input id="VOYAGE_IN" name="VOYAGE_IN" type="text" value="<?php echo($row_request["VOYAGE_IN"]); ?>" size="5" readonly="1" />- <input id="VOYAGE_OUT" name="VOYAGE_OUT" type="text" value="<?php echo($row_request["VOYAGE_OUT"]); ?>" size="5" readonly="1" /></td></tr><tr><td class="form-field-caption" valign="top" align="right">Nama Agen</td><td width="300" class="form-field-caption" > : <input id="KD_AGEN" name="KD_AGEN" type="hidden" value="<?php echo($row["KD_AGEN"]); ?>" n="1" /><input id="NM_AGEN" name="NM_AGEN" type="text" value="<?php echo($row_request["NM_AGEN"]); ?>" size="40" readonly="1" /><?php	if ($error["NO_UKK"]): ?><span class="form-field-error"><?php echo($error["NM_AGEN"]); ?></span><?php	endif; ?></td><td width="100"></td><td class="form-field-caption" valign="top" align="right" width="250">Port Of Destination</td><td valign="middle"  class="form-field-caption">:</td><td><input id="KD_PELABUHAN_ASAL" name="KD_PELABUHAN_ASAL" type="hidden" value="<?php echo($row_request["KD_PELABUHAN_ASAL"]); ?>" size="3" class="pod" readonly="1" /><input id="NM_PELABUHAN_ASAL" name="NM_PELABUHAN_ASAL" type="text" value="<?php echo($row_request["NM_PELABUHAN_ASAL"]); ?>" size="40" maxlength="100" title="Autocomplete" class="pod" readonly="1" /></td></tr><tr><td class="form-field-caption" valign="top" align="right" >No PKK</td><td class="form-field-caption" >: <input id="NO_UKK" name="NO_UKK" type="text" value="<?php echo($row_request["NO_UKK"]); ?>" readonly="readonly" size="20" maxlength="16" title="Autocomplete" class="pkkkapal" /></td><td width="100"></td><td class="form-field-caption" valign="top" align="right" width="250">Final Discharge</td><td valign="top" class="form-field-caption">: </td><td><input id="KD_PELABUHAN_TUJUAN" name="KD_PELABUHAN_TUJUAN" type="hidden" value="<?php echo($row_request["KD_PELABUHAN_TUJUAN"]); ?>" size="3" class="pod2" readonly="1" /><input id="NM_PELABUHAN_TUJUAN" name="NM_PELABUHAN_TUJUAN" type="text" value="<?php echo($row_request["NM_PELABUHAN_TUJUAN"]); ?>" size="40" maxlength="100" class="pod2" title="Autocomplete" readonly="1" /></td></tr><tr><td class="form-field-caption" valign="middle" align="right" width="177">No Booking</td><td colspan="5" class="form-field-caption" >: <input id="NO_BOOKING" name="NO_BOOKING" type="text" value="<?php echo($row_request["NO_BOOKING"]); ?>" size="16" maxlength="16" readonly="1" /></td></tr></table></fieldset><fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px; "><legend>&nbsp &nbsp <font color="red"><b>Entry Dokumen Pendukung</b></font></legend><table style="margin: 10px 10px 10px 10px;" border="0" ><tr><td class="form-field-caption" valign="top" align="right">No P.E.B </td><input type="hidden" id="PENUMPUKAN" size="40" name="PENUMPUKAN" class="kdemkl " placeholder=" AUTOCOMPLETE" style="background-color:#FFFFCC;" /><input size="40" name="ID_PENUMPUKAN" id="ID_PENUMPUKAN" type="hidden" /><input size="40" name="ALMT_PENUMPUKAN" id="ALMT_PENUMPUKAN" type="hidden" /><input size="40" name="NPWP_PENUMPUKAN" id="NPWP_PENUMPUKAN" type="hidden" /></td><td valign="top" class="form-field-caption">: <input id="id_NO_PEB" name="NO_PEB" type="text" value="<?php echo($row_request["NO_PEB"]); ?>" size="15" /></td><td width="100"></td><td class="form-field-caption" valign="middle" align="right">Nomor Dokumen</td><td class="form-field-caption">: <input type="text" name="NO_DOC" size="15" id="NO_DOC" value="<?php echo($row_request["NO_DOKUMEN"]); ?>" /></td></td></tr><tr><td class="form-field-caption" valign="top" align="right">No. N.P.E</td><td class="form-field-caption"> : <input id="id_NO_NPE" name="NO_NPE" type="text" value="<?php echo($row_request["NO_NPE"]); ?>" size="15" /></td><td width="100"><td class="form-field-caption" valign="middle" align="right">Nomor JPB</td><td class="form-field-caption"> : <input type="text" name="NO_JPB" id="NO_JPB" value="<?php echo($row_request["NO_JPB"]); ?>" /></td></tr><tr><td class="form-field-caption" valign="top" align="right">Nomor D.O</td><td valign="top" class="form-field-caption"> : <input type="text" name="NO_DO" id="NO_DO" value="<?php echo($row_ict["NO_DO"]); ?>" /></td><td width="100"><td class="form-field-caption" valign="middle" align="right"> BPRP</td><td class="form-field-caption"> : <input type="text" name="BPRP" id="BPRP" value="<?php echo($row_request["BPRP"]); ?>" /></td></tr><tR><td class="form-field-caption" valign="middle" align="right">Nomor B.L</td><td class="form-field-caption">: <input type="text" name="NO_BL" id="NO_BL" value="<?php echo($row_ict["NO_BL"]); ?>" /></td></tr><tr><td class="form-field-caption" valign="top" align="left">Nomor SPPB</td><td class="form-field-caption"> : <input type="text" id="NO_SPPB" name="NO_SPPB" value="<?php echo($row_ict["NO_SPPB"]); ?>" /></td><td width="100"><td class="form-field-caption" valign="middle" align="right">Tanggal SPPB</td><td class="form-field-caption"> : <input type="text" id="TGL_SPPB" name="TGL_SPPB" value="" /></td></tr><tr><td class="form-field-caption" valign="middle" align="right">Keterangan</td><td class="form-field-caption"> : <input type="text" size="50" name="KETERANGAN" id="KETERANGAN" value="<?php echo($row_request["KETERANGAN"]); ?> " /></td></tr></table></fieldset></form></fieldset><fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; "><center><fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px; "><legend> &nbsp &nbsp <font color="red"><b>Entry No Container</b></font></legend><table style="margin: 10px 10px 10px 10px" border="0"><tr><td class="form-field-caption"> Yard Stack</td><td class="form-field-caption"> : </td><td><input type="text" name="YARD_STACK" id="YARD_STACK" value="<?php echo($row_request["STUFFING_DARI"]); ?>" readonly="1" /></td></tr><tr><td class="form-field-caption"> Ex Batal SP2 </td><td class="form-field-caption"> : </td><td><select name="REMARK_SP2" id="REMARK_SP2"><?php $this->renderSelectOptions(array (
  0 => 
  array (
    'value' => 'N',
    'label' => 'TIDAK',
  ),
  1 => 
  array (
    'value' => 'Y',
    'label' => 'YA',
  ),
),$false,$false,'0','1'); ?></select></td></tr><tr><td class="form-field-caption"> Nomor Container </td><td class="form-field-caption"> : </td><td><input type="text" name="NO_CONT" ID="NO_CONT" placeholder=" AUTOCOMPLETE" style="background-color:#FFFFCC;" /></td><input type="hidden" name="BP_ID" ID="BP_ID" readonly="readonly" /><input type="hidden" name="NO_UKK_CONT" ID="NO_UKK_CONT" readonly="readonly" /><input type="hidden" name="TGL_STACK" ID="TGL_STACK" readonly="readonly" /><input type="hidden" name="NO_REQ_SP2" ID="NO_REQ_SP2" readonly="readonly" /><input type="hidden" name="SP2" ID="SP2" value="<?php echo($sp2); ?>" readonly="readonly" /><td style="width:100px;">&nbsp;</td><td class="form-field-caption"> Berbahaya </td><td class="form-field-caption"> : </td><td><select name="BERBAHAYA" id="BERBAHAYA"><?php $this->renderSelectOptions(array (
  0 => 
  array (
    'value' => 'N',
    'label' => 'TIDAK',
  ),
  1 => 
  array (
    'value' => 'Y',
    'label' => 'YA',
  ),
),$false,$false,'0','1'); ?></select><font color="red">*) Harus Diisi</font></td></tr><tr><td class="form-field-caption"> Ukuran </td><td class="form-field-caption"> : </td><td><input type="text" size="5" name="SIZE" ID="SIZE" readonly="readonly" style="background-color:#FFFFCC;" /></td><td style="width:100px;">&nbsp;</td><td  class="form-field-caption"> No Seal</td><td class="form-field-caption"> : </td><td><input type="text" size="10" name="NO_SEAL" ID="NO_SEAL" /></td></tr><tr><td class="form-field-caption"> Type </td><td class="form-field-caption"> : </td><td><input type="text" size="7" name="TYPE" ID="TYPE" readonly="readonly" style="background-color:#FFFFCC;" /></td><td style="width:100px;">&nbsp;</td><td class="form-field-caption"> Commodity </td><td class="form-field-caption"> : </td><td><input type="text" size="15" name="COMMODITY" ID="COMMODITY" placeholder=" AUTOCOMPLETE" style="background-color:#FFFFCC;" /><input type="hidden" name="KD_COMMODITY" id="KD_COMMODITY" /></td></tr><tr><td class="form-field-caption"> Asal Container </td><td class="form-field-caption"> : </td><td><input type="text" name="ASAL_CONT" ID="ASAL_CONT" readonly="readonly" style="background-color:#FFFFCC;" /></td><td style="width:100px;">&nbsp;</td><td class="form-field-caption">Stuffing Via</td><td class="form-field-caption"> : </td><td><select name="JENIS" id="JENIS"><?php $this->renderSelectOptions(array (
  0 => 
  array (
    'value' => 'STUFFING_LAP',
    'label' => 'LAPANGAN',
  ),
  1 => 
  array (
    'value' => 'STUFFING_GUD_TONGKANG',
    'label' => 'GUDANG EKS TONGKANG',
  ),
  2 => 
  array (
    'value' => 'STUFFING_GUD_TRUCK',
    'label' => 'GUDANG EKS TRUCK',
  ),
),$false,$false,'0','1'); ?></select></td></tr><tr><td class="form-field-caption"> Status </td><td class="form-field-caption"> : </td><td><input type="text" name="STATUS" id="STATUS" readonly="readonly" style="background-color:#FFFFCC;" /></td><td style="width:100px;">&nbsp;</td><td class="form-field-caption"> Depo Tujuan </td><td class="form-field-caption"> : </td><!-- <td> 
				<?php
						$db				= getDB("storage");
						$get_yard		= "SELECT * FROM YARD_AREA";
						$result_yard	= $db->query($get_yard);
						$row_yard		= $result_yard->getAll();
						$y_list	= "<select id='DEPO_TUJUAN' name='DEPO_TUJUAN'>"; 
						$cur_y	= $_SESSION["IDYARD_STORAGE"];
						
						foreach($row_yard as $row)
						{	
							$y_id	= $row["ID"];
							$y_name	= $row["NAMA_YARD"];
							$y_list	.= "<option value='$y_id' ";
								if($cur_y == $y_id)
								{
									$y_list .= "selected=selected";
								}
							$y_list	.= ">$y_name</option>";
						}
						
						$y_list	.= "</select>";
						echo $y_list;
			?>
			</td> --><td><input type="text" name="NM_DEPO_TUJUAN" value="USTER" readonly="1" style="background-color:#FFFFCC;" /><input type="text" name="DEPO_TUJUAN" value="1" readonly="1" hidden="1" style="background-color:#FFFFCC;" /></td></tr><tr><td class="form-field-caption"> Tanggal Bongkar</td><td class="form-field-caption"> : </td><td><input type="text" size="17" name="TGL_BONGKAR" ID="TGL_BONGKAR" readonly="readonly" style="background-color:#FFFFCC;" /></td><td style="width:100px;">&nbsp;</td><td  class="form-field-caption"> Berat</td><td class="form-field-caption"> : </td><td><input type="text" name="BERAT" ID="BERAT" /></td></tr><tr><td class="form-field-caption">Voyage / Kapal</td><td class="form-field-caption"> : </td><td><input type="text" name="VOYAGE" id="VOYAGE" readonly="readonly" placeholder="<?php echo($row_request["VOYAGE"]); ?>" style="background-color:#FFFFCC;" /> / <input name="VESSEL" id="VESSEL" readonly="readonly" placeholder="<?php echo($row_request["VESSEL"]); ?>" style="background-color:#FFFFCC;" /><input type="hidden" name="NO_BOOKING" id="NO_BOOKING" value="<?php echo($row_request["NO_BOOKING"]); ?>" /></td><td style="width:100px;">&nbsp;</td><!-- <td  class="form-field-caption"> Early Stuffing</td>
            <td class="form-field-caption"> : </td>
			<td > 
            <select name="EARLY_STUFF" id="EARLY_STUFF">
				<option value="N">TIDAK</option> 
				<option value="Y">YA</option>
            </select> --><!-- Tanggal :
			<input size="10" type="text" name="TGL_EARLY_STUFF" id="TGL_EARLY_STUFF"  /> --></td></tr><tr><td class="form-field-caption">Lokasi </td><td align="center" class="form-field-caption">:</td><td class="form-field-caption"> Blok : <input size="4" type="text" name="BLOK" id="BLOK" readonly="readonly" style="background-color:#FFFFCC;" /> Slot : <input size="4" type="text" name="SLOT" id="SLOT" readonly="readonly" style="background-color:#FFFFCC;" /></td><td style="width:100px;">&nbsp;</td><td  class="form-field-caption"> Penump Empty s/d</td><td class="form-field-caption"> : </td><td><input type="text" name="TGL_EMPTY" size="10" ID="TGL_EMPTY" /></td></tr><tr><td class="form-field-caption"></td><td></td><td class="form-field-caption"> Row : <input size="4" type="text" name="ROW" id="ROW" readonly="readonly" style="background-color:#FFFFCC;" /> Tier : <input size="4" type="text" name="TIER" id="TIER" readonly="readonly" style="background-color:#FFFFCC;" /></td><td style="width:100px;">&nbsp;</td><td  class="form-field-caption"> Keterangan</td><td class="form-field-caption"> : </td><td><input type="text" name="KETERANGAN" size="30" ID="KETERANGAN" /></td></tr><tr><td style="width:200px;" colspan="4">&nbsp;</td><td></td><td></td><td></td></tr><tr align="center"><td colspan="7"><a class="link-button" onClick="add_cont()"><img src='images/cont_addnew.gif' border="0"> Tambahkan Container </a>&nbsp;</td></tr></table><div id="cont_list" style="margin: 10px 10px 10px 10px;"></div></fieldset></center></fieldset><div id="dialog-form"></div><script>
function save_request($total){
	window.open('<?php echo($HOME); ?><?php echo($APPID); ?>','_self');
}

$(function(){
	$( "#dialog-form" ).dialog({
			autoOpen: false,
			height: 300,
			width: 500,
			modal: true,
			buttons: {
					"Close": function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {

			}
		});
	
		/*
		$("#radio_single").click(function () {
					$("#detailFrame2").slideUp("1000");
					$("#detailFrame").slideDown("1000");
					});
		
		
		$("#radio_multi").click(function () {
					$("#detailFrame").slideUp("1000");
					$("#detailFrame2").slideDown("1000");
					//alert("tes");
					});
			
		$(".showDetData").click(function () {
					$("#detailFrame2").slideUp("1000");
					$("#detailFrame").slideDown("1000");
					$("#radio_single").prop('checked', true);
					$("#radio_multi").prop('unchecked', true);
					});	
		*/
	//disable penumpukan empty s/d,  jika early stuffing diisi	
  		
 // $("#TGL_EMPTY").prop('disabled', true);
  
  $("#EARLY_STUFF").change(function(){
	
	//if($("#EARLY_STUFF").val()=="N")
	//{
	//	$("#TGL_EMPTY").prop('disabled', true);
		//$("#TGL_EARLY_STUFF").prop('disabled', false);
		
		
	//}else if($("#EARLY_STUFF").val()=="Y")
	//{
		
	//	$("#TGL_EMPTY").prop('disabled', false);
		//$("#TGL_EARLY_STUFF").prop('disabled', true);
	//}
	});
	
	
		
});

$(function() {	
	//$( "#TGL_BONGKAR" ).datepicker();
	//$( "#TGL_BONGKAR" ).datepicker( "option", "dateFormat", "dd-mm-yy" );
	
	$( "#EMKL" ).autocomplete({
		minLength: 3,
		source: "<?php echo($HOME); ?><?php echo($APPID); ?>.auto/pbm",
		focus: function( event, ui ) {
			$( "#EMKL" ).val( ui.item.NAMA );
			return false;
		},
		select: function( event, ui ) {
			$( "#EMKL" ).val( ui.item.NAMA );
			$( "#ID_EMKL" ).val( ui.item.ID );
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + item.NAMA + "</a>" )
			.appendTo( ul );
	};

	$("#NO_CONT").on("focus", function(){
	  if($("#YARD_STACK").val() == 'TPK') {
	      	$(this).autocomplete({ 
	        	minLength: 3,
				source: function(request, response) {
				$.getJSON("<?php echo($HOME); ?><?php echo($APPID); ?>.auto/containerunion_praya",{  NO_CONTAINER:$( "#NO_CONT" ).val(), VOYAGE:$( "#VOYAGE" ).val(), ID_VSB:$( "#NO_UKK" ).val(), VOYIN:$( "#VOYAGE_IN" ).val(), VOYOUT:$( "#VOYAGE_OUT" ).val(), VESID:$("#KD_KAPAL").val() }, response);
				},	
				focus: function( event, ui ) {
					$( "#NO_CONT" ).val( ui.item.containerNo );
					return false;
				},
				select: function( event, ui ) {
					$( "#NO_CONT" ).val( ui.item.containerNo );
					$( "#SIZE" ).val( ui.item.containerSize);
					$( "#STATUS" ).val( ui.item.containerStatus);
					$( "#TYPE" ).val( ui.item.containerType);
					$("#VOYAGE").val( ui.item.voyage);
					$("#VESSEL").val( ui.item.vesselName);
					$("#TGL_BONGKAR").val(ui.item.vesselConfirm);
					$("#TGL_STACK").val( ui.item.TGL_STACK);
					$("#NO_UKK_CONT").val( ui.item.NO_UKK);	
					$("#NM_AGEN").val( ui.item.NM_AGEN);
					$("#BP_ID").val( ui.item.BP_ID);	
					$("#BLOK").val( ui.item.ydBlock);
					$("#SLOT").val( ui.item.ydSlot);
					$("#ROW").val( ui.item.ydRow);
					$("#TIER").val( ui.item.ydTier);
					$("#TGL_EMPTY").val( ui.item.EMPTY_SD);
					$("#ASAL_CONT").val('TPK');
					$("#NO_REQ_SP2").val( ui.item.NO_REQUEST);
					$("#TGL_EMPTY").val();
					$("#COMMODITY").focus();
					
					return false;
				}
	      	}).data( "autocomplete" )._renderItem = function( ul, item ) {
				return $( "<li style='text-align:left'></li>" )
				.data( "item.autocomplete", item )
				.append( "<a>" + item.containerNo + "<br/> "+item.containerSize+" "+item.containerType+"</a>" )
				.appendTo( ul );
			};
	   }else{
	   		$(this).autocomplete({ 
	   			minLength: 3,
				source: "<?php echo($HOME); ?><?php echo($APPID); ?>.auto/containerunion",
				focus: function( event, ui ) {
					$( "#NO_CONT" ).val( ui.item.NO_CONTAINER );
					return false;
				},
				select: function( event, ui ) {
					$( "#NO_CONT" ).val( ui.item.NO_CONTAINER );
					$( "#SIZE" ).val( ui.item.KD_SIZE);
					$( "#STATUS" ).val( ui.item.STATUS_CONT);
					$( "#TYPE" ).val( ui.item.KD_TYPE);
					$("#VOYAGE").val( ui.item.VOYAGE_IN);
					$("#VESSEL").val( ui.item.NM_KAPAL);
					$("#TGL_BONGKAR").val( ui.item.TGL_BONGKAR);
					$("#TGL_STACK").val( ui.item.TGL_STACK);
					$("#NO_UKK").val( ui.item.NO_UKK);	
					$("#NM_AGEN").val( ui.item.NM_AGEN);
					$("#BP_ID").val( ui.item.BP_ID);	
					$("#BLOK").val( ui.item.BLOK_);
					$("#SLOT").val( ui.item.SLOT_);
					$("#ROW").val( ui.item.ROW_);
					$("#TIER").val( ui.item.TIER_);
					$("#TGL_EMPTY").val( ui.item.EMPTY_SD);
					$("#ASAL_CONT").val( ui.item.ASAL_CONT);
					if(ui.item.ASAL_CONT == 'DEPO'){
						$.post("<?php echo($HOME); ?><?php echo($APPID); ?>.auto/get_tgl_stack",{no_cont : ui.item.NO_CONTAINER}, function(data){
							$("#TGL_BONGKAR").val(data);
						});
					}
					$("#COMMODITY").focus();
					
					return false;
				}
			})
			.data( "autocomplete" )._renderItem = function( ul, item ) {
				return $( "<li style='text-align:left'></li>" )
					.data( "item.autocomplete", item )
					.append( "<a>" + item.NO_CONTAINER + "<br/> "+item.KD_SIZE+" "+item.KD_TYPE+"</a>" )
					.appendTo( ul );
			};
	   	}
	});
	
	$( "#NM_KAPAL" ).autocomplete({
		minLength: 3,
		source: "<?php echo($HOME); ?><?php echo($APPID); ?>.auto/master_vessel_palapa",
		focus: function(event, ui) {
			$( "#NM_KAPAL" ).val( ui.item.VESSEL);
			return false;
		},
		select: function(event, ui) {
			$( "#KD_KAPAL").val( ui.item.VESSEL_CODE);
			$(" #VOYAGE").val(ui.item.VOYAGE);
			$( "#ID_VSB_VOYAGE" ).val( ui.item.ID_VSB_VOYAGE);
			$( "#VESSEL_ID").val( ui.item.VESSEL_CODE);
			$( "#VOYAGE_IN").val( ui.item.VOYAGE_IN);
			$( "#VOYAGE_OUT").val( ui.item.VOYAGE_OUT);
			$( "#KD_KAPAL").val( ui.item.VESSEL_CODE);
			$( "#NM_AGEN").val( ui.item.OPERATOR_NAME);
			$( "#KD_AGEN").val( ui.item.OPERATOR_ID);
			$( "#NO_UKK").val( ui.item.ID_VSB_VOYAGE);
			if (ui.item.VESSEL_CODE && ui.item.ID_VSB_VOYAGE) {
                $("#NO_BOOKING").val(`BP${ui.item.VESSEL_CODE}${ui.item.ID_VSB_VOYAGE}`);
            }
			$( "#TGL_BERANGKAT").val(ui.item.ATD);
			$( "#TGL_STACKING").val(ui.item.OPEN_STACK);
			$( "#TGL_MUAT").val(ui.item.CLOSING_TIME_DOC);
			$( "#KD_PELABUHAN_ASAL").val( ui.item.ID_POL);
			$( "#KD_PELABUHAN_TUJUAN").val( ui.item.ID_POD);
			$( "#NM_PELABUHAN_ASAL").val( ui.item.POL);
			$( "#NM_PELABUHAN_TUJUAN").val( ui.item.POD);
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + item.VESSEL+ " | " + item.VOYAGE_IN+ "/" + item.VOYAGE_OUT + "</a>" )
			.appendTo( ul );
	}; 
	
	$( "#EMKL" ).autocomplete({
		minLength: 3,
		source: "<?php echo($HOME); ?><?php echo($APPID); ?>.auto/pbm",
		focus: function( event, ui ) {
			$( "#EMKL" ).val( ui.item.NM_PBM );
			return false;
		},
		select: function( event, ui ) {
			$( "#EMKL" ).val( ui.item.NM_PBM );
			$( "#ID_EMKL" ).val( ui.item.KD_PBM );
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + item.NM_PBM + "</a>" )
			.appendTo( ul );
	};
	
	 $( "#COMMODITY" ).autocomplete({
		minLength: 3,
		source: "<?php echo($HOME); ?><?php echo($APPID); ?>.auto/komoditi",
		focus: function(event, ui) {
			$( "#COMMODITY" ).val( ui.item.NM_COMMODITY);
			return false;
		},
		select: function(event, ui) {
			$( "#COMMODITY").val( ui.item.NM_COMMODITY);
			$( "#KD_COMMODITY").val( ui.item.KD_COMMODITY);
			$("#TGL_EMPTY").focus();
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + item.NM_COMMODITY+ "</a>")
			.appendTo( ul );
	};       

	
	
	$("#cont_list").load('<?=HOME?><?=APPID?>/cont_list?no_req=<?php echo($row_request["NO_REQUEST"]); ?> #list', function(data) {    
			//alert(data);
			var no = '<?php echo $jum; ?>';
				for(i=1;i<=no;i++){
					
					//$("#TGL_APPROVE_"+i).datepicker();
					//$("#TGL_APPROVE_"+i).datepicker( "option", "dateFormat", "yy-mm-dd" );
					//$("#TGL_APPROVE_"+i).datepicker( "dateFormat", "yy-mm-dd" );						
					//$("#TGL_APPROVE_"+i).datepicker("setDate", data );
					//$("#TGL_APPROVE_"+i).datepicker( "option", "minDate", selectedDate );
					//$("#TGL_APPROVE_"+i).datepicker({ 	minDate: 0,
					//									dateFormat: 'dd-mm-yy'});	
					$("#TGL_APPROVE_"+i).datepicker({ 	dateFormat: 'dd-mm-yy'});	
				}				
				
	}); 	
});

function add_cont()
{
	
	var no_cont_		= $("#NO_CONT").val();
	var no_req_stuf		= "<?php echo($row_request["NO_REQUEST"]); ?>";
	var no_req_rec		= "<?php echo($row_request["NO_REQUEST_RECEIVING"]); ?>";
	var no_req_del		= "<?php echo($row_request["NO_REQUEST_DELIVERY"]); ?>";
	var no_req_ict_		= $("#NO_REQUEST2").val();
	var size_			= $("#SIZE").val();
	var type_			= $("#TYPE").val();
	var status			= $("#STATUS").val();
	var berbahaya_		= $("#BERBAHAYA").val();
	var commodity_		= $("#COMMODITY").val();
	var kd_commodity_	= $("#KD_COMMODITY").val();
	var jenis_			= $("#JENIS").val();
	var no_seal			= $("#NO_SEAL").val();
	var berat			= $("#BERAT").val();
	var keterangan		= $("#KETERANGAN").val();
	var no_booking_		= $("#NO_BOOKING").val();
	var no_ukk_			= "<?php echo($row_request["NO_UKK"]); ?>";
	var no_ukk_cont		= $("#NO_UKK_CONT").val();
	var bp_id			= $("#BP_ID").val();
	var sp2				= $("#SP2").val();
	var tgl_stack		= $("#TGL_STACK").val();
	var voyage			= $("#VOYAGE").val();
	var vessel			= $("#VESSEL").val();
	var tgl_bongkar		= $("#TGL_BONGKAR").val();
	var depo_tujuan		= $("#DEPO_TUJUAN").val();
	var no_do			= $("#NO_DO").val();
	var no_bl			= $("#NO_BL").val();
	var no_sppb			= $("#NO_SPPB").val();
	var tgl_sppb		= $("#TGL_SPPB").val();
	var blok_cont		= $("#BLOK").val();
	var slot_cont		= $("#SLOT").val();
	var rows_cont		= $("#ROW").val();
	var tier_cont		= $("#TIER").val();
	var ASAL_CONT		= $("#ASAL_CONT").val();
	var TGL_EMPTY		= $("#TGL_EMPTY").val();
	var EARLY_STUFF		= $("#EARLY_STUFF").val();
	var remark_sp2		= $("#REMARK_SP2").val();
	var no_req_sp2		= $("#NO_REQ_SP2").val();
	
	var urlcek = '<?php echo($HOME); ?><?php echo($APPID); ?>/cek_capacity_tpk';
	var no_booking_		= $("#NO_BOOKING").val();
    
    if($("#TGL_EMPTY").val() == ''){
        $.blockUI({ 
            message: '<h1>Tgl Penumpukan Empty s/d Harus Diisi!</h1>', 
            timeout: 1000
        });	
        $("#TGL_EMPTY").focus();
        return false;
    }
    
	$.post(urlcek,{no_booking : no_booking_},function(data){
		if(data == 'T'){
			$.blockUI({ 
				message: '<h1>Kapasitas Booking Stack TPK Tidak Mencukupi!</h1>', 
				timeout: 1000
			});	
		}
		else {
		
			var url				= "<?php echo($HOME); ?><?php echo($APPID); ?>.ajax/add_cont";
	
			$('#cont_list').html('<p align=center><br><b>PLEASE WAIT ....</b><br><img src=<?php echo($HOME); ?>images/loadingbox.gif /></p>');
			
			$.post(url,{NO_CONT: no_cont_, NO_REQ_STUF : no_req_stuf, NO_REQ_REC : no_req_rec, 
						NO_REQ_DEL : no_req_del, NO_REQ_ICT : no_req_ict_, SIZE : size_, 
						TYPE : type_, STATUS : status, COMMODITY : commodity_,KD_COMMODITY : kd_commodity_, 
						JENIS : jenis_, BERBAHAYA : berbahaya_, NO_SEAL : no_seal, BERAT : berat, 
						KETERANGAN : keterangan, NO_BOOKING : no_booking_, NO_UKK : no_ukk_, BP_ID: bp_id, SP2 : sp2,
						TGL_STACK : tgl_stack, VOYAGE : voyage, VESSEL : vessel, TGL_BONGKAR : tgl_bongkar,
						DEPO_TUJUAN : depo_tujuan, NO_DO : no_do, NO_BL : no_bl, NO_SPPB : no_sppb, TGL_SPPB: tgl_sppb,
						BLOK : blok_cont, SLOT : slot_cont, ROW: rows_cont, TIER : tier_cont, ASAL_CONT : ASAL_CONT,
						TGL_EMPTY : TGL_EMPTY, EARLY_STUFF : EARLY_STUFF,REMARK_SP2 : remark_sp2, NO_REQ_SP2 : no_req_sp2, ID_VSB:no_ukk_cont},function(data){
						
				console.log(data);
				if(data == "EXIST" )
				{
					alert("Container Sudah terdaftar di request lain, silahkan cek di history container");	
				}
				else if(data == "BERBAHAYA")
				{
					alert("Status Berbahaya belum diisi");
				}
				else if(data == "EXIST_DEL")
				{
					alert("Masih Aktif di Request Delivery");
				}
				else if(data == "EXIST_REC")
				{
						alert("Container Masih Aktif di Req Receiving / Belum Gate In");
				}
				else if(data == "BELUM_REC")
				{
						alert("Container Belum IN");
				}
				else if(data == "SUDAH_REQUEST")
				{
					alert("Maaf no container "+no_cont_+" telah mengajukan request stuffing"); 	
				}
				else if(data == "OK")
				{
					alert("Succeed"); 	
				}
				$("#cont_list").load('<?=HOME?><?=APPID?>/cont_list?no_req=<?php echo($row_request["NO_REQUEST"]); ?> #list', function(data) {
						var no = '<?php echo count($row_list); ?>';
						no++;
						for(i=1;i<=no;i++){
							//$("#TGL_APPROVE_"+i).datepicker();
							//$("#TGL_APPROVE_"+i).datepicker( "option", "dateFormat", "yy-mm-dd" );
							//$("#TGL_APPROVE_"+i).datepicker( "dateFormat", "yy-mm-dd" );
							//$("#TGL_APPROVE_"+i).datepicker("setDate", data );
							
							//$("#TGL_APPROVE_"+i).datepicker( "option", "minDate", selectedDate );
							//$("#TGL_APPROVE_"+i).datepicker({ 	minDate: 0,
							//									dateFormat: 'dd-mm-yy'});
							$("#TGL_APPROVE_"+i).datepicker({ 	dateFormat: 'dd-mm-yy'});
								
						}
				
					});
					
					$( "#NO_CONT" ).val('');
					$( "#SIZE" ).val('');
					$( "#STATUS" ).val('');
					$( "#TYPE" ).val('');
					$("#VOYAGE").val('');
					$("#VESSEL").val('');
					$("#TGL_BONGKAR").val('');
					$("#TGL_STACK").val('');
					$("#NO_UKK").val('');
					$("#NM_AGEN").val('');
					$("#BP_ID").val('');
					$("#BLOK").val('');
					$("#SLOT").val('');
					$("#ROW").val('');
					$("#TIER").val('');
					$("#BERBAHAYA").val('');
					$("#NO_SEAL").val('');
					$("#JENIS").val('');
					$("#DEPO_TUJUAN").val('');
					$("#BERAT").val('');
					$("#KETERANGAN").val('');
					$("#ASAL_CONT").val('');
					$( "#NO_CONT" ).focus();
					
			});	
		}
	});
	//prosedur add comment disini, method $.post, include user id dan content id
}

function del_cont($no_cont,$no_req_sp2)
{
	var no_req_		= "<?php echo($row_request["NO_REQUEST"]); ?>";
	var no_req2		= $("#NO_REQUEST2").val();
	var url			= "<?php echo($HOME); ?><?php echo($APPID); ?>.ajax/del_cont";
	
	$('#cont_list').html('<p align=center><b><br>PLEASE WAIT ....</b><br><img src=<?php echo($HOME); ?>images/loadingbox.gif /></p>');
	
	$.post(url,{NO_CONT: $no_cont, NO_REQ : no_req_, NO_REQ2 : no_req2, NO_REQ_SP2 : $no_req_sp2},function(data){
		console.log(data);
		if(data == "OK")
		{	
			alert("Berhasil menghapus container " + $no_cont);
		}else{
			alert("Gagal menghapus container " + $no_cont);
		}
		$("#cont_list").load('<?=HOME?><?=APPID?>/cont_list?no_req=<?php echo($row_request["NO_REQUEST"]); ?> #list', function(data) {
				var no = '<?php echo count($row_list); ?>';				
				for(i=1;i<=no-1;i++){
					//$("#TGL_APPROVE_"+i).datepicker();
					//$("#TGL_APPROVE_"+i).datepicker( "option", "dateFormat", "yy-mm-dd" );
					//$("#TGL_APPROVE_"+i).datepicker( "dateFormat", "yy-mm-dd" );
					//$("#TGL_APPROVE_"+i).datepicker( "option", "minDate", selectedDate );
					//$("#TGL_APPROVE_"+i).datepicker({ 	minDate: 0,
					//									dateFormat: 'dd-mm-yy'});	
					$("#TGL_APPROVE_"+i).datepicker({ 	dateFormat: 'dd-mm-yy'});
				}
			}); 
	});	
}
//Date Picker
$(function() {	
	
	$( "#tgl_req_dev" ).datepicker();
	$( "#tgl_req_dev" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
	
	//$( "#TGL_EMPTY" ).datepicker();
	//$( "#TGL_EMPTY" ).datepicker({ minDate: 0, dateFormat: 'dd-mm-yy'});
	// $( "#TGL_EMPTY" ).datepicker({ dateFormat: 'dd-mm-yy'});
	$( "#TGL_EMPTY" ).datepicker({ dateFormat: 'dd-mm-yy', minDate: new Date()});
	
	$( "#TGL_EARLY_STUFF" ).datepicker({ dateFormat: 'dd-mm-yy'});

	$( "#ID_TGL_MULAI" ).datepicker();
	$( "#ID_TGL_MULAI" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
	$("#ID_TGL_MULAI").val('<?php echo($row_request["TGL_AWAL_RFR"]); ?>');

	$( "#ID_TGL_NANTI" ).datepicker();
	$( "#ID_TGL_NANTI" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
	$("#ID_TGL_NANTI").val('<?php echo($row_request["TGL_AKHIR_RFR"]); ?>');

	$( "#TGL_REQ" ).datepicker();
	$( "#TGL_REQ" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
	
	//$( "#TGL_SPPB" ).val('<?php echo($row_ict["TGL_SPPB"]); ?>');
	$( "#TGL_SPPB" ).datepicker();
	$( "#TGL_SPPB" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
	$( "#TGL_SPPB" ).val('<?php echo($row_ict["TGL_SPPB"]); ?>');
	
});

function calculator(){
//	$.blockUI({ message: '<div id=\"divmsg\"><img src=\"images/cont_loading.gif\" /> <h2>Please wait..</h2></div>' ,css: {'color':'blue', 'height': '90px', 'margin-top': '100px', 'padding-top': '0px' }});
	$mulai = $('#ID_TGL_MULAI').val();
	$nanti = $('#ID_TGL_NANTI').val();
	$.post("<?php echo($HOME); ?><?=APPID?>/refcal/",{'mulai': $mulai, 'nanti':$nanti},function(data){
		//$.blockUI({ message: '<div id=\"divmsg\">'+data+'</div>' ,css: {'color':'blue', 'height': '90px', 'margin-top': '100px', 'padding-top': '0px' }});
		$('#id_SHIFT_RFR').val(data);
	});
}

function update_tgl_approve($no_cont, $tgl_approve, $asal_cont){
	//var tgl_approve = $("#TGL_APPROVE").val();
	//var no_cont = $("#no_cont").val();
	//alert(tgl_approve);
	

	if($("#YARD_STACK").val() == 'TPK'){
		$.getJSON("<?php echo($HOME); ?><?php echo($APPID); ?>.auto/containerunion_praya", { NO_CONTAINER:$no_cont, VOYAGE: $("#VOYAGE").val(), ID_VSB: $("#ID_VSB_VOYAGE").val(), VOYIN: $("#VOYAGE_IN").val(), VOYOUT: $("#VOYAGE_OUT").val(), VESID: $("#KD_KAPAL").val() }).done(function( json ) {
			// var container_data = json[0];

			if (json === null) {
				alert('Container Not Found In Praya');
				return false;
			}

			var container_praya = json[0];

			var no_req_ict_		= $("#NO_REQUEST2").val();
			var no_req_ict2_	= $("#NO_REQUEST3").val();
			var no_do			= $("#NO_DO").val();
			var no_bl			= $("#NO_BL").val();
			var no_booking_		= $("#NO_BOOKING").val();
			var sp2				= $("#SP2").val();
			var no_req 			= $("#no_req").val();
			var no_req_rec		= "<?php echo($row_request["NO_REQUEST_RECEIVING"]); ?>";
			var no_req_del		= "<?php echo($row_request["NO_REQUEST_DELIVERY"]); ?>";
			var url = '<?=HOME?><?=APPID?>/approve/';
			$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?php echo($HOME); ?>images/loadingbox.gif />' });
			$.post(url,{
					NO_REQ_REC : no_req_rec, 
					NO_REQ_DEL : no_req_del, 
					tgl_approve : $tgl_approve, 
					no_cont : $no_cont, 
					no_req : no_req, 
					NO_REQ_ICT : no_req_ict_, 
					NO_REQ_ICT2 : no_req_ict2_, 
					NO_DO : no_do, 
					NO_BL : no_bl, 
					SP2 : sp2, 
					NO_BOOKING : no_booking_,
					ASAL_CONT : 'TPK', 
					CONTAINER_SIZE: container_praya.containerSize,
					CONTAINER_TYPE: container_praya.containerType,
					CONTAINER_STATUS: container_praya.containerStatus,
					CONTAINER_HZ: container_praya.hz,
					CONTAINER_IMO: container_praya.imo,
					CONTAINER_ISO_CODE: container_praya.isoCode,
					CONTAINER_HEIGHT: container_praya.containerHeight,
					CONTAINER_CARRIER: container_praya.carrier,
					CONTAINER_REEFER_TEMP: container_praya.reeferTemp,
					CONTAINER_BOOKING_SL: container_praya.bookingSl,
					CONTAINER_OVER_WIDTH: container_praya.overWidth,
					CONTAINER_OVER_LENGTH: container_praya.overLength,
					CONTAINER_OVER_HEIGHT: container_praya.overHeight,
					CONTAINER_OVER_FRONT: container_praya.overFront,
					CONTAINER_OVER_REAR: container_praya.overRear,
					CONTAINER_OVER_LEFT: container_praya.overLeft,
					CONTAINER_OVER_RIGHT: container_praya.overRight,
					CONTAINER_UN_NUMBER: container_praya.unNumber,
					CONTAINER_POD: container_praya.pod,
					CONTAINER_POL: container_praya.pol,
					CONTAINER_VESSEL_CONFIRM: container_praya.dischargeDate,
					CONTAINER_COMODITY_TYPE_CODE: container_praya.commodity,
				},
				function(data){
					if(data){
							//alert(data);
							$.unblockUI({ 
							onUnblock: function(){ alert('Container Approved'); } 
							});
					}
						
					$("#cont_list").load('<?=HOME?><?=APPID?>/cont_list?no_req=<?php echo($row_request["NO_REQUEST"]); ?> #list', function(data) {
						var no = '<?php echo count($row_list); ?>';				
							for(i=1;i<=no-1;i++){
								//$("#TGL_APPROVE_"+i).datepicker();
								//$("#TGL_APPROVE_"+i).datepicker( "option", "minDate", selectedDate );
								//$("#TGL_APPROVE_"+i).datepicker({ 	minDate: 0,
								//									dateFormat: 'dd-mm-yy'});			
								$("#TGL_APPROVE_"+i).datepicker({ 	dateFormat: 'dd-mm-yy'});									
							}
					});
			});
			// $.post(url, {
			// 	NO_REQ_REC : no_req_rec, 
			// 	NO_REQ_DEL : no_req_del, 
			// 	tgl_approve : $tgl_approve, 
			// 	no_cont : $no_cont, 
			// 	no_req : no_req, 
			// 	NO_REQ_ICT : no_req_ict_, 
			// 	NO_REQ_ICT2 : no_req_ict2_, 
			// 	NO_DO : no_do, 
			// 	NO_BL : no_bl, 
			// 	SP2 : sp2, 
			// 	NO_BOOKING : no_booking_, 
			// 	ASAL_CONT : $asal_cont,
			// 	CONTAINER_SIZE: container_praya.containerSize,
			// 	CONTAINER_TYPE: container_praya.containerType,
			// 	CONTAINER_STATUS: container_praya.containerStatus,
			// 	CONTAINER_HZ: container_praya.hz,
			// 	CONTAINER_IMO: container_praya.imo,
			// 	CONTAINER_ISO_CODE: container_praya.isoCode,
			// 	CONTAINER_HEIGHT: container_praya.containerHeight,
			// 	CONTAINER_CARRIER: container_praya.carrier,
			// 	CONTAINER_REEFER_TEMP: container_praya.reeferTemp,
			// 	CONTAINER_BOOKING_SL: container_praya.bookingSl,
			// 	CONTAINER_OVER_WIDTH: container_praya.overWidth,
			// 	CONTAINER_OVER_LENGTH: container_praya.overLength,
			// 	CONTAINER_OVER_HEIGHT: container_praya.overHeight,
			// 	CONTAINER_OVER_FRONT: container_praya.overFront,
			// 	CONTAINER_OVER_REAR: container_praya.overRear,
			// 	CONTAINER_OVER_LEFT: container_praya.overLeft,
			// 	CONTAINER_OVER_RIGHT: container_praya.overRight,
			// 	CONTAINER_UN_NUMBER: container_praya.unNumber,
			// 	CONTAINER_POD: container_praya.pod,
			// 	CONTAINER_POL: container_praya.pol,
			// 	CONTAINER_VESSEL_CONFIRM: container_praya.vesselConfirm,
			// 	CONTAINER_COMODITY_TYPE_CODE: container_praya.commodity,
			// }, function (data) {
			// 	//alert(data);
			// 	if(data == 'EXIST'){

			// 		$.unblockUI({
			// 			onUnblock: function(){ alert('Tgl Approve Updated'); }
			// 		});
			// 	}
			// 	else if(data == 'OK') {
			// 		$.unblockUI({ 
			// 			onUnblock: function(){ alert('Container Approved'); } 
			// 		});
			// 	}
			// 	else{
			// 		$.unblockUI({
			// 			onUnblock: function(){ alert({data}); } 
			// 		});
			// 	}

			// 	// $("#cont_list").load('<?=HOME?><?=APPID?>/cont_list?no_req=<?php echo($row_request["NO_REQUEST"]); ?> #list', function(data) {
			// 	// 		var no = '<?php echo count($row_list); ?>';				
			// 	// 		for(i=1;i<=no-1;i++){
			// 	// 			//$("#TGL_APPROVE_"+i).datepicker();
			// 	// 			//$("#TGL_APPROVE_"+i).datepicker( "option", "minDate", selectedDate );
			// 	// 			//$("#TGL_APPROVE_"+i).datepicker({ 	minDate: 0,
			// 	// 			//									dateFormat: 'dd-mm-yy'});			
			// 	// 			$("#TGL_APPROVE_"+i).datepicker({ 	dateFormat: 'dd-mm-yy'});									
			// 	// 		}
			// 	// });
			// });
		}).fail(function( jqxhr, textStatus, error ) {
			var err = textStatus + ", " + error;
			alert( "Get Container Failed: " + err );
		});
	}else{
		var no_req_ict_		= $("#NO_REQUEST2").val();
		var no_req_ict2_	= $("#NO_REQUEST3").val();
		var no_do			= $("#NO_DO").val();
		var no_bl			= $("#NO_BL").val();
		var no_booking_		= $("#NO_BOOKING").val();
		var sp2				= $("#SP2").val();
		var no_req 			= $("#no_req").val();
		var no_req_rec		= "<?php echo($row_request["NO_REQUEST_RECEIVING"]); ?>";
		var no_req_del		= "<?php echo($row_request["NO_REQUEST_DELIVERY"]); ?>";
		var url = '<?=HOME?><?=APPID?>/approve/';
		$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?php echo($HOME); ?>images/loadingbox.gif />' });
		$.post(url,{NO_REQ_REC : no_req_rec, NO_REQ_DEL : no_req_del, tgl_approve : $tgl_approve, no_cont : $no_cont, no_req : no_req, NO_REQ_ICT : no_req_ict_, NO_REQ_ICT2 : no_req_ict2_, NO_DO : no_do, NO_BL : no_bl, SP2 : sp2, NO_BOOKING : no_booking_, ASAL_CONT : 'DEPO' }, function(data){
			if(data){
					//alert(data);
					$.unblockUI({ 
					onUnblock: function(){ alert('Container Approved'); } 
					});
			}
					
			$("#cont_list").load('<?=HOME?><?=APPID?>/cont_list?no_req=<?php echo($row_request["NO_REQUEST"]); ?> #list', function(data) {
				var no = '<?php echo count($row_list); ?>';				
					for(i=1;i<=no-1;i++){
						//$("#TGL_APPROVE_"+i).datepicker();
						//$("#TGL_APPROVE_"+i).datepicker( "option", "minDate", selectedDate );
						//$("#TGL_APPROVE_"+i).datepicker({ 	minDate: 0,
						//									dateFormat: 'dd-mm-yy'});			
						$("#TGL_APPROVE_"+i).datepicker({ 	dateFormat: 'dd-mm-yy'});									
					}
			});
		});
	}

	
}

function info_lapangan(){
	$("#dialog-form").load('<?=HOME?><?=APPID?>.info/info/');
	$("#dialog-form").dialog( "open" );
}
</script>