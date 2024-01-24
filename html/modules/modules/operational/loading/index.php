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
			source: "operational.loading.auto/parameter",
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
		$('#load_table_cont').load("<?=HOME?>operational.loading.table/find_cont?no_cont="+my_cont);
	});
	
	function find_cont()
	{
		alert(my_cont+' , '+i);
		i=i+1;
		$('#load_table_cont').load("<?=HOME?>operational.loading.table/find_cont?no_cont="+my_cont);
		$('#load_preview2').html('<table width="100%" height"100%"><tr><td bgcolor="white"><p align="center"><img src="<?=HOME?>images/loading_gud.gif" width="55%" height="90%"/></p></td></tr></table>');
		$('#load_preview2').load("<?=HOME?>operational.loading.sticky_layout/load_ship_pss?no_cont="+my_cont);
	}
		
	function plc_bay(no_ukk,nocont)
	{
		var e = document.getElementById("id_alat");
		var id_alat = e.options[e.selectedIndex].value;
		var soa_ = document.getElementById("nama_soa").value;
		//alert(id_alat);
		
		if((id_alat == "")||(soa_ == "")||(no_ukk == "")||(nocont == ""))
		{
			alert("Data Not Complete...");
			return false;
		}
		else
		{
			var r = confirm("Are you sure?");
			if(r == true)
			{
				$('#dialog_table_cont').html('<table width="100%" height"100%"><tr><td bgcolor="white"><p align="center"><img src="<?=HOME?>images/loading_gud.gif" width="55%" height="90%"/></p></td></tr></table>');
				$('#dialog_table_cont').load("<?=HOME?>operational.loading.plc/koreksi?no_ukk="+no_ukk+"&no_cont="+nocont+"&alat="+id_alat+"&soa="+soa_).dialog({modal:true, height:700,width:800, title : "Loading Confirm"});				
			}
			else
			{
				return false;
			}
		}		
	}
	
	function loading_confirm(no_ukk,nocont)
	{
		//alert(no_ukk);
		var alat_	= document.getElementById("id_alat").value;
		var alat_op	= document.getElementById("op_alat").value;
		var ht_	    = document.getElementById("id_ht").value;
		var ht_op	= document.getElementById("op_ht").value;
		var soa_	= document.getElementById("nama_soa").value;
		var ukk_    = no_ukk;
		var no_cont = nocont;
		
		if((alat_ == "")||(soa_ == "")||(ukk_ == "")||(no_cont == "")||(alat_op == "")||(ht_ == "")||(ht_op == ""))
		{
			alert("Data Not Complete...");
			return false;
		}
		else
		{
			var r = confirm("Are you sure?");
			if(r == true)
			{
				var url	= "<?=HOME?>operational.loading.plc/confirm_load";	
				$.post(url,{NO_UKK : ukk_, NO_CONTAINER : no_cont, ALAT : alat_, OP_ALAT : alat_op, HT : ht_, OP_HT : ht_op, SOA : soa_},function(data){
					console.log(data);
					if(data == "OK")
					{
						alert("Success");
						window.location = "<?=HOME?>operational.loading/";  	
					}
					else
					{
						alert("Failed");
						window.location = "<?=HOME?>operational.loading/";
					}
				});
			}
			else
			{
				return false;
			}
		}		
	}
	
	function load_cont(id_cell,no_cont)
	{
		alert(no_cont);
		var alat_	= document.getElementById("id_alat").value;
		var yoa_	= document.getElementById("nama_yoa").value;
		
	}
	
</script>
<html lang="en">
	<body>

	<div class="content">
	<span class="graybrown"><img src='images/stevedoring.gif' align="absmiddle" width="80" border='0' class="icon"/>
		<font color="#DE7E21"> Form Loading Confirm</font></span>
	<br>
	<br>
	<hr color="#dedede" width="870px" align="center"></hr>
	<br>
	
		<table  cellspacing='2' cellpadding='2' border='0' width="100%" style="margin-top:10px;" valign="top">
			<tr>
				<td class="form-field-caption" valign="middle" align="right" width="125">NO CONTAINER</td>
				<td valign="middle" class="form-field-input">: <input style="font-size:26px; font-weight:bold;" id="NO_CONTAINER" name="NO_CONTAINER" size="17" title="entry" class="suggestuwriter" type="text" maxlength="16" value="" /> &nbsp; <input type="button" value="FIND" onclick="find_cont()" style="height:30px; width:50px; vertical-align:middle;"></TD>
			</tr>
			<tr>
				<td class="form-field-caption" valign="middle" align="right" width="125">Alat yang digunakan</td>
				<td valign="middle" class="form-field-input">: 
				<?
					$db=getDb();
					$query_alat	="SELECT ID_ALAT, NAMA_ALAT FROM MASTER_ALAT WHERE PENEMPATAN='BAY'";
					
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
						
				</select>&nbsp;&nbsp;
				<select id="op_alat" name="op_alat">
					<option value="OP.OJA">OP.OJA</option>
					<option value="OP.TSJ">OP.TSJ</option>
				</select>
				</td>
			</tr>
			<tr>
				<td class="form-field-caption" valign="middle" align="right" width="125">HT Number</td>
				<td valign="middle" class="form-field-input">: 
				<?
					$db=getDb();
					$query_alat	="SELECT ID_ALAT, NAMA_ALAT FROM MASTER_ALAT WHERE PENEMPATAN='YARD' AND ID_ALAT LIKE 'HT%'";
					
					$result_alat= $db->query($query_alat);
					$row_alat	= $result_alat->getAll();
				?>
				
				<select id="id_ht" name="id_ht">
				<?	
					foreach ($row_alat as $r_alt)
					{
						$id_alt=$r_alt['ID_ALAT'];
						$name_alt=$r_alt['NAMA_ALAT'];
						echo "<option value=".$id_alt.">".$name_alt."</option>";
					}
				?>
						
				</select>&nbsp;&nbsp;
				<select id="op_ht" name="op_ht">
					<option value="OP.OJA">OP.OJA</option>
					<option value="OP.TSJ">OP.TSJ</option>
				</select>
				</td>
			</tr>
			<tr>
				<td class="form-field-caption" valign="middle" align="right" width="125">S.O.A</td>
				<td valign="middle" class="form-field-input">: <input  id="nama_soa" name="nama_soa" size="20" title="entry" class="suggestuwriter" type="text" maxlength="16" /></TD>
			</tr>
			<tr>
				<td class="form-field-caption" valign="middle" align="right" width="125">Date </td>
				<td valign="middle" class="form-field-input">: <input  id="date_" name="date_" size="20" title="entry" class="suggestuwriter" type="text" maxlength="16" value="<?=date('d-m-Y H:i')?>" readonly="readonly" /></TD>
			</tr>			
		</table>
    <br>
	<div id="load_table_cont"></div>
	<br><br>
	<table>
	<tr>
	<td>
	<fieldset style="border: 1px solid #94969b;-moz-border-radius:5px;width:870px;" id="bwh_layout">
			<legend align="center"><font color='#0482d5'>Preview Ship Position</font></legend>
			<br>
			<div id='load_preview2' align="center"></div>
			<br/>			
			&nbsp;
			<br/>
			&nbsp;			
	</fieldset>
	<table>
		<tr>
			<td width="20" style="background-color:#FFCC99;"></td><td>&nbsp;Planning</td>
			<td width="20">&nbsp;&nbsp;</td>
			<td width="20" style="background-color:#C2EBFF;"></td><td>&nbsp;Realization</td>
		</tr>
	</table>
	</td>
	</tr>
	</table>
	<div id="dialog_table_cont"></div>
	</div>
</body>
</html>