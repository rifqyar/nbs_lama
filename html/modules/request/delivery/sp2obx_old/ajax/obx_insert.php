<script type="text/javascript">
var v_idplp = "";
var v_vessel = "";
var v_voy_in = "";
var v_voy_out = "";
var v_tpl1 = "";
var v_tpl2 = "";
var v_nobc = "";
var v_bcdate = "";
var v_ata = "";
var v_atd = "";
var glob_emkl = "";

$('#loadobx').load("<?=HOME?>request.delivery.sp2obx.ajax/load_obx?idplp="+0+"&setoption=0");

$(document).ready(function(){	
				$("#plp_no").autocomplete({		
					minLength: 3,
					source: "request.delivery.sp2obx.auto/plp_no",
					focus: function( event, ui ) 
					{
						$("#plp_no").val( ui.item.ID_PLP);
						return false;
					},
					select: function( event, ui ) 
					{					
						$("#plp_no").val(ui.item.ID_PLP);
						
						v_idplp = ui.item.ID_PLP;
						v_vessel = ui.item.VESSEL;
						v_voy_in = ui.item.VOY_IN;
						v_voy_out = ui.item.VOY_OUT;
						v_tpl1 = ui.item.TPL1;
						v_tpl2 = ui.item.TPL2;
						v_nobc = ui.item.BC_NUMB;
						v_bcdate = ui.item.BC_DATE;
						v_ata = ui.item.ATA;
						v_atd = ui.item.ATD;
						
						return false;
					}
				})
					.data( "autocomplete" )._renderItem = function( ul, item ) 
					{
						return $( "<li></li>" )
						.data("item.autocomplete", item )
						.append("<a>" + item.ID_PLP + "<br/><b>NO PLP : " + item.PLP_NUMB + "</b><br/>" + item.VESSEL + " " + item.VOY_IN + "-" + item.VOY_OUT + "</a>")
						.appendTo(ul);
					};	

				$("#tgl_del").datepicker({
					dateFormat: 'dd-mm-yy'
					});
				
				});
				
