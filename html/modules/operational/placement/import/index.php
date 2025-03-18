<!DOCTYPE html>
<style>
.content{
	width:95%;
	margin-left:auto;
	margin-right:auto;
	margin-bottom: 10px;
	margin-top:20px;
}
</style>
<script type="text/javascript">

	var my_cont;
	var my_blok;
	var my_slot;
	var my_row;
	var my_tier;
	var glob_ukk;
	var i= 0;
	
	function preview_blok(block_id,slot_id,row_id,tier_id,id_jobslip)
	{
		var e = document.getElementById("id_alat");
		var id_alat = e.options[e.selectedIndex].value;
		var id_user = document.getElementById('nama_yoa').value;
		alert(id_alat+' '+id_user+' '+id_jobslip);
		$('#load_preview').html('<table width="100%" height"100%"><tr><td bgcolor="white"><p align="center"><img src="<?=HOME?>images/loading_gud.gif" width="55%" height="90%"/></p></td></tr></table>');
		$('#load_preview').load("<?=HOME?>operational.placement.export.sticky_layout/load_row_slot?block_id="+block_id+"&slot_id="+slot_id+"&row_id="+row_id+"&tier_id="+tier_id+"&yard_id=23");
		var url				= "<?=HOME?>operational.placement.export.plc/placement";
		$.post(url,{IDJ : id_jobslip,ID_ALAT: id_alat, ID_USER: id_user},function(data) 
				  {
					alert(data);
		      		console.log(data);
			      });	
		$('#load_table_cont').load("<?=HOME?>operational.placement.import.table/find_cont?no_cont="+my_cont);
		$('#load_preview2').html('<table width="100%" height"100%"><tr><td bgcolor="white"><p align="center"><img src="<?=HOME?>images/loading_gud.gif" width="55%" height="90%"/></p></td></tr></table>');
		$('#load_preview2').load("<?=HOME?>maintenance.monitoring.sticky_layout/load_row_slot?block_id="+block_id+"&slot_id="+slot_id+"&row_id="+row_id+"&yard_id=23");
		
	}
	
	function koreksi_placement(block_id,slot_id,cont_id,v_ukk )
	{
		var e = document.getElementById("id_alat");
		var id_alat = e.options[e.selectedIndex].value;
		var id_user = document.getElementById('nama_yoa').value;
		$('#dialog_table_cont').html('<table width="100%" height"100%"><tr><td bgcolor="white"><p align="center"><img src="<?=HOME?>images/loading_gud.gif" width="55%" height="90%"/></p></td></tr></table>');
		$('#dialog_table_cont').load("<?=HOME?>operational.placement.import.correction/koreksi?block_id="+block_id+"&slot_id="+slot_id+"&&yard_id=23&no_cont="+cont_id+"&ukk="+v_ukk).dialog({modal:true, height:600,width:600, buttons: { "close": function() 
			{ 
				$(this).dialog("close"); 
				document.location.reload(true);
			} }});
	}
	$(document).ready(function() 
	{
		
		
        $( "#NO_CONTAINER" ).autocomplete({
			minLength: 3,
			source: "operational.placement.import.auto/parameter",
			focus: function( event, ui ) 
			{
				$( "#NO_CONTAINER" ).val( ui.item.NO_CONTAINER);
				return false;
			},
			select: function( event, ui ) 
			{
				$( "#NO_CONTAINER" ).val( ui.item.NO_CONTAINER);
				my_cont=ui.item.NO_CONTAINER;
				my_blok=ui.item.ID_BLOCK;
				my_slot=ui.item.SLOT_;
				my_row=ui.item.ROW_;
				my_tier=ui.item.TIER_;
				glob_ukk=ui.item.NO_UKK;
				return false;
			}
		})
		.data( "autocomplete" )._renderItem = function( ul, item ) 
		{
			return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + item.NO_CONTAINER + " <br> " + item.SIZE_ + " / "+item.TYPE_+" / "+item.STATUS_+" / "+item.BERAT+"<br>"+item.VESSEL+" / "+item.VOYAGE+" </a>")
			.appendTo( ul );
		
		};
		$('#load_table_cont').load("<?=HOME?>operational.placement.import.table/find_cont?no_cont="+my_cont+"&vukk="+glob_ukk);
	});
	
	function find_cont()
	{
		alert(my_cont+' , '+i);
		i=i+1;
		$('#load_table_cont').load("<?=HOME?>operational.placement.import.table/find_cont?no_cont="+my_cont+"&vukk="+glob_ukk+"&block_id="+my_blok+"&slot_id="+my_slot);
		$('#load_preview').html('<table width="100%" height"100%"><tr><td bgcolor="white"><p align="center"><img src="<?=HOME?>images/loading_gud.gif" width="55%" height="90%"/></p></td></tr></table>');
		$('#load_preview').load("<?=HOME?>operational.placement.export.sticky_layout/load_row_slot?block_id="+my_blok+"&slot_id="+my_slot+"&row_id="+my_row+"&tier_id="+my_tier+"&yard_id=23");
		$('#load_preview2').html('<table width="100%" height"100%"><tr><td bgcolor="white"><p align="center"><img src="<?=HOME?>images/loading_gud.gif" width="55%" height="90%"/></p></td></tr></table>');
		$('#load_preview2').load("<?=HOME?>maintenance.monitoring.sticky_layout/load_row_slot?block_id="+my_blok+"&slot_id="+my_slot+"&row_id="+my_row+"&yard_id=23");
	}
