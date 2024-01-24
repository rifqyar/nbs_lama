<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
$backuri                      = str_replace('&_saveok=1', '', $_SERVER['REQUEST_URI']);
$_SESSION['__uriback'][APPID] = $backuri . (strpos($_SERVER['REQUEST_URI'], '?') === false ? '?' : '');

	$no_ukk=$_GET['id_vessel'];
	$db=getDb();
	$rb=$db->query("select NM_KAPAL, VOYAGE_IN, VOYAGE_OUT from RBM_H WHERE NO_UKK='$no_ukk'");
	$rt=$rb->fetchRow();
?>

<script type="text/javascript" src="<?=HOME?>js/jQuery.dualListBox-1.3.js"></script> 
<SCRIPT>$.configureBoxes();</SCRIPT>
<div>
	<input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="/wEPDwUKMTk5MjI0ODUwOWRky6CoCkIXJ8wm5xhTe3/fLC1fdlk=" />
	</div>

    <div>

	<TABLE>
		<TR>
			<td><b>Vessel - Voyage</b></td>
			<td><b>:</b></td>
			<td><b><?=$rt['NM_KAPAL']?></b></td>
		</TR>
		<TR>
			<td><b>UKK</b></td>
			<td><b>:</b></td>
			<td><b><?=$no_ukk?></b></td>
		</TR>
		<TR>
			
			<td></td>
		</TR>
		
	</TABLE>
	<br>
	<hr width="100%">
	<br>
	
	<form name="frm1" action="<?=HOME?>planning.baplie_import.ajax/create_vip_group">	
    <table >
		
        <tr >
            <td valign="bottom">
                Filter : <input type="text" id="box1Filter" /><button type="button" id="box1Clear">X</button><br /><br />
				<select id="box1View" multiple="multiple" style="height:150px;width:200px;">
				
					<?php
						
						
						$db = getDB();
						$get_cont		= "SELECT NO_CONTAINER FROM ISWS_LIST_CONTAINER WHERE NO_UKK='$no_ukk' and E_I='I'";
						$result_cont	= $db->query($get_cont);
						$row_cont		= $result_cont->getAll();
						foreach($row_cont as $row)
						{	
							$y_cont	= $row["NO_CONTAINER"];
							$y_list	.= "<option value='$y_cont' >$y_cont</option>";
						}
						echo $y_list;
					?>
					<span id="box1Counter" class="countLabel"></span><select id="box1Storage"></select>
                </select>
						<br/>
                         <span id="box1Counter" class="countLabel"></span>
                       <select style="display:none;" id="box1Storage">
                        </select>
                </td>
                <td align="center">
					<button id="to12" type="button"><img src="images/left.png"></button>
                    <button id="to2" type="button"><img src="images/left2.png"></button>
                </td>
                <td valign="bottom">
				Container VIP Group
				<br>
                    <select id="box2View" name="box2View" multiple="multiple" style="height:150px;width: 200px;"></select>
                    </select><br/>
                    <span id="box2Counter" class="countLabel"></span>
                    <select style="display:none;" id="box2Storage">
                </td>
            </tr>
			<tr>
		<td colspan='3' height='5'>
		</td>
		<tr>
		<td colspan='3' class="form-field-caption"  id='data1'><b>Grouping berdasarkan : </b>
	     <select id="group" name="group">
				  <option value=""> -- Pilih --</option>
				  <option value="peng"> Pengguna Jasa</option>
				  <option value="carr"> Carrier </option>
				</select> 
		</td>
		</tr>
		<tr>
			<td colspan="3" class="form-field-caption" id='data2'><b>Pemilik Barang : </b><input type="text" size="5" id="id_pengguna" name="id_pengguna" readonly='readonly'><input type="text" size="20" id="pengguna_jasa" name="pengguna_jasa" placeholder='AUTOCOMPLETE'> &nbsp; </td>
		</td>
		</tr>
		<tr>
			<td colspan="3" class="form-field-caption" id='data3'><b>Carrier : </b><input type="text" size="5" id="id_carrier" name="id_carrier" readonly='readonly'><input type="text" size="20" id="carrier" name="carrier"  placeholder='AUTOCOMPLETE'> &nbsp;</td>
		</td>
		</tr>
				
			
	
        </table>
		<input type="hidden" id="NO_UKK" name="NO_UKK" value="<? echo $no_ukk; ?>"  />
		<input type="submit">
		
		
		</FORM>
    </div>
	
	<script>
	
	
	
	
$(document).ready(function() {

	$( "#pengguna_jasa" ).autocomplete({
				minLength: 3,
				source: "planning.baplie_import.auto/pengguna_jasa",
				focus: function( event, ui ) {
					$( "#pengguna_jasa" ).val( ui.item.KODE_PELANGGAN);
					return false;
				},
				select: function( event, ui ) {
					$( "#pengguna_jasa" ).val( ui.item.NAMA_PELANGGAN);
					$( "#id_pengguna" ).val( ui.item.KODE_PELANGGAN);
					return false;
				}
			})
			.data( "autocomplete" )._renderItem = function( ul, item ) {
				return $( "<li></li>" )
					.data( "item.autocomplete", item )
					.append( "<a>" + item.NAMA_PELANGGAN + "<br />" + item.KODE_PELANGGAN + "</a>")
					.appendTo( ul );
			};
			
	$( "#carrier" ).autocomplete({
				minLength: 3,
				source: "planning.baplie_import.auto/carrier",
				focus: function( event, ui ) {
					$( "#carrier" ).val( ui.item.CODE);
					return false;
				},
				select: function( event, ui ) {
					$( "#carrier" ).val( ui.item.LINE_OPERATOR);
					$( "#id_carrier" ).val( ui.item.CODE);
					return false;
				}
			})
			.data( "autocomplete" )._renderItem = function( ul, item ) {
				return $( "<li></li>" )
					.data( "item.autocomplete", item )
					.append( "<a>" + item.CODE + "<br />" + item.LINE_OPERATOR + "</a>")
					.appendTo( ul );
			};
			
	$('#pengguna_jasa').hide();
	$('#carrier').hide();
	$('#id_pengguna').hide();
	$('#id_carrier').hide();
	$('#data1').show();
	$('#data2').hide();
	$('#data3').hide();
	
	$('#group').change(function(){
		if($('#group').val()=='peng'){	
			$('#pengguna_jasa').show();
			$('#carrier').hide();
			$('#data2').show();
			$('#data3').hide();
			$('#id_pengguna').show();
			$('#id_carrier').hide();
		} else {	
			$('#carrier').show();
			$('#pengguna_jasa').hide();
			$('#data2').hide();
			$('#data3').show();
			$('#id_pengguna').hide();
			$('#id_carrier').show();
		} 
	});
	
});
</script>