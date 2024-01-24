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


	 $('#list input').on('change', function() {
            var radioval = $('input[name="terminal"]:checked', '#list').val();
    
   
    $( "#trx_number" ).autocomplete({
		minLength: 7,
		 source: function(request, response) {
		    $.getJSON("<?=HOME?><?=APPID?>.auto/invoice_number", { terminal : radioval, term : $("#trx_number").val() }, 
		              response);
		  },
		focus: function( event, ui ) 
		{
			$( "#trx_number" ).val( ui.item.NO_NOTA);
			return false;
		},
		select: function( event, ui ) 
		{
			$( "#trx_number" ).val( ui.item.NO_NOTA);
            show_contlist(ui.item.NO_REQUEST);
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a align='center'>" + item.NO_NOTA + "<br>" +item.NO_REQUEST+" <br>"+ item.EMKL +"</a>")
		.appendTo( ul );
	
	};
	 });
    
});

function show_contlist($id_req){
    var url  = "<?=HOME?><?=APPID?>.ajax/cont_list";
    $("#container_list").html("<p align=center><img src='<?=HOME?>images/loadingbox.gif' /></p>");
    $("#container_list").load(url,{NO_REQ:$id_req},function(data){        
        
        
    });
}

</script>

<div class="content">
	<div class="main_side">
	<p>
	<h1> <img src="<?=HOME?>images/tca_icon.png" height="10%" width="10%" style="vertical-align:middle"> TID & Container Association</h1></p>
    <div style="text-align:center;margin-bottom:40px;margin-top:40px;">
        <label style="font-size:15pt;">No. Invoice / No. Request : </label> <br/>
        <div id="list">
        	<input type="radio" name="terminal" id="terminal" value="tpk"  /> TPK &nbsp;&nbsp;&nbsp; 
        	<input type="radio" name="terminal" id="terminal" value="uster"  /> USTER <br/>
		</div>
        <input type="text" name="trx_number" id="trx_number" size="20"
            style="background-color:#FFF;text-align:center;height:30px;font-size:20pt;"   />
        <br/>
        <br/>
        <div id="container_list" style="width:100%"> </div>
    </div>
    
	</div>
</div>