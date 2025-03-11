<?php if (!defined("XLITE_INCLUSION")) die(); ?><span class="graybrown"><img src='images/dokumenbig.png' border='0' class="icon"/> Realisasi Stripping</span><form id="realisasi"><fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; "><center><table style="margin: 30px 30px 30px 30px;" border="0"><tr><td>No. Container</td><td> : </td><td><input type="text" name="NO_CONT" ID="NO_CONT" placeholder="AUTOCOMPLETE" style="font-size:20px; font-weight:bold; text-transform:uppercase" tabindex="2" /></td></tr><!--
            <tr>
                <td>Status Container</td>
                <td> : </td>
                <td> <select name="STATUS" id="STATUS">
                        <option value="FULL" selected="selected"> FULL </option>
                        <option value="EMPTY"> EMPTY </option>
                     </select> 
                </td>
            </tr>
            --></table><fieldset style="margin-left:80px; margin-right:80px; border:medium solid #999; padding-bottom:10px;"><legend style="font-size:16px; font-weight:bold; color:#999"> Data Petikemas </legend><table><tr><td> SIZE </td><td> : </td><td><input type="text" readonly="readonly" value="" placeholder="Auto Fill" name="SIZE" id="SIZE" /></td><td width="200px">&nbsp;</td><td> TYPE </td><td> : </td><td><input type="text" readonly="readonly" value="" placeholder="Auto Fill" name="TYPE" id="TYPE" /></td></tr><tr><td> VIA </td><td> : </td><td><input type="text" readonly="readonly" value="" placeholder="Auto Fill" name="VIA" id="VIA" /></td><td width="200px">&nbsp;</td><td> Nomor Request Stripping </td><td> : </td><td><input type="text" readonly="readonly" value="" placeholder="Auto Fill" name="NO_REQ" id="NO_REQ" /></td></tr><tr><td> Tanggal Request Stripping </td><td> : </td><td><input type="text" readonly="readonly" value="" placeholder="Auto Fill" name="TGL_REQ" id="TGL_REQ" /></td><td width="200px"> &nbsp; </td><td> Penggunaan alat mekanis </td><td> : </td><td><input type="checkbox" name="alat" id="alat" /></td></tr><tr><td> Keterangan </td><td> : </td><td><input type="text" value="" name="keterangan" id="keterangan" /></td><td width="200px"> &nbsp; </td></tr></table></fieldset></center></fieldset></form><fieldset class="form-fieldset" style="margin: 5px 80px 5px 80px; "> &nbsp;&nbsp;<a class="link-button" onclick="set_realisasi()"><img src='images/tambah.png' border="0"> Simpan </a>&nbsp;<br/><br/></fieldset><script>

$(function() 
{	
	$( "#NO_CONT" ).autocomplete({
		minLength: 6,
		source: "<?php echo($HOME); ?><?php echo($APPID); ?>.auto/container",
		focus: function( event, ui ) {
			$( "#NO_CONT" ).val( ui.item.NO_CONTAINER );
			return false;
		},
		select: function( event, ui ) {
			$( "#NO_CONT" ).val( ui.item.NO_CONTAINER );
			$( "#SIZE" ).val( ui.item.SIZE_);
			$( "#TYPE" ).val( ui.item.TYPE_);
			$( "#VIA" ).val( ui.item.VIA);
			$( "#NO_REQ" ).val( ui.item.NO_REQUEST );			
			$( "#TGL_REQ" ).val( ui.item.TGL_REQUEST );
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li style='text-align:left'></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + item.NO_CONTAINER + " | "+item.NO_REQUEST+"<br/> "+item.SIZE_+" "+item.TYPE_+"</a>" )
			.appendTo( ul );
	};
	
});

function set_realisasi()
{
	var no_cont_	= $("#NO_CONT").val();
	var no_req_		= $("#NO_REQ").val();
	var alat_		= $("#alat").val();
	var keterangan		= $("#keterangan").val();
	
	var url			= "<?php echo($HOME); ?><?php echo($APPID); ?>.ajax/set_realisasi";
	
	$.blockUI({ message: '<h1>Please wait...</h1>' });
	$.post(url,{NO_CONT: no_cont_, NO_REQ: no_req_, alat : alat_, keterangan : keterangan},function(data){
		console.log(data);
		$.unblockUI({ 
				onUnblock: function(){
				} 
				});
		if(data == "OK")
		{
			alert("BERHASIL");
			
			$('#realisasi').each(function(){
	        	this.reset();
			});
		}
		else if(data == "OVER")
		{
			alert("Masa Stripping Telah Habis, Silahkan Lakukan Perpanjangan Terlebih Dahulu");
			
			$('#realisasi').each(function(){
	        	this.reset();
			});
		}
         else if(data == "NOTA_FAIL")
            {
                alert("Nota Belum Lunas");
                
                $('#realisasi').each(function(){
                    this.reset();
                });
            }
		else if(data == "NOT_AKTIF")
		{
			alert("PETIKEMAS "+no_cont_+" DENGAN NOMOR REQUEST "+no_req_+" SUDAH DI REALISASI STRIPPING");
			
			$('#realisasi').each(function(){
	        	this.reset();
			});
		}
		else if(data == "NOT_PLACEMENT"){
			alert("PETIKEMAS "+no_cont_+" BELUM DI PLACEMENT");
		}
		else if(data == "LOGIN_CLEAR"){
			alert("Session Habis / Login Kembali");
			$('#realisasi').each(function(){
	        	this.reset();
			});
		}
	});	
}



</script>