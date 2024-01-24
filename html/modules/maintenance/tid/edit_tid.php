<?php
	$db = getDB();
	$tid = $_GET[tid];
	$query = "SELECT TID,TRUCK_NUMBER,REGISTRANT_NAME,REGISTRANT_PHONE,COMPANY_NAME,COMPANY_ADDRESS,COMPANY_PHONE,EMAIL,KIU,TO_CHAR(EXPIRED_KIU,'dd-mm-yyyy') EXPIRED_KIU,NO_STNK,TO_CHAR(EXPIRED_STNK,'dd-mm-yyyy') EXPIRED_STNK,TO_CHAR(EXPIRED_DATE,'dd-mm-yyyy') EXPIRED_DATE,USER_ENTRY,TO_CHAR(DATE_ENTRY,'dd-mm-yyyy') DATE_ENTRY FROM TID_REPO WHERE TID='$tid'";
	$hasil=$db->query($query);
	$hasil_=$hasil->fetchRow();
	
?>

<style>
.content{
	width:95%;
	margin-left:auto;
	margin-right:auto;
	margin-bottom: 10px;
	margin-top:20px;

}
button{
	border-radius: 4px;
    border: 1px solid #d0d0d0;
	}
button:hover {
	background: #80dffb;
	box-shadow: 0 2px 4px rgba(0,0,0,0.5), inset 0 1px rgba(255,255,255,0.3), inset 0 10px rgba(255,255,255,0.2), inset 0 10px 20px rgba(255,255,255,0.25), inset 0 -15px 30px rgba(0,0,0,0.3);

	-o-box-shadow: 0 2px 4px rgba(0,0,0,0.5), inset 0 1px rgba(255,255,255,0.3), inset 0 10px rgba(255,255,255,0.2), inset 0 10px 20px rgba(255,255,255,0.25), inset 0 -15px 30px rgba(0,0,0,0.3);

	-webkit-box-shadow: 0 2px 4px rgba(0,0,0,0.5), inset 0 1px rgba(255,255,255,0.3), inset 0 10px rgba(255,255,255,0.2), inset 0 10px 20px rgba(255,255,255,0.25), inset 0 -15px 30px rgba(0,0,0,0.3);
	-moz-box-shadow: 0 2px 4px rgba(0,0,0,0.5), inset 0 1px rgba(255,255,255,0.3), inset 0 10px rgba(255,255,255,0.2), inset 0 10px 20px rgba(255,255,255,0.25), inset 0 -15px 30px rgba(0,0,0,0.3);

	
}

</style>
<script type='text/javascript'>
//var neg1;
var neg2;

function onfoucusshiftref(){
	var etd_v = $("#tgl_muat").val();
	$("#end_shift").val(etd_v);
}

function edit_tid(){
	var tid=$( "#tid" ).val();
	var registrant_name=$( "#registrant_name" ).val();
	var registrant_phone=$( "#registrant_phone" ).val();
	var company_name=$( "#company_name" ).val();
	var company_phone=$( "#company_phone" ).val();
	var company_address=$( "#company_address" ).val();
	var email=$( "#email" ).val();
	var truck_number=$( "#truck_number" ).val();
	var no_stnk=$( "#no_stnk" ).val();
	var expired_stnk=$( "#expired_stnk" ).val();
	var kiu=$( "#kiu" ).val();
	var expired_kiu=$( "#expired_kiu" ).val();
	
	if( registrant_name=='')
	{
		alert('Entry Registrant Name please,..');
		return false;
	}
	else if( registrant_phone=='')
	{
		alert('Entry Registrant Phone please,..');
		return false;
	}
	else if( company_name=='')
	{
		alert('Entry Company Name please,..');
		return false;
	}
	else if( company_phone=='')
	{
		alert('Entry Company Phone please,..');
		return false;
	}
	else if( company_address=='')
	{
		alert('Entry Company Address please,..');
		return false;
	}
	else if( email=='')
	{
		alert('Entry Email please,..');
		return false;
	}
	else if(truck_number=='')
	{
		alert('Entry valid container number please');
		return false;
	}
	else
	{
		var url="<?=HOME;?>maintenance.tid.ajax/update_tid";;
		$.post(url,{TID : tid,
			REGISTRANT_NAME : registrant_name,
			REGISTRANT_PHONE :registrant_phone,
			COMPANY_NAME : company_name,
			COMPANY_PHONE : company_phone,
			COMPANY_ADDRESS : company_address,
			EMAIL : email,
			KIU : kiu,
			EXPIRED_KIU : expired_kiu,
			TRUCK_NUMBER : truck_number,
			NO_STNK : no_stnk,
			EXPIRED_STNK : expired_stnk},function(data){	
			alert(data);
			window.location="<?=HOME?>maintenance.tid/";
		});
	}
}

