<?php if (!defined("XLITE_INCLUSION")) die(); ?><div id="list"><table width="100%"><thead><?php	if ($multipage): ?><tr><td align="center" style="padding-bottom:4px;" colspan="7" ><?php	if ($prevvisible): ?><a onclick="change_page(parseFloat($('#navsel_').val())-1)" style="cursor:pointer;">< Sebelumnya</a>   <?php	endif; ?><select id="navsel_" name="p" onchange="change_page($('#navsel_').val())" style="font-size:10px; border: solid 1px #333333; background-color:#FFFDCA;"><?php $this->renderSelectOptions(array (
),$navpage,$navlist,'0','1'); ?></select><?php	if ($nextvisible): ?>   <a onclick="change_page(parseFloat($('#navsel_').val())+1)" style="cursor:pointer;">Selanjutnya ></a><?php	endif; ?></td></tr><?php	endif; ?><tr style=" font-size:10pt"><th width="2%" valign="top" class="grid-header"  style="font-size:8pt">No </th><th width="200" valign="top" class="grid-header"  style="font-size:8pt">No. Request </th><th width="100" valign="top" class="grid-header"  style="font-size:8pt">Tgl. Request</th><th  width="200" valign="top" class="grid-header"  style="font-size:8pt">Emkl</th><th  width="200" valign="top" class="grid-header"  style="font-size:8pt">Vessel / Voyage</th><th width="200" valign="top" class="grid-header"  style="font-size:8pt">Jml Cont</th><th width="50" valign="top" class="grid-header"  style="font-size:8pt">Action</th></tr><?php $__ctr=0;
foreach( $row_list as $rows ): 
$__ctr++; ?><tr bgcolor="#f9f9f3" onMouseOver=this.style.backgroundColor="#BAD5FC" onMouseOut=this.style.backgroundColor=""><td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?php echo($rows["__no"]); ?> </td><td width="25%" align="left" valign="middle" class="grid-cell"   ><b><font color="#0066CC" style="font-size:9pt"> NO REQUEST : </b></font><font color="black" style="font-size:10pt"><b> <?php echo($rows["NO_REQUEST"]); ?></b></font><br><font color="#0066CC" style="font-size:10pt"> NO NOTA : </font><font color="red" style="font-size:10pt"> <?php echo($rows["NO_NOTA"]); ?></font></td><td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><font color="#0066CC" style="font-size:9pt"></font><?php echo($rows["TGL_REQUEST"]); ?> </td><td width="20%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:9pt"><?php echo($rows["NAMA_EMKL"]); ?></font></td><td width="20%" align="left" valign="middle" class="grid-cell"  style="font-size:9pt"><font color="#0066CC" style="font-size:9pt"><?php echo($rows["NAMA_VESSEL"]); ?>, <?php echo($rows["VOYAGE"]); ?> </font></td><td width="20%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><font color="#0066CC" style="font-size:9pt"><b><?php echo($rows["JML_CONT"]); ?></font> BOX </b></td><td align="center" class="grid-cell" onclick="reload_page()" style="color:#000; font-family:Arial; font-size:9pt"><?php 
                    	cek_nota($rows["NO_REQUEST"]);
                    ?></td></tr><?php endforeach; ?></tbody></table></div>