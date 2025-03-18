<?php
	$req=$_GET['id'];
	$ship=$_GET['ship'];
	$car=$_GET['car'];
	$book_ship_h=$_GET['book_ship_h'];
?>

<script>
$(document).ready(function() 
{
src2 = 'request.anne.auto/carrier_ves';
	
//======================================= autocomplete commodity==========================================//
	$( "#comm" ).autocomplete({
		minLength: 3,
		source: "request.anne.auto/commodity",
		focus: function( event, ui ) 
		{
			$( "#comm" ).val( ui.item.NM_COMMODITY);
			return false;
		},
		select: function( event, ui ) 
		{
			$( "#comm" ).val( ui.item.NM_COMMODITY);
			$( "#icomm" ).val( ui.item.KD_COMMODITY);

			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a align='center'>" + item.NM_COMMODITY + "<br>" +item.KD_COMMODITY+"</a>")
		.appendTo( ul );
	
	};
	
	$( "#carrier_d" ).autocomplete({
		minLength: 2,
		source: function(request, response) {
            $.ajax({
                url: src2,
                dataType: "json",
                data: {
                    term : request.term,
                    vessel : encodeURIComponent($("#vessel").val())
                },
                success: function(data) {
                    response(data);
                }
            });
        },
		focus: function( event, ui ) 
		{
			$( "#carrier_d" ).val( ui.item.LINE_OPERATOR);
			return false;
		},
		select: function( event, ui ) 
		{
			$( "#carrier_d" ).val( ui.item.LINE_OPERATOR);
			$( "#icar_d" ).val( ui.item.CODE);

			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a align='center'>" + item.LINE_OPERATOR + "<br>" +item.CODE+"</a>")
		.appendTo( ul );
	
	};
	//======================================= autocomplete commodity==========================================//

	$("#imo").attr('disabled','disabled');
	$("#temp").attr('disabled','disabled');
	$("#unnumber").attr('disabled','disabled');
});

/*updated by gandul 29/1/2014 beberapa fungsi validasi*/
/*validasi imo un_number karena hazard*/
function check_hz()
{
	var hz_ = $("#hc").val();
	//alert(hz_);
	if(hz_=='Y')
	{
		$("#imo").removeAttr('disabled');
		$("#unnumber").removeAttr('disabled');
	}
	else
	{
		$("#imo").attr('disabled','disabled');
		$("#unnumber").attr('disabled','disabled');
	}
}
/*validasi temperatur harus numeric*/
function check_rfr()
{
	var type_ = $("#tc").val();
	//alert(hz_);
	if(type_=='RFR')
	{	
		$("#temp").removeAttr('disabled');
	}	
	else if (type_=='TNK')
	{	
		$("#temp").removeAttr('disabled');
	}	
	else
	{
		$("#temp").attr('disabled','disabled');
	}
	cek_iso();
}

function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 43 || charCode > 57))
        return false;
    return true;
}

/*validasi container number*/
function validate( nn )
{
    return true;
	//remarks by ivan
         var mapping = "A10*B12*C13*D14*E15*F16*G17*H18*I19*J20*K21*L23*M24*N25*O26*P27*Q28*R29*S30*T31*U32*V34*W35*X36*Y37*Z38";
 
         var chknumber = nn.substring(0,10);
	 var chknouprcase = chknumber.toUpperCase();
	 var chknolength = chknumber.length;

		 if (chknolength != 10)
           {
           return false;
           }
         else
             {
              chkdigit = new Array (10);

	      for (var a = 0; a <= 3; a++)
                 {
		  var characters = chknouprcase.charAt(a);
		  var charpos = mapping.indexOf(characters);
		  chkdigit[a] = mapping.substring(charpos+1, charpos+3);
		 }
 
	      for (var b = 4; b < 10; b++)
		 {
		  chkdigit[b] = chknouprcase.charAt(b);
		 }
 
	      var maptotal = 0;
 
	      for (var c = 0; c < 10; c++)
	         {
	          var total1 = chkdigit[c] * Math.pow(2,c);
	          var maptotal = maptotal + total1;
	         }
 
  	      if (maptotal)
	        {
 	         var quotient = maptotal / 11;
	         var integers = Math.floor(quotient);
	         var decimals = quotient - integers;
			 var finalchkdigit = Math.round(decimals*11);
			 if(finalchkdigit==10)
			   {
			    //alert('It is recomended not to use this number since the check digit is 10');
				finalchkdigit = 0;
				}

	          if (finalchkdigit == nn.substring(10,11) ) { return true; } else { return false;}
              }
              else
                  {
                   return false;
                  }
	     }
}

$('#nc').keyup(function() {
    $(this).val($(this).val().toUpperCase());
});

function massvalidate()
{
	document.getElementById('badnumbers').innerHTML = '';

	var re = new RegExp("[ ,;\n]", "g");
	var cnumbers = $('#nc').val().replace(re, ",");
	
	cnumbers = cnumbers.toUpperCase();
	var brokenstring=cnumbers.split(",");
	var msg='';
	for (i=0; i<brokenstring.length; i++ )
	{
		if ( !validate(brokenstring[i]) ) msg = msg + "<p style='color:red;font-size:200%;'>" + brokenstring[i] + '</p>';
	}
	if ( msg.length>0 )
	{
		document.getElementById('badnumbers').innerHTML = msg+"<p align='left' style='color:red;'>CONTAINER INVALID</p>";
		$('#rsct').val('0');
	} else {
		document.getElementById('badnumbers').innerHTML = "<p align='left' style='font-weight:bold;font-size:200%;'>CONTAINER "+cnumbers+" VALID</p>";
		$('#rsct').val('1');
	}
}
/*updated by gandul 29/1/2014 beberapa fungsi validasi*/
</script>

<table>
<tr>
		<td class="form-field-caption" align="right">No. Container</td>
		<td class="form-field-caption" align="right">:</td>
		<td colspan="6" valign="middle">
		<input type="text" size="11" id="nc" onblur="cek_iso()" onkeyup="massvalidate()" name="nc" style="background-color:#FFFFCC; font-weight:bold;font-size:24px;text-align:center"/> 
		<button onclick="add_cont1('<?=$req;?>','<?=$ship;?>','<?=$car;?>','<?=$book_ship_h;?>')"><img src="<?=HOME;?>images/add_ct.png"/></button>
		</td>
		<td align="left" colspan="7"><div id='badnumbers'></div><input type="hidden" id="rsct" name="rsct" /></td>
</tr>
<tr>
		<td class="form-field-caption" align="right">Size </td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<select id="sc" onchange="cek_iso()">
			<option value="20">20</option>
			<option value="40">40</option>
			<option value="45">45</option>
			</select>
		</td>
		<td class="form-field-caption" align="right">Type </td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<select id="tc" onchange="check_rfr()">
			<option value="DRY">DRY</option>
			<option value="HQ">HQ</option>
			<option value="OT">OT</option>
			<option value="TNK">TNK</option>
			<option value="OVD">OVD</option>
			<option value="FLT">FLT</option>
			<option value="RFR">RFR</option>
			</select>
		</td>
		<td class="form-field-caption" align="right">Status </td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<select id="stc">
			<option value="FCL">Full</option>
			<option value="MTY">Empty</option>
			</select>
		</td>
		<td class="form-field-caption" align="right">Height </td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<select id="hgc" onchange="cek_iso()">
			<option value="8.6">8,6</option>
			<option value="9.6">9,6</option>
			<option value="OOG">OOG</option>
			</select>
		</td>
		
	</tr>
	<tr>
		<td class="form-field-caption" align="right">Hazzard - IMO</td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<select id="hc" onchange="check_hz()">
			<option value="T">T</option>
			<option value="Y">Y</option>
			</select> <input type="text" size="4" id="imo" name="imo"/>
		</td>
		<td class="form-field-caption" align="right">UN Number</td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<input type="text" size="4" id="unnumber" name="unnumber"/>
		</td>
	</tr>
	<tr>
		<td class="form-field-caption" align="right">Temp </td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<input type="text" size="15" id="temp" name="temp" onkeypress="return isNumberKey(event)"/>
		</td>
		<td class="form-field-caption" align="right">OW </td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<input type="text" size="5" id="ow" name="ow"/>
		</td>
		<td class="form-field-caption" align="right">OH </td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<input type="text" size="5" id="oh" name="oh"/>
		</td>
		<td class="form-field-caption" align="right">OL </td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<input type="text" size="5" id="ol" name="ol"/>
		</td>
	</tr>
	<tr>
		<td class="form-field-caption" align="right">Commodity </td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<input type="text" size="15" id="comm" name="comm"/><input type="hidden" size="15" id="icomm"/>
		</td>
		<td class="form-field-caption" align="right">Carrier </td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<input type="text" size="10" id="carrier_d" name="carrier_d"/><input type="hidden" size="15" id="icarrier_d"/>
		</td>
		<td>
			<input type="text" size="4" id="icar_d" readonly="readonly"/>
		</td>
	</tr>
    
		<TR>
		<td class="form-field-caption" align="right">ISO Code </td>
		<td class="form-field-caption" align="right">:</td>
		<td colspan="13">
			<input type="text" size="15" id="iso" name="iso"/>
		</td>
	</tr>
	 
	</table>