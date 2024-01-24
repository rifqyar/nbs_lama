--<?php
	$idrpstv=$_GET['id'];
	$db=getDb();
	$query="select cust_name, cust_no, cust_addr, cust_tax_no from bil_rpstv_h where id_rpstv='$idrpstv'";
	$qry=$db->query($query);
	$res=$qry->fetchRow();
	
?>
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
</style>
<script>
$(document).ready(function() 
{	
	//======================================= autocomplete EMKL==========================================//
	$( "#custnm" ).autocomplete({
		minLength: 3,
		source: "request.anne.auto/emkl",
		focus: function( event, ui ) 
		{
			$( "#custnm" ).val( ui.item.NAMA_PERUSAHAAN);
			return false;
		},
		select: function( event, ui ) 
		{
			$( "#custnm" ).val( ui.item.NAMA_PERUSAHAAN);
			$( "#custno" ).val( ui.item.KD_PELANGGAN);
			$( "#custadd" ).val( ui.item.ALAMAT_PERUSAHAAN);
			$( "#custtax" ).val( ui.item.NO_NPWP);
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a align='center'>" + item.NAMA_PERUSAHAAN + "<br>" +item.ALAMAT_PERUSAHAAN+"</a>")
		.appendTo( ul );
	
	};
	//======================================= autocomplete EMKL==========================================//
	$("#onld").load("<?=HOME;?>billing.bm.ajax/prv?id="+<?=$idrpstv;?>);
});

function sub_cust()
{
	var custno=$("#custno").val();
	var custadd=$("#custadd").val();
	var custnm=$("#custnm").val();
	var custtax=$("#custtax").val();
	var url="<?=HOME;?>billing.bm.ajax/upd_cust";
	if(custno == ""){
		alert("Customer Harus Diisi");
	}
	else {
		$.post(url,{CUSTNO:custno,CUSTNM:custnm,CUSTADD:custadd,CUSTTAX:custtax, IDRPSTV:<?=$idrpstv;?>},
			function (data){
				alert(data);
				$("#onld").load("<?=HOME;?>billing.bm.ajax/prv?id="+<?=$idrpstv;?>);
			}
		);	
	}
	
}

function save_n(){	
var pelanggan=$("#custnm").val();
	var pelanggan='';
    $.post("<?=HOME?>billing.bm.ajax/cek_cust",{IDRPSTV:<?=$idrpstv;?>},function(data){
        pelanggan=data;
        if(pelanggan == 'NO'){
        alert ("Customer Harap Diinput Terlebih Dahulu Di bagian atas halaman ini");
        $("#custnm").focus();
        $("#custnm").attr("style","border:1px solid red");
        return false;
        } else if(pelanggan == 'NPWP'){
            alert ("NPWP belum terdaftar. Silahkan hubungi Customer Service.");
            return false;
        }
        else{
               var url="<?=HOME;?>billing.bm.ajax/save_fix?id="+<?=$idrpstv;?>;
               $.post(
              url, function (data)
              {
                alert(data);
                window.open("<?=HOME?>billing.bm.cetak/cetak_nota?id=<?=$idrpstv;?>",'_self');
              }
              );
        }
    });
	
}
</script>
<div class="content">
	<table>
		<tr>
			
			<td valign="center"><img src="images/step1.png" width='20' /></td>
			<td>&nbsp;<b>Entry Customer Please</b></td>
		</tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr>
			
			<td colspan="2">Customer Name</td>
			<td>: <input type="text" size='40' value="<?=$res[CUST_NAME];?>" id='custnm'/> <input type="text" size='5' value="<?=$res[CUST_NO];?>" id='custno'/> 
			<button onclick='sub_cust()' title='Update Customer'><img src="images/hhdd.png" width="20"></button></td>
		</tr>
		<tr>
			
			<td colspan="2">Customer Address</td>
			<td>: <input type="text" size='100' value="<?=$res[CUST_ADDR];?>" id='custadd'/> <input type="hidden" value="<?=$res[CUST_TAX_NO];?>" size='20' id='custtax'/></td>
		</tr>
	</table>
</div>
<div id="onld"></div>
 
 <fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; height:100px; text-align:center; vertical-align:middle" id="status_lunas">
<button onclick="save_n()"> <font size='5'> &nbsp SAVE NOTA &nbsp</font></font></button>

 </fieldset>
 
 