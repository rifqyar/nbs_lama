<?php if (!defined("XLITE_INCLUSION")) die(); ?><span class="graybrown"><img src='images/sp2_p.png' border='0' class="icon"/><font color="#0378C6"> Request</font> Delivery Ke TPK / REPO MUAT - SP2</span><br/><br/><fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; "><form id="searchForm" name="searchForm" action="{HOME}delivery.permintaan/search/" method="get"><div style="padding:10px;font-family:Arial; font-size:8pt; color:#555555; font-weight:bold"><table><tr><td>No Request</td><td> : </td><td><input type="text" name="no_req" id="no_req" /></td><td><tr><td>Periode Kegiatan </td><td> : </td><td><input type="text" name="from" id="from" /> s/d <input type="text" name="to" id="to" /></td><td> &nbsp;&nbsp; <a class="link-button" style="height:25" onclick="search_request()"><img src='images/cari.png' border='0' />Cari</a><br></table><div style="padding-left:20px"><font color="#0066CC"><a onclick="window.open('<?php echo($HOME); ?><?php echo($APPID); ?>/add','_self')" class="link-button" style="height:25" ><img src='images/sp2p.png' border="0"> Tambah Request Delivery</a></font></div></div></form></fieldset><fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; "><center><div id="request_list" style="margin: 5px 5px 5px 5px"></div></center></fieldset><script>
$('#request_list').html('<p align=center><img src=<?php echo($HOME); ?>images/loadingbox.gif /></p>');
$("#request_list").load('<?=HOME?><?=APPID?>/req_list #list', function(data) {        	  
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
	var url 	= "<?=HOME?><?=APPID?>/req_list #list";
	
	$('#request_list').html('<p align=center><img src=<?php echo($HOME); ?>images/loadingbox.gif /></p>');
	$("#request_list").load(url,{no_req : no_req_, from : from_, to : to_, cari : cari_}, function(data){
	
	});

}
function change_page($page){
	var selected_p = $page;
	$('#request_list').html('<p align=center><img src=<?php echo($HOME); ?>images/loadingbox.gif /></p>');
	$("#request_list").load('<?=HOME?><?=APPID?>/req_list?pp='+selected_p+' #list', function(data) {});
}
</script>