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

	var my_cont = new Array();
	var i= 0;
		
	$(document).ready(function() 
	{		
        $( "#NO_CONTAINER" ).autocomplete({
			minLength: 3,
			source: "operational.relokasi.export.auto/parameter",
			focus: function( event, ui ) 
			{
				$( "#NO_CONTAINER" ).val( ui.item.NO_CONTAINER);
				return false;
			},
			select: function( event, ui ) 
			{
				$( "#NO_CONTAINER" ).val( ui.item.NO_CONTAINER);
				my_cont[i]="'"+ui.item.NO_CONTAINER+"'";
				return false;
			}
		})
		.data( "autocomplete" )._renderItem = function( ul, item ) 
		{
			return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + item.NO_CONTAINER + "<br>" + item.SIZE_ + " / "+item.TYPE_CONT+" / "+item.STATUS_CONT+"<br>"+item.VESSEL+" "+item.VOYAGE+"<br><b>POD</b> : "+item.POD+" <b>GROSS</b> : "+item.TON+"</a>")
			.appendTo( ul );
		
		};
		$('#load_table_cont').load("<?=HOME?>operational.relokasi.export.table/find_cont?no_cont="+my_cont);
	});
	
	function find_cont()
	{
		alert(my_cont+' , '+i);
		i=i+1;
		$('#load_table_cont').load("<?=HOME?>operational.relokasi.export.table/find_cont?no_cont="+my_cont);
	}
	
	function relocate(no_cont,sz,id_block,b,s,r,t)
	{
		var alat_	= document.getElementById("id_alat").value;
		var yoa_	= document.getElementById("nama_yoa").value;
		
		if(alat_ == "")
		{
			alert("Alat harus diisi");
			return false;
		}
		else if(yoa_=="")
		{
			alert("YOA harus diisi");
			return false;
		}
		else
		{
			myWindow=window.open('<?=HOME?>operational.relokasi.export.plc/relocate_cont?no_container='+no_cont+'&size_cont='+sz+'&id_block='+id_block+'&block='+b+'&slot='+s+'&row='+r+'&tier='+t,'','width=1200,height=700,scrollbars=yes,resizable=no');
			myWindow.focus();
		}		
	}
	
</script>
<html lang="en">
	<body>

	<div class="content">
	<span class="graybrown"><img src='images/cont_placement.png' align="absmiddle" width="80" border='0' class="icon"/>
		<font color="#DE7E21"> Form Re-location After Placement</font><font color="#0066CC"> [ EXPORT ]</font></span>
	<br>
	<br>
	<HR color="#dedede" width="870px" align="center"></hr>
	<br>
	
	<form id="dataForm" action="" method="">
		<table  cellspacing='2' cellpadding='2' border='0' width="100%" style="margin-top:10px;" valign="top">
			<tr>
				<td class="form-field-caption" valign="middle" align="right" width="125">NO CONTAINER</td>
				<td valign="middle" class="form-field-input">: <input style="font-size:26px; font-weight:bold;" id="NO_CONTAINER" name="NO_CONTAINER" size="15" title="entry" class="suggestuwriter" type="text" maxlength="16" value="" /> &nbsp; <input type="button" value="FIND" onclick="find_cont()" style="height:20px; width:50px; vertical-align:middle;"></TD>
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
				<td valign="middle" class="form-field-input">: <input  id="date_" name="date_" size="20" title="entry" class="suggestuwriter" type="text" maxlength="16" value="<?=date('d-m-Y H:i')?>" readonly="readonly" /></TD>
			</tr>			
		</table>
		</fieldset>
    <br>
	<div id="load_table_cont"></div>
	<BR><BR>	
	<div id="dialog_table_cont"></div>
	</div>
</body>
</html>