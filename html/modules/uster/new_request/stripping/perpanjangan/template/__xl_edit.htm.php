<?php if (!defined("XLITE_INCLUSION")) die(); ?><form id="form" method="POST" action="<?=HOME?><?=APPID?>/edit_do"> <span class="graybrown"><img src='images/document-open.png' border='0' class="icon"/><font color="#DE7E21"> Perpanjangan Stripping Petikemas </font></span><fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; "><center><table style="margin: 30px 30px 30px 30px;" ><tr><td class="form-field-caption">Nomor Request</td><td class="form-field-caption"> : </td><td class="form-field-input"><input type="text" name="NO_REQ" id="NO_REQ" value="<?php echo($row_request["NO_REQUEST"]); ?>" readonly="readonly" /></td></tr><tr><td class="form-field-caption">Penerima / Consignee</td><td class="form-field-caption"> : </td><td><input type="text" id="CONSIGNEE" size="40" name="CONSIGNEE" class="kdemkl " placeholder="<?php echo($row_request["NAMA_PEMILIK"]); ?>" style="background-color:#FFFFCC;" readonly="readonly" /><input size="40" name="ID_CONSIGNEE" id="ID_CONSIGNEE" type="hidden" value="<?php echo($row_request["KD_CONSIGNEE"]); ?>" /><input size="40" name="ALMT_CONSIGNEE" id="ALMT_CONSIGNEE" type="hidden" /><input size="40" name="NPWP_CONSIGNEE" id="NPWP_CONSIGNEE" type="hidden" /></td></tr><tr><td class="form-field-caption">Nama Personal Consignee</td><td class="form-field-caption"> : </td><td><input type="text" id="CONSIGNEE_PERSONAL" size="40" name="CONSIGNEE_PERSONAL" placeholder="<?php echo($row_request["CONSIGNEE_PERSONAL"]); ?>" style="background-color:#FFFFCC;" readonly="readonly" /></td></tr><tr><td class="form-field-caption"></td><td></td><td><input type="hidden" id="PENUMPUKAN" size="40" name="PENUMPUKAN" class="kdemkl " placeholder="<?php echo($row_request["NAMA_PENUMPUK"]); ?>" style="background-color:#FFFFCC;" readonly="readonly" /><input size="40" name="ID_PENUMPUKAN" id="ID_PENUMPUKAN" type="hidden" /><input size="40" name="ALMT_PENUMPUKAN" id="ALMT_PENUMPUKAN" type="hidden" /><input size="40" name="NPWP_PENUMPUKAN" id="NPWP_PENUMPUKAN" type="hidden" /></td></tr><tr><td class="form-field-caption">Nama Kapal</td><td class="form-field-caption"> : </td><td><input id="NM_KAPAL" size="40" name="NM_KAPAL" type="text" style="background-color:#FFFFCC;" value="<?php echo($row_request["O_VESSEL"]); ?>" />&nbsp;&nbsp; <input type="text" id="VOYAGE_IN" size="5" name="VOYAGE_IN" value="<?php echo($row_request["O_VOYIN"]); ?>" /> - <input type="text" id="VOYAGE_OUT" size="5" name="VOYAGE_OUT" value="<?php echo($row_request["O_VOYOUT"]); ?>" /><input type="hidden" id="NO_BOOKING" name="NO_BOOKING" /><input type="hidden" id="IDVSB" name="IDVSB" value="<?php echo($row_request["O_IDVSB"]); ?>" /><input type="hidden" id="CALLSIGN" name="CALLSIGN" /></td></tr><!-- <tr>
       		<td class="form-field-caption">Tanggal Rencana Mulai Stripping</td>
            <td> : </td>
            <td> <input type="text" id="TGL_AWAL"  name="TGL_AWAL" value="<?php echo($row_request["TGL_AWAL"]); ?>" placeholder="<?php echo($row_request["TGL_AWAL"]); ?>"/>
		</tr>
        <tr>
        	<td class="form-field-caption">Tanggal Rencana Selesai Stripping</td>
            <td> : </td>
            <td> <input type="text" id="TGL_AKHIR"  name="TGL_AKHIR" value="<?php echo($row_request["TGL_AKHIR"]); ?>" placeholder="<?php echo($row_request["TGL_AKHIR"]); ?>"/>
		</tr> --><tr><td class="form-field-caption">Nomor D.O</td><td class="form-field-caption"> : </td><td class="form-field-input"><input type="text" id="NO_DO" name="NO_DO" value="<?php echo($row_request["NO_DO"]); ?>" readonly="readonly" /></td></tr><tr><td class="form-field-caption">Nomor B.L</td><td class="form-field-caption"> : </td><td class="form-field-input"><input type="text" id="NO_BL" name="NO_BL" value="<?php echo($row_request["NO_BL"]); ?>" readonly="readonly" /></td></tr><tr><td class="form-field-caption">Nomor SPPB</td><td class="form-field-caption"> : </td><td><input type="text" id="NO_SPPB" size="40" name="NO_SPPB" value="<?php echo($row_request["NO_SPPB"]); ?>" readonly="readonly" /></td></tr><tr><td class="form-field-caption">Tanggal SPPB</td><td class="form-field-caption"> : </td><td><input type="text" id="TGL_SPPB" name="TGL_SPPB" value="<?php echo($row_request["TGL_SPPB"]); ?>" readonly="readonly" /></td></tr><tr><td class="form-field-caption">Type Stripping</td><td class="form-field-caption"> : </td><td class="form-field-input"><input type="text" name="TYPE_S" id="TYPE_S" value="<?php echo($row_request["TYPE_STRIPPING"]); ?>" readonly="readonly" /></td><td> <?php echo($dla); ?> </td></tr><tr><td class="form-field-caption">Keterangan</td><td class="form-field-caption"> : </td><td class="form-field-input"><input type="text" id="keterangan" name="keterangan" style="width:250px" /></td></tr><?php
        	if($row_request["APPROVE"] == "NY")
            {
        ?><!--<tr>
        	<td class="form-footer"> <a id="submitForm" onclick="save()" class="link-button" ><img src='images/valid.png' border='0' />&nbsp;Simpan Hasil Edit</a></td>
	       	<td class="form-footer" colspan=2> <a id="submitForm" href="<?=HOME?><?=APPID?>/approve?no_req=<?php echo($row_request["NO_REQUEST"]); ?>" class="link-button" ><img src='images/yes.png' border='0' />&nbsp;APPROVE</a> &nbsp; &nbsp;<a id="submitForm" href="<?=HOME?><?=APPID?>/reject?no_req=<?php echo($row_request["NO_REQUEST"]); ?>" class="link-button" ><img src='images/batal.png' border='0' />&nbsp;TOLAK</a></td>
        </tr>--><?php
        	}
            else
            {
        	}
        ?></table></center></fieldset><fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; "><center><div id="cont_list" style="margin: 10px 10px 10px 10px;"></div><?php if(!isset($_GET['pre'])) { ?><span id="saveit"><a id="resetForm" onclick="simpanarea();" class="link-button" style="font-weight:bold; color:#F00" ><img src='images/valid.png' border='0' />&nbsp;<blink>Edit Perpanjangan Stripping</blink></a> &nbsp;&nbsp; <!--<input tabindex="11" style="height:30px" type="button" value="Perpanjangan SP2" onclick="simpanarea();"  />--></span><?php  } 
        if($row_request['CLOSING'] != 'CLOSED'){ ?><span id="approveit"><a id="resetForm" onclick="approve();" class="link-button" style="font-weight:bold; color:#F00" ><img src='images/valid.png' border='0' />&nbsp;<blink>Approve Request</blink></a> &nbsp;&nbsp; <!--<input tabindex="11" style="height:30px" type="button" value="Perpanjangan SP2" onclick="simpanarea();"  />--></span><?php } ?><br><br></center></fieldset></form><script>
