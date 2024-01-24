<script>

$(document).ready(function() 
{	
    $("#expired_stnk").datepicker({
			dateFormat: 'dd-mm-yy'
            });
	
});

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
		$("#nor").removeAttr('disabled');
	}	
	else if (type_=='TNK')
	{	
		$("#temp").removeAttr('disabled');
	}	
	else
	{

		$("#temp").attr('disabled','disabled');
		$("#nor").attr('disabled','disabled');
		$("#nor").val('T');
	}
	$("#temp").val('');
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
function calculate(b) {
    if (b.length > 9) {
        var a = {};
        a.A = 10;
        a.B = 12;
        a.C = 13;
        a.D = 14;
        a.E = 15;
        a.F = 16;
        a.G = 17;
        a.H = 18;
        a.I = 19;
        a.J = 20;
        a.K = 21;
        a.L = 23;
        a.M = 24;
        a.N = 25;
        a.O = 26;
        a.P = 27;
        a.Q = 28;
        a.R = 29;
        a.S = 30;
        a.T = 31;
        a.U = 32;
        a.V = 34;
        a.W = 35;
        a.X = 36;
        a.Y = 37;
        a.Z = 38;
        var c = [];
        c[0] = 1;
        c[1] = 2;
        c[2] = 4;
        c[3] = 8;
        c[4] = 16;
        c[5] = 32;
        c[6] = 64;
        c[7] = 128;
        c[8] = 256;
        c[9] = 512;
        var d = [],
            e = 0;
        for (lcv = 0; lcv < 4; lcv++) d[lcv] = a[b.charAt(lcv)];
        for (lcv = 4; lcv < 10; lcv++) d[lcv] = parseInt(b.charAt(lcv), 10);
        for (lcv = 0; lcv < 10; lcv++) {
            d[lcv] *= c[lcv];
            e += d[lcv]
        }
        De = e % 11;
        if (De == 10) De = 0;
        return De
    } else alert()
}

function validate_v2(input){
    var output = false;

    input = input.replace(/^\s*([\S\s]*?)\s*$/, "$1");
    if (/^[A-Z]{4}\d{6,7}/.test(input)) {
		var temp = {};
        checkDigit = calculate(input.replace("-", ""));
        output = 
        	input.length > 10 && input.length < 13 ? 
        		checkDigit == input.replace("-", "").charAt(10) ? 
        			true : 
    				false : 
    			input.length == 10 ? 
    				false : //'{' + input + ": " + checkDigit + "}" : 
				false;
    } else {
    	// output =
        // input.length == 0 ? "\n" : "\n" + input + " Bad";
        output = false;
    }
    console.log(output);
	//edited by ivan 1 januari 2014
	if ($('#bypass_valid').is(":checked"))
	{
		output = true;
	}
    return output;
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
		// if ( !validate(brokenstring[i]) ) msg = msg + "<p style='color:red;font-size:200%;'>" + brokenstring[i] + '</p>';
		if ( !validate_v2(brokenstring[i]) ) {
			msg = msg + "<p style='color:red;font-size:200%;'>" + brokenstring[i] + '</p>';
		}
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
		<td class="form-field-caption" align="right">Police Number</td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<input type="text" size="20" id="truck_number" name="truck_number"/>
		</td>
		<td></td>
		<td></td>
		<td>
			<button onclick="add_truck()">Add Truck</button>
		</td>
</tr>
<tr>
		<td class="form-field-caption" align="right">STNK Number</td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<input type="text" size="20" id="no_stnk" name="no_stnk"/>
		</td>
		<td class="form-field-caption" align="right">Expired STNK</td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<input type="text" size="20" id="expired_stnk" name="expired_stnk"/>
		</td>
</tr>
</table>