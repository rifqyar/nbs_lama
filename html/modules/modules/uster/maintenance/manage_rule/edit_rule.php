<?php
$db 	= getDB("storage"); 
$id_rule = $_POST["ID_RULE"];
$id_kategori = $_POST["ID_KATEGORI"];

$query_view = "SELECT RULE_MENU_.ID_MENU, MENU.NAME MENU, RULE_MENU_.ID_SUBMENU, NVL(SUB_MENU.NAME,'-') SUBMENU FROM RULE_MENU_ 
            INNER JOIN MENU ON RULE_MENU_.ID_MENU = MENU.ID LEFT JOIN SUB_MENU ON RULE_MENU_.ID_SUBMENU = SUB_MENU.ID
            WHERE RULE_MENU_.ID_ROLE = '$id_rule' AND RULE_MENU_.ID_KATEGORI='$id_kategori' ORDER BY RULE_MENU_.ID_MENU ASC";
	$res_view = $db->query($query_view);
	$view_rule = $res_view->getAll();
	
$re = $db->query("SELECT ROLE.NAMA_ROLE FROM ROLE WHERE ID = '$id_rule'");
$re_r = $re->fetchRow();

$re1 = $db->query("SELECT KATEGORI_MENU.NAME FROM KATEGORI_MENU WHERE ID = '$id_kategori'");
$re_r1 = $re1->fetchRow();

?>
<div id="rule">
<h3>Rule <font style="color:red"><?=$re_r["NAMA_ROLE"]; ?></font> | Kategori <font style="color:red"><?=$re_r1["NAME"];?></font> </h3>
<table class="grid-table" border='0' cellpadding="1" cellspacing="1" width="100%">
	  <tr style=" font-size:10pt">
		  <th width="2%" valign="top" class="grid-header"  style="font-size:8pt">MENU | <a onclick="add_kategori('<?=$id_rule;?>','<?=$id_kategori;?>');"><img src='images/plus.png' border='0' /></a> </th>
		  <th width="110" valign="top" class="grid-header"  style="font-size:8pt">SUB_MENU</th>
		  <th width="2%" valign="top" class="grid-header"  style="font-size:8pt"> </th>
	  </tr>
	  <?php $id_before = 0;	$id_kat_before = 0;?>
	  <?php foreach($view_rule as $rows){?>
	  <tr bgcolor="#f9f9f3" onMouseOver=this.style.backgroundColor="#BAD5FC" onMouseOut=this.style.backgroundColor="">
		  <?php if($rows["ID_MENU"] != $id_before){?>
		  
		  <td width="15%" align="center" valign="middle" class="grid-cell"   style="font-size:9pt"><b><?=$rows["MENU"];?></b></td>
		  <td width="20%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;"><?=$rows["SUBMENU"];?></td>
		  <td><a onclick="hapus_rule('<?=$id_rule;?>','<?=$id_kategori;?>','<?=$rows["ID_MENU"];?>','<?=$rows["ID_SUBMENU"];?>');"><img src='images/cont_red_delete.png' border='0' /></a></td>
			<?php } else { ?>		  		  
		  <td width="15%" align="center" valign="middle" class="grid-cell"   style="font-size:9pt"><b></b></td>
		  <td width="20%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;"><?=$rows["SUBMENU"];?></td>  
		  <td><a onclick="hapus_rule('<?=$id_rule;?>','<?=$id_kategori;?>','<?=$rows["ID_MENU"];?>','<?=$rows["ID_SUBMENU"];?>');"><img src='images/cont_red_delete.png' border='0' /></a></td>
		  <?php }
		  $id_before = $rows["ID_MENU"];
		  ?>
	  </tr>
	 <?php } ?>
</table>
<a onclick="back_to()" class="link-button">Back</a>
</div>