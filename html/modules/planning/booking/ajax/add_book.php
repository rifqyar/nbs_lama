<?PHP
	$id_vs=$_GET['id_vessel'];
?>

<script>
function set_teus()
{
	var i=$("#box").val();
	var x;
	var sz=$("#size").val();
	if (sz==20)
	{
		x=i;
	}
	else if (sz==40)
	{
		x=i*2;
	}
	else if (sz==45)
	{
		x=i*2;
	}
	$("#teus").val(x);
}

</script>
<div align='left'>
<p>
	<img src="<?=HOME?>images/add_book.png" style="vertical-align:middle"> 
	<b> <font color='#69b3e2' size='4px'>Add</font> </b>
	 <font color='#888b8d' size='4px'>
	 Booking Stack 
	 </font>
	</p>
</div>
<br>
<br>
<table>
	<tr>
		<td>Size - Type - Status</td>
		<td>:</td>
		<td>
			<select name="size" id="size" >
                  <option value="">--Pilih--</option>
				  <option value="20">20"</option>
				  <option value="40">40"</option>
				  <option value="45">45"</option>
            </select>
			&nbsp;
			<select name="type" id="type" >
                  <option value=""> -- Pilih -- </option>
				  <option value="DRY"> DRY </option>
				  <option value="FLT"> FLT </option>
				  <option value="OT"> OT </option>
				  <option value="OVD"> OVD </option>
				  <option value="RFR"> RFR </option>
				  <option value="TNK"> TNK </option>
            </select>
			&nbsp;
			<select name="status" id="status" >
                  <option value=""> -- Pilih -- </option>
				  <option value="FCL"> FCL </option>
				  <option value="MTY"> MTY </option>
				  <option value="LCL"> LCL </option>
				  <option value="UC"> UC </option>
            </select>
		</td>
	</tr>
	<tr>
		<td>Total Box</td>
		<td>:</td>
		<td><input type="text" name="box" id="box" size="5" onblur="set_teus()"/>&nbsp;Box</td>
	</tr>
	<tr>
		<td>Total Teus</td>
		<td>:</td>
		<td><input type="text" name="teus" id="teus" size="5" onclick="set_teus()" readonly="readonly"/>&nbsp;Teus</td>
	</tr>
	<tr>
		<td>POD</td>
		<td>:</td>
		<td><input type="text" name="pelabuhan" id="pelabuhan" size="20"/>&nbsp;<input type="text" name="id_pelabuhan" id="id_pelabuhan" size="5" readonly="readonly"/>&nbsp;<input type="button" name="add_booking" value="Add Booking" onclick="add_booking()" /></td>
	</tr>
</table>

<div id="loadx"></div>
</div>


<script type='text/javascript'>
$(document).ready(function() 
{	
	$('#loadx').html('<img src="<?=HOME?>images/loadingF.gif" />');
	$('#loadx').load("<?=HOME?>planning.booking.ajax/detail_booking?id=<?=$id_vs?>");
		
    $( "#pelabuhan" ).autocomplete({
		minLength: 3,
		source: "<?=HOME?>planning.booking.auto/pelabuhan",
		focus: function( event, ui ) {
			$( "#pelabuhan" ).val( ui.item.PELABUHAN);
			return false;
		},
		select: function( event, ui ) {
			$( "#pelabuhan" ).val( ui.item.PELABUHAN);
            $( "#id_pelabuhan" ).val( ui.item.ID_PEL);
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + item.PELABUHAN + " | " + item.ID_PEL + "</a>")
			.appendTo( ul );
	};  
});

function edit($size, $type, $status, $box, $teus, $id_pel, $pel_tuj){
	    $('#size').val('{$size}');
		$('#id_vs').val('{$id_vs}');
		$('#box').val('{$box}');
		$('#teus').val('{$teus}');
		$('#type').val('{$status}');
		$('#id_pelabuhan').val('{$id_pelabuhan}');
		$('#status').val('{$status}');
		$('#pelabuhan').val('{$pelabuhan}');
}

function add_booking(){
	    var size_ 		= $("#size").val();
		var id_vs_		= '<?=$id_vs?>';	
		var box_		= $("#box").val();
		var type_ 		= $("#type").val();
		var teus_ 		= $("#teus").val();	
		var status_		= $("#status").val();
		var pelabuhan_	= $("#pelabuhan").val();
		var id_pelabuhan_	= $("#id_pelabuhan").val();
		var url 	= "<?=HOME?>planning.booking.ajax/save_booking";
	 
		$.post(url,{size :size_, id_vs : id_vs_,box:box_, type :type_,teus : teus_,status :status_, pelabuhan:pelabuhan_, id_pelabuhan : id_pelabuhan_}, function(data){
		$('#loadx').html('<img src="<?=HOME?>images/loadingF.gif" />');
		$('#loadx').load("<?=HOME?>planning.booking.ajax/detail_booking?id=<?=$id_vs?>");
		});
}

function delete($id_book){
	    var id_book_ 		= $id_book;
		var url 			= "<?=HOME?><?=APPID?>.ajax/delete #list";
	 
		$("#loadx").load(url,{id_book :id_book_}, function(data){
		$('#loadx').load("<?=HOME?>planning.booking/detail_booking?id=<?=$id_vs?> #list");
		});
}
</script>