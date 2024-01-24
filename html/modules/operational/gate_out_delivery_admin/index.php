<style>
.content{
	width:95%;
	margin-left:auto;
	margin-right:auto;
	margin-bottom: 10px;
	margin-top:20px;

}
.ui-jqgrid .ui-jqgrid-htable th div {
    height:auto;
    overflow:hidden;
    padding-right:4px;
    padding-top:2px;
    position:relative;
    vertical-align:text-top;
    white-space:normal !important;
}
</style>

<script type="text/javascript">
setInterval(function()
		{
			jQuery('#list_cont_js').jqGrid('setSelection', '0');
		},
		2000);

	function cek_text()
	{
		var f;
		if($("#no_cont").val()=="")
		{
			alert("Nomor container harus diisi");
			return false;
		}
		else if($("#no_cont").val().length < 11)
		{
			alert("Harap cek nomor container!!!");
			return false;
		}
		else if(berat=="")
		{
			alert("Berat harus diisi");
			return false;
		}
		else if(vessel=="")
		{
			alert("Vessel ~ Voyage harus diisi");
			return false;
		}
		else
		{
			return warn();
		}
	}
	
	function warn()
	{
		var r=confirm("Container has been saved, Thank you !!!");
		if(r==true)
		{
			return true;
		}
		else
		{
			return false;
		}
	
	}

</script>

<script type='text/javascript'>
var my_cont='';
var my_size='';
var my_tipe='';
var my_status='';
var my_req='';
var my_hz='';
var my_vessel='';
var my_voyage='';
var my_pel_asal='';
var my_pel_tuj='';
var my_idpel_asal='';
var my_idpel_tuj='';
var v_blok='';
var v_slot='';
var v_row='';
var v_tier='';
var v_err='';
var my_h='';

jQuery(function() {
 jQuery("#list_cont_js").jqGrid({
	url:'<?=HOME?>datanya/data_gateout?q=gateout',
	mtype : "post",
	datatype: "json",
	colNames:['No UKK','Container','Size / Type / Status','Vessel - Voy','Gate Out','Yard Location'], 
	colModel:[
		{name:'no_ukk', width:120, align:"center"},
		{name:'no_cont', width:100, align:"center"},
		{name:'sts', width:120, align:"center",sortable:false,search:false},
		{name:'voy', width:220, align:"center",sortable:false,search:false},
		{name:'tgl_gate_out', width:140, align:"center",sortable:false,search:false},
		{name:'yd', width:120, align:"center",sortable:false,search:false}		
		],
	rowNum:5,
	width: 865,
	height: "100%",//250

	//rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_l_cont',
	viewrecords: true,
	shrinkToFit: false,
	//grid.setRowData ( id, false, {height: 30} ),
	caption:"Update History Gate Out"
 });
  jQuery("#list_cont_js").jqGrid('navGrid','#pg_l_cont',{del:false,add:false,edit:false,search:false}); 
 jQuery("#list_cont_js").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
});


function isNumberKey(evt)
{
	var charCode = (evt.which) ? evt.which : event.keyCode;

	if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		return false;
	}
		
	return true;
}
function add_cont_s()
{
	var no_c_s = $("#no_conts").val();
	var size_c_s = $("#size_s").val();
	var type_c_s = $("#tipe_s").val();
	var url				= "<?=HOME?>operational.gate_in.add_container/add_c";
	$.post(url,{NO_CONT: no_c_s, SIZE_S: size_c_s, TYPE_S: type_c_s},function(data) 
	{
		alert(data);
		console.log(data);
	});	
	
	$("#add_container").dialog("close");
}
function find_cont()
{
	if(my_cont=='')	
	{
		alert('container not found\nplease add container');	
		return false;
	}
	else
	{
		$( "#no_ukk" ).val(my_ukk);
		$( "#no_cont" ).val(my_cont);
		$( "#size" ).val(my_size);
		$( "#tipe" ).val(my_tipe);
		$( "#status" ).val(my_status);
		$( "#no_req" ).val(my_no_req);
		$( "#iso_code" ).val(my_iso);
		$( "#berat" ).val(my_berat);
		$( "#height_c" ).val(my_height_c);
		$( "#hz" ).val(my_hz);
		$( "#vessel" ).val(my_vessel);
		$( "#voyage_in" ).val(my_voyage_in);
		$( "#voyage_out" ).val(my_voyage_out);
		$( "#pel_asal" ).val(my_pel_asal);
		$( "#pel_tujuan" ).val(my_pel_tuj);
		$( "#tgl_berlaku" ).val(my_tanggal_berlaku);
		$( "#yd_pss" ).val(my_yd_posisi);
		
		return true;
	}
}

