<?php 
	$db = getDB("storage");
	$no_cont = $_POST["NO_CONT"];
	$q = $db->query("SELECT * FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'");
	$r = $q->fetchRow();
?>
<div id="cont">
	<table class="form-input" style="margin: 30px 30px 30px 30px;"  >
    	<tr>
            <td  class="form-field-caption"> Nomor Container </td>
            <td> : </td>
            <td> <input type="text" ID="NO_CONT" value = "<?=$no_cont?>" readonly /> </td>
        </tr>
        <tr>   
            <td  class="form-field-caption"> Ukuran </td>
            <td> : </td>
            <td> <select id="SIZE">                
            	<option value="20">20</option>
            	<option value="40">40</option>
            	</select> 
            </td>
        </tr>
        <tr>
            <td  class="form-field-caption"> TIPE </td>
            <td> : </td>
            <td> <select id="TYPE" style="width:80px">
				<option value="DRY">DRY</option>
				<option value="FLT">FLT</option>
				<option value="HQ">HQ</option>
				<option value="OT">OT</option>
				<option value="OVD">OVD</option>
				<option value="RFR">RFR</option>
				<option value="TNK">TNK</option>
			</select>
            </td>
        </tr>
        <tr>   
            <td  class="form-field-caption"> Location </td>
            <td> : </td>
            <td> <select id="LOCATION">
                <option value="GATO">GATO</option>
                <option value="IN_YARD">IN_YARD</option>
                <option value="GATI">GATI</option>
                </select> 
            </td>
        </tr>
	</table>
</div>
