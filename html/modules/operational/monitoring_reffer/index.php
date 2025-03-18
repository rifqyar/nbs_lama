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
		var r=confirm("Data akan disimpan, pastikan data yang sudah diinput benar");
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
	url:'<?=HOME?>datanya/data_2?q=list_cont_gate',
	mtype : "post",
	datatype: "json",
	colNames:['Alert','Reprint','Container Numb','Kategori','Vessel - Voy','Gate In','Yard Plan<br>Blok Slot','Placement<br>B S R T','Placement\nDate'], 
	colModel:[
	{name:'alert', width:120, align:"center",sortable:false,search:false},
		{name:'aksi', width:40, align:"center",sortable:false,search:false},
		{name:'cont_no', width:120, align:"center"},
		{name:'kat', width:240, align:"center",sortable:false,search:false},
		{name:'ves', width:150, align:"center",sortable:false,search:false},
		{name:'gi', width:100, align:"center",sortable:false,search:false},
		{name:'yp', width:60, align:"center",sortable:false,search:false},
		{name:'plc', width:80, align:"center",sortable:false,search:false},
		{name:'plcd', width:100, align:"center",sortable:false,search:false}
		
	],
	rowNum:10,
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
	caption:"Update Gate In & Placement"
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
		$( "#no_cont" ).val(my_cont);
		$( "#size" ).val(my_size);
		$( "#tipe" ).val(my_tipe);
		$( "#status" ).val(my_status);
		$( "#no_req" ).val(my_req);
		$( "#hz" ).val(my_hz);
		$( "#vessel" ).val(my_vessel);
		$( "#voyage" ).val(my_voyage);
		$( "#pel_asal" ).val(my_pel_asal);
		$( "#pel_tujuan" ).val(my_pel_tuj);
		$( "#iso_code" ).val(my_iso);
		$( "#height_c" ).val(my_h);
		return true;
	}
}


	$(document).ready(function() 
	{
		
        $( "#NO_CONTAINER" ).autocomplete({
			minLength: 3,
			source: "operational.monitoring_reffer.auto/container",
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
				my_cont=ui.item.NO_CONTAINER;
				my_size=ui.item.SIZE_;
				my_tipe=ui.item.TYPE_;
				my_status=ui.item.STATUS;
				my_hz=ui.item.HZ;
				my_vessel=ui.item.NM_KAPAL;
				my_voyage=ui.item.VOYAGE;
				my_pel_asal=ui.item.NM_PELABUHAN_ASAL;
				my_pel_tuj=ui.item.NM_PELABUHAN_TUJUAN;
				
				return false;
			}
		})
		.data( "autocomplete" )._renderItem = function( ul, item ) 
		{
			return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + item.NO_CONTAINER + " <br> " + item.SIZE_ + " / "+item.TYPE_+" / "+item.STATUS+"<BR>"+item.NM_KAPAL+" / "+item.VOYAGE+" </a>")
			.appendTo( ul );
		
		};
	});


</script>


<div class="content" id="finds">
	<p>
	<h2> <img src="<?=HOME?>images/reffer.jpg" height="10%" width="10%" style="vertical-align:middle">&nbsp;<font color="#0378C6">Reffer</font> Monitoring</h2></p>
	
	<p><br/>
	  </p>
	<table>
	
		<tr>
				<td class="form-field-caption" valign="middle" align="right" width="125">NO CONTAINER</td>
				<td valign="middle" class="form-field-input">: </TD>
				<td valign="middle" class="form-field-input">
				<input style="font-size:26px; font-weight:bold;" id="NO_CONTAINER" name="NO_CONTAINER" size="15" title="entry" class="suggestuwriter" type="text" maxlength="16" value="" /> &nbsp; <input type="button" name="find_c" id="find_c" value="FIND" onclick="find_cont()" style="height:35px; width:50px;" class="context-menu-one	">&nbsp;&nbsp;</TD>
				<td valign="middle" ALIGN="left" class="form-field-input"></td>
				<td valign="middle" ALIGN="right" class="form-field-input" width="350"></td>
		</tr>
	</table>
	<hr width="870" color="#e1e0de"></hr><p><br/></p>
	<form method="post" action="<?=HOME?>operational.gate_in/save" onSubmit='return cek_text()' >
	<table>
	<tr>
			<td class="form-field-caption" align="right">No Request</td>
			<td class="form-field-caption" align="right">:</td>
			<td><input type="text" size="15" maxlength="11" name="no_req" id="no_req" style="background-color:#FFFFFF;" readonly="readonly" />
			</td>
			<td></td>
			<td class="form-field-caption" align="right">Plug In </td>
			<td class="form-field-caption" align="right">:</td>
			<td><input type="text" size="15" maxlength="11" name="plugin" id="plugin"/> 
			</td>
	</TR>
	 <tr>
		<td class="form-field-caption" align="right">No Container</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="15" maxlength="11" name="no_cont" id="no_cont" style="background-color:#FFFFFF;" readonly="readonly" />
		</td>
		<td></td>
		<td class="form-field-caption" align="right">Plug Out </td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="15" maxlength="11" name="plugout" id="plugout"/>
		</td>
	</tr>
	 <tr>
		<td class="form-field-caption" align="right">Size / Type / Status / HZ</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="5" name="size" id="size" style="background-color:#FFFFFF;" readonly="readonly" />
			<input type="text" size="5" name="tipe" id="tipe" style="background-color:#FFFFFF;" readonly="readonly" />
			<input type="text" size="5" name="status" id="status" style="background-color:#FFFFFF;" readonly="readonly" />&nbsp;
			<input type="text" size="5" name="hz" id="hz" style="background-color:#FFFFFF;" readonly="readonly" />
		</td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">Berat</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="5" name="berat" id="berat" /> Kgs
		</td>
     </tr>
	 <tr>
		<td class="form-field-caption" align="right">Vessel / Voyage</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="25" name="vessel" id="vessel" /> / <input type="text" size="5" name="voyage" id="voyage" readonly="readonly" />
			<input type="hidden" name="id_vessel" id="id_vessel" />
			<input type="hidden" name="id_vs" id="id_vs" />
		</td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">Tgl Gate In </td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" readonly='readonly' size="20" name="tgl_gate_in" id="tgl_gate_in" value="<?=date('d-m-Y H:i:s')?>" />
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
		<td><textarea type="text" readonly='readonly' size="20" name="remark" id="remark" value="" /></textarea>
		</td>
     </tr>
	  <tr>
		<td class="form-field-caption" align="right">Lokasi Placement</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="15" name="placement" id="placement" readonly="readonly" />
		</td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right"> </td>
		<td class="form-field-caption" align="right"></td>
		<td>		</td>
     </tr>
	</table>
	
	</form>
	<br />
	<hr width="870" color="#e1e0de"></hr>
	
	<br/>
	<table id='list_cont_js' width="100%"></table> <div id='pg_l_cont'></div>
	<br/>
	
	
</div>


