<?php
	$db = getDb("dbint");

	$id_vsb = $_GET["id"];
	$query_ = "select RBM_DLD from m_vsb_voyage where ID_VSB_VOYAGE = '$id_vsb'";
	$rwq = $db->query($query_)->fetchRow();

	$opt = "<select id='status'>";
	if ($rwq[RBM_DLD] == 1) {
	$opt	.="<option value='0'>DOWNLOAD RBM</option>";
	}
	else if ($rwq[RBM_DLD] == 2) {
	$opt	.="<option value='0'>DOWNLOAD RBM</option>";
	$opt 	.="<option value='1'>GROUPING RBM</option>";
	}		
	$opt .="</select>";
?>
<script type="text/javascript">
	function save_cancel(){
		var status = $("#status").val();
		$.post("<?=HOME?>billing.rbm.ajax/save_cancel",{ID_VSB:'<?=$id_vsb?>',STATUS:status},function(data){
			if (data=="Y") {
				alert('Sukses Simpan');
			}
			else {
				alert(data);
			}
			$("#cancel_final").dialog("close");
			jQuery("#booking").jqGrid('setGridParam',{datatype: 'json'}).trigger('reloadGrid');

		});
	}
</script>
<div>
	<table border="0px" class="adad">
		<tr>
			<td>RUBAH STATUS KE</td>
			<td>:</td>
			<td><?=$opt?></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td><button onclick="save_cancel()">SAVE</button></td>
		</tr>
	</table>
</div>