function create_tid()
{
	var registrant_name=$( "#registrant_name" ).val();
	var registrant_phone=$( "#registrant_phone" ).val();
	var company_name=$( "#company_name" ).val();
	var company_phone=$( "#company_phone" ).val();
	var company_address=$( "#company_address" ).val();
	var email=$( "#email" ).val();
	var truck_number=$( "#truck_number" ).val();
	var no_stnk=$( "#no_stnk" ).val();
	var expired_stnk=$( "#expired_stnk" ).val();
	var kiu=$( "#kiu" ).val();
	var expired_kiu=$( "#expired_kiu" ).val();
	
	if( registrant_name=='')
	{
		alert('Entry Registrant Name please,..');
		return false;
	}
	else if( registrant_phone=='')
	{
		alert('Entry Registrant Phone please,..');
		return false;
	}
	else if( company_name=='')
	{
		alert('Entry Company Name please,..');
		return false;
	}
	else if( company_phone=='')
	{
		alert('Entry Company Phone please,..');
		return false;
	}
	else if( company_address=='')
	{
		alert('Entry Company Address please,..');
		return false;
	}
	else if( email=='')
	{
		alert('Entry Email please,..');
		return false;
	}
	else
	{
		document.getElementById("registrant_name").readOnly = true;
		document.getElementById("registrant_phone").readOnly = true;
		document.getElementById("company_name").readOnly = true;
		document.getElementById("company_phone").readOnly = true;
		document.getElementById("company_address").readOnly = true;
		document.getElementById("email").readOnly = true;
		document.getElementById("kiu").readOnly = true;
		document.getElementById("expired_kiu").readOnly = true;
		document.getElementById('but_create').style.display = "none";
		$('#detail_req').load("<?=HOME?>maintenance.tid.ajax/detail_req");
		$('#detail_container').load("<?=HOME?>maintenance.tid.ajax/list_truck?registrant_name="+encodeURI(registrant_name)+"&registrant_phone="+encodeURI(registrant_phone)+"&company_name="+encodeURI(company_name)+"&company_phone="+encodeURI(company_phone)+"&company_address="+encodeURI(company_address)+"&email="+encodeURI(email));
	}
	
}

function add_truck()
{
	var registrant_name=$( "#registrant_name" ).val();
	var registrant_phone=$( "#registrant_phone" ).val();
	var company_name=$( "#company_name" ).val();
	var company_phone=$( "#company_phone" ).val();
	var company_address=$( "#company_address" ).val();
	var email=$( "#email" ).val();
	var kiu=$( "#kiu" ).val();
	var expired_kiu=$( "#expired_kiu" ).val();
	var truck_number=$( "#truck_number" ).val();
	var no_stnk=$( "#no_stnk" ).val();
	var expired_stnk=$( "#expired_stnk" ).val();
	
	var url="<?=HOME;?>maintenance.tid.ajax/add_detail_req";
	
	if(truck_number=='')
	{
		alert('Entry valid container number please');
		return false;
	}
	else 
	{
		$.post(url,{REGISTRANT_NAME : registrant_name,
			REGISTRANT_PHONE :registrant_phone,
			COMPANY_NAME : company_name,
			COMPANY_PHONE : company_phone,
			COMPANY_ADDRESS : company_address,
			EMAIL : email,
			KIU : kiu,
			EXPIRED_KIU : expired_kiu,
			TRUCK_NUMBER : truck_number,
			NO_STNK : no_stnk,
			EXPIRED_STNK : expired_stnk},function(data){	
			alert(data);
			$('#detail_container').load("<?=HOME?>maintenance.tid.ajax/list_truck?registrant_name="+encodeURI(registrant_name)+"&registrant_phone="+encodeURI(registrant_phone)+"&company_name="+encodeURI(company_name)+"&company_phone="+encodeURI(company_phone)+"&company_address="+encodeURI(company_address)+"&email="+encodeURI(email));$( "#truck_number" ).val('');
			$( "#no_stnk" ).val('');
			$( "#expired_stnk" ).val('');
		});
		
			
	
	}
	
	//alert(nc);
}

$(document).ready(function() 
{	
    $("#expired_kiu").datepicker({
			dateFormat: 'dd-mm-yy'
            });
			
	$("#expired_stnk").datepicker({
			dateFormat: 'dd-mm-yy'
            });
	
});

