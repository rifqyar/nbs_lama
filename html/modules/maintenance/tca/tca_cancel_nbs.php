<style>
.content{
	width:95%;
	margin-left:auto;
	margin-right:auto;
	margin-bottom: 10px;
	margin-top:20px;
}
.main_side{
	width:100%;
	float:left;
	text-align:left;
}
.rightside{ 
	width:25%;
	float:right;
	text-align:center;
}
.ganjil {
  background-color: #FFF; /* Warna untuk baris ganjil */
}
.genap {
  background-color: #bbe3fe; /* Warna untuk baris genap */
}   

</style>
<script type="text/javascript">
$(function(){
   
    
    $( "#cont_number" ).autocomplete({
		minLength: 7,
		source: "<?=HOME?><?=APPID?>.auto/cont_number",
		focus: function( event, ui ) 
		{
			$( "#cont_number" ).val( ui.item.F_TCACN);
			return false;
		},
		select: function( event, ui ) 
		{
			$( "#cont_number" ).val( ui.item.F_TCACN);
            show_contlist(ui.item.F_TCACN);
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a align='center'>" + item.F_TCACN + "<br>" +item.TRUCK_NUMBER+" - "+ item.F_TCATN +"</a>")
		.appendTo( ul );
	
	};
    
});

function show_contlist($id_cont){
    var url  = "<?=HOME?><?=APPID?>.ajax/cont_detail";
    $("#container_detail").html("<p align=center><img src='<?=HOME?>images/loadingbox.gif' /></p>");
    $("#container_detail").load(url,{CONT_NO:$id_cont},function(data){        
        
        
    });
}

</script>

<div class="content">
	<div class="main_side">
	<p>
	<h2> <img src="<?=HOME?>images/delivery.png" height="5%" width="5%" style="vertical-align:middle"> Cancel Association</h2></p>
    <div style="text-align:center;margin-bottom:40px;margin-top:40px;">
        <label style="font-size:20pt;">No. Container : </label><input type="text" name="cont_number" id="cont_number" size="14"
            style="background-color:#FFF;text-align:center;height:30px;font-size:20pt;"   />
        <br/>
        <br/>
        <div id="container_detail" style="width:100%"> </div>
    </div>
    
	</div>
</div>