<script type="text/javascript">
function ReloadPage() { 
	location.reload();
}
$(document).ready(function() {
	//setTimeout("ReloadPage()", 180000);
	
	$( "#nama_pelanggan" ).autocomplete({
		minLength: 3,
		source: "planning.ppn_list.all.auto/pelanggan",
		focus: function( event, ui ) 
		{
			$( "#nama_pelanggan" ).val( ui.item.NAMA_PERUSAHAAN);
			return false;
		},
		select: function( event, ui ) 
		{
			$( "#nama_pelanggan" ).val( ui.item.NAMA_PERUSAHAAN);
			$( "#kd_pelanggan" ).val( ui.item.KD_PELANGGAN);
			$( "#npwp" ).val( ui.item.NO_NPWP);
			$( "#alamat" ).html( ui.item.ALAMAT_PERUSAHAAN);
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a align='center'>" + item.NAMA_PERUSAHAAN + " (" +item.KD_PELANGGAN+") <br>" 
		+ item.NO_NPWP+" <br>"
		+ item.ALAMAT_PERUSAHAAN +"</a>")
		.appendTo( ul );
	
	};
	
});

jQuery(function() {
 jQuery("#l_trans").jqGrid({
	url:'<?=HOME?>planning.ppn_list.all.ajax/data',
	mtype : "post",
	datatype: "json",
	colNames:['Action','Kode Pelanggan','Nama Pelanggan','NPWP','Alamat','Remark'], 
	colModel:[
		{name:'act', width:120, align:"center", search:false},
		{name:'kode', width:100, align:"center"},
		{name:'nama', width:250, align:"left"},
		{name:'npwp', width:150, align:"left"},
		{name:'alamat',width:300, align:"left"},
		{name:'remark',width:300, align:"left"}
	],
	rowNum:20,
	width: 778,
	height: "100%",
	ignoreCase:true,
	//250

	rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_l_trans',
	viewrecords: true,
	shrinkToFit: false,
	caption:"List"
 });
   jQuery("#l_trans").jqGrid('navGrid','#pg_l_trans',{del:false,add:false,edit:false,search:false});
 
 jQuery("#l_trans").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : true,defaultSearch: 'cn'});
 
});

function allocate_ppn(){
	var kd_pelanggan=$('#kd_pelanggan').val();	
	var user=$('#user').val();	
	var remark = $('#remark').val();
	
	if (kd_pelanggan=="") {
		alert ("Pilih Pelanggan secara auto complete");
		return false;
	}
	
	var con = confirm("Apakah anda yakin ingin menyimpan data dengan kode pelanggan " + kd_pelanggan);
	if(con){
		var url="<?=HOME;?>planning.ppn_list.all.ajax/save";
		
		$.post(url,{kd_pelanggan:kd_pelanggan,remark:remark,user:user},function(data){	
			var explode = data.split('||');
			var v_msg = explode[0];
			var v_result = explode[1];
			if (v_msg!='OK')
			{
				alert(v_result);
				return false;
			}
			else
			{
				alert (v_result);	
				$('input').val('');
				$('textarea').html('');
				$('textarea').val('');
				jQuery("#l_trans").jqGrid('setGridParam',{datatype: 'json'}).trigger('reloadGrid');	
			}
		});	
	}
}

function del(i)
{
	var url="<?=HOME;?>planning.ppn_list.all.ajax/delete";
	
	var question=confirm("Apakah anda yakin menghapus kd pelanggan "+i);
	if (question) {
		$.post(url,{kd_pelanggan:i},function(data){	
			alert (data);
			jQuery("#l_trans").jqGrid('setGridParam',{datatype: 'json'}).trigger('reloadGrid');
		});
	} else {
		return false;
	}
	
}

</script>

<div class="content">
	<div class="main_side">
	<br/><br/>
	<p align = "center">
		<font color='#888b8d' size='5px'>LIST PELANGGAN BEBAS PPN</font>
	</p>
	<br/><br/>
	<p  align = "center">
		<table>
			<tr>
				<td style="text-align:right">
					PELANGGAN : 
				</td>
				<td>
					<input type="text" id='nama_pelanggan' name="nama_pelanggan" size="40" placeholder='Autocomplete' />
				</td>
			</tr>
			<tr>
				<td style="text-align:right">
					KODE PELANGGAN : 
				</td>
				<td>
					<input type="text" id='kd_pelanggan' readonly name="kd_pelanggan" size="40"/>
				</td>
			</tr>
			<tr>
				<td style="text-align:right">
					NPWP : 
				</td>
				<td>
					<input type="text" id='npwp' readonly name="npwp" size="40"/>
				</td>
			</tr>
			<tr>
				<td style="vertical-align: top; text-align:right">
					ALAMAT : 
				</td>
				<td>
					<textarea type="text" id='alamat' name="alamat" disabled size="40" style="width: 260px; height: 84px;"></textarea>
				</td>
			</tr>
			<tr>
				<td style="vertical-align: top; text-align:right">
					REMARK : 
				</td>
				<td>
					<textarea type="text" id='remark' name="remark" size="40" style="width: 260px; height: 84px;"></textarea>
				</td>
			</tr>
		</table>
		
		<input type="hidden" id='user' name="user" value="<?=$_SESSION['PENGGUNA_ID'];?>"/>
		<br/><br/>
		<button onclick="allocate_ppn()" > T A M B A H </button>
	</p>
	<br/>
	<p  align = "center">
	<table id='l_trans' width="100%"></table> 
	<div id='pg_l_trans'></div>	
	</p>
	<br/>
	<br/>
	</div>
</div>