$(function(){
	$( "#dialog-form" ).dialog({
			autoOpen: false,
			height: 400,
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
});
$(function() {	
	$( "#tgl_bongkar" ).datepicker();
	$( "#tgl_bongkar" ).datepicker( "option", "dateFormat", "dd-mm-yy" );
	

	/*$( "#tgl_mulai" ).datepicker();
	$( "#tgl_mulai" ).datepicker( "option", "dateFormat", "dd-mm-yy" );
	
	$( "#tgl_selesai" ).datepicker();
	$( "#tgl_selesai" ).datepicker( "option", "dateFormat", "dd-mm-yy" );*/
	
	$( "#tgl_mulai" ).datepicker({
		defaultDate: "+1w",
		changeMonth: true,
		numberOfMonths: 1,
		onClose: function( selectedDate ) {
			$( "#tgl_selesai" ).datepicker( "option", "minDate", selectedDate );
			$("#tgl_mulai").datepicker( "option", "dateFormat", "dd-mm-yy" );
		}
	});
	$( "#tgl_selesai").datepicker({
		defaultDate: "+1w",
		changeMonth: true,
		numberOfMonths: 1,
		onClose: function( selectedDate ) {
			$( "#tgl_mulai").datepicker( "option", "maxDate", selectedDate );
			$("#tgl_selesai").datepicker( "option", "dateFormat", "dd-mm-yy" );
		}
	});
	
	
	$( "#TGL_AWAL" ).datepicker();
	$( "#TGL_AWAL" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
	
	
	$( "#TGL_AKHIR" ).datepicker();
	$( "#TGL_AKHIR" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
	
	
	$("#cont_list").load('<?=HOME?><?=APPID?>/cont_list?no_req=<?php echo($row_request["NO_REQUEST"]); ?>&edit=1 #list', function(data) {        	  		
		<?php $no = $row_count["JUMLAH"];
					$i=0;
			?>
			<?php	echo "$(function() {";
				  foreach($row_tgl as $rowt){
					$i++;
					$new_date = date('Y-m-d', strtotime($rowt["TGL_SELESAI"]));
					echo '$( "#TGL_PERP_'.$i.'" ).datepicker({
						changeMonth: true,
						numberOfMonths: 1});
						$("#TGL_PERP_'.$i.'").datepicker( "option", "dateFormat", "yy-mm-dd");
						//$( "#TGL_PERP_'.$i.'" ).datepicker( "option", "minDate", "'.$new_date.'" );
						';
				}
				echo "});";
		?>
		
		}); 
	}); 	

function simpanarea(){
		//DATA_SP2     = $('#NO_CONT').val();	
		//TGL_PERP       = $('#TGL_PERP').val();	
		
		$('#form').submit();
	
}

function approve(){
    var norequest = '<?php echo($row_request["NO_REQUEST"]); ?>';
    var norequestold = '<?php echo($row_request["PERP_DARI"]); ?>';
    var url = '<?=HOME?><?=APPID?>/approve_req';
    if(confirm("Request Yang telah di approve tidak dapat diedit kembali, pastikan request sudah benar")){
    $.post(url,{NO_REQ:norequest,NO_REQOLD:norequestold},function(data){
        alert(data);
        //if(data == 'OK'){
            window.open('<?=HOME?><?=APPID?>','_self');
        //}
    });
    }
}
	
function info_lapangan(){
	$("#dialog-form").load('<?=HOME?><?=APPID?>.info/info/');
	$("#dialog-form").dialog( "open" );
}

function delete_cont($no_container){
	var no_request = "<?php echo($row_request["NO_REQUEST"]); ?>";
	var url = "<?php echo($HOME); ?><?php echo($APPID); ?>/delete_cont";
	$.post(url,{NO_CONT : $no_container, NO_REQ : no_request},function(data){
		if (data == "OK") {
			alert("Delete Succeed");
		};

	});

	$("#cont_list").load('<?=HOME?><?=APPID?>/cont_list?no_req=<?php echo($row_request["NO_REQUEST"]); ?>&edit=1 #list', function(data) {        	  		
		<?php $no = $row_count["JUMLAH"];
					$i=0;
			?>
			<?php	echo "$(function() {";
				  foreach($row_tgl as $rowt){
					$i++;
					$new_date = date('Y-m-d', strtotime($rowt["TGL_SELESAI"]));
					echo '$( "#TGL_PERP_'.$i.'" ).datepicker({
						defaultDate: "+1w",
						changeMonth: true,
						numberOfMonths: 1});
						$("#TGL_PERP_'.$i.'").datepicker( "option", "dateFormat", "yy-mm-dd");
						$( "#TGL_PERP_'.$i.'" ).datepicker( "option", "minDate", "'.$new_date.'" );
						';
				}
				echo "});";
		?>
		
		}); 
}

</script>