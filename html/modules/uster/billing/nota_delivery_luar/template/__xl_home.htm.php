<?php if (!defined("XLITE_INCLUSION")) die(); ?><span class="graybrown"><img src='images/sp2_p.png' border='0' class="icon"/><font color="#0378C6"> Nota</font> Delivery Luar- SP2</span><br/><br/><fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; "><form id="searchForm" name="searchForm" action="{HOME}delivery.permintaan/search/" method="get"><div style="padding:10px;font-family:Arial; font-size:8pt; color:#555555; font-weight:bold"><table><tr><td >No Request</td><td> : </td><td><input type="text" name="no_req" id="no_req" /></td><td><tr><td>Periode Kegiatan </td><td> : </td><td><input type="text" name="from" id="from" /> s/d <input type="text" name="to" id="to" /></td><td></tr></table><a class="link-button" style="height:25" onclick="search_request()" ><img src='images/cari.png' border='0' />Cari</a></div></form></fieldset><fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; "><center><div id="nota_list" style="margin: 5px 5px 5px 5px"></div></center></fieldset><script>
$('#nota_list').html('<p align=center><img src=<?php echo($HOME); ?>images/loadingbox.gif /></p>');
$("#nota_list").load('<?=HOME?><?=APPID?>/nota_list #list', function(data) {        	  
	}); 
</script><script>
$(function() {	

	$( "#from" ).datepicker();
	$( "#from" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
        
        $( "#to" ).datepicker();
	$( "#to" ).datepicker( "option", "dateFormat", "yy-mm-dd" );

});

function search_request()
{
	var no_req_ 	= $("#no_req").val();
	var from_ 	= $("#from").val();	
	var cari_	= "cari"; 
	var to_         = $("#to").val();
	var url 	= "<?=HOME?><?=APPID?>/nota_list #list";
	 
	$('#nota_list').html('<p align=center><img src=<?php echo($HOME); ?>images/loadingbox.gif /></p>');
	$("#nota_list").load(url,{NO_REQ : no_req_,FROM : from_, TO : to_, CARI : cari_}, function(data){
	
	});

}
function change_page($page){
	var selected_p = $page;
	$('#nota_list').html('<p align=center><img src=<?php echo($HOME); ?>images/loadingbox.gif /></p>');
	$("#nota_list").load('<?=HOME?><?=APPID?>/nota_list?pp='+selected_p+' #list', function(data) {});
}

function recalc(req,nota){
    var url 	= "<?=HOME?><?=APPID?>.ajax/recalc";
    $.post(url,{REQ:req,NOTA:nota},function(data){
        alert("success");
    });
        
}
    
function recalc_pnkn(req,nota){
    var url 	= "<?=HOME?><?=APPID?>.ajax/recalc_pnkn";
    $.post(url,{REQ:req,NOTA:nota},function(data){
        alert("success");
    });
        
}
</script>