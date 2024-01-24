<style>
    h1{margin-bottom: 20px;}
    #pend{
        margin-bottom: 20px;
    }

</style>

<script type="text/javascript">
    
function export_excel()
{
    var noukk = $("#ukk").val();     
    window.open("<?=$HOME?>report.stackexport/detail_excel?noukk="+noukk);
   
	
}    

function tampilkan(){
    var noukk = $("#ukk").val();   
    
    if(noukk=='')
    {
        alert("masukan data kapal"); 
        return false;}
    else
    {
        $( "#tampilkan").load( "<?=$HOME?>report.stackexport/tampilkan?noukk="+noukk);
      
        
    }    
}


$(document).ready(function() 
{	
	//======================================= autocomplete vessel==========================================//
	
        
        $( "#vessel" ).autocomplete({
		minLength: 3,
		source: "report.stackexport.auto/vessel",
		focus: function( event, ui ) 
		{
			$( "#vessel" ).val( ui.item.VESSEL);
			return false;
		},
		select: function( event, ui ) 
		{
			$( "#vessel" ).val( ui.item.VESSEL);
			$( "#voyin" ).val( ui.item.VOYAGE_IN);
			$( "#voyo" ).val( ui.item.VOYAGE_OUT);
			$( "#ukk" ).val( ui.item.ID_VSB_VOYAGE);
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a align='center'>" + item.VESSEL + " " +item.VOYAGE_IN+" - "+ item.VOYAGE_OUT+" <br>"+ item.ID_VSB_VOYAGE +"</a>")
		.appendTo( ul );
	
	};
	//======================================= autocomplete vessel==========================================//
});



</script>

	<h2> <img src="<?=HOME?>images/delivery.png" height="5%" width="5%" style="vertical-align:middle"> Report Data Stacking Extension Export</h2>

	<table>
	 <tr height="15">
		<td class="form-field-caption" align="right">
        Vessel : <input type="text" size="25" id="vessel"/>/<input type="text" size="5" id="voyin"/> | <input type="text" size="5" id="voyo"/>
		
		<input type="hidden" size="5" id="ukk"/>
		</td>
	</tr>
	<tr height="15">
		<td>
			<button onclick="export_excel();">Export To Excel</button>
                        <!--<button id="show" onclick="tampilkan();">Tampilkan</button>-->                        
		</td>
	</tr>
	</table>
        
        <div id="tampilkan" style="margin:40 15 0 15"></div>