$(document).ready(function(){
	
	$("#emkl").autocomplete({
		minLength: 3,
		source: "request.delivery.sp2obx.auto/pelanggan",
		focus: function( event, ui ) 
		{
			$("#emkl").val( ui.item.NAMA_PERUSAHAAN);
			return false;
		},
		select: function( event, ui ) 
		{
			$("#emkl").val(ui.item.NAMA_PERUSAHAAN);
			glob_emkl = ui.item.NAMA_PERUSAHAAN;
			$("#alamat").val(ui.item.ALAMAT_PERUSAHAAN);
			$("#npwp").val(ui.item.NO_NPWP);
			$("#coa").val(ui.item.KD_PELANGGAN);
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data("item.autocomplete", item )
		.append("<a><b>" + item.NAMA_PERUSAHAAN + "</b><br>" + item.ALAMAT_PERUSAHAAN + "<br>" + item.NO_NPWP + "</a>")
		.appendTo(ul);
	};		
	
				});

function load_detail()
{
	$("#vessel").val(v_vessel);
	$("#voyage").val(v_voy_in);
	$("#voyage_out").val(v_voy_out);
	$("#tpl1").val(v_tpl1);
	$("#tpl2").val(v_tpl2);
	$("#nobc").val(v_nobc);
	$("#bcdate").val(v_bcdate); 
	$("#rta").val(v_ata); 
	
	$('#loadobx').load("<?=HOME?>request.delivery.sp2obx.ajax/load_obx?idplp="+v_idplp+"&setoption=1");
}

function save_obx()
{
	var list_cont = new Array();	
	$('#box2View > option').each(function(){
							list_cont.push($(this).val());
							});
	console.log(list_cont);
	var contjoint = list_cont.join("&");
	//alert(contjoint);
	
	if(v_idplp=="")
	{
		alert("PLP Number Not Found");
		return false;
	}
	else if(glob_emkl == "" || glob_emkl != "Pelayanan Jasa")
	{
		alert("Customer ID Not Valid");
		return false; 
	}
	else if($("#tgl_del").val()=="")
	{
		alert("Delivery Date Not Found");
		return false;
	}
	else
	{	
		var url = "<?=HOME?>request.delivery.sp2obx.ajax/app2_plp";
		var r = confirm("Request SP2 OBX akan disimpan, anda yakin?");
		if(r == true)
		{
			$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?=HOME;?>images/103.gif width="30" height="30" /><br><br>' });
			$.post(url,{
						ID_PLP : v_idplp,
						CUST_NO : $("#coa").val(),
						CUST_NAME : $("#emkl").val(),
						CUST_ADDR : $("#alamat").val(),
						CUST_TAX : $("#npwp").val(),
						TGL_DEL : $("#tgl_del").val(),
						LIST_CONT : contjoint
			},function(data){
				//alert(data);
				if(data == "OK")
				{
					$.unblockUI({
					onUnblock: function(){ }
					});
					alert("Success...");
					window.location = "<?=HOME?>request.delivery.sp2obx/";
				}
				else
				{
					$.unblockUI({
					onUnblock: function(){ }
					});
					alert("Failed...");
					window.location = "<?=HOME?>request.delivery.sp2obx/";
				}
			});	
		}
		else
		{
			return false;
		}
	}
}

</script>

	<br />
	<table>
		<tr>
			<td class="form-field-caption" align="right"></td>
			<td class="form-field-caption" align="right"></td>
			<td></td>			
		</tr>
		<tr>
			<td class="form-field-caption" align="right">PLP NUMBER</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="plp_no" id="plp_no" size="15" placeholder="Autocomplete"/>&nbsp;&nbsp;<button onclick="load_detail()"><img border="0" src="images/valid.png" height="12px" width="12px"/></button>
			</td>			
		</tr>
		<tr>
			<td class="form-field-caption" align="right">VESSEL / VOY</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="vessel" id="vessel" size="30" ReadOnly /> / <input type="text" name="voyage" id="voyage" size="5" ReadOnly /><input type="text" name="voyage_out" id="voyage_out" size="5" ReadOnly />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">RTA</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left"><input type="text" name="rta" id="rta" size="10" ReadOnly /></td>			
		</tr>
		<tr>
			<td class="form-field-caption" align="right">TPS ASAL</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left"><input type="text" name="tpl1" id="tpl1" size="15" ReadOnly /></td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">TPS TUJUAN</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left"><input type="text" name="tpl2" id="tpl2" size="15" ReadOnly /></td>			
		</tr>
		<tr>
			<td class="form-field-caption" align="right">BC NUMBER</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left"><input type="text" name="nobc" id="nobc" size="20" ReadOnly /></td>			
		</tr>
		<tr>
			<td class="form-field-caption" align="right">BC DATE</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left"><input type="text" name="bcdate" id="bcdate" size="10" ReadOnly /></td>			
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Nama Pelanggan</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="emkl" id="emkl" size="30" placeholder="Autocomplete"/> )* Pelayanan Jasa
				<input type="hidden" name="coa" id="coa" />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Alamat</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="alamat" id="alamat" size="50" ReadOnly />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">NPWP</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="npwp" id="npwp" size="30" ReadOnly />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Tgl Delivery</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="tgl_del" id="tgl_del" size="10" />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right" valign="top">KETERANGAN</td>
			<td class="form-field-caption" align="right" valign="top"> : </td>
			<td class="form-field-caption" align="left" valign="top">
				<textarea id="ket" cols="25" rows="2" name="ket" /></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="3" align="center">
				<br/>
				<div id="loadobx"></div>
			</td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
	</table>
	<input id="id_SAVEBUT" class="butsave" type="button" onClick="save_obx()" value="S A V E">