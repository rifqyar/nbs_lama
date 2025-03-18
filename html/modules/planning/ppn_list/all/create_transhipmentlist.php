<?php
	$db = getDB(ibis);	
	$id_trans=$_GET['id'];

	$query 	= "select VESSEL,CALL_SIGN, VOYAGE_IN, VOYAGE_OUT, TO_CHAR(TO_DATE(ETA,'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:Mi') ETA, ID_VSB_VOYAGE
	from TRANS_CODECO_LIST_H WHERE trim(ID_TRANS) = trim('$id_trans')";
	$res = $db->query($query)->fetchRow();
?>
<script type="text/javascript">
jQuery(function() {
 jQuery("#l_trans_det").jqGrid({
	url:'<?=HOME?>datanya/data_trans_codeco_list_detail?r=<?=$id_trans;?>',
	mtype : "post",
	datatype: "json",
	colNames:['','No Container'], 
	colModel:[
		{name:'act', width:85, align:"center", search:false},
		{name:'container', width:150, align:"center"}
	],
	rowNum:20,
	width: 280,
	height: "100%",
	ignoreCase:true,
	//250

	rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_l_trans_det',
	viewrecords: true,
	shrinkToFit: false,
	caption:"Data Container"
 });
   jQuery("#l_trans_det").jqGrid('navGrid','#pg_l_trans_det',{del:false,add:false,edit:false,search:false});
 
 jQuery("#l_trans_det").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : true,defaultSearch: 'cn'});
 
});

function add_cont1(i)
{
	var nc=$('#nc').val();
	var voy_out = "<?=$res['VOYAGE_OUT'];?>";
	var voy_in = "<?=$res['VOYAGE_IN'];?>";
	var id_vsb = "<?=$res['ID_VSB_VOYAGE'];?>";
	var call_sign = "<?=$res['CALL_SIGN'];?>";
	var vessel = "<?=$res['VESSEL'];?>";
	
	var url="<?=HOME;?>planning.transhipment_list.transhipment.ajax/add_det_trans_list";
	if(nc=='')
	{
		alert('Entry container number please');
		return false;
	}
	else 
	{
		$.post(url,{NC: nc, ID_TRANS:i, VOYAGE_OUT: voy_out, ID_VSB_VOYAGE:id_vsb, VESSEL:vessel, VOYAGE_IN:voy_in,CALL_SIGN:call_sign},function(data){	
			if (data.substring(0,5) =='EXIST'){
				alert(data);
			} else {
				alert(data);
				$('#l_trans_det').setGridParam({ page: 1, datatype: "json" }).trigger('reloadGrid');
				$('#nc').val('');
                $('#nc').focus()
				//$('#terminal').val('');
			}
		});
	}
}

function del(j,i)
{
	var k = "<?=$res['ID_VSB_VOYAGE'];?>";
	var url="<?=HOME;?>planning.transhipment_list.transhipment.ajax/del_cont_trans";
	$.post(url,{ID_TRANS:i, CONT:j},function(data){	
		alert(data);
        jQuery("#l_trans_det").jqGrid('setGridParam',{datatype: 'json'}).trigger('reloadGrid');
	});
}

</script>
<div class="content">
	<p>
	<img src="<?=HOME?>images/delivery.png" height="5%" width="5%" style="vertical-align:middle"> <b> <font color='#69b3e2' size='4px'>Create</font> </b>
	 <font color='#888b8d' size='4px'>
	 Transhipment List
	 </font>
	
	<p><br/>
	  </p>
	
	<hr width="870" color="#e1e0de"></hr><p><br/></p>
	<table>
	<tr>
		<td class="form-field-caption" align="right">Vessel</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="41" name="vessel" id="vessel" value="<?=$res['VESSEL'];?>" readonly="readonly"/>
		<input type="hidden" size="41" name="id_vsb_voyage" id="id_vsb_voyage" value="<?=$res['ID_VSB_VOYAGE'];?>" readonly="readonly"/>
		<input type="hidden" size="41" name="call_sign" id="call_sign" value="<?=$res['CALL_SIGN'];?>" readonly="readonly"/>
		</td>
		<td colspan="4"></td>
		
	</tr>
	<tr>
		<td class="form-field-caption" align="right">Voyage</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="18" name="voyage_in" id="voyage_in" value="<?=$res['VOYAGE_IN'];?>" readonly="readonly" />	
		<input type="text" size="18" name="voyage_out" id="voyage_out" value="<?=$res['VOYAGE_OUT'];?>" readonly="readonly" />
		</td>
		<td width="100">&nbsp;</td>
		<td class="form-field-caption" align="right">Tgl Kedatangan</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="20" name="ata" id="ata" value="<?=$res['ETA'];?>" readonly="readonly" /></td>
	</tr>
	</table>
	</form>
	<br>
	<hr width="870" color="#e1e0de"></hr>
	<br>
	<div>
		<table>
			<tr>
				<td class="form-field-caption" align="right">No. Container</td>
				<td class="form-field-caption" align="right">:</td>
				<td colspan="11" valign="middle">
				<input type="text" size="11" id="nc" name="nc" style="background-color:#FFFFCC; font-weight:bold;font-size:24px;text-align:center"/> 
				<button onclick="add_cont1('<?=$id_trans;?>')"><img src="<?=HOME;?>images/add_ct.png"/></button>
				</td>
			</tr>
				<tr>
		<td class="form-field-caption" colspan="3" valign="middle"><b>Upload File Excel Container Transhipment Export : </b> </td>
		<td colspan="3" valign="middle">
                    
                    <form method="post" enctype="multipart/form-data" action="<?=HOME?>planning.transhipment_list.transhipment.ajax/upload_file_trans?r=<?=$id_trans;?>">  
                    <input name="userfile" type="file">                        
                        <input name="upload" type="submit" value="import">                        
                    </form>
                        
                        
                        
		</td>
                <td colspan="3" valign="middle"><a href="./uploads/Template_Upload_Transhipment_Export.xls" target="_blank"><font color="red"><i><b>Download Template File</b></i></font></a></td>
		
	</tr>	
			<tr>
				<td colspan="13">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="13">&nbsp;</td>
			</tr>
		</table>
		<br/>
		<p  align = "center">
			<table id='l_trans_det' width="100%"></table> 
			<div id='pg_l_trans_det'></div>	
		</p>
		<br/>
		<br/>
	</div>
	<br/>
</div>