function js_created()
{
	var url	= "<?=HOME?>operational.gate_out_delivery_admin.auto/save";
	
	var n_p = $('#no_pol').val();
	var seal_no = $('#seal_no').val();
	var remarkc=$('#remark').val();
	var tgl_go=$('#tgl_gate_out').val();
	var go_jam=$('#go_jam').val();
	var go_menit=$('#go_menit').val();	
	
	//alert(brt);
	$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?=HOME;?>images/loading81.gif width="30" height="30" /><br><br>' });
	$.post(url,{NO_CONT: my_cont, REQ_DEV: my_req, NO_POL:n_p,REMARK:remarkc,NO_UKK:my_ukk,SEAL:seal_no,TGL_GATE_OUT:tgl_go,JAM_GATE_OUT:go_jam,MENIT_GATE_OUT:go_menit},function(data) 
	{
		console.log(data);
		/*
		if (data=='sukses')
		{
			alert("success...");
			window.location = "<?=HOME?>operational.gate_out_delivery/";
		}
		else
		{
			alert("failed...");
			window.location = "<?=HOME?>operational.gate_out_delivery/";
		}
		*/
		$.unblockUI({
				onUnblock: function(){ }
				});
		alert(data);
		window.location = "<?=HOME?>operational.gate_out_delivery_admin/";
		
	});	
}


function print_js()
{
	$("#print_jobslip").printElement();
	$("#add_container").dialog("close");
}


	$(document).ready(function() 
	{
		/*
		$("#finds").contextMenu({
					menu: 'myMenu'
				},
					function(action, el, pos)
					{
						$.blockUI({ message: '<h1><br>Please wait...Syncrhonize Container</h1><br><img src={$HOME}images/loadingbox.gif /><br><br>' });
						var url="<?=HOME?>operational.gate_in.add_container/sync";
						$.post(url,{},function(data) 
						{
							alert('ok');
						});
					var ccnt=$("#NO_CONTAINER").val();	
					$('#add_container').html('<table width="100%" height"100%"><tr><td bgcolor="white"><p align="center"><img src="<?=HOME?>images/loading_gud.gif" width="55%" height="90%"/></p></td></tr></table>').dialog({modal:true, height:200,width:450});
					$('#add_container').load("<?=HOME?>operational.gate_in.add_container/sync").dialog({modal:true, height:200,width:450});
				});
		*/

		$("#tgl_gate_out").datepicker({
                                dateFormat: 'd/m/yy'
                  
                            });
		
        $( "#NO_CONTAINER" ).autocomplete({
			minLength: 3,
			source: "operational.gate_out_delivery.auto/container",
			focus: function( event, ui ) 
			{
				$( "#NO_CONTAINER" ).val( ui.item.NO_CONTAINER);
				return false;
			},
			select: function( event, ui ) 
			{
				/*$( "#no_cont" ).val( ui.item.NO_CONTAINER);
				$( "#size" ).val( ui.item.UKURAN);
				$( "#tipe" ).val( ui.item.TYPE_);
				
				*/
				my_ukk=ui.item.NO_UKK;
				my_cont=ui.item.NO_CONTAINER;
				my_size=ui.item.SIZE_;
				my_tipe=ui.item.TYPE_;
				my_status=ui.item.STATUS;
				my_no_req=ui.item.NO_REQUEST;
				my_iso=ui.item.ISO_CODE;
				my_berat=ui.item.BERAT;
				my_height_c=ui.item.HEIGHT;
				my_hz=ui.item.HZ; 
				my_vessel=ui.item.NM_KAPAL;
				my_voyage_in=ui.item.VOYAGE_IN;
				my_voyage_out=ui.item.VOYAGE_OUT;
				my_pel_asal=ui.item.NM_PELABUHAN_ASAL;
				my_pel_tuj=ui.item.NM_PELABUHAN_TUJUAN;
				my_tanggal_berlaku=ui.item.TANGGAL_BERLAKU;	
				my_yd_posisi=ui.item.YD_POSISI;
				
				return false;
			}
		})
		.data( "autocomplete" )._renderItem = function( ul, item ) 
		{
			return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a> " + item.NO_CONTAINER + " <br> " + item.SIZE_ + " / "+item.TYPE_+" / "+item.STATUS+"<BR>"+item.NM_KAPAL+" / "+item.VOYAGE_IN+" - "+ item.VOYAGE_OUT +" <br> "+ item.NO_REQUEST +"</a>")
			.appendTo( ul );
		
		};
	});

function help()
{
	$('#help_d').load("<?=HOME?>operational.gate_out_delivery.auto/help").dialog({modal:true, height:400,width:600});
}
</script>

<script src="<?=HOME?>context_menu/jsprint.js" type="text/javascript"></script>