</script>
<html lang="en">
	<body>

	<div class="content">
	<span class="graybrown"><img src='images/cont_placement.png' align="absmiddle" width="80" border='0' class="icon"/>
		<font color="#DE7E21"> Form Multi Placement After Discharge </font><font color="#0066CC"> [ IMPORT ]</font></span>
	<br>
	<br>
	<HR color="#dedede" width="870px" align="center"></hr>
	<br>
	
	<form id="dataForm" action="" method="">
		<table  cellspacing='2' cellpadding='2' border='0' width="100%" style="margin-top:10px;" valign="top">
			<tr>
				<td class="form-field-caption" valign="middle" align="right" width="125">NO CONTAINER</td>
				<td valign="middle" class="form-field-input">: <input style="font-size:26px; font-weight:bold;" id="NO_CONTAINER" name="NO_CONTAINER" size="15" title="entry" class="suggestuwriter" type="text" maxlength="16" value="" /> &nbsp; <input type="button" value="FIND" onclick="find_cont()" style="height:35px; width:50px;"></TD>
			</tr>
			
			<tr>
				<td class="form-field-caption" valign="middle" align="right" width="125">Alat yang digunakan</td>
				<td valign="middle" class="form-field-input">: 
				<?
					$db=getDb();
					$query_alat	="SELECT ID_ALAT, NAMA_ALAT FROM MASTER_ALAT WHERE PENEMPATAN='YARD'";
					
					$result_alat= $db->query($query_alat);
					$row_alat	= $result_alat->getAll();
				?>
				
				<select id="id_alat" name="id_alat">
				<?	
					foreach ($row_alat as $r_alt)
					{
						$id_alt=$r_alt['ID_ALAT'];
						$name_alt=$r_alt['NAMA_ALAT'];
						echo "<option value=".$id_alt.">".$name_alt."</option>";
					}
				?>
						
				</select>
				</td>
			</tr>
			<tr>
				<td class="form-field-caption" valign="middle" align="right" width="125">Y.O.A</td>
				<td valign="middle" class="form-field-input">: <input  id="nama_yoa" name="nama_yoa" size="20" title="entry" class="suggestuwriter" type="text" maxlength="16" /></TD>
			</tr>
			<tr>
				<td class="form-field-caption" valign="middle" align="right" width="125">Date </td>
				<td valign="middle" class="form-field-input">: <input  id="date_" name="date_" size="20" title="entry" class="suggestuwriter" type="text" maxlength="16" value="<?=date('d-m-Y   H:i')?>" /></TD>
			</tr>
			
		</table>
		</fieldset>
    <br>
	<div id="load_table_cont"></div>
	<BR><BR>
	<table>
	<tr>
	<td>
	
	<fieldset style="border: 1px solid #94969b;-moz-border-radius:5px;width:870px;" id="bwh_layout">
			<legend align="center"><font color='#0482d5'>Preview Blok Existing</font></legend>
			<br>
			<div id='load_preview2' align="center"></div>
			<BR>
			&nbsp;
			<BR>
			&nbsp;			
	</fieldset>
	</td>
	</tr>
	</table>
	
	<div id="dialog_table_cont"></div>
	</div>
</body>
</html>