function cek_iso()
{
	var sc=$('#sc').val();
	var tc=$('#tc').val();
	if(tc=='HQ'){
		$('#hgc').val('9.6');
	}
	/*else
	{
		$('#hgc').val('8.6');
	}*/
	
	var hgc=$('#hgc').val();
	var url="<?=HOME;?>request.anne.ajax/cek_cont_req";
	$.post(url,{SC:sc, TC:tc,HGC:hgc},function(data){	
		//alert(data);
		$('#iso').val(data);
	});
}

function update_req()
{
	window.location="<?=HOME?>maintenance.tid/";
}

function del(a,b,c,d,e)
{
	var url="<?=HOME;?>request.anne.ajax/del_cont";
	$.post(url,{NO_CONT:b, NO_REQ:a, VESSEL:c, VOYAGE:d, OPERATORID:e},function(data){	
		alert(data);
		$('#detail_container').load("<?=HOME?>maintenance.tid.ajax/list_truck?id="+a);
	});
}

</script>

<div class="content">
	<p>
	<img src="<?=HOME?>images/delivery.png" height="5%" width="5%" style="vertical-align:middle"> <b> <font color='#69b3e2' size='4px'>Edit</font> </b>
	 <font color='#888b8d' size='4px'>
	 TID
	 </font>
	
	<p><br/>
	  </p>
	
	<hr width="870" color="#e1e0de"></hr><p><br/></p>
	
	<table>
	<tr>
		<td class="form-field-caption" align="right">TID</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="35" name="tid" id="tid"  value="<?=$hasil_[TID]?>"/>
		</td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td class="form-field-caption" align="right">Registrant Name</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="35" name="registrant_name" id="registrant_name"  value="<?=$hasil_[REGISTRANT_NAME]?>"/>
		</td>
		<td></td>
		<td class="form-field-caption" align="right">Registrant Phone</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="35" name="registrant_phone" id="registrant_phone" value="<?=$hasil_[REGISTRANT_PHONE]?>"/>
		</td>
	</tr>
	<tr>
		<td class="form-field-caption" align="right">Company Name</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="35" name="company_name" id="company_name" value="<?=$hasil_[COMPANY_NAME]?>"/>
		</td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">Company Phone</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="35" name="company_phone" id="company_phone" value="<?=$hasil_[COMPANY_PHONE]?>"/>
		</td>
	</tr>
	<tr>
		<td class="form-field-caption" align="right">Company Address</td>
		<td class="form-field-caption" align="right">:</td>
		<td><textarea id="company_address" name="company_address" cols="30" style="background-color:#FFFFFF;" rows="2" ><?=$hasil_[COMPANY_ADDRESS]?></textarea>
		</td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">Email</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="35" name="email" id="email" value="<?=$hasil_[EMAIL]?>"/>
		</td>
    </tr>
	<tr>
		
		<td class="form-field-caption" align="right">KIU</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="35" name="kiu" id="kiu" value="<?=$hasil_[KIU]?>"/>
		</td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">Expired KIU</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="35" name="expired_kiu" id="expired_kiu" value="<?=$hasil_[EXPIRED_KIU]?>"/>
		</td>
	</tr>
	<tr>
		<td class="form-field-caption" align="right">Police Number</td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<input type="text" size="20" id="truck_number" name="truck_number" value="<?=$hasil_[TRUCK_NUMBER]?>"/>
		</td>
		<td></td>
		<td></td>
		<td></td>
</tr>
<tr>
		<td class="form-field-caption" align="right">STNK Number</td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<input type="text" size="20" id="no_stnk" name="no_stnk" value="<?=$hasil_[NO_STNK]?>"/>
		</td>
		<td class="form-field-caption" align="right">Expired STNK</td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<input type="text" size="20" id="expired_stnk" name="expired_stnk" value="<?=$hasil_[EXPIRED_STNK]?>"/>
		</td>
</tr>

	 <tr>
		
		
     </tr>
	 
	 <tr>
		<td class="form-field-caption" align="right"></td>
		<td class="form-field-caption" align="right"></td>
		<td></td>
		<td>&nbsp;</td>
		
     </tr>
	 <tr>
		<td colspan="7"><button onclick="edit_tid()" id="but_create">Save</button></td>
     </tr>
	</table>
	</form>
	<br>
	<hr width="870" color="#e1e0de"></hr>
	<br>
	<div>
		<div id="detail_req"></div>
		<br>
		<div id="detail_container"></div>
	</div>
	<br/>
	<br/>
	
</div>