<div class="content" id="finds">
	<p>
	<h2> <img src="<?=HOME?>images/truckyellow1.png" height="5%" width="5%" style="vertical-align:middle">&nbsp;<font color="#0378C6">Delivery</font> Gate-Out Administrator</h2></p>
	
	<p><br/>
	  </p>
	<table>
	
		<tr>
				<td class="form-field-caption" valign="middle" align="right" width="125">NO CONTAINER</td>
				<td valign="middle" class="form-field-input">: </TD>
				<td valign="middle" class="form-field-input">
				<input style="font-size:26px; font-weight:bold;" id="NO_CONTAINER" name="NO_CONTAINER" size="15" title="entry" class="suggestuwriter" type="text" maxlength="16" value="" /> &nbsp; <input type="button" name="find_c" id="find_c" value="FIND" onclick="find_cont()" style="height:35px; width:50px;" class="context-menu-one	">&nbsp;&nbsp;</TD>
				<!--<td valign="middle" ALIGN="left" class="form-field-input"><button onclick="help()" style="height:35px; width:50px;"><img src='images/Refresh2.png' width=20px height=20px border='0'></button></td> -->
				<td valign="middle" ALIGN="left" class="form-field-input"><button onclick="sync()" style="height:35px; width:50px;"><img src='images/Refresh2.png' width=20px height=20px border='0'></button></td> 
				
		</tr>
		<TR>
			<TD colspan="3" align="right"><b><i><font color="red">click syncrhonize</font> - if container unavailable to gate out</i></b></TD>
		</TR>
	</table>
	<hr width="870" color="#e1e0de"></hr><p><br/></p>
	<form method="post" action="<?=HOME?>operational.gate_out_delivery/save" onSubmit='return cek_text()'>
	<table>
	<tr>
		<td class="form-field-caption" align="right">No Request</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="15" maxlength="11" name="no_req" id="no_req" readonly="readonly" />
		</td>
		<td></td>
		<td class="form-field-caption" align="right">Yard Location</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="15" id="yd_pss" name="yd_pss" readonly="readonly" />
		</td>
	</tr>
	 <tr>
		<td class="form-field-caption" align="right">No Container</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="15" maxlength="11" name="no_cont" id="no_cont" readonly="readonly" />
		</td>
		<td></td>
		<td class="form-field-caption" align="right">No Polisi</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="10" id="no_pol" name="no_pol" />
		</td>
	</tr>
	 <tr>
		<td class="form-field-caption" align="right">Size / Type / Status / HZ</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="5" name="size" id="size" readonly="readonly" />
			<input type="text" size="5" name="tipe" id="tipe" readonly="readonly" />
			<input type="text" size="5" name="status" id="status" readonly="readonly" />&nbsp;
			<input type="text" size="5" name="hz" id="hz" readonly="readonly" />
		</td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">Berat</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="5" name="berat" id="berat" readonly="readonly" /> Kgs
		</td>
     </tr>
	 <tr>
		<td class="form-field-caption" align="right">Height</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="5" name="height_c" id="height_c" readonly="readonly" />
		</td>
	 </tr>
	 <tr>
		<td class="form-field-caption" align="right">ISO CODE</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="5" name="iso_code" id="iso_code" readonly="readonly" />
		</td>
		<td></td>
		<td class="form-field-caption" align="right">No Seal</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="30" name="seal_no" id="seal_no" />
		</td>
	 </tr>
	 <tr>
		<td class="form-field-caption" align="right">Vessel / Voyage</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="25" name="vessel" id="vessel" readonly="readonly" /> / <input type="text" size="5" name="voyage_in" id="voyage_in" readonly="readonly" />
			<input type="hidden" name="id_vessel" id="id_vessel" />
			<input type="hidden" name="id_vs" id="id_vs" />
		</td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">Tgl Gate Out</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="8" name="tgl_gate_out" id="tgl_gate_out" />&nbsp;&nbsp;&nbsp;<input type="text" name="go_jam" id="go_jam" size="2" maxlength="2"/>&nbsp;:&nbsp;<input type="text" name="go_menit" id="go_menit" size="2" maxlength="2"/>
		</td>
     </tr>
	 <tr>
		<td class="form-field-caption" align="right">Pelabuhan Asal ~ Tujuan</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="15" name="pel_asal" id="pel_asal" readonly="readonly" />
			<input type="hidden" name="id_pel_asal" id="id_pel_asal" /> ~
			<input type="text" size="15" name="pel_tujuan" id="pel_tujuan" readonly="readonly" />
			<input type="hidden" name="id_pel_tujuan" id="id_pel_tujuan" />
		</td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">Remark </td>
		<td class="form-field-caption" align="right">:</td>
		<td><textarea type="text"  size="20" name="remark" id="remark" value="" /></textarea>
		</td>
     </tr>
	 <tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	 <td>
		<input type="button" value= "Gate Out" onclick="js_created()"/>
	 </td>
	 </tr>
	 
	</table>
	
	</form>
	<div id="add_container" ></div>
	<div id="print_jobslip" ></div>
	<div id="help_d" ></div>
	<br>
	<hr width="870" color="#e1e0de"></hr>
	
	<br/>
	<table id='list_cont_js' width="100%"></table> <div id='pg_l_cont'></div>
	<br/>
		
	
</div>
<script>
function sync()
{
	$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?=$HOME?>images/loadingbox.gif /><br><br>' });
	var url="<?=HOME?>operational.gate_out_delivery.add_container/sync";
	$.post(url,{},function(data) 
				  {
						$.unblockUI({
						onUnblock: function(){  }
						});
			      });	
}

</script>

