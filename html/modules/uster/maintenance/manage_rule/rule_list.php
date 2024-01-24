<?php
$db 	= getDB("storage"); 
$query_view = "select distinct rule_menu_.id_role, ROLE.NAMA_ROLE, rule_menu_.id_kategori, KATEGORI_MENU.NAME kategori
				from rule_menu_ left join role on rule_menu_.id_role =  ROLE.ID
				left join kategori_menu on rule_menu_.id_kategori = KATEGORI_MENU.ID
				order by rule_menu_.id_role asc, rule_menu_.id_kategori asc";
	$res_view = $db->query($query_view);
	$view_rule = 	$res_view->getAll();
?>
<div id="rule">
    <table class="grid-table" border='0' cellpadding="1" cellspacing="1" width="100%">
	  <tr style=" font-size:10pt">
		  <th width="2%" valign="top" class="grid-header"  style="font-size:8pt">RULE USER</th>
		  <th width="110" valign="top" class="grid-header"  style="font-size:8pt">KATEGORI MENU</th>
		  <th width="110" valign="top" class="grid-header"  style="font-size:8pt">ACTION</th>
	  </tr>
	  <?php $id_before = 0;	$id_kat_before = 0;?>
	  <?php foreach($view_rule as $rows){?>
	  <tr bgcolor="#f9f9f3" onMouseOver=this.style.backgroundColor="#BAD5FC" onMouseOut=this.style.backgroundColor="">
		  <?php if($rows["ID_ROLE"] != $id_before){?>
		  <td width="15%" align="center" valign="middle" class="grid-cell"   style="font-size:9pt"><b><?=$rows["NAMA_ROLE"];?></b></td>
		   <td width="20%" align="left" valign="middle" class="grid-cell"  style="font-size:9pt;"><?=$rows["KATEGORI"];?></td>
		   <td width="20%" align="left" valign="middle" class="grid-cell"  style="font-size:9pt;">
		   <a onclick="ubah_rule('<?=$rows["ID_ROLE"];?>','<?=$rows["ID_KATEGORI"];?>');"><img src='images/cont_edit.gif' border='0' />&nbsp;Ubah Rule</a></td>
			<?php } else { ?>		  
		  <td width="15%" align="center" valign="middle" class="grid-cell"   style="font-size:9pt"><b></b></td>
		  <td width="20%" align="left" valign="middle" class="grid-cell"  style="font-size:9pt;"><?=$rows["KATEGORI"];?></td>  
		  <td width="20%" align="left" valign="middle" class="grid-cell"  style="font-size:9pt;">
		   <a onclick="ubah_rule('<?=$rows["ID_ROLE"];?>','<?=$rows["ID_KATEGORI"];?>');"><img src='images/cont_edit.gif' border='0' />&nbsp;Ubah Rule</a></td>
		  <?php }
		  $id_before = $rows["ID_ROLE"];
		  ?>
	  </tr>
	 <?php } ?>
</table>
</div>