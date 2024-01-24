<fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; ">
	<left>
    <table class="form-input" style="margin: 30px 30px 30px 30px;" border="0">
    	<tr>
            
            <td><div style="padding:10px;font-family:Arial; font-size:10pt; color:#555555; font-weight:bold">NO. REQUEST </div></td>
            <td> : </td>
            <td> <input type="text" name="No_Req"/></td>
            
    	</tr>
    	<tr>
            <td><div style="padding:10px;font-family:Arial; font-size:10pt; color:#555555; font-weight:bold">TGL. REQUEST DELIVERY</div></td>
            <td> : </td>
            <td><input type="text" name="FROM" id="from"/> S/D <input type="text" name="TO" id="to"/> </td>
    	</tr>
        <tr>
        	<td colspan="3" align="right"><a id="searchButton" onclick="search_requet('searchForm')" class="link-button" style="height:25" ><img src='images/cari.png' border='0' /> Cari</a></td>
        </tr>
	</table>
    </left>
</fieldset>

<fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; ">
    <a onclick="window.open('{$HOME}{$APPID}/add','_self')" class="link-button" style="height:25" ><img src='images/sp2p.png' border="0"> Tambah Request Delivery</a>

</fieldset>
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
